<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Penghasilan extends Model
{
    use HasFactory;

    public static function get_data()
    {
//        $data = [
//            (object)['id' => 1, 'nama' => 'Tidak Berpenghasilan', 'min' => 0, 'max' => 0],
//            (object)['id' => 2, 'nama' => 'Kurang dari Rp.  500.000', 'min' => 0, 'max' => 500000],
//            (object)['id' => 3, 'nama' => 'Rp. 500.000 - Rp. 1.000.000', 'min' => 500000, 'max' => 1000000],
//            (object)['id' => 4, 'nama' => 'Rp. 1.000.000 - Rp. 2.000.000', 'min' => 1000000, 'max' => 2000000],
//            (object)['id' => 5, 'nama' => 'Rp. 2.000.000 - Rp. 3.000.000', 'min' => 2000000, 'max' => 3000000],
//            (object)['id' => 6, 'nama' => 'Rp.  3.000.000 - Rp. 5.000.000', 'min' => 3000000, 'max' => 5000000],
//            (object)['id' => 7, 'nama' => 'Rp. 5.000.000 - Rp. 10.000.000', 'min' => 5000000, 'max' => 10000000],
//            (object)['id' => 8, 'nama' => 'Rp. 10.000.000 - Rp.  20.000.000', 'min' => 10000000, 'max' => 20000000],
//            (object)['id' => 9, 'nama' => 'Lebih dari Rp. 20.000.000', 'min' => 20000000, 'max' => 999999999],
//        ];
        $data = DB::select('SELECT * FROM referensi.list_penghasilan()');
        return collect($data);
    }
}
