<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class KelasPerkuliahan extends Model
{
    use HasFactory;

    public static function get_daftar($kd_prodi = 'all', $tahun_akademik = 'all', $search = '', $offset = 0, $limit = -1)
    {
        return DB::select("SELECT * FROM akademik.get_daftar_kelas(?,?,?,?,?)", [
            $kd_prodi, $tahun_akademik, $search, $offset, $limit
        ]);
    }

    public static function insup($nama_kelas, $kode_kelas, $kode_prodi, $tahun_akademik, $keterangan, $id_kelas = "00000000-0000-0000-0000-000000000000")
    {
        return DB::selectOne("SELECT * FROM akademik.insup_kelas(?,?,?,?,?,?)", [
            $id_kelas, $nama_kelas, $kode_kelas, $kode_prodi, $tahun_akademik, $keterangan
        ]);
    }

    public static function set_aktif($id_kelas, $aktif)
    {
        return DB::selectOne("SELECT * FROM akademik.set_status_kelas(?,?)", [
            $id_kelas, $aktif
        ]);
    }
}
