<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SuratKeputusan extends Model
{
    use HasFactory;

    public static function daftar_sk($offset = 0, $limit = -1, $search = '', $tahun = null)
    {
        return DB::select('SELECT * FROM organisasi.daftar_surat_keputusan(:offset, :limit, :search, :tahun)', [
            'offset' => $offset, 'limit' => $limit,
            'search' => $search, 'tahun' => $tahun
        ]);
    }

    public static function insert_sk($nomor, $tgl, $nama_sk, $path, $id_personal_pengakses, $jenis_pengakses = 0){
        return DB::select('SELECT * FROM organisasi.insert_surat_keputusan(:nomor, :tgl, :nama_sk, :path, :id_personal, :jenis_pengakses)', [
            'nomor' => $nomor, 'tgl' => $tgl, 'nama_sk' => $nama_sk, 'path' => $path, 'id_personal' => $id_personal_pengakses, 'jenis_pengakses' => $jenis_pengakses
        ])[0];
    }

    public static function delete_sk($id_sk){
        return DB::select('SELECT * FROM organisasi.hapus_surat_keputusan(:id_sk)', [
            'id_sk' => $id_sk
        ])[0];
    }

    public static function detail_sk($id_sk){
        return DB::select('SELECT * FROM organisasi.get_detail_surat_keputusan(:id_sk)', [
            'id_sk' => $id_sk
        ])[0];
    }

    public static function daftar_partisipan_sk($id_sk, $search = '', $offset = -1, $limit = 10){
        return DB::select('SELECT * FROM organisasi.get_partisipan_surat_keputusan(:id_sk, :search, :offset, :limit)', [
            'id_sk' => $id_sk, 'search' => $search, 'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function insert_partisipan_sk($id_sk, $id_personal){
        return DB::select('SELECT * FROM organisasi.tambah_partisipan_surat_keputusan(:id_sk, :id_personal)', [
            'id_sk' => $id_sk, 'id_personal' => $id_personal
        ])[0];
    }

    public static function delete_partisipan_sk($id_sk, $id_personal){
        return DB::select('SELECT * FROM organisasi.hapus_partisipan_surat_keputusan(:id_sk, :id_personal)', [
            'id_sk' => $id_sk, 'id_personal' => $id_personal
        ])[0];
    }

    public static function daftar_tahun_sk($offset = -1, $limit = 10)
    {
        return DB::select('SELECT * FROM organisasi.daftar_tahun_sk(:offset, :limit)', [
            'offset' => $offset, 'limit' => $limit
        ]);
    }

    public static function daftar_sk_on_karyawan($id_personal, $offset = 0, $limit = -1, $search = '', $tahun_sk = null)
    {
        return DB::select('SELECT * FROM organisasi.get_daftar_sk_on_karyawan(:id_personal, :offset, :limit, :search, :tahun)', [
            'id_personal' => $id_personal,'offset' => $offset, 'limit' => $limit,
            'search' => $search, 'tahun' => $tahun_sk
        ]);
    }
}
