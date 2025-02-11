<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pendidikan extends Model
{
    use HasFactory;

    public static function list_pendidikan_terakhir($search = ''){
        return DB::select('SELECT * FROM referensi.list_pendidikan_terakhir(:search)', [
            'search' => $search
        ]);
    }

    public static function list_jenjang_pendidikan(){
        return DB::select('SELECT * FROM referensi.jenjang_pendidikan ORDER BY id_jenjang_pendidikan ASC', []);
    }
}
