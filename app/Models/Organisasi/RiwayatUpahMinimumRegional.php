<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RiwayatUpahMinimumRegional extends Model
{
    use HasFactory;
    public static function get_daftar_umr($bulan, $tahun, $search = "", $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_umr(:bulan, :tahun, :search, :offset, :limit)', [
            'bulan' => $bulan, 'tahun' => $tahun, 'search' => $search, 'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function insert_umr($nilai_umr)
    {
        return DB::select('SELECT * FROM organisasi.insert_upah_minimum_regional(:nilai_umr)', [
            'nilai_umr' => $nilai_umr
        ])[0];
    }

    public static function get_detail_umr($id_umr, $search = "", $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_detail_umr(:id_umr, :search, :offset, :limit)', [
            'id_umr' => $id_umr, 'search' => $search, 'offset' => $offset, 'limit' => $limit
        ]);
    }
}
