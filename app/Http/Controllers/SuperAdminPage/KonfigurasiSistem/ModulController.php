<?php

namespace App\Http\Controllers\SuperAdminPage\KonfigurasiSistem;

use App\Http\Controllers\Controller;
use App\Models\Sistem\Aplikasi;
use App\Models\Sistem\Modul;
use Illuminate\Http\Request;

class ModulController extends Controller
{
    public function index(){
        $menu = 'Pengelolaan Modul Aplikasi';
        $aplikasi = Aplikasi::get_daftar_aplikasi("", 0, -1);
        return view('super_admin_page.konfigurasi_sistem.modul', compact('menu', 'aplikasi'));
    }

    public function json_get_daftar_modul(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Modul::get_daftar_modul($request->id, $search, $start, $length);
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
            'nama_modul' => 'required',
            'keterangan' => 'required'
        ]);
        $data = Modul::insup('00000000-0000-0000-0000-000000000000', $request->id_aplikasi, $request->nama_modul, $request->keterangan);
        return response()->json($data, 200);
    }

    public function update(Request $request){
        $request->validate([
            'id' => 'required',
            'nama_modul' => 'required',
            'keterangan' => 'required'
        ]);
        $data = Modul::insup($request->id, $request->id_aplikasi, $request->nama_modul, $request->keterangan);
        return response()->json($data, 200);
    }

    public function delete(Request $request){
        $request->validate([
            'id' => 'required'
        ]);
        $data = Modul::delete_modul($request->id);
        return response()->json($data, 200);
    }
}
