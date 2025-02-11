<?php

namespace App\Http\Controllers\SuperAdminPage\KonfigurasiSistem;

use App\Http\Controllers\Controller;
use App\Models\Sistem\Aplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AplikasiController extends Controller
{
    public function index(){
        $menu = 'Pengelolaan Aplikasi Mandala';
        return view('super_admin_page.konfigurasi_sistem.aplikasi', compact('menu'));
    }

    public function json_get_daftar_aplikasi()
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Aplikasi::get_daftar_aplikasi($search, $start, $length);
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
            'nama_aplikasi' => 'required',
            'keterangan' => 'required'
        ]);
        $data = Aplikasi::insup('00000000-0000-0000-0000-000000000000', $request->nama_aplikasi, $request->keterangan);
        return response()->json($data, 200);
    }

    public function update(Request $request){
        $request->validate([
            'id' => 'required',
            'nama_aplikasi' => 'required',
            'keterangan' => 'required'
        ]);
        $data = Aplikasi::insup($request->id, $request->nama_aplikasi, $request->keterangan);
        return response()->json($data, 200);
    }

    public function delete(Request $request){
        $request->validate([
            'id' => 'required'
        ]);
        $data = Aplikasi::delete_aplikasi($request->id);
        return response()->json($data, 200);
    }
}
