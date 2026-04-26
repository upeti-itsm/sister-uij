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
        $data = DB::selectOne('SELECT * FROM organisasi.cek_keabsahan_dokumen(?)', [
            $qr
        ]);

        return $data;
    }
}
