<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\Karyawan;
use App\Models\Pengguna\AkunMahasiswa;
use App\Models\Pengguna\AkunPengguna;
use App\Models\Pengguna\Sertifikat;
use App\Models\Pengguna\SertifikatMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{
    public function profile()
    {
        $menu = 'Profil';
        $user = Session::get('user');

        $detail = AkunMahasiswa::getProfilByNim($user->nim)[0] ?? null;

        $karyawan = null;
        if ($user && isset($user->id_personal)) {
            $karyawan = Karyawan::get_detail_karyawan_by_id_personal($user->id_personal);
        }

        return view('account.profile', compact('menu', 'user', 'karyawan', 'detail'));
    }

    public function update_profile(Request $request)
    {
        $user = Session::get('user');
        if (!$user || !isset($user->id_personal)) {
            Session::flash('failed_message', 'Akun ini tidak memiliki profil pegawai yang dapat diubah.');
            return redirect()->back();
        }

        $request->validate([
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email:rfc,dns|max:255',
            'alamat' => 'nullable|string|max:255',
        ], [
            'email.email' => 'Pastikan Email terisi dengan alamat email yang benar',
        ]);

        $karyawanOld = Karyawan::get_detail_karyawan_by_id_personal($user->id_personal);

        $noHp = $request->no_hp !== null ? trim($request->no_hp) : ($karyawanOld->no_hp ?? '');
        $email = $request->email !== null ? trim($request->email) : ($karyawanOld->email ?? '');
        $alamat = $request->alamat !== null ? trim($request->alamat) : ($karyawanOld->alamat ?? '');

        $result = Karyawan::update_data_personal_on_karyawan(
            $user->id_personal,
            $karyawanOld->no_ktp ?? '',
            $karyawanOld->nama ?? ($user->nama_lengkap ?? ''),
            $karyawanOld->gelar_depan ?? '',
            $karyawanOld->gelar_belakang ?? '',
            $karyawanOld->tempat_lahir ?? '',
            $karyawanOld->tanggal_lahir ?? null,
            $noHp,
            $email,
            $karyawanOld->kd_jenis_kelamin ?? null,
            $karyawanOld->id_agama ?? null,
            $alamat
        );

        if ($result && isset($result->status) && (int)$result->status === 1) {
            $karyawanNew = Karyawan::get_detail_karyawan_by_id_personal($user->id_personal);
            Session::forget('karyawan');
            Session::put('karyawan', $karyawanNew);
            Session::flash('success_message', $result->keterangan ?? 'Profil berhasil diperbarui.');
            return redirect()->back();
        }

        Session::flash('failed_message', $result->keterangan ?? 'Gagal memperbarui profil.');
        return redirect()->back()->withInput();
    }

    public function change_password()
    {
        $menu = 'Ganti Password';
        $user = Session::get('user');

        return view('account.change_password', compact('menu', 'user'));
    }

    public function update_password(Request $request)
    {
        $request->validate([
            'old_pass' => 'required',
            'new_pass' => 'required',
            'retype_new_pass' => 'required'
        ]);

        if ($request->new_pass != $request->retype_new_pass) {
            Session::flash('failed_message', 'Password Baru yang dimasukkan tidak sama');
            return redirect()->back();
        }

        if (isset(Session::get('user')->id_personal)) {
            $pengguna = AkunPengguna::updatePassword($request->old_pass, $request->new_pass, '')[0] ?? null;
            if ($pengguna && $pengguna->is_success) {
                Sertifikat::where('id_personal', Session::get('user')->id_personal)->update(['is_data_aktif' => false]);
                Sertifikat::where('id_sertifikat', Session::get('user')->id_sertifikat)->update(['waktu_akses_terakhir' => now()]);
                Session::flush();
                Session::flash('success_message', 'Password berhasil dirubah, silahkan masuk menggunakan password baru');
                return redirect('/pengelola');
            }

            Session::flash('failed_message', $pengguna->result ?? 'Gagal mengubah password');
            return redirect()->back();
        }

        $pengguna = AkunMahasiswa::updatePassword($request->old_pass, $request->new_pass)[0] ?? null;
        if ($pengguna && $pengguna->is_success) {
            SertifikatMahasiswa::where('id_mhs', Session::get('user')->id_mhs)->update(['is_data_aktif' => false]);
            SertifikatMahasiswa::where('id_sertifikat', Session::get('user')->id_sertifikat)->update(['waktu_akses_terakhir' => now()]);
            Session::flush();
            Session::flash('success_message', 'Password berhasil dirubah, silahkan masuk menggunakan password baru');
            return redirect('/sign-in');
        }

        Session::flash('failed_message', $pengguna->result ?? 'Gagal mengubah password');
        return redirect()->back();
    }
}
