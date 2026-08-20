<?php

namespace App\Http\Controllers\AdminAkademikPage\Perkuliahan;

use App\Http\Controllers\Controller;
use App\Models\Akademik\ManajemenRuangan;

use Illuminate\Http\Request;

class ManajemenRuanganController extends Controller
{
    public function index()
    {
        $menu = 'Manajemen Ruangan';
        $daftar_fakultas = ManajemenRuangan::get_daftar_fakultas('', -1, 10, null, 1);
        return view('admin_akademik_page.perkuliahan.manajemen_ruangan', compact('menu', 'daftar_fakultas'));
    }

    public function json(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = ManajemenRuangan::get_daftar_ruangan($request->id, $search, $start, $length);

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
            'ruang_perkuliahan' => 'required',
            'kd_fakultas' => 'required',
            'kapasitas' => 'required',
            'informasi_kelas' => 'required'
        ]);

        $status = ($request->status_ruangan == '1') ? true : false;

        $data = ManajemenRuangan::insup_ruangan($request->ruang_perkuliahan, $request->kd_fakultas, $request->kapasitas, $request->informasi_kelas, $status, 0);

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $request->validate([
            'ruang_perkuliahan' => 'required',
            'kd_fakultas' => 'required',
            'kapasitas' => 'required',
            'informasi_kelas' => 'required',
            'status_ruangan' => 'required',
            'id' => 'required',
        ]);
        $data = ManajemenRuangan::insup_ruangan($request->ruang_perkuliahan, $request->kd_fakultas, $request->kapasitas, $request->informasi_kelas, $request->status_ruangan, $request->id);
        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $data = ManajemenRuangan::delete_ruangan($request->id);
        return response()->json($data);
    }
}
