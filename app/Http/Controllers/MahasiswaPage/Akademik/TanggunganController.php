<?php

namespace App\Http\Controllers\MahasiswaPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\TanggunganMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TanggunganController extends Controller
{
    public function index()
    {
        $menu = 'Melihat Tanggungan';
        return view('mahasiswa_page.keuangan.tanggungan', compact('menu'));
    }

    public function json(Request $request)
    {
        try {
            $request->validate([
                'status_lunas' => 'required',
            ]);
            // Set default values untuk filter
            $nim = Session::get('user')->nim;
            $length = $request->length ?? 10;
            $start = $request->start ?? 0;
            $search = $request->search_value;
            $data_ = TanggunganMahasiswa::get_daftar_tanggungan($nim, $search, $length, $start, $request->status_lunas);
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
            return response()->json($data);
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

    public function json_detail_pembayaran(Request $request)
    {
        try {
            // Set default values untuk filter
            $nim = Session::get('user')->nim;
            $length = $request->length ?? 10;
            $start = $request->start ?? 0;
            $search = $request->search_value;

            $data_ = TanggunganMahasiswa::get_riwayat_pemabayaran($nim, $search, $length, $start);
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
            return response()->json($data);
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
}
