<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PengaturanHr extends Model
{
    public static function get_pengaturan($jenis_jenjang = "")
    {
        return DB::select('SELECT * FROM organisasi.get_honor_ngajar_fungsional_s1_s2(:jenis_jenjang)', [
            'jenis_jenjang' => $jenis_jenjang
        ]);
    }

    public static function edit(?int $id_jabatan_fungsional, $nominal_tunjangan, bool $is_s2)
    {
        return DB::selectOne(
            'SELECT * FROM organisasi.update_honor_ngajar_s1_s2(:id_jabatan_fungsional, :nominal_tunjangan, :is_s2)',
            [
                'id_jabatan_fungsional' => $id_jabatan_fungsional,
                'nominal_tunjangan' => $nominal_tunjangan,
                'is_s2' => $is_s2,
            ]
        );
    }
}
