<?php

namespace App\Http\Controllers\KeuanganPage\Siakad;

use App\Http\Controllers\Controller;
use App\Models\SIAKAD_MODEL\tblMahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function daftar_mahasiswa()
    {
        $menu = "Keuangan - Melihat Daftar Mahasiswa Siakad";
        $angkatan = tblMahasiswa::getAngkatan();
        $status_aktif = tblMahasiswa::getStatusAktif();
        return view('keuangan_page.siakad.daftar_mahasiswa', compact('menu', 'angkatan', 'status_aktif'));
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
        $total_ = tblMahasiswa::getTotalRecordsMahasiswa($request->angkatan, $request->status, $search);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $total_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }
}
