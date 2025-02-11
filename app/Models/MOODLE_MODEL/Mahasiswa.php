<?php

namespace App\Models\MOODLE_MODEL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $connection = "emane";

    public static function get_daftar_mahasiswa($angkatan = "...", $search = "", $limit = 10, $offset = 0)
    {
        return DB::connection("emane")->select('SELECT * FROM get_mahasiswa(:angkatan, :search, :limit , :offset)', [
            'angkatan' => $angkatan,
            'search' => $search,
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    public static function get_daftar_suspended_mahasiswa($angkatan = "...", $search = "", $offset = 0, $limit = 10)
    {
        return DB::connection("emane")->select('SELECT * FROM get_mahasiswa(:angkatan, :search, :offset, :limit, :is_suspend)', [
            'angkatan' => $angkatan,
            'search' => $search,
            'limit' => $limit,
            'offset' => $offset,
            'is_suspend' => true
        ]);
    }

    public static function sync_mahasiswa($npk, $nama, $angkatan, $password, $kd_prodi, $alamat_rumah, $hp, $kota, $email, $nama_prodi, $jenis_pendanaan)
    {
        return DB::connection('emane')->select('SELECT * FROM public.sync_mahasiswa(:npk, :nama, :angkatan, :password, :kd_prodi, :alamat_rumah, :hp, :kota, :email, :nama_prodi, :jenis_pendanaan)', [
            'npk' => $npk,
            'nama' => $nama,
            'angkatan' => $angkatan,
            'password' => $password,
            'kd_prodi' => $kd_prodi,
            'alamat_rumah' => $alamat_rumah,
            'hp' => $hp,
            'kota' => $kota,
            'email' => $email,
            'nama_prodi' => $nama_prodi,
            'jenis_pendanaan' => $jenis_pendanaan,
        ])[0];
    }

    public static function get_asynchron_user()
    {
        return DB::connection("emane")->select('SELECT * FROM get_asynchron_user()')[0];
    }

    public static function suspend_mahasiswa($npk, $alasan){
        return DB::connection("emane")->select('SELECT * FROM suspend_user_by_npk(:npk, :alasan)', [
            'npk' => $npk, 'alasan' => $alasan
        ])[0];
    }

    public static function unsuspend_mahasiswa($user_type, $username){
        return DB::connection('emane')->select('SELECT * FROM public.activate_user(:user_type, :username)', [
            'user_type' => $user_type,
            'username' => $username
        ])[0];
    }
}
