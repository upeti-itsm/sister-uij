<?php

namespace App\Models\Absensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RekapitulasiAbsensiKaryawan extends Model
{
    use HasFactory;

    public static function get_rekap_informasi()
    {
        return DB::select('SELECT * FROM absensi.get_rekap_informasi_absensi_by_pimpinan()')[0];
    }

    public static function get_detail_karyawan($offset = -1, $limit = 10, $search = '', $status_kehadiran = 1)
    {
        return DB::select('SELECT * FROM absensi.absensi_get_rekap_kehadiran_karyawan(:offset, :limit, :search, :status_kehadiran)', [
            'offset' => $offset, 'limit' => $limit, 'search' => $search, 'status_kehadiran' => $status_kehadiran
        ]);
    }
}
