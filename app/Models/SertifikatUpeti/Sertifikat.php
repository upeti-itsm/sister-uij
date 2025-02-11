<?php

namespace App\Models\SertifikatUpeti;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sertifikat extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_sertifikat';
    protected $table = 'sertifikat_upeti.sertifikat';
    public $timestamps = false;
    protected $casts = [
        'id_sertifikat' => 'string'
    ];
    protected $keyType = 'string';

    public static function get_sertifikat_by_nim($nim)
    {
        return DB::select('SELECT * FROM sertifikat_upeti.get_sertifikat_by_nim(:nim)', [
            'nim' => $nim
        ])[0];
    }

    public static function generate_sertifikat($id_sertifikat){
        return DB::select('SELECT * FROM sertifikat_upeti.generate_sertifikat(:id_sertifikat)', [
            'id_sertifikat' => $id_sertifikat
        ])[0];
    }
}
