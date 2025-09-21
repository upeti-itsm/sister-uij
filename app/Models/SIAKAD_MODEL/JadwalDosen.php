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
                $tahun_akademik,
                $search,
                $limit,
                $offset
            ]);
        } else {
            return DB::connection('siakad')->select("SELECT cast(t1.idkelas as char(36)) as idkelas, t1.thnakademik, t1.namalengkap AS dosen_pengampuh, t1.namakelas, t3.hari, t4.jamperkuliahan, t4.kdjamperkuliahan, t6.kdmatakuliah, t6.namamatakuliah, t6.sks, t6.thnkurikulum, COUNT(*) OVER()::integer AS jml_record FrOm (sELECt t1.idkelas, t1.namakelas, t1.idkegiatanmatakuliah, t1.thnakademik, rtrim(ltrim(t4.gelardepan || ' ' || t5.nama || ' ' || t4.gelarbelakang)) as namalengkap FrOm akademik.kelasperkuliahan t1 InnEr jOIn akademik.dosenkelas t2 On t1.idkelas=t2.idkelas  LEFT join staf.stafdosen t3 ON t2.idstafdosen = t3.idstafdosen  left join staf.pegawai t4 ON t3.idpegawai = t4.idpegawai left join person.identitas t5 on t4.idpersonal = t5.idpersonal  WhErE thnakademik = ?) t1 LEFt jOIn akademik.jadwalperkuliahan t2 On	t1.idkelas=t2.idkelas LEFt jOIn support.hari t3 On t2.kdhari=t3.kdhari LEFt jOIn akademik.jamperkuliahan t4 On t2.kdjamperkuliahan=t4.kdjamperkuliahan LEFt jOIn akademik.kegiatanmatakuliah t5 On t1.idkegiatanmatakuliah = t5.idkegiatanmatakuliah LEFt jOIn akademik.matakuliah t6 On t5.idmatakuliah=t6.idmatakuliah WHERE LOWER(t6.namamatakuliah) ~* LOWER(?) OrdEr BY t2.kdhari ,t2.kdjamperkuliahan ,t6.kdmatakuliah", [
                $tahun_akademik,
                $search
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

    public static function getMatakuliahByDosen($tahun_akademik, $id_personal, $search = '', $offset = 0, $limit = -1)
    {
        return DB::select("SELECT * FROM akademik.get_list_matakuliah_by_personal_dosen(?,?,?,?,?)", [
            $id_personal, $tahun_akademik, $search, $offset, $limit
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

    public static function getMatkulDosen($nidn)
    {
        return DB::select("SELECT * FROM akademik.daftar_jadwal_perkuliahan_dosen(?)", [
            $nidn
        ]);
    }

    public static function getDetailJadwalKuliah($id)
    {
        return DB::connection('siakad')->select('SELECT * FROM tblJadwalKuliah tjk WHERE tjk.jadwal_kuliah_id = ?', [
            $id
        ])[0];
    }

    public static function export_presensi($id_jadwal, $search = '', $offset = -1, $limit = 0)
    {
        return DB::select("select * from absensi.rekap_presensi_mahasiswa_per_jadwal(?,?,?,?)", [
            $id_jadwal, $search, $offset, $limit
        ]);
    }

    public static function insert_kriteria_penilaian($id_jadwal, $id_kriteria, $kriteria = NULL, $bobot = NULL)
    {
        return DB::selectOne("SELECT * FROM akademik.insert_kriteria_penilaian(?,?,?,?)", [
            $id_jadwal, $id_kriteria, $kriteria, $bobot
        ]);
    }

    public static function get_kriteria_penilaian($id_jadwal_kuliah)
    {
        return DB::select("SELECT *  FROM akademik.list_kriteria_penilaian_jadwal_kuliah(?)", [
            $id_jadwal_kuliah
        ]);
    }

    public static function get_list_mahasiswa_by_jadwal($id_jadwal)
    {
        return DB::select("SELECT * FROM akademik.get_list_mahasiswa_jadwal_kuliah(?)", [
            $id_jadwal
        ]);
    }

    public static function insup_nilai($id_kriteria_penilaian, $nim, $nilai)
    {
        // Validasi input
        self::validateInputs($id_kriteria_penilaian, $nim, $nilai);

        // Menggunakan Laravel's DB dengan raw expression yang lebih aman
        return DB::selectOne(
            "SELECT * FROM akademik.insup_nilai_matakuliah(?, ?, ?)",
            [
                (string) $id_kriteria_penilaian,
                (string) $nim,
                (string) $nilai
            ]
        );
    }

    private static function validateInputs($kriteria, $nim, $nilai)
    {
        // Validasi bahwa input tidak mengandung karakter berbahaya
        $dangerousChars = ["'", '"', ';', '--', '/*', '*/', 'DROP', 'DELETE', 'UPDATE', 'INSERT'];

        foreach ([$kriteria, $nim, $nilai] as $input) {
            foreach ($dangerousChars as $char) {
                if (stripos($input, $char) !== false && $char !== ';' && $char !== ',') {
                    throw new \InvalidArgumentException('Input mengandung karakter yang tidak diizinkan');
                }
            }
        }

        // Validasi format spesifik
        if (!preg_match('/^[a-f0-9-,]+$/i', $kriteria)) {
            throw new \InvalidArgumentException('Format kriteria tidak valid');
        }

        if (!preg_match('/^[0-9,]+$/', $nim)) {
            throw new \InvalidArgumentException('Format NIM tidak valid');
        }

        if (!preg_match('/^[0-9.,;]+$/', $nilai)) {
            throw new \InvalidArgumentException('Format nilai tidak valid');
        }
    }

    public static function get_kriteria($search = NULL, $offset = -1, $limit = 10)
    {
        return DB::select("SELECT * FROM akademik.get_daftar_kriteria_penilaian(?,?,?)", [
            $search, $offset, $limit
        ]);
    }

    public static function delete_kriteria($id_kriteria_penilaian)
    {
        return DB::selectOne("SELECT * FROM akademik.delete_kriteria_penilaian_matakuliah(?)", [
            $id_kriteria_penilaian
        ]);
    }
}
