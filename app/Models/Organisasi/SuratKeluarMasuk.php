<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SuratKeluarMasuk extends Model
{
    use HasFactory;

    public static function daftar_surat_keluar($offset = 0, $limit = -1, $search = '', $tahun_surat = null)
    {
        return DB::select('SELECT * FROM organisasi.daftar_surat_keluar(:offset, :limit, :search, :tahun)', [
            'offset' => $offset, 'limit' => $limit,
            'search' => $search, 'tahun' => $tahun_surat
        ]);
    }

    public static function daftar_surat_masuk($offset = 0, $limit = -1, $search = '', $tahun_surat = null)
    {
        return DB::select('SELECT * FROM organisasi.daftar_surat_masuk(:offset, :limit, :search, :tahun)', [
            'offset' => $offset, 'limit' => $limit,
            'search' => $search, 'tahun' => $tahun_surat
        ]);
    }

    public static function daftar_tahun_surat($jenis, $offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.daftar_tahun_surat(:jenis, :offset, :limit)', [
            'jenis' => $jenis, 'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function insert_surat_keluar($nomor, $tgl, $perihal, $kode, $path, $penerima, $id_personal_pengakses, $jenis = 0){
        return DB::select('SELECT * FROM organisasi.insert_surat_keluar(:nomor, :tgl, :perihal, :kode, :path, :penerima, :id_personal, :jenis)', [
            'nomor' => $nomor, 'tgl' => $tgl, 'perihal' => $perihal, 'kode' => $kode, 'path' => $path, 'penerima' => $penerima, 'id_personal' => $id_personal_pengakses, 'jenis' => $jenis
        ])[0];
    }

    public static function insert_surat_masuk($nomor, $tgl_surat, $tgl_diterima, $perihal, $kode, $path, $pengirim, $nomor_berkas, $id_personal_pengakses, $jenis = 0){
        return DB::select('SELECT * FROM organisasi.insert_surat_masuk(:nomor, :tgl_surat, :tgl_diterima, :perihal, :kode, :path, :pengirim, :nomor_berkas, :id_personal, :jenis)', [
            'nomor' => $nomor, 'tgl_surat' => $tgl_surat, 'tgl_diterima' => $tgl_diterima, 'perihal' => $perihal, 'kode' => $kode, 'path' => $path, 'pengirim' => $pengirim, 'nomor_berkas' => $nomor_berkas, 'id_personal' => $id_personal_pengakses, 'jenis' => $jenis
        ])[0];
    }

    public static function delete_surat_keluar($id_surat_keluar){
        return DB::select('SELECT * FROM organisasi.hapus_surat_keluar(:id_surat_keluar)', [
            'id_surat_keluar' => $id_surat_keluar
        ])[0];
    }

    public static function delete_surat_masuk($id_surat_masuk){
        return DB::select('SELECT * FROM organisasi.hapus_surat_masuk(:id_surat_masuk)', [
            'id_surat_masuk' => $id_surat_masuk
        ])[0];
    }

    public static function detail_surat_keluar($id_surat){
        return DB::select('SELECT * FROM organisasi.get_detail_surat_keluar(:id_surat)', [
            'id_surat' => $id_surat
        ])[0];
    }

    public static function detail_surat_masuk($id_surat){
        return DB::select('SELECT * FROM organisasi.get_detail_surat_masuk(:id_surat)', [
            'id_surat' => $id_surat
        ])[0];
    }

    public static function daftar_partisipan_surat_keluar($id_surat_keluar, $search = '', $offset = -1, $limit = 10){
        return DB::select('SELECT * FROM organisasi.get_partisipan_surat_keluar(:id_surat, :search, :offset, :limit)', [
            'id_surat' => $id_surat_keluar, 'search' => $search, 'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function daftar_partisipan_surat_masuk($id_surat_keluar, $search = '', $offset = -1, $limit = 10){
        return DB::select('SELECT * FROM organisasi.get_partisipan_surat_masuk(:id_surat, :search, :offset, :limit)', [
            'id_surat' => $id_surat_keluar, 'search' => $search, 'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function insert_partisipan_surat_keluar($id_surat, $id_personal){
        return DB::select('SELECT * FROM organisasi.tambah_partisipan_surat_keluar(:id_surat, :id_personal)', [
            'id_surat' => $id_surat, 'id_personal' => $id_personal
        ])[0];
    }

    public static function insert_partisipan_surat_masuk($id_surat, $id_personal){
        return DB::select('SELECT * FROM organisasi.tambah_partisipan_surat_masuk(:id_surat, :id_personal)', [
            'id_surat' => $id_surat, 'id_personal' => $id_personal
        ])[0];
    }

    public static function delete_partisipan_surat_keluar($id_surat, $id_personal){
        return DB::select('SELECT * FROM organisasi.hapus_partisipan_surat_keluar(:id_surat, :id_personal)', [
            'id_surat' => $id_surat, 'id_personal' => $id_personal
        ])[0];
    }

    public static function delete_partisipan_surat_masuk($id_surat, $id_personal){
        return DB::select('SELECT * FROM organisasi.hapus_partisipan_surat_masuk(:id_surat, :id_personal)', [
            'id_surat' => $id_surat, 'id_personal' => $id_personal
        ])[0];
    }

    public static function daftar_surat($id_personal, $offset = 0, $limit = -1, $search = '', $tahun_surat = null)
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_surat_on_karyawan(:id_personal, :offset, :limit, :search, :tahun)', [
            'id_personal' => $id_personal,'offset' => $offset, 'limit' => $limit,
            'search' => $search, 'tahun' => $tahun_surat
        ]);
    }
}
