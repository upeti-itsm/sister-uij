<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JabatanFungsional extends Model
{
    use HasFactory;

    public static function get_daftar_jabatan_fungsional($search = '', $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_jabatan_fungsional(:search, :offset, :limit)', [
            'search' => $search, 'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function insup_tunjangan_fungsional($jabatan, $nominal_tunjangan, $id_jabatan_fungsional = 0)
    {
        return DB::selectOne('SELECT * FROM organisasi.insup_jabatan_fungsional(:id_jafung, :jabatan, :nominal_tunjangan)', [
            'jabatan' => $jabatan, 'nominal_tunjangan' => $nominal_tunjangan, 'id_jafung' => $id_jabatan_fungsional
        ]);
    }

    public static function set_active_tunjangan_fungsional($id_jabatan_fungsional, $status)
    {
        return DB::selectOne('SELECT * FROM organisasi.set_status_jabatan_fungsional(:id_jafung, :status)', [
            'id_jafung' => $id_jabatan_fungsional, 'status' => $status
        ]);
    }
}
