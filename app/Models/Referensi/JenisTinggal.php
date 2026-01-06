<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JenisTinggal extends Model
{
    use HasFactory;

    public static function get_jenis_tinggal()
    {
//        $data = [
//            (object)['id' => 1, 'nama' => 'Bersama Orang Tua'],
//            (object)['id' => 2, 'nama' => 'Kost'],
//            (object)['id' => 3, 'nama' => 'Kontrak'],
//            (object)['id' => 4, 'nama' => 'Asrama'],
//            (object)['id' => 5, 'nama' => 'Rumah Sendiri'],
//            (object)['id' => 6, 'nama' => 'Lainnya'],
//        ];
        $data = DB::select("SELECT * FROM referensi.list_jenis_tinggal()");
        return collect($data);
    }
}
