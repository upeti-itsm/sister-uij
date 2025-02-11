<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Agama extends Model
{
    use HasFactory;

    public static function list_agama()
    {
        return DB::select('SELECT * FROM referensi.list_agama()');
    }
}
