<?php

namespace App\Models\SIAKAD_MODEL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class keu_tblTransaksiMahasiswa extends Model
{
    use HasFactory;
    protected $connection = "siakad";

    public static function get_transaksi_mahasiswa($nim){
        return DB::connection('siakad')->select("SELECT t1.kode_biaya, t2.nama_biaya, FORMAT(t1.jumlah_bayar, 0, 'de_DE') AS jumlah_bayar, t1.semester, DATE_FORMAT(tgl_bayar, '%d %b %Y') AS tgl_bayar, t1.id_kwitansi, t1.jenis_bayar, t1.keterangan, FORMAT(t1.denda, 0, 'de_DE') AS denda FROM keu_tblTransaksiMahasiswa t1 JOIN keu_tblDaftarBiaya t2 ON t1.kode_biaya = t2.kode_biaya WHERE NPK = :nim", [
            'nim' => $nim
        ]);
    }
}
