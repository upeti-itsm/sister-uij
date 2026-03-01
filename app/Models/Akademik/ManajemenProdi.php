<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ManajemenProdi extends Model
{
    use HasFactory;

    public static function get_daftar($sts_aktif = null)
    {
        return DB::select("SELECT * FROM akademik.get_daftar_aturan_sks(?)", [$sts_aktif]);
    }

    public static function get_daftar_program_studi(
        $kd_prodi = null,
        $kd_dikti = null,
        $kd_fakultas = null,
        $param_search = '',
        $sts_aktif = true,
        $no_page = -1,
        $jml_record_perpage = 10
    )
    {
        \Log::info('Calling PostgreSQL function with params:', [
            'kd_prodi' => $kd_prodi,
            'kd_dikti' => $kd_dikti,
            'kd_fakultas' => $kd_fakultas,
            'param_search' => $param_search,
            'sts_aktif' => $sts_aktif,
            'no_page' => $no_page,
            'jml_record_perpage' => $jml_record_perpage
        ]);

        try {
            // Method 1: Try with explicit casting
            $result = DB::select("SELECT * FROM akademik.get_daftar_program_studi(?::varchar, ?::varchar, ?::varchar, ?::varchar, ?::boolean, ?::integer, ?::integer)", [
                $kd_prodi,
                $kd_dikti,
                $kd_fakultas,
                $param_search,
                $sts_aktif,
                $no_page,
                $jml_record_perpage
            ]);

            \Log::info('Method 1 (with casting) result:', ['count' => count($result), 'result' => $result]);

            if (!empty($result)) {
                return $result;
            }
        } catch (\Exception $e) {
            \Log::error('Method 1 failed: ' . $e->getMessage());
        }

        try {
            // Method 2: Try without SELECT *
            $result = DB::select("SELECT akademik.get_daftar_program_studi(?, ?, ?, ?, ?, ?, ?)", [
                $kd_prodi,
                $kd_dikti,
                $kd_fakultas,
                $param_search,
                $sts_aktif,
                $no_page,
                $jml_record_perpage
            ]);

            \Log::info('Method 2 (without SELECT *) result:', ['count' => count($result), 'result' => $result]);

            if (!empty($result)) {
                return $result;
            }
        } catch (\Exception $e) {
            \Log::error('Method 2 failed: ' . $e->getMessage());
        }

        try {
            // Method 3: Original method
            $result = DB::select("SELECT * FROM akademik.get_daftar_program_studi(?, ?, ?, ?, ?, ?, ?)", [
                $kd_prodi,
                $kd_dikti,
                $kd_fakultas,
                $param_search,
                $sts_aktif,
                $no_page,
                $jml_record_perpage
            ]);

            \Log::info('Method 3 (original) result:', ['count' => count($result), 'result' => $result]);

            if (!empty($result)) {
                return $result;
            }
        } catch (\Exception $e) {
            \Log::error('Method 3 failed: ' . $e->getMessage());
        }

        // Fallback: Direct query
        \Log::info('All methods returned empty, trying direct query...');

        try {
            // First, get all data without any conditions to see actual column names
            $result = DB::select("SELECT * FROM akademik.program_studi LIMIT 5");

            \Log::info('Sample data from akademik.program_studi:', [
                'count' => count($result),
                'sample' => $result
            ]);

            if (!empty($result)) {
                // Get column names from first row
                $columns = array_keys((array)$result[0]);
                \Log::info('Available columns:', $columns);

                // Now get all data
                $allData = DB::select("SELECT * FROM akademik.program_studi");
                \Log::info('All data count:', ['count' => count($allData)]);

                return $allData;
            }
        } catch (\Exception $e) {
            \Log::error('Direct query error: ' . $e->getMessage());
        }

        return [];
    }

    public static function insup($id, $ips_min, $ips_max, $sks)
    {
        return DB::selectOne("SELECT * FROM akademik.insup_aturan_sks(?,?,?,?)", [
            $id, $ips_min, $ips_max, $sks
        ]);
    }

    public static function insup_prodi(
        $id_program_studi = null,
        $kd_program_studi = null,
        $nama_program_studi = null,
        $kd_jenjang_didik = null,
        $kd_fakultas = null,
        $karyawan_id_kaprodi = null,
        $kd_nim = null,
        $no_urut_prodi_wisuda = null,
        $sts_kip = false,
        $kd_dikti = '34',
        $is_s2 = false
        // Function PostgreSQL HANYA punya 11 parameter!
    )
    {
        // Helper function untuk escape string PostgreSQL
        $pg_escape = function($val) {
            if ($val === null) return 'NULL';
            return "'" . str_replace("'", "''", $val) . "'";
        };

        // Cast sesuai function signature - character bukan character(2)!
        $id_program_studi_safe = $id_program_studi ? $pg_escape($id_program_studi) . '::uuid' : 'NULL::uuid';
        $kd_program_studi_safe = $kd_program_studi ? $pg_escape($kd_program_studi) . '::varchar' : 'NULL::varchar';
        $nama_program_studi_safe = $nama_program_studi ? $pg_escape($nama_program_studi) . '::varchar' : 'NULL::varchar';
        // Cast ke 'character' (generic) bukan 'character(2)' atau 'bpchar(2)'
        $kd_jenjang_didik_safe = $kd_jenjang_didik ? $pg_escape(substr($kd_jenjang_didik, 0, 2)) . '::character' : 'NULL::character';
        $kd_fakultas_safe = $kd_fakultas ? $pg_escape($kd_fakultas) . '::varchar' : 'NULL::varchar';
        $karyawan_id_kaprodi_safe = $karyawan_id_kaprodi ? $pg_escape($karyawan_id_kaprodi) . '::varchar' : 'NULL::varchar';
        $kd_nim_safe = $kd_nim ? $pg_escape(substr($kd_nim, 0, 2)) . '::character' : 'NULL::character';
        $no_urut_prodi_wisuda_safe = $no_urut_prodi_wisuda !== null ? $no_urut_prodi_wisuda . '::integer' : 'NULL::integer';
        $sts_kip_safe = $sts_kip ? 'true::boolean' : 'false::boolean';
        $kd_dikti_safe = $kd_dikti ? $pg_escape($kd_dikti) . '::varchar' : 'NULL::varchar';
        $is_s2_safe = $is_s2 ? 'true::boolean' : 'false::boolean';

        // HANYA 11 parameter - function PostgreSQL tidak punya is_data_aktif!
        $sql = "SELECT * FROM akademik.insup_program_studi(
            {$id_program_studi_safe},
            {$kd_program_studi_safe},
            {$nama_program_studi_safe},
            {$kd_jenjang_didik_safe},
            {$kd_fakultas_safe},
            {$karyawan_id_kaprodi_safe},
            {$kd_nim_safe},
            {$no_urut_prodi_wisuda_safe},
            {$sts_kip_safe},
            {$kd_dikti_safe},
            {$is_s2_safe}
        )";

        return DB::selectOne($sql);
    }

    public static function set_aktif($id, $status)
    {
        return DB::selectOne("SELECT * FROM akademik.set_status_aktif_aturan_sks(?,?)", [
            $id, $status
        ]);
    }

    public static function set_aktif_prodi($id, $status)
    {
        return DB::selectOne("SELECT * FROM akademik.set_status_aktif_program_studi(?::uuid, ?::boolean)", [
            $id, $status
        ]);
    }

    public static function get_daftar_fakultas(
        $param_search = '',
        $no_page = -1,
        $jml_record_perpage = 10,
        $kd_fakultas = null,
        $sts_aktif = true
    )
    {
        return DB::select("SELECT * FROM akademik.get_daftar_fakultas(?::varchar, ?::integer, ?::integer, ?::varchar, ?::boolean)", [
            $param_search,
            $no_page,
            $jml_record_perpage,
            $kd_fakultas,
            $sts_aktif
        ]);
    }
}
