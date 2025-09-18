<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class KRS extends Model
{
    use HasFactory;

    public static function get_daftar($kd_prodi, $tahun_akademik = 1, $search = '', $offset = -1, $limit = 10)
    {
        return DB::select("SELECT * FROM akademik.get_daftar_jadwal_krs(?,?,?,?,?)", [
            $kd_prodi, $tahun_akademik, $search, $offset, $limit
        ]);
    }
}
