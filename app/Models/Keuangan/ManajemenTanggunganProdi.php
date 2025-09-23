<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ManajemenTanggunganProdi extends Model
{
    use HasFactory;

    public static function get_daftar_tagihan_prodi($id_tagihan_prodi, $kd_prodi, $sts_aktif, $search = "", $start = 0, $length = -1)
    {
        return DB::select("SELECT * FROM keuangan.get_daftar_tagihan_prodi(?,?,?,?,?,?)", [
            $id_tagihan_prodi,
            $kd_prodi,
            $sts_aktif,
            $search,
            $start,
            $length
        ]);
    }

    public static function get_daftar_jenis_tagihan()
    {
        return DB::select("SELECT * FROM keuangan.get_daftar_jenis_tagihan()");
    }

    public static function get_daftar_prodi()
    {
        return DB::select("SELECT * FROM keuangan.get_daftar_prodi()");
    }

    public static function get_daftar_periodisasi()
    {
        return DB::select("SELECT * FROM keuangan.get_daftar_periodisasi()");
    }

    public static function insup_tanggungan_prodi($prodi, $jenis_tagihan, $jumlah_tagihan, $semester_mulai, $semester_selesai, $tipe_periodisasi, $status_tanggungan, $id)
    {
        return DB::selectOne("SELECT * FROM keuangan.insup_tagihan_prodi(?,?,?,?,?,?,?,?)", [
            $prodi,
            $jenis_tagihan,
            $jumlah_tagihan,
            $semester_mulai,
            $semester_selesai,
            $tipe_periodisasi,
            $status_tanggungan,
            $id
        ]);
    }

    public static function delete_tanggungan_prodi($id)
    {
        return DB::selectOne("SELECT * FROM keuangan.hapus_tagihan_prodi(?)", [
            $id
        ]);
    }
}
