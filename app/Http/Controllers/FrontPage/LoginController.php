<?php

namespace App\Http\Controllers\FrontPage;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPassword;
use App\Models\Akademik\Mahasiswa;
use App\Models\Organisasi\Karyawan;
use App\Models\Pengguna\AkunMahasiswa;
use App\Models\Pengguna\AkunPengguna;
use App\Models\Pengguna\Sertifikat;
use App\Models\Pengguna\SertifikatMahasiswa;
use App\Models\SIAKAD_MODEL\tblMahasiswa;
use App\Models\SIAKAD_MODEL\vwJadwalKuliahMahasiswa;
use App\Models\Sistem\Modul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login()
    {
        return view('front_page.login');
    }

    public function auth(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'g-recaptcha-response' => 'required|captcha'
        ],
            [
                'username.required' => 'Username is required',
                'password.required' => 'Password is required',
                'g-recaptcha-response.required' => 'Captcha is required',
            ]
        );
//        $mahasiswa = tblMahasiswa::getMahasiswaByNpk($request->username);
//        if (isset($mahasiswa->NPK)){
//            $pass = 'null';
//            if (!empty($mahasiswa->password))
//                $pass = $mahasiswa->password;
//            Mahasiswa::sync_mahasiswa_with_siakad($mahasiswa->NPK, $mahasiswa->nisn, $mahasiswa->dosen_wali, $mahasiswa->tgl_lulus_sma, $mahasiswa->inf_jurusan_sma, $mahasiswa->sekolah_asal, $mahasiswa->inf_tgl_lulus, $mahasiswa->inf_nomor_ijazah, $mahasiswa->inf_nomor_transkrip, $mahasiswa->status_aktif, $mahasiswa->program_id, $mahasiswa->konsentrasi_id,
//                $mahasiswa->nama_wali, $mahasiswa->pekerjaan_wali, $mahasiswa->jenis_mahasiswa, $mahasiswa->jenis_pendanaan, $mahasiswa->no_seri_ijazah, $mahasiswa->nama_lengkap, $mahasiswa->tempat_lahir, $mahasiswa->tgl_lahir, $mahasiswa->jenis_kelamin, $mahasiswa->agama_id, $mahasiswa->status_menikah, $mahasiswa->hp, $mahasiswa->telepon_rumah, $mahasiswa->alamat_rumah,
//                $mahasiswa->kode_pos_rumah, $mahasiswa->kewarganegaraan, $mahasiswa->email, $mahasiswa->nik, $mahasiswa->rt, $mahasiswa->rw, $mahasiswa->ds_kel, $mahasiswa->nama_ibu, $pass, $mahasiswa->angkatan, $mahasiswa->jenis_kelas, $mahasiswa->judul_skripsi, $mahasiswa->ipk, $mahasiswa->kota_rumah);
//            \App\Models\MOODLE_MODEL\Mahasiswa::sync_mahasiswa($mahasiswa->NPK, $mahasiswa->nama_lengkap, $mahasiswa->angkatan, $pass, $mahasiswa->program_id, $mahasiswa->alamat_rumah, $mahasiswa->hp, $mahasiswa->tempat_lahir, $mahasiswa->email, $mahasiswa->nama_program, $mahasiswa->jenis_pendanaan);
//        }
        $user = AkunPengguna::setAuth($request, Session::get('ip'));
//        dd($user);
        if (isset($user[0]->id_sertifikat)) {
            Session::put('user', $user[0]);
            if (isset($user[0]->id_personal)) {
                $username = AkunPengguna::where('id_akun', $user[0]->id_akun)->first();
                $peran['all'] = AkunPengguna::getListPeranByPersonal($user[0]->id_personal);
                $karyawan = Karyawan::get_detail_karyawan_by_id_personal(Session::get('user')->id_personal);
                Session::put('karyawan', $karyawan);
            } else {
                $username = AkunMahasiswa::where('id_akun', $user[0]->id_akun)->first();
                $peran['all'] = AkunMahasiswa::getListPeranByMahasiswa($user[0]->id_mhs);
//                $ta = tblMahasiswa::getNilaiSkripsi($user[0]->nim);
                $ta = array();
                if (sizeof($ta) > 0) {
                    if ($ta[0]->nilai_huruf != "-" && $ta[0]->nilai_huruf != "E")
                        Session::put("nilai_ta", $ta);
                }
//                $labkom = tblMahasiswa::getNilaiLabkom($user[0]->nim);
                $labkom = array();
                if (sizeof($labkom) > 0) {
                    if ($labkom[0]->nilai_huruf != "-")
                        Session::put("nilai_labkom", $ta);
                }
//                $krsTA = vwJadwalKuliahMahasiswa::isKrsTugasAkhir($user[0]->nim);
                $krsTA = array();
                if (sizeof($krsTA) > 0)
                    Session::put("krs_ta", $krsTA);
            }
            Session::put('username', $username->username);
            $peran['aktif'] = $user[0]->id_peran;
            $peran['aktif_'] = $user[0]->nama_peran;
            Session::put('peran', $peran);
            $modul = array();
            $mod = Modul::getListModulByPeran($user[0]->id_peran);
            foreach ($mod as $item) {
                $modul[$item->nama_modul] = $item->kd_kewenangan;
            }
            Session::put('modul', $modul);
            return redirect(route('dashboard.dashboard'));
        } else {
            Session::flash('failed_message', "Username / Password yang anda masukkan salah !");
            return redirect()->back();
        }
    }

    public function logout()
    {
        if (isset(Session::get('user')->id_personal)) {
            Sertifikat::where('id_personal', Session::get('user')->id_personal)->update(['is_data_aktif' => false]);
            Sertifikat::where('id_sertifikat', Session::get('user')->id_sertifikat)->update(['waktu_akses_terakhir' => now()]);
        } else {
            SertifikatMahasiswa::where('id_mhs', Session::get('user')->id_mhs)->update(['is_data_aktif' => false]);
            SertifikatMahasiswa::where('id_sertifikat', Session::get('user')->id_sertifikat)->update(['waktu_akses_terakhir' => now()]);
        }
        Session::flush();
        return redirect('/sign-in');
    }

    public function gantiPeran($id_peran)
    {
        Session::forget('modul');
        Session::forget('peran');
        if (isset(Session::get('user')->id_personal))
            $peran['all'] = AkunPengguna::getListPeranByPersonal(Session::get('user')->id_personal);
        else
            $peran['all'] = AkunMahasiswa::getListPeranByMahasiswa(Session::get('user')->id_mhs);
        $peran['aktif'] = $id_peran;
        foreach ($peran['all'] as $item) {
            if ($item->id_peran == $id_peran)
                $peran['aktif_'] = $item->nama_peran;
        }
        $modul = array();
        $mod = Modul::getListModulByPeran($id_peran);
        foreach ($mod as $item) {
            $modul[$item->nama_modul] = $item->kd_kewenangan;
        }
        Session::put('peran', $peran);
        Session::put('modul', $modul);
        return redirect()->back();
    }

    public function lupa_password()
    {
        return view('front_page.lupa_password');
    }

    public function gantiPassword(Request $request)
    {
        $request->validate([
            'old_pass' => 'required',
            'new_pass' => 'required',
            'retype_new_pass' => 'required'
        ]);
        if ($request->new_pass != $request->retype_new_pass) {
            Session::flash('failed_message', "Password Baru yang dimasukkan tidak sama");
            return redirect()->back();
        } else {
            if (isset(Session::get('user')->id_personal)) {
                $pengguna = AkunPengguna::updatePassword($request->old_pass, $request->new_pass, '')[0];
                if ($pengguna->is_success) {
                    Sertifikat::where('id_personal', Session::get('user')->id_personal)->update(['is_data_aktif' => false]);
                    Sertifikat::where('id_sertifikat', Session::get('user')->id_sertifikat)->update(['waktu_akses_terakhir' => now()]);
                    Session::flush();
                    Session::flash('success_message', "Password berhasil dirubah, silahkan masuk menggunakan password baru");
                    return redirect('/pengelola');
                } else {
                    Session::flash('failed_message', $pengguna->result);
                    return redirect()->back();
                }
            } else {
                $pengguna = AkunMahasiswa::updatePassword($request->old_pass, $request->new_pass)[0];
                if ($pengguna->is_success) {
                    SertifikatMahasiswa::where('id_mhs', Session::get('user')->id_mhs)->update(['is_data_aktif' => false]);
                    SertifikatMahasiswa::where('id_sertifikat', Session::get('user')->id_sertifikat)->update(['waktu_akses_terakhir' => now()]);
                    Session::flush();
                    Session::flash('success_message', "Password berhasil dirubah, silahkan masuk menggunakan password baru");
                    return redirect('/sign-in');
                } else {
                    Session::flash('failed_message', $pengguna->result);
                    return redirect()->back();
                }
            }
        }
    }

    public function sync_password_mahasiswa()
    {
        $data = \App\Models\SIAKAD_MODEL\tblMahasiswa::getMahasiswaByNpk(Session::get('user')->nim);
        $pass = 'null';
        if (!empty($data->password))
            $pass = $data->password;
        $sipadu = \App\Models\Akademik\Mahasiswa::sync_mahasiswa_with_siakad($data->NPK, $data->nisn, $data->dosen_wali, $data->tgl_lulus_sma, $data->inf_jurusan_sma, $data->sekolah_asal, $data->inf_tgl_lulus, $data->inf_nomor_ijazah, $data->inf_nomor_transkrip, $data->status_aktif, $data->program_id, $data->konsentrasi_id,
            $data->nama_wali, $data->pekerjaan_wali, $data->jenis_mahasiswa, $data->jenis_pendanaan, $data->no_seri_ijazah, $data->nama_lengkap, $data->tempat_lahir, $data->tgl_lahir, $data->jenis_kelamin, $data->agama_id, $data->status_menikah, $data->hp, $data->telepon_rumah, $data->alamat_rumah,
            $data->kode_pos_rumah, $data->kewarganegaraan, $data->email, $data->nik, $data->rt, $data->rw, $data->ds_kel, $data->nama_ibu, $pass, $data->angkatan, $data->jenis_kelas, $data->judul_skripsi, $data->ipk, $data->kota_rumah);
        $moodle = \App\Models\MOODLE_MODEL\Mahasiswa::sync_mahasiswa($data->NPK, $data->nama_lengkap, $data->angkatan, $pass, $data->program_id, $data->alamat_rumah, $data->hp, $data->tempat_lahir, $data->email, $data->nama_program, $data->jenis_pendanaan);
        SertifikatMahasiswa::where('id_mhs', Session::get('user')->id_mhs)->update(['is_data_aktif' => false]);
        SertifikatMahasiswa::where('id_sertifikat', Session::get('user')->id_sertifikat)->update(['waktu_akses_terakhir' => now()]);
        Session::flush();
        Session::flash('success_message', "Profil beserta password berhasil dirubah, silahkan masuk menggunakan password baru");
        return redirect('/sign-in');
    }

    public function forget_password(Request $request){
        $request->validate([
            'email' => 'required'
        ]);
        $account = AkunPengguna::get_user_account($request->email);
        if ($account->status == 1) {
            try {
                Mail::to($account->email)
                    ->send(new ForgetPassword($account));
                Session::flash('success_message', "Akun telah dikirimkan ke email tersebut.");
                return redirect()->back();
            } catch (\Exception $exception) {
                Session::flash('failed_message', "Gagal, Email tidak valid");
                return redirect()->back();
            }
        } else {
            Session::flash('failed_message', "Gagal, Email Tidak Terdaftar");
            return redirect()->back();
        }
    }
}
