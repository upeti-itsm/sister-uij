<?php

namespace App\Http\Controllers\MahasiswaPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Akademik\KRS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class KRSController extends Controller
{
    public function index(){
        $menu = "Mengelola Kartu Rencana Studi";
        return view('mahasiswa_page.akademik.krs.index', compact('menu'));
    }

    public function json()
    {
        try {
            // Set default values untuk filter
            $kd_prodi = Session::get('user')->kd_prodi;
            $length = $request->length ?? 10;
            $start = $request->start ?? 0;
            $search = $request->search['value'] ?? '';

            $data_ = KRS::get_daftar($kd_prodi, 1, null, $search, $start, $length);

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

            return response()->json($data, 200);
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

    public function json_draft()
    {
        try {
            // Set default values untuk filter
            $kd_prodi = Session::get('user')->kd_prodi;
            $length = $request->length ?? 10;
            $start = $request->start ?? 0;
            $search = $request->search['value'] ?? '';
            $nim = Session::get('user')->nim;
            $data_ = KRS::get_daftar($kd_prodi, 1, $nim, $search, $start, $length);
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

            return response()->json($data, 200);
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

    public function store(Request $request){
        $request->validate([
            'krs_data' => 'required'
        ]);
        $nim = Session::get('user')->nim;
        $krsData = is_string($request->krs_data)
            ? json_decode($request->krs_data, true)
            : $request->krs_data;

        $idJadwalString = collect($krsData)
            ->pluck('id_jadwal')
            ->implode(',');
        $data = KRS::inDelKRS($nim, $idJadwalString);
        return response()->json($data);
    }

    public function ajukan_krs(Request $request){
        $request->validate([
            'id_krs' => 'required'
        ]);
        $data = KRS::update_status_krs($request->id_krs, 1);
        return response()->json($data);
    }

    public function cekMaksimalKrs()
    {
        $nim = Session::get('user')->nim;
        $sks = KRS::getSKSMaks($nim);
        return response()->json($sks, 200);
    }
}
