<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Semester extends Model
{
    use HasFactory;

    public static function get_semester($offset = -1, $limit = 10, $id_semester = 0)
    {
        return DB::select('SELECT * FROM akademik.get_tahun_akademik(:offset, :limit, :id_semester)', [
            'limit' => $limit, 'offset' => $offset, 'id_semester' => $id_semester
        ]);
    }

    public static function sync_semester_with_siakad($id_semester, $nama_semester, $is_periode_aktif, $tgl_awal_perkuliahan, $tgl_akhir_perkuliahan, $tahun_akademik, $tgl_mulai_krs, $tgl_akhir_krs, $tgl_mulai_input_nilai, $tgl_akhir_input_nilai)
    {
        return DB::select('SELECT * FROM akademik.sync_tahun_akademik_with_siakad(:id_semester, :nama_semester, :is_periode_aktif, :tgl_awal_perkuliahan, :tgl_akhir_perkuliahan, :tahun_akademik, :tgl_mulai_krs, :tgl_akhir_krs, :tgl_mulai_input_nilai, :tgl_akhir_input_nilai)', [
            'id_semester' => $id_semester, 'nama_semester' => $nama_semester, 'is_periode_aktif' => $is_periode_aktif, 'tgl_awal_perkuliahan' => $tgl_awal_perkuliahan,
            'tgl_akhir_perkuliahan' => $tgl_akhir_perkuliahan, 'tahun_akademik' => $tahun_akademik, 'tgl_mulai_krs' => $tgl_mulai_krs, 'tgl_akhir_krs' => $tgl_akhir_krs, 'tgl_mulai_input_nilai' => $tgl_mulai_input_nilai,
            'tgl_akhir_input_nilai' => $tgl_akhir_input_nilai
        ])[0];
    }
}
