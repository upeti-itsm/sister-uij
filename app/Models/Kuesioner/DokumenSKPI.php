<?php

namespace App\Models\Kuesioner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DokumenSKPI extends Model
{
    use HasFactory;

    public static function cek_status_skpi($id_mhs, $id_semester = 0)
    {
        return DB::select('SELECT * FROM kuesioner.cek_mahasiswa_upload_skpi(:id_mhs, :id_semester)', [
            'id_mhs' => $id_mhs, 'id_semester' => $id_semester
        ])[0];
    }

    public static function insup_dokumen_skpi($id_mhs, $path_kartu_prestasi, $path_dokumen_pendukung, $id_semester = 0){
        return DB::select('SELECT * FROM kuesioner.insup_dokumen_skpi_kepuasan_mahasiswa(:id_mhs, :id_semester, :path_dok_pendukung, :path_kartu)', [
            'id_mhs' => $id_mhs, 'id_semester' => $id_semester, 'path_dok_pendukung' => $path_dokumen_pendukung, 'path_kartu' => $path_kartu_prestasi
        ])[0];
    }

    public static function get_list_dokumen_skpi($id_mhs, $id_semester = 0, $offset = -1, $limit = 10){
        return DB::select('SELECT * FROM kuesioner.get_list_dokumen_skpi_kepuasan_mahasiswa(:id_mhs, :id_semester, :offset, :limit)', [
            'id_mhs' => $id_mhs, 'id_semester' => $id_semester, 'offset' => $offset, 'limit' => $limit
        ]);
    }
}
