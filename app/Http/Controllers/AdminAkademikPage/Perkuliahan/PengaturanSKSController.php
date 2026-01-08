<?php

namespace App\Http\Controllers\AdminAkademikPage\Perkuliahan;

use App\Http\Controllers\Controller;
use App\Models\Akademik\PengaturanSKS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PengaturanSKSController extends Controller
{
    public function index()
    {
        $menu = 'Pengaturan SKS';
        return view('admin_akademik_page.perkuliahan.pengaturan_sks', compact('menu'));
    }

    public function json(Request $request)
    {
        try {
            $data = PengaturanSKS::get_daftar();

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error get pengaturan SKS', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ips_min' => 'required|numeric',
                'ips_max' => 'required|numeric',
                'sks' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $result = PengaturanSKS::insup(
                null,
                $request->ips_min,
                $request->ips_max,
                $request->sks
            );

            // Check response dari function database
            if ($result) {
                // Cek message untuk determine success
                $message = $result->message ?? 'Data berhasil disimpan';
                $isSuccess = (isset($result->status) && $result->status == 200) ||
                            (stripos($message, 'berhasil') !== false);

                if ($isSuccess) {
                    return response()->json([
                        'status' => 'success',
                        'message' => $message
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => $message
                    ], 400);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menambahkan data'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error store pengaturan SKS', [
                'message' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'ips_min' => 'required|numeric',
                'ips_max' => 'required|numeric',
                'sks' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $result = PengaturanSKS::insup(
                $request->id,
                $request->ips_min,
                $request->ips_max,
                $request->sks
            );

            // Check response dari function database
            if ($result) {
                // Cek message untuk determine success
                $message = $result->message ?? 'Data berhasil diupdate';
                $isSuccess = (isset($result->status) && $result->status == 200) ||
                            (stripos($message, 'berhasil') !== false);

                if ($isSuccess) {
                    return response()->json([
                        'status' => 'success',
                        'message' => $message
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => $message
                    ], 400);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengupdate data'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error update pengaturan SKS', [
                'message' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Get status from request, default to false (nonaktif)
            $status = $request->has('status') ? filter_var($request->status, FILTER_VALIDATE_BOOLEAN) : false;
            $result = PengaturanSKS::set_aktif($request->id, $status);

            // Check response dari function database
            if ($result) {
                // Cek message untuk determine success
                $message = $result->message ?? 'Status berhasil diubah';
                $isSuccess = (isset($result->status) && $result->status == 200) ||
                            (stripos($message, 'berhasil') !== false);

                if ($isSuccess) {
                    return response()->json([
                        'status' => 'success',
                        'message' => $message
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => $message
                    ], 400);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengubah status'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error delete pengaturan SKS', [
                'message' => $e->getMessage(),
                'id' => $request->id
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
