<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TataPamong extends Model
{
    use HasFactory;

    public static function insup_tata_pamong($tahun_terbit, $no_dokumen, $nama_dokumen, $deskripsi, $link_dokumen, $personal, $id = 0)
    {
        return DB::select('SELECT * FROM organisasi.insup_tatapamong(?,?,?,?,?,?,?)', [
            $personal, $id, $tahun_terbit, $no_dokumen, $nama_dokumen, $deskripsi, $link_dokumen
        ])[0];
    }

    public static function get_tahun_terbit()
    {
        return DB::select('SELECT * FROM organisasi.get_tahun_tatapamong()');
    }

    public static function daftar_tata_pamong($tahun_terbit, $search = "", $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_tata_pamong(?,?,?,?)', [
            $tahun_terbit, $search, $offset, $limit
        ]);
    }
}
