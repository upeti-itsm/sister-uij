<?php

namespace App\Http\Controllers\KaryawanPage\SuratMenyurat;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\SuratKeluarMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SuratController extends Controller
{
    public function index(){
        $menu = "Melihat Surat";
        $tahun_surat = SuratKeluarMasuk::daftar_tahun_surat(1);
        return view('karyawan_page.surat_menyurat.surat', compact('menu', 'tahun_surat'));
    }

    public function json_get_daftar_surat(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = SuratKeluarMasuk::daftar_surat(Session::get('user')->id_personal, $start, $length, $search, $request->tahun);
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
