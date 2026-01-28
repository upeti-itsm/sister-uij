<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PengaturanSKS extends Model
{
    use HasFactory;

    public static function get_daftar($sts_aktif = null)
    {
        return DB::select("SELECT * FROM akademik.get_daftar_aturan_sks(?)", [$sts_aktif]);
    }

    public static function insup($id, $ips_min, $ips_max, $sks)
    {
        return DB::selectOne("SELECT * FROM akademik.insup_aturan_sks(?,?,?,?)", [
            $id, $ips_min, $ips_max, $sks
        ]);
    }

    public static function set_aktif($id, $status)
    {
        return DB::selectOne("SELECT * FROM akademik.set_status_aktif_aturan_sks(?,?)", [
            $id, $status
        ]);
    }
}
