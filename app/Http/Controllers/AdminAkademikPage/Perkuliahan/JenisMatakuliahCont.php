<?php

namespace App\Http\Controllers\AdminAkademikPage\Perkuliahan;

use App\Http\Controllers\Controller;
use App\Models\Akademik\JenisMatakuliah;
use Illuminate\Http\Request;

class JenisMatakuliahCont extends Controller
{
    public function index()
    {
        $menu = 'Pengelolaan Jenis Matakuliah';
        return view('admin_akademik_page.perkuliahan.jenis_matakuliah', compact('menu'));
    }

    public function json(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = JenisMatakuliah::get_daftar($search, $start, $length);
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
            'kd_jenis_matakuliah' => 'required',
            'nama_jenis' => 'required',
            'keterangan' => 'required',
        ]);
        $data = JenisMatakuliah::insup(0, $request->kd_jenis_matakuliah, $request->nama_jenis, $request->keterangan);
        return response()->json($data);
    }

    public function update(Request $request) {
        $request->validate([
            'id' => 'required',
            'kd_jenis_matakuliah' => 'required',
            'nama_jenis' => 'required',
            'keterangan' => 'required'
        ]);
        $data = JenisMatakuliah::insup($request->id, $request->kd_jenis_matakuliah, $request->nama_jenis, $request->keterangan);
        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required'
        ]);
        $data = JenisMatakuliah::set_aktif($request->id, $request->status);
        return response()->json($data);
    }
}
