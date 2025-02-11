<?php

namespace App\Http\Middleware;

use App\Models\Pengguna\AkunMahasiswa;
use App\Models\Pengguna\AkunPengguna;
use App\Models\Pengguna\Sertifikat;
use App\Models\Pengguna\SertifikatMahasiswa;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class Rules
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $modul)
    {
        if ($modul == 'login') {
            if (isset(Session::get('user')->id_sertifikat)) {
                return redirect('/dashboard');
            }
        } else {
            if (array_key_exists('user', Session::all())) {
                if (isset(Session::get('user')->id_personal)) {
                    $user = AkunPengguna::cekSertifikat(Session::get('user')->id_sertifikat, Session::get('user')->id_personal);
                    if (sizeof($user) == 1) {
                        if (!array_key_exists($modul, Session::get('modul'))) {
                            if (array_key_exists('Dashboard', Session::get('modul'))) {
                                //Session::flash('failed_message', 'Anda Tidak Memiliki Akses ke Modul ' . $modul);
                                return redirect('/dashboard');
                            } else {
                                Sertifikat::where('id_personal', Session::get('user')->id_personal)->update(['is_data_aktif' => false]);
                                Sertifikat::where('id_sertifikat', Session::get('user')->id_sertifikat)->update(['waktu_akses_terakhir' => now()]);
                                Session::flush();
                                Session::flash('failed_message', 'Anda Tidak Memiliki Akses ke Modul ' . $modul);
                                return redirect('/sign-in');
                            }
                        }
                    } else {
                        Sertifikat::where('id_personal', Session::get('user')->id_personal)->update(['is_data_aktif' => false]);
                        Sertifikat::where('id_sertifikat', Session::get('user')->id_sertifikat)->update(['waktu_akses_terakhir' => now()]);
                        Session::flush();
                        Session::flash('failed_message', 'Invalid Session');
                        return redirect('/sign-in');
                    }
                } else {
                    $user = AkunMahasiswa::cekSertifikat(Session::get('user')->id_sertifikat, Session::get('user')->id_mhs);
                    if (sizeof($user) == 1) {
                        if (!array_key_exists($modul, Session::get('modul'))) {
                            if (array_key_exists('Dashboard', Session::get('modul'))) {
                                //Session::flash('failed_message', 'Anda Tidak Memiliki Akses ke Modul ' . $modul);
                                return redirect('/dashboard');
                            } else {
                                SertifikatMahasiswa::where('id_mhs', Session::get('user')->id_mhs)->update(['is_data_aktif' => false]);
                                SertifikatMahasiswa::where('id_sertifikat', Session::get('user')->id_sertifikat)->update(['waktu_akses_terakhir' => now()]);
                                Session::flush();
                                Session::flash('failed_message', 'Anda Tidak Memiliki Akses ke Modul ' . $modul);
                                return redirect('/sign-in');
                            }
                        }
                    } else {
                        SertifikatMahasiswa::where('id_mhs', Session::get('user')->id_mhs)->update(['is_data_aktif' => false]);
                        Sertifikat::where('id_sertifikat', Session::get('user')->id_sertifikat)->update(['waktu_akses_terakhir' => now()]);
                        Session::flush();
                        Session::flash('failed_message', 'Invalid Session');
                        return redirect('/sign-in');
                    }
                }
            } else {
                Session::flush();
                return redirect('/sign-in');
            }
        }
        return $next($request);
    }
}
