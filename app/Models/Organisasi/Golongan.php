<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Golongan extends Model
{
    use HasFactory;

    public static function get_daftar_golongan($search = ''){
        return DB::select('SELECT * FROM organisasi.get_daftar_golongan(:search)', [
            'search' => $search
        ]);
    }
}
