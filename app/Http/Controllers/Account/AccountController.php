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

        // dd(session()->get('user'));
        return view('account.profile', compact('menu', 'detail'));
    }

    public function update_profile(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email|max:100',
            'no_hp' => 'nullable|string|max:30',
            'alamat' => 'nullable|string|max:2000',
            'path_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            if (isset(Session::get('user')->id_mhs)) {
                $result = $this->updateProfilMahasiswa($request);
            } elseif (isset(Session::get('user')->id_personal)) {
                $result = $this->updateProfilKaryawan($request);
            } else {
                Session::flash('failed_message', 'Gagal memperbarui profil. Data pengguna tidak ditemukan.');
                return redirect()->back();
            }

            if ($result['success']) {
                Session::flash('success_message', $result['message']);
            } else {
                Session::flash('failed_message', $result['message'] ?: 'Gagal memperbarui profil.');
            }
        } catch (\Throwable $th) {
            Session::flash('failed_message', 'Gagal memperbarui profil: ' . $th->getMessage());
        }

        return redirect()->back();
    }

    private function updateProfilMahasiswa(Request $request)
    {
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

            $fileName = $nim . '.jpg';
            $photo->storeAs('profil_mahasiswa/' . $nim, $fileName, 'public');

            $pathPhoto = $fileName;
        }

        $result = Mahasiswa::update_profil_mahasiswa_by_mhs($nim, $email, $telp, $pathPhoto, $alamat);
        return $this->normalizeUpdateResult($result, 'Profil berhasil diperbarui.');
    }

    private function updateProfilKaryawan(Request $request)
    {
        $idPersonal = Session::get('user')->id_personal;
        $detailKaryawan = Karyawan::get_detail_karyawan_by_id_personal($idPersonal);

        $hasContactUpdate = $request->exists('email') || $request->exists('no_hp') || $request->exists('alamat');
        $hasPhotoUpdate = $request->hasFile('path_photo');

        if (!$hasContactUpdate && !$hasPhotoUpdate) {
            return [
                'success' => false,
                'message' => 'Tidak ada data yang diperbarui.',
            ];
        }

        if ($hasPhotoUpdate) {
            $photo = $request->file('path_photo');

            $fileName = $idPersonal . '.jpg';
            $photo->storeAs('profil_karyawan/' . $idPersonal, $fileName, 'public');

            $photoResult = Karyawan::update_path_photo($idPersonal, $fileName);

            $photoUpdate = $this->normalizeUpdateResult($photoResult, 'Foto profil berhasil diperbarui.', 'Gagal memperbarui foto profil.');
            if (!$photoUpdate['success']) {
                return $photoUpdate;
            }
        }

        if ($hasContactUpdate) {
            $email = $request->exists('email') ? $request->input('email') : ($detailKaryawan->email ?? null);
            $telp = $request->exists('no_hp') ? $request->input('no_hp') : ($detailKaryawan->no_hp ?? ($detailKaryawan->telp ?? null));
            $alamat = $request->exists('alamat') ? $request->input('alamat') : ($detailKaryawan->alamat ?? null);

            $nama = $detailKaryawan->nama ?? ($detailKaryawan->nama_lengkap ?? null);
            $jenisKelamin = $detailKaryawan->kd_jenis_kelamin ?? null;

            if (empty($jenisKelamin) && !empty($detailKaryawan->jenis_kelamin ?? null)) {
                $jenisKelaminText = strtolower($detailKaryawan->jenis_kelamin);
                if (strpos($jenisKelaminText, 'laki') !== false) {
                    $jenisKelamin = 'lk';
                } elseif (strpos($jenisKelaminText, 'perempuan') !== false || strpos($jenisKelaminText, 'wanita') !== false) {
                    $jenisKelamin = 'pr';
                }
            }

            $result = Karyawan::update_data_personal_on_karyawan(
                $idPersonal,
                $detailKaryawan->no_ktp ?? null,
                $nama,
                $detailKaryawan->gelar_depan ?? null,
                $detailKaryawan->gelar_belakang ?? null,
                $detailKaryawan->tempat_lahir ?? null,
                $detailKaryawan->tanggal_lahir ?? null,
                $telp,
                $email,
                $jenisKelamin,
                $detailKaryawan->id_agama ?? null,
                $alamat
            );

            $profileUpdate = $this->normalizeUpdateResult($result, 'Profil berhasil diperbarui.');
            if (!$profileUpdate['success']) {
                return $profileUpdate;
            }
        }

        $karyawan = Karyawan::get_detail_karyawan_by_id_personal($idPersonal);
        Session::forget('karyawan');
        Session::put('karyawan', $karyawan);

        return [
            'success' => true,
            'message' => $hasContactUpdate ? 'Profil berhasil diperbarui.' : 'Foto profil berhasil diperbarui.',
        ];
    }

    private function normalizeUpdateResult($result, $successMessage = 'Profil berhasil diperbarui.', $failedMessage = 'Gagal memperbarui profil.')
    {
        $isSuccess = false;
        $message = $successMessage;

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

        if (!$isSuccess && ($message === $successMessage || empty($message))) {
            $message = $failedMessage;
        }

        return [
            'success' => $isSuccess,
            'message' => $message,
        ];
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
