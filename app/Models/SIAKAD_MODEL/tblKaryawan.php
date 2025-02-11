<?php

namespace App\Models\SIAKAD_MODEL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class tblKaryawan extends Model
{
    use HasFactory;
    protected $connection = "siakad";

    public static function getDaftarKaryawan($search = "", $limit = -1, $offset = 0){
        if ($limit != -1) {
            return DB::connection('siakad')->select('SELECT t1.karyawan_id, t1.nik AS username, t1.password, t1.nip, t1.npwp, t1.bank_account, t1.masa_pensiun, t1.status_aktif, t1.tgl_masuk, t1.asal_sekolah, t1.nidn, t1.nama_lengkap, TRIM(t1.gelar_awal) AS gelar_awal, TRIM(t1.gelar_akhir) AS gelar_akhir, t1.no_ktp, t1.tempat_lahir, t1.tgl_lahir, t1.alamat_rumah, t1.kode_pos_rumah, t1.telepon_rumah, t1.hp_no, t1.agama, t1.jenis_kelamin, t1.status_menikah, t1.jenjang_pendidikan_akhir, t1.nama_ibu, t1.email, t1.status_karyawan, t2.nama_program AS nama_prodi, t2.program_id AS kd_prodi FROM tblKaryawan t1 JOIN tblProgramStudi t2 ON t1.program_id = t2.program_id WHERE (LOWER(t1.nama_lengkap) LIKE CONCAT("%", LOWER(:search), "%") OR LOWER(t1.nidn) LIKE CONCAT("%", LOWER(:nidn), "%")) LIMIT :limit OFFSET :offset', [
                'search' => $search,
                'nidn' => $search,
                'limit' => $limit,
                'offset' => $offset
            ]);
        } else {
            return DB::connection('siakad')->select('SELECT t1.karyawan_id, t1.nik AS username, t1.password, t1.nip, t1.npwp, t1.bank_account, t1.masa_pensiun, t1.status_aktif, t1.tgl_masuk, t1.asal_sekolah, t1.nidn, t1.nama_lengkap, TRIM(t1.gelar_awal) AS gelar_awal, TRIM(t1.gelar_akhir) AS gelar_akhir, t1.no_ktp, t1.tempat_lahir, t1.tgl_lahir, t1.alamat_rumah, t1.kode_pos_rumah, t1.telepon_rumah, t1.hp_no, t1.agama, t1.jenis_kelamin, t1.status_menikah, t1.jenjang_pendidikan_akhir, t1.nama_ibu, t1.email, t1.status_karyawan, t2.nama_program AS nama_prodi, t2.program_id AS kd_prodi FROM tblKaryawan t1 JOIN tblProgramStudi t2 ON t1.program_id = t2.program_id WHERE (LOWER(t1.nama_lengkap) LIKE CONCAT("%", LOWER(:search), "%") OR LOWER(t1.nidn) LIKE CONCAT("%", LOWER(:nidn), "%"))', [
                'search' => $search,
                'nidn' => $search
            ]);
        }
    }

    public static function getTotalRecordsDosen($search = ""){
        return DB::connection('siakad')->select('SELECT COUNT(t1.karyawan_id) AS jml_record FROM tblKaryawan t1 JOIN tblProgramStudi t2 ON t1.program_id = t2.program_id WHERE (LOWER(t1.nama_lengkap) LIKE CONCAT("%", LOWER(:search), "%") OR LOWER(t1.nidn) LIKE CONCAT("%", LOWER(:nidn), "%"))', [
            'search' => $search,
            'nidn' => $search
        ]);
    }

    public static function getDosenByNik($nik){
        return DB::connection('siakad')->select('SELECT t1.karyawan_id, t1.nik AS username, t1.password, t1.nip, t1.npwp, t1.bank_account, t1.masa_pensiun, t1.status_aktif, t1.tgl_masuk, t1.asal_sekolah, t1.nidn, t1.nama_lengkap, TRIM(t1.gelar_awal) AS gelar_awal, TRIM(t1.gelar_akhir) AS gelar_akhir, t1.no_ktp, t1.tempat_lahir, t1.tgl_lahir, t1.alamat_rumah, t1.kode_pos_rumah, t1.telepon_rumah, t1.hp_no, t1.agama, t1.jenis_kelamin, t1.status_menikah, t1.jenjang_pendidikan_akhir, t1.nama_ibu, t1.email, t1.status_karyawan, t2.nama_program AS nama_prodi, t2.program_id AS kd_prodi FROM tblKaryawan t1 JOIN tblProgramStudi t2 ON t1.program_id = t2.program_id WHERE t1.nik = :nik', [
            'nik' => $nik
        ])[0];
    }
}
