<?php

namespace App\Http\Controllers\HRDPage\Sarpras;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\Sarpras;
use Illuminate\Http\Request;

class SarprasCont extends Controller
{
    public function index()
    {
        $menu = 'Daftar Sarana Prasarana';
        return view('hrd_page.sarpras.daftar_sarpras', compact('menu'));
    }

    public function json(Request $request)
    {
        $request->validate([
            'jenis_sarpras' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Sarpras::get_daftar_sarana_prasaran($request->jenis_sarpras, $start, $length, $search);
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
