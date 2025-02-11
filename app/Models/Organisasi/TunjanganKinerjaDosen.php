<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TunjanganKinerjaDosen extends Model
{
    use HasFactory;

    public static function get_tunjangan_kinerja($search = "", $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_kinerja_dosen(:search, :offset, :limit)', [
            'search' => $search, 'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function insup_tunjangan_kinerja($kd_kinerja, $keterangan, $nominal_tunjangan)
    {
        return DB::select('SELECT * FROM organisasi.insup_kinerja_dosen(:kd_kinerja, :keterangan, :nominal_tunjangan)', [
            'kd_kinerja' => $kd_kinerja, 'nominal_tunjangan' => $nominal_tunjangan, 'keterangan' => $keterangan
        ]);
    }
}
