<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RekapitulasiGajiBulanan extends Model
{
    use HasFactory;

    public static function get_daftar_gaji_bulanan($id_personal = "00000000-0000-0000-0000-000000000000", $periode_pembayaran = NULL, $tahun = NULL, $search = "", $offset = -1, $limit = 10, $jenis_karyawan = NULL, $is_repair = NULL)
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_gaji_bulanan_pegawai(:id_personal, :periode_pembayaran, :tahun, :search, :offset, :limit, :jenis_karyawan, :is_repair)', [
            'id_personal' => $id_personal, 'periode_pembayaran' => $periode_pembayaran, 'tahun' => $tahun, 'search' => $search,
            'offset' => $offset, 'limit' => $limit, 'jenis_karyawan' => $jenis_karyawan, 'is_repair' => $is_repair
        ]);
    }

    public static function set_rekapitulasi_gaji_bulanan($jenis_karyawan = "1,4,5,6,7")
    {
        return DB::select('SELECT * FROM organisasi.set_rekapitulasi_gaji_bulanan_pegawai(:jenis_karyawan)', [
            'jenis_karyawan' => $jenis_karyawan
        ])[0];
    }

    public static function get_detail_gaji_bulanan_karyawan($id_rekapitulasi_gaji_bulanan)
    {
        return DB::select('SELECT * FROM organisasi.get_detail_gaji_bulanan_karyawan(:id_rekap)', [
            'id_rekap' => $id_rekapitulasi_gaji_bulanan
        ])[0];
    }

    public static function export_gaji_bulanan_for_bank($periode, $tahun, $search = '', $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.export_gaji_karyawan(:periode, :tahun, :search, :offset, :limit)', [
            'periode' => $periode, 'tahun' => $tahun, 'search' => $search, 'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function export_gaji_bulanan_for_rekap($periode, $tahun)
    {
        return DB::select('SELECT * FROM organisasi.export_rekapitulasi_gaji_bulanan_karyawan_on_keuangan(:periode, :tahun)', [
            'periode' => $periode, 'tahun' => $tahun
        ]);
    }

    public static function set_pengajuan_perbaikan_gaji_karyawan($id_rekap, $status_perbaikan, $keterangan){
        return DB::select('SELECT * FROM organisasi.set_pengajuan_perbaikan_gaji_karyawan(:id_rekap, :status_perbaikan, :keterangan)', [
            'id_rekap' => $id_rekap, 'status_perbaikan' => $status_perbaikan, 'keterangan' => $keterangan
        ])[0];
    }

    public static function generate_ulang_gaji($id_karyawan){
        return DB::select('SELECT * FROM organisasi.regenerate_rekapitulasi_gaji_bulanan_pegawai(:id_karyawan)', [
            'id_karyawan' => $id_karyawan
        ])[0];
    }

    public static function get_last_gaji(){
        return DB::select('SELECT * FROM organisasi.get_informasi_periode_gaji_terakhir()');
    }
}
