<?php

namespace App\Http\Controllers\MahasiswaPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Kuesioner\NilaiKepuasanMahasiswa;
use App\Models\Kuesioner\RekapitulasiKepuasanMahasiswa;
use App\Models\Kuesioner\SubUnsurKuesionerKepuasanMahasiswa;
use App\Models\Kuesioner\UnsurKuesionerKepuasanMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class KuesionerKepuasanMahasiswaThdpKinerjaManajemenController extends Controller
{
    public function index()
    {
        $menu = "Kuesioner - Kepuasan Mahasiswa";
        $unsur = UnsurKuesionerKepuasanMahasiswa::get_list_unsur();
        $sub_unsur = array();
        foreach ($unsur as $item) {
            $sub_unsur[$item->id_unsur] = SubUnsurKuesionerKepuasanMahasiswa::get_list_sub_unsur($item->id_unsur);
        }
        $nilai = NilaiKepuasanMahasiswa::get_list_nilai_kepuasan();
        if (sizeof($unsur) > 0)
            return view('mahasiswa_page.akademik.kuesioner_kepuasan_mahasiswa', compact('menu', 'unsur', 'sub_unsur', 'nilai'));
        else {
            Session::flash("failed_message", "Tidak Ada Kuesioner Kepuasan Mahasiswa");
            return redirect(route('dashboard.dashboard'));
        }
    }

    public function insert_response(Request $request)
    {
        $id_sub_unsur = array();
        $id_nilai = array();
        foreach ($request->except('_token') as $key => $part) {
            array_push($id_sub_unsur, $key);
            array_push($id_nilai, $part);
        }
        $response = RekapitulasiKepuasanMahasiswa::add_response_kepuasan(Session::get("user")->id_mhs, implode(",", $id_sub_unsur), implode(",", $id_nilai), 1);
        Session::flash($response->status == 1 ? "success_message" : "failed_message", $response->keterangan);
        return redirect(route('mahasiswa.akademik.skpi.index'));
    }
}
