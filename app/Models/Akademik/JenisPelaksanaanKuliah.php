<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JenisPelaksanaanKuliah extends Model
{
    use HasFactory;

    public static function get_daftar($search = "", $start = 0, $length = -1)
    {
        return DB::select("SELECT * FROM akademik.get_daftar_jenis_pelaksanaan_matakuliah(?,?,?)", [
            $search, $start, $length
        ]);
    }

    public static function insup($id_jenis_pelaksanaan_kuliah, $kd_jenis_pelaksanaan_kuliah, $jenis_pelaksanaan_kuliah, $keterangan)
    {
        return DB::selectOne("SELECT * FROM akademik.insup_jenis_pelaksanaan_matakuliah(?,?,?,?)", [
            $id_jenis_pelaksanaan_kuliah, $kd_jenis_pelaksanaan_kuliah, $jenis_pelaksanaan_kuliah, $keterangan
        ]);
    }

    public static function set_aktif($id, $aktif)
    {
        return DB::selectOne("SELECT * FROM akademik.set_status_aktif_jenis_pelaksanaan_matakuliah(?,?)", [
            $id, $aktif
        ]);
    }
}
