<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Agama extends Model
{
    use HasFactory;

    public static function list_agama()
    {
        return DB::select('SELECT * FROM referensi.list_agama()');
    }

    // Method untuk get dummy data
    public static function all($columns = ['*'])
    {
        $data = [
            (object)['id' => 1, 'nama' => 'Islam'],
            (object)['id' => 2, 'nama' => 'Kristen'],
            (object)['id' => 3, 'nama' => 'Katolik'],
            (object)['id' => 4, 'nama' => 'Hindu'],
            (object)['id' => 5, 'nama' => 'Buddha'],
            (object)['id' => 6, 'nama' => 'Konghucu'],
        ];

        return collect($data);
    }
}
