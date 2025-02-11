<?php

namespace App\Models\Pengguna;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_sertifikat';
    protected $table = 'pengguna.sertifikat';
    protected $fillable =
        ['waktu_login', 'waktu_akses_terakhir', 'id_personal', 'ip_komputer', 'is_data_aktif'];
    public $timestamps = false;
    protected $casts = [
        'id_sertifikat' => 'string'
    ];
    protected $keyType = 'string';
}
