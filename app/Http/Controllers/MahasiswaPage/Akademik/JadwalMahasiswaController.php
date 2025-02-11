<?php

namespace App\Http\Controllers\MahasiswaPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Akademik\JadwalKuliahMahasiswa;
use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\Semester;
use App\Models\MOODLE_MODEL\JadwalMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class JadwalMahasiswaController extends Controller
{
    public function index()
    {
        $menu = 'Sinkronisasi Jadwal Mahasiswa dengan Siakad';
        $tahun_akademik = JadwalKuliahMahasiswa::get_tahun_akademik();
        $tahun_akademik_aktif = Semester::get_semester()[0];
        return view('mahasiswa_page.akademik.jadwal_mahasiswa', compact('menu',  'tahun_akademik', 'tahun_akademik_aktif'));
    }

    public function json_get_daftar(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = JadwalKuliahMahasiswa::get_jadwal_matakuliah_mahasiswa(Session::get('user')->nim, 'all', $request->tahun_akademik, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data);
    }

    public function json_get_jadwal_kuliah_by_tahun_akademik(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required'
        ]);
        $delete = JadwalKuliahMahasiswa::delete_jadwal_matakuliah_mahasiswa($request->tahun_akademik, Session::get('user')->nim);
        if ($delete->status) {
            $elearning = JadwalMahasiswa::delete_jadwal_mahasiswa( Session::get('user')->nim, $request->tahun_akademik);
            $data = \App\Models\SIAKAD_MODEL\vwJadwalKuliahMahasiswa::getVwJadwalKuliahMahasiswaByNim(Session::get('user')->nim, $request->tahun_akademik);
            return response()->json($data);
        } else
            return response()->json($delete, 500);
    }

    public function json_syncron_data(Request $request)
    {
        $request->validate([
            'jadwal_kuliah_id' => 'required',
            'nim' => 'required',
            'nama_mata_kuliah' => 'required',
            'kelas_id' => 'required',
            'nama_dosen' => 'required',
            'tahun_akademik' => 'required',
        ]);
        $elearning = JadwalMahasiswa::sync_jadwal_mahasiswa($request->jadwal_kuliah_id, Session::get('user')->nim, $request->nama_mata_kuliah, $request->kelas_id, $request->nama_dosen, $request->tahun_akademik);
        $data = JadwalKuliahMahasiswa::insert_jadwal_matakuliah_mahasiswa(Session::get('user')->nim, $request->jadwal_kuliah_id);
        if ($data->status)
            return response()->json($data);
        else
            return response()->json($data, 500);
    }
}
