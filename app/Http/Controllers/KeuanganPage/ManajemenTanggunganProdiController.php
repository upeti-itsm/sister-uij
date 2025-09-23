<?php

namespace App\Http\Controllers\KeuanganPage;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\ManajemenTanggunganProdi;

use Illuminate\Http\Request;

class ManajemenTanggunganProdiController extends Controller
{
    public function index()
    {
        $menu = 'Manajemen Tanggungan Prodi';
        $prodi = ManajemenTanggunganProdi::get_daftar_prodi();
        $periodisasi = ManajemenTanggunganProdi::get_daftar_periodisasi();
        $jenis_tagihan = ManajemenTanggunganProdi::get_daftar_jenis_tagihan();
        return view('keuangan_page.manajemen_tanggungan_prodi', compact('menu', 'prodi', 'periodisasi', 'jenis_tagihan'));
    }

    public function json(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];

        $id_tagihan_prodi = $request->id ?? '00000000-0000-0000-0000-000000000000';
        $kd_prodi = $request->kd_prodi ?? 'all';
        $sts_aktif = $request->sts_aktif ?? true;

        $data_ = ManajemenTanggunganProdi::get_daftar_tagihan_prodi($id_tagihan_prodi, $kd_prodi, $sts_aktif, $search, $start, $length);

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
            'prodi' => 'required',
            'jenis_tagihan' => 'required',
            'jumlah_tagihan' => 'required|numeric',
            'tipe_periodisasi' => 'required',
            'semester_mulai' => 'required',
            'semester_selesai' => 'nullable',
            'status_tanggungan' => 'required'
        ]);

        $status = ($request->status_tanggungan == '1') ? true : false;

        $data = ManajemenTanggunganProdi::insup_tanggungan_prodi($request->prodi, $request->jenis_tagihan, $request->jumlah_tagihan, $request->semester_mulai, $request->semester_selesai, $request->tipe_periodisasi, $status, $request->id);

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $request->validate([
            'prodi' => 'required',
            'jenis_tagihan' => 'required',
            'jumlah_tagihan' => 'required|numeric',
            'tipe_periodisasi' => 'required',
            'semester_mulai' => 'required',
            'semester_selesai' => 'nullable',
            'status_tanggungan' => 'required'
        ]);

        $status = ($request->status_tanggungan == '1') ? true : false;

        $data = ManajemenTanggunganProdi::insup_tanggungan_prodi($request->prodi, $request->jenis_tagihan, $request->jumlah_tagihan, $request->semester_mulai, $request->semester_selesai, $request->tipe_periodisasi, $status, $request->id);

        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $data = ManajemenTanggunganProdi::delete_tanggungan_prodi($request->id);
        return response()->json($data);
    }
}
