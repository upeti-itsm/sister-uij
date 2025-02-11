<?php

namespace App\Models\Kuesioner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UnsurSubKuesionerKepuasanWisudawan extends Model
{
    use HasFactory;

    public static function get_list_sub_unsur($id_unsur = 0, $offset = -1, $limit = 10, $search = '', $id_sub_unsur = 0, $is_active = true)
    {
        return DB::select('SELECT * FROM kuesioner.get_list_sub_unsur_kuesioner_kepuasan_wisudawan(:id_unsur, :offset, :limit, :search, :id_sub_unsur, :is_active)', [
            'id_unsur' => $id_unsur, 'offset' => $offset, 'limit' => $limit, 'search' => $search,
            'id_sub_unsur' => $id_sub_unsur, 'is_active' => $is_active
        ]);
    }
}
