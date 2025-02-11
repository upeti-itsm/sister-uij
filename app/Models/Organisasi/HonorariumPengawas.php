<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HonorariumPengawas extends Model
{
    use HasFactory;
    public static function get_daftar_honorarium_pengawas($id_personal = "00000000-0000-0000-0000-000000000000", $periode_pembayaran = NULL, $tahun = NULL, $search = "", $offset = -1, $limit = 10, $jenis_karyawan = NULL)
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_gaji_bulanan_pegawai(:id_personal, :periode_pembayaran, :tahun, :search, :offset, :limit, :jenis_karyawan)', [
            'id_personal' => $id_personal, 'periode_pembayaran' => $periode_pembayaran, 'tahun' => $tahun, 'search' => $search,
            'offset' => $offset, 'limit' => $limit, 'jenis_karyawan' => $jenis_karyawan
        ]);
    }

    public static function set_honorarium_pengawas($jenis_karyawan = "1,4,5,6,7")
    {
        return DB::select('SELECT * FROM organisasi.set_rekapitulasi_gaji_bulanan_pegawai(:jenis_karyawan)', [
            'jenis_karyawan' => $jenis_karyawan
        ])[0];
    }

    public static function get_detail_honorarium_pengawas($id_honorarium_mengajar)
    {
        return DB::select('SELECT * FROM organisasi.get_detail_gaji_bulanan_karyawan(:id_rekap)', [
            'id_rekap' => $id_honorarium_mengajar
        ])[0];
    }
}
