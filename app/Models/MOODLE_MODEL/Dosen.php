<?php

namespace App\Models\MOODLE_MODEL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dosen extends Model
{
    use HasFactory;

    protected $connection = "emane";

    public static function get_daftar_dosen($username = "", $search = "", $offset = 0, $limit = -1)
    {
        return DB::connection("emane")->select('SELECT * FROM get_dosen(:username, :search, :offset, :limit)', [
            'username' => $username,
            'search' => $search,
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    public static function sync_dosen($username, $password, $nidn, $nama_lengkap, $email, $kota_asal, $nama_prodi, $nomor_hp, $alamat_rumah, $status_dosen)
    {
        return DB::connection('emane')->select('SELECT * FROM public.sync_dosen(:username, :password, :nidn, :nama_lengkap, :email, :kota_asal, :nama_prodi, :nomor_hp, :alamat_rumah, :status_dosen)', [
            'username' => $username,
            'password' => $password,
            'nidn' => $nidn,
            'nama_lengkap' => $nama_lengkap,
            'email' => $email,
            'kota_asal' => $kota_asal,
            'nama_prodi' => $nama_prodi,
            'nomor_hp' => $nomor_hp,
            'alamat_rumah' => $alamat_rumah,
            'status_dosen' => $status_dosen
        ])[0];
    }

    public static function get_asynchron_user()
    {
        return DB::connection("emane")->select('SELECT * FROM get_asynchron_user(:jenis_user)', [
            'jenis_user' => 'TC'
        ])[0];
    }
}
