<?php

namespace App\Http\Controllers\SuperAdminPage\Moodle;

use App\Http\Controllers\Controller;
use App\Models\MOODLE_MODEL\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index()
    {
        $menu = "Dosen | Moodle";
        return view('super_admin_page.moodle.dosen', compact('menu'));
    }

    public function json_get_daftar_dosen(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Dosen::get_daftar_dosen("", $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function json_get_dosen_aktif(Request $request)
    {
        $data = \App\Models\SIAKAD_MODEL\staff\StafDosen::akademik_tfdaftardosen();
        return response()->json($data, 200);
    }

    public function json_get_dosen_by_nik(Request $request)
    {
        $request->validate([
            'id_staff' => 'required'
        ]);
        $data = \App\Models\SIAKAD_MODEL\staff\StafDosen::akademik_tfdaftardosen($request->id_staff);
        return response()->json($data, 200);
    }

    public function json_syncron(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'nidn' => 'required',
            'nama_lengkap' => 'required',
            'email' => 'required',
            'kota_asal' => 'required',
            'kd_prodi' => 'required',
            'nama_prodi' => 'required',
            'nomor_hp' => 'required',
            'alamat_rumah' => 'required',
            'status_dosen' => 'required',
            'karyawan_id' => 'required',
            'npwp' => 'required',
            'bank_account' => 'required',
            'masa_pensiun' => 'required',
            'status_aktif' => 'required',
            'tgl_masuk' => 'required',
            'asal_sekolah' => 'required',
            'nama' => 'required',
            'no_ktp' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'kode_pos_rumah' => 'required',
            'telepon_rumah' => 'required',
            'agama' => 'required',
            'jenis_kelamin' => 'required',
            'status_menikah' => 'required',
            'jenjang_pendidikan_akhir' => 'required',
            'nama_ibu' => 'required',
        ]);
        $data = Dosen::sync_dosen($request->username, $request->password, $request->nidn, $request->nama_lengkap, $request->email, $request->kota_asal, $request->nama_prodi, $request->nomor_hp, $request->alamat_rumah, $request->status_dosen);
        $dosen = \App\Models\Akademik\Dosen::sync_dosen_with_siakad($request->karyawan_id, $request->username, $request->password, $request->nip, $request->npwp, $request->bank_account, $request->masa_pensiun, $request->status_aktif, $request->tgl_masuk, $request->asal_sekolah, $request->nidn, $request->nama, $request->gelar_awal, $request->gelar_akhir, $request->no_ktp, $request->tempat_lahir, $request->tgl_lahir, $request->alamat_rumah, $request->kode_pos_rumah, $request->telepon_rumah, $request->nomor_hp, $request->agama, $request->jenis_kelamin, $request->status_menikah, $request->jenjang_pendidikan_akhir, $request->nama_ibu, $request->email, $request->kd_prodi);
        if ($data->is_success)
            return response()->json($data, 200);
        else
            return response()->json($data, 500);
    }
}
