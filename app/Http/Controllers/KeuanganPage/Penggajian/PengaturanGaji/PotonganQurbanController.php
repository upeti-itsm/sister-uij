<?php

namespace App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\RiwayatPotonganQurban;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PotonganQurbanController extends Controller
{
    public function index()
    {
        $menu = 'Pengaturan Gaji - Potongan Qurban';
        return view('keuangan_page.penggajian.pengaturan_gaji.potongan_qurban.potongan_qurban', compact('menu'));
    }

    public function json_get_daftar_potongan_qurban(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = RiwayatPotonganQurban::get_daftar_potongan_qurban($request->bulan, $request->tahun, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function upload(Request $request)
    {
        $collection = Excel::toArray(new \App\Imports\Organisasi\RiwayatPotonganQurban(), $request->file('file'));
        return response()->json($collection[0]);
    }

    public function insert_potongan(Request $request)
    {
        $request->validate([
            'id_karyawan' => 'required',
            'potongan' => 'required',
            'awal' => 'required',
            'akhir' => 'required'
        ]);
        $result = RiwayatPotonganQurban::insert_potongan_qurban($request->id_karyawan, $request->potongan, $request->awal, $request->akhir);
        return response()->json($result);
    }

    public function get_detail_potongan($bulan, $tahun)
    {
        $menu = 'Pengaturan Gaji - Potongan Arisan';
        return view('keuangan_page.penggajian.pengaturan_gaji.potongan_qurban.detail_potongan_qurban', compact('menu', 'bulan', 'tahun'));
    }

    public function json_get_detail_potongan_qurban(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = RiwayatPotonganQurban::get_detail_potongan_qurban($request->bulan, $request->tahun, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function update_potongan(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'nominal' => 'required'
        ]);
        $result = RiwayatPotonganQurban::update_potongan_qurban($request->id, $request->nominal);
        return response()->json($result);
    }

    public function delete_potongan(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $result = RiwayatPotonganQurban::delete_potongan_qurban($request->id);
        return response()->json($result);
    }
}
