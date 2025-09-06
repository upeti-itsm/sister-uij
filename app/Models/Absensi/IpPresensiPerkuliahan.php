<?php

namespace App\Models\Absensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IpPresensiPerkuliahan extends Model
{
    use HasFactory;

    public static function get_data($offset = 0, $limit = -1, $search = "", $status)
    {
        return DB::select('SELECT * FROM absensi.daftar_ip_address_presensi_perkuliahan(:offset, :limit, :search, :status)', [
            'offset' => $offset,
            'limit' => $limit,
            'search' => $search,
            'status' => $status
        ]);
    }

    public static function insup($id, $alamat_ip, $sts_aktif)
    {
        return DB::selectOne('SELECT * FROM absensi.insup_ip_address_presensi_perkuliahan(:id, :alamat_ip, :sts_aktif)', [
            'id' => $id,
            'alamat_ip' => $alamat_ip,
            'sts_aktif' => $sts_aktif
        ]);
    }

    public static function hapus($id)
    {
        return DB::selectOne('SELECT * FROM absensi.del_ip_address_presensi_perkuliahan(:id)', [
            'id' => $id
        ]);
    }
}
