<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PlottingPerkuliahan extends Model
{
    use HasFactory;

    public static function get_daftar($kd_prodi = 'all', $tahun_akademik = 'all', $search = "", $offset = 0, $limit = -1)
    {
        return DB::select("SELECT * FROM akademik.get_daftar_ploting_matakuliah(?,?,?,?,?)", [
            $kd_prodi, $tahun_akademik, $search, $offset, $limit
        ]);
    }

    public static function insup($id_matakuliah, $id_karyawan, $tahun_akademik, $jenis_pengajaran, $id_kelas, $id_plotting = null)
    {
        return DB::selectOne("SELECT * FROM akademik.insup_ploting_matakuliah(?,?,?,?,?,?)", [
            $id_plotting, $id_matakuliah, $id_karyawan, $tahun_akademik, $jenis_pengajaran, $id_kelas
        ]);
    }

    public static function set_aktif($id, $aktif)
    {
        return DB::selectOne("SELECT * FROM akademik.set_status_ploting_matakuliah(?,?)", [
            $id, $aktif
        ]);
    }

    public static function get_dosen($search = "")
    {
        return DB::select("SELECT * FROM organisasi.get_daftar_pegawai(?) t1 WHERE t1.id_jenis_karyawan IN (1,2,6,3,7)", [
            $search
        ]);
    }

    public static function import_ploting($kd_matkul, $nidn, $tahun_akademik, $jenis_pengajaran, $kd_kelas)
    {
        return DB::selectOne("SELECT * FROM akademik.import_ploting_matakuliah(?,?,?,?,?)", [
            $kd_matkul, $nidn, $tahun_akademik, $jenis_pengajaran, $kd_kelas
        ]);
    }
}
