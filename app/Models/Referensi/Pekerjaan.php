<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pekerjaan extends Model
{
    use HasFactory;

    public static function get_data()
    {
//        $data = [
//            (object)['id' => 1, 'nama' => 'Tidak Bekerja'],
//            (object)['id' => 2, 'nama' => 'Petani'],
//            (object)['id' => 3, 'nama' => 'Peternak'],
//            (object)['id' => 4, 'nama' => 'Nelayan'],
//            (object)['id' => 5, 'nama' => 'Buruh Tani/Perkebunan'],
//            (object)['id' => 6, 'nama' => 'Buruh Nelayan'],
//            (object)['id' => 7, 'nama' => 'Buruh Bangunan'],
//            (object)['id' => 8, 'nama' => 'Pedagang Kecil'],
//            (object)['id' => 9, 'nama' => 'Pedagang Besar'],
//            (object)['id' => 10, 'nama' => 'Wiraswasta'],
//            (object)['id' => 11, 'nama' => 'Guru/Dosen'],
//            (object)['id' => 12, 'nama' => 'PNS/TNI/POLRI'],
//            (object)['id' => 13, 'nama' => 'Karyawan Swasta'],
//            (object)['id' => 14, 'nama' => 'Pensiunan'],
//            (object)['id' => 15, 'nama' => 'Tenaga Kesehatan'],
//            (object)['id' => 16, 'nama' => 'Buruh Harian Lepas'],
//            (object)['id' => 17, 'nama' => 'Ibu Rumah Tangga'],
//            (object)['id' => 18, 'nama' => 'Sudah Meninggal'],
//            (object)['id' => 19, 'nama' => 'Lainnya'],
//        ];
        $data = DB::select('SELECT * FROM referensi.list_pekerjaan()');
        return collect($data);
    }
}
