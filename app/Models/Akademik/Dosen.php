<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dosen extends Model
{
    protected $primaryKey = 'id_dossen';
    protected $table = 'akademik.dosen';
    public $timestamps = false;
    protected $casts = [
        'id_dosen' => 'string'
    ];
    protected $keyType = 'string';

    public static function get_dosen($id_prodi = "00000000-0000-0000-0000-000000000000", $offset = -1, $limit = 10, $search = "")
    {
        return DB::select('SELECT * FROM akademik.get_dosen(:id_prodi, :offset, :limit, :search)', [
            'id_prodi' => $id_prodi,
            'offset' => $offset,
            'limit' => $limit,
            'search' => $search
        ]);
    }

    public static function insup_dosen($id_dosen, $nama_dosen, $nidn, $nip, $kd_jenis_kelamin, $id_agama, $tempat_lahir, $tanggal_lahir, $id_status_kepegawaian, $nama_ibu_kandung, $nik, $npwp, $id_jenis_sdm, $no_sk_cpns, $tgl_sk_cpns, $no_sk_pengangkatan, $tgl_mulai_sk_pengangkatan, $id_lembaga_pengangakatan, $id_pangkat_golongan, $id_sumber_gaji, $alamat, $dusun, $rt, $rw, $kode_pos, $id_wilayah, $nomor_telepon, $nomor_hp, $surel, $status_pernikahan, $nama_suami_istri, $nip_suami_istri, $tanggal_mulai_pns, $id_pekerjaan_suami_istri)
    {
        return DB::select('SELECT * FROM akademik.sync_dosen_with_feeder(:id_dosen, :nama_dosen, :nidn, :nip, :kd_jenis_kelamin, :id_agama, :tempat_lahir, :tanggal_lahir::date, :id_status_kepegawaian, :nama_ibu_kandung, :nik, :npwp, :id_jenis_sdm, :no_sk_cpns, :tgl_sk_cpns::date, :no_sk_pengangkatan, :tgl_mulai_sk_pengangkatan::date, :id_lembaga_pengangakatan, :id_pangkat_golongan, :id_sumber_gaji, :alamat, :dusun, :rt, :rw, :kode_pos, :id_wilayah, :nomor_telepon, :nomor_hp, :surel, :status_pernikahan, :nama_suami_istri, :nip_suami_istri, :tanggal_mulai_pns::date, :id_pekerjaan_suami_istri)', [
            'id_dosen' => $id_dosen, 'nama_dosen' => $nama_dosen, 'nidn' => $nidn, 'nip' => $nip, 'kd_jenis_kelamin' => $kd_jenis_kelamin, 'id_agama' => $id_agama, 'tempat_lahir' => $tempat_lahir, 'tanggal_lahir' => $tanggal_lahir, 'id_status_kepegawaian' => $id_status_kepegawaian, 'nama_ibu_kandung' => $nama_ibu_kandung, 'nik' => $nik, 'npwp' => $npwp, 'id_jenis_sdm' => $id_jenis_sdm, 'no_sk_cpns' => $no_sk_cpns, 'tgl_sk_cpns' => $tgl_sk_cpns, 'no_sk_pengangkatan' => $no_sk_pengangkatan, 'tgl_mulai_sk_pengangkatan' => $tgl_mulai_sk_pengangkatan, 'id_lembaga_pengangakatan' => $id_lembaga_pengangakatan, 'id_pangkat_golongan' => $id_pangkat_golongan, 'id_sumber_gaji' => $id_sumber_gaji, 'alamat' => $alamat, 'dusun' => $dusun, 'rt' => $rt, 'rw' => $rw, 'kode_pos' => $kode_pos, 'id_wilayah' => $id_wilayah, 'nomor_telepon' => $nomor_telepon, 'nomor_hp' => $nomor_hp, 'surel' => $surel, 'status_pernikahan' => $status_pernikahan, 'nama_suami_istri' => $nama_suami_istri, 'nip_suami_istri' => $nip_suami_istri, 'tanggal_mulai_pns' => $tanggal_mulai_pns, 'id_pekerjaan_suami_istri' => $id_pekerjaan_suami_istri
        ])[0];
    }

    public static function sync_dosen_with_siakad($id_krayawan, $nik, $password, $nip, $npwp, $bank_account, $masa_pensiun, $status_aktif, $tgl_masuk, $asal_sekolah, $nidn, $nama_lengkap, $gelar_awal, $gelar_akhir, $no_ktp, $tempat_lahir, $tgl_lahir, $alamat_rumah, $kode_pos_rumah, $telepon_rumah, $hp_no, $agama, $jenis_kelamin, $status_menikah, $jenjang_pendidikan_akhir, $nama_ibu, $email, $kd_prodi)
    {
        if ($masa_pensiun == "1900-00-00")
            $masa_pensiun = "1900-01-01";
        if ($tgl_masuk == "1900-00-00")
            $tgl_masuk = "1900-01-01";
        if ($tgl_lahir == "1900-00-00")
            $tgl_lahir = "1900-01-01";
        return DB::select('SELECT * FROM akademik.sync_dosen_with_siakad(:id_karyawan, :nik, :password, :nip, :npwp, :bank_account, :masa_pensiun, :status_aktif, :tgl_masuk, :asal_sekolah, :nidn, :nama_lengkap, :gelar_awal, :gelar_akhir, :no_ktp, :tempat_lahir, :tgl_lahir, :alamat_rumah, :kode_pos_rumah, :telepon_rumah, :hp_no, :agama, :jenis_kelamin, :status_menikah, :jenjang_pendidikan_akhir, :nama_ibu, :email, :kd_prodi)', [
            'id_karyawan' => $id_krayawan, 'nik' => $nik, 'password' => $password, 'nip' => $nip, 'npwp' => $npwp,
            'bank_account' => $bank_account, 'masa_pensiun' => $masa_pensiun, 'status_aktif' => $status_aktif, 'tgl_masuk' => $tgl_masuk,
            'asal_sekolah' => $asal_sekolah, 'nidn' => $nidn, 'nama_lengkap' => $nama_lengkap, 'gelar_awal' => $gelar_awal, 'gelar_akhir' => $gelar_akhir,
            'no_ktp' => $no_ktp, 'tempat_lahir' => $tempat_lahir, 'tgl_lahir' => $tgl_lahir, 'alamat_rumah' => $alamat_rumah, 'kode_pos_rumah' => $kode_pos_rumah,
            'telepon_rumah' => $telepon_rumah, 'hp_no' => $hp_no, 'agama' => $agama, 'jenis_kelamin' => $jenis_kelamin, 'status_menikah' => $status_menikah,
            'jenjang_pendidikan_akhir' => $jenjang_pendidikan_akhir, 'nama_ibu' => $nama_ibu, 'email' => $email, 'kd_prodi' => $kd_prodi
        ])[0];
    }

    public static function get_dosen_by_id_personal($id_personal)
    {
        return DB::select('SELECT * FROM akademik.get_dosen(:id_prodi, :offset, :limit, :search, :id_personal)', [
            'id_prodi' => "00000000-0000-0000-0000-000000000000",
            'offset' => 0,
            'limit' => 1,
            'search' => "",
            'id_personal' => $id_personal
        ])[0];
    }

    public static function get_riwayat_pendidikan($id_personal, $offset = 0, $limit = 100)
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_riwayat_pendidikan_personal(?,?,?)', [
            $id_personal, $offset, $limit
        ]);
    }

    public static function update_riwayat_pendidikan($id_personal, $no_pendidikan, $nama_pt, $bid_ilmu, $tgl_lulus, $nama_prodi)
    {
        return DB::select('SELECT * FROM organisasi.update_riwayat_pendidikan_on_dosen(?,?,?,?,?,?)', [
            $id_personal, $no_pendidikan, $nama_pt, $bid_ilmu, $tgl_lulus, $nama_prodi
        ])[0];
    }

    public static function get_riwayat_publikasi_jurnal($id_karyawan, $search = "", $offset = 0, $limit = -1, $jenis_jurnal = 1)
    {
        if ($jenis_jurnal == 1)
            return DB::select('SELECT * FROM organisasi.get_daftar_riwayat_jurnal(?,?,?,?)', [
                $id_karyawan, $search, $offset, $limit
            ]);
        elseif ($jenis_jurnal == 0)
            return DB::select('SELECT * FROM organisasi.get_daftar_riwayat_jurnal_internasional_dosen(?,?,?,?)', [
                $id_karyawan, $search, $offset, $limit
            ]);
        else
            return DB::select('SELECT * FROM organisasi.get_daftar_riwayat_jurnal_pengabdian(?,?,?,?)', [
                $id_karyawan, $search, $offset, $limit
            ]);
    }

    public static function insup_riwayat_publikasi_jurnal($id_riwayat, $id_karyawan, $tahun, $judul, $nama_jurnal, $volume, $nomor, $jenis_jurnal = 1)
    {
        if ($jenis_jurnal == 1)
            return DB::select('SELECT * FROM organisasi.insup_riwayat_jurnal_nasional(?,?,?,?,?,?,?)', [
                $id_riwayat, $id_karyawan, $tahun, $judul, $nama_jurnal, $volume, $nomor
            ])[0];
        elseif ($jenis_jurnal == 0)
            return DB::select('SELECT * FROM organisasi.insup_riwayat_jurnal_internasional(?,?,?,?,?,?,?)', [
                $id_riwayat, $id_karyawan, $tahun, $judul, $nama_jurnal, $volume, $nomor
            ])[0];
        else
            return DB::select('SELECT * FROM  organisasi.insup_riwayat_jurnal_pengabdian(?,?,?,?,?,?,?)', [
                $id_riwayat, $id_karyawan, $tahun, $judul, $nama_jurnal, $volume, $nomor
            ])[0];
    }

    public static function get_kecukupan_dosen($search = '', $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_rekap_kecukupan_dosen_pt(?,?,?)', [
            $search, $offset, $limit
        ]);
    }

    public static function get_jabatan_akademik_dosen()
    {
        return DB::select('SELECT * FROM organisasi.get_rekap_jabatan_akademik_dosen_tetap()');
    }

    public static function get_sertifikasi_dosen($search = '', $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_rekap_pekerti_dosen(?,?,?)', [
            $search, $offset, $limit
        ]);
    }

    public static function get_dosen_tidak_tetap()
    {
        return DB::select('SELECT * FROM organisasi.get_rekap_dosen_tidak_tetap()');
    }

    public static function get_rasio_dosen($search = '', $offset = -1, $limit = 10, $tahun_akademik = null)
    {
        return DB::select('SELECT * FROM organisasi.get_rekap_rasio_terhadap_mahasiswa(?,?,?,?)', [
            $search, $offset, $limit, $tahun_akademik
        ]);
    }

    public static function get_detail_kecukupan_dosen($id_prodi = '00000000-0000-0000-0000-000000000000', $request = 0, $search = '', $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_detail_rekap_kecukupan_dosen_pt(?,?,?,?,?)', [
            $id_prodi, $request, $search, $offset, $limit
        ]);
    }

    public static function get_detail_jafa($kd_pendidikan = '0', $jafung = 0, $search = '', $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_detail_rekap_jabatan_akademik_dosen_tetap(?,?,?,?,?)', [
            $kd_pendidikan, $jafung, $search, $offset, $limit
        ]);
    }

    public static function get_detail_serdos($kd_pendidikan = '0', $jafung = 0, $search = '', $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_detail_rekap_pekerti_dosen(?,?,?,?,?)', [
            $kd_pendidikan, $jafung, $search, $offset, $limit
        ]);
    }

    public static function get_detail_jafa_tidak_tetap($kd_pendidikan = '0', $jafung = 0, $search = '', $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_detail_rekap_dosen_tidak_tetap(?,?,?,?,?)', [
            $kd_pendidikan, $jafung, $search, $offset, $limit
        ]);
    }

    public static function get_detail_mahasiswa($angkatan, $id_prodi, $search = '', $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_detail_rekap_rasio_dosen_jml_mahasiswa(?,?,?,?,?)', [
            $angkatan, $id_prodi, $search, $offset, $limit
        ]);
    }
}
