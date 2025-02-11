<?php

namespace App\Http\Controllers\BPMPage\Kuesioner\KuesionerKepuasanWisudawan;

use App\Http\Controllers\Controller;
use App\Models\Akademik\JadwalWisuda;
use App\Models\Kuesioner\RekapitulasiKepuasanWisudawan;
use App\Models\Kuesioner\UnsurKuesionerKepuasanWisudawan;
use App\Models\Kuesioner\UnsurSubKuesionerKepuasanWisudawan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HasilKuesionerController extends Controller
{
    public function index(){
        $menu = "Melihat Hasil Kuesioner Kepuasan Wisudawan";
        $unsur = UnsurKuesionerKepuasanWisudawan::get_list_unsur();
        $jadwal_wisuda = JadwalWisuda::get_daftar_jadwal_wisuda("all", -1);
        return view("bpm_page.kuesioner.kuesioner_kepuasan_wisudawan.hasil_kuesioner", compact('menu', 'unsur', 'jadwal_wisuda'));
    }

    public function get_response_pertanyaan(Request $request){
        $request->validate([
            'id_unsur' => 'required',
            'id_jadwal' => 'required'
        ]);
        $response = RekapitulasiKepuasanWisudawan::get_response_by_id_unsur($request->id_unsur, $request->id_jadwal);
        return response()->json($response);
    }

    public function export_response($id = 0){
        return Excel::download(new \App\Exports\Kuesioner\ExportHasilKuesionerKepuasanWisudawan($id), 'response.xlsx');
    }
}
