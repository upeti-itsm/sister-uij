<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JadwalKuliahMahasiswa extends Model
{
    use HasFactory;

    public static function get_tahun_akademik()
    {
        return DB::select('SELECT * FROM akademik.get_tahun_akademik_jadwal_kuliah_mahasiswa()');
    }

    public static function get_jadwal_matakuliah_mahasiswa($nim = 'all', $prodi = "all", $tahun_akademik = "all", $search = "", $offset = 0, $limit = -1)
    {
        return DB::select('SELECT * FROM akademik.get_daftar_jadwal_kuliah_mahasiswa(:nim, :prodi, :tahun_akademik, :search, :offset, :limit)', [
            'search' => $search, 'prodi' => $prodi, 'tahun_akademik' => $tahun_akademik,
            'limit' => $limit, 'offset' => $offset, 'nim' => $nim
        ]);
    }

    public static function insert_jadwal_matakuliah_mahasiswa($nim, $jadawal_kuliah_id)
    {
        return DB::select('SELECT * FROM akademik.insert_jadwal_kuliah_mahasiswa(:nim, :jadwal_kuliah_id)', [
            'jadwal_kuliah_id' => $jadawal_kuliah_id, 'nim' => $nim
        ])[0];
    }

    public static function delete_jadwal_matakuliah_mahasiswa($tahun_akademik, $nim = '-', $id = 0)
    {
        return DB::select('SELECT * FROM akademik.delete_jadwal_kuliah_mahasiswa(:tahun_akademik, :nim, :id)', [
            'tahun_akademik' => $tahun_akademik, 'nim' => $nim, 'id' => $id
        ])[0];
    }

    public static function set_absensi_perkuliahan_mahasiswa($id_rekap, $id_mhs, $ip)
    {
        return DB::selectOne("select * from absensi.set_absensi_perkuliahan_mahasiswa(?,?,?)", [
            $id_rekap, $id_mhs, $ip
        ]);
    }

    public static function rekap_presensi_mahasiswa_per_jadwal($id_jadwal_kuliah, $search = '' , $offset = 0, $limit = -1)
    {
        return DB::select('SELECT * FROM absensi.rekap_presensi_mahasiswa_per_jadwal_new(:id_jadwal_kuliah, :search, :limit, :offset)', [
            'id_jadwal_kuliah' => $id_jadwal_kuliah,
            'search'          => $search,
            'limit'           => $limit,
            'offset'          => $offset
        ]);
    }
}
