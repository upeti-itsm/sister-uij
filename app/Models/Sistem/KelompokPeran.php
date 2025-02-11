<?php

namespace App\Models\Sistem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class KelompokPeran extends Model
{
    use HasFactory;
    protected $primaryKey = 'kd_kelompok_peran';
    protected $table = 'sistem.kelompok_peran';
    protected $fillable =
        ['kd_kelompok_peran',
            'kelompok_peran'];
    public $timestamps = false;
    protected $casts = [
        'kd_kelompok_peran' => 'string'
    ];
    protected $keyType = 'kd_kelompok_peran';

    public static function get_daftar_kelompok_peran($search = "", $offset = 0, $limit = 10)
    {
        return DB::select('SELECT * FROM sistem.get_daftar_kelompok_peran(:cari, :ofset, :limit)', [
            'ofset' => $offset,
            'limit' => $limit,
            'cari' => $search
        ]);
    }
}
