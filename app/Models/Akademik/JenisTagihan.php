<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JenisTagihan extends Model
{
    use HasFactory;

    public static function get_daftar($id_jenis_tagihan = 0, $status_aktif = null, $search = "", $start = 0, $length = -1)
    {
        return DB::select("SELECT * FROM keuangan.get_daftar_jenis_tagihan(?,?,?,?,?)", [
            $id_jenis_tagihan,
            $status_aktif,
            $search,
            $start,
            $length
        ]);
    }

    public static function insup($jenis_tagihan, $tipe_periodisasi, $status_aktif, $keterangan, $id_jenis_tagihan)
    {
        return DB::selectOne("SELECT * FROM keuangan.insup_jenis_tagihan(?,?,?,?,?)", [
            $jenis_tagihan,
            $tipe_periodisasi,
            $status_aktif,
            $keterangan,
            $id_jenis_tagihan
        ]);
    }

    public static function delete_tagihan($id)
    {
        return DB::selectOne("SELECT * FROM keuangan.hapus_jenis_tagihan(?)", [
            $id
        ]);
    }
}
