<?php

namespace App\Models\Sekretaris;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PengelolaanSurat extends Model
{
    use HasFactory;

    public static function get_all_status_pengajuan()
    {
        return DB::select('SELECT * FROM organisasi.get_status_surat_rektorat()');
    }

    public static function get_all_jenis_surat()
    {
        return DB::select('SELECT * FROM organisasi.get_jenis_surat_rektorat()');
    }

    public static function get_all_pimpinan_rektorat()
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_pimpinan_rektorat()');
    }
}
