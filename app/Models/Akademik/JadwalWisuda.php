<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JadwalWisuda extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_jadwal_wisuda';
    protected $table = 'akademik.jadwal_wisuda';

    public static function get_daftar_jadwal_wisuda($tahun_akademik = "all", $offset = 0, $limit = 10)
    {
        return DB::select('SELECT * FROM akademik.get_jadwal_wisuda(:tahun_akademik, :ofset, :limit)', [
            'tahun_akademik' => $tahun_akademik,
            'ofset' => $offset,
            'limit' => $limit
        ]);
    }

    public static function insup($periode, $tgl_pelaksanaan, $kuota, $tgl_pendaftaran_dibuka, $tgl_pendaftaran_ditutup, $id = 0)
    {
        return DB::select('SELECT * FROM akademik.insup_jadwal_wisuda(:id, :periode, :tgl_pelaksanaan, :kuota, :tgl_pendaftaran_dibuka, :tgl_pendaftaran_ditutup)', [
            'id' => $id,
            'periode' => $periode,
            'tgl_pelaksanaan' => $tgl_pelaksanaan,
            'kuota' => $kuota,
            'tgl_pendaftaran_dibuka' => $tgl_pendaftaran_dibuka,
            'tgl_pendaftaran_ditutup' => $tgl_pendaftaran_ditutup
        ])[0];
    }

    public static function delete_jadwal($id)
    {
        return DB::select('SELECT * FROM akademik.delete_jadwal_wisuda(:id)', [
            'id' => $id
        ])[0];
    }

    public static function get_tahun_pelaksanaan()
    {
        return DB::select('SELECT * FROM akademik.get_list_tahun_pelaksanaan_wisuda()');
    }

    public static function check_mahasiswa($id_mahasiswa)
    {
        return DB::select('SELECT * FROM akademik.get_status_mahasiswa_daftar_wisuda(:id_mahasiswa)', [
            'id_mahasiswa' => $id_mahasiswa
        ])[0];
    }
}
