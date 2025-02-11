<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RiwayatPotonganBpjs extends Model
{
    use HasFactory;

    public static function get_daftar_potongan_bpjs($bulan = 0, $tahun = 0, $offset = -1, $limit = 10)
    {
        $tahun != 0 ?: $tahun = now()->year;
        return DB::select('SELECT * FROM organisasi.get_daftar_potongan_bpjs(:bulan, :tahun, :offset, :limit)', [
            'bulan' => $bulan, 'tahun' => $tahun, 'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function insert_potongan_bpjs($id_karyawan, $potongan_kesehatan, $potongan_ketenagakerjaan, $tunjangan, $periode, $tahun)
    {
        return DB::select('SELECT * FROM organisasi.set_bpjs_pegawai(?,?,?,?,?,?)', [
            $id_karyawan, $potongan_kesehatan, $potongan_ketenagakerjaan, $tunjangan, $periode, $tahun
        ])[0];
    }

    public static function get_detail_potongan_bpjs($bulan = 0, $tahun = 0, $search = "", $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_detail_daftar_potongan_bpjs(:bulan, :tahun, :search, :offset, :limit)', [
            'bulan' => $bulan, 'tahun' => $tahun, 'search' => $search, 'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function update_potongan_bpjs($id, $nominal, $kerja, $tunjangan)
    {
        return DB::selectOne('SELECT * FROM organisasi.update_potongan_bpjs_pegawai(?,?,?,?)', [
            $id, $nominal, $kerja, $tunjangan
        ]);
    }

    public static function delete_potongan_bpjs($id)
    {
        return DB::selectOne('SELECT * FROM organisasi.set_non_aktif_riwayat_bpjs(:id)', [
            'id' => $id
        ]);
    }
}
