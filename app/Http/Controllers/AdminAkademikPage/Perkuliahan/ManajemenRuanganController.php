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
        return view('admin_akademik_page.perkuliahan.manajemen_ruangan', compact('menu'));
    }

    public function json(Request $request)
    {
        // $request->validate([
        //     'id' => 'required'
        // ]);

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
}
