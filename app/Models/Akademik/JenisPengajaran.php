<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JenisPengajaran extends Model
{
    use HasFactory;

    public static function get_daftar($search = "", $offset = 0, $limit = -1)
    {
        return DB::select("SELECT * FROM akademik.get_daftar_jenis_pengajaran(?,?,?)", [
            $search, $offset, $limit
        ]);
    }

    public static function insup($id, $jenis_pengajaran)
    {
        return DB::selectOne("SELECT * FROM akademik.insup_jenis_pengajaran(?,?)", [
            $id, $jenis_pengajaran
        ]);
    }

    public static function set_aktif($id, $status)
    {
        return DB::selectOne("SELECT * FROM akademik.set_status_jenis_pengajaran(?,?)", [
            $id, $status
        ]);
    }
}
