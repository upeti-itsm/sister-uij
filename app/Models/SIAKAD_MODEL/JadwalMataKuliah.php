<?php

namespace App\Models\SIAKAD_MODEL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JadwalMataKuliah extends Model
{
    use HasFactory;

    public static function get_jadwal_mata_kuliah($tahun_akademik){
        return DB::connection('siakad')->select("select row_number() over ( order by jp.idjadwalperkuliahan) as row_num, jp.idjadwalperkuliahan as p_jadwal_kuliah_id, kp.thnakademik as p_tahun_akademik, kp.idkelas as p_kelas_id, kp.namakelas as p_nama_kelas, jp.idruang as p_ruang_id, cast(jp.kdhari as integer) as p_hari, jam.jammulai as p_jam_mulai, jam.jamakhir as p_jam_selesai, mk.idmatakuliah as p_matakuliah_id, mk.namamatakuliah as p_nama_mata_kuliah, r.kapasitas as p_kapasitas, trim(p.nomorpegawai) as p_dosen_id, null as p_asisten_id, null as p_nama_asisten, kp.kdprogramstudi as p_kd_prodi, mk.sks as p_sks, 0 as p_is_lab, 1 as p_id_jenis_kelas_matakuliah, mk.kdmatakuliah as p_kd_mata_kuliah from akademik.jadwalperkuliahan jp join akademik.kelasperkuliahan kp on jp.idkelas = kp.idkelas join sarana.ruang r on jp.idruang = r.idruang join support.hari h on jp.kdhari = h.kdhari join akademik.jamperkuliahan jam on jp.kdjamperkuliahan = jam.kdjamperkuliahan join akademik.kegiatanmatakuliah kmk on kp.idkegiatanmatakuliah = kmk.idkegiatanmatakuliah join akademik.matakuliah mk on kmk.idmatakuliah = mk.idmatakuliah left join akademik.dosenkelas dk on kp.idkelas = dk.idkelas inner join staf.stafdosen sd on dk.idstafdosen = sd.idstafdosen left join support.programstudi ps on kp.kdprogramstudi = ps.kdprogramstudi left join staf.pegawai p using(idpegawai) where length(trim(p.nomorpegawai)) >= 10 and kp.thnakademik = ?", [
            $tahun_akademik
        ]);
    }

    public static function get_jadwal_mata_kuliah_by_id($jadwal_kuliah_id){
        return DB::connection('siakad')->select('select row_number() over ( order by jp.idjadwalperkuliahan) as row_num, jp.idjadwalperkuliahan as p_jadwal_kuliah_id, kp.thnakademik as p_tahun_akademik, kp.idkelas as p_kelas_id, kp.namakelas as p_nama_kelas, jp.idruang as p_ruang_id, cast(jp.kdhari as integer) as p_hari, jam.jammulai as p_jam_mulai, jam.jamakhir as p_jam_selesai, mk.idmatakuliah as p_matakuliah_id, mk.namamatakuliah as p_nama_mata_kuliah, r.kapasitas as p_kapasitas, trim(p.nomorpegawai) as p_dosen_id, null as p_asisten_id, null as p_nama_asisten, kp.kdprogramstudi as p_kd_prodi, mk.sks as p_sks, 0 as p_is_lab, 1 as p_id_jenis_kelas_matakuliah, mk.kdmatakuliah as p_kd_mata_kuliah from akademik.jadwalperkuliahan jp join akademik.kelasperkuliahan kp on jp.idkelas = kp.idkelas join sarana.ruang r on jp.idruang = r.idruang join support.hari h on jp.kdhari = h.kdhari join akademik.jamperkuliahan jam on jp.kdjamperkuliahan = jam.kdjamperkuliahan join akademik.kegiatanmatakuliah kmk on kp.idkegiatanmatakuliah = kmk.idkegiatanmatakuliah join akademik.matakuliah mk on kmk.idmatakuliah = mk.idmatakuliah left join akademik.dosenkelas dk on kp.idkelas = dk.idkelas inner join staf.stafdosen sd on dk.idstafdosen = sd.idstafdosen left join support.programstudi ps on kp.kdprogramstudi = ps.kdprogramstudi left join staf.pegawai p using(idpegawai) where length(trim(p.nomorpegawai)) >= 10 and jp.idjadwalperkuliahan = ?', [
            $jadwal_kuliah_id
        ])[0];
    }

    public static function get_jadwal_kuliah($tahun_akademik){
        return DB::connection('siakad')->select("SELECT * FROM tblJadwalKuliah WHERE tahun_akademik = :tahun_akademik AND fd_id_kls != '-'", [
            'tahun_akademik' => $tahun_akademik
        ]);
    }
}
