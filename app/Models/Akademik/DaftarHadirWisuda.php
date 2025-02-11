<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DaftarHadirWisuda extends Model
{
    use HasFactory;

    public static function get_daftar_hadir($tahun_akademik = 0, $search = 0, $offset = 0, $limit = -1)
    {
        return DB::select('SELECT * FROM akademik.get_daftar_peserta_wisuda(:tahun_akademik, :search, :offset, :limit)', [
            'search' => $search, 'limit' => $limit, 'offset' => $offset, 'tahun_akademik' => $tahun_akademik
        ]);
    }

    public static function insert_tamu($nim, $nomor, $kode, $jenis)
    {
        return DB::select('SELECT * FROM akademik.insert_kehadiran_wisuda(:nim, :nomor_undangan, :kode, :jenis)', [
            'nim' => $nim, 'nomor_undangan' => $nomor, 'kode' => $kode, 'jenis' => $jenis
        ])[0];
    }

    public static function detail_tamu($id)
    {
        return DB::select('SELECT * FROM akademik.insert_tamu_wisuda(:id)', [
            'id' => $id
        ]);
    }

    public static function get_tamu($offset, $limit){
        return DB::select('SELECT * FROM akademik.get_tamu_orang_tua(:offset, :limit)', [
            'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function get_tamu_vvip($offset, $limit){
        return DB::select('SELECT * FROM akademik.get_tamu_wisuda(:offset, :limit)', [
            'offset' => $offset, 'limit' => $limit
        ]);
    }
}
