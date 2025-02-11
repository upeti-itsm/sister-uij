<?php

namespace App\Models\Kuesioner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UnsurKuesionerKepuasanWisudawan extends Model
{
    use HasFactory;

    public static function get_list_unsur($offset = -1, $limit = 10, $search = '', $id_unsur = 0, $is_active = true){
        return DB::select('SELECT * FROM kuesioner.get_list_unsur_kuesioner_kepuasan_wisudawan(:offset, :limit, :search, :id_unsur, :is_active)', [
            'offset'=> $offset, 'limit' => $limit, 'search' => $search,
            'id_unsur' => $id_unsur, 'is_active' => $is_active
        ]);
    }
}
