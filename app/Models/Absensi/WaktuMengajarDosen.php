<?php

namespace App\Models\Absensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WaktuMengajarDosen extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_waktu_mengajar';
    protected $table = 'absensi.waktu_mengajar';
    public $timestamps = false;

    public static function getWaktuMengajar($jenis_kelas = 'REG-P'){
        return DB::select('SELECT * FROM absensi.get_jadwal_mengajar_dosen(:jenis_kelas)', [
            'jenis_kelas' => $jenis_kelas
        ]);
    }
}
