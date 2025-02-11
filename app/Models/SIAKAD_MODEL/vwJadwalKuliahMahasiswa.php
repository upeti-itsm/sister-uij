<?php

namespace App\Models\SIAKAD_MODEL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class vwJadwalKuliahMahasiswa extends Model
{
    use HasFactory;

    protected $connection = "siakad";

    public static function getVwJadwalKuliahMahasiswa($tahun_akademik)
    {
        return DB::connection('siakad')->select('SELECT jadwal_kuliah_id, NPK, nama_mata_kuliah, kelas_id, nama_dosen, tahun_akademik FROM vwJadwalKuliahMahasiswa WHERE tahun_akademik = :tahun_akademik', [
            'tahun_akademik' => $tahun_akademik
        ]);
    }

    public static function getDaftarVwJadwalKuliahMahasiswa($tahun_akademik, $search = "", $limit = -1, $offset = 0)
    {
        if ($limit == -1)
            return DB::connection('siakad')->select("SELECT cast(t1.idkelas as char(36)) as idkelas ,T1.nim ,T1.namakelas ,T3.hari ,T3.kdhari ,T4.jamperkuliahan ,T4.kdJamperkuliahan ,T6.kdmatakuliah ,T6.namamatakuliah ,T6.sks ,T6.thnkurikulum ,COUNT(*) OVER()::integer AS jml_record FROM ( SELECT T1.idkelas ,T1.namakelas ,T1.idkegiatanmatakuliah ,T2.nim FROM akademik.kelasperkuliahan T1 INNER JOIN akademik.pesertakelas T2 ON T1.idkelas=T2.idkelas WHERE (T2.nim ~* ?) AND thnakademik=? ) T1 LEFT JOIN akademik.jadwalperkuliahan T2 ON T1.idkelas=T2.idkelas LEFT JOIN support.hari T3 ON T2.kdhari=T3.kdhari LEFT JOIN akademik.jamperkuliahan T4 ON T2.kdjamperkuliahan=T4.kdjamperkuliahan LEFT JOIN akademik.kegiatanmatakuliah T5 ON T1.idkegiatanmatakuliah=T5.idkegiatanmatakuliah LEFT JOIN akademik.matakuliah T6 ON T5.idmatakuliah=T6.idmataKuliah ORDER BY T2.kdhari ,T2.kdjamperkuliahan ,T6.kdmatakuliah", [
                $search, $tahun_akademik
            ]);
        else
            return DB::connection('siakad')->select("SELECT cast(t1.idkelas as char(36)) as idkelas ,T1.nim ,T1.namakelas ,T3.hari ,T3.kdhari ,T4.jamperkuliahan ,T4.kdJamperkuliahan ,T6.kdmatakuliah ,T6.namamatakuliah ,T6.sks ,T6.thnkurikulum ,COUNT(*) OVER()::integer AS jml_record FROM ( SELECT T1.idkelas ,T1.namakelas ,T1.idkegiatanmatakuliah ,T2.nim FROM akademik.kelasperkuliahan T1 INNER JOIN akademik.pesertakelas T2 ON T1.idkelas=T2.idkelas WHERE (T2.nim ~* ?) AND thnakademik=? ) T1 LEFT JOIN akademik.jadwalperkuliahan T2 ON T1.idkelas=T2.idkelas LEFT JOIN support.hari T3 ON T2.kdhari=T3.kdhari LEFT JOIN akademik.jamperkuliahan T4 ON T2.kdjamperkuliahan=T4.kdjamperkuliahan LEFT JOIN akademik.kegiatanmatakuliah T5 ON T1.idkegiatanmatakuliah=T5.idkegiatanmatakuliah LEFT JOIN akademik.matakuliah T6 ON T5.idmatakuliah=T6.idmataKuliah ORDER BY T2.kdhari ,T2.kdjamperkuliahan ,T6.kdmatakuliah LIMIT ? OFFSET ?", [
                $search, $tahun_akademik, $limit, $offset
            ]);
    }

    public static function getTotalRecordVwJadwalKuliahMahasiswa($tahun_akademik, $search = "")
    {
        return DB::connection('siakad')->select('SELECT COUNT(jadwal_kuliah_id) AS jml_record FROM vwJadwalKuliahMahasiswa WHERE tahun_akademik = :tahun_akademik AND (LOWER(NPK) LIKE CONCAT("%", LOWER(:search), "%") OR LOWER(nama_mata_kuliah) LIKE CONCAT("%", LOWER(:nama), "%"))', [
            'tahun_akademik' => $tahun_akademik,
            'search' => $search,
            'nama' => $search,
        ]);
    }

    public static function getVwJadwalKuliahMahasiswaByNim($nim, $tahun_akademik)
    {
        return DB::connection('siakad')->select('SELECT jadwal_kuliah_id, NPK, nama_mata_kuliah, kelas_id, nama_dosen, tahun_akademik FROM vwJadwalKuliahMahasiswa WHERE tahun_akademik = :tahun_akademik AND NPK = :npk', [
            'npk' => $nim,
            'tahun_akademik' => $tahun_akademik
        ]);
    }

    public static function getVwJadwalKuliahMahasiswaById($id_jadwal)
    {
        return DB::connection('siakad')->select('SELECT jadwal_kuliah_id, NPK, nama_mata_kuliah, kelas_id, nama_dosen, tahun_akademik FROM vwJadwalKuliahMahasiswa WHERE jadwal_kuliah_id :id_jadwal', [
            'id_jadwal' => $id_jadwal
        ]);
    }

    public static function isKrsTugasAkhir($nim)
    {
        return DB::connection('siakad')->select('SELECT jadwal_kuliah_id, NPK, nama_mata_kuliah, kelas_id, nama_dosen, tahun_akademik FROM vwJadwalKuliahMahasiswa WHERE tahun_akademik = :tahun_akademik AND NPK = :npk AND (upper(nama_mata_kuliah) = "SKRIPSI" OR upper(nama_mata_kuliah) = "TUGAS AKHIR" OR upper(nama_mata_kuliah) = "TESIS")', [
            'npk' => $nim,
            'tahun_akademik' => TahunAkademik::getTahunAkademikAktif()->tahun_akademik
        ]);
    }
}
