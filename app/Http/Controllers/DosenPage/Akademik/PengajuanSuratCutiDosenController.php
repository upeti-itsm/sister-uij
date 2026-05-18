<?php

namespace App\Http\Controllers\DosenPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Akademik\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PengajuanSuratCutiDosenController extends Controller
{
    public function index()
    {
        $menu = 'Persetujuan Surat Cuti';
        return view('dosen_page.akademik.pengajuan_surat.pengajuan_surat_cuti', compact('menu'));
    }

    // DATATABLE
    public function json(Request $request)
    {
        try {
            $user = Session::get('user');
            // dd($user);
            if (!$user) {
                return response()->json(
                    [
                        'draw' => intval($request->draw ?? 1),
                        'recordsTotal' => 0,
                        'recordsFiltered' => 0,
                        'data' => [],
                        'error' => 'Session user tidak ditemukan',
                    ],
                    401,
                );
            }

            $idPersonal = $user->id_personal ?? null;
            if (!$idPersonal) {
                return response()->json(
                    [
                        'draw' => intval($request->draw ?? 1),
                        'recordsTotal' => 0,
                        'recordsFiltered' => 0,
                        'data' => [],
                        'error' => 'ID personal dosen tidak ditemukan',
                    ],
                    422,
                );
            }

            $status = $request->status ?? null;
            $search = trim(is_array($request->search) ? $request->search['value'] ?? '' : $request->search ?? '');
            $start = $request->start ?? 0;
            $length = $request->length ?? 10;

            $result = PengajuanSurat::get_list_dosen($idPersonal, $status, $search, $start, $length);

            return response()->json(
                [
                    'draw' => intval($request->draw ?? 1),
                    'recordsTotal' => $result['total'],
                    'recordsFiltered' => $result['total'],
                    'data' => $result['data'],
                    'error' => null,
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'draw' => intval($request->draw ?? 1),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    // DETAIL
    public function detail(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json(['status' => '0', 'keterangan' => 'Session user tidak ditemukan'], 401);
            }

            $idRiwayat = $request->id_pengajuan;
            if (!$idRiwayat) {
                return response()->json(['status' => '0', 'keterangan' => 'ID pengajuan tidak valid'], 422);
            }

            $idPersonal = $user->id_personal ?? null;
            if (!$idPersonal) {
                return response()->json(['status' => '0', 'keterangan' => 'ID personal dosen tidak ditemukan'], 422);
            }

            $detail = PengajuanSurat::get_detail_with_access($idRiwayat, $idPersonal);
            if (!$detail) {
                return response()->json(['status' => '0', 'keterangan' => 'Pengajuan tidak ditemukan atau Anda tidak memiliki akses'], 403);
            }

            return response()->json(['status' => '1', 'data' => $detail], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => '0', 'keterangan' => $e->getMessage()], 500);
        }
    }

    // APPROVE & REJECT
    public function approve(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json(['status' => '0', 'keterangan' => 'Session user tidak ditemukan'], 401);
            }

            $idRiwayat = $request->id_pengajuan;
            if (!$idRiwayat) {
                return response()->json(['status' => '0', 'keterangan' => 'ID pengajuan tidak valid'], 422);
            }

            $idPersonal = $user->id_personal ?? null;
            if (!$idPersonal) {
                return response()->json(['status' => '0', 'keterangan' => 'ID personal dosen tidak ditemukan'], 422);
            }

            $detail = PengajuanSurat::get_detail_with_access($idRiwayat, $idPersonal);
            if (!$detail) {
                return response()->json(['status' => '0', 'keterangan' => 'Pengajuan tidak ditemukan atau Anda tidak memiliki akses'], 403);
            }

            if ((string) ($detail->status_pengajuan ?? '') !== '1') {
                return response()->json(['status' => '0', 'keterangan' => 'Pengajuan tidak dapat disetujui, status tidak valid'], 422);
            }

            $result = PengajuanSurat::set_status($idRiwayat, '2', $idPersonal, null);

            if ($result && $result->status === 1) {
                return response()->json(['status' => '1', 'keterangan' => $result->keterangan], 200);
            }

            return response()->json(['status' => '0', 'keterangan' => $result->keterangan ?? 'Gagal menyetujui'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => '0', 'keterangan' => $e->getMessage()], 500);
        }
    }

    public function reject(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json(['status' => '0', 'keterangan' => 'Session user tidak ditemukan'], 401);
            }

            $idRiwayat = $request->id_pengajuan;
            if (!$idRiwayat) {
                return response()->json(['status' => '0', 'keterangan' => 'ID pengajuan tidak valid'], 422);
            }

            $catatan = trim($request->catatan ?? '');
            if (empty($catatan)) {
                return response()->json(['status' => '0', 'keterangan' => 'Catatan penolakan wajib diisi'], 422);
            }

            $idPersonal = $user->id_personal ?? null;
            if (!$idPersonal) {
                return response()->json(['status' => '0', 'keterangan' => 'ID personal dosen tidak ditemukan'], 422);
            }

            $detail = PengajuanSurat::get_detail_with_access($idRiwayat, $idPersonal);
            if (!$detail) {
                return response()->json(['status' => '0', 'keterangan' => 'Pengajuan tidak ditemukan atau Anda tidak memiliki akses'], 403);
            }

            if ((string) ($detail->status_pengajuan ?? '') !== '1') {
                return response()->json(['status' => '0', 'keterangan' => 'Pengajuan tidak dapat ditolak, status tidak valid'], 422);
            }

            $result = PengajuanSurat::set_status($idRiwayat, '3', $idPersonal, $catatan);

            if ($result && $result->status === 1) {
                return response()->json(['status' => '1', 'keterangan' => $result->keterangan], 200);
            }

            return response()->json(['status' => '0', 'keterangan' => $result->keterangan ?? 'Gagal menolak'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => '0', 'keterangan' => $e->getMessage()], 500);
        }
    }
}
