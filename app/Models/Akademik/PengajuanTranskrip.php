<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PengajuanTranskrip extends Model
{
    use HasFactory;
    protected $table = 'akademik.pengajuan_transkrip';

    // ============================================================
    // DATATABLE
    // ============================================================

    /**
     * Get daftar pengajuan transkrip milik mahasiswa (server-side DataTable)
     *
     * @param string      $nim    NIM mahasiswa
     * @param string|null $status Filter status pengajuan
     * @param string|null $tahun  Filter tahun pengajuan (YYYY)
     * @param string      $search Keyword pencarian
     * @param int         $offset Offset pagination
     * @param int         $limit  Limit pagination
     * @return array
     */
    public static function get_daftar_pengajuan(
        $nim,
        $status = null,
        $tahun  = null,
        $search = '',
        $offset = 0,
        $limit  = 10
    ) {
        return DB::select(
            "SELECT * FROM akademik.get_daftar_pengajuan_transkrip(?,?,?,?,?,?)",
            [
                (string) $nim,
                $status,
                $tahun,
                $search,
                $offset,
                $limit
            ]
        );
    }

    // ============================================================
    // STATISTIK & INFO
    // ============================================================

    /**
     * Get statistik pengajuan transkrip
     * (total, diproses, disetujui, ditolak)
     *
     * @param string $nim NIM mahasiswa
     * @return object|null
     */
    public static function get_statistik($nim)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.get_statistik_pengajuan_transkrip(?)",
            [(string) $nim]
        );
    }

    /**
     * Get info mahasiswa untuk form pengajuan
     * (IPK diambil dari fungsi transkrip)
     *
     * @param string $nim NIM mahasiswa
     * @return object|null
     */
    public static function get_info_mahasiswa($nim)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.get_transkrip_mahasiswa(?)",
            [(string) $nim]
        );
    }

    // ============================================================
    // CRUD PENGAJUAN
    // ============================================================

    /**
     * Buat pengajuan transkrip baru
     * Mengembalikan object dengan field: status, keterangan, no_pengajuan
     *
     * @param string      $nim
     * @param string      $keperluan
     * @param string      $bahasa
     * @param int         $jumlahLembar
     * @param string|null $emailTujuan
     * @param string|null $catatan
     * @return object|null
     */
    public static function buat_pengajuan(
        $nim,
        $keperluan,
        $bahasa,
        $jumlahLembar,
        $emailTujuan = null,
        $catatan     = null
    ) {
        return DB::selectOne(
            "SELECT * FROM akademik.buat_pengajuan_transkrip(?,?,?,?,?,?)",
            [
                (string) $nim,
                (string) $keperluan,
                (string) $bahasa,
                (int)    $jumlahLembar,
                $emailTujuan,
                $catatan
            ]
        );
    }

    /**
     * Get detail satu pengajuan transkrip
     * Ownership check dilakukan di level DB function (nim harus cocok)
     *
     * @param string|int $idPengajuan
     * @param string     $nim
     * @return object|null
     */
    public static function get_detail($idPengajuan, $nim)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.get_detail_pengajuan_transkrip(?,?)",
            [
                $idPengajuan,
                (string) $nim
            ]
        );
    }

    /**
     * Batalkan pengajuan transkrip
     * Mengembalikan object dengan field: status, keterangan
     *
     * @param string|int $idPengajuan
     * @param string     $nim
     * @return object|null
     */
    public static function batalkan_pengajuan($idPengajuan, $nim)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.batalkan_pengajuan_transkrip(?,?)",
            [
                $idPengajuan,
                (string) $nim
            ]
        );
    }

    // ============================================================
    // RIWAYAT & NILAI
    // ============================================================

    /**
     * Get riwayat aktivitas satu pengajuan transkrip
     *
     * @param string|int $idPengajuan
     * @return array
     */
    public static function get_riwayat($idPengajuan)
    {
        return DB::select(
            "SELECT * FROM akademik.get_riwayat_pengajuan_transkrip(?)",
            [$idPengajuan]
        );
    }

    /**
     * Get data nilai lengkap mahasiswa untuk cetak transkrip
     * (semua semester, semua mata kuliah)
     *
     * @param string $nim NIM mahasiswa
     * @return array
     */
    public static function get_nilai_transkrip($nim)
    {
        return DB::select(
            "SELECT * FROM akademik.get_nilai_transkrip_mahasiswa(?)",
            [(string) $nim]
        );
    }

    // ============================================================
    // HELPER / CEK
    // ============================================================

    /**
     * Cek apakah mahasiswa punya pengajuan yang sedang aktif
     * (status: diajukan / proses_kaprodi / proses_dekan)
     * Mengembalikan object jika ada, null jika tidak ada
     *
     * @param string $nim NIM mahasiswa
     * @return object|null
     */
    public static function cek_pengajuan_aktif($nim)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.cek_pengajuan_transkrip_aktif(?)",
            [(string) $nim]
        );
    }
}
