<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ManajemenRuangan extends Model
{
    use HasFactory;

    public static function get_daftar_ruangan($id_ruangan = 0, $search = "", $start = 0, $length = -1)
    {
        return DB::select("SELECT * FROM akademik.get_daftar_ruang_perkuliahan(?,?,?,?)", [
            $id_ruangan,
            $search,
            $start,
            $length
        ]);
    }

    public static function insup_ruangan($ruang_perkuliahan, $kapasitas, $informasi_kelas, $sts_aktif, $id_ruang_perkuliahan)
    {
        return DB::selectOne("SELECT * FROM akademik.insup_ruang_perkuliahan(?,?,?,?,?)", [
            $ruang_perkuliahan,
            $kapasitas,
            $informasi_kelas,
            $sts_aktif,
            $id_ruang_perkuliahan
        ]);
    }

    public static function delete_ruangan($id_ruang_perkuliahan)
    {
        return DB::selectOne("SELECT * FROM akademik.hapus_ruang_perkuliahan(?)", [
            $id_ruang_perkuliahan
        ]);
    }
}
