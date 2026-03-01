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
        // Load semua prodi untuk selection
        $all_prodi = ManajemenFakultas::get_all_prodi_aktif();

        $data = [
            'title' => 'Manajemen Fakultas',
            'menu' => 'Manajemen Fakultas',
            'modul' => 'Manajemen Fakultas',
            'all_prodi' => $all_prodi,
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
            $sts_aktif = $request->sts_aktif ?? 2;

            // Pastikan sts_aktif adalah integer (0, 1, atau 2)
            $sts_aktif = intval($sts_aktif);

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

            // Tambahkan list prodi untuk setiap fakultas
            foreach ($data as $fakultas) {
                $fakultas->daftar_prodi = ManajemenFakultas::get_daftar_prodi_by_fakultas($fakultas->kd_fakultas);
            }

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
            // INSERT baru: is_data_aktif = true (otomatis aktif)
            $result = ManajemenFakultas::insup_fakultas(
                $request->kd_fakultas,
                $request->nama_fakultas,
                $request->dekan,
                $request->kd_nim_fak,
                true  // Data baru otomatis aktif
            );

            if ($result && isset($result->status) && $result->status === true) {
                // Update prodi yang dipilih
                if ($request->has('prodi_list') && is_array($request->prodi_list)) {
                    foreach ($request->prodi_list as $kd_prodi) {
                        ManajemenFakultas::update_prodi_fakultas($kd_prodi, $request->kd_fakultas);
                    }
                }

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

            // UPDATE: Kirim kembali is_data_aktif yang sudah ada (tidak mengubahnya)
            // is_data_aktif_old dikirim dari JavaScript dengan nilai yang sama dari database
            // Jadi status tidak berubah saat update data fakultas
            $is_aktif_sekarang = $request->is_data_aktif_old === '1' || $request->is_data_aktif_old === 1 || $request->is_data_aktif_old === true;

            $result = ManajemenFakultas::insup_fakultas(
                $kd_fak,
                $request->nama_fakultas,
                $request->dekan,
                $request->kd_nim_fak,
                $is_aktif_sekarang  // Kirim nilai yang sama, tidak mengubah status
            );

            if ($result && isset($result->status) && $result->status === true) {
                // Update prodi assignments
                if ($request->has('prodi_list') && is_array($request->prodi_list)) {
                    // Ambil prodi yang saat ini assigned ke fakultas ini
                    $current_prodi = ManajemenFakultas::get_daftar_prodi_by_fakultas($kd_fak);
                    $current_prodi_ids = array_map(function($p) { return $p->kd_program_studi; }, $current_prodi);

                    // Prodi yang dipilih sekarang
                    $selected_prodi_ids = $request->prodi_list;

                    // Prodi yang perlu di-assign (yang dipilih tapi belum di fakultas ini)
                    $to_assign = array_diff($selected_prodi_ids, $current_prodi_ids);
                    foreach ($to_assign as $kd_prodi) {
                        ManajemenFakultas::update_prodi_fakultas($kd_prodi, $kd_fak);
                    }

                    // Prodi yang perlu di-remove (yang sebelumnya di fakultas ini tapi tidak dipilih)
                    $to_remove = array_diff($current_prodi_ids, $selected_prodi_ids);
                    foreach ($to_remove as $kd_prodi) {
                        ManajemenFakultas::remove_prodi_from_fakultas($kd_prodi);
                    }
                } else {
                    // Jika tidak ada prodi yang dipilih, remove semua prodi dari fakultas ini
                    $current_prodi = ManajemenFakultas::get_daftar_prodi_by_fakultas($kd_fak);
                    foreach ($current_prodi as $prodi) {
                        ManajemenFakultas::remove_prodi_from_fakultas($prodi->kd_program_studi);
                    }
                }

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
