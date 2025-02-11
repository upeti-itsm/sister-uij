<?php

namespace App\Models\Kuesioner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UnsurKuesionerKepuasanMahasiswa extends Model
{
    use HasFactory;

    public static function get_list_unsur($offset = -1, $limit = 10, $search = '', $id_unsur = 0, $id_jenis = 1, $is_active = true)
    {
        return DB::select('SELECT * FROM kuesioner.get_list_unsur_kuesioner_kepuasan_mahasiswa(:offset, :limit, :search, :id_unsur, :id_jenis, :is_active)', [
            'offset' => $offset, 'limit' => $limit, 'search' => $search,
            'id_unsur' => $id_unsur, 'id_jenis' => $id_jenis, 'is_active' => $is_active
        ]);
    }
}
