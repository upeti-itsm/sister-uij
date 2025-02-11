<?php

namespace App\Http\Controllers\AdminAkademikPage\Akademik\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Akademik\Mahasiswa;
use App\Models\Akademik\ProgramStudi;
use App\Models\SIAKAD_MODEL\tblMahasiswa;
use Illuminate\Http\Request;

class SinkronisasiMahasiswaSiakadController extends Controller
{
    public function index()
    {
        $menu = 'Sinkronisasi Mahasiswa dengan Siakad';
        $program_studi = ProgramStudi::get_program_studi("0", "0", "", true, -1);
        $angkatan = Mahasiswa::get_list_angkatan();
        $angkatan_siakad = tblMahasiswa::getAngkatan();
        return view('admin_akademik_page.akademik.mahasiswa.sinkronisasi_mahasiswa_siakad', compact('menu', 'program_studi', 'angkatan', 'angkatan_siakad'));
    }

    public function json_get_daftar_mahasiswa(Request $request)
    {
        $request->validate([
            'prodi' => 'required',
            'angkatan' => 'required',
            'status' => 'required',
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = Mahasiswa::get_mahasiswa($request->prodi, "0", $start, $length, $search, 'x', $request->angkatan, -1, $request->status);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function json_get_mahasiswa_by_angkatan(Request $request)
    {
        $request->validate([
            'angkatan' => 'required'
        ]);
        $data = \App\Models\SIAKAD_MODEL\tblMahasiswa::getMahasiswaByAngkatan($request->angkatan);
        return response()->json($data, 200);
    }

    public function json_get_mahasiswa_by_nim(Request $request)
    {
        $request->validate([
            'nim' => 'required'
        ]);
        $data = \App\Models\SIAKAD_MODEL\tblMahasiswa::getMahasiswaByNpk($request->nim);
        return response()->json($data, 200);
    }

    public function json_syncron_data(Request $request)
    {
        $pass = 'null';
        if (!empty($request->password))
            $pass = $request->password;
        $request->validate([
            'npk' => 'required',
            'status_aktif' => 'required',
            'program_id' => 'required',
            'nama_lengkap' => 'required',
            'angkatan' => 'required'
        ]);
        $data = \App\Models\Akademik\Mahasiswa::sync_mahasiswa_with_siakad($request->npk, $request->inf_nisn, $request->dosen_wali, $request->tgl_lulus_sma, $request->inf_jurusan_sma, $request->sekolah_asal, $request->inf_tgl_lulus, $request->inf_nomor_ijazah, $request->inf_nomor_transkrip, $request->status_aktif, $request->program_id, $request->konsentrasi_id,
            $request->nama_wali, $request->pekerjaan_wali, $request->jenis_mahasiswa, $request->jenis_pendanaan, $request->nomor_seri_ijazah, $request->nama_lengkap, $request->tempat_lahir, $request->tanggal_lahir, $request->jenis_kelamin, $request->agama_id, $request->status_menikah, $request->hp, $request->telepon_rumah, $request->alamat_rumah,
            $request->kode_pos_rumah, $request->inf_warga_negara, $request->email, $request->nik, $request->rt, $request->rw, $request->ds_kel, $request->nama_ibu, $pass, $request->angkatan, $request->jenis_kelas, $request->judul_skripsi, $request->ipk, $request->kota_rumah);
        $moodle = \App\Models\MOODLE_MODEL\Mahasiswa::sync_mahasiswa($request->npk, $request->nama_lengkap, $request->angkatan, $pass, $request->program_id, $request->alamat_rumah, $request->hp, $request->tempat_lahir, $request->email, $request->nama_program, $request->jenis_pendanaan);
        if ($data->is_success)
            return response()->json($data);
        else
            return response()->json($data, 500);
    }
}
