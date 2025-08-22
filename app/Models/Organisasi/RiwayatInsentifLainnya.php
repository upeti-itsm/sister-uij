<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RiwayatInsentifLainnya extends Model
{
    use HasFactory;
    public static function get_daftar_insentif_lainnya($bulan = 0, $tahun = 0, $offset = -1, $limit = 10)
    {
        $tahun != 0 ?: $tahun = now()->year;
        return DB::select('SELECT * FROM organisasi.get_daftar_insentif_lainnya(:bulan, :tahun, :offset, :limit)', [
            'bulan' => $bulan, 'tahun' => $tahun, 'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function insert_insentif_lainnya($id_karyawan, $insentif, $periode, $tahun, $keterangan)
    {
        return DB::select('SELECT * FROM organisasi.insert_insentif_lainnya(?, ?, ?, ?, ?)', [
            $id_karyawan, $insentif, $periode, $tahun, $keterangan
        ])[0];
    }

    public static function get_detail_insentif_lainnya($bulan = 0, $tahun = 0, $search = "", $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_detail_daftar_insentif_lainnya(:bulan, :tahun, :search, :offset, :limit)', [
            'bulan' => $bulan, 'tahun' => $tahun, 'search' => $search, 'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function update_insentif_lainnya($id, $nominal, $keterangan)
    {
        return DB::select('SELECT * FROM organisasi.update_insentif_lainnya_karyawan(:id, :nominal, :keterangan)', [
            'id' => $id, 'nominal' => $nominal, 'keterangan' => $keterangan
        ])[0];
    }

    public static function delete_insentif_lainnya($id)
    {
        return DB::select('SELECT * FROM organisasi.hapus_insentif_lainnya(:id)', [
            'id' => $id
        ])[0];
    }
}
