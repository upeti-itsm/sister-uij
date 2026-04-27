<?php

namespace App\Http\Controllers\DekanPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Akademik\PengajuanTranskrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TranskripDekanController extends Controller
{
    /**
     * Halaman utama pengesahan transkrip (Dekan)
     */
    public function index()
    {
        $menu = "Persetujuan Transkrip Nilai";
        return view('dekan_page.akademik.transkrip.index', compact('menu'));
    }

    // ============================================================
    // DATATABLE
    // ============================================================

    /**
     * Get data pengajuan transkrip untuk DataTable (Server-side)
     * Scope: hanya pengajuan yang sudah disetujui kaprodi (proses_dekan)
     *        atau seluruh status untuk monitoring
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

            $status     = $request->status   ?? null;
            $tahun      = $request->tahun    ?? null;
            $prodi      = $user->id_personal   ?? null;
            $search     = $request->search['value'] ?? $request->search ?? '';
            $start      = $request->start    ?? 0;
            $length     = $request->length   ?? 10;

            $data_ = PengajuanTranskrip::get_daftar_pengajuan_dekan(
                $status, $tahun, $prodi, $search, $start, $length
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
     * Get statistik pengajuan transkrip untuk dekan
     * (menunggu, disahkan, ditolak, total)
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

            $idFakultas = $user->id_personal ?? null;
            $data       = PengajuanTranskrip::get_statistik_dekan($idFakultas);

            if ($data) {
                return response()->json([
                    'menunggu' => $data->sedang_diproses ?? 0,
                    'disahkan' => $data->disahkan ?? 0,
                    'ditolak'  => $data->ditolak  ?? 0,
                    'total'    => $data->total_pengajuan     ?? 0
                ], 200);
            }

            return response()->json([
                'menunggu' => 0,
                'disahkan' => 0,
                'ditolak'  => 0,
                'total'    => 0
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'     => '0',
                'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get daftar prodi dalam fakultas dekan
     * (untuk dropdown filter)
     */
    public function getProdiList(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Session user tidak ditemukan'
                ], 401);
            }

            $idFakultas = $user->id_fakultas ?? null;
            $data       = PengajuanTranskrip::get_prodi_list_dekan($idFakultas);

            $result = [];
            foreach ($data as $item) {
                $result[] = [
                    'id'   => $item->id_prodi,
                    'nama' => $item->nama_prodi
                ];
            }

            return response()->json($result, 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'     => '0',
                'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============================================================
    // DETAIL & PREVIEW
    // ============================================================

    /**
     * Get detail satu pengajuan transkrip
     * Dilengkapi info mahasiswa, info persetujuan kaprodi, dan riwayat aktivitas
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

            $idPengajuan = $request->id_riwayat_pengajuan_nilai;
            if (!$idPengajuan) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'ID pengajuan tidak valid'
                ], 422);
            }

            $detail = PengajuanTranskrip::get_detail($idPengajuan);
//            dd($detail);
            if (!$detail) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Data pengajuan tidak ditemukan atau bukan wewenang Anda'
                ], 404);
            }

            $ipk = floatval($detail->ipk ?? 0);
            if ($ipk <= 0 && !empty($detail->nim)) {
                try {
                    $infoMhs = PengajuanTranskrip::get_info_mahasiswa($detail->nim);
                    $ipk = floatval($infoMhs->ipk ?? 0);
                } catch (\Exception $e) {
                    $ipk = 0;
                }
            }

            return response()->json([
                'status' => '1',
                'data'   => [
                    'id_riwayat_pengajuan'     => $detail->id_riwayat_pengajuan_nilai,
                    'nomor_pengajuan'     => $detail->nomor_pengajuan,
                    'nim'              => $detail->nim,
                    'nama_mahasiswa'   => $detail->nama_mahasiswa   ?? '-',
                    'nama_prodi'       => $detail->nama_prodi       ?? '-',
                    'angkatan'         => $detail->angkatan         ?? '-',
                    'ipk'              => $ipk,
                    // Info persetujuan kaprodi
                    'nama_kaprodi'     => $detail->nama_kaprodi     ?? '-',
                    'tgl_kaprodi'      => $detail->tgl_kaprodi      ?? null,
                    'catatan_kaprodi'  => $detail->catatan_kaprodi  ?? '-',
                    // Detail pengajuan
                    'keperluan'        => $detail->keperluan,
                    'status'           => $detail->status,
                    'alasan_tolak'     => $detail->alasan_tolak     ?? null,
                    'tgl_pengajuan'    => $detail->tgl_created,
                    'tgl_dekan'        => $detail->tgl_dekan        ?? null,
                    'tgl_selesai'      => $detail->tgl_selesai      ?? null,
                    'riwayat'          => $detail->riwayat_ajuan          ?? null,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'     => '0',
                'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get preview nilai mahasiswa
     * (ditampilkan di modal detail sebelum dekan memutuskan)
     */
    public function getPreviewNilai(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Session user tidak ditemukan'
                ], 401);
            }

            $nim = $request->nim;
            if (!$nim) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'NIM tidak valid'
                ], 422);
            }

            // Validasi: mahasiswa harus dari fakultas yang dipimpin dekan ini
            $kdFakultas   = $user->kd_fakultas ?? null;
            $isMahasiswa  = PengajuanTranskrip::cek_mahasiswa_fakultas($nim, $kdFakultas);

            if (!$isMahasiswa) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Mahasiswa tidak terdaftar di fakultas Anda'
                ], 403);
            }

            $data = PengajuanTranskrip::get_nilai_transkrip($nim);

            return response()->json([
                'status' => '1',
                'data'   => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'     => '0',
                'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============================================================
    // TINDAKAN DEKAN
    // ============================================================

    /**
     * Sahkan pengajuan transkrip
     * Status berubah: proses_dekan -> disetujui
     */
    public function sahkan(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Session user tidak ditemukan'
                ], 401);
            }

            $idPengajuan = $request->id_riwayat_pengajuan;
            if (!$idPengajuan) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'ID pengajuan tidak valid'
                ], 422);
            }

            $catatan    = $request->catatan  ?? null;
            $result = PengajuanTranskrip::sahkan_dekan($idPengajuan, $catatan);

            if ($result && $result->status === 1) {
                return response()->json([
                    'status'     => '1',
                    'keterangan' => $result->keterangan ?? 'Transkrip berhasil disahkan'
                ], 200);
            }

            return response()->json([
                'status'     => '0',
                'keterangan' => $result->keterangan ?? 'Gagal mengesahkan transkrip'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'     => '0',
                'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tolak pengajuan transkrip oleh dekan
     * Status berubah: proses_dekan -> ditolak
     */
    public function tolak(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Session user tidak ditemukan'
                ], 401);
            }

            $idPengajuan = $request->id_riwayat_pengajuan;
            $alasanTolak = trim($request->alasan_tolak ?? '');

            if (!$idPengajuan) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'ID pengajuan tidak valid'
                ], 422);
            }

            if (empty($alasanTolak)) {
                return response()->json([
                    'status'     => '0',
                    'keterangan' => 'Alasan penolakan wajib diisi'
                ], 422);
            }

            $result = PengajuanTranskrip::tolak_dekan($idPengajuan, $alasanTolak);

            if ($result && $result->status === 1) {
                return response()->json([
                    'status'     => '1',
                    'keterangan' => $result->keterangan ?? 'Pengajuan berhasil ditolak'
                ], 200);
            }

            return response()->json([
                'status'     => '0',
                'keterangan' => $result->keterangan ?? 'Gagal menolak pengajuan'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'     => '0',
                'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
