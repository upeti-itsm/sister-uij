<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class temp_model extends Model
{
    use HasFactory;

    public static function get_am()
    {
        return DB::select("SELECT * FROM temp_schema.backup_am WHERE id_semester = '20221' AND nama_civitas IS NULL AND id_jenis_aktivitas IN (3, 2, 22) --LIMIT 1");
    }

    public static function get_kurikulum()
    {
        return DB::select("SELECT * FROM temp_schema.kurikulum WHERE id_kurikulum IS NULL ORDER BY id ASC");
    }

    public static function get_matkul_kurikulum()
    {
        return DB::select("SELECT * FROM temp_schema.matkul_kurikulum WHERE inserted IS NULL ORDER BY id ASC");
    }

    public static function get_peserta_kelas_kuliah()
    {
        return DB::select("SELECT * FROM temp_schema.peserta_kelas_kuliah_genap WHERE inserted IS NULL AND id_semester = '20222' ORDER BY id ASC --LIMIT 1");
    }

    public static function set_id_aktivitas($id, $id_aktivitas)
    {
        return DB::select("UPDATE temp_schema.backup_am SET id_aktivitas_feeder = ? WHERE id = ?", [
            $id_aktivitas, $id
        ]);
    }

    public static function get_am_pembimbing()
    {
//        return DB::select("SELECT t1.*, t2.id_aktivitas_feeder FROM temp_schema.backup_am_pembimbing t1 JOIN temp_schema.backup_am t2 ON t1.id_aktivitas = t2.id_aktivitas_feeder WHERE t2.id_semester = '20221' AND t1.id_bimbing_mahasiswa_feeder IS NULL AND id_kategori_kegiatan = '110601' ORDER BY t1.id ASC");
        return DB::select("SELECT * FROM temp_schema.backup_am_pembimbing WHERE id_aktivitas_feeder IS NOT NULL AND nama_dosen IS NOT NULL --LIMIT 1");
    }

    public static function set_id_bimbing($id, $id_aktivitas)
    {
        return DB::select("UPDATE temp_schema.backup_am_pembimbing SET id_bimbing_mahasiswa_feeder = ? WHERE id = ?", [
            $id_aktivitas, $id
        ]);
    }

    public static function get_am_penguji()
    {
//        return DB::select("SELECT t1.*, t2.id_aktivitas_feeder FROM temp_schema.backup_am_penguji t1 JOIN temp_schema.backup_am t2 ON t1.id_aktivitas = t2.id_aktivitas_feeder WHERE t2.id_semester = '20222' AND t1.id_uji_feeder IS NULL ORDER BY t1.id ASC");
        return DB::select("SELECT * FROM temp_schema.backup_am_penguji WHERE id_aktivitas_feeder IS NOT NULL AND nama_dosen IS NOT NULL");
    }

    public static function get_mahasiswa()
    {
        return DB::select("SELECT t1.* FROM temp_schema.mahasiswa t1 WHERE t1.id_mahasiswa IS NULL ORDER BY t1.id ASC");
    }

    public static function set_id_uji($id, $id_aktivitas)
    {
        return DB::select("UPDATE temp_schema.backup_am_penguji SET id_uji_feeder = ? WHERE id = ?", [
            $id_aktivitas, $id
        ]);
    }

    public static function get_am_peserta()
    {
//        return DB::select("SELECT t1.*, t2.id_aktivitas_feeder FROM temp_schema.anggota_aktivitas_mahasiswa t1 JOIN temp_schema.aktivitas_mahasiswa t2 ON t1.id_aktivitas = t2.id_aktivitas_feeder WHERE t2.id_semester = '20221' AND t2.nama_civitas IS NOT NULL AND t1.id_anggota_feeder IS NULL");
        return DB::select("SELECT * FROM temp_schema.backup_am_peserta WHERE id_aktivitas_feeder IS NOT NULL AND nama_jenis_peran IS NOT NULL");
    }

    public static function delete_anggota_feeder($id_anggota)
    {
        return DB::select("DELETE FROM temp_schema.anggota_aktivitas_mahasiswa WHERE id_anggota_feeder = ?", [
            $id_anggota
        ]);
    }

    public static function set_id_anggota($id, $id_aktivitas)
    {
        return DB::select("UPDATE temp_schema.anggota_aktivitas_mahasiswa SET id_anggota_feeder = ? WHERE id = ?", [
            $id_aktivitas, $id
        ]);
    }

    public static function get_anggota_bimbingan_krs()
    {
        return DB::select('SELECT t2.id_aktivitas_feeder, t1.id_registrasi_mahasiswa, 2 FROM temp_schema.bimbingan_krs t1 JOIN temp_schema.backup_am t2 ON t1.dosen = t2.nama_civitas WHERE t1.id_registrasi_mahasiswa IS NOT NULL');
    }

    public static function set_id_kurikulum($id, $id_aktivitas)
    {
        return DB::select("UPDATE temp_schema.kurikulum SET id_kurikulum = ? WHERE id = ?", [
            $id_aktivitas, $id
        ]);
    }

    public static function set_inserted_matkul_kurikulum($idKur, $idMatkul)
    {
        return DB::select("UPDATE temp_schema.matkul_kurikulum SET inserted = 1 WHERE id_matkul = ? AND id_kurikulum = ?", [
            $idMatkul, $idKur
        ]);
    }

    public static function set_inserted_peserta_kuliah($idKelas, $idRegis)
    {
        return DB::select("UPDATE temp_schema.peserta_kelas_kuliah_genap SET inserted = 1 WHERE id_kelas_kuliah = ? AND id_registrasi_mahasiswa = ?", [
            $idKelas, $idRegis
        ]);
    }

    public static function set_id_mahasiswa($idKelas, $idRegis)
    {
        return DB::select("UPDATE temp_schema.mahasiswa SET id_mahasiswa = 1 WHERE id = ?", [
            $idKelas, $idRegis
        ]);
    }

    public static function get_pengampu_kelas_kuliah()
    {
        return DB::select('SELECT * FROM temp_schema.dosen_pengampu WHERE id_aktivitas_mengajar IS NULL -- LIMIT 1');
    }

    public static function set_id_aktivitas_mengajar($id, $id_aktivitas)
    {
        return DB::select("UPDATE temp_schema.dosen_pengampu SET id_aktivitas_mengajar = ? WHERE id = ?", [
            $id_aktivitas, $id
        ]);
    }

    public static function get_nilai_kelas_kuliah()
    {
        return DB::select("SELECT * FROM temp_schema.peserta_kelas_kuliah_genap WHERE id_semester = '20222' AND inserted_nilai IS NULL --LIMIT 1");
    }

    public static function set_inserted_nilai_kelas($id)
    {
        return DB::select("UPDATE temp_schema.peserta_kelas_kuliah_genap SET inserted_nilai = 1 WHERE id = ?", [
            $id
        ]);
    }

    public static function get_indeks_prestasi()
    {
        return DB::select("SELECT * FROM temp_schema.indeks_prestasi WHERE id_semester = '20222' AND inserted IS NULL --LIMIT 10");
    }

    public static function set_inserted_indeks_prestasi($id)
    {
        return DB::select("UPDATE temp_schema.indeks_prestasi SET inserted = 1 WHERE id = ?", [
            $id
        ]);
    }

    public static function set_error_indeks_prestasi($id, $error)
    {
        return DB::select("UPDATE temp_schema.indeks_prestasi SET error_desc = ? WHERE id = ?", [
            $error, $id
        ]);
    }
}
