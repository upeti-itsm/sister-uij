<?php

namespace App\Http\Controllers\SuperAdminPage\Moodle;

use App\Http\Controllers\Controller;
use App\Models\MOODLE_MODEL\JadwalMahasiswa;
use App\Models\SIAKAD_MODEL\TahunAkademik;
use Illuminate\Http\Request;

class JadwalMahasiswaController extends Controller
{
    public function index()
    {
        $menu = "Jadwal Mahasiswa | Moodle";
        $tahun_akademik = TahunAkademik::getTahunAkademik();
        return view('super_admin_page.moodle.jadwal_mahasiswa', compact('menu', 'tahun_akademik'));
    }

    public function json_get_daftar_jadwal_mahasiswa(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = JadwalMahasiswa::get_daftar_jadwal_mahasiswa($request->tahun_akademik, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function json_get_krs_mahasiswa(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required'
        ]);
        $data = \App\Models\SIAKAD_MODEL\vwJadwalKuliahMahasiswa::getVwJadwalKuliahMahasiswa($request->tahun_akademik);
        return response()->json($data, 200);
    }

    public function json_get_krs_mahasiswa_by_nim(Request $request)
    {
        $request->validate([
            'nim' => 'required',
            'tahun_akademik' => 'required'
        ]);
        $data = \App\Models\SIAKAD_MODEL\vwJadwalKuliahMahasiswa::getVwJadwalKuliahMahasiswaByNim($request->nim, $request->tahun_akademik);
        if (sizeof($data) > 0) {
            $delete = JadwalMahasiswa::delete_jadwal_mahasiswa($request->nim, $request->tahun_akademik);
            if ($delete->is_success) {
                return response()->json($data, 200);
            } else {
                return response()->json($delete, 500);
            }
        } else {
            return response()->json($data, 200);
        }
    }

    public function json_get_jadwal_siakad_by_id(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $data = \App\Models\SIAKAD_MODEL\vwJadwalKuliahMahasiswa::getVwJadwalKuliahMahasiswaById($request->id);
        return response()->json($data, 200);
    }

    public function json_syncron(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required',
            'nim' => 'required',
            'nama_mata_kuliah' => 'required',
            'kelas_id' => 'required',
            'nama_dosen' => 'required',
            'tahun_akademik' => 'required'
        ]);
        $data = JadwalMahasiswa::sync_jadwal_mahasiswa($request->id_jadwal, $request->nim, $request->nama_mata_kuliah, $request->kelas_id, $request->nama_dosen, $request->tahun_akademik);
        if ($data->is_success)
            return response()->json($data, 200);
        else
            return response()->json($data, 500);
    }

    public function delete_jadwal_mahasiswa(Request $request){
        $request->validate([
            'id' => 'required',
            'nim' => 'required'
        ]);
        $jadwal = JadwalMahasiswa::delete_jadwal_mahasiswa_by_id($request->id, $request->nim);
        return response()->json($jadwal, 200);
    }
}
