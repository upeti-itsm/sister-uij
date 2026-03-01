<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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
        $status = null,
        $tahun  = null,
        $nim = null,
        $kd_prodi = null,
        $search = '',
        $offset = -1,
        $limit  = 10
    ) {
        return DB::select(
            "SELECT * FROM akademik.get_list_pengajuan_transkrip_nilai(?,?,?,?,?,?,?)",
            [
                $status,
                $tahun,
                $nim,
                $kd_prodi,
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
            "SELECT * FROM akademik.get_ipk_mahasiswa_by_nim(?)",
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
        $keperluan
    ) {
        return DB::selectOne(
            "SELECT * FROM akademik.insup_pengajuan_transkrip_nilai(?,?,?,?)",
            [
                null,
                (string) $nim,
                (string) $keperluan,
                '1'
            ]
        );
    }

    /**
     * Get detail satu pengajuan transkrip
     * Ownership check dilakukan di level DB function (nim harus cocok)
     *
     * @param string|int $idPengajuan
     * @return object|null
     */
    public static function get_detail($idPengajuan)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.get_detail_riwayat_transkrip_nilai(?)",
            [
                $idPengajuan
            ]
        );
    }

    public static function ajukan_draft($idPengajuan)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.set_status_pengajuan_transkrip_nilai(?::uuid, ?::character varying, ?::uuid, ?::character varying)",
            [
                $idPengajuan,
                '2',
                null,
                ''
            ]
        );
    }

    public static function hapus_draft($idPengajuan)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.set_status_pengajuan_transkrip_nilai(?::uuid, ?::character varying, ?::uuid, ?::character varying)",
            [
                $idPengajuan,
                '0',
                null,
                ''
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
    public static function batalkan_pengajuan($idPengajuan)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.set_status_pengajuan_transkrip_nilai(?::uuid, ?::character varying, ?::uuid, ?::character varying)",
            [
                $idPengajuan,
                '1',
                null,
                ''
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

    // ============================================================
// METHOD TAMBAHAN UNTUK KAPRODI
// Tambahkan method-method berikut ke dalam class PengajuanTranskrip
// ============================================================

    /**
     * Get daftar pengajuan transkrip untuk kaprodi
     * Scope: hanya prodi yang diampu kaprodi ini
     *
     * @param string|int  $idProdi ID prodi kaprodi
     * @param string|null $status  Filter status
     * @param string|null $tahun   Filter tahun (YYYY)
     * @param string|null $prodi   Filter id_prodi spesifik
     * @param string      $search  Keyword pencarian
     * @param int         $offset  Offset pagination
     * @param int         $limit   Limit pagination
     * @return array
     */
    public static function get_daftar_pengajuan_kaprodi(
        $idProdi,
        $status = null,
        $tahun  = null,
        $prodi  = null,
        $search = '',
        $offset = 0,
        $limit  = 10
    ) {
        return DB::select(
            "SELECT * FROM akademik.get_daftar_pengajuan_transkrip_kaprodi(?,?,?,?,?,?,?)",
            [
                $idProdi,
                $status,
                $tahun,
                $prodi,
                $search,
                $offset,
                $limit
            ]
        );
    }

    /**
     * Get statistik pengajuan transkrip untuk kaprodi
     * (menunggu, disetujui, ditolak, total)
     *
     * @param string|int $idProdi ID prodi kaprodi
     * @return object|null
     */
    public static function get_statistik_kaprodi($idProdi)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.get_statistik_pengajuan_transkrip_kaprodi(?)",
            [$idProdi]
        );
    }

    /**
     * Get daftar prodi yang diampu kaprodi
     * (untuk dropdown filter di halaman kaprodi)
     *
     * @param string|int $idProdi ID prodi kaprodi
     * @return array
     */
    public static function get_prodi_list_kaprodi($idProdi)
    {
        return DB::select(
            "SELECT * FROM akademik.get_prodi_list_kaprodi(?)",
            [$idProdi]
        );
    }

    /**
     * Get detail pengajuan transkrip (scope kaprodi)
     * Ownership check: pengajuan harus dari prodi yang diampu kaprodi ini
     *
     * @param string|int $idPengajuan ID pengajuan
     * @param string|int $idProdi     ID prodi kaprodi
     * @return object|null
     */
    public static function get_detail_kaprodi($idPengajuan, $idProdi)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.get_detail_pengajuan_transkrip_kaprodi(?,?)",
            [
                $idPengajuan,
                $idProdi
            ]
        );
    }

    /**
     * Cek apakah mahasiswa terdaftar di prodi yang diampu kaprodi
     * (digunakan sebelum kaprodi melihat preview nilai mahasiswa)
     *
     * @param string     $nim     NIM mahasiswa
     * @param string|int $idProdi ID prodi kaprodi
     * @return object|null
     */
    public static function cek_mahasiswa_prodi($nim, $idProdi)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.cek_mahasiswa_prodi(?,?)",
            [
                (string) $nim,
                $idProdi
            ]
        );
    }

    /**
     * Setujui pengajuan transkrip oleh kaprodi
     * Status berubah: diajukan -> proses_dekan
     * Mengembalikan object dengan field: status, keterangan
     *
     * @param string|int  $idPengajuan ID pengajuan
     * @param string|int  $idUser      ID user kaprodi (untuk log riwayat)
     * @param string|null $catatan     Catatan kaprodi (opsional)
     * @return object|null
     */
    public static function setujui_kaprodi($idPengajuan, $idUser, $catatan = null)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.setujui_pengajuan_transkrip_kaprodi(?,?,?)",
            [
                $idPengajuan,
                $idUser,
                $catatan
            ]
        );
    }

    /**
     * Tolak pengajuan transkrip oleh kaprodi
     * Status berubah: diajukan -> ditolak
     * Mengembalikan object dengan field: status, keterangan
     *
     * @param string|int $idPengajuan ID pengajuan
     * @param string|int $idUser      ID user kaprodi (untuk log riwayat)
     * @param string     $alasanTolak Alasan penolakan (wajib)
     * @return object|null
     */
    public static function tolak_kaprodi($idPengajuan, $idUser, $alasanTolak)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.tolak_pengajuan_transkrip_kaprodi(?,?,?)",
            [
                $idPengajuan,
                $idUser,
                (string) $alasanTolak
            ]
        );
    }

    // ============================================================
// METHOD TAMBAHAN UNTUK DEKAN
// Tambahkan method-method berikut ke dalam class PengajuanTranskrip
// ============================================================

    /**
     * Get daftar pengajuan transkrip untuk dekan
     * Scope: semua prodi dalam fakultas yang dipimpin dekan ini
     *
     * @param string|int  $idFakultas ID fakultas dekan
     * @param string|null $status     Filter status
     * @param string|null $tahun      Filter tahun (YYYY)
     * @param string|null $prodi      Filter id_prodi spesifik
     * @param string      $search     Keyword pencarian
     * @param int         $offset     Offset pagination
     * @param int         $limit      Limit pagination
     * @return array
     */
    public static function get_daftar_pengajuan_dekan(
        $idFakultas,
        $status = null,
        $tahun  = null,
        $prodi  = null,
        $search = '',
        $offset = 0,
        $limit  = 10
    ) {
        return DB::select(
            "SELECT * FROM akademik.get_daftar_pengajuan_transkrip_dekan(?,?,?,?,?,?,?)",
            [
                $idFakultas,
                $status,
                $tahun,
                $prodi,
                $search,
                $offset,
                $limit
            ]
        );
    }

    /**
     * Get statistik pengajuan transkrip untuk dekan
     * (menunggu, disahkan, ditolak, total)
     *
     * @param string|int $idFakultas ID fakultas dekan
     * @return object|null
     */
    public static function get_statistik_dekan($idFakultas)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.get_statistik_pengajuan_transkrip_dekan(?)",
            [$idFakultas]
        );
    }

    /**
     * Get daftar prodi dalam fakultas dekan
     * (untuk dropdown filter di halaman dekan)
     *
     * @param string|int $idFakultas ID fakultas dekan
     * @return array
     */
    public static function get_prodi_list_dekan($idFakultas)
    {
        return DB::select(
            "SELECT * FROM akademik.get_prodi_list_dekan(?)",
            [$idFakultas]
        );
    }

    /**
     * Get detail pengajuan transkrip (scope dekan)
     * Ownership check: pengajuan harus dari fakultas yang dipimpin dekan ini
     * Termasuk info persetujuan kaprodi (nama_kaprodi, tgl_kaprodi, catatan_kaprodi)
     *
     * @param string|int $idPengajuan ID pengajuan
     * @param string|int $idFakultas  ID fakultas dekan
     * @return object|null
     */
    public static function get_detail_dekan($idPengajuan, $idFakultas)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.get_detail_pengajuan_transkrip_dekan(?,?)",
            [
                $idPengajuan,
                $idFakultas
            ]
        );
    }

    /**
     * Cek apakah mahasiswa terdaftar di fakultas yang dipimpin dekan
     * (digunakan sebelum dekan melihat preview nilai mahasiswa)
     *
     * @param string     $nim        NIM mahasiswa
     * @param string|int $idFakultas ID fakultas dekan
     * @return object|null
     */
    public static function cek_mahasiswa_fakultas($nim, $idFakultas)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.cek_mahasiswa_fakultas(?,?)",
            [
                (string) $nim,
                $idFakultas
            ]
        );
    }

    /**
     * Sahkan pengajuan transkrip oleh dekan
     * Status berubah: proses_dekan -> disetujui
     * Mengembalikan object dengan field: status, keterangan
     *
     * @param string|int  $idPengajuan ID pengajuan
     * @param string|int  $idUser      ID user dekan (untuk log riwayat)
     * @param string|null $catatan     Catatan dekan (opsional)
     * @return object|null
     */
    public static function sahkan_dekan($idPengajuan, $idUser, $catatan = null)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.sahkan_pengajuan_transkrip_dekan(?,?,?)",
            [
                $idPengajuan,
                $idUser,
                $catatan
            ]
        );
    }

    /**
     * Tolak pengajuan transkrip oleh dekan
     * Status berubah: proses_dekan -> ditolak
     * Mengembalikan object dengan field: status, keterangan
     *
     * @param string|int $idPengajuan ID pengajuan
     * @param string|int $idUser      ID user dekan (untuk log riwayat)
     * @param string     $alasanTolak Alasan penolakan (wajib)
     * @return object|null
     */
    public static function tolak_dekan($idPengajuan, $idUser, $alasanTolak)
    {
        return DB::selectOne(
            "SELECT * FROM akademik.tolak_pengajuan_transkrip_dekan(?,?,?)",
            [
                $idPengajuan,
                $idUser,
                (string) $alasanTolak
            ]
        );
    }
}
