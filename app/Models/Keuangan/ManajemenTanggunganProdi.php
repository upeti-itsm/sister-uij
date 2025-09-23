<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TanggunganMahasiswa extends Model
{
    use HasFactory;

    public static function get_daftar_tanggungan($nim, $search = "", $limit = 10, $offset = -1, $is_lunas = false)
    {
        return DB::select("SELECT * FROM keuangan.get_tagihan_mahasiswa(?,?,?,?,?)", [
            $nim,
            $search,
            $limit,
            $offset,
            $is_lunas
        ]);
    }

    public static function get_riwayat_pemabayaran($nim, $search = "", $limit = 10, $offset = -1)
    {
        return DB::select("SELECT * FROM keuangan.get_riwayat_pembayaran_mahasiswa(?,?,?,?)", [
            $nim,
            $search,
            $limit,
            $offset
        ]);
    }
}
