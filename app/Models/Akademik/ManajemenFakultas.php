<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManajemenFakultas extends Model
{
    use HasFactory;

    public static function get_daftar_fakultas(
        $param_search = '',
        $no_page = -1,
        $jml_record_perpage = 10,
        $kd_fakultas = null,
        $sts_aktif = 2
    )
    {
        return DB::select("SELECT * FROM akademik.get_daftar_fakultas(?, ?, ?, ?, ?)", [
            $param_search,
            $no_page,
            $jml_record_perpage,
            $kd_fakultas,
            $sts_aktif
        ]);
    }

    public static function insup_fakultas(
        $kd_fakultas = null,
        $nama_fakultas = null,
        $dekan = null,
        $kd_nim_fak = null,
        $is_data_aktif = true
    )
    {
        return DB::selectOne("SELECT * FROM akademik.insup_fakultas(?, ?, ?, ?, ?)", [
            $kd_fakultas,
            $nama_fakultas,
            $dekan,
            $kd_nim_fak,
            $is_data_aktif
        ]);
    }

    public static function set_status_aktif_fakultas($kd_fakultas, $status)
    {
        // Trim untuk menghilangkan trailing space dari PostgreSQL character type
        $kd_fakultas = trim($kd_fakultas);

        return DB::selectOne("SELECT * FROM akademik.set_status_aktif_fakultas(?, ?)", [
            $kd_fakultas, $status
        ]);
    }

    public static function get_daftar_prodi_by_fakultas($kd_fakultas)
    {
        // Ambil daftar program studi berdasarkan kode fakultas
        return DB::select("SELECT kd_program_studi, nama_program_studi, kd_jenjang_didik FROM akademik.program_studi WHERE kd_fakultas = ? AND is_data_aktif = true ORDER BY nama_program_studi", [
            $kd_fakultas
        ]);
    }

    public static function get_all_prodi_aktif()
    {
        // Ambil semua program studi yang aktif untuk selection
        return DB::select("SELECT kd_program_studi, nama_program_studi, kd_jenjang_didik, kd_fakultas FROM akademik.program_studi WHERE is_data_aktif = true ORDER BY nama_program_studi");
    }

    public static function update_prodi_fakultas($kd_program_studi, $kd_fakultas)
    {
        // Update kd_fakultas untuk program studi tertentu
        try {
            DB::update("UPDATE akademik.program_studi SET kd_fakultas = ? WHERE kd_program_studi = ?", [
                $kd_fakultas,
                $kd_program_studi
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Update prodi fakultas error:', ['message' => $e->getMessage()]);
            return false;
        }
    }

    public static function remove_prodi_from_fakultas($kd_program_studi)
    {
        // Hapus assignment fakultas dari program studi (set ke null)
        try {
            DB::update("UPDATE akademik.program_studi SET kd_fakultas = NULL WHERE kd_program_studi = ?", [
                $kd_program_studi
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Remove prodi fakultas error:', ['message' => $e->getMessage()]);
            return false;
        }
    }
}
