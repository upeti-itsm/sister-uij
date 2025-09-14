<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Matakuliah extends Model
{
    use HasFactory;

    public static function get_daftar($kd_prodi = 'all', $id_kurikulum = null, $search = "", $offset = 0, $limit = -1) {
        return DB::select("SELECT * FROM akademik.get_daftar_matakuliah(?,?,?,?,?)", [
            $kd_prodi, $id_kurikulum, $search, $offset, $limit
        ]);
    }

    public static function insup($id_matakuliah, $kd_matakuliah, $nama_matakuliah, $id_kurikulum, $sks, $id_konsentrasi, $semester, $kd_jenis_matakuliah, $kd_jenis_pelaksanaan, $id_matakuliah_prasyarat, $feeder_id = null)
    {
        return DB::selectOne("SELECT * FROM akademik.insup_matakuliah(?,?,?,?,?,?,?,?,?,?,?)", [
            $id_matakuliah, $kd_matakuliah, $nama_matakuliah, $id_kurikulum, $sks, $id_konsentrasi, $semester, $kd_jenis_matakuliah, $kd_jenis_pelaksanaan, $id_matakuliah_prasyarat, $feeder_id
        ]);
    }

    public static function set_aktif($id, $status)
    {
        return DB::selectOne("SELECT * FROM akademik.set_status_aktif_matakuliah(?,?)", [
            $id, $status
        ]);
    }
}
