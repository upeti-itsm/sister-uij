<?php

namespace App\Models\Sistem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class KewenanganPeran extends Model
{
    use HasFactory;

    public static function get_daftar_kewenangan_peran($id_aplikasi = NULL, $search = "", $offset = 0, $limit = -1)
    {
        return DB::select('SELECT * FROM sistem.get_daftar_kewenangan_peran(:id_aplikasi, :search, :offset, :limit)', [
            'id_aplikasi' => $id_aplikasi, 'search' => $search,
            'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function get_daftar_kewenangan_peran_by_peran($id_peran, $search = "", $offset = 0, $limit = -1)
    {
        return DB::select('SELECT * FROM sistem.get_daftar_kewenangan_peran_by_peran(:id_peran, :search, :offset, :limit)', [
            'id_peran' => $id_peran, 'search' => $search,
            'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function add_kewenangan_peran($id_peran, $id_modul, $id_kewenangan_akses)
    {
        return DB::select('SELECT * FROM sistem.add_kewenangan_peran(:id_peran, :id_modul, :id_kewenangan_akses)', [
            'id_peran' => $id_peran, 'id_modul' => $id_modul, 'id_kewenangan_akses' => $id_kewenangan_akses
        ])[0];
    }

    public static function delete_kewenangan_peran($id_peran, $id_modul)
    {
        return DB::select('SELECT * FROM sistem.delete_kewenangan_peran(:id_peran, :id_modul)', [
            'id_peran' => $id_peran, 'id_modul' => $id_modul
        ])[0];
    }
}
