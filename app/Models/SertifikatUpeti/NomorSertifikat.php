<?php

namespace App\Models\SertifikatUpeti;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NomorSertifikat extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'sertifikat_upeti.nomor_sertifika';
    public $timestamps = false;

    public static function get_daftar_nomor_sertifikat($offset = 0, $limit = 0, $search = "", $is_aktif = true)
    {
        return DB::select('SELECT * FROM sertifikat_upeti.get_daftar_nomor_sertifikat(:offset, :limit, :search, :is_aktif)', [
            'offset' => $offset,
            'limit' => $limit,
            'search' => $search,
            'is_aktif' => $is_aktif
        ]);
    }

    public static function insup_nomor_sertifikat($nomor_sertifikat, $id = 0){
        return DB::select('SELECT * FROM sertifikat_upeti.insup_nomor_sertifikat(:id, :nomor_sertifikat)', [
            'id' => $id,
            'nomor_sertifikat' => $nomor_sertifikat
        ])[0];
    }
}
