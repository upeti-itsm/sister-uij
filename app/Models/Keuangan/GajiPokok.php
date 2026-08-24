<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GajiPokok extends Model
{
    use HasFactory;

    public static function get_tunjangan_pendidikans($id_config_tunjangan_pendidikan, $id_jenis_karyawan, $param_search = "", $no_page = 0, $jml_record_perpage = -1)
    {
        return DB::select("SELECT * FROM organisasi.get_config_tunjangan_pendidikan(?,?,?,?,?)", [
            $id_config_tunjangan_pendidikan,
            $id_jenis_karyawan,
            $param_search,
            $no_page,
            $jml_record_perpage
        ]);
    }

    public static function update_tunjangan_pendidikan($id_config_tunjangan_pendidikan, $nominal_tunjangan, $sts_aktif)
    {
        return DB::selectOne("SELECT * FROM organisasi.update_config_tunjangan_pendidikan(?,?,?)", [
            $id_config_tunjangan_pendidikan,
            $nominal_tunjangan,
            $sts_aktif
        ]);
    }

    public static function insert_tunjangan_pendidikan($id_jenis_karyawan, $kd_pendidikan, $nominal_tunjangan, $sts_aktif)
    {
        return DB::selectOne("SELECT * FROM organisasi.insert_config_tunjangan_pendidikan(?,?,?,?)", [
            $id_jenis_karyawan,
            $kd_pendidikan,
            $nominal_tunjangan,
            $sts_aktif
        ]);
    }

    public static function list_pendidikan($param_search = "")
    {
        return DB::select("SELECT * FROM referensi.list_pendidikan_terakhir(?)", [
            $param_search
        ]);
    }

    public static function list_jenis_karyawan($id_jenis_karyawan = null)
    {
        return DB::select("SELECT * FROM organisasi.get_jenis_karyawan(?)", [
            $id_jenis_karyawan
        ]);
    }
}
