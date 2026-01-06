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

    public static function get_list_jenjang_pendidikan()
    {
        $data = [
            (object)['id' => 1, 'nama' => 'Tidak Sekolah', 'urutan' => 1],
            (object)['id' => 2, 'nama' => 'SD / Sederajat', 'urutan' => 2],
            (object)['id' => 3, 'nama' => 'SMP / Sederajat', 'urutan' => 3],
            (object)['id' => 4, 'nama' => 'SMA / Sederajat', 'urutan' => 4],
            (object)['id' => 5, 'nama' => 'D1', 'urutan' => 5],
            (object)['id' => 6, 'nama' => 'D2', 'urutan' => 6],
            (object)['id' => 7, 'nama' => 'D3', 'urutan' => 7],
            (object)['id' => 8, 'nama' => 'D4/S1', 'urutan' => 8],
            (object)['id' => 9, 'nama' => 'S2', 'urutan' => 9],
            (object)['id' => 10, 'nama' => 'S3', 'urutan' => 10],
        ];

        return collect($data);
    }
}
