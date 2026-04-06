<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Akademik\Mahasiswa;
use App\Models\Organisasi\Karyawan;
use App\Models\Pengguna\AkunMahasiswa;
use App\Models\Pengguna\AkunPengguna;
use App\Models\Pengguna\Sertifikat;
use App\Models\Pengguna\SertifikatMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function profile()
    {
        $menu = 'Akun';
        $detail = null;

        if (isset(Session::get('user')->id_mhs)) {
            $detail = AkunMahasiswa::getProfilByNim(Session::get('user')->nim);
            $detail = $detail[0] ?? Mahasiswa::get_mahasiswa_by_nim(Session::get('user')->nim);
        } elseif (isset(Session::get('user')->id_personal)) {
            $detail = Karyawan::get_detail_karyawan_by_id_personal(Session::get('user')->id_personal);
        }

        return view('account.profile', compact('menu', 'detail'));
    }

    public function update_profile(Request $request)
    {
        if (!isset(Session::get('user')->id_mhs)) {
            Session::flash('failed_message', 'Update profil hanya tersedia untuk mahasiswa.');
            return redirect()->back();
        }

        $request->validate([
            'email' => 'nullable|email|max:100',
            'no_hp' => 'nullable|string|max:30',
            'alamat' => 'nullable|string|max:2000',
            'path_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $nim = Session::get('user')->nim;
        $detailMahasiswa = Mahasiswa::get_mahasiswa_by_nim($nim);
        $detailProfil = AkunMahasiswa::getProfilByNim($nim);
        $detailProfil = $detailProfil[0] ?? null;

        $emailDefault = $detailMahasiswa->email ?? ($detailProfil->email ?? null);
        $telpDefault = $detailMahasiswa->handphone ?? ($detailMahasiswa->telepon ?? ($detailProfil->telp ?? null));
        $alamatDefault = $detailMahasiswa->alamat ?? ($detailProfil->alamat ?? null);

        $email = $request->exists('email') ? $request->input('email') : $emailDefault;
        $telp = $request->exists('no_hp') ? $request->input('no_hp') : $telpDefault;
        $alamat = $request->exists('alamat') ? $request->input('alamat') : $alamatDefault;
        $pathPhoto = $detailMahasiswa->path_photo ?? null;

        if ($request->hasFile('path_photo')) {
            $photo = $request->file('path_photo');
            $baseName = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
            $safeBaseName = Str::slug($baseName);
            $fileName = date('YmdHis') . '_' . ($safeBaseName ?: 'foto') . '.' . $photo->getClientOriginalExtension();

            $destinationPath = public_path('files/profil_mahasiswa/' . $nim);
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $photo->move($destinationPath, $fileName);
            $pathPhoto = $fileName;
        }

        try {
            $result = Mahasiswa::update_profil_mahasiswa_by_mhs($nim, $email, $telp, $pathPhoto, $alamat);

            $isSuccess = false;
            $message = 'Profil berhasil diperbarui.';

            if (is_object($result)) {
                if (property_exists($result, 'is_success')) {
                    $isSuccess = (bool) $result->is_success;
                } elseif (property_exists($result, 'status')) {
                    $isSuccess = (bool) $result->status;
                } else {
                    $isSuccess = true;
                }

                if (property_exists($result, 'result') && !empty($result->result)) {
                    $message = $result->result;
                } elseif (property_exists($result, 'keterangan') && !empty($result->keterangan)) {
                    $message = $result->keterangan;
                }
            }

            if ($isSuccess) {
                Session::flash('success_message', $message);
            } else {
                Session::flash('failed_message', $message ?: 'Gagal memperbarui profil.');
            }
        } catch (\Throwable $th) {
            Session::flash('failed_message', 'Gagal memperbarui profil: ' . $th->getMessage());
        }

        return redirect()->back();
    }

    public function change_password()
    {
        $menu = 'Akun';
        return view('account.change_password', compact('menu'));
    }

    public function update_password(Request $request)
    {
        $request->validate([
            'old_pass' => 'required',
            'new_pass' => 'required',
            'retype_new_pass' => 'required',
        ]);

        if ($request->new_pass !== $request->retype_new_pass) {
            Session::flash('failed_message', 'Password Baru yang dimasukkan tidak sama');
            return redirect()->back();
        }

        $response = AkunPengguna::updatePassword($request->old_pass, $request->new_pass, '');
        $result = $response[0] ?? null;

        if (isset($result->is_success) && $result->is_success) {
            if (isset(Session::get('user')->id_personal)) {
                Sertifikat::where('id_personal', Session::get('user')->id_personal)->update(['is_data_aktif' => false]);
                Sertifikat::where('id_sertifikat', Session::get('user')->id_sertifikat)->update(['waktu_akses_terakhir' => now()]);
            } elseif (isset(Session::get('user')->id_mhs)) {
                SertifikatMahasiswa::where('id_mhs', Session::get('user')->id_mhs)->update(['is_data_aktif' => false]);
                SertifikatMahasiswa::where('id_sertifikat', Session::get('user')->id_sertifikat)->update(['waktu_akses_terakhir' => now()]);
            }

            Session::flush();
            Session::flash('success_message', 'Password berhasil dirubah, silahkan masuk menggunakan password baru');
            return redirect('/sign-in');
        }

        $failedMessage = 'Gagal mengubah password.';
        if (isset($result->result) && !empty($result->result)) {
            $failedMessage = $result->result;
        }

        Session::flash('failed_message', $failedMessage);
        return redirect()->back();
    }
}
