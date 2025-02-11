<?php

namespace App\Http\Controllers\BPMPage\Kuesioner\KuesionerKepuasanMahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Kuesioner\JenisKuesioner;
use App\Models\Kuesioner\RekapitulasiKepuasanMahasiswa;
use App\Models\Kuesioner\UnsurKuesionerKepuasanMahasiswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HasilKuesionerController extends Controller
{
    public function index(){
        $menu = "Melihat Hasil Kuesioner Kepuasan Mahasiswa";
        $unsur = UnsurKuesionerKepuasanMahasiswa::get_list_unsur();
        $semester = RekapitulasiKepuasanMahasiswa::get_tahun_akademik();
        $jenis_kuesioner = JenisKuesioner::get_jenis_kuesioner();
        return view("bpm_page.kuesioner.kuesioner_kepuasan_mahasiswa.hasil_kuesioner", compact('menu', 'unsur', 'semester', 'jenis_kuesioner'));
    }

    public function get_unsur_by_id_jenis($id){
        return response(UnsurKuesionerKepuasanMahasiswa::get_list_unsur(-1, 10, '', 0, $id));
    }

    public function get_response_pertanyaan(Request $request){
        $request->validate([
            'id_unsur' => 'required',
            'id_semester' => 'required'
        ]);
        $response = RekapitulasiKepuasanMahasiswa::get_response_by_id_unsur($request->id_unsur, $request->id_semester);
        return response()->json($response);
    }

    public function export_response($id_jenis, $id_semester){
        return Excel::download(new \App\Exports\Kuesioner\ExportHasilKuesionerKepuasanMahasiswa($id_jenis, $id_semester), 'response.xlsx');
    }
}
