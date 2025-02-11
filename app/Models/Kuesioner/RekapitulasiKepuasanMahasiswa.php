<?php

namespace App\Models\Kuesioner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RekapitulasiKepuasanMahasiswa extends Model
{
    use HasFactory;

    public static function add_response_kepuasan($id_mahasiswa, $id_sub_unsur, $id_nilai, $id_jenis, $id_semester = 0)
    {
        return DB::select('SELECT * FROM kuesioner.insert_response_kuesioner_kepuasan_mahasiswa(:id_mhs, :id_sub_unsur, :id_nilai, :id_jenis, :id_semester)', [
            'id_mhs' => $id_mahasiswa, 'id_sub_unsur' => $id_sub_unsur, 'id_nilai' => $id_nilai, 'id_jenis' => $id_jenis, 'id_semester' => $id_semester
        ])[0];
    }

    public static function cek_status_pengisian($id_mhs, $id_jenis, $id_semester = 0)
    {
        return DB::select('SELECT * FROM kuesioner.cek_mahasiswa_mengisi_kuesioner_kepuasan_mahasiswa(:id_mhs, :id_jenis, :id_semester)', [
            'id_mhs' => $id_mhs, 'id_semester' => $id_semester, 'id_jenis' => $id_jenis
        ])[0];
    }

    public static function get_response_by_id_unsur($id_unsur, $id_semester = 0)
    {
        return DB::select('SELECT * FROM kuesioner.get_respon_by_id_unsur_kepuasan_mahasiswa(:id_unsur, :id_semester)', [
            'id_unsur' => $id_unsur, 'id_semester' => $id_semester
        ]);
    }

    public static function export_hasil_kuesioner($id_jenis = 1, $id_semester = 0, $id_mahasiswa = 0)
    {
        return DB::select('SELECT * FROM kuesioner.get_list_hasil_kuesioner_kepuasan_mahasiswa(:id_mahasiswa, :id_jenis, :id_semester)', [
            'id_mahasiswa' => $id_mahasiswa, 'id_jenis' => $id_jenis, 'id_semester' => $id_semester
        ]);
    }

    public static function get_tahun_akademik(){
        return DB::select('SELECT * FROM kuesioner.get_list_semester_on_kepuasan_mahasiswa()');
    }
}
