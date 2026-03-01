<?php

namespace App\Http\Controllers\MahasiswaPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Akademik\KRS;
use Barryvdh\DomPDF\Facade;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class KRSController extends Controller
{
    public function index()
    {
        $menu = "Mengelola Kartu Rencana Studi";
        return view('mahasiswa_page.akademik.krs.index', compact('menu'));
    }

    public function json()
    {
        try {
            // Set default values untuk filter
            $kd_prodi = Session::get('user')->kd_prodi;
            $length = $request->length ?? 10;
            $start = $request->start ?? 0;
            $search = $request->search['value'] ?? '';

            $data_ = KRS::get_daftar($kd_prodi, 1, null, $search, $start, $length);

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

    public function json_draft()
    {
        try {
            // Set default values untuk filter
            $kd_prodi = Session::get('user')->kd_prodi;
            $length = $request->length ?? 10;
            $start = $request->start ?? 0;
            $search = $request->search['value'] ?? '';
            $nim = Session::get('user')->nim;
            $data_ = KRS::get_daftar($kd_prodi, 1, $nim, $search, $start, $length);
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

    public function store(Request $request)
    {
        $request->validate([
            'krs_data' => 'required'
        ]);
        $nim = Session::get('user')->nim;
        $krsData = is_string($request->krs_data)
            ? json_decode($request->krs_data, true)
            : $request->krs_data;
        $idJadwalString = collect($krsData)
            ->pluck('id_jadwal')
            ->implode(',');
        $data = KRS::inDelKRS($nim, $idJadwalString, $krsData[0]['id_krs_mahasiswa']);
        return response()->json($data);
    }

    public function ajukan_krs(Request $request)
    {
        $request->validate([
            'id_krs_list' => 'required'
        ]);
        $data = KRS::update_status_krs($request->id_krs_list, 1);
        return response()->json($data);
    }

    public function cekMaksimalKrs()
    {
        $nim = Session::get('user')->nim;
        $sks = KRS::getSKSMaks($nim);
        return response()->json($sks, 200);
    }

    /**
     * Download KRS Mahasiswa (PDF)
     */
    public function downloadKRS(Request $request)
    {
        try {
            $user = Session::get('user');
            if (! $user) {
                return response()->json([
                    'status' => '0',
                    'keterangan' => 'Session user tidak ditemukan'
                ], 401);
            }

            // Ambil data KRS dari Model
            $kd_prodi = $user->kd_prodi;
            $nim = $user->nim ??  $request->nim;

            $krsData = KRS::get_daftar($kd_prodi, 1, $nim);
            if (empty($krsData) || count($krsData) == 0) {
                return response()->json([
                    'status' => '0',
                    'keterangan' => 'Data KRS tidak ditemukan atau belum disetujui'
                ], 404);
            }

            // Ambil data pertama untuk info mahasiswa dan status
            $firstRecord = $krsData[0];

            // Parse Tahun Akademik dari format "20251"
            $tahunAkademikParsed = $this->parseTahunAkademik($firstRecord->tahun_akademik);

            // Data Mahasiswa dari record pertama
            $mahasiswaData = (object) [
                'nim' => $nim,
                'nama_mahasiswa' => $user->nama_lengkap ?? 'Nama Mahasiswa',
                'angkatan' => $this->getAngkatanFromNIM($nim) ?? '-',
                'nama_prodi' => $user->nama_prodi ?? '-',
                'jenjang' => $user->jenjang ?? 'S1',
                'nama_dps' => $firstRecord->nama_dps ?? 'Dosen PA',
                'nidn' => $firstRecord->nidn_dps ?? '-',
                'nama_kaprodi' => $firstRecord->nama_kaprodi ?? 'Ketua Program Studi',
                'nidn_kaprodi' => $firstRecord->nidn_kaprodi ?? '-',
                'id_krs' => $firstRecord->qr_mahasiswa ?? '-',
                'id_persetujuan_dps' => $firstRecord->qr_dps ?? '-',
                'id_persetujuan_kaprodi' => $firstRecord->qr_kaprodi ?? '-'
            ];

            // Tahun Akademik
            $tahunAkademikAktif = (object) [
                'id' => 1,
                'nama' => $tahunAkademikParsed['nama'], // "2024/2025"
                'semester' => $tahunAkademikParsed['semester'], // "Ganjil", "Genap", atau "Antara"
                'is_active' => 1
            ];

            // Mapping hari dari angka ke nama
            $hariNames = [
                0 => '-',
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
                7 => 'Minggu'
            ];

            // Transform data mata kuliah untuk template
            $mataKuliah = [];
            foreach ($krsData as $mk) {
                // Skip jika bukan mata kuliah yang diambil
                if (!$mk->is_diambil && $mk->id_krs_mahasiswa === null) {
                    continue;
                }

                $mataKuliah[] = [
                    'kd_mata_kuliah' => $mk->kd_mata_kuliah ??  '-',
                    'nama_mata_kuliah' => $mk->nama_mata_kuliah ??  '-',
                    'sks' => $mk->sks ??  0,
                    'nama_kelas' => $mk->nama_kelas ?? '-',
                    'hari' => $hariNames[$mk->hari] ?? '-',
                    'jam' => $this->formatJam($mk->jam_mulai, $mk->jam_selesai),
                    'ruang' => $mk->ruang ??  '-',
                    'nama_dosen' => $mk->nama_dosen ?? '-'
                ];
            }

            // Jika tidak ada mata kuliah yang diambil, return error
            if (count($mataKuliah) == 0) {
                return response()->json([
                    'status' => '0',
                    'keterangan' => 'Tidak ada mata kuliah yang diambil'
                ], 404);
            }

            // Hitung total SKS
            $totalSKS = array_sum(array_column($mataKuliah, 'sks'));
            $totalMK = count($mataKuliah);

            // Komentar dari database atau default
            $komentarDPS = $firstRecord->komentar_dps ?? '-';
            $komentarKaprodi = $firstRecord->komentar_kaprodi ?? '-';

            // Format tanggal
            $tglPengajuan = $firstRecord->tgl_pengajuan_krs ?
                Carbon::parse($firstRecord->tgl_pengajuan_krs)->format('d/m/Y') :
                Carbon::now()->subDays(12)->format('d/m/Y');

            $tglVerifikasiDPS = $firstRecord->tgl_persetujuan_dps ?
                Carbon::parse($firstRecord->tgl_persetujuan_dps)->format('d/m/Y') :
                Carbon::now()->subDays(8)->format('d/m/Y');

            $tglVerifikasiKaprodi = $firstRecord->tgl_persetujuan_kaprodi ?
                Carbon::parse($firstRecord->tgl_persetujuan_kaprodi)->format('d/m/Y') :
                Carbon::now()->subDays(5)->format('d/m/Y');

            $tanggalCetak = Carbon::now()->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY hh:mm');

            // Generate QR Codes sebagai svg dan encode ke base64
            $qrMahasiswa = base64_encode(
                QrCode::format('svg')
                    ->size(200)
                    ->margin(1)
                    ->errorCorrection('H')
                    ->generate(route('frontpage.detail_qr', ['id' => base64_encode($mahasiswaData->id_krs)]))
            );

            $qrDPS = base64_encode(
                QrCode::format('svg')
                    ->size(200)
                    ->margin(1)
                    ->errorCorrection('H')
                    ->generate(route('frontpage.detail_qr', ['id' => base64_encode($mahasiswaData->id_persetujuan_dps)]))
            );

            $qrKaprodi = base64_encode(
                QrCode:: format('svg')
                    ->size(200)
                    ->margin(1)
                    ->errorCorrection('H')
                    ->generate(route('frontpage.detail_qr', ['id' => base64_encode($mahasiswaData->id_persetujuan_kaprodi)]))
            );
            $logoPath = public_path('image/logo-uij.png');
            $logoBase64 = '';

            if (file_exists($logoPath)) {
                $logoData = file_get_contents($logoPath);
                $logoBase64 = base64_encode($logoData);
            }

            // Data untuk PDF
            $data = [
                'mahasiswa' => $mahasiswaData,
                'tahun_akademik' => $tahunAkademikAktif,
                'mata_kuliah' => $mataKuliah,
                'total_sks' => $totalSKS,
                'total_mk' => $totalMK,
                'tanggal_cetak' => $tanggalCetak,
                'komentar_dps' => $komentarDPS,
                'komentar_kaprodi' => $komentarKaprodi,
                'tgl_pengajuan' => $tglPengajuan,
                'tgl_verifikasi_dps' => $tglVerifikasiDPS,
                'tgl_verifikasi_kaprodi' => $tglVerifikasiKaprodi,
                'qr_mahasiswa' => $qrMahasiswa,
                'qr_dps' => $qrDPS,
                'qr_kaprodi' => $qrKaprodi,
                'logo' => $logoBase64
            ];

            // Generate PDF
            $pdf = Facade::loadView('mahasiswa_page.akademik.krs.pdf', $data);

            // Set paper size dan orientation
            $pdf->setPaper('A4', 'portrait');

            // Set options
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
                'enable_php' => false
            ]);

            // Download dengan nama file
            $filename = 'KRS_' .  $mahasiswaData->nim . '_' . str_replace('/', '-', $tahunAkademikAktif->nama) . '_' . $tahunAkademikAktif->semester . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return response()->json([
                'status' => '0',
                'keterangan' => 'Terjadi kesalahan saat generate PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper function untuk parse tahun akademik
     * Format input: "20251" -> Output: ['nama' => '2024/2025', 'semester' => 'Ganjil']
     *
     * @param string $tahunAkademik Format: "20251" (4 digit tahun + 1 digit semester)
     * @return array ['nama' => string, 'semester' => string]
     */
    private function parseTahunAkademik($tahunAkademik)
    {
        // Default values
        $result = [
            'nama' => '2024/2025',
            'semester' => 'Ganjil'
        ];

        // Validasi input
        if (empty($tahunAkademik) || strlen($tahunAkademik) != 5) {
            return $result;
        }

        // Parse tahun (4 digit pertama)
        $tahun = substr($tahunAkademik, 0, 4);

        // Parse semester (1 digit terakhir)
        $semesterCode = substr($tahunAkademik, 4, 1);

        // Convert tahun ke format "YYYY/(YYYY+1)"
        $tahunInt = intval($tahun);
        $tahunBerikutnya = $tahunInt + 1;
        $namaTahun = $tahunInt .  '/' . $tahunBerikutnya;

        // Convert semester code ke nama
        $namaSemester = 'Ganjil'; // default
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
     * Helper function untuk format jam
     *
     * @param string $jam_mulai Format: "08:00: 00"
     * @param string $jam_selesai Format: "10:00:00"
     * @return string Format: "08:00-10:00"
     */
    private function formatJam($jam_mulai, $jam_selesai)
    {
        if (! $jam_mulai || !$jam_selesai) {
            return '-';
        }

        // Format jam dari 08:00:00 menjadi 08:00
        $mulai = substr($jam_mulai, 0, 5);
        $selesai = substr($jam_selesai, 0, 5);
        return $mulai . '-' . $selesai;
    }

    /**
     * Helper function untuk parse angkatan dari NIM
     * 2 digit pertama NIM = tahun angkatan
     *
     * @param string $nim NIM mahasiswa
     * @return string Angkatan 4 digit (contoh: "2025")
     */
    private function getAngkatanFromNIM($nim)
    {
        // Default angkatan
        $defaultAngkatan = date('Y');

        // Validasi NIM minimal 2 karakter
        if (empty($nim) || strlen($nim) < 2) {
            return $defaultAngkatan;
        }

        // Ambil 2 digit pertama
        $tahunDuaDigit = substr($nim, 0, 2);

        // Convert ke integer
        $tahunInt = intval($tahunDuaDigit);

        // Tentukan abad (20xx atau 19xx)
        // Asumsi: 00-50 = 2000-2050, 51-99 = 1951-1999
        if ($tahunInt >= 0 && $tahunInt <= 50) {
            $angkatan = 2000 + $tahunInt;
        } else {
            $angkatan = 1900 + $tahunInt;
        }

        return (string) $angkatan;
    }
}
