<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kurikulum extends Model
{
    use HasFactory;

    public static function get_daftar_kurikulum($kd_prodi, $search = "", $start = 0, $length = -1)
    {
        return DB::select("SELECT * FROM akademik.get_daftar_kurikulum(?,?,?,?)", [
            $kd_prodi, $search, $start, $length
        ]);
    }

    public static function insup_kurikulum($id_kurikulum, $nama_kurikulum, $tahun_kurikulum, $kd_program_studi, $sks_lulus)
    {
        return DB::selectOne("SELECT * FROM akademik.insup_kurikulum(?,?,?,?,?)", [
            $id_kurikulum, $nama_kurikulum, $tahun_kurikulum, $kd_program_studi, $sks_lulus
        ]);
    }

    public static function set_aktif_kurikulum($id_kurikulum, $aktif)
    {
        return DB::selectOne("SELECT * FROM akademik.set_status_aktif_kurikulum(?,?)", [
            $id_kurikulum, $aktif
        ]);
    }
}
