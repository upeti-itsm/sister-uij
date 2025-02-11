<?php

namespace App\Http\Controllers\SuperAdminPage\KonfigurasiSistem;

use App\Http\Controllers\Controller;
use App\Models\Sistem\Aplikasi;
use App\Models\Sistem\KewenanganPeran;
use App\Models\Sistem\Modul;
use App\Models\Sistem\Peran;
use Illuminate\Http\Request;

class KewenanganPeranController extends Controller
{
    public function index(){
        $menu = 'Pengelolaan Kewenangan Peran';
        $aplikasi = Aplikasi::get_daftar_aplikasi("", 0, -1);
        return view('super_admin_page.konfigurasi_sistem.kewenangan_peran', compact('menu', 'aplikasi'));
    }

    public function json_get_daftar_kewenangan_peran(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = KewenanganPeran::get_daftar_kewenangan_peran($request->id, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function detail($id){
        $menu = 'Pengelolaan Kewenangan Peran';
        $peran = Peran::get_detail_peran($id);
        $modul = Modul::get_daftar_modul($peran->id_aplikasi);
        return view('super_admin_page.konfigurasi_sistem.detail_kewenangan_peran', compact('menu', 'peran', 'modul'));
    }

    public function json_get_daftar_kewenangan_peran_by_peran(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = KewenanganPeran::get_daftar_kewenangan_peran_by_peran($request->id, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function store(Request $request){
        $request->validate([
            'id_peran' => 'required',
            'id_modul' => 'required',
            'id_kewenangan_akses' => 'required',
        ]);
        $kewenangan_peran = KewenanganPeran::add_kewenangan_peran($request->id_peran, $request->id_modul, $request->id_kewenangan_akses);
        return response()->json($kewenangan_peran);
    }

    public function delete(Request $request){
        $request->validate([
            'id_peran' => 'required',
            'id_modul' => 'required'
        ]);
        $kewenangan_peran = KewenanganPeran::delete_kewenangan_peran($request->id_peran, $request->id_modul);
        return response()->json($kewenangan_peran);
    }
}
