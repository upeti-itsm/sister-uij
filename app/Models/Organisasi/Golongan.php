<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Golongan extends Model
{
    use HasFactory;

    public static function get_daftar_golongan($search = ''){
        return DB::select('SELECT * FROM organisasi.get_daftar_golongan(:search)', [
            'search' => $search
        ]);
    }

    public static function get_golongan($offset = 0, $limit = 10, $search = '', $status = null)
    {
        return DB::select(
            'SELECT * FROM organisasi.daftar_golongan(:offset, :limit, :search, :status)',
            [
                'offset' => (int) $offset,
                'limit' => (int) $limit,
                'search' => $search ?? '',
                'status' => $status,
            ]
        );
    }

    public static function insup_golongan($id_golongan, $golongan, $masa_kerja, $gaji_pokok)
    {
        return DB::selectOne(
            'SELECT * FROM organisasi.insup_golongan(:id_golongan, :golongan, :masa_kerja, :gaji_pokok::money)',
            [
                'id_golongan' => (int) $id_golongan,
                'golongan' => (string) $golongan,
                'masa_kerja' => (int) $masa_kerja,
                'gaji_pokok' => $gaji_pokok
            ]
        );
    }

    public static function set_status_golongan($id_golongan, $status)
    {
        return DB::selectOne(
            'SELECT * FROM organisasi.set_status_golongan(:id_golongan, (:status)::boolean)',
            [
                'id_golongan' => (int) $id_golongan,
                'status' => $status ? 'true' : 'false'
            ]
        );
    }

    public static function import_golongan($golongan, $masa_kerja, $gaji_pokok)
    {
        return DB::selectOne(
            'SELECT * FROM organisasi.import_golongan(:golongan, :masa_kerja, :gaji_pokok::money)',
            [
                'golongan' => (string) $golongan,
                'masa_kerja' => (int) $masa_kerja,
                'gaji_pokok' => $gaji_pokok
            ]
        );
    }
}
