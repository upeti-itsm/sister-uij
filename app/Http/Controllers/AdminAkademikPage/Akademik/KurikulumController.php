<?php

namespace App\Http\Controllers\AdminAkademikPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Akademik\Kurikulum;
use App\Models\Akademik\ProgramStudi;
use Illuminate\Http\Request;

class KurikulumController extends Controller
{
    public function index()
    {
        $menu = 'Kurikulum';
        $prodi = ProgramStudi::get_program_studi();
        return view('admin_akademik_page.akademik.kurikulum.index', compact('menu', 'prodi'));
    }

    public function json(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Kurikulum::get_daftar_kurikulum($request->id, $search, $start, $length);
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
            'nama_kurikulum' => 'required',
            'tahun_kurikulum' => 'required',
            'kd_prodi' => 'required',
            'sks_lulus' => 'required'
        ]);
        $data = Kurikulum::insup_kurikulum('00000000-0000-0000-0000-000000000000', $request->nama_kurikulum, $request->tahun_kurikulum, $request->kd_prodi, $request->sks_lulus);
        return response()->json($data);
    }

    public function update(Request $request) {
        $request->validate([
            'id' => 'required',
            'nama_kurikulum' => 'required',
            'tahun_kurikulum' => 'required',
            'kd_prodi' => 'required',
            'sks_lulus' => 'required'
        ]);
        $data = Kurikulum::insup_kurikulum($request->id, $request->nama_kurikulum, $request->tahun_kurikulum, $request->kd_prodi, $request->sks_lulus);
        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required'
        ]);
        $data = Kurikulum::set_aktif_kurikulum($request->id, $request->status);
        return response()->json($data);
    }


}
