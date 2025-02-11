<?php

namespace App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah;

use App\Http\Controllers\Controller;
use App\Models\Akademik\JadwalKuliahMahasiswa;
use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\Semester;
use App\Models\MOODLE_MODEL\JadwalMahasiswa;
use Illuminate\Http\Request;

class JadwalMahasiswaController extends Controller
{
    public function index()
    {
        $menu = 'Sinkronisasi Jadwal Mahasiswa dengan Siakad';
        $program_studi = ProgramStudi::get_program_studi("0", "0", "", true, -1);
        $tahun_akademik_siakad = Semester::get_semester();
        $tahun_akademik = JadwalKuliahMahasiswa::get_tahun_akademik();
        return view('admin_akademik_page.akademik.matakuliah.jadwal_kuliah_mahasiswa', compact('menu', 'program_studi', 'tahun_akademik', 'tahun_akademik_siakad'));
    }

    public function json_get_daftar(Request $request)
    {
        $request->validate([
            'prodi' => 'required',
            'tahun_akademik' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = JadwalKuliahMahasiswa::get_jadwal_matakuliah_mahasiswa('all', $request->prodi, $request->tahun_akademik, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function json_get_jadwal_kuliah_by_tahun_akademik(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required'
        ]);
        $delete = JadwalKuliahMahasiswa::delete_jadwal_matakuliah_mahasiswa($request->tahun_akademik);
        if ($delete->status) {
            $elearning = JadwalMahasiswa::delete_jadwal_mahasiswa_by_tahun_akademik($request->tahun_akademik);
            $data = \App\Models\SIAKAD_MODEL\vwJadwalKuliahMahasiswa::getVwJadwalKuliahMahasiswa($request->tahun_akademik);
            return response()->json($data);
        } else
            return response()->json($delete, 500);
    }

    public function json_get_jadwal_kuliah_by_tahun_akademik_nim(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required',
            'nim' => 'required'
        ]);
        $delete = JadwalKuliahMahasiswa::delete_jadwal_matakuliah_mahasiswa($request->tahun_akademik, $request->nim);
        if ($delete->status) {
            $elearning = JadwalMahasiswa::delete_jadwal_mahasiswa($request->nim, $request->tahun_akademik);
            $data = \App\Models\SIAKAD_MODEL\vwJadwalKuliahMahasiswa::getVwJadwalKuliahMahasiswaByNim($request->nim, $request->tahun_akademik);
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
        $elearning = JadwalMahasiswa::sync_jadwal_mahasiswa($request->jadwal_kuliah_id, $request->nim, $request->nama_mata_kuliah, $request->kelas_id, $request->nama_dosen, $request->tahun_akademik);
        $data = JadwalKuliahMahasiswa::insert_jadwal_matakuliah_mahasiswa($request->nim, $request->jadwal_kuliah_id);
        if ($data->status)
            return response()->json($data);
        else
            return response()->json($data, 500);
    }
}
