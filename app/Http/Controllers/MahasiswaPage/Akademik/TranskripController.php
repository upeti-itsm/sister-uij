<?php

namespace App\Http\Controllers\MahasiswaPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Akademik\PengajuanTranskrip;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TranskripController extends Controller
{
    /**
     * Halaman utama Pengajuan Transkrip
     */
    public function index()
    {
        $menu = "Pengajuan Transkrip Nilai";
        return view('mahasiswa_page.akademik.transkrip.index', compact('menu'));
    }

    // ============================================================
    // DATATABLE
    // ============================================================

    /**
     * Get data pengajuan transkrip untuk DataTable (Server-side)
     */
    public function json(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'draw'            => intval($request->draw ?? 1),
                    'recordsTotal'    => 0,
                    'recordsFiltered' => 0,
                    'data'            => [],
                    'error'           => 'Session user tidak ditemukan'
                ], 401);
            }

            $nim      = $user->nim;
            $kd_prodi = $user->id_personal;
            $status   = $request->status ?? null;
            $tahun    = $request->tahun  ?? null;
            $search   = $request->search['value'] ?? $request->search ?? '';
            $start    = $request->start  ?? 0;
            $length   = $request->length ?? 10;

            $data_ = PengajuanTranskrip::get_daftar_pengajuan(
                $status, $tahun, $nim, $kd_prodi, $search, $start, $length
            );

            $data = [
                'draw'            => intval($request->draw ?? 1),
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => $data_,
                'error'           => null
            ];

            if (count($data_) > 0) {
                $data['recordsTotal']    = $data_[0]->jml_record ?? count($data_);
                $data['recordsFiltered'] = $data['recordsTotal'];
            }

            return response()->json($data, 200);

        } catch (\Exception $e) {
            return response()->json([
                'draw'            => intval($request->draw ?? 1),
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
                'error'           => $e->getMessage()
            ], 500);
        }
    }

    // ============================================================
    // STATISTIK & INFO
    // ============================================================

    /**
     * Get statistik pengajuan transkrip (untuk stat cards)
     */
    public function getStatistik(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Session user tidak ditemukan'
                ], 401);
            }

            $nim  = $user->nim;
            $data = PengajuanTranskrip::get_statistik($nim);

            if ($data) {
                return response()->json([
                    'total_pengajuan' => $data->total_pengajuan ?? 0,
                    'diproses'        => $data->sedang_diproses        ?? 0,
                    'disetujui'       => $data->disetujui       ?? 0,
                    'ditolak'         => $data->ditolak         ?? 0
                ], 200);
            }

            return response()->json([
                'total_pengajuan' => 0,
                'diproses'        => 0,
                'disetujui'       => 0,
                'ditolak'         => 0
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'     => '0',
                'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get info mahasiswa untuk form pengajuan
     * (NIM, nama, prodi, IPK)
     */
    public function getMahasiswaInfo(Request $request)
{
    try {
        $user = Session::get('user');
        if (!$user) {
            return response()->json([
                'status'     => '0',
                'keterangan' => 'Session user tidak ditemukan'
            ], 401);
        }

        $nim = $user->nim ?? '';
        $ipk = 0.00;

        if ($nim !== '') {
            try {
                $info = PengajuanTranskrip::get_info_mahasiswa($nim);
                $ipk  = $info ? ($info->ipk ?? 0.00) : 0.00;
            } catch (\Exception $e) {
                $ipk = 0.00;
            }
        }

        return response()->json([
            'nim'        => $nim !== '' ? $nim : '-',
            'nama'       => $user->nama_lengkap ?? '-',
            'nama_prodi' => $user->nama_prodi   ?? '-',
            'ipk'        => $ipk
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status'     => '0',
            'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

    // ============================================================
    // CRUD PENGAJUAN
    // ============================================================

    /**
     * Ajukan transkrip baru
     */
    public function ajukan(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Session user tidak ditemukan'
                ], 401);
            }

            // --- Validasi ---
            $validated = $this->validatePengajuan($request);
            if ($validated !== true) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => $validated
                ], 422);
            }

            $nim          = $user->nim;
            $keperluan    = $request->keperluan;

            // --- Simpan ke DB ---
            $result = PengajuanTranskrip::buat_pengajuan(
                $nim,
                $keperluan
            );

            if ($result && $result->status === true) {
                return response()->json([
                    'status'       => '1',
                    'keterangan'   => $result->keterangan ?? 'Pengajuan transkrip berhasil dikirim',
                    'no_pengajuan' => $result->no_pengajuan ?? '-'
                ], 200);
            }


            return response()->json([
                'status'     => '0',
                'keterangan' => $result->keterangan ?? 'Gagal menyimpan pengajuan'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'status'     => '0',
                'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detail satu pengajuan (untuk modal detail)
     */
    public function getDetail(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Session user tidak ditemukan'
                ], 401);
            }

            $idPengajuan = $request->id_pengajuan_induk ?? $request->id_riwayat_pengajuan_nilai;
            if (!$idPengajuan) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'ID pengajuan tidak valid'
                ], 422);
            }

            $detail = PengajuanTranskrip::get_detail($idPengajuan);
            if (!$detail) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Data pengajuan tidak ditemukan'
                ], 404);
            }

            // --- Riwayat aktivitas ---
            $riwayat = $detail->riwayat_ajuan;

            return response()->json([
                'status' => '1',
                'data'   => [
                    'id_pengajuan_induk' => $detail->id_pengajuan_induk ?? $idPengajuan,
                    'id_riwayat_pengajuan_nilai'   => $detail->id_riwayat_pengajuan_nilai,
                    'nomor_pengajuan'   => $detail->nomor_pengajuan ?? '-',
                    'keperluan'      => $detail->keperluan,
                    'status'         => $detail->status,
                    'alasan_tolak'   => $detail->alasan_tolak  ?? null,
                    'tgl_pengajuan'  => $detail->tgl_created,
                    'tgl_kaprodi'    => $detail->tgl_kaprodi   ?? null,
                    'tgl_dekan'      => $detail->tgl_dekan     ?? null,
                    'tgl_selesai'    => $detail->tgl_selesai   ?? null,
                    'riwayat'        => $riwayat
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'     => '0',
                'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function ajukan_draft(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Session user tidak ditemukan'
                ], 401);
            }

            $idPengajuan = $request->id_riwayat_pengajuan_nilai;
            if (!$idPengajuan) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'ID pengajuan tidak valid'
                ], 422);
            }
            $result = PengajuanTranskrip::ajukan_draft($idPengajuan);
            if ($result && $result->status === 1) {
                return response()->json([
                    'status'     => '1',
                    'keterangan' => $result->keterangan ?? 'Pengajuan transkrip berhasil dibatalkan'
                ], 200);
            }

            return response()->json([
                'status'     => '0',
                'keterangan' => $result->keterangan ?? 'Gagal membatalkan pengajuan'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'     => '0',
                'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function hapus_draft(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Session user tidak ditemukan'
                ], 401);
            }

            $idPengajuan = $request->id_riwayat_pengajuan_nilai;
            if (!$idPengajuan) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'ID pengajuan tidak valid'
                ], 422);
            }
            $result = PengajuanTranskrip::hapus_draft($idPengajuan);
            if ($result && $result->status === 1) {
                return response()->json([
                    'status'     => '1',
                    'keterangan' => $result->keterangan ?? 'Pengajuan transkrip berhasil dibatalkan'
                ], 200);
            }

            return response()->json([
                'status'     => '0',
                'keterangan' => $result->keterangan ?? 'Gagal membatalkan pengajuan'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'     => '0',
                'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Batalkan pengajuan transkrip
     */
    public function batalkan(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Session user tidak ditemukan'
                ], 401);
            }

            $idPengajuan = $request->id_riwayat_pengajuan_nilai;
            if (!$idPengajuan) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'ID pengajuan tidak valid'
                ], 422);
            }
            $result = PengajuanTranskrip::batalkan_pengajuan($idPengajuan);
            if ($result && $result->status === 1) {
                return response()->json([
                    'status'     => '1',
                    'keterangan' => $result->keterangan ?? 'Pengajuan transkrip berhasil dibatalkan'
                ], 200);
            }

            return response()->json([
                'status'     => '0',
                'keterangan' => $result->keterangan ?? 'Gagal membatalkan pengajuan'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'     => '0',
                'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============================================================
    // DOWNLOAD PDF
    // ============================================================

    /**
     * Download transkrip dalam format PDF
     */
    public function download(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Session user tidak ditemukan'
                ], 401);
            }

            $idPengajuanInduk = $request->id_pengajuan_induk ?? $request->id_riwayat_pengajuan_nilai;
            if (!$idPengajuanInduk) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'ID pengajuan tidak valid'
                ], 422);
            }

            // --- Validasi pengajuan milik mahasiswa ini & sudah disetujui ---
            $detail = PengajuanTranskrip::get_detail($idPengajuanInduk);

            // Jika request awal masih id_riwayat, ambil ulang memakai id_pengajuan_induk.
            if ($detail && !empty($detail->id_pengajuan_induk ?? null)
                && (string) $detail->id_pengajuan_induk !== (string) $idPengajuanInduk) {
                $idPengajuanInduk = $detail->id_pengajuan_induk;
                $detailByInduk = PengajuanTranskrip::get_detail($idPengajuanInduk);
                if ($detailByInduk) {
                    $detail = $detailByInduk;
                }
            }

            if (!$detail) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Pengajuan tidak ditemukan'
                ], 404);
            }

            if (isset($detail->nim) && (string) $detail->nim !== (string) $user->nim) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Pengajuan tidak ditemukan'
                ], 404);
            }

            // Validasi jika status belum disetujui
            if ($detail->keterangan_status !== 'Disetujui' && $detail->status !== '5') {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Transkrip hanya dapat diunduh jika pengajuan sudah disetujui'
                ], 422);
            }

            $nim = $user->nim;

            // --- Get data transkrip lengkap ---
            $nilaiList = PengajuanTranskrip::get_nilai_transkrip($nim);

            if (empty($nilaiList)) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Data nilai transkrip tidak ditemukan'
                ], 404);
            }

            // --- Data mahasiswa ---
            $mahasiswaData = (object) [
                'nim'             => $nim,
                'nama_mahasiswa'  => $user->nama_lengkap ?? 'Nama Mahasiswa',
                'angkatan'        => $this->getAngkatanFromNIM($nim) ?? '-',
                'nama_prodi' => $user->kd_fakultas?? '-',
                'kd_fakultas' => $user->nama_prodi ?? '-',
                'ttl'    => $user->ttl  ?? '-',
                'jenjang'         => $user->jenjang      ?? 'S1',
            ];

            // dd($mahasiswaData);
            // --- Transform data nilai per semester ---
            $nilaiPerSemester = $this->groupNilaiPerSemester($nilaiList);

            // --- Hitung rekap ---
            $rekap = $this->hitungRekapTranskrip($nilaiList);

            // --- QR Code Section ---

            // 1. QR Code Prodi
            $qrIdProdi = $detail->id_tandatangan_dokumen_kaprodi ?? $idPengajuanInduk;
            $qrCodeProdi = base64_encode(
                QrCode::format('svg')->size(200)->margin(1)->errorCorrection('H')
                    ->generate(route('frontpage.detail_qr', ['id' => base64_encode($qrIdProdi)]))
            );

            // 2. QR Code Dekan
            $qrIdDekan = $detail->id_tandatangan_dokumen_dekan ?? $idPengajuanInduk;
            $qrCodeDekan = base64_encode(
                QrCode::format('svg')->size(200)->margin(1)->errorCorrection('H')
                    ->generate(route('frontpage.detail_qr', ['id' => base64_encode($qrIdDekan)]))
            );

            // 3. QR Code Dokumen
            $qrIdDoc = $detail->nomor_dokumen ?? $idPengajuanInduk;
            $qrCodeDoc = base64_encode(
                QrCode::format('svg')->size(200)->margin(1)->errorCorrection('H')
                    ->generate(route('frontpage.detail_qr', ['id' => base64_encode($qrIdDoc)]))
            );

            // 4. QR Default (Sesuai dengan pdf.blade.php UIJ yang sekarang)
            $qrCode = base64_encode(
                QrCode::format('svg')->size(200)->margin(1)->errorCorrection('H')
                    ->generate(route('frontpage.detail_qr', ['id' => base64_encode($idPengajuanInduk)]))
            );

            // --- Logo ---
            $logoPath   = public_path('image/logo-uij.png');
            $logoBase64 = '';
            if (file_exists($logoPath)) {
                $logoBase64 = base64_encode(file_get_contents($logoPath));
            }

            // --- Tanggal cetak ---
            $tanggalCetak = Carbon::now()
                ->timezone('Asia/Jakarta')
                ->locale('id')
                ->isoFormat('D MMMM YYYY HH:mm');

            // --- Data untuk PDF ---
            $data = [
                'mahasiswa'         => $mahasiswaData,
                'pengajuan'         => $detail,
                'nilai_per_semester'=> $nilaiPerSemester,
                'total_sks'         => $rekap['total_sks'],
                'sks_lulus'         => $rekap['sks_lulus'],
                'total_mk'          => $rekap['total_mk'],
                'ipk'               => number_format($rekap['ipk'], 2),
                'tanggal_cetak'     => $tanggalCetak,

                // Variabel QR Code dikirim semua agar aman & fleksibel
                'qr_code'           => $qrCode,
                'qr_code_prodi'     => $qrCodeProdi,
                'qr_code_dekan'     => $qrCodeDekan,
                'qr_code_doc'       => $qrCodeDoc,

                'logo'              => $logoBase64
            ];

            // --- Generate PDF ---
            $pdf = Facade::loadView('mahasiswa_page.akademik.transkrip.pdf', $data);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
                'defaultFont'          => 'sans-serif',
                'enable_php'           => false
            ]);

            $filename = 'Transkrip_' . $mahasiswaData->nim . '_' . date('Ymd') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return response()->json([
                'status'     => '0',
                'keterangan' => 'Terjadi kesalahan saat generate PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    /**
     * Validasi input pengajuan transkrip
     *
     * @return true|string  true jika valid, string pesan error jika tidak
     */
    private function validatePengajuan(Request $request)
    {
        $keperluan    = $request->keperluan;

        $keperluanValid = [
            'Melamar Pekerjaan',
            'Beasiswa',
            'Melanjutkan Studi',
            'Keperluan Pribadi',
            'Keperluan Institusi',
            'Lainnya'
        ];

        if (empty($keperluan) || !in_array($keperluan, $keperluanValid)) {
            return 'Keperluan tidak valid';
        }
        return true;
    }

    /**
     * Group data nilai per semester untuk tampilan transkrip
     */
    private function groupNilaiPerSemester(array $nilaiList): array
    {
        $grouped = [];

        foreach ($nilaiList as $item) {
            $key = $item->tahun_akademik ?? 'unknown';

            if (!isset($grouped[$key])) {
                $parsed          = $this->parseTahunAkademik($key);
                $grouped[$key]   = [
                    'tahun_akademik' => $key,
                    'nama'           => $parsed['nama'],
                    'semester'       => $parsed['semester'],
                    'mata_kuliah'    => [],
                    'total_sks'      => 0,
                    'total_bobot'    => 0,
                    'ips'            => 0
                ];
            }

            $sks   = intval($item->sks   ?? 0);
            $bobot = floatval($item->bobot ?? 0);

            $grouped[$key]['mata_kuliah'][] = [
                'kd_mata_kuliah'   => $item->kd_mata_kuliah  ?? '-',
                'nama_matakuliah' => $item->nama_matakuliah ?? '-',
                'sks'              => $sks,
                'nilai_angka'      => $item->nilai_angka
                    ? number_format($item->nilai_angka, 2)
                    : '-',
                'nilai_huruf'      => $item->nilai_huruf      ?? '-',
                'bobot'            => number_format($bobot, 2),
                'sts_nilai'        => $item->sts_nilai         ?? '-'
            ];

            $grouped[$key]['total_sks']   += $sks;
            $grouped[$key]['total_bobot'] += ($bobot * $sks);
        }

        // Hitung IPS per semester
        foreach ($grouped as $key => $sem) {
            $ips = $sem['total_sks'] > 0
                ? ($sem['total_bobot'] / $sem['total_sks'])
                : 0;
            $grouped[$key]['ips'] = number_format($ips, 2);
        }

        // Urutkan ascending berdasarkan tahun_akademik
        ksort($grouped);

        return array_values($grouped);
    }

    /**
     * Hitung rekap keseluruhan transkrip
     */
    private function hitungRekapTranskrip(array $nilaiList): array
    {
        $totalSks   = 0;
        $sksLulus   = 0;
        $totalMk    = count($nilaiList);
        $totalBobot = 0;

        foreach ($nilaiList as $item) {
            $sks   = intval($item->sks   ?? 0);
            $bobot = floatval($item->bobot ?? 0);

            $totalSks   += $sks;
            $totalBobot += ($bobot * $sks);

            $sts = strtoupper($item->sts_nilai ?? '');
            if ($sts === 'LULUS') {
                $sksLulus += $sks;
            }
        }

        $ipk = $totalSks > 0 ? ($totalBobot / $totalSks) : 0;

        return [
            'total_sks'   => $totalSks,
            'sks_lulus'   => $sksLulus,
            'total_mk'    => $totalMk,
            'total_bobot' => round($totalBobot, 2),
            'ipk'         => round($ipk, 2)
        ];
    }

    /**
     * Helper: Parse tahun akademik dari format database
     * Format input: "20251" -> Output: ['nama' => '2025/2026', 'semester' => 'Ganjil']
     */
    private function parseTahunAkademik($tahunAkademik): array
    {
        $result = [
            'nama'     => '2024/2025',
            'semester' => 'Ganjil'
        ];

        if (empty($tahunAkademik) || strlen($tahunAkademik) != 5) {
            return $result;
        }

        $tahun          = substr($tahunAkademik, 0, 4);
        $semesterCode   = substr($tahunAkademik, 4, 1);

        $tahunInt       = intval($tahun);
        $namaTahun      = $tahunInt . '/' . ($tahunInt + 1);

        $semesterMap = [
            '1' => 'Ganjil',
            '2' => 'Genap',
            '3' => 'Antara'
        ];

        return [
            'nama'     => $namaTahun,
            'semester' => $semesterMap[$semesterCode] ?? 'Ganjil'
        ];
    }

    /**
     * Helper: Get angkatan dari NIM
     */
    private function getAngkatanFromNIM($nim): string
    {
        $defaultAngkatan = (string) date('Y');

        if (empty($nim) || strlen($nim) < 2) {
            return $defaultAngkatan;
        }

        $tahunDuaDigit = substr($nim, 0, 2);
        $tahunInt      = intval($tahunDuaDigit);

        $angkatan = ($tahunInt >= 0 && $tahunInt <= 50)
            ? 2000 + $tahunInt
            : 1900 + $tahunInt;

        return (string) $angkatan;
    }
}
