<?php

namespace App\Models\Kaprodi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PengajuanJurnalMengajarDosen extends Model
{
    use HasFactory;

    public static function get_jurnal_mengajar_dosen($id_personal, $id_pengajuan_jurnal_mengajar_dosen, $sts_pengajuan, $search = '', $offset = 0, $limit = -1)
    {
        return DB::select(
            'SELECT * FROM akademik.get_pengajuan_jurnal_mengajar_dosen(
                :p_id_personal,
                :p_id_pengajuan_jurnal_mengajar_dosen,
                :p_sts_pengajuan,
                :p_search,
                :p_offset,
                :p_limit
            )',
            [
                'p_id_personal' => $id_personal,
                'p_id_pengajuan_jurnal_mengajar_dosen' => $id_pengajuan_jurnal_mengajar_dosen,
                'p_sts_pengajuan' => $sts_pengajuan,
                'p_search' => $search,
                'p_offset' => $offset,
                'p_limit' => $limit
            ]
        );
    }

    public static function set_status_ajuan_oleh_kaprodi($id_jurnal, $status, $id_personal, $catatan)
    {
        return DB::selectOne('SELECT * FROM akademik.set_status_pengajuan_jurnal_mengajar_dosen(?::uuid, ?::integer, ?::uuid, ?::text)', [
            $id_jurnal,
            (int) $status,
            $id_personal,
            $catatan
        ]);
    }
}
