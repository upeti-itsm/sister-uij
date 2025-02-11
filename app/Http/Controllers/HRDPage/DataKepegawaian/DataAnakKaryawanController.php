<?php

namespace App\Http\Controllers\HRDPage\DataKepegawaian;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\AnakKaryawan;
use App\Models\Organisasi\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DataAnakKaryawanController extends Controller
{
    public function index($id, $is_insert = false)
    {
        $menu = "Detail Data Pegawai - HRD";
        $karyawan = Karyawan::get_detail_karyawan_by_id_personal($id);
        return view('hrd_page.data_kepegawaian.data_anak', compact('menu', 'karyawan', 'is_insert'));
    }

    public function json_get_daftar_anak_karyawan(Request $request)
    {
        $request->validate([
            'id_personal' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = AnakKaryawan::get_daftar_anak($request->id_personal, $start, $length, $search);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function insup(Request $request)
    {
        $request->validate([
            'id_personal' => 'required',
            'nik' => 'required|max:16|min:16',
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required|date_format:Y-m-d',
        ], [
            'id_personal.required' => 'ID Karyawan Belum Di isi',
            'nik.required' => 'Pastikan NIK anak terisi',
            'nik.max' => 'Pastikan NIK tidak lebih dari 16 digit',
            'nik.min' => 'Pastikan NIK tidak kurang dari 16 digit',
            'nama.required' => 'Pastikan nama anak terisi',
            'jenis_kelamin.required' => 'Pastikan jenis kelamin sudah dipilih',
            'tempat_lahir.required' => 'Pastikan tempat lahir terisi',
            'tgl_lahir.required' => 'Pastikan tanggal lahir sudah dipilih',
            'tgl_lahir.date_format' => 'Invalid format tanggal lahir',
        ]);

        $anak = AnakKaryawan::insup_anak_karyawan($request->id_personal, $request->nama, $request->jenis_kelamin, $request->tgl_lahir, $request->nik, $request->tempat_lahir, $request->id_anak);
        if ($anak->status == 1) {
            Session::flash('success_message', $anak->keterangan);
            return redirect()->back();
        } else {
            Session::flash('failed_message', $anak->keterangan);
            return redirect()->back()->withInput();
        }
    }

    public function json_detail_anak($id)
    {
        $anak = AnakKaryawan::get_detail_anak($id);
        return response()->json($anak, 200);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $anak = AnakKaryawan::delete_anak_karyawan($request->id);
        return response()->json($anak, 200);
    }
}
