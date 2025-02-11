<?php

namespace App\Models\MOODLE_MODEL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JadwalMahasiswa extends Model
{
    use HasFactory;
    protected $connection = "emane";

    public static function get_daftar_jadwal_mahasiswa($tahun_akademik = "...", $search = "", $limit = 10, $offset = 0){
        return DB::connection("emane")->select('SELECT * FROM get_jadwal_mahasiswa(:tahun_akademik, :search, :limit, :offset)', [
            'tahun_akademik' => $tahun_akademik,
            'search' => $search,
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    public static function sync_jadwal_mahasiswa($id_jadwal, $npk, $nama_matkul, $kelas_id, $nama_dosen, $tahun_akademik){
        return DB::connection('emane')->select('SELECT * FROM public.sync_jadwal_mahasiswa(:id_jadwal, :npk, :nama_matkul, :kelas_id, :nama_dosen, :tahun_akademik)', [
            'id_jadwal' => $id_jadwal,
            'npk' => $npk,
            'nama_matkul' => $nama_matkul,
            'kelas_id' => $kelas_id,
            'nama_dosen' => $nama_dosen,
            'tahun_akademik' => $tahun_akademik
        ])[0];
    }

    public static function delete_jadwal_mahasiswa($nim, $tahun_akademik){
        return DB::connection("emane")->select('SELECT * FROM public.delete_jadwal_mahasiswa_by_nim(:nim, :tahun_akademik)', [
            'nim' => $nim,
            'tahun_akademik' => $tahun_akademik
        ])[0];
    }

    public static function delete_jadwal_mahasiswa_by_tahun_akademik($tahun_akademik){
        return DB::connection("emane")->select('SELECT * FROM public.delete_jadwal_mahasiswa_by_tahun_akademik(:tahun_akademik)', [
            'tahun_akademik' => $tahun_akademik
        ])[0];
    }

    public static function delete_jadwal_mahasiswa_by_id($jadwal_id, $nim)
    {
        return DB::connection('emane')->select("SELECT * FROM public.delete_jadwal_mahasiswa(:jadwal_id, :nim)", [
            'jadwal_id' => $jadwal_id,
            'nim' => $nim
        ])[0];
    }
}
