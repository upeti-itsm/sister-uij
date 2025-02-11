<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HonorariumMengajar extends Model
{
    use HasFactory;

    public static function get_daftar_honorarium_mengajar($id_personal = "00000000-0000-0000-0000-000000000000", $periode_pembayaran = NULL, $tahun = NULL, $search = "", $offset = -1, $limit = 10, $jenis_karyawan = '1,2,3,6,7,4', $is_repair = NULL)
    {
        return DB::select('SELECT * FROM akademik.get_daftar_honorarium_dosen_mengajar(:id_personal, :periode_pembayaran, :tahun, :search, :offset, :limit, :jenis_karyawan, :is_repair)', [
            'id_personal' => $id_personal, 'periode_pembayaran' => $periode_pembayaran, 'tahun' => $tahun, 'search' => $search,
            'offset' => $offset, 'limit' => $limit, 'jenis_karyawan' => $jenis_karyawan, 'is_repair' => $is_repair
        ]);
    }

    public static function set_honorarium_mengajar($jenis_karyawan = "1,2,3,6,7")
    {
        return DB::select('SELECT * FROM akademik.set_honorarium_dosen_mengajar(:jenis_karyawan)', [
            'jenis_karyawan' => $jenis_karyawan
        ])[0];
    }

    public static function get_detail_honorarium_mengajar($id_honorarium_mengajar)
    {
        return DB::select('SELECT * FROM akademik.get_detail_honorarium_dosen_mengajar(:id_rekap)', [
            'id_rekap' => $id_honorarium_mengajar
        ])[0];
    }

    public static function get_informasi_periode_honor_mengajar_terakhir(){
        return DB::select('SELECT * FROM akademik.get_informasi_periode_honor_mengajar_terakhir()');
    }

    public static function set_pengajuan_perbaikan_honor_mengajar($id_rekap, $status_perbaikan, $keterangan){
        return DB::select('SELECT * FROM akademik.set_pengajuan_perbaikan_honorarium_dosen_mengajar(:id_rekap, :status_perbaikan, :keterangan)', [
            'id_rekap' => $id_rekap, 'status_perbaikan' => $status_perbaikan, 'keterangan' => $keterangan
        ])[0];
    }

    public static function generate_ulang_honor_mengajar($id_karyawan){
        return DB::select('SELECT * FROM akademik.regenerate_honorarium_dosen_mengajar(:id_karyawan)', [
            'id_karyawan' => $id_karyawan
        ])[0];
    }

    public static function export_honorarium_for_rekap($periode, $tahun)
    {
        return DB::select('SELECT * FROM akademik.export_rekapitulasi_honorarium_mengajar_dosen(:periode, :tahun)', [
            'periode' => $periode, 'tahun' => $tahun
        ]);
    }
}
