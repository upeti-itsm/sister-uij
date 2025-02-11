<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UnitKerja extends Model
{
    use HasFactory;

    public static function get_daftar_unit_kerja($search = ''){
        return DB::select('SELECT * FROM organisasi.get_daftar_unit_kerja(:search)', [
            'search' => $search
        ]);
    }
}
