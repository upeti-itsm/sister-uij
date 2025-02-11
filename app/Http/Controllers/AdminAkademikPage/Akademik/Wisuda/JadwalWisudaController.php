<?php

namespace App\Http\Controllers\AdminAkademikPage\Akademik\Wisuda;

use App\Http\Controllers\Controller;
use App\Models\Akademik\JadwalWisuda;
use Illuminate\Http\Request;

class JadwalWisudaController extends Controller
{
    public function index()
    {
        $menu = "Pengelolaan Jadwal Wisuda";
        $tahun_pelaksanaan = JadwalWisuda::get_tahun_pelaksanaan();
        return view('admin_akademik_page.akademik.wisuda.jadwal_wisuda', compact('menu', 'tahun_pelaksanaan'));
    }

    public function json_get_daftar_jadwal_wisuda(Request $request)
    {
        $request->validate([
            'tahun_pelaksanaan' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = JadwalWisuda::get_daftar_jadwal_wisuda($request->tahun_pelaksanaan, $start, $length);
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
            'periode' => 'required',
            'tgl_pelaksanaan' => 'required',
            'kuota' => 'required',
            'tgl_pendaftaran_dibuka' => 'required',
            'tgl_pendaftaran_ditutup' => 'required'
        ]);
        $data = JadwalWisuda::insup($request->periode, $request->tgl_pelaksanaan, $request->kuota, $request->tgl_pendaftaran_dibuka, $request->tgl_pendaftaran_ditutup);
        return response()->json($data, 200);
    }

    public function update(Request $request){
        $request->validate([
            'id' => 'required',
            'periode' => 'required',
            'tgl_pelaksanaan' => 'required',
            'kuota' => 'required',
            'tgl_pendaftaran_dibuka' => 'required',
            'tgl_pendaftaran_ditutup' => 'required'
        ]);
        $data = JadwalWisuda::insup($request->periode, $request->tgl_pelaksanaan, $request->kuota, $request->tgl_pendaftaran_dibuka, $request->tgl_pendaftaran_ditutup, $request->id);
        return response()->json($data, 200);
    }

    public function delete(Request $request){
        $request->validate([
            'id' => 'required'
        ]);
        $data = JadwalWisuda::delete_jadwal($request->id);
        return response()->json($data, 200);
    }
}
