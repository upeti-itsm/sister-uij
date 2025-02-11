<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AnakKaryawan extends Model
{
    use HasFactory;

    public static function get_daftar_anak($id_personal, $offset = -1, $limit = 10, $search = '')
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_anak_by_id_personal(:id_personal, :offset, :limit, :search)', [
            'id_personal' => $id_personal, 'offset' => $offset, 'limit' => $limit, 'search' => $search
        ]);
    }

    public static function insup_anak_karyawan($id_personal, $nama, $kd_jenis_kelamin, $tgl_lahir, $nik, $tempat_lahir, $id_anak = 0){
        return DB::select('SELECT * FROM organisasi.insup_anak_karyawan(:id_anak, :id_personal, :nama, :kd_jenis_kelamin, :tgl_lahir, :nik, :tempat_lahir)', [
            'id_anak' => $id_anak, 'id_personal' => $id_personal, 'nama' => $nama, 'kd_jenis_kelamin' => $kd_jenis_kelamin, 'tgl_lahir' => $tgl_lahir, 'nik' => $nik, 'tempat_lahir' => $tempat_lahir
        ])[0];
    }

    public static function get_detail_anak($id_anak){
        return DB::select('SELECT * FROM organisasi.get_detail_anak_karyawan(:id_anak)', [
            'id_anak' => $id_anak
        ])[0];
    }

    public static function delete_anak_karyawan($id_anak){
        return DB::select('SELECT * FROM organisasi.hapus_anak_karyawan(:id_anak)', [
            'id_anak' => $id_anak
        ])[0];
    }
}
