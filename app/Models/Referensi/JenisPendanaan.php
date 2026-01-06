<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JenisPendanaan extends Model
{
    use HasFactory;

    public static function get_data()
    {
//        $data = [
//            (object)['id' => '01', 'kd_jenis_pendanaan' => '01', 'nama' => 'Biaya Sendiri', 'nama_pendanaan' => 'Biaya Sendiri'],
//            (object)['id' => '02', 'kd_jenis_pendanaan' => '02', 'nama' => 'Beasiswa DIKTI', 'nama_pendanaan' => 'Beasiswa DIKTI'],
//            (object)['id' => '03', 'kd_jenis_pendanaan' => '03', 'nama' => 'Beasiswa BIDIKMISI', 'nama_pendanaan' => 'Beasiswa BIDIKMISI'],
//            (object)['id' => '04', 'kd_jenis_pendanaan' => '04', 'nama' => 'Beasiswa PPA', 'nama_pendanaan' => 'Beasiswa PPA'],
//            (object)['id' => '05', 'kd_jenis_pendanaan' => '05', 'nama' => 'Beasiswa AFIRMASI', 'nama_pendanaan' => 'Beasiswa AFIRMASI'],
//            (object)['id' => '06', 'kd_jenis_pendanaan' => '06', 'nama' => 'Beasiswa Perusahaan/Swasta', 'nama_pendanaan' => 'Beasiswa Perusahaan/Swasta'],
//            (object)['id' => '07', 'kd_jenis_pendanaan' => '07', 'nama' => 'Beasiswa Pemerintah Daerah', 'nama_pendanaan' => 'Beasiswa Pemerintah Daerah'],
//            (object)['id' => '08', 'kd_jenis_pendanaan' => '08', 'nama' => 'Beasiswa Perguruan Tinggi', 'nama_pendanaan' => 'Beasiswa Perguruan Tinggi'],
//            (object)['id' => '09', 'kd_jenis_pendanaan' => '09', 'nama' => 'Ikatan Dinas', 'nama_pendanaan' => 'Ikatan Dinas'],
//            (object)['id' => '10', 'kd_jenis_pendanaan' => '10', 'nama' => 'Beasiswa Lainnya', 'nama_pendanaan' => 'Beasiswa Lainnya'],
//            (object)['id' => '11', 'kd_jenis_pendanaan' => '11', 'nama' => 'KIP Kuliah', 'nama_pendanaan' => 'KIP Kuliah'],
//        ];
        $data = DB::select('SELECT * FROM akademik.list_jenis_pendanaan()');
        return collect($data);
    }
}
