<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RiwayatPotonganArisan extends Model
{
    use HasFactory;
    public static function get_daftar_potongan_arisan($bulan = 0, $tahun = 0, $offset = -1, $limit = 10)
    {
        $tahun != 0 ?: $tahun = now()->year;
        return DB::select('SELECT * FROM organisasi.get_daftar_potongan_arisan(:bulan, :tahun, :offset, :limit)', [
            'bulan' => $bulan, 'tahun' => $tahun, 'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function insert_potongan_arisan($id_karyawan, $potongan, $awal, $akhir)
    {
        return DB::select('SELECT * FROM organisasi.insert_potongan_arisan(:id_karyawan, :potongan, :awal, :akhir)', [
            'id_karyawan' => $id_karyawan, 'potongan' => $potongan, 'awal' => $awal, 'akhir' => $akhir
        ])[0];
    }

    public static function get_detail_potongan_arisan($bulan = 0, $tahun = 0, $search = "", $offset = 0, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_detail_daftar_potongan_arisan(:bulan, :tahun, :search, :offset, :limit)', [
            'bulan' => $bulan, 'tahun' => $tahun, 'search' => $search, 'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function update_potongan_arisan($id, $nominal)
    {
        return DB::select('SELECT * FROM organisasi.update_potongan_arisan_karyawan(:id, :nominal)', [
            'id' => $id, 'nominal' => $nominal
        ])[0];
    }

    public static function delete_potongan_arisan($id)
    {
        return DB::select('SELECT * FROM organisasi.hapus_potongan_arisan(:id)', [
            'id' => $id
        ])[0];
    }
}
