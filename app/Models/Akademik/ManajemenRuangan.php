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
}
