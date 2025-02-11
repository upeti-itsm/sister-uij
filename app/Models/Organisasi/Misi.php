<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Misi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'organisasi.misi';
    public $timestamps = false;

    public static function get_misi()
    {
        return DB::select('SELECT * FROM organisasi.misi ORDER BY nomor_urut ASC');
    }

    public static function insup_misi($nomor, $misi, $id = 0)
    {
        return DB::select('SELECT * FROM organisasi.insup_misi(?,?,?)', [
            $id, $nomor, $misi
        ])[0];
    }

    public static function delete_misi($id)
    {
        return DB::select('SELECT * FROM organisasi.del_misi_institusi(?)', [
            $id
        ])[0];
    }

    public static function get_visi()
    {
        return DB::select('SELECT * FROM organisasi.institusi ORDER BY tgl_created DESC')[0];
    }

    public static function update_visi($id, $visi)
    {
        return DB::select('SELECT * FROM organisasi.update_visi(?,?)', [
            $id, $visi
        ])[0];
    }

    public static function update_dokumen($path_dokumen)
    {
        return DB::select('SELECT * FROM organisasi.set_struktur_organisasi(?)', [
            $path_dokumen
        ])[0];
    }

    public static function get_struktur()
    {
        return DB::select('SELECT * FROM organisasi.get_struktur_organisasi()')[0];
    }
}
