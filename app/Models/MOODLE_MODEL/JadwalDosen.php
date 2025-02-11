<?php

namespace App\Models\MOODLE_MODEL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JadwalDosen extends Model
{
    use HasFactory;
    protected $connection = "emane";

    public static function get_daftar_jadwal_siakad($tahun_akademik = "...", $search = "", $limit = 10, $offset = 0){
        return DB::connection("emane")->select('SELECT * FROM get_jadwal_siakad(:tahun_akademik, :search, :limit, :offset)', [
            'tahun_akademik' => $tahun_akademik,
            'search' => $search,
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    public static function sync_jadwal_siakad($id_jadwal, $nama_matkul, $kelas_id, $prodi, $tahun_akademik, $nik, $nama_pengajar, $nik_asisten, $nama_asisten){
        return DB::connection('emane')->select('SELECT * FROM public.sync_jadwal_siakad(:id_jadwal, :nama_matkul, :kelas_id, :prodi, :tahun_akademik, :nik, :nama_pengajar, :nik_asisten, :nama_asisten)', [
            'id_jadwal' => $id_jadwal,
            'nama_matkul' => $nama_matkul,
            'kelas_id' => $kelas_id,
            'prodi' => $prodi,
            'tahun_akademik' => $tahun_akademik,
            'nik' => $nik,
            'nik_asisten' => $nik_asisten,
            'nama_pengajar' => $nama_pengajar,
            'nama_asisten' => $nama_asisten
        ])[0];
    }

    public static function move_to_course($tahun_akademik){
        return DB::connection("emane")->select('SELECT * FROM sync_course_moodle_v2(:tahun_akademik)', [
            'tahun_akademik' => $tahun_akademik
        ])[0];
    }

    public static function get_asynchron_course(){
        return DB::connection("emane")->select('SELECT * FROM get_asynchron_course()')[0];
    }
}
