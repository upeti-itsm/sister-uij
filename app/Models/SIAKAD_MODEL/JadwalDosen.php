<?php

namespace App\Models\SIAKAD_MODEL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JadwalDosen extends Model
{
    use HasFactory;

    protected $connection = "siakad";

    public static function getVwJadwalKuliahDosen($tahun_akademik)
    {
        return DB::connection('siakad')->select('SELECT t1.jadwal_kuliah_id, t1.nama_mata_kuliah, t1.kelas_id, t1.prodi, t1.tahun_akademik, t1.nama_lengkap, t1.karyawan_id, t2.nik, t3.nik as nik_asisten, t3.nama_lengkap as nama_asisten FROM vwJadwalDosen t1 JOIN tblKaryawan t2 ON t1.karyawan_id = t2.karyawan_id JOIN tblJadwalKuliah t4 ON t1.jadwal_kuliah_id = t4.jadwal_kuliah_id LEFT JOIN tblKaryawan t3 ON LOWER(t4.nama_asisten) = LOWER(t3.nama_lengkap) WHERE t1.tahun_akademik = :tahun_akademik', [
            'tahun_akademik' => $tahun_akademik
        ]);
    }

    public static function getVwJadwalKuliahDosenById($id)
    {
        return DB::connection('siakad')->select('SELECT t1.jadwal_kuliah_id, t1.nama_mata_kuliah, t1.kelas_id, t1.prodi, t1.tahun_akademik, t1.nama_lengkap, t1.karyawan_id, t2.nik, t3.nik as nik_asisten, t3.nama_lengkap as nama_asisten FROM vwJadwalDosen t1 JOIN tblKaryawan t2 ON t1.karyawan_id = t2.karyawan_id JOIN tblJadwalKuliah t4 ON t1.jadwal_kuliah_id = t4.jadwal_kuliah_id LEFT JOIN tblKaryawan t3 ON LOWER(t4.nama_asisten) = LOWER(t3.nama_lengkap) WHERE t1.jadwal_kuliah_id = :id', [
            'id' => $id
        ])[0];
    }

    public static function vwJadwalKuliahDosen($tahun_akademik, $search = "", $limit = -1, $offset = 0)
    {
        if ($limit != -1) {
            return DB::connection('siakad')->select("SELECT cast(t1.idkelas as char(36)) as idkelas, t1.thnakademik, t1.namalengkap AS dosen_pengampuh, t1.namakelas, t3.hari, t4.jamperkuliahan, t4.kdjamperkuliahan, t6.kdmatakuliah, t6.namamatakuliah, t6.sks, t6.thnkurikulum, COUNT(*) OVER()::integer AS jml_record FrOm (sELECt t1.idkelas, t1.namakelas, t1.idkegiatanmatakuliah, t1.thnakademik, rtrim(ltrim(t4.gelardepan || ' ' || t5.nama || ' ' || t4.gelarbelakang)) as namalengkap FrOm akademik.kelasperkuliahan t1 InnEr jOIn akademik.dosenkelas t2 On t1.idkelas=t2.idkelas  LEFT join staf.stafdosen t3 ON t2.idstafdosen = t3.idstafdosen  left join staf.pegawai t4 ON t3.idpegawai = t4.idpegawai left join person.identitas t5 on t4.idpersonal = t5.idpersonal  WhErE thnakademik = ?) t1 LEFt jOIn akademik.jadwalperkuliahan t2 On	t1.idkelas=t2.idkelas LEFt jOIn support.hari t3 On t2.kdhari=t3.kdhari LEFt jOIn akademik.jamperkuliahan t4 On t2.kdjamperkuliahan=t4.kdjamperkuliahan LEFt jOIn akademik.kegiatanmatakuliah t5 On t1.idkegiatanmatakuliah = t5.idkegiatanmatakuliah LEFt jOIn akademik.matakuliah t6 On t5.idmatakuliah=t6.idmatakuliah WHERE LOWER(t6.namamatakuliah) ~* LOWER(?) OrdEr BY t2.kdhari ,t2.kdjamperkuliahan ,t6.kdmatakuliah LIMIT ? OFFSET ?", [
                $tahun_akademik, $search, $limit, $offset
            ]);
        } else {
            return DB::connection('siakad')->select("SELECT cast(t1.idkelas as char(36)) as idkelas, t1.thnakademik, t1.namalengkap AS dosen_pengampuh, t1.namakelas, t3.hari, t4.jamperkuliahan, t4.kdjamperkuliahan, t6.kdmatakuliah, t6.namamatakuliah, t6.sks, t6.thnkurikulum, COUNT(*) OVER()::integer AS jml_record FrOm (sELECt t1.idkelas, t1.namakelas, t1.idkegiatanmatakuliah, t1.thnakademik, rtrim(ltrim(t4.gelardepan || ' ' || t5.nama || ' ' || t4.gelarbelakang)) as namalengkap FrOm akademik.kelasperkuliahan t1 InnEr jOIn akademik.dosenkelas t2 On t1.idkelas=t2.idkelas  LEFT join staf.stafdosen t3 ON t2.idstafdosen = t3.idstafdosen  left join staf.pegawai t4 ON t3.idpegawai = t4.idpegawai left join person.identitas t5 on t4.idpersonal = t5.idpersonal  WhErE thnakademik = ?) t1 LEFt jOIn akademik.jadwalperkuliahan t2 On	t1.idkelas=t2.idkelas LEFt jOIn support.hari t3 On t2.kdhari=t3.kdhari LEFt jOIn akademik.jamperkuliahan t4 On t2.kdjamperkuliahan=t4.kdjamperkuliahan LEFt jOIn akademik.kegiatanmatakuliah t5 On t1.idkegiatanmatakuliah = t5.idkegiatanmatakuliah LEFt jOIn akademik.matakuliah t6 On t5.idmatakuliah=t6.idmatakuliah WHERE LOWER(t6.namamatakuliah) ~* LOWER(?) OrdEr BY t2.kdhari ,t2.kdjamperkuliahan ,t6.kdmatakuliah", [
                $tahun_akademik, $search
            ]);
        }
    }

    public static function getTotalRecordsVwJadwalDosen($tahun_akademik = "all", $search = "")
    {
        if ($tahun_akademik == "all") {
            return DB::connection('siakad')->select('SELECT COUNT(t1.jadwal_kuliah_id) AS jml_record FROM vwJadwalDosen t1 JOIN tblKaryawan t2 ON t1.karyawan_id = t2.karyawan_id JOIN tblJadwalKuliah t4 ON t1.jadwal_kuliah_id = t4.jadwal_kuliah_id LEFT JOIN tblKaryawan t3 ON LOWER(t4.nama_asisten) = LOWER(t3.nama_lengkap) WHERE (LOWER(t1.nama_mata_kuliah) LIKE CONCAT("%", LOWER(:nama_matkul), "%")) OR (LOWER(t1.nama_lengkap) LIKE CONCAT("%", LOWER(:nama_user), "%")) OR (t1.karyawan_id = :id_karyawan)', [
                'nama_matkul' => $search,
                'nama_user' => $search,
                'id_karyawan' => $search,
            ]);
        } else {
            return DB::connection('siakad')->select('SELECT COUNT(t1.jadwal_kuliah_id) AS jml_record FROM vwJadwalDosen t1 JOIN tblKaryawan t2 ON t1.karyawan_id = t2.karyawan_id JOIN tblJadwalKuliah t4 ON t1.jadwal_kuliah_id = t4.jadwal_kuliah_id LEFT JOIN tblKaryawan t3 ON LOWER(t4.nama_asisten) = LOWER(t3.nama_lengkap) WHERE t1.tahun_akademik = :tahun_akademik AND ((LOWER(t1.nama_mata_kuliah) LIKE CONCAT("%", LOWER(:nama_matkul), "%")) OR (LOWER(t1.nama_lengkap) LIKE CONCAT("%", LOWER(:nama_user), "%")) OR (t1.karyawan_id = :id_karyawan))', [
                'tahun_akademik' => $tahun_akademik,
                'nama_matkul' => $search,
                'nama_user' => $search,
                'id_karyawan' => $search,
            ]);
        }
    }

    public static function getMatakuliahByDosen($username, $tahun_akademik, $search = '')
    {
        return DB::connection('siakad')->select('SELECT t1.jadwal_kuliah_id, t1.nama_mata_kuliah, t1.kelas_id, t1.prodi, t1.tahun_akademik, t1.nama_lengkap, t1.karyawan_id, t2.nik, t3.nik as nik_asisten, t3.nama_lengkap as nama_lengkap_asisten FROM vwJadwalDosen t1 JOIN tblKaryawan t2 ON t1.karyawan_id = t2.karyawan_id JOIN tblJadwalKuliah t4 ON t1.jadwal_kuliah_id = t4.jadwal_kuliah_id LEFT JOIN tblKaryawan t3 ON LOWER(t4.nama_asisten) = LOWER(t3.nama_lengkap) WHERE t2.nik = :username AND t1.tahun_akademik = :tahun_akademik AND (LOWER(t1.nama_mata_kuliah) LIKE CONCAT("%", LOWER(:nama_matkul), "%"))', [
            'username' => $username,
            'tahun_akademik' => $tahun_akademik,
            'nama_matkul' => $search
        ]);
    }

    public static function getTotalRecordsMatakuliahByDosen($username, $tahun_akademik, $search = '')
    {
        return DB::connection('siakad')->select('SELECT COUNT(t1.jadwal_kuliah_id) AS jml_record FROM vwJadwalDosen t1 JOIN tblKaryawan t2 ON t1.karyawan_id = t2.karyawan_id JOIN tblJadwalKuliah t4 ON t1.jadwal_kuliah_id = t4.jadwal_kuliah_id LEFT JOIN tblKaryawan t3 ON LOWER(t4.nama_asisten) = LOWER(t3.nama_lengkap) WHERE t2.nik = :username AND t1.tahun_akademik = :tahun_akademik AND (LOWER(t1.nama_mata_kuliah) LIKE CONCAT("%", LOWER(:nama_matkul), "%"))', [
            'username' => $username,
            'tahun_akademik' => $tahun_akademik,
            'nama_matkul' => $search
        ]);
    }

    public static function getMatkulDosen($staff_id = 'EA60CE8D-2BD5-4513-97AC-0483966592AB', $ta = '20241')
    {
        return DB::connection('siakad')->select("SELECT t1.idkelas, t3.namamatakuliah, t1.namakelas, t4.namaprogramstudi, t1.thnakademik, t8.nama, t6.idstafdosen FROM akademik.kelasperkuliahan t1 JOIN akademik.kegiatanmatakuliah t2 ON t2.idkegiatanmatakuliah = t1.idkegiatanmatakuliah JOIN akademik.matakuliah t3 ON t3.idmatakuliah = t2.idmatakuliah JOIN support.programstudi t4 ON t4.kdprogramstudi=t1.kdprogramstudi JOIN akademik.dosenkelas t5 ON t5.idkelas=t1.idkelas JOIN staf.stafdosen t6 ON t6.idstafdosen=t5.idstafdosen JOIN staf.pegawai t7 ON t7.idpegawai=t6.idpegawai JOIN person.identitas t8 ON t8.idpersonal=t7.idpersonal WHERE t1.thnakademik = ? and t5.idstafdosen = ?", [
            $ta, $staff_id
        ]);
    }

    public static function getDetailJadwalKuliah($id)
    {
        return DB::connection('siakad')->select('SELECT * FROM tblJadwalKuliah tjk WHERE tjk.jadwal_kuliah_id = ?', [
            $id
        ])[0];
    }

}
