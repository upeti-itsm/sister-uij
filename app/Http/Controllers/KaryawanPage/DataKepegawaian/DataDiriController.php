<?php

namespace App\Http\Controllers\KaryawanPage\DataKepegawaian;

use App\Http\Controllers\Controller;
use App\Models\Akademik\ProgramStudi;
use App\Models\Organisasi\Karyawan;
use App\Models\Referensi\Agama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class DataDiriController extends Controller
{
    public function index(){
        $menu = "Melihat Data Diri";
        $karyawan = Karyawan::get_detail_karyawan_by_id_personal(Session::get('user')->id_personal);
        return view('karyawan_page.data_kepegawaian.data_diri', compact('menu', 'karyawan'));
    }

    public function ubah_data_diri(){
        $menu = "Mengubah Data Diri";
        $karyawan = Karyawan::get_detail_karyawan_by_id_personal(Session::get('user')->id_personal);
        $agama = Agama::list_agama();
        return view('karyawan_page.data_kepegawaian.form_ubah_data_diri', compact('menu', 'karyawan', 'agama'));
    }

    public function update_path_photo(Request $request)
    {
        $request->validate([
            'file' => 'required|max:10000|mimes:jpg,jpeg,png'
        ], [
            'file.required' => 'Pastikan File Photo sudah terisi',
            'file.max' => 'Pastikan ukuran file tidak lebih dari 10MB',
            'file.mimes' => 'Pastikan ekstensi file JPG/JPEG/PNG',
        ]);
        $t = time();
        $file = $request->file('file');
        $file_name = 'photo_profile_' . date("Y_m_d_h_m_s", $t) . '.' . $file->getClientOriginalExtension();
        $dokumen = Session::get('karyawan');
        if (!empty($dokumen)) {
            File::delete('files/profil_karyawan/' . Session::get('user')->id_personal . '/' . $dokumen->path_photo);
        }
        $photo_profile = Karyawan::update_path_photo(Session::get('user')->id_personal, $file_name);
        if ($photo_profile->status == 1) {
            $karyawan = Karyawan::get_detail_karyawan_by_id_personal(Session::get('user')->id_personal);
            Session::forget('karyawan');
            Session::put('karyawan', $karyawan);
            // Proses Upload File
            $destinationPath = 'files/profil_karyawan/' . Session::get('user')->id_personal;
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
            $file->move($destinationPath, $file_name);
        }
        return response()->json($photo_profile, 200);
    }

    public function update_data_personal(Request $request){
        $request->validate([
            'no_ktp' => 'required|max:16|min:16',
            'nama' => 'required',
            'tempat_lahir' => 'required', 'tgl_lahir' => 'required',
            'no_hp' => 'required|min:10|max:13',
            'email' => 'required|email:rfc,dns',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'alamat' => 'required'
        ], [
            'no_ktp.required' => 'Pastikan Nomor KTP terisi',
            'no_ktp.max' => 'Pastikan Nomor KTP tidak lebih dari 16 Digit',
            'no_ktp.min' => 'Pastikan Nomor KTP tidak kurang dari 16 Digit',
            'nama.required' => 'Pastikan Nama Terisi',
            'tempat_lahir.required' => 'Pastikan Tempat Lahir Terisi',
            'tgl_lahir.required' => 'Pastikan Tanggal Lahir Terisi',
            'no_hp.required' => 'Pastikan Nomor HP Terisi',
            'no_hp.min' => 'Pastikan Nomor HP tidak kurang Dari 10 Karakter',
            'no_hp.max' => 'Pastikan Nomor HP tidak Lebih Dari 13 Karakter',
            'email.email' => 'Pastikan Email terisi dengan alamat email yang benar',
            'email.required' => 'Pastikan Email Terisi',
            'jenis_kelamin.required' => 'Pastikan Anda Sudah Memilih Jenis Kelamin',
            'agama.required' => 'Pastikan Anda Sudah Memilih Agama',
            'alamat.required' => 'Pastikan Anda Sudah Mengisi Alamat',
        ]);
        $karyawan = Karyawan::update_data_personal_on_karyawan(Session::get('user')->id_personal, $request->no_ktp, $request->nama, $request->gelar_depan, $request->gelar_belakang, $request->tempat_lahir, $request->tgl_lahir, $request->no_hp, $request->email, $request->jenis_kelamin, $request->agama, $request->alamat);
        if ($karyawan->status == 1) {
            Session::flash('success_message', $karyawan->keterangan);
            return redirect()->back();
        } else {
            Session::flash('failed_message', $karyawan->keterangan);
            return redirect()->back()->withInput();
        }
    }
}
