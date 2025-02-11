<?php

namespace App\Http\Controllers\SekretarisPage\SuratMenyurat;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\Karyawan;
use App\Models\Organisasi\SuratKeluarMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Uuid;

class SuratKeluarMasukController extends Controller
{
    public function index($jenis_surat = 'SK')
    {
        $menu = 'Surat Keluar Masuk';
        $tahun_surat_keluar = SuratKeluarMasuk::daftar_tahun_surat(1);
        $tahun_surat_masuk = SuratKeluarMasuk::daftar_tahun_surat(2);
        $karyawan = Karyawan::get_daftar_karyawan();
        return view('sekretaris_page/surat_menyurat/surat_keluar_masuk', compact('menu', 'tahun_surat_keluar', 'tahun_surat_masuk', 'karyawan', 'jenis_surat'));
    }

    public function json_get_daftar_surat_keluar(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = SuratKeluarMasuk::daftar_surat_keluar($start, $length, $search, $request->tahun);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function json_get_daftar_surat_masuk(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = SuratKeluarMasuk::daftar_surat_masuk($start, $length, $search, $request->tahun);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function insert_surat_keluar(Request $request)
    {
        $request->validate([
            'nomor' => 'required', 'tgl' => 'required',
            'perihal' => 'required', 'kode' => 'required',
            'path' => 'required|max:5000', 'penerima' => 'required',
        ], [
            'nomor.required' => 'Pastikan Nomor Surat Terisi',
            'tgl.required' => 'Pastikan Tanggal Surat Terisi',
            'perihal.required' => 'Pastikan Perihal Surat Terisi',
            'kode.required' => 'Pastikan Kode Surat Terisi',
            'penerima.required' => 'Pastikan Penerima Surat Terisi',
            'path.required' => 'Pastikan File Arsip Surat Terisi',
            'path.max' => 'Pastikan ukuran Arsip Tidak Lebih dari 5MB'
        ]);
        //$request->dd();
        $dok = $request->file('path');
        $file_name = Uuid::uuid4() . '.' . $dok->getClientOriginalExtension();
        if ($request->pilihan_akses == "-2" || $request->pilihan_akses == -2)
            $surat_keluar = SuratKeluarMasuk::insert_surat_keluar($request->nomor, $request->tgl, $request->perihal, $request->kode, 'files/arsip_surat_menyurat/surat_keluar_masuk/surat_keluar/' . $file_name, $request->penerima, $request->akses ? implode(',', $request->akses) : null);
        else
            $surat_keluar = SuratKeluarMasuk::insert_surat_keluar($request->nomor, $request->tgl, $request->perihal, $request->kode, 'files/arsip_surat_menyurat/surat_keluar_masuk/surat_keluar/' . $file_name, $request->penerima, '00000000-0000-0000-0000-000000000000', $request->pilihan_akses);
        if ($surat_keluar->status == 1) {
            $destinationPath = 'files/arsip_surat_menyurat/surat_keluar_masuk/surat_keluar/';
            $dok->move($destinationPath, $file_name);
            Session::flash('success_message', $surat_keluar->keterangan);
            return redirect()->back();
        } else {
            Session::flash('failed_message', $surat_keluar->keterangan);
            return redirect()->back();
        }
    }

    public function insert_surat_masuk(Request $request)
    {
        $request->validate([
            'nomor' => 'required', 'tgl_surat' => 'required', 'nomor_berkas' => 'required',
            'tgl_diterima' => 'required', 'perihal' => 'required', 'kode' => 'required',
            'path' => 'required|max:5000', 'pengirim' => 'required',
        ], [
            'nomor.required' => 'Pastikan Nomor Surat Terisi',
            'nomor_berkas.required' => 'Pastikan Nomor Berkas Terisi',
            'tgl_surat.required' => 'Pastikan Tanggal Surat Terisi',
            'tgl_diterima.required' => 'Pastikan Tanggal Diterima Terisi',
            'perihal.required' => 'Pastikan Perihal Surat Terisi',
            'kode.required' => 'Pastikan Kode Surat Terisi',
            'pengirim.required' => 'Pastikan Pengirim Surat Terisi',
            'path.required' => 'Pastikan File Arsip Surat Terisi',
            'path.max' => 'Pastikan ukuran Arsip Tidak Lebih dari 5MB'
        ]);
        $dok = $request->file('path');
        $file_name = Uuid::uuid4() . '.' . $dok->getClientOriginalExtension();
        if ($request->pilihan_akses == "-2" || $request->pilihan_akses == -2)
            $surat_masuk = SuratKeluarMasuk::insert_surat_masuk($request->nomor, $request->tgl_surat, $request->tgl_diterima, $request->perihal, $request->kode, 'files/arsip_surat_menyurat/surat_keluar_masuk/surat_masuk/' . $file_name, $request->pengirim, $request->nomor_berkas, $request->akses ? implode(',', $request->akses) : null);
        else
            $surat_masuk = SuratKeluarMasuk::insert_surat_masuk($request->nomor, $request->tgl_surat, $request->tgl_diterima, $request->perihal, $request->kode, 'files/arsip_surat_menyurat/surat_keluar_masuk/surat_masuk/' . $file_name, $request->pengirim, $request->nomor_berkas, '00000000-0000-0000-0000-000000000000', $request->pilihan_akses);
        if ($surat_masuk->status == 1) {
            $destinationPath = 'files/arsip_surat_menyurat/surat_keluar_masuk/surat_masuk/';
            $dok->move($destinationPath, $file_name);
            Session::flash('success_message', $surat_masuk->keterangan);
            return redirect(route('sekretaris.surat_menyurat.surat_keluar_masuk.index', ['jenis_surat' => 'SM']));
        } else {
            Session::flash('failed_message', $surat_masuk->keterangan);
            return redirect(route('sekretaris.surat_menyurat.surat_keluar_masuk.index', ['jenis_surat' => 'SM']));
        }
    }

    public function delete_surat_keluar(Request $request)
    {
        $request->validate([
            'id_surat' => 'required'
        ]);
        $data = SuratKeluarMasuk::delete_surat_keluar($request->id_surat);
        if ($data->status == 1)
            if (File::exists(public_path($data->path_surat_keluar))) {
                File::delete(public_path($data->path_surat_keluar));
            }
        return response()->json($data, 200);
    }

    public function delete_surat_masuk(Request $request)
    {
        $request->validate([
            'id_surat' => 'required'
        ]);
        $data = SuratKeluarMasuk::delete_surat_masuk($request->id_surat);
        if ($data->status == 1)
            if (File::exists(public_path($data->path_surat_masuk))) {
                File::delete(public_path($data->path_surat_masuk));
            }
        return response()->json($data, 200);
    }

    public function detail_partisipan_surat_keluar($id_surat)
    {
        $menu = 'Surat Keluar Masuk';
        $surat = SuratKeluarMasuk::detail_surat_keluar($id_surat);
        $karyawan = Karyawan::get_daftar_karyawan();
        return view('sekretaris_page/surat_menyurat/detail_partisipan_surat_keluar', compact('menu', 'surat', 'id_surat', 'karyawan'));
    }

    public function detail_partisipan_surat_masuk($id_surat)
    {
        $menu = 'Surat Keluar Masuk';
        $surat = SuratKeluarMasuk::detail_surat_masuk($id_surat);
        $karyawan = Karyawan::get_daftar_karyawan();
        return view('sekretaris_page/surat_menyurat/detail_partisipan_surat_masuk', compact('menu', 'surat', 'id_surat', 'karyawan'));
    }

    public function json_get_daftar_partisipan_surat_keluar(Request $request)
    {
        $request->validate([
            'id_surat' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = SuratKeluarMasuk::daftar_partisipan_surat_keluar($request->id_surat, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function json_get_daftar_partisipan_surat_masuk(Request $request)
    {
        $request->validate([
            'id_surat' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = SuratKeluarMasuk::daftar_partisipan_surat_masuk($request->id_surat, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function add_partisipan_surat_keluar(Request $request)
    {
        $request->validate([
            'id_surat' => 'required',
            'id_personal' => 'required',
        ]);
        $data = SuratKeluarMasuk::insert_partisipan_surat_keluar($request->id_surat, $request->id_personal);
        return response()->json($data, 200);
    }

    public function add_partisipan_surat_masuk(Request $request)
    {
        $request->validate([
            'id_surat' => 'required',
            'id_personal' => 'required',
        ]);
        $data = SuratKeluarMasuk::insert_partisipan_surat_masuk($request->id_surat, $request->id_personal);
        return response()->json($data, 200);
    }

    public function delete_partisipan_surat_keluar(Request $request)
    {
        $request->validate([
            'id_surat' => 'required',
            'id_personal' => 'required',
        ]);
        $data = SuratKeluarMasuk::delete_partisipan_surat_keluar($request->id_surat, $request->id_personal);
        return response()->json($data, 200);
    }

    public function delete_partisipan_surat_masuk(Request $request)
    {
        $request->validate([
            'id_surat' => 'required',
            'id_personal' => 'required',
        ]);
        $data = SuratKeluarMasuk::delete_partisipan_surat_masuk($request->id_surat, $request->id_personal);
        return response()->json($data, 200);
    }
}
