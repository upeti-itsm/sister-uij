<?php

namespace App\Http\Controllers\MahasiswaPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Akademik\KHS;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class KHSController extends Controller
{
    /**
     * Halaman utama KHS
     */
    public function index()
    {
        $menu = "Hasil Studi Mahasiswa";
        return view('mahasiswa_page.akademik.khs.index', compact('menu'));
    }

    /**
     * Get data KHS untuk DataTable (Server-side)
     */
    public function json(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'draw' => intval($request->draw ?? 1),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'Session user tidak ditemukan'
                ], 401);
            }

            $nim = $user->nim;
            $tahun_akademik = $request->tahun_akademik ?? '';
            $semester = $request->semester ?? '';
            $search = $request->search['value'] ?? $request->search ?? '';
            $start = $request->start ?? 0;
            $length = $request->length ?? 25;

            // Get data dari model
            $data_ = KHS::get_daftar_nilai($nim, $tahun_akademik, $semester, $search, $start, $length);

            $data = [
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => $data_,
                'error' => null
            ];

            if (count($data_) > 0) {
                $data['recordsTotal'] = $data_[0]->jml_record ?? count($data_);
                $data['recordsFiltered'] = $data['recordsTotal'];

                // Hitung statistik untuk response
                $statistik = $this->hitungStatistik($data_);
                $data['statistik'] = $statistik;
            }

            return response()->json($data, 200);

        } catch (\Exception $e) {
            return response()->json([
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get daftar tahun akademik yang pernah diambil mahasiswa
     */
    public function getTahunAkademikList(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'status' => '0',
                    'keterangan' => 'Session user tidak ditemukan'
                ], 401);
            }

            $nim = $user->nim;
            $data = KHS::get_tahun_akademik_list($nim);

            // Transform data ke format yang dibutuhkan
            $result = [];
            foreach ($data as $item) {
                $parsed = $this->parseTahunAkademik($item->tahun_akademik);
                $result[] = [
                    'id' => $item->tahun_akademik,
                    'nama' => $parsed['nama'] . ' - ' . $parsed['semester'],
                    'tahun' => $parsed['nama'],
                    'semester' => $parsed['semester']
                ];
            }

            return response()->json($result, 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => '0',
                'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistik semester aktif
     */
    public function getCurrentSemesterStats(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'status' => '0',
                    'keterangan' => 'Session user tidak ditemukan'
                ], 401);
            }

            $nim = $user->nim;

            // Get data semester aktif (tahun_akademik = 1 berarti aktif)
            $data = KHS::get_statistik_semester($nim, '1');

            if ($data) {
                return response()->json([
                    'total_mk' => $data->total_mk ?? 0,
                    'total_sks' => $data->total_sks ?? 0,
                    'ips' => $data->ips ?? 0.00,
                    'ipk' => $data->ipk ?? 0.00
                ], 200);
            }

            return response()->json([
                'total_mk' => 0,
                'total_sks' => 0,
                'ips' => 0.00,
                'ipk' => 0.00
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => '0',
                'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get data transkrip lengkap
     */
    public function getTranskrip(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'status' => '0',
                    'keterangan' => 'Session user tidak ditemukan'
                ], 401);
            }

            $nim = $user->nim;
            $data = KHS::get_transkrip($nim);

            if ($data) {
                return response()->json([
                    'total_sks' => $data->total_sks ?? 0,
                    'sks_lulus' => $data->sks_lulus ?? 0,
                    'total_mk' => $data->total_mk ?? 0,
                    'ipk' => $data->ipk ?? 0.00,
                    'total_bobot' => $data->total_bobot ?? 0
                ], 200);
            }

            return response()->json([
                'total_sks' => 0,
                'sks_lulus' => 0,
                'total_mk' => 0,
                'ipk' => 0.00,
                'total_bobot' => 0
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => '0',
                'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download KHS dalam format PDF
     */
    public function downloadKHS(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'status' => '0',
                    'keterangan' => 'Session user tidak ditemukan'
                ], 401);
            }

            $nim = $user->nim;
            $tahun_akademik = $request->tahun_akademik ?? '1'; // Default semester aktif
            $semester = $request->semester ?? '';

            // Get data KHS
            $khsData = KHS::get_daftar_nilai($nim, $tahun_akademik, $semester, '', 0, 10000);

            if (empty($khsData) || count($khsData) == 0) {
                return response()->json([
                    'status' => '0',
                    'keterangan' => 'Data KHS tidak ditemukan'
                ], 404);
            }

            // Data mahasiswa
            $mahasiswaData = (object) [
                'nim' => $nim,
                'nama_mahasiswa' => $user->nama_lengkap ?? 'Nama Mahasiswa',
                'angkatan' => $this->getAngkatanFromNIM($nim) ?? '-',
                'nama_prodi' => $user->nama_prodi ?? '-',
                'jenjang' => $user->jenjang ?? 'S1',
                'nama_dps' => $khsData[0]->nama_dps ?? 'Dosen PA',
                'nidn' => $khsData[0]->nidn_dps ?? '-'
            ];

            // Parse tahun akademik
            $tahunAkademikParsed = $this->parseTahunAkademik($khsData[0]->tahun_akademik ?? '20251');

            $tahunAkademikInfo = (object) [
                'id' => $khsData[0]->tahun_akademik ?? '1',
                'nama' => $tahunAkademikParsed['nama'],
                'semester' => $tahunAkademikParsed['semester']
            ];

            // Transform data mata kuliah
            $mataKuliah = [];
            $totalSKS = 0;
            $totalBobot = 0;

            foreach ($khsData as $mk) {
                $sks = intval($mk->sks ?? 0);
                $bobot = floatval($mk->bobot ?? 0);

                $mataKuliah[] = [
                    'kd_mata_kuliah' => $mk->kd_mata_kuliah ?? '-',
                    'nama_mata_kuliah' => $mk->nama_mata_kuliah ?? '-',
                    'sks' => $sks,
                    'nilai_angka' => $mk->nilai_angka ? number_format($mk->nilai_angka, 2) : '-',
                    'nilai_huruf' => $mk->nilai_huruf ?? '-',
                    'bobot' => number_format($bobot, 2),
                    'nama_dosen' => $mk->nama_dosen ?? '-'
                ];

                $totalSKS += $sks;
                $totalBobot += ($bobot * $sks);
            }

            // Hitung IPS
            $ips = $totalSKS > 0 ? ($totalBobot / $totalSKS) : 0;

            // Get IPK
            $transkrip = KHS::get_transkrip($nim);
            $ipk = $transkrip->ipk ?? 0;

            // Generate QR Code
            $qrCode = base64_encode(
                QrCode::format('svg')
                    ->size(200)
                    ->margin(1)
                    ->errorCorrection('H')
                    ->generate(route('frontpage.verifikasi_khs', ['nim' => base64_encode($nim)]))
            );

            // Logo
            $logoPath = public_path('image/logo-uij.png');
            $logoBase64 = '';
            if (file_exists($logoPath)) {
                $logoData = file_get_contents($logoPath);
                $logoBase64 = base64_encode($logoData);
            }

            // Tanggal cetak
            $tanggalCetak = Carbon::now()->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY HH:mm');

            // Data untuk PDF
            $data = [
                'mahasiswa' => $mahasiswaData,
                'tahun_akademik' => $tahunAkademikInfo,
                'mata_kuliah' => $mataKuliah,
                'total_sks' => $totalSKS,
                'total_mk' => count($mataKuliah),
                'ips' => number_format($ips, 2),
                'ipk' => number_format($ipk, 2),
                'tanggal_cetak' => $tanggalCetak,
                'qr_code' => $qrCode,
                'logo' => $logoBase64
            ];

            // Generate PDF
            $pdf = Facade::loadView('mahasiswa_page.akademik.khs.pdf', $data);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
                'enable_php' => false
            ]);

            // Nama file
            $filename = 'KHS_' . $mahasiswaData->nim . '_' .
                str_replace('/', '-', $tahunAkademikInfo->nama) . '_' .
                $tahunAkademikInfo->semester . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return response()->json([
                'status' => '0',
                'keterangan' => 'Terjadi kesalahan saat generate PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper: Hitung statistik dari data KHS
     */
    private function hitungStatistik($data)
    {
        $totalMK = count($data);
        $totalSKS = 0;
        $totalBobot = 0;

        foreach ($data as $item) {
            $sks = intval($item->sks ?? 0);
            $bobot = floatval($item->bobot ?? 0);

            $totalSKS += $sks;
            $totalBobot += ($bobot * $sks);
        }

        $ips = $totalSKS > 0 ? ($totalBobot / $totalSKS) : 0;

        return [
            'total_mk' => $totalMK,
            'total_sks' => $totalSKS,
            'ips' => round($ips, 2),
            'total_bobot' => round($totalBobot, 2)
        ];
    }

    /**
     * Helper: Parse tahun akademik dari format database
     * Format input: "20251" -> Output: ['nama' => '2024/2025', 'semester' => 'Ganjil']
     */
    private function parseTahunAkademik($tahunAkademik)
    {
        $result = [
            'nama' => '2024/2025',
            'semester' => 'Ganjil'
        ];

        if (empty($tahunAkademik) || strlen($tahunAkademik) != 5) {
            return $result;
        }

        $tahun = substr($tahunAkademik, 0, 4);
        $semesterCode = substr($tahunAkademik, 4, 1);

        $tahunInt = intval($tahun);
        $tahunBerikutnya = $tahunInt + 1;
        $namaTahun = $tahunInt . '/' . $tahunBerikutnya;

        $namaSemester = 'Ganjil';
        switch ($semesterCode) {
            case '1':
                $namaSemester = 'Ganjil';
                break;
            case '2':
                $namaSemester = 'Genap';
                break;
            case '3':
                $namaSemester = 'Antara';
                break;
            default:
                $namaSemester = 'Ganjil';
        }

        return [
            'nama' => $namaTahun,
            'semester' => $namaSemester
        ];
    }

    /**
     * Helper: Get angkatan dari NIM
     */
    private function getAngkatanFromNIM($nim)
    {
        $defaultAngkatan = date('Y');

        if (empty($nim) || strlen($nim) < 2) {
            return $defaultAngkatan;
        }

        $tahunDuaDigit = substr($nim, 0, 2);
        $tahunInt = intval($tahunDuaDigit);

        if ($tahunInt >= 0 && $tahunInt <= 50) {
            $angkatan = 2000 + $tahunInt;
        } else {
            $angkatan = 1900 + $tahunInt;
        }

        return (string) $angkatan;
    }
}
