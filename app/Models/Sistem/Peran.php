<?php

namespace App\Models\Sistem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Peran extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_peran';
    protected $table = 'sistem.peran';
    protected $fillable =
        ['tgl_created', 'tgl_updated', 'is_data_aktif',
            'id_aplikasi',
            'nama_peran',
            'kd_kelompok_peran',
            'keterangan'];
    public $timestamps = false;

    public static function get_daftar_peran($id_aplikasi = "00000000-0000-0000-0000-000000000000", $kd_kelompok = "..", $search = "", $offset = 0, $limit = -1)
    {
        return DB::select('SELECT * FROM sistem.get_daftar_peran(:id_aplikasi, :kd_kelompok, :cari, :ofset, :limit)', [
            'id_aplikasi' => $id_aplikasi,
            'kd_kelompok' => $kd_kelompok,
            'ofset' => $offset,
            'limit' => $limit,
            'cari' => $search
        ]);
    }

    public static function insup($id_aplikasi, $nama_peran, $keterangan, $kd_kelompok_peran, $id_peran = NULL){
        return DB::select('SELECT * FROM sistem.insup_daftar_peran(:id_aplikasi, :nama_peran, :kd_kelompok_peran, :keterangan, :id_peran)', [
            'id_aplikasi' => $id_aplikasi, 'nama_peran' => $nama_peran,
            'keterangan' => $keterangan, 'kd_kelompok_peran' => $kd_kelompok_peran,
            'id_peran' => $id_peran
        ])[0];
    }

    public static function delete_peran($id_peran){
        return DB::select('SELECT * FROM sistem.delete_peran(:id_peran)', [
            'id_peran' => $id_peran
        ])[0];
    }

    public static function get_detail_peran($id_peran){
        return DB::select('SELECT * FROM sistem.get_detail_peran(:id_peran)', [
            'id_peran' => $id_peran
        ])[0];
    }
}
