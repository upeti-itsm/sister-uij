<?php

namespace App\Http\Controllers\DekanPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Akademik\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PengajuanSuratAktifDekanController extends Controller
{
    public function index()
    {
        $menu = 'Pengesahan Surat Aktif';
        return view('dekan_page.akademik.pengajuan_surat.pengajuan_surat_aktif', compact('menu'));
    }

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
                    'error'           => 'Session user tidak ditemukan',
                ], 401);
            }

            $idPersonal = $user->id_personal ?? null;

            $statusInput   = $request->status ?? null;
            $statusAllowed = ['2', '4', '5'];

            $status = in_array($statusInput, $statusAllowed) ? $statusInput : null;

            $search = trim(is_array($request->search)
                ? ($request->search['value'] ?? '')
                : ($request->search ?? ''));
            $start  = $request->start  ?? 0;
            $length = $request->length ?? 10;

            $result = PengajuanSurat::get_list_aktif_dekan($idPersonal, $status, $search, $start, $length);

            return response()->json([
                'draw'            => intval($request->draw ?? 1),
                'recordsTotal'    => $result['total'],
                'recordsFiltered' => $result['total'],
                'data'            => $result['data'],
                'error'           => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'draw'            => intval($request->draw ?? 1),
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
                'error'           => $e->getMessage(),
            ], 500);
        }
    }

    public function detail(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json(['status' => '0', 'keterangan' => 'Session user tidak ditemukan'], 401);
            }

            $idPengajuan = $request->id_pengajuan;
            if (!$idPengajuan) {
                return response()->json(['status' => '0', 'keterangan' => 'ID pengajuan tidak valid'], 422);
            }

            $detail = PengajuanSurat::get_detail_aktif($idPengajuan);
            if (!$detail) {
                return response()->json(['status' => '0', 'keterangan' => 'Pengajuan tidak ditemukan'], 404);
            }

            $statusAllowed = ['2', '4', '5'];
            if (!in_array((string) ($detail->status_pengajuan ?? ''), $statusAllowed)) {
                return response()->json(['status' => '0', 'keterangan' => 'Akses tidak diizinkan'], 403);
            }

            return response()->json(['status' => '1', 'data' => $detail], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => '0', 'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function approve(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json(['status' => '0', 'keterangan' => 'Session user tidak ditemukan'], 401);
            }

            $idPengajuan = $request->id_pengajuan;
            if (!$idPengajuan) {
                return response()->json(['status' => '0', 'keterangan' => 'ID pengajuan tidak valid'], 422);
            }

            $detail = PengajuanSurat::get_detail_aktif($idPengajuan);
            if (!$detail || (string) ($detail->status_pengajuan ?? '') !== '2') {
                return response()->json(['status' => '0', 'keterangan' => 'Pengajuan tidak dapat disetujui, status tidak valid'], 422);
            }

            $result = PengajuanSurat::set_status_aktif($idPengajuan, '4', $user->id_personal ?? null, $request->catatan ?? null);

            if ($result && (string) $result->status === '1') {
                return response()->json(['status' => '1', 'keterangan' => $result->keterangan], 200);
            }

            return response()->json(['status' => '0', 'keterangan' => $result->keterangan ?? 'Gagal menyetujui'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => '0', 'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function reject(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json(['status' => '0', 'keterangan' => 'Session user tidak ditemukan'], 401);
            }

            $idPengajuan = $request->id_pengajuan;
            if (!$idPengajuan) {
                return response()->json(['status' => '0', 'keterangan' => 'ID pengajuan tidak valid'], 422);
            }

            $catatan = $request->catatan ?? null;

            $detail = PengajuanSurat::get_detail_aktif($idPengajuan);
            if (!$detail || (string) ($detail->status_pengajuan ?? '') !== '2') {
                return response()->json(['status' => '0', 'keterangan' => 'Pengajuan tidak dapat ditolak, status tidak valid'], 422);
            }

            $result = PengajuanSurat::set_status_aktif($idPengajuan, '5', $user->id_personal ?? null, $catatan);

            if ($result && (string) $result->status === '1') {
                return response()->json(['status' => '1', 'keterangan' => $result->keterangan], 200);
            }

            return response()->json(['status' => '0', 'keterangan' => $result->keterangan ?? 'Gagal menolak'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => '0', 'keterangan' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
