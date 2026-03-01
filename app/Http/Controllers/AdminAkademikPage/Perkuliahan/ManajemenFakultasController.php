<?php

namespace App\Http\Controllers\AdminAkademikPage\Perkuliahan;

use App\Http\Controllers\Controller;
use App\Models\Akademik\ManajemenFakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\FacadesLog;

class ManajemenFakultasController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Manajemen Fakultas',
            'menu' => 'Manajemen Fakultas',
            'modul' => 'Manajemen Fakultas',
        ];

        return view('admin_akademik_page.perkuliahan.manajemen_fakultas', $data);
    }

    public function json_get_daftar_fakultas(Request $request)
    {
        try {
            $param_search = $request->param_search ?? '';
            $no_page = $request->no_page ?? -1;
            $jml_record_perpage = $request->jml_record_perpage ?? 10;
            $kd_fakultas = $request->kd_fakultas ?? null;
            $sts_aktif = $request->sts_aktif ?? true;

            // Convert sts_aktif to boolean if needed
            if ($sts_aktif !== null && $sts_aktif !== '') {
                $sts_aktif = filter_var($sts_aktif, FILTER_VALIDATE_BOOLEAN);
            } else {
                $sts_aktif = true;
            }

            Log::info('Get Fakultas params:', [
                'param_search' => $param_search,
                'no_page' => $no_page,
                'jml_record_perpage' => $jml_record_perpage,
                'kd_fakultas' => $kd_fakultas,
                'sts_aktif' => $sts_aktif
            ]);

            $data = ManajemenFakultas::get_daftar_fakultas(
                $param_search,
                $no_page,
                $jml_record_perpage,
                $kd_fakultas,
                $sts_aktif
            );

            Log::info('Fakultas result:', ['count' => count($data), 'data' => $data]);

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Get Fakultas error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $result = ManajemenFakultas::insup_fakultas(
                $request->kd_fakultas,
                $request->nama_fakultas,
                $request->dekan,
                $request->kd_nim_fak,
                true
            );

            if ($result && isset($result->status) && $result->status === true) {
                return response()->json([
                    'status' => 'success',
                    'message' => $result->keterangan ?? 'Data fakultas berhasil disimpan',
                    'data' => [
                        'kd_fakultas' => $result->kd_fakultas
                    ]
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => $result->keterangan ?? 'Gagal menyimpan data fakultas'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            // Gunakan kd_fakultas_old jika ada (untuk update key), atau kd_fakultas biasa
            $kd_fak = $request->kd_fakultas_old ?: $request->kd_fakultas;

            // Untuk update, status tetap seperti semula (tidak diubah dari form)
            // Status diubah melalui tombol toggle di table
            $result = ManajemenFakultas::insup_fakultas(
                $kd_fak,
                $request->nama_fakultas,
                $request->dekan,
                $request->kd_nim_fak,
                null  // is_data_aktif = null, biar function database pakai nilai yang sudah ada
            );

            if ($result && isset($result->status) && $result->status === true) {
                return response()->json([
                    'status' => 'success',
                    'message' => $result->keterangan ?? 'Data fakultas berhasil diperbarui',
                    'data' => [
                        'kd_fakultas' => $result->kd_fakultas
                    ]
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => $result->keterangan ?? 'Gagal memperbarui data fakultas'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function toggle_status(Request $request)
    {
        try {
            $kd_fakultas = trim($request->kd_fakultas);
            $status = $request->status === 'true' || $request->status === true || $request->status === '1';

            Log::info('Toggle status fakultas:', [
                'kd_fakultas' => $kd_fakultas,
                'status' => $status
            ]);

            $result = ManajemenFakultas::set_status_aktif_fakultas(
                $kd_fakultas,
                $status
            );

            Log::info('Toggle status result:', ['result' => $result]);

            if ($result && isset($result->status) && $result->status == 1) {
                return response()->json([
                    'status' => 'success',
                    'message' => $result->keterangan ?? 'Status fakultas berhasil diubah'
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => $result->keterangan ?? 'Gagal mengubah status fakultas'
            ], 400);
        } catch (\Exception $e) {
            Log::error('Toggle status error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
