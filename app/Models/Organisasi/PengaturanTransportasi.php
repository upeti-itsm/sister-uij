<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PengaturanTransportasi extends Model
{
    public static function get_pengaturan()
    {
        return DB::select('SELECT * FROM organisasi.get_nominal_transport_s2()');
    }

    public static function edit($id_jabatan_fungsional = null, $nominal_tunjangan)
    {
        return DB::selectOne('SELECT * FROM organisasi.update_transport_s2(:id_jabatan_fungsional, :nominal_tunjangan)', [
            'id_jabatan_fungsional' => $id_jabatan_fungsional,
            'nominal_tunjangan' => $nominal_tunjangan
        ]);
    }
}
