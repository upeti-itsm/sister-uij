<?php

namespace App\Models\Pengguna;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AkunPengguna extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_akun';
    protected $table = 'pengguna.akun_pengguna';
    protected $fillable =
        ['tgl_created', 'tgl_updated', 'is_data_aktif', 'id_personal', 'username', 'password', 'id_aplikasi'];
    public $timestamps = false;
    protected $casts = [
        'id_akun' => 'string'
    ];
    protected $keyType = 'string';

    public static function setAuth($request, $ip)
    {
        return DB::select('select * from pengguna.set_autentikasi_v2(:username, :password, :ip, :id_app)', ['username' => $request->username, 'password' => $request->password, 'ip' => $ip, 'id_app' => '7d7c0b9a-6c3b-4b75-ab50-9b74877b860a']);
    }

    public static function cekSertifikat($sertifikat, $id_personal)
    {
        return DB::select('select * from pengguna.check_sertifikat(:id_sertifikat, :id_personal, :id_app)', ['id_sertifikat' => $sertifikat, 'id_personal' => $id_personal, 'id_app' => '7d7c0b9a-6c3b-4b75-ab50-9b74877b860a']);
    }

    public static function getListPeranByPersonal($id_personal)
    {
        return DB::select('select * from pengguna.list_peran_by_personal(:id_personal, :id_app)', ['id_personal' => $id_personal, 'id_app' => '7d7c0b9a-6c3b-4b75-ab50-9b74877b860a']);
    }

    public static function updatePassword($old_pass, $new_pass, $username)
    {
        return DB::select('select * from pengguna.update_account(:id_akun, :old_pass, :new_pass, :username, :id_app)', [
            'id_akun' => Session::get('user')->id_akun,
            'old_pass' => $old_pass,
            'new_pass' => $new_pass,
            'username' => $username,
            'id_app' => '7d7c0b9a-6c3b-4b75-ab50-9b74877b860a',
        ]);
    }

    public static function get_user_account($email){
        return DB::select('SELECT * FROM akademik.get_akun_pengguna_by_personal(:email)', [
            'email' => $email
        ])[0];
    }
}
