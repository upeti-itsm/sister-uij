<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Personal extends Model
{
    use HasFactory;

    public static function get_detail_personal($id_personal){
        return DB::select('SELECT * FROM referensi.get_detail_personal(:id_personal)', [
            'id_personal' => $id_personal
        ])[0];
    }
}
