<?php

namespace App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\RiwayatUpahMinimumRegional;
use Illuminate\Http\Request;

class PengaturanUMRController extends Controller
{
    public function index()
    {
        $menu = 'Pengaturan Gaji - Pengaturan UMR';
        return view('keuangan_page.penggajian.pengaturan_gaji.pengaturan_umr.daftar_umr', compact('menu'));
    }

    public function json_get_daftar(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = RiwayatUpahMinimumRegional::get_daftar_umr($request->bulan, $request->tahun, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function insert_umr(Request $request)
    {
        $request->validate([
            'nilai_umr' => 'required'
        ]);
        $result = RiwayatUpahMinimumRegional::insert_umr($request->nilai_umr);
        return response()->json($result);
    }

    public function get_detail_umr($id_umr)
    {
        $menu = 'Pengaturan Gaji - Pengaturan UMR';
        return view('keuangan_page.penggajian.pengaturan_gaji.pengaturan_umr.detail_umr', compact('menu', 'id_umr'));
    }

    public function json_get_detail_umr(Request $request)
    {
        $request->validate([
            'id_umr' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = RiwayatUpahMinimumRegional::get_detail_umr($request->id_umr, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }
}
