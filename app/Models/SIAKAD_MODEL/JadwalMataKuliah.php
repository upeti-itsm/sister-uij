<?php

namespace App\Models\SIAKAD_MODEL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JadwalMataKuliah extends Model
{
    use HasFactory;

    public static function get_jadwal_mata_kuliah($tahun_akademik){
        return DB::connection('siakad')->select('SELECT t1.nama_dosen, t1.nama_asisten, t1.jadwal_kuliah_id, t1.tahun_akademik, t1.kelas_id, t1.ruang_id, t1.hari, t1.jam_mulai, t1.jam_selesai, t1.mata_kuliah_id AS matakuliah_id, t1.nama_mata_kuliah, t1.kapasitas, t1.dosen_id, t3.nik AS nik_pengampu, t4.nik AS nik_asisten, t1.asisten_id, t1.prodi AS kd_prodi, t2.jumlah_sks, CASE WHEN UPPER(t1.nama_mata_kuliah) LIKE "LABORATORIUM%" THEN 1 ELSE 0 END AS is_lab, CASE WHEN UPPER(RIGHT(t1.kelas_id, 1)) = "M" THEN 2 ELSE 1 END AS jenis_kelas FROM tblJadwalKuliah t1 JOIN tblMataKuliah t2 ON t1.mata_kuliah_id = t2.mata_kuliah_id JOIN tblKaryawan t3 ON t1.dosen_id = t3.karyawan_id JOIN tblKaryawan t4 ON LOWER(t1.nama_asisten) = LOWER(t4.nama_lengkap) WHERE t1.tahun_akademik = :tahun_akademik', [
            'tahun_akademik' => $tahun_akademik
        ]);
    }

    public static function get_jadwal_mata_kuliah_by_id($jadwal_kuliah_id){
        return DB::connection('siakad')->select('SELECT t1.nama_dosen, t1.nama_asisten, t1.jadwal_kuliah_id, t1.tahun_akademik, t1.kelas_id, t1.ruang_id, t1.hari, t1.jam_mulai, t1.jam_selesai, t1.mata_kuliah_id AS matakuliah_id, t1.nama_mata_kuliah, t1.kapasitas, t1.dosen_id, t3.nik AS nik_pengampu, t4.nik AS nik_asisten, t1.asisten_id, t1.prodi AS kd_prodi, t2.jumlah_sks, CASE WHEN UPPER(t1.nama_mata_kuliah) LIKE "LABORATORIUM%" THEN 1 ELSE 0 END AS is_lab, CASE WHEN UPPER(RIGHT(t1.kelas_id, 1)) = "M" THEN 2 ELSE 1 END AS jenis_kelas FROM tblJadwalKuliah t1 JOIN tblMataKuliah t2 ON t1.mata_kuliah_id = t2.mata_kuliah_id JOIN tblKaryawan t3 ON t1.dosen_id = t3.karyawan_id JOIN tblKaryawan t4 ON LOWER(t1.nama_asisten) = LOWER(t4.nama_lengkap) WHERE t1.jadwal_kuliah_id = :jadwal_kuliah_id', [
            'jadwal_kuliah_id' => $jadwal_kuliah_id
        ])[0];
    }

    public static function get_jadwal_kuliah($tahun_akademik){
        return DB::connection('siakad')->select("SELECT * FROM tblJadwalKuliah WHERE tahun_akademik = :tahun_akademik AND fd_id_kls != '-'", [
            'tahun_akademik' => $tahun_akademik
        ]);
    }
}
