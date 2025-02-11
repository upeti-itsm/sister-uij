<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HonorariumKoreksi extends Model
{
    use HasFactory;
    public static function get_daftar_honorarium_koreksi($id_personal = "00000000-0000-0000-0000-000000000000", $periode_pembayaran = NULL, $tahun = NULL, $search = "", $offset = -1, $limit = 10, $jenis_karyawan = NULL)
    {
        return DB::select('SELECT * FROM akademik.get_daftar_honorarium_koreksi(:id_personal, :periode_pembayaran, :tahun, :search, :offset, :limit, :jenis_karyawan)', [
            'id_personal' => $id_personal, 'periode_pembayaran' => $periode_pembayaran, 'tahun' => $tahun, 'search' => $search,
            'offset' => $offset, 'limit' => $limit, 'jenis_karyawan' => $jenis_karyawan
        ]);
    }

    public static function set_honorarium_koreksi($jenis_karyawan = "1,2,3,6,7")
    {
        return DB::select('SELECT * FROM akademik.set_honorarium_koreksi(:jenis_karyawan)', [
            'jenis_karyawan' => $jenis_karyawan
        ])[0];
    }

    public static function get_detail_honorarium_koreksi($id_honorarium_mengajar)
    {
        return DB::select('SELECT * FROM akademik.get_detail_honorarium_koreksi(:id_rekap)', [
            'id_rekap' => $id_honorarium_mengajar
        ])[0];
    }

    public static function get_informasi_periode_honor_koreksi_terakhir(){
        return DB::select('SELECT * FROM akademik.get_informasi_periode_honor_koreksi_terakhir()');
    }

    public static function set_pengajuan_perbaikan_honor_koreksi($id_rekap, $status_perbaikan, $keterangan){
        return DB::select('SELECT * FROM akademik.set_pengajuan_perbaikan_honorarium_koreksi(:id_rekap, :status_perbaikan, :keterangan)', [
            'id_rekap' => $id_rekap, 'status_perbaikan' => $status_perbaikan, 'keterangan' => $keterangan
        ])[0];
    }

    public static function generate_ulang_honor_koreksi($id_karyawan){
        return DB::select('SELECT * FROM akademik.regenerate_honorarium_koreksi(:id_karyawan)', [
            'id_karyawan' => $id_karyawan
        ])[0];
    }

    public static function export_honorarium_for_rekap($tahun_akademik)
    {
        return DB::select('SELECT * FROM akademik.export_rekapitulasi_honorarium_koreksi(:tahun_akademik)', [
            'tahun_akademik' => $tahun_akademik
        ]);
    }
}
