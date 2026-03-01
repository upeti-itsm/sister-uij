<?php

namespace App\Http\Controllers\AdminAkademikPage\Perkuliahan;

use App\Http\Controllers\Controller;
use App\Models\Akademik\ManajemenProdi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ManajemenProdiController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Manajemen Prodi',
            'menu' => 'Manajemen Prodi',
            'modul' => 'Manajemen Prodi',
        ];

        return view('admin_akademik_page\perkuliahan\manajemen_prodi', $data);
    }

    public function json_get_daftar_fakultas(Request $request)
    {
        try {
            $param_search = $request->param_search ?? '';
            $no_page = $request->no_page ?? -1;
            $jml_record_perpage = $request->jml_record_perpage ?? 100;
            $kd_fakultas = $request->kd_fakultas ?? null;
            $sts_aktif = $request->sts_aktif ?? 2;

            // Pastikan sts_aktif adalah integer (0, 1, atau 2)
            if ($sts_aktif === true || $sts_aktif === 'true') {
                $sts_aktif = 1;
            } elseif ($sts_aktif === false || $sts_aktif === 'false') {
                $sts_aktif = 0;
            } else {
                $sts_aktif = intval($sts_aktif);
            }

            \Log::info('Get Fakultas params:', [
                'param_search' => $param_search,
                'no_page' => $no_page,
                'jml_record_perpage' => $jml_record_perpage,
                'kd_fakultas' => $kd_fakultas,
                'sts_aktif' => $sts_aktif
            ]);

            $data = ManajemenProdi::get_daftar_fakultas(
                $param_search,
                $no_page,
                $jml_record_perpage,
                $kd_fakultas,
                $sts_aktif
            );

            \Log::info('Fakultas result count:', ['count' => count($data)]);

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Get Fakultas error:', ['message' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function json_get_daftar_jenjang(Request $request)
    {
        try {
            $kd_jenjang_didik = $request->kd_jenjang_didik ?? null;
            $param_search = $request->param_search ?? '';
            $no_page = $request->no_page ?? -1;
            $jml_record_perpage = $request->jml_record_perpage ?? 100;

            $data = ManajemenProdi::get_daftar_jenjang_didik(
                $kd_jenjang_didik,
                $param_search,
                $no_page,
                $jml_record_perpage
            );

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Get Jenjang error:', ['message' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function json_get_daftar_program_studi(Request $request)
    {
        try {
            $kd_prodi = $request->kd_prodi ?? null;
            $kd_dikti = $request->kd_dikti ?? null;
            $kd_fakultas = $request->kd_fakultas ?? null;
            $param_search = $request->param_search ?? $request->search ?? '';
            $sts_aktif = $request->sts_aktif ?? null;
            $no_page = $request->no_page ?? -1;
            $jml_record_perpage = $request->jml_record_perpage ?? 10;

            // Clean and trim kd_fakultas
            if ($kd_fakultas !== null) {
                $kd_fakultas = trim($kd_fakultas);
                if ($kd_fakultas === '') {
                    $kd_fakultas = null;
                }
            }

            // Clean and trim param_search
            if ($param_search !== null) {
                $param_search = trim($param_search);
            }

            // Convert sts_aktif to boolean for program_studi function (masih boolean)
            if ($sts_aktif !== null && $sts_aktif !== '') {
                $sts_aktif = filter_var($sts_aktif, FILTER_VALIDATE_BOOLEAN);
            } else {
                $sts_aktif = null;
            }

            \Log::info('========== RECEIVED PARAMS ==========');
            \Log::info('ManajemenProdi params:', [
                'kd_prodi' => $kd_prodi,
                'kd_dikti' => $kd_dikti,
                'kd_fakultas' => $kd_fakultas,
                'param_search' => $param_search,
                'sts_aktif' => $sts_aktif,
                'no_page' => $no_page,
                'jml_record_perpage' => $jml_record_perpage
            ]);
            \Log::info('=====================================');

            $data = ManajemenProdi::get_daftar_program_studi(
                $kd_prodi,
                $kd_dikti,
                $kd_fakultas,
                $param_search,
                $sts_aktif,
                $no_page,
                $jml_record_perpage
            );

            // Get jenjang data for mapping
            $jenjangData = ManajemenProdi::get_daftar_jenjang_didik();
            $jenjangMap = [];
            foreach ($jenjangData as $jenjang) {
                $jenjangMap[$jenjang->kd_jenjang_didik] = $jenjang->jenjang_didik;
            }

            // Add nama_jenjang_didik to each prodi record
            foreach ($data as $prodi) {
                $prodi->nama_jenjang_didik = $jenjangMap[$prodi->kd_jenjang_didik] ?? '-';
            }

            \Log::info('ManajemenProdi result count:', ['count' => count($data), 'data' => $data]);

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'params' => [
                    'kd_prodi' => $kd_prodi,
                    'kd_dikti' => $kd_dikti,
                    'kd_fakultas' => $kd_fakultas,
                    'param_search' => $param_search,
                    'sts_aktif' => $sts_aktif,
                    'no_page' => $no_page,
                    'jml_record_perpage' => $jml_record_perpage
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('ManajemenProdi error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Store request:', $request->all());

            // Convert boolean values properly
            $sts_kip = $request->sts_kip === '1' || $request->sts_kip === 1 || $request->sts_kip === true || $request->sts_kip === 'on';
            $is_s2 = $request->is_s2 === '1' || $request->is_s2 === 1 || $request->is_s2 === true || $request->is_s2 === 'on';

            $result = ManajemenProdi::insup_prodi(
                null, // id_program_studi
                $request->kd_prodi, // kd_program_studi
                $request->nm_prodi, // nama_program_studi
                $request->jenjang, // kd_jenjang_didik
                $request->kd_fakultas, // kd_fakultas
                $request->kaprodi_id, // karyawan_id_kaprodi
                $request->kd_nim, // kd_nim
                $request->no_urut_wisuda, // no_urut_prodi_wisuda
                $sts_kip, // sts_kip
                $request->kd_dikti ?? '034', // kd_dikti
                $is_s2 // is_s2
            );

            \Log::info('Store result:', ['result' => $result]);

            // Check if result is successful (status = true)
            if ($result && isset($result->status) && $result->status === true) {
                return response()->json([
                    'status' => 'success',
                    'message' => $result->keterangan ?? 'Data program studi berhasil disimpan',
                    'data' => [
                        'id_program_studi' => $result->id_program_studi,
                        'kd_program_studi' => $result->kd_program_studi
                    ]
                ]);
            }

            // If status is false, return error
            return response()->json([
                'status' => 'error',
                'message' => $result->keterangan ?? 'Gagal menyimpan data program studi'
            ], 400);
        } catch (\Exception $e) {
            \Log::error('Store error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            \Log::info('Update request:', $request->all());

            // Convert boolean values properly
            $sts_kip = $request->sts_kip === '1' || $request->sts_kip === 1 || $request->sts_kip === true || $request->sts_kip === 'on';
            $is_s2 = $request->is_s2 === '1' || $request->is_s2 === 1 || $request->is_s2 === true || $request->is_s2 === 'on';

            $result = ManajemenProdi::insup_prodi(
                $request->id, // id_program_studi
                $request->kd_prodi, // kd_program_studi
                $request->nm_prodi, // nama_program_studi
                $request->jenjang, // kd_jenjang_didik
                $request->kd_fakultas, // kd_fakultas
                $request->kaprodi_id, // karyawan_id_kaprodi
                $request->kd_nim, // kd_nim
                $request->no_urut_wisuda, // no_urut_prodi_wisuda
                $sts_kip, // sts_kip
                $request->kd_dikti ?? '034', // kd_dikti
                $is_s2 // is_s2
            );

            \Log::info('Update result:', ['result' => $result]);

            // Check if result is successful (status = true)
            if ($result && isset($result->status) && $result->status === true) {
                return response()->json([
                    'status' => 'success',
                    'message' => $result->keterangan ?? 'Data program studi berhasil diperbarui',
                    'data' => [
                        'id_program_studi' => $result->id_program_studi,
                        'kd_program_studi' => $result->kd_program_studi
                    ]
                ]);
            }

            // If status is false, return error
            return response()->json([
                'status' => 'error',
                'message' => $result->keterangan ?? 'Gagal memperbarui data program studi'
            ], 400);
        } catch (\Exception $e) {
            \Log::error('Update error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function toggle_status(Request $request)
    {
        try {
            \Log::info('Toggle status request:', [
                'id' => $request->id,
                'status' => $request->status
            ]);

            $result = ManajemenProdi::set_aktif_prodi(
                $request->id,
                $request->status === 'true' || $request->status === true || $request->status === '1'
            );

            \Log::info('Toggle status result:', ['result' => $result]);

            // Check if result is successful (status = 1 means success)
            if ($result && isset($result->status) && $result->status == 1) {
                return response()->json([
                    'status' => 'success',
                    'message' => $result->keterangan ?? 'Status program studi berhasil diubah'
                ]);
            }

            // If status is not 1, return error
            return response()->json([
                'status' => 'error',
                'message' => $result->keterangan ?? 'Gagal mengubah status program studi'
            ], 400);
        } catch (\Exception $e) {
            \Log::error('Toggle status error:', [
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
