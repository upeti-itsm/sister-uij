<?php

namespace App\Http\Controllers\SuperAdminPage\Siakad;

use App\Http\Controllers\Controller;
use App\Models\SIAKAD_MODEL\tblMahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function daftar_mahasiswa()
    {
        $menu = "Mahasiswa | Siakad";
        $angkatan = tblMahasiswa::getAngkatan();
        $status_aktif = tblMahasiswa::getStatusAktif();
        return view('super_admin_page.siakad.mahasiswa', compact('menu', 'angkatan', 'status_aktif'));
    }

    public function json_daftar_mahasiswa(Request $request)
    {
        $request->validate([
            'angkatan' => 'required',
            'status' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = tblMahasiswa::getDaftarMahasiswa($request->angkatan, $request->status, $search, $length, $start);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }
}
