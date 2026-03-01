<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ManajemenFakultas extends Model
{
    use HasFactory;

    public static function get_daftar_fakultas(
        $param_search = '',
        $no_page = -1,
        $jml_record_perpage = 10,
        $kd_fakultas = null,
        $sts_aktif = true
    )
    {
        return DB::select("SELECT * FROM akademik.get_daftar_fakultas(?::varchar, ?::integer, ?::integer, ?::varchar, ?::boolean)", [
            $param_search,
            $no_page,
            $jml_record_perpage,
            $kd_fakultas,
            $sts_aktif
        ]);
    }

    public static function insup_fakultas(
        $kd_fakultas = null,
        $nama_fakultas = null,
        $dekan = null,
        $kd_nim_fak = null,
        $is_data_aktif = true
    )
    {
        $pg_escape = function($val) {
            if ($val === null) return 'NULL';
            return "'" . str_replace("'", "''", $val) . "'";
        };

        $kd_fakultas_safe = $kd_fakultas ? $pg_escape($kd_fakultas) . '::varchar' : 'NULL::varchar';
        $nama_fakultas_safe = $nama_fakultas ? $pg_escape($nama_fakultas) . '::varchar' : 'NULL::varchar';
        $dekan_safe = $dekan ? $pg_escape($dekan) . '::varchar' : 'NULL::varchar';
        $kd_nim_fak_safe = $kd_nim_fak ? $pg_escape($kd_nim_fak) . '::varchar' : 'NULL::varchar';
        $is_data_aktif_safe = $is_data_aktif ? 'true::boolean' : 'false::boolean';

        $sql = "SELECT * FROM akademik.insup_fakultas(
            {$kd_fakultas_safe},
            {$nama_fakultas_safe},
            {$dekan_safe},
            {$kd_nim_fak_safe},
            {$is_data_aktif_safe}
        )";

        return DB::selectOne($sql);
    }

    public static function set_status_aktif_fakultas($kd_fakultas, $status)
    {
        // Trim untuk menghilangkan trailing space dari PostgreSQL character type
        $kd_fakultas = trim($kd_fakultas);

        return DB::selectOne("SELECT * FROM akademik.set_status_aktif_fakultas(?::varchar, ?::boolean)", [
            $kd_fakultas, $status
        ]);
    }
}
