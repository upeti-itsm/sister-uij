<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sarpras extends Model
{
    use HasFactory;

    public static function get_daftar_sarana_prasaran($id_jenis = 0, $offset = 0, $limit = -1, $search = '')
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_sarana_prasarana(?,?,?,?)', [
            $id_jenis, $offset, $limit, $search
        ]);
    }
}
