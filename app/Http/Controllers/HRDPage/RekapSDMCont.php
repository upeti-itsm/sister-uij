<?php

namespace App\Http\Controllers\HRDPage;

use App\Http\Controllers\Controller;
use App\Models\Akademik\Dosen;
use App\Models\Akademik\Mahasiswa;
use Illuminate\Http\Request;

class RekapSDMCont extends Controller
{
    public function kecukupan_dosen()
    {
        $menu = 'Rekap SDM - Kecukupan Dosen';
        $dosen = Dosen::get_kecukupan_dosen();
        return view('hrd_page.data_kepegawaian.rekap_sdm.kecukupan_dosen', compact('menu', 'dosen'));
    }

    public function jabatan_akademik_dosen()
    {
        $menu = 'Rekap SDM - Jabatan Akademik';
        $dosen = Dosen::get_jabatan_akademik_dosen();
        return view('hrd_page.data_kepegawaian.rekap_sdm.jabatan_akademik', compact('menu', 'dosen'));
    }

    public function sertifikasi_dosen()
    {
        $menu = 'Rekap SDM - Sertifikasi Dosen';
        $dosen = Dosen::get_sertifikasi_dosen();
        return view('hrd_page.data_kepegawaian.rekap_sdm.sertifikasi_dosen', compact('menu', 'dosen'));
    }

    public function dosen_tidak_tetap()
    {
        $menu = 'Rekap SDM - Dosen Tidak Tetap';
        $dosen = Dosen::get_dosen_tidak_tetap();
        return view('hrd_page.data_kepegawaian.rekap_sdm.dosen_tidak_tetap', compact('menu', 'dosen'));
    }

    public function rasio_dosen()
    {
        $menu = 'Rekap SDM - Rasio Dosen';
        $tahun_akademik = Mahasiswa::get_list_angkatan();
        return view('hrd_page.data_kepegawaian.rekap_sdm.rasio_dosen', compact('menu', 'tahun_akademik'));
    }

    public function rasio_dosen_json(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Dosen::get_rasio_dosen('', -1, 10, $request->tahun_akademik);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function detail_dosen($jenis, $kode1 = '00000000-0000-0000-0000-000000000000', $kode2 = 0)
    {
        $menu = 'Rekap SDM - Detail Dosen';
        $get['back'] = $jenis;
        $get['id_prodi'] = $kode1;
        $get['jenis'] = $kode2;
        return view('hrd_page.data_kepegawaian.rekap_sdm.detail_dosen', compact('menu', 'get'));
    }

    public function detail_dosen_json($jenis, Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        if ($jenis == 'kecukupan' || $jenis == 'rasio')
            $data_ = Dosen::get_detail_kecukupan_dosen($request->id_prodi, $request->jenis, $search, $start, $length);
        elseif ($jenis == 'jafa')
            $data_ = Dosen::get_detail_jafa($request->id_prodi, $request->jenis, $search, $start, $length);
        elseif ($jenis == 'serdos')
            $data_ = Dosen::get_detail_serdos($request->id_prodi, $request->jenis, $search, $start, $length);
        elseif ($jenis == 'lb')
            $data_ = Dosen::get_detail_jafa_tidak_tetap($request->id_prodi, $request->jenis, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function detail_mahasiswa($id_prodi, $angkatan = '0000')
    {
        $menu = 'Rekap SDM - Mahasiswa Dosen';
        return view('hrd_page.data_kepegawaian.rekap_sdm.detail_mahasiswa', compact('menu', 'angkatan', 'id_prodi'));
    }

    public function detail_mahasiswa_json(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Dosen::get_detail_mahasiswa($request->angkatan, $request->id_prodi, $search, $start, $length);
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
