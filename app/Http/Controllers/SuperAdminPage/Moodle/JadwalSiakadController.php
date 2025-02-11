<?php

namespace App\Http\Controllers\SuperAdminPage\Moodle;

use App\Http\Controllers\Controller;
use App\Models\MOODLE_MODEL\JadwalDosen;
use App\Models\SIAKAD_MODEL\TahunAkademik;
use Illuminate\Http\Request;

class JadwalSiakadController extends Controller
{
    public function index()
    {
        $menu = "Jadwal Dosen | Moodle";
        $tahun_akademik = TahunAkademik::getTahunAkademik();
        $jml_record = JadwalDosen::get_asynchron_course();
        return view('super_admin_page.moodle.jadwal_siakad', compact('menu', 'tahun_akademik', 'jml_record'));
    }

    public function json_get_daftar_jadwal_siakad(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = JadwalDosen::get_daftar_jadwal_siakad($request->tahun_akademik, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function json_get_jadwal_siakad(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required'
        ]);
        $data = \App\Models\SIAKAD_MODEL\JadwalDosen::getVwJadwalKuliahDosen($request->tahun_akademik);
        return response()->json($data, 200);
    }

    public function json_get_jadwal_siakad_by_id(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $data = \App\Models\SIAKAD_MODEL\JadwalDosen::getVwJadwalKuliahDosenById($request->id);
        return response()->json($data, 200);
    }

    public function json_syncron(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required',
            'nama_matkul' => 'required',
            'kelas_id' => 'required',
            'prodi' => 'required',
            'tahun_akademik' => 'required',
            'nik' => 'required',
            'nama_pengajar' => 'required',
        ]);
        $data = JadwalDosen::sync_jadwal_siakad($request->id_jadwal, $request->nama_matkul, $request->kelas_id, $request->prodi, $request->tahun_akademik, $request->nik, $request->nama_pengajar, $request->nik_asisten, $request->nama_asisten);
        if ($data->is_success)
            return response()->json($data, 200);
        else
            return response()->json($data, 500);
    }

    public function json_move_to_course(Request $request){
        $request->validate([
            'tahun_akademik' => 'required'
        ]);
        $data = JadwalDosen::move_to_course($request->tahun_akademik);
        return response()->json($data, 200);
    }
}
