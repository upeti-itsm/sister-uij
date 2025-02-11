<?php

namespace App\Models\Sistem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Aplikasi extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_aplikasi';
    protected $table = 'sistem.aplikasi';
    protected $fillable =
        ['tgl_created', 'tgl_updated', 'is_data_aktif',
            'id_aplikasi',
            'nama_aplikasi',
            'keterangan'];
    public $timestamps = false;
    protected $casts = [
        'id_aplikasi' => 'string'
    ];
    protected $keyType = 'string';

    public static function get_daftar_aplikasi($search = "", $offset = 0, $limit = 10)
    {
        return DB::select('SELECT * FROM sistem.get_daftar_aplikasi(:cari, :ofset, :limit)', [
            'ofset' => $offset,
            'limit' => $limit,
            'cari' => $search
        ]);
    }

    public static function insup($id_aplikasi, $nama_aplikasi, $keterangan){
        return DB::select('SELECT * FROM sistem.insup_daftar_aplikasi(:id_aplikasi, :nama_aplikasi, :keterangan)', [
            'id_aplikasi' => $id_aplikasi,
            'nama_aplikasi' => $nama_aplikasi,
            'keterangan' => $keterangan
        ])[0];
    }

    public static function delete_aplikasi($id_aplikasi){
        return DB::select('SELECT * FROM sistem.delete_aplikasi(:id_aplikasi)', [
            'id_aplikasi' => $id_aplikasi
        ])[0];
    }

    public static function get_detail_aplikasi($id_aplikasi){
        return DB::select('SELECT * FROM sistem.get_detail_aplikasi(:id_aplikasi)', [
            'id_aplikasi' => $id_aplikasi
        ])[0];
    }
}
