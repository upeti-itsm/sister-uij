<?php

namespace App\Http\Controllers\SuperAdminPage\KonfigurasiSistem;

use App\Http\Controllers\Controller;
use App\Models\Sistem\Aplikasi;
use App\Models\Sistem\KelompokPeran;
use App\Models\Sistem\Peran;
use Illuminate\Http\Request;

class PeranController extends Controller
{
    public function index(){
        $menu = 'Pengelolaan Peran Aplikasi';
        $aplikasi = Aplikasi::get_daftar_aplikasi("", 0, -1);
        $kelompok_peran = KelompokPeran::get_daftar_kelompok_peran("", 0, -1);
        return view('super_admin_page.konfigurasi_sistem.peran', compact('menu', 'aplikasi', 'kelompok_peran'));
    }

    public function json_get_daftar_peran(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'kelompok_peran' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Peran::get_daftar_peran($request->id, $request->kelompok_peran, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_aplikasi' => 'required',
            'kd_kelompok' => 'required',
            'nama_peran' => 'required',
            'keterangan' => 'required'
        ]);
        $data = Peran::insup($request->id_aplikasi, $request->nama_peran, $request->keterangan, $request->kd_kelompok);
        return response()->json($data, 200);
    }

    public function update(Request $request){
        $request->validate([
            'id' => 'required',
            'kd_kelompok' => 'required',
            'nama_peran' => 'required',
            'keterangan' => 'required'
        ]);
        $data = Peran::insup($request->id_aplikasi, $request->nama_peran, $request->keterangan, $request->kd_kelompok, $request->id);
        return response()->json($data, 200);
    }

    public function delete(Request $request){
        $request->validate([
            'id' => 'required'
        ]);
        $data = Peran::delete_peran($request->id);
        return response()->json($data, 200);
    }
}
