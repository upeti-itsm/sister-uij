<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class KonsentrasiJurusan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_konsentrasi_jurusan';
    protected $table = 'akademik.konsentrasi_jurusan';
    public $timestamps = false;
    protected $casts = [
        'id_konsentrasi_jurusan' => 'string'
    ];
    protected $keyType = 'string';

    public static function get_konsentrasi_jurusan($id_konsentrasi_jurusan = '00000000-0000-0000-0000-000000000000', $kd_prodi = "0", $offset = 0, $limit = 10, $search = "", $sts_aktif = true)
    {
        return DB::select('SELECT * FROM sipadu.get_konsentrasi_jurusan(:id_konsentrasi_jurusan, :kd_prodi, :ofset, :limit, :cari, :sts_aktif)', [
            'id_konsentrasi_jurusan' => $id_konsentrasi_jurusan,
            'kd_prodi' => $kd_prodi,
            'ofset' => $offset,
            'limit' => $limit,
            'cari' => $search,
            'sts_aktif' => $sts_aktif
        ]);
    }

    public static function insup_konsentrasi_jurusan($kd_prodi, $nama_konsentrasi, $tahun_dibuka, $id_konsentrasi_jurusan = "00000000-0000-0000-0000-000000000000")
    {
        return DB::select('SELECT * FROM sipadu.insup_konsentrasi_jurusan(:kd_prodi, :nama_konsentrasi, :tahun_dibuka, :id)', [
            'kd_prodi' => $kd_prodi,
            'nama_konsentrasi' => $nama_konsentrasi,
            'tahun_dibuka' => $tahun_dibuka,
            'id' => $id_konsentrasi_jurusan
        ])[0];
    }

    public static function set_status_konsentrasi_jurusan($id_konsentrasi_jurusan, $status_aktif = false)
    {
        return DB::select('SELECT * FROM sipadu.set_status_konsentrasi_jurusan(:id_konsentrasi_jurusan, :sts_aktif)', [
            'id_konsentrasi_jurusan' => $id_konsentrasi_jurusan,
            'sts_aktif' => $status_aktif
        ])[0];
    }
}
