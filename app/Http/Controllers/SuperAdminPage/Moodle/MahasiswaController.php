<?php

namespace App\Http\Controllers\SuperAdminPage\Moodle;

use App\Exports\Siakad\TanggunganMahasiswa;
use App\Http\Controllers\Controller;
use App\Models\MOODLE_MODEL\Mahasiswa;
use App\Models\SIAKAD_MODEL\keu_tblTanggunganMahasiswa;
use App\Models\SIAKAD_MODEL\keu_tblTransaksiMahasiswa;
use App\Models\SIAKAD_MODEL\tblMahasiswa;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class MahasiswaController extends Controller
{
    public function index()
    {
        $menu = "Mahasiswa | Moodle";
        $angkatan = tblMahasiswa::getAngkatan();
        $jml_record = Mahasiswa::get_asynchron_user();
        return view('super_admin_page.moodle.mahasiswa', compact('menu', 'angkatan', 'jml_record'));
    }

    public function suspended()
    {
        $menu = "Suspended Mahasiswa | Moodle";
        $angkatan = tblMahasiswa::getAngkatan();
        $jml_record = Mahasiswa::get_asynchron_user();
        return view('super_admin_page.moodle.suspended_mahasiswa', compact('menu', 'angkatan', 'jml_record'));
    }

    public function json_get_daftar_mahasiswa(Request $request)
    {
        $request->validate([
            'angkatan' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Mahasiswa::get_daftar_mahasiswa($request->angkatan, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function json_get_daftar_suspended_mahasiswa(Request $request)
    {
        $request->validate([
            'angkatan' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Mahasiswa::get_daftar_suspended_mahasiswa($request->angkatan, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function json_get_tanggungan_mahasiswa(Request $request)
    {
        $request->validate([
            'npk' => 'required'
        ]);
        $tanggungan = keu_tblTanggunganMahasiswa::getTanggunganMahasiswaByNPK($request->npk);
        return response()->json($tanggungan, 200);
    }

    public function json_get_transaki_mahasiswa(Request $request)
    {
        $request->validate([
            'npk' => 'required'
        ]);
        $tanggungan = keu_tblTransaksiMahasiswa::get_transaksi_mahasiswa($request->npk);
        return response()->json($tanggungan, 200);
    }

    public function json_get_tanggungan_mahasiswa_siakad(Request $request)
    {
        $request->validate([
            'batas' => 'required',
            'angkatan' => 'required',
            'jenis_kelas' => 'required',
        ]);
        $tanggungan = keu_tblTanggunganMahasiswa::get_daftar_tanggungan_suspend($request->batas, $request->angkatan, $request->jenis_kelas);
        return response()->json($tanggungan, 200);
    }

    public function suspend_mahasiswa_by_npk(Request $request)
    {
        $request->validate([
            'npk' => 'required',
            'alasan' => 'required',
        ]);
        $is_in_suspended_criteria = \App\Models\Akademik\Mahasiswa::is_in_suspended_criteria($request->npk)->status;
        if ($is_in_suspended_criteria == 1)
            $data = Mahasiswa::suspend_mahasiswa($request->npk, $request->alasan);
        else
            $data = Mahasiswa::suspend_mahasiswa("00", $request->alasan);
        return response()->json($data, 200);
    }

    public function un_suspend_mahasiswa_by_npk(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'user_type' => 'required',
        ]);
        $data = Mahasiswa::unsuspend_mahasiswa($request->user_type, $request->username);
        return response()->json($data, 200);
    }

    public function json_get_daftar_mahasiswa_data_center(Request $request)
    {
        $request->validate([
            'angkatan' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Mahasiswa::get_daftar_mahasiswa($request->angkatan, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function json_get_mahasiswa_aktif(Request $request)
    {
        $request->validate([
            'angkatan' => 'required'
        ]);
        $data = \App\Models\SIAKAD_MODEL\tblMahasiswa::getMahasiswaAktif($request->angkatan);
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

    public function json_get_mahasiswa_by_npk(Request $request)
    {
        $request->validate([
            'npk' => 'required'
        ]);
        $data = \App\Models\SIAKAD_MODEL\tblMahasiswa::getMahasiswaByNpk($request->npk);
        return response()->json($data, 200);
    }

    public function json_get_mahasiswa_by_npk_data_center(Request $request)
    {
        $request->validate([
            'npk' => 'required'
        ]);
        $data = \App\Models\SIAKAD_MODEL\tblMahasiswa::getMahasiswaByNpk($request->npk);
        return response()->json($data, 200);
    }

    public function json_syncron(Request $request)
    {
        $request->validate([
            'npk' => 'required',
            'nama' => 'required',
            'tahun_akademik' => 'required',
            'password' => 'required',
            'kd_prodi' => 'required',
            'alamat_rumah' => 'required',
            'nama_prodi' => 'required',
            'jenis_pendanaan' => 'required',
        ]);
        $data = Mahasiswa::sync_mahasiswa($request->npk, $request->nama, $request->tahun_akademik, $request->password, $request->kd_prodi, $request->alamat_rumah, $request->hp, $request->kota, $request->email, $request->nama_prodi, $request->jenis_pendanaan);
        if ($data->is_success)
            return response()->json($data, 200);
        else
            return response()->json($data, 500);
    }

    public function json_syncron_data_center(Request $request)
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
            $request->kode_pos_rumah, $request->inf_warga_negara, $request->email, $request->nik, $request->rt, $request->rw, $request->ds_kel, $request->nama_ibu, $pass, $request->angkatan, $request->jenis_kelas, $request->judul_skripsi, $request->ipk);
        if ($data->is_success)
            return response()->json($data, 200);
        else
            return response()->json($data, 500);
    }

    public function testTelegram()
    {
        $response = Telegram::getMe();
        dd($response);
    }
}
