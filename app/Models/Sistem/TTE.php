<?php

namespace App\Models\Sistem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TTE extends Model
{
    use HasFactory;
    public static function cekTTE($qr)
    {
        return DB::selectOne('SELECT * FROM organisasi.cek_keabsasan_dokumen(?)' , [
            base64_decode($qr)
        ]);
    }
}
