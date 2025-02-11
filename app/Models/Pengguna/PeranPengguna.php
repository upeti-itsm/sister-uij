<?php

namespace App\Models\Pengguna;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PeranPengguna extends Model
{
    use HasFactory;
    public static function get_daftar_peran_pengguna($id_aplikasi = NULL, $search = "", $offset = 0, $limit = -1)
    {
        return DB::select('SELECT * FROM pengguna.get_daftar_peran_pengguna(:id_aplikasi, :search, :offset, :limit)', [
            'id_aplikasi' => $id_aplikasi, 'search' => $search,
            'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function get_daftar_peran_pengguna_by_personal_and_aplikasi($id_personal, $id_aplikasi, $search = "", $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM pengguna.get_daftar_peran_pengguna_by_personal_and_aplikasi(:id_personal, :id_aplikasi, :search, :offset, :limit)', [
            'id_personal' => $id_personal, 'id_aplikasi' => $id_aplikasi, 'search' => $search,
            'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function add_peran_pengguna($id_personal, $id_peran, $is_default)
    {
        return DB::select('SELECT * FROM pengguna.add_peran_pengguna(:id_personal, :id_peran, :is_default)', [
            'id_personal' => $id_personal, 'id_peran' => $id_peran, 'is_default' => $is_default
        ])[0];
    }

    public static function delete_peran_pengguna($id_personal, $id_peran)
    {
        return DB::select('SELECT * FROM pengguna.delete_peran_pengguna(:id_personal, :id_peran)', [
            'id_personal' => $id_personal, 'id_peran' => $id_peran
        ])[0];
    }
}
