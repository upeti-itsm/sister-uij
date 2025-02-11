<?php

namespace App\Http\Controllers\SuperAdminPage\Siakad;

use App\Http\Controllers\Controller;
use App\Models\SIAKAD_MODEL\staff\StafDosen;
use App\Models\SIAKAD_MODEL\tblKaryawan;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function daftar_dosen()
    {
        $menu = "Dosen | Siakad";
        return view('super_admin_page.siakad.dosen', compact('menu'));
    }

    public function json_daftar_dosen(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = StafDosen::daftar_dosen($search, $start, $length);
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
