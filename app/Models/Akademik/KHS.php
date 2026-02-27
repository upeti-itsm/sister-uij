<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class KHS extends Model
{
    use HasFactory;
    protected $table = 'akademik.khs';

    /**
     * Get daftar nilai mahasiswa (KHS)
     *
     * @param string $nim NIM mahasiswa
     * @param string $tahun_akademik ID tahun akademik (1 = aktif, atau kode tahun akademik)
     * @param string $semester Kode semester (1=Ganjil, 2=Genap, 3=Antara)
     * @param string $search Keyword pencarian
     * @param int $offset Offset pagination
     * @param int $limit Limit pagination
     * @return array
     */
    public static function get_daftar_nilai($nim, $tahun_akademik = null, $semester = null, $search = '', $offset = 0, $limit = -1)
    {
        return DB::select("SELECT * FROM akademik.get_hasil_studi_mahasiswa(?,?,?,?,?,?)", [
            (string) $nim,
            $tahun_akademik,
            $semester,
            $search,
            $offset,
            $limit
        ]);
    }

    /**
     * Get daftar tahun akademik yang pernah diambil mahasiswa
     *
     * @param string $nim NIM mahasiswa
     * @return array
     */
    public static function get_tahun_akademik_list($nim)
    {
        return DB::select('SELECT DISTINCT tahun_akademik FROM akademik.get_tahun_akademik_mahasiswa(?) ORDER BY tahun_akademik DESC', [
            $nim
        ]);
    }

    /**
     * Get statistik semester (IPS, total SKS, dll)
     *
     * @param string $nim NIM mahasiswa
     * @param string $tahun_akademik ID tahun akademik
     * @return object|null
     */
    public static function get_statistik_semester($nim, $tahun_akademik = '1')
    {
        return DB::selectOne('SELECT * FROM akademik.get_statistik_semester_mahasiswa(?,?)', [
            $nim,
            $tahun_akademik
        ]);
    }

    /**
     * Get transkrip lengkap mahasiswa (IPK, total SKS, dll)
     *
     * @param string $nim NIM mahasiswa
     * @return object|null
     */
    public static function get_transkrip($nim)
    {
        return DB::selectOne('SELECT * FROM akademik.get_transkrip_mahasiswa(?)', [
            $nim
        ]);
    }

    /**
     * Get detail nilai mata kuliah tertentu
     *
     * @param string $id_nilai ID nilai
     * @return object|null
     */
    public static function get_detail_nilai($id_nilai)
    {
        return DB::selectOne('SELECT * FROM akademik.get_detail_nilai(?)', [
            $id_nilai
        ]);
    }

    /**
     * Get rekap nilai per semester
     *
     * @param string $nim NIM mahasiswa
     * @return array
     */
    public static function get_rekap_per_semester($nim)
    {
        return DB::select('SELECT * FROM akademik.get_rekap_nilai_per_semester(?)', [
            $nim
        ]);
    }

    /**
     * Get grafik perkembangan IP
     *
     * @param string $nim NIM mahasiswa
     * @return array
     */
    public static function get_grafik_ip($nim)
    {
        return DB::select('SELECT * FROM akademik.get_grafik_ip_mahasiswa(?)', [
            $nim
        ]);
    }

    /**
     * Cek apakah mahasiswa sudah lulus
     *
     * @param string $nim NIM mahasiswa
     * @return object|null
     */
    public static function cek_kelulusan($nim)
    {
        return DB::selectOne('SELECT * FROM akademik.cek_kelulusan_mahasiswa(?)', [
            $nim
        ]);
    }

    /**
     * Get mata kuliah yang belum lulus (nilai D atau E)
     *
     * @param string $nim NIM mahasiswa
     * @return array
     */
    public static function get_matakuliah_belum_lulus($nim)
    {
        return DB::select('SELECT * FROM akademik.get_matakuliah_belum_lulus(?)', [
            $nim
        ]);
    }

    /**
     * Get statistik nilai mahasiswa (jumlah A, B, C, D, E)
     *
     * @param string $nim NIM mahasiswa
     * @return object|null
     */
    public static function get_statistik_nilai($nim)
    {
        return DB::selectOne('SELECT * FROM akademik.get_statistik_nilai_mahasiswa(?)', [
            $nim
        ]);
    }
}
