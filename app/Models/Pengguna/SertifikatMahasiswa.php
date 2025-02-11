<?php

namespace App\Models\Pengguna;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SertifikatMahasiswa extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_sertifikat';
    protected $table = 'pengguna.sertifikat_mahasiswa';
    protected $fillable =
        ['waktu_login', 'waktu_akses_terakhir', 'id_mhs', 'ip_komputer', 'is_data_aktif'];
    public $timestamps = false;
    protected $casts = [
        'id_sertifikat' => 'string'
    ];
    protected $keyType = 'string';
}
