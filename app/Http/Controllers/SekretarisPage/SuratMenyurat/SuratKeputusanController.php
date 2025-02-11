<?php

namespace App\Http\Controllers\SekretarisPage\SuratMenyurat;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\Karyawan;
use App\Models\Organisasi\SuratKeputusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Uuid;

class SuratKeputusanController extends Controller
{
    public function index()
    {
        $menu = 'Pengelolaan Surat Keputusan';
        $tahun_sk = SuratKeputusan::daftar_tahun_sk();
        $karyawan = Karyawan::get_daftar_karyawan();
        return view('sekretaris_page/surat_menyurat/surat_keputusan', compact('menu', 'tahun_sk', 'karyawan'));
    }

    public function json_daftar_sk(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = SuratKeputusan::daftar_sk($start, $length, $search, $request->tahun);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function insert_sk(Request $request)
    {
        $request->validate([
            'nomor' => 'required', 'tgl' => 'required',
            'nama_sk' => 'required', 'path' => 'required|max:5000',
        ], [
            'nomor.required' => 'Pastikan Nomor SK Terisi',
            'tgl.required' => 'Pastikan Tanggal SK Terisi',
            'nama_sk.required' => 'Pastikan Nama SK Terisi',
            'path.required' => 'Pastikan File Arsip SK Terisi',
            'path.max' => 'Pastikan ukuran Arsip SK Tidak Lebih dari 5MB'
        ]);
        $dok = $request->file('path');
        $file_name = Uuid::uuid4() . '.' . $dok->getClientOriginalExtension();
        if ($request->pilihan_akses == "-2" || $request->pilihan_akses == -2)
            $SK = SuratKeputusan::insert_sk($request->nomor, $request->tgl, $request->nama_sk, 'files/arsip_surat_menyurat/surat_keputusan/' . $file_name, $request->akses ? implode(',', $request->akses) : null);
        else
            $SK = SuratKeputusan::insert_sk($request->nomor, $request->tgl, $request->nama_sk, 'files/arsip_surat_menyurat/surat_keputusan/' . $file_name, '00000000-0000-0000-0000-000000000000', $request->pilihan_akses);
        if ($SK->status == 1) {
            $destinationPath = 'files/arsip_surat_menyurat/surat_keputusan/';
            $dok->move($destinationPath, $file_name);
            Session::flash('success_message', $SK->keterangan);
            return redirect()->back();
        } else {
            Session::flash('failed_message', $SK->keterangan);
            return redirect()->back();
        }
    }

    public function delete_sk(Request $request)
    {
        $request->validate([
            'id_sk' => 'required'
        ]);
        $data = SuratKeputusan::delete_sk($request->id_sk);
        if ($data->status == 1)
            if (File::exists(public_path($data->path_sk))) {
                File::delete(public_path($data->path_sk));
            }
        return response()->json($data, 200);
    }

    public function detail_partisipan_sk($id_surat)
    {
        $menu = 'Pengelolaan Surat Keputusan';
        $surat = SuratKeputusan::detail_sk($id_surat);
        $karyawan = Karyawan::get_daftar_karyawan();
        return view('sekretaris_page/surat_menyurat/detail_partisipan_surat_keputusan', compact('menu', 'surat', 'id_surat', 'karyawan'));
    }

    public function json_get_daftar_partisipan_sk(Request $request)
    {
        $request->validate([
            'id_sk' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = SuratKeputusan::daftar_partisipan_sk($request->id_sk, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function add_partisipan_sk(Request $request)
    {
        $request->validate([
            'id_sk' => 'required',
            'id_personal' => 'required',
        ]);
        $data = SuratKeputusan::insert_partisipan_sk($request->id_sk, $request->id_personal);
        return response()->json($data, 200);
    }

    public function delete_partisipan_sk(Request $request)
    {
        $request->validate([
            'id_sk' => 'required',
            'id_personal' => 'required',
        ]);
        $data = SuratKeputusan::delete_partisipan_sk($request->id_sk, $request->id_personal);
        return response()->json($data, 200);
    }
}
