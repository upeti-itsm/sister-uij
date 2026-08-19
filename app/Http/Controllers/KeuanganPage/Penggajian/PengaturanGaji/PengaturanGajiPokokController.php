<?php

namespace App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\Golongan;
use Illuminate\Http\Request;

class PengaturanGajiPokokController extends Controller
{
    public function index()
    {
        $menu = 'Pengaturan Gaji - Gaji Pokok';
        // $data = Golongan::get_golongan();
        // dd($data);
        return view('keuangan_page.penggajian.pengaturan_gaji.gaji_pokok', compact('menu'));
    }

    public function get_golongan(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Golongan::get_golongan(
            $start,
            $length,
            $search,
            $request->status ? $request->status : null
        );
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
