<?php

namespace App\Models\Pengguna;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AkunMahasiswa extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_akun';
    protected $table = 'pengguna.akun_mahasiswa';
    protected $fillable =
        ['tgl_created', 'tgl_updated', 'is_data_aktif', 'id_registrasi_mahasiswa', 'username', 'password'];
    public $timestamps = false;
    protected $casts = [
        'id_akun' => 'string'
    ];
    protected $keyType = 'string';

    public static function setAuth($request, $ip)
    {
        return DB::select('select * from pengguna.set_autentikasi_v2(:username, :password, :ip, :id_app)', ['username' => $request->username, 'password' => $request->password, 'ip' => $ip, 'id_app' => '7d7c0b9a-6c3b-4b75-ab50-9b74877b860a']);
    }

    public static function cekSertifikat($sertifikat, $id_mhs)
    {
        return DB::select('select * from pengguna.check_sertifikat_mahasiswa(:id_sertifikat, :id_mhs, :id_app)', ['id_sertifikat' => $sertifikat, 'id_mhs' => $id_mhs, 'id_app' => '7d7c0b9a-6c3b-4b75-ab50-9b74877b860a']);
    }

    public static function getListPeranByMahasiswa($id_mhs)
    {
        return DB::select('select * from pengguna.list_peran_by_mahasiswa(:id_mhs, :id_app)', ['id_mhs' => $id_mhs, 'id_app' => '7d7c0b9a-6c3b-4b75-ab50-9b74877b860a']);
    }

    public static function updatePassword($old_pass, $new_pass)
    {
        return DB::select('select * from pengguna.update_account_mahasiswa(:id_akun, :old_pass, :new_pass)', [
            'id_akun' => Session::get('user')->id_akun,
            'old_pass' => $old_pass,
            'new_pass' => $new_pass
        ]);
    }
}
