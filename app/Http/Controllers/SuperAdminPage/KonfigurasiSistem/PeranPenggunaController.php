<?php

namespace App\Http\Controllers\SuperAdminPage\KonfigurasiSistem;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\Karyawan;
use App\Models\Pengguna\PeranPengguna;
use App\Models\Sistem\Aplikasi;
use App\Models\Sistem\Peran;
use Illuminate\Http\Request;

class PeranPenggunaController extends Controller
{
    public function index(){
        $menu = 'Pengelolaan Peran Pengguna';
        $aplikasi = Aplikasi::get_daftar_aplikasi("", 0, -1);
        return view('super_admin_page.konfigurasi_sistem.peran_pengguna', compact('menu', 'aplikasi'));
    }

    public function json_get_daftar_peran_pengguna(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = PeranPengguna::get_daftar_peran_pengguna($request->id, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function detail($id, $id_aplikasi){
        $menu = 'Pengelolaan Peran Pengguna';
        $personal = Karyawan::get_detail_karyawan_by_id_personal($id);
        $peran = Peran::get_daftar_peran($id_aplikasi);
        $aplikasi = Aplikasi::get_detail_aplikasi($id_aplikasi);
        return view('super_admin_page.konfigurasi_sistem.detail_peran_pengguna', compact('menu', 'peran', 'personal', 'aplikasi'));
    }

    public function json_get_daftar_peran_pengguna_by_personal(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'id_aplikasi' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = PeranPengguna::get_daftar_peran_pengguna_by_personal_and_aplikasi($request->id, $request->id_aplikasi, $search, $start, $length);
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
            'id_personal' => 'required',
            'id_peran' => 'required',
            'is_default' => 'required',
        ]);
        $peran_pengguna = PeranPengguna::add_peran_pengguna($request->id_personal, $request->id_peran, $request->is_default == 1);
        return response()->json($peran_pengguna);
    }

    public function delete(Request $request){
        $request->validate([
            'id_peran' => 'required',
            'id_personal' => 'required'
        ]);
        $peran_pengguna = PeranPengguna::delete_peran_pengguna($request->id_personal, $request->id_peran);
        return response()->json($peran_pengguna);
    }
}
