<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\FacadesLog;

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
        Log::info('========== MODEL get_daftar_program_studi ==========');
        Log::info('Calling PostgreSQL function with params:', [
            'kd_prodi' => $kd_prodi,
            'kd_dikti' => $kd_dikti,
            'kd_fakultas' => $kd_fakultas,
            'param_search' => $param_search,
            'sts_aktif' => $sts_aktif,
            'no_page' => $no_page,
            'jml_record_perpage' => $jml_record_perpage
        ]);
        Log::info('====================================================');

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

            Log::info('Method 1 result count:', ['count' => count($result)]);

            if (!empty($result)) {
                return $result;
            }
        } catch (\Exception $e) {
            Log::error('Method 1 failed: ' . $e->getMessage());
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

            Log::info('Method 2 (without SELECT *) result:', ['count' => count($result), 'result' => $result]);

            if (!empty($result)) {
                return $result;
            }
        } catch (\Exception $e) {
            Log::error('Method 2 failed: ' . $e->getMessage());
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

            Log::info('Method 3 (original) result:', ['count' => count($result), 'result' => $result]);

            if (!empty($result)) {
                return $result;
            }
        } catch (\Exception $e) {
            Log::error('Method 3 failed: ' . $e->getMessage());
        }

        // Fallback: Direct query with proper WHERE conditions
        Log::warning('All DB function methods failed, using direct query fallback...');

        try {
            $query = "SELECT ps.*,
                             f.nama_fakultas,
                             jd.jenjang_didik AS nama_jenjang_didik
                      FROM akademik.program_studi ps
                      LEFT JOIN akademik.fakultas f ON ps.kd_fakultas = f.kd_fakultas
                      LEFT JOIN akademik.jenjang_didik jd ON ps.kd_jenjang_didik = jd.kd_jenjang_didik
                      WHERE 1=1";

            $params = [];

            // Filter by kd_fakultas
            if ($kd_fakultas !== null && $kd_fakultas !== '') {
                $query .= " AND ps.kd_fakultas = ?";
                $params[] = $kd_fakultas;
            }

            // Filter by param_search (nama_program_studi)
            if ($param_search !== null && $param_search !== '') {
                $query .= " AND LOWER(ps.nama_program_studi) LIKE LOWER(?)";
                $params[] = '%' . $param_search . '%';
            }

            // Filter by sts_aktif
            if ($sts_aktif !== null) {
                $query .= " AND ps.is_data_aktif = ?";
                $params[] = $sts_aktif;
            }

            $query .= " ORDER BY ps.nama_program_studi ASC";

            Log::info('Fallback query:', ['query' => $query, 'params' => $params]);

            $result = DB::select($query, $params);

            Log::info('Fallback query result count:', ['count' => count($result)]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Fallback query error: ' . $e->getMessage());
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
        $id_program_studi,
        $kd_program_studi,
        $nama_program_studi,
        $kd_jenjang_didik,
        $kd_fakultas,
        $karyawan_id_kaprodi,
        $kd_nim,
        $no_urut_prodi_wisuda,
        $sts_kip,
        $kd_dikti,
        $is_s2
    )
    {
        return DB::selectOne("SELECT * FROM akademik.insup_program_studi(?,?,?,?,?,?,?,?,?,?,?)", [
            $id_program_studi,
            $kd_program_studi,
            $nama_program_studi,
            $kd_jenjang_didik,
            $kd_fakultas,
            $karyawan_id_kaprodi,
            $kd_nim,
            $no_urut_prodi_wisuda,
            $sts_kip,
            $kd_dikti,
            $is_s2
        ]);
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
        $sts_aktif = 2
    )
    {
        return DB::select("SELECT * FROM akademik.get_daftar_fakultas(?,?,?,?,?)", [
            $param_search,
            $no_page,
            $jml_record_perpage,
            $kd_fakultas,
            $sts_aktif
        ]);
    }

    public static function get_daftar_jenjang_didik(
        $kd_jenjang_didik = null,
        $param_search = '',
        $no_page = -1,
        $jml_record_perpage = 100
    )
    {
        return DB::select("SELECT * FROM akademik.get_daftar_jenjang_didik(?,?,?,?)", [
            $kd_jenjang_didik,
            $param_search,
            $no_page,
            $jml_record_perpage
        ]);
    }
}
