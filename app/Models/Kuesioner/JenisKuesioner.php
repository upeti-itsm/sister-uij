<?php

namespace App\Models\Kuesioner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JenisKuesioner extends Model
{
    use HasFactory;
    public static function get_jenis_kuesioner($offset = -1, $limit = 10, $search = ''){
        return DB::select('SELECT * FROM kuesioner.get_list_jenis_kuesioner(:offset,:limit, :search)', [
            'offset' => $offset, 'limit' => $limit, 'search' => $search
        ]);
    }
}
