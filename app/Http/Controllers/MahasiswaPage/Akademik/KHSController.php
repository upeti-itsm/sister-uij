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

            // Ambil filter mentah dari request (default string kosong)
            $tahun = trim((string) ($request->tahun_akademik ?? ''));
            $sem   = trim((string) ($request->semester ?? ''));

            /**
             * Bentuk parameter tahun_akademik untuk function DB:
             * - Jika tahun & semester kosong => NULL (agar sesuai harapan)
             * - Jika tahun terisi dan semester terisi => gabung (contoh: 2024 + 1 => 20241)
             * - Jika hanya tahun terisi (semester kosong) => kirim NULL (umumnya function butuh 5 digit)
             *   Jika function DB kamu bisa terima tahun saja, ganti jadi: $tahun_akademik = $tahun;
             */
            if ($tahun === '' && $sem === '') {
                $tahun_akademik = "";
            } elseif ($tahun !== '' && $sem !== '') {
                $tahun_akademik = $tahun . $sem;
            } else {
                // Salah satu kosong -> default null supaya tidak mengirim "" atau format tidak valid
                $tahun_akademik = null;
            }

            // Search: kamu handle 2 tipe (DataTables object atau string)
            $search = '';
            if (is_array($request->search)) {
                $search = $request->search['value'] ?? '';
            } else {
                $search = $request->search ?? '';
            }

            $start  = intval($request->start ?? 0);
            $length = intval($request->length ?? 10);

            $data_ = KHS::get_daftar_nilai($nim, $tahun_akademik, $search, $start, $length);

            $data = [
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => $data_,
                'error' => null
            ];

            if (is_array($data_) && count($data_) > 0) {
                // Kalau function DB mengembalikan jml_record di setiap row
                $data['recordsTotal'] = $data_[0]->jml_record ?? count($data_);
                $data['recordsFiltered'] = $data['recordsTotal'];

                // Statistik untuk response (sesuai kode kamu)
                $data['statistik'] = $this->hitungStatistik($data_);
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

    public function getSemesterList(Request $request)
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

            $data = KHS::get_semester_list($nim);

            $result = [];
            foreach ($data as $item) {
                $result[] = [
                    'id' => $item->id_semester,
                    'nama' => $item->semester
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
            $tahun_akademik = $request->tahun_akademik ?? null;

            $data = KHS::get_statistik_semester($nim, $tahun_akademik);

            if ($data) {
                return response()->json([
                    'total_mk' => $data->jumlah_matkul ?? 0,
                    'total_sks' => $data->sks_total ?? 0,
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
            $tahun_akademik = $request->tahun_akademik ?? null;
            $data = KHS::get_statistik_semester($nim, $tahun_akademik);

            if ($data) {
                return response()->json([
                    'total_sks' => $data->sks_semester ?? 0,
                    'sks_lulus' => $data->sks_total ?? 0,
                    'total_mk' => $data->jumlah_matkul ?? 0,
                    'ipk' => $data->ipk ?? 0.00
                ], 200);
            }

            return response()->json([
                'total_sks' => 0,
                'sks_lulus' => 0,
                'total_mk' => 0,
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
     * Download KHS dalam format PDF
     */
    public function downloadKHS(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json(['status' => '0', 'keterangan' => 'Session user tidak ditemukan'], 401);
            }

            $nim = $user->nim;
            $tahun_akademik = $request->tahun_akademik . $request->semester ?? null;

            $khsData = KHS::get_daftar_nilai($nim, $tahun_akademik);

            if (empty($khsData) || count($khsData) == 0) {
                return response()->json(['status' => '0', 'keterangan' => 'Data KHS tidak ditemukan'], 404);
            }

            $first = $khsData[0];

            $mahasiswaData = (object) [
                'nim'             => $first->nim,
                'nama_mahasiswa'  => $first->nama_mahasiswa,
                'nama_prodi'      => $first->nama_prodi,
                'nama_fakultas'   => $first->nama_fakultas,
                'tahun_ajaran'    => $first->tahun_ajaran,
                'semester'        => $first->semester,
                'nama_semester'   => $first->nama_semester,
                'ips'             => $first->ips ?? 0,
                'ipk'             => $first->ipk ?? 0,
                'sks_semester'    => $first->sks_semester ?? 0,
                'sks_total'       => $first->sks_total ?? 0,
                'beban_maks_sks'  => $first->beban_maks_sks ?? 0,
                'nama_wakil_dekan' => $first->nama_wakil_dekan ?? '-',
                'nidn_wakil_dekan' => $first->nidn_wakil_dekan ?? '-',
                'jumlah_matakuliah' => $first->jumlah_matakuliah ?? 0,
            ];

            $mataKuliah = [];
            $totalSKS = 0;

            foreach ($khsData as $mk) {
                $sks = intval($mk->sks ?? 0);
                $mataKuliah[] = [
                    'kd_matakuliah'  => $mk->kd_matakuliah ?? '-',
                    'matakuliah'     => $mk->matakuliah ?? '-',
                    'sks'            => $sks,
                    'nilai_angka'    => $mk->nilai_angka ?? '-',
                    'sts_nilai'      => $mk->sts_nilai ?? '-',
                ];
                $totalSKS += $sks;
            }

            $qrCode = base64_encode(
                QrCode::format('svg')
                    ->size(150)
                    ->margin(1)
                    ->errorCorrection('H')
                    ->generate('Halo')
            );

            $logoPath = public_path('image/uij.png');
            $logoBase64 = '';
            if (file_exists($logoPath)) {
                $logoBase64 = base64_encode(file_get_contents($logoPath));
            }

            $tanggalCetak = Carbon::now()->timezone('Asia/Jakarta');

            $data = [
                'mahasiswa'    => $mahasiswaData,
                'mata_kuliah'  => $mataKuliah,
                'total_sks'    => $totalSKS,
                'qr_code'      => $qrCode,
                'logo'         => $logoBase64,
                'tanggal_cetak' => $tanggalCetak,
            ];

            $pdf = Facade::loadView('mahasiswa_page.akademik.khs.pdf', $data);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
                'defaultFont'          => 'serif',
                'enable_php'           => false,
            ]);

            $filename = 'LHS_' . $mahasiswaData->nim . '_' . $mahasiswaData->tahun_ajaran . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            return response()->json(['status' => '0', 'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
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
