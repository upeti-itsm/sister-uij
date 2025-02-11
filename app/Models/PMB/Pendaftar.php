<?php

namespace App\Models\PMB;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pendaftar extends Model
{
    use HasFactory;

    public static function get_informasi_perolehan_pendaftar()
    {
        return DB::select('SELECT * FROM pmb.get_rekap_informasi_perolehan_mahasiswa()')[0];
    }

    public static function get_mahasiswa_baru($kd_prodi = 'x', $offset = -1, $limit = 10, $search = '', $tahun_seleksi = 'now'){
        if ($tahun_seleksi == 'now') $tahun_seleksi = Carbon::now()->year;
        return DB::select('SELECT * FROM pmb.get_mahasiswa_baru(:kd_prodi, :offset, :limit, :search, :tahun_seleksi)', [
            'kd_prodi' => $kd_prodi, 'offset' => $offset, 'limit' => $limit, 'search' => $search, 'tahun_seleksi' => $tahun_seleksi
        ]);
    }
}
