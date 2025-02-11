<?php

namespace App\Models\Sistem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Modul extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_modul';
    protected $table = 'sistem.modul';
    protected $fillable =
        ['tgl_created', 'tgl_updated', 'is_data_aktif',
            'id_aplikasi',
            'nama_modul',
            'keterangan'];
    public $timestamps = false;
    protected $casts = [
        'id_modul' => 'string'
    ];
    protected $keyType = 'string';

    public static function getListModulByPersonal($id_personal)
    {
        return DB::select('select * from sistem.list_modul_by_personal(:id_personal)', ['id_personal' => $id_personal]);
    }

    public static function getListModulByPeran($id_peran)
    {
        return DB::select('select * from sistem.list_modul_by_peran(:id_peran)', ['id_peran' => $id_peran]);
    }

    public static function get_daftar_modul($id_aplikasi = "00000000-0000-0000-0000-000000000000", $search = "", $offset = 0, $limit = -1)
    {
        return DB::select('SELECT * FROM sistem.get_daftar_modul(:id_aplikasi, :cari, :ofset, :limit)', [
            'id_aplikasi' => $id_aplikasi,
            'ofset' => $offset,
            'limit' => $limit,
            'cari' => $search
        ]);
    }

    public static function insup($id_modul, $id_aplikasi, $nama_modul, $keterangan){
        return DB::select('SELECT * FROM sistem.insup_daftar_modul(:id_modul, :id_aplikasi, :nama_modul, :keterangan)', [
            'id_modul' => $id_modul,
            'id_aplikasi' => $id_aplikasi,
            'nama_modul' => $nama_modul,
            'keterangan' => $keterangan
        ])[0];
    }

    public static function delete_modul($id_modul){
        return DB::select('SELECT * FROM sistem.delete_modul(:id_modul)', [
            'id_modul' => $id_modul
        ])[0];
    }
}
