<?php

namespace App\Models\SIAKAD_MODEL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JadwalMataKuliah extends Model
{
    use HasFactory;

    public static function get_jadwal_mata_kuliah($tahun_akademik){
        return DB::connection('siakad')->select("SELECT ROW_NUMBER() OVER (ORDER BY jp.idjadwalperkuliahan) AS row_num, jp.idjadwalperkuliahan AS p_jadwal_kuliah_id, kp.thnakademik AS p_tahun_akademik, kp.idkelas AS p_kelas_id, jp.idruang AS p_ruang_id, CAST(jp.kdhari AS integer) AS p_hari, jam.jammulai AS p_jam_mulai, jam.jamakhir AS p_jam_selesai, mk.idmatakuliah AS p_matakuliah_id, mk.namamatakuliah AS p_nama_mata_kuliah, r.kapasitas AS p_kapasitas, dk.idstafdosen AS p_dosen_id, NULL AS p_asisten_id, kp.kdprogramstudi AS p_kd_prodi, mk.sks AS p_sks, 0 AS p_is_lab, 1 AS p_id_jenis_kelas_matakuliah FROM akademik.jadwalperkuliahan jp JOIN akademik.kelasperkuliahan kp ON jp.idkelas = kp.idkelas JOIN sarana.ruang r ON jp.idruang = r.idruang JOIN support.hari h ON jp.kdhari = h.kdhari JOIN akademik.jamperkuliahan jam ON jp.kdjamperkuliahan = jam.kdjamperkuliahan JOIN akademik.kegiatanmatakuliah kmk ON kp.idkegiatanmatakuliah = kmk.idkegiatanmatakuliah JOIN akademik.matakuliah mk ON kmk.idmatakuliah = mk.idmatakuliah LEFT JOIN akademik.dosenkelas dk ON kp.idkelas = dk.idkelas LEFT JOIN staf.stafdosen sd ON dk.idstafdosen = sd.idstafdosen LEFT JOIN support.programstudi ps ON kp.kdprogramstudi = ps.kdprogramstudi where kp.thnakademik = ?", [
            $tahun_akademik
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
