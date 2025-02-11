<?php

namespace App\Models\Absensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RekapitulasiAbsensiMengajarDosen extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_rekapitulasi_absensi_mengajar_dosen';
    protected $table = 'absensi.rekapitulasi_absensi_mengajar_dosen';
    public $timestamps = false;

    public static function addAbsensi($username, $jadwal_id, $fullname, $keterangan, $pertemuan_ke, $tgl_pelaksanaan, $id_waktu_mengajar, $jml_mahasiswa_hadir, $jml_mahasiswa_tdk_hadir, $path_bukti_ajar, $path_absensi_mahasiswa, $materi_pembelajaran, $tahun_akademik, $jenis_pertemuan){
        return DB::select('SELECT * FROM absensi.absensi_set_waktu_mengajar_dosen(:username, :jadwal_id, :fullname, :keterangan, :pertemuan_ke, :tgl_pelaksanaan, :id_waktu_mengajar, :jml_mahasiswa_hadir, :jml_mahasiswa_tdk_hadir, :path_bukti_ajar, :path_absensi_mahasiswa, :materi_pembelajaran, :tahun_akademik, :jenis_pertemuan)', [
            'username' => $username, 'jadwal_id' => $jadwal_id, 'fullname' => $fullname, 'keterangan' => $keterangan,
            'pertemuan_ke' => $pertemuan_ke, 'tgl_pelaksanaan' => $tgl_pelaksanaan, 'id_waktu_mengajar' => $id_waktu_mengajar,
            'jml_mahasiswa_hadir' => $jml_mahasiswa_hadir, 'jml_mahasiswa_tdk_hadir' => $jml_mahasiswa_tdk_hadir,
            'path_bukti_ajar' => $path_bukti_ajar, 'path_absensi_mahasiswa' => $path_absensi_mahasiswa, 'materi_pembelajaran' => $materi_pembelajaran, 'tahun_akademik' => $tahun_akademik, 'jenis_pertemuan' => $jenis_pertemuan
        ])[0];
    }

    public static function getRekapitulasiByUsername($username = "all", $search = "", $offset = -1, $limit = 10){
        return DB::select('SELECT * FROM absensi.get_rekapitulasi_absensi_mengajar_dosen_by_username(:username, :search, :offset, :limit)', [
            'username' => $username,
            'search' => $search,
            'offset' => $offset,
            'limit' => $limit
        ]);
    }

    public static function getRekapitulasiByPersonal($tgl_awal, $tgl_akhir, $id_personal = NULL, $search = "", $offset = -1, $limit = 10, $tahun_akademik = '00000'){
        return DB::select('SELECT * FROM absensi.get_rekapitulasi_absensi_mengajar_dosen_by_personal(:tgl_awal, :tgl_akhir, :id_personal, :search, :offset, :limit, :tahun_akademik)', [
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
            'id_personal' => $id_personal,
            'search' => $search,
            'offset' => $offset,
            'limit' => $limit,
            'tahun_akademik' => $tahun_akademik
        ]);
    }

    public static function getRekapitulasiByPersonalOnHrd($tgl_awal, $tgl_akhir, $id_personal = NULL, $search = "", $offset = -1, $limit = 10, $tahun_akademik = '20221'){
        return DB::select('SELECT * FROM absensi.get_rekapitulasi_absensi_mengajar_dosen_by_personal_on_hrd(:tgl_awal, :tgl_akhir, :id_personal, :search, :offset, :limit, :tahun_akademik)', [
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
            'id_personal' => $id_personal,
            'search' => $search,
            'offset' => $offset,
            'limit' => $limit,
            'tahun_akademik' => $tahun_akademik
        ]);
    }

    public static function delRekapitulasi($id_rekapitulasi, $id_personal){
        return DB::select('SELECT * FROM absensi.del_rekapitulasi_absensi_mengajar_dosen(:id_rekapitulasi,:id_personal)', [
            'id_rekapitulasi' => $id_rekapitulasi,
            'id_personal' => $id_personal
        ])[0];
    }
}
