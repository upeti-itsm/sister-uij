<?php

namespace App\Http\Controllers\KaryawanPage\SuratMenyurat;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\SuratKeputusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SKController extends Controller
{
    public function index(){
        $menu = "Melihat Surat Keputusan (SK)";
        $tahun_sk = SuratKeputusan::daftar_tahun_sk();
        return view('karyawan_page.surat_menyurat.surat_keputusan', compact('menu', 'tahun_sk'));
    }

    public function json_get_daftar_sk(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = SuratKeputusan::daftar_sk_on_karyawan(Session::get('user')->id_personal, $start, $length, $search, $request->tahun);
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
