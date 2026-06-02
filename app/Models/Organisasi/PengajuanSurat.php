<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PengajuanSurat extends Model
{
    use HasFactory;

    /**
     * Ambil daftar pengajuan surat dengan pagination & search
     */
    public static function daftar_pengajuan_surat(
        $id_personal,
        $id_unit_bagian = null,
        $id_log_surat = null,
        $id_status_surat = null,
        $id_jenis_surat = null,
        $tanggal_dari = null,
        $tanggal_sampai = null,
        $sts_aktif = true,
        $search = '',
        $page = 1,
        $limit = 10
    ) {
        return DB::select(
            'SELECT * FROM akademik.get_daftar_pengajuan_surat_rektorat(
                :p_id_log_surat,
                :p_id_personal_akses,
                :p_id_unit_bagian_akses,
                :p_id_status_surat,
                :p_id_jenis_surat,
                :p_tanggal_dari,
                :p_tanggal_sampai,
                :p_sts_aktif,
                :p_param_search,
                :p_no_page,
                :p_jml_record_perpage
            )',
            [
                'p_id_log_surat'         => $id_log_surat,
                'p_id_personal_akses'    => $id_personal,
                'p_id_unit_bagian_akses' => $id_unit_bagian,
                'p_id_status_surat'      => $id_status_surat,
                'p_id_jenis_surat'       => $id_jenis_surat,
                'p_tanggal_dari'         => $tanggal_dari,
                'p_tanggal_sampai'       => $tanggal_sampai,
                'p_sts_aktif'            => $sts_aktif,
                'p_param_search'         => $search ?? '',
                'p_no_page'              => $page,
                'p_jml_record_perpage'   => $limit,
            ]
        );
    }

    /**
     * Insert / Update pengajuan surat rektorat
     */
    public static function insup_pengajuan_surat(
        $id_log_surat,
        $perihal,
        array $unit_bagian_penerima,
        array $personal_penerima,
        $isi_surat,
        $tanggal_surat,
        $id_jenis_surat,
        $unit_bagian_pengirim,
        $lampiran = null
    ) {
        $unit_bagian_penerima_pg = '{' . implode(',', $unit_bagian_penerima) . '}';
        $personal_penerima_pg    = '{' . implode(',', $personal_penerima) . '}';

        return DB::select(
            'SELECT * FROM akademik.insup_pengajuan_surat_rektorat(
                :id_log_surat,
                :perihal,
                :unit_bagian_penerima,
                :personal_penerima,
                :isi_surat,
                :tanggal_surat,
                :id_jenis_surat,
                :unit_bagian_pengirim,
                :lampiran
            )',
            [
                'id_log_surat'          => $id_log_surat,
                'perihal'               => $perihal,
                'unit_bagian_penerima'  => $unit_bagian_penerima_pg,
                'personal_penerima'     => $personal_penerima_pg,
                'isi_surat'             => $isi_surat,
                'tanggal_surat'         => $tanggal_surat,
                'id_jenis_surat'        => $id_jenis_surat,
                'unit_bagian_pengirim'  => $unit_bagian_pengirim,
                'lampiran'              => $lampiran,
            ]
        )[0];
    }

    /**
     * Hapus pengajuan surat
     */
    public static function delete_pengajuan_surat($id_log_surat)
    {
        return DB::select('SELECT * FROM akademik.hapus_pengajuan_surat(:id_log_surat)', [
            'id_log_surat' => $id_log_surat,
        ])[0];
    }

    /**
     * Detail satu pengajuan surat
     */
    public static function detail_pengajuan_surat($id_log_surat)
    {
        $data = DB::select(
            'SELECT * FROM akademik.get_daftar_pengajuan_surat_rektorat(
                :p_id_log_surat,
                :p_id_personal_akses,
                :p_id_unit_bagian_akses,
                :p_id_status_surat,
                :p_id_jenis_surat,
                :p_tanggal_dari,
                :p_tanggal_sampai,
                :p_sts_aktif,
                :p_param_search,
                :p_no_page,
                :p_jml_record_perpage
            )',
            [
                'p_id_log_surat'         => $id_log_surat,
                'p_id_personal_akses'    => null,
                'p_id_unit_bagian_akses' => null,
                'p_id_status_surat'      => null,
                'p_id_jenis_surat'       => null,
                'p_tanggal_dari'         => null,
                'p_tanggal_sampai'       => null,
                'p_sts_aktif'            => true,
                'p_param_search'         => '',
                'p_no_page'              => 1,
                'p_jml_record_perpage'   => 1,
            ]
        );

        return $data[0] ?? null;
    }

    /**
     * Daftar tahun untuk filter
     */
    public static function daftar_tahun_pengajuan_surat()
    {
        return DB::select('SELECT * FROM akademik.daftar_tahun_pengajuan_surat()');
    }

    /**
     * Daftar jenis surat (untuk dropdown)
     */
    public static function daftar_jenis_surat()
    {
        return DB::select('SELECT * FROM organisasi.get_jenis_surat_rektorat(:p_id_jenis_surat)', [
            'p_id_jenis_surat' => null,
        ]);
    }

    /**
     * Daftar status surat (untuk dropdown)
     */
    public static function daftar_status_surat()
    {
        return DB::select('SELECT * FROM organisasi.get_status_surat_rektorat(:p_id_status_surat)', [
            'p_id_status_surat' => null,
        ]);
    }

    /**
     * Daftar pimpinan rektorat (untuk validasi)
     */
    public static function daftar_pimpinan_rektorat()
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_pimpinan_rektorat()');
    }

    /**
     * Set status pengajuan surat rektorat
     */
    public static function set_status_pengajuan_surat_rektorat(
        $id_log_surat,
        $id_status_surat,
        $id_personal_aktor,
        $id_personal_pimpinan = null
    ) {
        return DB::select(
            'SELECT * FROM akademik.set_status_pengajuan_surat_rektorat(
                :p_id_log_surat,
                :p_id_status_surat,
                :p_id_personal_aktor,
                :p_id_personal_pimpinan
            )',
            [
                'p_id_log_surat' => $id_log_surat,
                'p_id_status_surat' => $id_status_surat,
                'p_id_personal_aktor' => $id_personal_aktor,
                'p_id_personal_pimpinan' => $id_personal_pimpinan,
            ]
        )[0] ?? null;
    }
}
