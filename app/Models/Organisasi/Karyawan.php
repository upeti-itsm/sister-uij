<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Karyawan extends Model
{
    use HasFactory;

    public static function get_daftar_karyawan($search = '', $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_karyawan(:search, :offset, :limit)', [
            'search' => $search, 'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function get_username_by_id_personal($id_personal)
    {
        return DB::select('SELECT * FROM organisasi.get_username_by_id_personal(:id_personal)', [
            'id_personal' => $id_personal
        ])[0];
    }

    public static function get_detail_karyawan_by_id_personal($id_personal)
    {
        return DB::select('SELECT * FROM organisasi.get_detail_karyawan_by_id_personal(:id_personal)', [
            'id_personal' => $id_personal
        ])[0];
    }

    public static function update_path_photo($id_personal, $nama_file)
    {
        return DB::select('SELECT * FROM organisasi.update_path_photo_by_id_personal(:id_personal, :nama_file)', [
            'id_personal' => $id_personal, 'nama_file' => $nama_file
        ])[0];
    }

    public static function update_data_personal_on_karyawan($id_personal, $nomor_ktp, $nama, $gelar_depan, $gelar_belakang, $tempat_lahir, $tanggal_lahir, $nomor_hp, $email, $jenis_kelamin, $agama, $alamat)
    {
        return DB::select('SELECT * FROM organisasi.update_personal_karyawan(:id_personal, :nomor_ktp, :nama, :gelar_depan, :gelar_belakang, :tempat_lahir, :tanggal_lahir, :nomor_hp, :email, :jenis_kelamin, :agama, :alamat)', [
            'id_personal' => $id_personal, 'nomor_ktp' => $nomor_ktp, 'nama' => $nama,
            'gelar_depan' => $gelar_depan, 'gelar_belakang' => $gelar_belakang, 'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir, 'nomor_hp' => $nomor_hp, 'email' => $email,
            'jenis_kelamin' => $jenis_kelamin, 'agama' => $agama, 'alamat' => $alamat
        ])[0];
    }

    public static function get_daftar_pegawai($search = '', $offset = -1, $limit = 10, $jenis_pegawai = null, $id_personal = '00000000-0000-0000-0000-000000000000')
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_pegawai(:search, :offset, :limit, :jenis_pegawai, :id_personal)', [
            'search' => $search, 'offset' => $offset, 'limit' => $limit, 'jenis_pegawai' => $jenis_pegawai, 'id_personal' => $id_personal
        ]);
    }

    public static function insert_data_pegawai($nama_lengkap, $gelar_depan, $gelar_belakang, $nip, $nidn, $kd_jenis_kelamin, $tempat_lahir, $tanggal_lahir, $alamat, $nomor_hp, $email, $id_agama, $nomor_rekening, $tgl_masuk, $id_unit_kerja, $no_ktp, $nik_mandala, $path_photo, $id_jenis_karyawan, $status_pernikahan, $kd_pendidikan, $path_ijazah, $tgl_lulus, $id_golongan, $path_sk_golongan, $tmt_golongan, $id_jabatan_struktural, $tmt_jabatan_struktural, $path_sk_struktural, $id_jabatan_fungsional, $path_sk_jabatan_fungsional, $tmt_jabatan_fungsional, $file_kk, $nama_bank = 'BNI', $is_sertifikasi = false, $id_sinta = '', $path_sertifikasi = '-', $homebase = '-', $jenis_bank = '', $ip_absensi)
    {
        return DB::select('SELECT * FROM organisasi.insert_data_pegawai(:nama_lengkap, :gelar_depan, :gelar_belakang, :nip, :nidn, :kd_jenis_kelamin, :tempat_lahir, :tanggal_lahir, :alamat, :nomor_hp, :email, :id_agama, :nomor_rekening, :tgl_masuk, :id_unit_kerja, :no_ktp, :nik_mandala, :path_photo, :id_jenis_karyawan, :status_pernikahan, :kd_pendidikan, :path_ijazah, :tgl_lulus, :id_golongan, :path_sk_golongan, :tmt_golongan, :id_jabatan_struktural, :tmt_jabatan_struktural, :path_sk_struktural, :id_jabatan_fungsional, :path_sk_jabatan_fungsional, :tmt_jabatan_fungsional, :file_kk, :nama_bank, :is_sertifikasi, :id_sinta, :path_sertifikasi, :homebase, :jenis_bank, :ip_asbensi)', [
            'nama_lengkap' => $nama_lengkap, 'gelar_depan' => $gelar_depan, 'gelar_belakang' => $gelar_belakang,
            'nip' => $nip, 'nidn' => $nidn, 'kd_jenis_kelamin' => $kd_jenis_kelamin, 'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir, 'alamat' => $alamat, 'nomor_hp' => $nomor_hp, 'email' => $email, 'id_agama' => $id_agama,
            'nomor_rekening' => $nomor_rekening, 'tgl_masuk' => $tgl_masuk, 'id_unit_kerja' => $id_unit_kerja, 'no_ktp' => $no_ktp,
            'nik_mandala' => $nik_mandala, 'path_photo' => $path_photo, 'id_jenis_karyawan' => $id_jenis_karyawan,
            'status_pernikahan' => $status_pernikahan, 'kd_pendidikan' => $kd_pendidikan, 'path_ijazah' => $path_ijazah,
            'tgl_lulus' => $tgl_lulus, 'id_golongan' => $id_golongan, 'path_sk_golongan' => $path_sk_golongan, 'tmt_golongan' => $tmt_golongan,
            'id_jabatan_struktural' => $id_jabatan_struktural, 'tmt_jabatan_struktural' => $tmt_jabatan_struktural, 'path_sk_struktural' => $path_sk_struktural,
            'id_jabatan_fungsional' => $id_jabatan_fungsional, 'tmt_jabatan_fungsional' => $tmt_jabatan_fungsional, 'path_sk_jabatan_fungsional' => $path_sk_jabatan_fungsional,
            'file_kk' => $file_kk, 'nama_bank' => $nama_bank,
            'is_sertifikasi' => $is_sertifikasi, 'id_sinta' => $id_sinta, 'path_sertifikasi' => $path_sertifikasi, 'homebase' => $homebase, 'jenis_bank' => $jenis_bank, 'ip_absensi' => $ip_absensi
        ])[0];
    }

    public static function update_data_pegawai($id_personal, $nama, $gelar_depan, $gelar_belakang, $nip, $nidn, $kd_jenis_kelamin, $tempat_lahir, $tanggal_lahir, $alamat, $nomor_hp, $email, $id_agama, $nomor_rekening, $tgl_masuk, $id_unit_kerja, $no_ktp, $nik_mandala, $id_jenis_karyawan, $status_pernikahan, $file_kk, $id_golongan, $file_sk_golongan, $tmt_golongan, $id_jafung, $file_sk_jafung, $tmt_jafung, $id_jastruk, $file_sk_jastruk, $tmt_jastruk, $id_pendidikan, $file_ijazah, $tgl_lulus, $nama_bank = 'BNI', $is_sertifikasi = false, $id_sinta = '', $path_sertifikasi = '-', $homebase = '-', $jenis_bank = '', $ip_absensi = '')
    {
        return DB::select('SELECT * FROM organisasi.update_data_pegawai(:id_personal, :nama_lengkap, :gelar_depan, :gelar_belakang, :nip, :nidn, :kd_jenis_kelamin, :tempat_lahir, :tanggal_lahir, :alamat, :nomor_hp, :email, :id_agama, :nomor_rekening, :tgl_masuk, :id_unit_kerja, :no_ktp, :nik_mandala, :id_jenis_karyawan, :status_pernikahan, :file_kk, :id_golongan, :file_sk_golongan, :tmt_golongan, :id_jafung, :file_sk_jafung, :tmt_jafung, :id_jastruk, :file_sk_jastruk, :tmt_jastruk, :id_pendidikan, :file_ijazah, :tgl_lulus, :nama_bank, :is_sertifikasi, :id_sinta, :path_sertifikasi, :homebase, :jenis_bank, :ip_absensi)', [
            'id_personal' => $id_personal, 'nama_lengkap' => $nama, 'gelar_depan' => $gelar_depan, 'gelar_belakang' => $gelar_belakang,
            'nip' => $nip, 'nidn' => $nidn, 'kd_jenis_kelamin' => $kd_jenis_kelamin, 'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir, 'alamat' => $alamat, 'nomor_hp' => $nomor_hp, 'email' => $email, 'id_agama' => $id_agama,
            'nomor_rekening' => $nomor_rekening, 'tgl_masuk' => $tgl_masuk, 'id_unit_kerja' => $id_unit_kerja, 'no_ktp' => $no_ktp,
            'nik_mandala' => $nik_mandala, 'id_jenis_karyawan' => $id_jenis_karyawan,
            'status_pernikahan' => $status_pernikahan, 'file_kk' => $file_kk,
            'id_golongan' => $id_golongan, 'file_sk_golongan' => $file_sk_golongan, 'tmt_golongan' => $tmt_golongan,
            'id_jafung' => $id_jafung, 'file_sk_jafung' => $file_sk_jafung, 'tmt_jafung' => $tmt_jafung,
            'id_jastruk' => $id_jastruk, 'file_sk_jastruk' => $file_sk_jastruk, 'tmt_jastruk' => $tmt_jastruk,
            'id_pendidikan' => $id_pendidikan, 'file_ijazah' => $file_ijazah, 'tgl_lulus' => $tgl_lulus, 'nama_bank' => $nama_bank,
            'is_sertifikasi' => $is_sertifikasi, 'id_sinta' => $id_sinta, 'path_sertifikasi' => $path_sertifikasi, 'homebase' => $homebase, 'jenis_bank' => $jenis_bank, 'ip_absensi' => $ip_absensi
        ])[0];
    }

    public static function delete_pegawai($id_personal)
    {
        return DB::select('SELECT * FROM organisasi.hapus_pegawai(:id_personal)', [
            'id_personal' => $id_personal
        ])[0];
    }

    public static function update_kesehatan($id_karyawan, $status)
    {
        return DB::select('SELECT * FROM organisasi.set_potongan_asuransi_kesehatan(:id_karyawan, :status)', [
            'id_karyawan' => $id_karyawan, 'status' => $status
        ])[0];
    }

    public static function update_ketenagakerjaan($id_karyawan, $status)
    {
        return DB::select('SELECT * FROM organisasi.set_potongan_asuransi_ketenagakerjaan(:id_karyawan, :status)', [
            'id_karyawan' => $id_karyawan, 'status' => $status
        ])[0];
    }

    public static function update_dplk($id_karyawan, $status, $nominal = 0)
    {
        return DB::select('SELECT * FROM organisasi.set_potongan_dplk(:id_karyawan, :status, :nominal)', [
            'id_karyawan' => $id_karyawan, 'status' => $status, 'nominal' => $nominal
        ])[0];
    }

    public static function update_kinerja($id_karyawan, $kinerja)
    {
        return DB::select('SELECT * FROM organisasi.set_tunjangan_kinerja_dosen(:id_karyawan, :kinerja)', [
            'id_karyawan' => $id_karyawan, 'kinerja' => $kinerja
        ])[0];
    }

    public static function get_daftar_ulang_tahun($offset = -1, $limit = 10, $search = '')
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_ulang_tahun_karyawan(:offset, :limit, :search)', [
            'offset' => $offset, 'limit' => $limit, 'search' => $search
        ]);
    }
}
