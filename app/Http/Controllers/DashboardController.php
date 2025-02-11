<?php

namespace App\Http\Controllers;

use App\Models\Absensi\RekapitulasiAbsensiKaryawan;
use App\Models\Akademik\JadwalWisuda;
use App\Models\Akademik\Mahasiswa;
use App\Models\Akademik\Semester;
use App\Models\Kuesioner\DokumenSKPI;
use App\Models\Kuesioner\RekapitulasiKepuasanMahasiswa;
use App\Models\PMB\Pendaftar;
use App\Models\SIAKAD_MODEL\tblMahasiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $menu = "Dashboard";
        $data = array();
        if (Session::get('peran')['aktif'] == 41) {
            if (substr(Session::get('user')->nim, 0, 2) != '23') {
                $kuesioner_kepuasan = RekapitulasiKepuasanMahasiswa::cek_status_pengisian(Session::get('user')->id_mhs, 1, 333);
                if ($kuesioner_kepuasan->status == 0)
                    return redirect(route('mahasiswa.akademik.kuesioner_kepuasan_mahasiswa.index'));
                else {
                    $skpi = DokumenSKPI::cek_status_skpi(Session::get('user')->id_mhs, 333);
                    if ($skpi->status == 0)
                        return redirect(route('mahasiswa.akademik.skpi.index'));
                    else {
                        $data["nilai_skripsi"] = tblMahasiswa::getNilaiSkripsi(Session::get('user')->nim);
                        $data["jadwal_wisuda"] = JadwalWisuda::get_daftar_jadwal_wisuda("all", 0, 1);
                    }
                }
            } else {
                $data["nilai_skripsi"] = tblMahasiswa::getNilaiSkripsi(Session::get('user')->nim);
                $data["jadwal_wisuda"] = JadwalWisuda::get_daftar_jadwal_wisuda("all", 0, 1);
            }
        } elseif (Session::get('peran')['aktif'] == 45) {
            $data['now'] = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $data['pmb'] = Pendaftar::get_informasi_perolehan_pendaftar();
            $data['mahasiswa'] = Mahasiswa::get_informasi_mahasiswa();
            $data['abs'] = RekapitulasiAbsensiKaryawan::get_rekap_informasi();
        }
        return view('dashboard.dashboard', compact('menu', 'data'));
    }
}
