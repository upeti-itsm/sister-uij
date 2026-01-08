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
     * Download KRS Mahasiswa (PDF) - QR Code Base64 PNG
     */
    public function downloadKRS(Request $request)
    {
        try {
            // DUMMY DATA - Data Mahasiswa
            $mahasiswaData = (object) [
                'nim' => '2021001234',
                'nama_mahasiswa' => 'Ahmad Rizki Pratama',
                'angkatan' => '2021',
                'nama_prodi' => 'Teknik Informatika',
                'jenjang' => 'S1',
                'nama_dps' => 'Dr. Budi Santoso, M.Kom',
                'nidn' => '0123456789',
                'nama_kaprodi' => 'Prof. Dr. Ir. Siti Aminah, M.T',
                'nidn_kaprodi' => '0987654321'
            ];

            // DUMMY DATA - Tahun Akademik
            $tahunAkademikAktif = (object) [
                'id' => 1,
                'nama' => '2024/2025',
                'semester' => 'Ganjil',
                'is_active' => 1
            ];

            // DUMMY DATA - Mata Kuliah yang diambil
            $mataKuliahDummy = [
                [
                    'kd_mata_kuliah' => 'TIF301',
                    'nama_mata_kuliah' => 'Algoritma dan Pemrograman',
                    'sks' => 3,
                    'nama_kelas' => 'A',
                    'hari' => 'Senin',
                    'jam' => '08:00-10:30',
                    'ruang' => 'Lab 1',
                    'nama_dosen' => 'Dr. Andi Wijaya, M.Kom'
                ],
                [
                    'kd_mata_kuliah' => 'TIF302',
                    'nama_mata_kuliah' => 'Struktur Data',
                    'sks' => 3,
                    'nama_kelas' => 'A',
                    'hari' => 'Selasa',
                    'jam' => '13:00-15:30',
                    'ruang' => 'Lab 2',
                    'nama_dosen' => 'Prof. Siti Nurhaliza, M.T'
                ],
                [
                    'kd_mata_kuliah' => 'TIF303',
                    'nama_mata_kuliah' => 'Basis Data',
                    'sks' => 3,
                    'nama_kelas' => 'B',
                    'hari' => 'Rabu',
                    'jam' => '08:00-10:30',
                    'ruang' => 'Lab DB',
                    'nama_dosen' => 'Ir. Bambang Hartono, M.Sc'
                ],
                [
                    'kd_mata_kuliah' => 'TIF304',
                    'nama_mata_kuliah' => 'Pemrograman Web',
                    'sks' => 3,
                    'nama_kelas' => 'A',
                    'hari' => 'Kamis',
                    'jam' => '10:30-13:00',
                    'ruang' => 'Lab MM',
                    'nama_dosen' => 'Dra. Rina Kusumawati, M.Kom'
                ],
                [
                    'kd_mata_kuliah' => 'TIF305',
                    'nama_mata_kuliah' => 'Sistem Operasi',
                    'sks' => 3,
                    'nama_kelas' => 'A',
                    'hari' => 'Jumat',
                    'jam' => '08:00-10:30',
                    'ruang' => 'Lab 3',
                    'nama_dosen' => 'Dr. Hendra Saputra, M.T'
                ],
                [
                    'kd_mata_kuliah' => 'UNV201',
                    'nama_mata_kuliah' => 'Bahasa Inggris Teknik',
                    'sks' => 2,
                    'nama_kelas' => 'C',
                    'hari' => 'Sabtu',
                    'jam' => '08:00-09:40',
                    'ruang' => 'R.  201',
                    'nama_dosen' => 'Lisa Anderson, M.A'
                ]
            ];

            // Hitung total SKS
            $totalSKS = collect($mataKuliahDummy)->sum('sks');
            $totalMK = count($mataKuliahDummy);

            // DUMMY DATA - Komentar
            $komentarDPS = 'KRS sesuai IP semester lalu (3. 45). Total ' . $totalSKS . ' SKS memenuhi ketentuan.  Disetujui. ';
            $komentarKaprodi = 'Memenuhi persyaratan kurikulum. Tidak ada konflik jadwal. Disetujui. ';

            // Data tanggal
            $tglPengajuan = Carbon::now()->subDays(12)->format('d/m/Y');
            $tglVerifikasiDPS = Carbon::now()->subDays(8)->format('d/m/Y');
            $tglVerifikasiKaprodi = Carbon::now()->subDays(5)->format('d/m/Y');
            $tanggalCetak = Carbon::now()->locale('id')->isoFormat('D MMMM YYYY');

            // Generate QR Codes sebagai PNG dan encode ke base64
            $qrMahasiswa = base64_encode(
                QrCode::format('svg')
                    ->size(200)
                    ->margin(1)
                    ->errorCorrection('H')
                    ->generate('MHS|' . $mahasiswaData->nim . '|' . $mahasiswaData->nama_mahasiswa . '|' . $tglPengajuan)
            );

            $qrDPS = base64_encode(
                QrCode::format('svg')
                    ->size(200)
                    ->margin(1)
                    ->errorCorrection('H')
                    ->generate('DPS|' . $mahasiswaData->nidn . '|' . $mahasiswaData->nama_dps . '|' .  $tglVerifikasiDPS)
            );

            $qrKaprodi = base64_encode(
                QrCode::format('svg')
                    ->size(200)
                    ->margin(1)
                    ->errorCorrection('H')
                    ->generate('KPR|' . $mahasiswaData->nidn_kaprodi . '|' . $mahasiswaData->nama_kaprodi . '|' . $tglVerifikasiKaprodi)
            );

            // Data untuk PDF
            $data = [
                'mahasiswa' => $mahasiswaData,
                'tahun_akademik' => $tahunAkademikAktif,
                'mata_kuliah' => $mataKuliahDummy,
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
                'qr_kaprodi' => $qrKaprodi
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
            $filename = 'KRS_' .  $mahasiswaData->nim .  '_' . str_replace('/', '-', $tahunAkademikAktif->nama) . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return response()->json([
                'status' => '0',
                'keterangan' => 'Terjadi kesalahan saat generate PDF:  ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preview KRS (untuk testing di browser)
     */
    public function previewKRS(Request $request)
    {
        try {
            // DUMMY DATA - Data Mahasiswa
            $mahasiswaData = (object) [
                'nim' => '2021001234',
                'nama_mahasiswa' => 'Ahmad Rizki Pratama',
                'angkatan' => '2021',
                'nama_prodi' => 'Teknik Informatika',
                'jenjang' => 'S1',
                'nama_dps' => 'Dr.  Budi Santoso, M. Kom',
                'nidn' => '0123456789',
                'nama_kaprodi' => 'Prof. Dr.  Ir. Siti Aminah, M.T',
                'nidn_kaprodi' => '0987654321'
            ];

            $tahunAkademikAktif = (object) [
                'id' => 1,
                'nama' => '2024/2025',
                'semester' => 'Ganjil',
                'is_active' => 1
            ];

            $mataKuliahDummy = [
                [
                    'kd_mata_kuliah' => 'TIF301',
                    'nama_mata_kuliah' => 'Algoritma dan Pemrograman',
                    'sks' => 3,
                    'nama_kelas' => 'A',
                    'hari' => 'Senin',
                    'jam' => '08:00-10:30',
                    'ruang' => 'Lab 1',
                    'nama_dosen' => 'Dr. Andi Wijaya, M.Kom'
                ],
                [
                    'kd_mata_kuliah' => 'TIF302',
                    'nama_mata_kuliah' => 'Struktur Data',
                    'sks' => 3,
                    'nama_kelas' => 'A',
                    'hari' => 'Selasa',
                    'jam' => '13:00-15:30',
                    'ruang' => 'Lab 2',
                    'nama_dosen' => 'Prof. Siti Nurhaliza, M. T'
                ],
                [
                    'kd_mata_kuliah' => 'TIF303',
                    'nama_mata_kuliah' => 'Basis Data',
                    'sks' => 3,
                    'nama_kelas' => 'B',
                    'hari' => 'Rabu',
                    'jam' => '08:00-10:30',
                    'ruang' => 'Lab DB',
                    'nama_dosen' => 'Ir.  Bambang Hartono, M.Sc'
                ],
                [
                    'kd_mata_kuliah' => 'TIF304',
                    'nama_mata_kuliah' => 'Pemrograman Web',
                    'sks' => 3,
                    'nama_kelas' => 'A',
                    'hari' => 'Kamis',
                    'jam' => '10:30-13:00',
                    'ruang' => 'Lab MM',
                    'nama_dosen' => 'Dra. Rina Kusumawati, M. Kom'
                ],
                [
                    'kd_mata_kuliah' => 'TIF305',
                    'nama_mata_kuliah' => 'Sistem Operasi',
                    'sks' => 3,
                    'nama_kelas' => 'A',
                    'hari' => 'Jumat',
                    'jam' => '08:00-10:30',
                    'ruang' => 'Lab 3',
                    'nama_dosen' => 'Dr. Hendra Saputra, M.T'
                ],
                [
                    'kd_mata_kuliah' => 'UNV201',
                    'nama_mata_kuliah' => 'Bahasa Inggris Teknik',
                    'sks' => 2,
                    'nama_kelas' => 'C',
                    'hari' => 'Sabtu',
                    'jam' => '08:00-09:40',
                    'ruang' => 'R. 201',
                    'nama_dosen' => 'Lisa Anderson, M. A'
                ]
            ];

            $totalSKS = collect($mataKuliahDummy)->sum('sks');
            $totalMK = count($mataKuliahDummy);

            $komentarDPS = 'KRS sesuai IP semester lalu (3.45). Total ' . $totalSKS .  ' SKS memenuhi ketentuan. Disetujui. ';
            $komentarKaprodi = 'Memenuhi persyaratan kurikulum. Tidak ada konflik jadwal. Disetujui. ';

            $tglPengajuan = Carbon::now()->subDays(12)->format('d/m/Y');
            $tglVerifikasiDPS = Carbon::now()->subDays(8)->format('d/m/Y');
            $tglVerifikasiKaprodi = Carbon::now()->subDays(5)->format('d/m/Y');
            $tanggalCetak = Carbon::now()->locale('id')->isoFormat('D MMMM YYYY');

            $qrMahasiswa = base64_encode(
                QrCode::format('svg')
                    ->size(200)
                    ->margin(1)
                    ->errorCorrection('H')
                    ->generate('MHS|' . $mahasiswaData->nim . '|' . $mahasiswaData->nama_mahasiswa . '|' . $tglPengajuan)
            );

            $qrDPS = base64_encode(
                QrCode::format('svg')
                    ->size(200)
                    ->margin(1)
                    ->errorCorrection('H')
                    ->generate('DPS|' .  $mahasiswaData->nidn . '|' . $mahasiswaData->nama_dps .  '|' . $tglVerifikasiDPS)
            );

            $qrKaprodi = base64_encode(
                QrCode::format('svg')
                    ->size(200)
                    ->margin(1)
                    ->errorCorrection('H')
                    ->generate('KPR|' .  $mahasiswaData->nidn_kaprodi . '|' . $mahasiswaData->nama_kaprodi . '|' . $tglVerifikasiKaprodi)
            );

            $data = [
                'mahasiswa' => $mahasiswaData,
                'tahun_akademik' => $tahunAkademikAktif,
                'mata_kuliah' => $mataKuliahDummy,
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
                'qr_kaprodi' => $qrKaprodi
            ];

            return view('mahasiswa_page.akademik.krs.pdf', $data);

        } catch (\Exception $e) {
            return response()->json([
                'status' => '0',
                'keterangan' => 'Terjadi kesalahan:  ' . $e->getMessage()
            ], 500);
        }
    }
}
