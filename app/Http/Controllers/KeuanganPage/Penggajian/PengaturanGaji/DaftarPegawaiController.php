<?php

namespace App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\Karyawan;
use App\Models\Organisasi\TunjanganKinerjaDosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DaftarPegawaiController extends Controller
{
    public function index()
    {
        $menu = 'Pengaturan Gaji - Daftar Pegawai';
        $tunjangan_kinerja = TunjanganKinerjaDosen::get_tunjangan_kinerja();
        return view('keuangan_page.penggajian.pengaturan_gaji.daftar_pegawai', compact('menu', 'tunjangan_kinerja'));
    }

    public function json_get_daftar_pegawai(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Karyawan::get_daftar_pegawai($search, $start, $length, $request->jenis_pegawai ? implode(',', $request->jenis_pegawai) : null);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function update_asuransi(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required',
            'jenis' => 'required'
        ]);
        if ($request->jenis == "sehat")
            $data = Karyawan::update_kesehatan($request->id, $request->status);
        else
            $data = Karyawan::update_ketenagakerjaan($request->id, $request->status);
        return response()->json($data);
    }

    public function update_dplk(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required'
        ]);
        if ($request->status == "true")
            $request->validate([
                'nominal' => 'required'
            ]);
        $data = Karyawan::update_dplk($request->id, $request->status, $request->nominal);
        return response()->json($data);
    }

    public function update_kinerja(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'kinerja' => 'required'
        ]);
        $data = Karyawan::update_kinerja($request->id, $request->kinerja);
        Session::flash($data->status == 1 ? 'success_message' : 'failed_message', $data->keterangan);
        return redirect()->back();
    }
}
