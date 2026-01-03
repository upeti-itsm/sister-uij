<?php

namespace App\Http\Controllers\AdminAkademikPage\Perkuliahan;

use App\Http\Controllers\Controller;
use App\Models\Akademik\JenisPelaksanaanKuliah;
use Illuminate\Http\Request;

class JenisPelaksanaanKuliahCont extends Controller
{
    public function index()
    {
        $menu = 'Pengelolaan Jenis Pelaksanaan Kuliah';
        return view('admin_akademik_page.perkuliahan.jenis_pelaksanaan_kuliah', compact('menu'));
    }

    public function json(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = JenisPelaksanaanKuliah::get_daftar($search, $start, $length);
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
            'kd_jenis_pelaksanaan' => 'required',
            'jenis_pelaksanaan' => 'required',
            'keterangan' => 'required',
        ]);
        $data = JenisPelaksanaanKuliah::insup(0, $request->kd_jenis_pelaksanaan, $request->jenis_pelaksanaan, $request->keterangan);
        return response()->json($data);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'kd_jenis_pelaksanaan' => 'required',
            'jenis_pelaksanaan' => 'required',
            'keterangan' => 'required',
        ]);
        $data = JenisPelaksanaanKuliah::insup($request->id, $request->kd_jenis_pelaksanaan, $request->jenis_pelaksanaan, $request->keterangan);
        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required'
        ]);
        $data = JenisPelaksanaanKuliah::set_aktif($request->id, $request->status);
        return response()->json($data);
    }
}
