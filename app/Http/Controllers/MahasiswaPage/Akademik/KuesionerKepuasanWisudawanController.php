<?php

namespace App\Http\Controllers\MahasiswaPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Kuesioner\NilaiKepuasanWisudawan;
use App\Models\Kuesioner\RekapitulasiKepuasanWisudawan;
use App\Models\Kuesioner\UnsurKuesionerKepuasanWisudawan;
use App\Models\Kuesioner\UnsurSubKuesionerKepuasanWisudawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class KuesionerKepuasanWisudawanController extends Controller
{
    public function index()
    {
        $menu = "Kuesioner - Kepuasan Wisudawan";
        $unsur = UnsurKuesionerKepuasanWisudawan::get_list_unsur();
        $sub_unsur = array();
        foreach ($unsur as $item) {
            $sub_unsur[$item->id_unsur] = UnsurSubKuesionerKepuasanWisudawan::get_list_sub_unsur($item->id_unsur);
        }
        $nilai = NilaiKepuasanWisudawan::get_list_nilai_kepuasan_wisudawan();
        if (sizeof($unsur) > 0)
            return view('mahasiswa_page.akademik.kuesioner_kepuasan_wisudawan', compact('menu', 'unsur', 'sub_unsur', 'nilai'));
        else {
            Session::flash("failed_message", "Tidak Ada Kuesioner Kepuasan Wisudawan");
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
        $response = RekapitulasiKepuasanWisudawan::add_response_kepuasan_wisudawan(Session::get("user")->id_mhs, implode(",", $id_sub_unsur), implode(",", $id_nilai));
        Session::flash($response->status == 1 ? "success_message" : "failed_message", $response->keterangan);
        return redirect(route('mahasiswa.akademik.kuesioner_kepuasan_wisudawan.index'));
    }
}
