<?php

namespace App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah;

use App\Http\Controllers\Controller;
use App\Models\Akademik\JadwalMataKuliah;
use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\Semester;
use App\Models\MOODLE_MODEL\JadwalDosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class JadwalMatakuliahController extends Controller
{
    public function index($filter = -1)
    {
        $menu = 'Sinkronisasi Jadwal Kuliah dengan Siakad';
        $program_studi = ProgramStudi::get_program_studi("0", "0", "", true, -1);
        $tahun_akademik_siakad = Semester::get_semester();
        $tahun_akademik = JadwalMataKuliah::get_tahun_akademik();
        return view('admin_akademik_page.akademik.matakuliah.jadwal_matakuliah', compact('menu', 'program_studi', 'tahun_akademik', 'tahun_akademik_siakad', 'filter'));
    }

    public function json_get_daftar(Request $request)
    {
        $request->validate([
            'prodi' => 'required',
            'tahun_akademik' => 'required',
            'status' => 'required',
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = JadwalMataKuliah::get_jadwal_matakuliah($request->prodi, $request->tahun_akademik, $search, $start, $length, $request->status);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function json_get_jadwal_kuliah_siakad(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required'
        ]);
        $data = \App\Models\SIAKAD_MODEL\JadwalMataKuliah::get_jadwal_mata_kuliah($request->tahun_akademik);
        return response()->json($data, 200);
    }

    public function json_get_jadwal_by_id(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $jadwal = JadwalMataKuliah::get_detail_jadwal_kuliah($request->id);
        $data = \App\Models\SIAKAD_MODEL\JadwalMataKuliah::get_jadwal_mata_kuliah_by_id($jadwal->jadwal_kuliah_id);
        return response()->json($data, 200);
    }

    public function json_syncron_data(Request $request)
    {
        $request->validate([
            'jadwal_kuliah_id' => 'required',
            'tahun_akademik' => 'required',
            'kelas_id' => 'required',
            'ruang_id' => 'required',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'matakuliah_id' => 'required',
            'nama_mata_kuliah' => 'required',
            'kapasitas' => 'required',
            'dosen_id' => 'required',
            'asisten_id' => 'required',
            'kd_prodi' => 'required',
            'jumlah_sks' => 'required',
            'is_lab' => 'required',
            'jenis_kelas' => 'required',
            'nama_dosen' => 'required',
            'nama_asisten' => 'required',
            'nik_pengampu' => 'required',
            'nik_asisten' => 'required',
        ]);

        $moodle = JadwalDosen::sync_jadwal_siakad($request->jadwal_kuliah_id, $request->nama_mata_kuliah, $request->kelas_id, $request->kd_prodi, $request->tahun_akademik, $request->nik_pengampu, $request->nama_dosen, $request->nik_asisten, $request->nama_asisten);

        $data = JadwalMataKuliah::sync_jadwal_matakuliah_with_siakad($request->jadwal_kuliah_id, $request->tahun_akademik, $request->kelas_id,
            $request->ruang_id, $request->hari, $request->jam_mulai, $request->jam_selesai, $request->matakuliah_id,
            $request->nama_mata_kuliah, $request->kapasitas, $request->dosen_id, $request->asisten_id,
            $request->kd_prodi, $request->jumlah_sks, $request->is_lab, $request->jenis_kelas);

        if ($data->status)
            return response()->json($data);
        else
            return response()->json($data, 500);
    }

    public function detail($id)
    {
        $menu = 'Sinkronisasi Jadwal Kuliah dengan Siakad';
        $jadwal = JadwalMataKuliah::get_detail_jadwal_kuliah($id);
        return view('admin_akademik_page.akademik.matakuliah.detail_jadwal_kuliah', compact('menu', 'jadwal'));
    }

    public function set_jenis_jadwal(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'jenis_jadwal' => 'required',
        ]);
        if ($request->jenis_jadwal == 2)
            $request->validate([
                'koordinator' => 'required'
            ]);
        $jadwal = JadwalMataKuliah::set_jenis_jadwal_kuliah($request->id, $request->jenis_jadwal, $request->koordinator);
        if ($jadwal->status == 1) {
            Session::flash("success_message", $jadwal->keterangan);
            return redirect(route('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_siakad.index', ['filter' => 0]));
        } else {
            Session::flash("failed_message", $jadwal->keterangan);
            return redirect(route('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_siakad.detail', ['id' => $request->id]));
        }
    }
}
