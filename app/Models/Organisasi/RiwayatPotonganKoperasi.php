<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RiwayatPotonganKoperasi extends Model
{
    use HasFactory;

    public static function get_daftar_potongan_koperasi($bulan = 0, $tahun = 0, $offset = -1, $limit = 10)
    {
        $tahun != 0 ?: $tahun = now()->year;
        return DB::select('SELECT * FROM organisasi.get_daftar_potongan_koperasi(:bulan, :tahun, :offset, :limit)', [
            'bulan' => $bulan, 'tahun' => $tahun, 'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function insert_potongan_koperasi($id_karyawan, $potongan, $periode, $tahun)
    {
        return DB::select('SELECT * FROM organisasi.insert_potongan_koperasi(:id_karyawan, :potongan, :periode, :tahun)', [
            'id_karyawan' => $id_karyawan, 'potongan' => $potongan, 'periode' => $periode, 'tahun' => $tahun
        ])[0];
    }

    public static function get_detail_potongan_koperasi($bulan = 0, $tahun = 0, $search = "", $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_detail_daftar_potongan_koperasi(:bulan, :tahun, :search, :offset, :limit)', [
            'bulan' => $bulan, 'tahun' => $tahun, 'search' => $search, 'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function update_potongan_koperasi($id, $nominal)
    {
        return DB::select('SELECT * FROM organisasi.update_potongan_koperasi_karyawan(:id, :nominal)', [
            'id' => $id, 'nominal' => $nominal
        ])[0];
    }

    public static function delete_potongan_koperasi($id)
    {
        return DB::select('SELECT * FROM organisasi.hapus_potongan_koperasi(:id)', [
            'id' => $id
        ])[0];
    }
}
