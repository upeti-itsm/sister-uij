<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JabatanStruktural extends Model
{
    use HasFactory;

    public static function get_daftar_jabatan_struktural($search = '', $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_jabatan_struktural(:search, :offset, :limit)', [
            'search' => $search, 'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function insup_jabatan_struktural($jabatan_struktural, $nominal_tunjangan, $sks_wajib, $id_jabatan_struktural = null){
        return DB::selectOne('SELECT * FROM organisasi.insup_jabatan_struktural(:jabatan_struktural, :nominal_tunjangan, :id_jabatan, :sks)', [
            'jabatan_struktural' => $jabatan_struktural, 'nominal_tunjangan' => $nominal_tunjangan, 'id_jabatan' => $id_jabatan_struktural, 'sks' => $sks_wajib
        ]);
    }
}
