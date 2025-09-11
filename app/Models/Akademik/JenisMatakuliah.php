<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JenisMatakuliah extends Model
{
    use HasFactory;

    public static function get_daftar($search = "", $start = 0, $length = -1)
    {
        return DB::select("SELECT * FROM akademik.daftar_jenis_matakuliah(?,?,?)", [
            $search, $start, $length
        ]);
    }

    public static function insup($id_jenis_matakuliah, $kd_jenis_matakuliah, $nama_jenis, $keterangan)
    {
        return DB::selectOne("SELECT * FROM akademik.insup_jenis_matakuliah(?,?,?,?)", [
            $id_jenis_matakuliah, $kd_jenis_matakuliah, $nama_jenis, $keterangan
        ]);
    }

    public static function set_aktif($id, $aktif)
    {
        return DB::selectOne("SELECT * FROM akademik.set_status_aktif_jenis_matakuliah(?,?)", [
            $id, $aktif
        ]);
    }
}
