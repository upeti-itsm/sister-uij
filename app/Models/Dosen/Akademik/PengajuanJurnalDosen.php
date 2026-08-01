<?php

namespace App\Models\Dosen\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PengajuanJurnalDosen extends Model
{
    use HasFactory;

    public static function insup_pengajuan_jurnal_mengajar_dosen($id_jurnal, $id_jadwal_matkul, $id_personal, $catatan)
    {
        return DB::selectOne('SELECT * FROM akademik.insup_pengajuan_jurnal_mengajar_dosen(?::uuid, ?::character varying, ?::uuid, ?::text)', [
            !empty($id_jurnal) ? $id_jurnal : null,
            $id_jadwal_matkul,
            $id_personal,
            $catatan
        ]);
    }

    public static function set_status_ajuan($id_jurnal, $status, $id_personal)
    {
        return DB::selectOne('SELECT * FROM akademik.set_status_pengajuan_jurnal_mengajar_dosen(?::uuid, ?::integer, ?::uuid)', [
            $id_jurnal,
            (int) $status,
            $id_personal
        ]);
    }

    public static function generate_jurnal_mengajar_dosen($id_personal, $id_jadwal_kuliah, $search = '', $offset = 0, $limit = -1, $tahun_akademik = '00000')
    {
        $limit_val = ($limit !== null && (int)$limit >= 0) ? (int)$limit : null;
        $offset_val = ($offset !== null && (int)$offset >= 0) ? (int)$offset : 0;

        return DB::select('SELECT * FROM absensi.get_rekapitulasi_absensi_mengajar_dosen_by_personal_jurnal(?::uuid, ?::character varying, ?::character varying, ?::integer, ?::integer, ?::character varying)', [
            $id_personal,
            $id_jadwal_kuliah,
            $search ?? '',
            $offset_val,
            $limit_val,
            $tahun_akademik ?? '00000'
        ]);
    }
}
