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
        return DB::select('SELECT * FROM akademik.get_daftar_jadwal_kuliah_testing(:prodi, :tahun_akademik, :search, :offset, :limit, :status)', [
            'search' => $search,
            'prodi' => $prodi,
            'tahun_akademik' => $tahun_akademik,
            'limit' => $limit,
            'offset' => $offset,
            'status' => $status
        ]);
    }

    public static function get_detail_jadwal_kuliah($id)
    {
        $result = DB::select('SELECT * FROM akademik.get_daftar_jadwal_kuliah_testing(:prodi, :tahun_akademik, :search, :offset, :limit, :status)', [
            'prodi' => 'all',
            'tahun_akademik' => 'all',
            'search' => $id,
            'offset' => 0,
            'limit' => 1,
            'status' => -1
        ]);

        if (empty($result)) {
            return null;
        }

        return $result[0];
    }

    public static function set_jenis_jadwal_kuliah($id, $jenis_jadwal, $koordinator_id)
    {
        return DB::select('SELECT * FROM akademik.set_jenis_jadwal_kuliah(:id, :jenis_jadwal, :koordinator)', [
            'id' => $id,
            'jenis_jadwal' => $jenis_jadwal,
            'koordinator' => $koordinator_id
        ])[0];
    }

    public static function sync_jadwal_matakuliah_with_siakad($jadawal_kuliah_id, $tahun_akademik, $kelas_id, $ruang_id, $hari, $jam_mulai, $jam_selesai, $matakuliah_id, $nama_mata_kuliah, $kapasitas, $dosen_id, $asisten_id, $kd_prodi, $jml_sks, $is_lab, $jenis_kelas, $kd_matkul)
    {
        return DB::selectOne('SELECT * FROM akademik.sync_jadwal_matakuliah_with_siakad(:jadwal_kuliah_id, :tahun_akademik, :kelas_id, :ruang_id, :hari, :jam_mulai, :jam_selesai, :matakuliah_id, :nama_mata_kuliah, :kapasitas, :dosen_id, :asisten_id, :kd_prodi, :jml_sks, :is_lab, :jenis_kelas, :kd_matkul)', [
            'jadwal_kuliah_id' => $jadawal_kuliah_id,
            'tahun_akademik' => $tahun_akademik,
            'kelas_id' => $kelas_id,
            'ruang_id' => $ruang_id,
            'hari' => $hari,
            'jam_mulai' => $jam_mulai,
            'jam_selesai' => $jam_selesai,
            'matakuliah_id' => $matakuliah_id,
            'nama_mata_kuliah' => $nama_mata_kuliah,
            'kapasitas' => $kapasitas,
            'dosen_id' => $dosen_id,
            'asisten_id' => $asisten_id,
            'kd_prodi' => $kd_prodi,
            'jml_sks' => $jml_sks,
            'is_lab' => $is_lab,
            'jenis_kelas' => $jenis_kelas,
            'kd_matkul' => $kd_matkul
        ]);
    }

    public static function generate_jadwal($tahun_akademik)
    {
        return DB::selectOne("SELECT * FROM akademik.run_set_jadwal_otomatis(?)", [
            $tahun_akademik
        ]);
    }

    public static function list_ruangan($search = "", $offset = 0, $limit = -1)
    {
        return DB::select('SELECT * FROM akademik.get_daftar_ruang_perkuliahan_(:id_ruang_perkuliahan, :search, :offset, :limit)', [
            'id_ruang_perkuliahan' => '00000000-0000-0000-0000-000000000000',
            'search' => $search,
            'offset' => $offset,
            'limit' => $limit
        ]);
    }

    public static function get_daftar_ploting_matakuliah($kd_prodi = "all", $tahun_akademik = "all", $search = "", $offset = 0, $limit = -1)
    {
        return DB::select('SELECT * FROM akademik.get_daftar_ploting_matakuliah(:kd_prodi, :tahun_akademik, :search, :offset, :limit)', [
            'kd_prodi' => $kd_prodi,
            'tahun_akademik' => $tahun_akademik,
            'search' => $search,
            'offset' => $offset,
            'limit' => $limit
        ]);
    }

    public static function insert_jadwal_kuliah($id_ploting_matakuliah, $ruang_id, $hari, $jam_mulai, $jam_selesai, $kapasitas = null,$is_lab = 0 , $sts_aktif = true)
    {
        return DB::selectOne('SELECT * FROM akademik.insert_jadwal_kuliah(:id_ploting_matakuliah, :ruang_id, :hari, :jam_mulai, :jam_selesai, :kapasitas, :is_lab, :sts_aktif)', [
            'id_ploting_matakuliah' => $id_ploting_matakuliah,
            'ruang_id' => $ruang_id,
            'hari' => $hari,
            'jam_mulai' => $jam_mulai,
            'jam_selesai' => $jam_selesai,
            'kapasitas' => $kapasitas,
            'is_lab' => $is_lab,
            'sts_aktif' => $sts_aktif
        ]);
    }

    public static function update_jadwal_kuliah($id, $hari, $jam_mulai, $jam_selesai, $kapasitas, $jenis_jadwal)
    {
        return DB::selectOne('SELECT * FROM akademik.update_jadwal_kuliah(:id, :hari, :jam_mulai, :jam_selesai, :kapasitas, :jenis_jadwal)', [
            'id' => $id,
            'hari' => $hari,
            'jam_mulai' => $jam_mulai,
            'jam_selesai' => $jam_selesai,
            'kapasitas' => $kapasitas,
            'jenis_jadwal' => $jenis_jadwal
        ]);
    }

    public static function update_jadwal_kuliah_testing(
        $id,
        $ruang_id = null,
        $hari = null,
        $jam_mulai = null,
        $jam_selesai = null,
        $sts_aktif = null
    ) {
        return DB::selectOne('SELECT * FROM akademik.update_jadwal_kuliah_testing(
            :id, :ruang_id, :hari, :jam_mulai, :jam_selesai, :sts_aktif
        )', [
            'id' => $id,
            'ruang_id' => $ruang_id,
            'hari' => $hari,
            'jam_mulai' => $jam_mulai,
            'jam_selesai' => $jam_selesai,
            'sts_aktif' => $sts_aktif
        ]);
    }

    public static function delete_jadwal_kuliah($id)
    {
        return DB::selectOne('SELECT * FROM akademik.delete_jadwal_kuliah(:id)', [
            'id' => $id
        ]);
    }

    public static function set_status_aktif_jadwal_kuliah($id, $sts_aktif)
    {
        return DB::selectOne('SELECT * FROM akademik.set_status_aktif_jadwal_kuliah_testing(:id, :sts_aktif)', [
            'id' => $id,
            'sts_aktif' => $sts_aktif
        ]);
    }

    public static function update_status_kuliah_mahasiswa($nim, $status_kuliah)
    {
        return DB::selectOne('SELECT * FROM akademik.update_status_kuliah_mahasiswa(:nim, :status_kuliah)', [
            'nim' => $nim,
            'status_kuliah' => $status_kuliah
        ]);
    }
}
