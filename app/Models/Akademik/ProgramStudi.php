<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProgramStudi extends Model
{
    use HasFactory;

    protected $primaryKey = 'kd_program_studi';
    protected $table = 'akademik.program_studi';
    public $timestamps = false;
    protected $casts = [
        'kd_program_studi' => 'string'
    ];
    protected $keyType = 'string';

    public static function get_program_studi($kd_program_studi = "0", $kd_fakultas = "0", $search = "", $sts_aktif = true, $ofset = 0, $limit = 10)
    {
        return DB::select('SELECT * FROM akademik.get_program_studi(:kd_program_studi, :kd_fakultas, :cari, :sts_aktif, :ofset, :limit)', [
            'kd_program_studi' => $kd_program_studi,
            'kd_fakultas' => $kd_fakultas,
            'cari' => $search,
            'sts_aktif' => $sts_aktif,
            'ofset' => $ofset,
            'limit' => $limit,
        ]);
    }

    public static function insup_program_studi($kode, $nama, $id_jenjang, $nama_jenjang, $sts_feeder, $id){
        return DB::select("SELECT * FROM sipadu.insup_program_studi(:kode, :nama, :id_jenjang, :nama_jenjang, :sts_feeder, :id)", [
            'kode' => $kode,
            'nama' => $nama,
            'id_jenjang' => $id_jenjang,
            'nama_jenjang' => $nama_jenjang,
            'sts_feeder' => $sts_feeder,
            'id' => $id
        ])[0];
    }

    public static function set_status_program_studi($id_program_studi, $status_aktif = false)
    {
        return DB::select('SELECT * FROM akademik.set_status_program_studi(:id_program_studi, :sts_aktif)', [
            'id_program_studi' => $id_program_studi,
            'sts_aktif' => $status_aktif
        ])[0];
    }
}
