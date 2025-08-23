<?php

namespace App\Models\SIAKAD_MODEL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JadwalMataKuliah extends Model
{
    use HasFactory;

    public static function get_jadwal_mata_kuliah($tahun_akademik){
        return DB::connection('siakad')->select("SELECT ROW_NUMBER() OVER (ORDER BY jp.idjadwalperkuliahan) AS row_num, jp.idjadwalperkuliahan AS p_jadwal_kuliah_id, kp.thnakademik AS p_tahun_akademik, kp.idkelas AS p_kelas_id, kp.namakelas AS p_nama_kelas, jp.idruang AS p_ruang_id, CAST(jp.kdhari AS integer) AS p_hari, jam.jammulai AS p_jam_mulai, jam.jamakhir AS p_jam_selesai, mk.idmatakuliah AS p_matakuliah_id, mk.namamatakuliah AS p_nama_mata_kuliah, r.kapasitas AS p_kapasitas, dk.idstafdosen AS p_dosen_id, NULL AS p_asisten_id, NULL AS p_nama_asisten, kp.kdprogramstudi AS p_kd_prodi, mk.sks AS p_sks, 0 AS p_is_lab, 1 AS p_id_jenis_kelas_matakuliah FROM akademik.jadwalperkuliahan jp JOIN akademik.kelasperkuliahan kp ON jp.idkelas = kp.idkelas JOIN sarana.ruang r ON jp.idruang = r.idruang JOIN support.hari h ON jp.kdhari = h.kdhari JOIN akademik.jamperkuliahan jam ON jp.kdjamperkuliahan = jam.kdjamperkuliahan JOIN akademik.kegiatanmatakuliah kmk ON kp.idkegiatanmatakuliah = kmk.idkegiatanmatakuliah JOIN akademik.matakuliah mk ON kmk.idmatakuliah = mk.idmatakuliah LEFT JOIN akademik.dosenkelas dk ON kp.idkelas = dk.idkelas LEFT JOIN staf.stafdosen sd ON dk.idstafdosen = sd.idstafdosen LEFT JOIN support.programstudi ps ON kp.kdprogramstudi = ps.kdprogramstudi WHERE kp.thnakademik = ? AND dk.idstafdosen is not null", [
            $tahun_akademik
        ]);
    }

    public static function get_jadwal_mata_kuliah_by_id($jadwal_kuliah_id){
        return DB::connection('siakad')->select('SELECT ROW_NUMBER() OVER (ORDER BY jp.idjadwalperkuliahan) AS row_num, jp.idjadwalperkuliahan AS p_jadwal_kuliah_id, kp.thnakademik AS p_tahun_akademik, kp.idkelas AS p_kelas_id, kp.namakelas AS p_nama_kelas, jp.idruang AS p_ruang_id, CAST(jp.kdhari AS integer) AS p_hari, jam.jammulai AS p_jam_mulai, jam.jamakhir AS p_jam_selesai, mk.idmatakuliah AS p_matakuliah_id, mk.namamatakuliah AS p_nama_mata_kuliah, r.kapasitas AS p_kapasitas, dk.idstafdosen AS p_dosen_id, NULL AS p_asisten_id, NULL AS p_nama_asisten, kp.kdprogramstudi AS p_kd_prodi, mk.sks AS p_sks, 0 AS p_is_lab, 1 AS p_id_jenis_kelas_matakuliah FROM akademik.jadwalperkuliahan jp JOIN akademik.kelasperkuliahan kp ON jp.idkelas = kp.idkelas JOIN sarana.ruang r ON jp.idruang = r.idruang JOIN support.hari h ON jp.kdhari = h.kdhari JOIN akademik.jamperkuliahan jam ON jp.kdjamperkuliahan = jam.kdjamperkuliahan JOIN akademik.kegiatanmatakuliah kmk ON kp.idkegiatanmatakuliah = kmk.idkegiatanmatakuliah JOIN akademik.matakuliah mk ON kmk.idmatakuliah = mk.idmatakuliah LEFT JOIN akademik.dosenkelas dk ON kp.idkelas = dk.idkelas LEFT JOIN staf.stafdosen sd ON dk.idstafdosen = sd.idstafdosen LEFT JOIN support.programstudi ps ON kp.kdprogramstudi = ps.kdprogramstudi WHERE jp.idjadwalperkuliahan = ? AND dk.idstafdosen is not null;', [
            $jadwal_kuliah_id
        ])[0];
    }

    public static function get_jadwal_kuliah($tahun_akademik){
        return DB::connection('siakad')->select("SELECT * FROM tblJadwalKuliah WHERE tahun_akademik = :tahun_akademik AND fd_id_kls != '-'", [
            'tahun_akademik' => $tahun_akademik
        ]);
    }
}
