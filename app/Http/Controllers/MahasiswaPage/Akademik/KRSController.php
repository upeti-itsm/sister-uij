<?php

namespace App\Http\Controllers\MahasiswaPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Akademik\JadwalKuliahMahasiswa;
use App\Models\Akademik\KRS;
use App\Models\Akademik\Semester;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class KRSController extends Controller
{
    // =====================================================================
    // HALAMAN UTAMA
    // =====================================================================

    /**
     * Halaman daftar riwayat KRS (entry point)
     */
    public function riwayat()
    {
        $menu = "Mengelola Kartu Rencana Studi";
        $tahun_akademik = JadwalKuliahMahasiswa::get_tahun_akademik();
        return view('mahasiswa_page.akademik.krs.list_krs', compact('menu', 'tahun_akademik'));
    }

    /**
     * Halaman proses KRS (pilih mata kuliah)
     */
    public function index()
    {
        $menu = "Mengelola Kartu Rencana Studi";
        return view('mahasiswa_page.akademik.krs.index', compact('menu'));
    }

    // =====================================================================
    // JSON ENDPOINTS
    // =====================================================================

    /**
     * JSON DataTable daftar jadwal mata kuliah (server-side)
     * Endpoint: POST /mhs/krs/json
     */
    public function json(Request $request)
    {
        try {
            $kd_prodi = Session::get('user')->kd_prodi;
            $length = intval($request->length ?? 10);
            $start = intval($request->start ?? 0);
            $search = isset($request->search['value']) ? $request->search['value'] : '';

            $data_ = KRS::get_daftar($kd_prodi, 1, null, $search, $start, $length);

            $data = [
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => $data_,
                'error' => null,
            ];

            if (count($data_) > 0) {
                $data['recordsTotal'] = isset($data_[0]->jml_record) ? $data_[0]->jml_record : count($data_);
                $data['recordsFiltered'] = $data['recordsTotal'];
            }

            return response()->json($data, 200);

        } catch (\Exception $e) {
            return response()->json([
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * JSON draft KRS aktif milik mahasiswa yang sedang login
     * Endpoint: POST /mhs/krs/json-draft
     */
    public function json_draft(Request $request)
    {
        try {
            $user = Session::get('user');
            $kd_prodi = $user->kd_prodi;
            $nim = $user->nim;
            $length = intval($request->length ?? 10);
            $start = intval($request->start ?? 0);
            $search = isset($request->search['value']) ? $request->search['value'] : '';

            $data_ = KRS::get_daftar($kd_prodi, 1, $nim, $search, $start, $length);

            $data = [
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => $data_,
                'error' => null,
            ];

            if (count($data_) > 0) {
                $data['recordsTotal'] = isset($data_[0]->jml_record) ? $data_[0]->jml_record : count($data_);
                $data['recordsFiltered'] = $data['recordsTotal'];
            }

            return response()->json($data, 200);

        } catch (\Exception $e) {
            return response()->json([
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * JSON DataTable riwayat KRS semua tahun akademik (server-side)
     * Endpoint: POST /mhs/krs/riwayat/json
     */
    public function json_riwayat(Request $request)
    {
        try {
            $user            = Session::get('user');
            $nim             = $user->nim;
            $length          = intval($request->length          ?? 10);
            $start           = intval($request->start           ?? 0);
            $tahun_akademik  = $request->tahun_akademik         ?? '';

            // Ambil data riwayat berdasarkan tahun_akademik yang dipilih
            $rawData = KRS::get_riwayat_krs_mahasiswa($nim, $tahun_akademik, '', $start, $length);

            $recordsTotal = 0;
            if (!empty($rawData)) {
                $recordsTotal = isset($rawData[0]->jml_record)
                    ? intval($rawData[0]->jml_record)
                    : count($rawData);
            }

            // Transform data untuk DataTable
            $data = [];
            foreach ($rawData as $item) {
                $data[] = [
                    'id_krs'              => isset($item->id_krs)              ? $item->id_krs              : null,
                    'tahun_akademik'      => isset($item->tahun_akademik)      ? $item->tahun_akademik      : null,
                    'nama_tahun_akademik' => isset($item->nama_tahun_akademik) ? $item->nama_tahun_akademik : '-',
                    'semester'            => isset($item->semester)            ? $item->semester            : '-',
                    'ips'                 => isset($item->ips)                 ? $item->ips                 : null,
                    'ipk'                 => isset($item->ipk)                 ? $item->ipk                 : null,
                    'sks_maks'            => intval(isset($item->sks_maks)     ? $item->sks_maks            : 0),
                    'sks_ditempuh'        => intval(isset($item->sks_ditempuh) ? $item->sks_ditempuh        : 0),
                    'jml_matkul'          => intval(isset($item->jml_matkul)   ? $item->jml_matkul          : 0),
                    'status_krs'          => intval(isset($item->status_krs)   ? $item->status_krs          : 0),
                    'tgl_pengajuan'       => isset($item->tgl_pengajuan_krs)   ? $item->tgl_pengajuan_krs   : null,
                ];
            }

            // Hitung summary — ambil semua data tanpa limit untuk akurasi
            $allData = KRS::get_riwayat_krs_mahasiswa($nim, '', 0, 999999);
            $summary = $this->hitungSummaryRiwayat($allData);
            dd($allData, $summary);

            return response()->json([
                'draw'            => intval($request->draw ?? 1),
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'data'            => $data,
                'summary'         => $summary,
                'error'           => null,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'draw'            => intval($request->draw ?? 1),
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
                'summary'         => null,
                'error'           => $e->getMessage(),
            ], 500);
        }
    }

    // =====================================================================
    // AKSI KRS
    // =====================================================================

    /**
     * Simpan draft KRS
     * Endpoint: POST /mhs/krs/simpan
     */
    public function store(Request $request)
    {
        $request->validate([
            'krs_data' => 'required',
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

    /**
     * Ajukan KRS ke DPS
     * Endpoint: POST /mhs/krs/ajukan-krs
     */
    public function ajukan_krs(Request $request)
    {
        $request->validate([
            'id_krs_list' => 'required',
        ]);

        $data = KRS::update_status_krs($request->id_krs_list, 1);

        return response()->json($data);
    }

    /**
     * Cek SKS maksimal mahasiswa
     * Endpoint: POST /mhs/krs/sks-maksimal
     */
    public function cekMaksimalKrs()
    {
        $nim = Session::get('user')->nim;
        $sks = KRS::getSKSMaks($nim);
        return response()->json($sks, 200);
    }

    // =====================================================================
    // DOWNLOAD KRS (PDF)
    // =====================================================================

    /**
     * Download KRS mahasiswa sebagai PDF
     * Bisa dipanggil dari halaman proses KRS maupun riwayat KRS
     * Endpoint: POST /mhs/krs/download-krs
     *
     * Jika ada parameter id_krs   → download KRS dari riwayat (spesifik)
     * Jika tidak ada parameter id_krs → download KRS aktif saat ini
     */
    public function downloadKRS(Request $request)
    {
        try {
            $user = Session::get('user');

            if (!$user) {
                return response()->json([
                    'status' => '0',
                    'keterangan' => 'Session user tidak ditemukan',
                ], 401);
            }

            $kd_prodi = $user->kd_prodi;
            $nim = isset($user->nim) ? $user->nim : $request->nim;
            $ta = isset($request->tahun_akademik) ? $request->tahun_akademik : 1;
            $krsData = KRS::get_daftar($kd_prodi, 1, $nim);

            if (empty($krsData) || count($krsData) == 0) {
                return response()->json([
                    'status' => '0',
                    'keterangan' => 'Data KRS tidak ditemukan atau belum disetujui',
                ], 404);
            }

            $firstRecord = $krsData[0];

            // Pastikan status sudah disetujui final (status = 4)
            if (intval(isset($firstRecord->status_krs) ? $firstRecord->status_krs : 0) !== 4) {
                return response()->json([
                    'status' => '0',
                    'keterangan' => 'KRS belum mendapatkan persetujuan final',
                ], 403);
            }

            // Parse tahun akademik
            $tahunAkademikParsed = $this->parseTahunAkademik($firstRecord->tahun_akademik);

            // Data mahasiswa
            $mahasiswaData = (object)[
                'nim' => $nim,
                'nama_mahasiswa' => isset($user->nama_lengkap) ? $user->nama_lengkap : 'Nama Mahasiswa',
                'angkatan' => $this->getAngkatanFromNIM($nim),
                'nama_prodi' => isset($user->nama_prodi) ? $user->nama_prodi : '-',
                'jenjang' => isset($user->jenjang) ? $user->jenjang : 'S1',
                'nama_dps' => isset($firstRecord->nama_dps) ? $firstRecord->nama_dps : 'Dosen PA',
                'nidn' => isset($firstRecord->nidn_dps) ? $firstRecord->nidn_dps : '-',
                'nama_kaprodi' => isset($firstRecord->nama_kaprodi) ? $firstRecord->nama_kaprodi : 'Ketua Program Studi',
                'nidn_kaprodi' => isset($firstRecord->nidn_kaprodi) ? $firstRecord->nidn_kaprodi : '-',
                'id_krs' => isset($firstRecord->qr_mahasiswa) ? $firstRecord->qr_mahasiswa : '-',
                'id_persetujuan_dps' => isset($firstRecord->qr_dps) ? $firstRecord->qr_dps : '-',
                'id_persetujuan_kaprodi' => isset($firstRecord->qr_kaprodi) ? $firstRecord->qr_kaprodi : '-',
            ];

            // Data tahun akademik
            $tahunAkademikAktif = (object)[
                'id' => 1,
                'nama' => $tahunAkademikParsed['nama'],
                'semester' => $tahunAkademikParsed['semester'],
                'is_active' => 1,
            ];

            // Mapping hari
            $hariNames = [
                0 => '-', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
                4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu',
            ];

            // Transform daftar mata kuliah
            $mataKuliah = [];
            foreach ($krsData as $mk) {
                if (!$mk->is_diambil && $mk->id_krs_mahasiswa === null) {
                    continue;
                }
                $mataKuliah[] = [
                    'kd_mata_kuliah' => isset($mk->kd_mata_kuliah) ? $mk->kd_mata_kuliah : '-',
                    'nama_mata_kuliah' => isset($mk->nama_mata_kuliah) ? $mk->nama_mata_kuliah : '-',
                    'sks' => isset($mk->sks) ? $mk->sks : 0,
                    'nama_kelas' => isset($mk->nama_kelas) ? $mk->nama_kelas : '-',
                    'hari' => isset($hariNames[$mk->hari]) ? $hariNames[$mk->hari] : '-',
                    'jam' => $this->formatJam($mk->jam_mulai, $mk->jam_selesai),
                    'ruang' => isset($mk->ruang) ? $mk->ruang : '-',
                    'nama_dosen' => isset($mk->nama_dosen) ? $mk->nama_dosen : '-',
                ];
            }

            if (count($mataKuliah) == 0) {
                return response()->json([
                    'status' => '0',
                    'keterangan' => 'Tidak ada mata kuliah yang diambil',
                ], 404);
            }

            $totalSKS = array_sum(array_column($mataKuliah, 'sks'));
            $totalMK = count($mataKuliah);

            // Komentar
            $komentarDPS = isset($firstRecord->komentar_dps) ? $firstRecord->komentar_dps : '-';
            $komentarKaprodi = isset($firstRecord->komentar_kaprodi) ? $firstRecord->komentar_kaprodi : '-';

            // Tanggal-tanggal
            $tglPengajuan = isset($firstRecord->tgl_pengajuan_krs) && $firstRecord->tgl_pengajuan_krs
                ? Carbon::parse($firstRecord->tgl_pengajuan_krs)->format('d/m/Y')
                : Carbon::now()->subDays(12)->format('d/m/Y');

            $tglVerifikasiDPS = isset($firstRecord->tgl_persetujuan_dps) && $firstRecord->tgl_persetujuan_dps
                ? Carbon::parse($firstRecord->tgl_persetujuan_dps)->format('d/m/Y')
                : Carbon::now()->subDays(8)->format('d/m/Y');

            $tglVerifikasiKaprodi = isset($firstRecord->tgl_persetujuan_kaprodi) && $firstRecord->tgl_persetujuan_kaprodi
                ? Carbon::parse($firstRecord->tgl_persetujuan_kaprodi)->format('d/m/Y')
                : Carbon::now()->subDays(5)->format('d/m/Y');

            $tanggalCetak = Carbon::now()
                ->timezone('Asia/Jakarta')
                ->locale('id')
                ->isoFormat('D MMMM YYYY HH:mm');

            // Generate QR Code
            $qrMahasiswa = base64_encode(
                QrCode::format('svg')->size(200)->margin(1)->errorCorrection('H')
                    ->generate(route('frontpage.detail_qr', [
                        'id' => base64_encode($mahasiswaData->id_krs)
                    ]))
            );

            $qrDPS = base64_encode(
                QrCode::format('svg')->size(200)->margin(1)->errorCorrection('H')
                    ->generate(route('frontpage.detail_qr', [
                        'id' => base64_encode($mahasiswaData->id_persetujuan_dps)
                    ]))
            );

            $qrKaprodi = base64_encode(
                QrCode::format('svg')->size(200)->margin(1)->errorCorrection('H')
                    ->generate(route('frontpage.detail_qr', [
                        'id' => base64_encode($mahasiswaData->id_persetujuan_kaprodi)
                    ]))
            );

            // Logo
            $logoPath = public_path('image/logo-uij.png');
            $logoBase64 = file_exists($logoPath)
                ? base64_encode(file_get_contents($logoPath))
                : '';

            // Data untuk view PDF
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
                'logo' => $logoBase64,
            ];

            // Generate PDF
            $pdf = Facade::loadView('mahasiswa_page.akademik.krs.pdf', $data);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
                'enable_php' => false,
            ]);

            $filename = 'KRS_'
                . $mahasiswaData->nim
                . '_' . str_replace('/', '-', $tahunAkademikAktif->nama)
                . '_' . $tahunAkademikAktif->semester
                . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return response()->json([
                'status' => '0',
                'keterangan' => 'Terjadi kesalahan saat generate PDF: ' . $e->getMessage(),
            ], 500);
        }
    }

    // =====================================================================
    // PRIVATE HELPERS
    // =====================================================================

    /**
     * Hitung summary cards dari seluruh data riwayat
     */
    private function hitungSummaryRiwayat(array $allData)
    {
        if (empty($allData)) {
            return [
                'total_semester' => 0,
                'ipk_terakhir' => '0.00',
                'total_sks_lulus' => 0,
                'total_mk' => 0,
            ];
        }

        // IPK terakhir = dari record pertama (sudah di-order desc di query)
        $ipkTerakhir = floatval(isset($allData[0]->ipk) ? $allData[0]->ipk : 0);

        // Total SKS lulus semua semester
        $totalSksLulus = 0;
        foreach ($allData as $item) {
            $totalSksLulus += intval(isset($item->sks_ditempuh) ? $item->sks_ditempuh : 0);
        }

        // Total MK semua semester
        $totalMK = 0;
        foreach ($allData as $item) {
            $totalMK += intval(isset($item->jml_matkul) ? $item->jml_matkul : 0);
        }

        return [
            'total_semester' => count($allData),
            'ipk_terakhir' => number_format($ipkTerakhir, 2),
            'total_sks_lulus' => $totalSksLulus,
            'total_mk' => $totalMK,
        ];
    }

    /**
     * Parse tahun akademik dari format "20251"
     * Contoh: "20251" → ['nama' => '2025/2026', 'semester' => 'Ganjil']
     */
    private function parseTahunAkademik($tahunAkademik)
    {
        $result = ['nama' => '2024/2025', 'semester' => 'Ganjil'];

        if (empty($tahunAkademik) || strlen((string)$tahunAkademik) != 5) {
            return $result;
        }

        $tahun = substr((string)$tahunAkademik, 0, 4);
        $semesterCode = substr((string)$tahunAkademik, 4, 1);
        $tahunInt = intval($tahun);
        $namaTahun = $tahunInt . '/' . ($tahunInt + 1);

        // PHP 7.4 tidak support match expression, gunakan switch
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

        return ['nama' => $namaTahun, 'semester' => $namaSemester];
    }

    /**
     * Format jam dari "08:00:00" menjadi "08:00-10:00"
     */
    private function formatJam($jam_mulai, $jam_selesai)
    {
        if (!$jam_mulai || !$jam_selesai) {
            return '-';
        }
        return substr($jam_mulai, 0, 5) . '-' . substr($jam_selesai, 0, 5);
    }

    /**
     * Parse angkatan dari 2 digit pertama NIM
     * Contoh: NIM "2501xxx" → "2025"
     */
    private function getAngkatanFromNIM($nim)
    {
        if (empty($nim) || strlen($nim) < 2) {
            return date('Y');
        }

        $tahunInt = intval(substr($nim, 0, 2));
        $angkatan = $tahunInt <= 50 ? 2000 + $tahunInt : 1900 + $tahunInt;

        return (string)$angkatan;
    }
}
