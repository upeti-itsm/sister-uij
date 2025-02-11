<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kerjasama extends Model
{
    use HasFactory;

    public static function get_level_institusi()
    {
        return DB::select('SELECT * FROM organisasi.level_institusi');
    }

    public static function get_daftar_kerjasama($level, $search = '', $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_kerjasama(?,?,?,?)', [
            $level, $search, $offset, $limit
        ]);
    }

    public static function delete_kerjasama($id)
    {
        return DB::select('SELECT * FROM organisasi.del_kerjasama(?)', [
            $id
        ])[0];
    }

    public static function insup($lembaga_mitra, $tingkat_kerjasama, $bentuk_kegiatan, $tgl_kegiatan, $masa_berlaku, $tingkatan_level, $link_dokumen, $bukti_kerjasama, $id = 0)
    {
        return DB::select('SELECT * FROM organisasi.insup_kerjasama(?,?,?,?,?,?,?,?,?)', [
            $id, $lembaga_mitra, $tingkat_kerjasama, $bentuk_kegiatan, $tgl_kegiatan, $masa_berlaku, $tingkatan_level, $link_dokumen, $bukti_kerjasama
        ])[0];
    }
}
