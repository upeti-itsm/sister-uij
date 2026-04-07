<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class KRS extends Model
{
    use HasFactory;

    public static function get_daftar($kd_prodi, $tahun_akademik = 1, $nim = '', $search = '', $offset = 0, $limit = 100000)
    {
        return DB::select("SELECT * FROM akademik.get_daftar_jadwal_krs(?,?,?,?,?,?)", [
            $kd_prodi, $tahun_akademik, $nim, $search, $offset, $limit
        ]);
    }

    public static function getSKSMaks($nim)
    {
        return DB::selectOne('SELECT * FROM akademik.get_sks_maksimal_mahasiswa(?)', [
            $nim
        ]);
    }

    public static function inDelKRS($nim, $id_jadwal, $id_krs = '00000000-0000-0000-0000-000000000000') {
        return DB::selectOne('SELECT * FROM akademik.indel_krs_mahasiswa(?,?,?)', [
            $nim, $id_jadwal, $id_krs
        ]);
    }

    public static function get_detail_krs($id_krs)
    {
        return DB::select('SELECT * FROM akademik.get_detail_krs_mahasiswa(?)', [
            $id_krs
        ]);
    }

    public static function get_statistik_krs($tahun_akademik = '1', $type = 'all', $value = null)
    {
        return DB::selectOne('SELECT * FROM akademik.get_statistik_krs(?,?,?)', [
            $type, $value, $tahun_akademik
        ]);
    }

    public static function update_status_krs($id_krs, $status, $komentar = null, $nidn = null)
    {
        /*
         * 0 = DRAFT
         * 1 = MENUNGGU DPS
         * 2 = ACC DPS / MENUNGGU KAPRODI
         * 3 = DITOLAK DPS
         * 4 = SELESAI
         */
        return DB::selectOne('SELECT * FROM akademik.update_status_krs_mahasiswa(?,?,?,?)', [
            $id_krs, $status, $komentar, $nidn
        ]);
    }

    public static function get_daftar_krs_dps($nidn, $status_krs = null, $offset = -1, $limit = 10, $search = '', $tahun_akademik = '1') {
        return DB::select('SELECT * FROM akademik.daftar_krs_masuk_dps(?,?,?,?,?,?)', [
            $nidn, $status_krs, $offset, $limit, $search, $tahun_akademik
        ]);
    }

    public static function get_rekap_krs_dps($nidn = '', $tahun_akademik = '1')
    {
        return DB::selectOne('SELECT * FROM akademik.rekap_krs_per_dps(?,?)', [
            $nidn, $tahun_akademik
        ]);
    }

    public static function get_daftar_krs_prodi($kd_prodi, $status = null, $offset = -1, $limit = 10, $search = '', $tahun_akademik = '1')
    {
        return DB::select('SELECT * FROM akademik.daftar_krs_masuk_kaprodi(?,?,?,?,?,?)', [
            $kd_prodi, $status, $offset, $limit, $search, $tahun_akademik
        ]);
    }

    public static function get_rekap_krs_prodi($nidn_kaprodi = '', $tahun_akademik = '1')
    {
        return DB::selectOne('SELECT * FROM akademik.rekap_krs_per_kaprodi(?,?)', [
            $nidn_kaprodi, $tahun_akademik
        ]);
    }

    public static function get_riwayat_krs_mahasiswa($nim, $tahun_akademik = null, $search = '', $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM akademik.get_list_krs_by_mahasiswa(?,?,?,?,?)', [
            $nim, $tahun_akademik, $search, $offset, $limit
        ]);
    }

    public static function cek_periode_krs($nim)
    {
        return DB::selectOne("SELECT * FROM akademik.cek_periode_krs(?)", [$nim]);
    }
}
