<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AlatTransportasi extends Model
{
    use HasFactory;

    public static function get_data()
    {
//        $data = [
//            (object)['id' => 1, 'nama' => 'Jalan Kaki'],
//            (object)['id' => 2, 'nama' => 'Sepeda'],
//            (object)['id' => 3, 'nama' => 'Sepeda Motor'],
//            (object)['id' => 4, 'nama' => 'Mobil Pribadi'],
//            (object)['id' => 5, 'nama' => 'Angkutan Umum/Bus/Pete-Pete'],
//            (object)['id' => 6, 'nama' => 'Ojek'],
//            (object)['id' => 7, 'nama' => 'Ojek Online'],
//            (object)['id' => 8, 'nama' => 'Kereta Api'],
//            (object)['id' => 9, 'nama' => 'Lainnya'],
//        ];
        $data = DB::select("SELECT * FROM referensi.list_alat_transportasi()");
        return collect($data);
    }
}
