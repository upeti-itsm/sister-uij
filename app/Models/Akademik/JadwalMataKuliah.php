<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JadwalMataKuliah extends Model
{
    use HasFactory;

    public static function get_tahun_akademik()
    {
        return DB::select('SELECT * FROM akademik.get_tahun_akademik_jadwal_kuliah()');
    }

    public static function get_jadwal_matakuliah($prodi = "all", $tahun_akademik = "all", $search = "", $offset = 0, $limit = -1, $status = -1)
    {
        return DB::select('SELECT * FROM akademik.get_daftar_jadwal_kuliah(:prodi, :tahun_akademik, :search, :offset, :limit, :status)', [
            'search' => $search, 'prodi' => $prodi, 'tahun_akademik' => $tahun_akademik,
            'limit' => $limit, 'offset' => $offset, 'status' => $status
        ]);
    }

    public static function get_detail_jadwal_kuliah($id)
    {
        return DB::select('SELECT * FROM akademik.get_detail_jadwal_kuliah(:id)', [
            'id' => $id
        ])[0];
    }

    public static function set_jenis_jadwal_kuliah($id, $jenis_jadwal, $koordinator_id){
        return DB::select('SELECT * FROM akademik.set_jenis_jadwal_kuliah(:id, :jenis_jadwal, :koordinator)', [
            'id' => $id, 'jenis_jadwal' => $jenis_jadwal, 'koordinator' => $koordinator_id
        ])[0];
    }

    public static function sync_jadwal_matakuliah_with_siakad($jadawal_kuliah_id, $tahun_akademik, $kelas_id, $ruang_id, $hari, $jam_mulai, $jam_selesai, $matakuliah_id, $nama_mata_kuliah, $kapasitas, $dosen_id, $asisten_id, $kd_prodi, $jml_sks, $is_lab, $jenis_kelas)
    {
        return DB::select('SELECT * FROM akademik.sync_jadwal_matakuliah_with_siakad(:jadwal_kuliah_id, :tahun_akademik, :kelas_id, :ruang_id, :hari, :jam_mulai, :jam_selesai, :matakuliah_id, :nama_mata_kuliah, :kapasitas, :dosen_id, :asisten_id, :kd_prodi, :jml_sks, :is_lab, :jenis_kelas)', [
            'jadwal_kuliah_id' => $jadawal_kuliah_id, 'tahun_akademik' => $tahun_akademik, 'kelas_id' => $kelas_id, 'ruang_id' => $ruang_id,
            'hari' => $hari, 'jam_mulai' => $jam_mulai, 'jam_selesai' => $jam_selesai, 'matakuliah_id' => $matakuliah_id, 'nama_mata_kuliah' => $nama_mata_kuliah,
            'kapasitas' => $kapasitas, 'dosen_id' => $dosen_id, 'asisten_id' => $asisten_id, 'kd_prodi' => $kd_prodi,
            'jml_sks' => $jml_sks, 'is_lab' => $is_lab, 'jenis_kelas' => $jenis_kelas
        ])[0];
    }
}
