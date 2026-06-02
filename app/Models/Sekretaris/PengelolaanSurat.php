<?php

namespace App\Models\Sekretaris;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PengelolaanSurat extends Model
{
    use HasFactory;

    public static function get_all_status_pengajuan()
    {
        return DB::select('SELECT * FROM organisasi.get_status_surat_rektorat()');
    }

    public static function get_all_jenis_surat()
    {
        return DB::select('SELECT * FROM organisasi.get_jenis_surat_rektorat()');
    }

    public static function get_all_pimpinan_rektorat()
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_pimpinan_rektorat()');
    }

    public static function get_daftar_pengajuan_surat_rektorat($p_id_log_surat, $p_id_personal_akses, $p_id_unit_bagian_akses, $p_id_status_surat, $p_id_jenis_surat, $p_tanggal_dari, $p_tanggal_sampai, $p_sts_aktif, $p_param_search, $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM akademik.get_daftar_pengajuan_surat_rektorat(:p_id_log_surat, :p_id_personal_akses, :p_id_unit_bagian_akses, :p_id_status_surat, :p_id_jenis_surat, :p_tanggal_dari, :p_tanggal_sampai, :p_sts_aktif, :p_param_search, :offset, :limit)', [
            'p_id_log_surat' => $p_id_log_surat,
            'p_id_personal_akses' => $p_id_personal_akses,
            'p_id_unit_bagian_akses' => $p_id_unit_bagian_akses,
            'p_id_status_surat' => $p_id_status_surat,
            'p_id_jenis_surat' => $p_id_jenis_surat,
            'p_tanggal_dari' => $p_tanggal_dari,
            'p_tanggal_sampai' => $p_tanggal_sampai,
            'p_sts_aktif' => $p_sts_aktif,
            'p_param_search' => $p_param_search,
            'offset' => $offset,
            'limit' => $limit
        ]);
    }

    public static function insup_surat($p_id_log_surat, $p_perihal, $p_unit_bagian_penerima, $p_personal_penerima, $p_isi_surat, $p_tanggal_surat, $p_id_jenis_surat, $p_id_personal_pengaju, $p_unit_bagian_pengirim, $p_lampiran, $p_is_sekretaris)
    {
        return DB::select('SELECT * FROM akademik.insup_pengajuan_surat_rektorat(:p_id_log_surat, :p_perihal, :p_unit_bagian_penerima, :p_personal_penerima, :p_isi_surat, :p_tanggal_surat, :p_id_jenis_surat, :p_id_personal_pengaju, :p_unit_bagian_pengirim, :p_lampiran, :p_is_sekretaris)', [
            'p_id_log_surat' => $p_id_log_surat,
            'p_perihal' => $p_perihal,
            'p_unit_bagian_penerima' => $p_unit_bagian_penerima,
            'p_personal_penerima' => $p_personal_penerima,
            'p_isi_surat' => $p_isi_surat,
            'p_tanggal_surat' => $p_tanggal_surat,
            'p_id_jenis_surat' => $p_id_jenis_surat,
            'p_id_personal_pengaju' => $p_id_personal_pengaju,
            'p_unit_bagian_pengirim' => $p_unit_bagian_pengirim,
            'p_lampiran' => $p_lampiran,
            'p_is_sekretaris' => $p_is_sekretaris
        ]);
    }
}
