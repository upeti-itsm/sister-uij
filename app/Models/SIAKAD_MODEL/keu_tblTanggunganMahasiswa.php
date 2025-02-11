<?php

namespace App\Models\SIAKAD_MODEL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class keu_tblTanggunganMahasiswa extends Model
{
    use HasFactory;

    protected $connection = "siakad";

    public static function get_daftar_tanggungan($batas = 0, $reg = 'REG', $regm = 'REGM', $trf = 'TRF', $trfm = 'TRFM', $search = "", $limit = -1, $offset = 0)
    {
        if ($limit != -1)
            return DB::connection("siakad")->select('SELECT t1.NPK, tm.nama_lengkap, tm.jenis_pendanaan, GROUP_CONCAT(CONCAT(t1.kode_biaya," (",t1.semester, ")") SEPARATOR " - ") AS kode, tm.angkatan, tm.jenis_kelas, SUM(biaya-potongan) as total_tanggungan, SUM(total_bayar) as total_bayar, SUM(biaya-potongan-total_bayar) as sisa_tanggungan FROM keu_tblTanggunganMahasiswa t1 JOIN tblMahasiswa tm ON t1.NPK = tm.NPK WHERE tm.status_aktif LIKE "A%" AND (LOWER(tm.nama_lengkap) LIKE CONCAT("%", LOWER(:nama), "%") OR t1.NPK = :npk) AND tm.jenis_kelas IN (:reg, :regm, :trf, :trfm) GROUP BY t1.NPK, tm.nama_lengkap, tm.angkatan, tm.jenis_kelas, tm.jenis_pendanaan HAVING SUM(biaya-potongan-total_bayar) >= :batas ORDER BY SUM(biaya-potongan-total_bayar) DESC LIMIT :limit OFFSET :offset', [
                'batas' => $batas,
                'nama' => $search,
                'npk' => $search,
                'limit' => $limit,
                'offset' => $offset,
                'reg' => $reg,
                'regm' => $regm,
                'trf' => $trf,
                'trfm' => $trfm,
            ]);
        else
            return DB::connection("siakad")->select('SELECT t1.NPK, tm.nama_lengkap, tm.jenis_pendanaan, GROUP_CONCAT(CONCAT(t1.kode_biaya," (",t1.semester, ")") SEPARATOR " - ") AS kode, tm.angkatan, tm.jenis_kelas, SUM(biaya-potongan) as total_tanggungan, SUM(total_bayar) as total_bayar, SUM(biaya-potongan-total_bayar) as sisa_tanggungan FROM keu_tblTanggunganMahasiswa t1 JOIN tblMahasiswa tm ON t1.NPK = tm.NPK WHERE tm.status_aktif LIKE "A%" AND (LOWER(tm.nama_lengkap) LIKE CONCAT("%", LOWER(:nama), "%") OR t1.NPK = :npk) AND tm.jenis_kelas IN (:reg, :regm, :trf, :trfm) GROUP BY t1.NPK, tm.nama_lengkap, tm.angkatan, tm.jenis_kelas, tm.jenis_pendanaan HAVING SUM(biaya-potongan-total_bayar) >= :batas ORDER BY SUM(biaya-potongan-total_bayar) DESC', [
                'batas' => $batas,
                'nama' => $search,
                'npk' => $search,
                'reg' => $reg,
                'regm' => $regm,
                'trf' => $trf,
                'trfm' => $trfm,
            ]);
    }

    public static function get_daftar_tanggungan_suspend($batas = 0, $angkatan = 'all', $jenis_kelas = 'all')
    {
        if ($angkatan == 'all') {
            if ($jenis_kelas == 'malam')
                return DB::connection("siakad")->select('SELECT t1.NPK, tm.nama_lengkap, GROUP_CONCAT(CONCAT(t1.kode_biaya," (",t1.semester, ")") SEPARATOR " - ") AS kode, tm.angkatan, tm.jenis_kelas, SUM(biaya-potongan) as total_tanggungan, SUM(total_bayar) as total_bayar, SUM(biaya-potongan-total_bayar) as sisa_tanggungan FROM keu_tblTanggunganMahasiswa t1 JOIN tblMahasiswa tm ON t1.NPK = tm.NPK WHERE tm.status_aktif LIKE "A%" AND tm.jenis_kelas IN ("REGM", "TRFM") GROUP BY t1.NPK, tm.nama_lengkap, tm.angkatan, tm.jenis_kelas HAVING SUM(biaya-potongan-total_bayar) >= :batas ORDER BY SUM(biaya-potongan-total_bayar) DESC', [
                    'batas' => $batas,
                ]);
            elseif ($jenis_kelas == 'pagi')
                return DB::connection("siakad")->select('SELECT t1.NPK, tm.nama_lengkap, GROUP_CONCAT(CONCAT(t1.kode_biaya," (",t1.semester, ")") SEPARATOR " - ") AS kode, tm.angkatan, tm.jenis_kelas, SUM(biaya-potongan) as total_tanggungan, SUM(total_bayar) as total_bayar, SUM(biaya-potongan-total_bayar) as sisa_tanggungan FROM keu_tblTanggunganMahasiswa t1 JOIN tblMahasiswa tm ON t1.NPK = tm.NPK WHERE tm.NPK NOT LIKE "231%" AND tm.status_aktif LIKE "A%" AND tm.jenis_kelas IN ("REG", "TRF") GROUP BY t1.NPK, tm.nama_lengkap, tm.angkatan, tm.jenis_kelas HAVING SUM(biaya-potongan-total_bayar) >= :batas ORDER BY SUM(biaya-potongan-total_bayar) DESC', [
                    'batas' => $batas,
                ]);
            else
                return DB::connection("siakad")->select('SELECT t1.NPK, tm.nama_lengkap, GROUP_CONCAT(CONCAT(t1.kode_biaya," (",t1.semester, ")") SEPARATOR " - ") AS kode, tm.angkatan, tm.jenis_kelas, SUM(biaya-potongan) as total_tanggungan, SUM(total_bayar) as total_bayar, SUM(biaya-potongan-total_bayar) as sisa_tanggungan FROM keu_tblTanggunganMahasiswa t1 JOIN tblMahasiswa tm ON t1.NPK = tm.NPK WHERE tm.NPK NOT LIKE "231%" AND tm.status_aktif LIKE "A%" AND tm.jenis_kelas IN ("REG", "REGM", "TRF", "TRFM") GROUP BY t1.NPK, tm.nama_lengkap, tm.angkatan, tm.jenis_kelas HAVING SUM(biaya-potongan-total_bayar) >= :batas ORDER BY SUM(biaya-potongan-total_bayar) DESC', [
                    'batas' => $batas,
                ]);
        } else {
            if ($jenis_kelas == 'malam')
                return DB::connection("siakad")->select('SELECT t1.NPK, tm.nama_lengkap, GROUP_CONCAT(CONCAT(t1.kode_biaya," (",t1.semester, ")") SEPARATOR " - ") AS kode, tm.angkatan, tm.jenis_kelas, SUM(biaya-potongan) as total_tanggungan, SUM(total_bayar) as total_bayar, SUM(biaya-potongan-total_bayar) as sisa_tanggungan FROM keu_tblTanggunganMahasiswa t1 JOIN tblMahasiswa tm ON t1.NPK = tm.NPK WHERE tm.NPK NOT LIKE "231%" AND tm.status_aktif LIKE "A%" AND tm.jenis_kelas IN ("REGM", "TRFM") AND tm.angkatan = :angkatan GROUP BY t1.NPK, tm.nama_lengkap, tm.angkatan, tm.jenis_kelas HAVING SUM(biaya-potongan-total_bayar) >= :batas ORDER BY SUM(biaya-potongan-total_bayar) DESC', [
                    'batas' => $batas,
                    'angkatan' => $angkatan
                ]);
            elseif ($jenis_kelas == 'pagi')
                return DB::connection("siakad")->select('SELECT t1.NPK, tm.nama_lengkap, GROUP_CONCAT(CONCAT(t1.kode_biaya," (",t1.semester, ")") SEPARATOR " - ") AS kode, tm.angkatan, tm.jenis_kelas, SUM(biaya-potongan) as total_tanggungan, SUM(total_bayar) as total_bayar, SUM(biaya-potongan-total_bayar) as sisa_tanggungan FROM keu_tblTanggunganMahasiswa t1 JOIN tblMahasiswa tm ON t1.NPK = tm.NPK WHERE tm.NPK NOT LIKE "231%" AND tm.status_aktif LIKE "A%" AND tm.jenis_kelas IN ("REG", "TRF") AND tm.angkatan = :angkatan GROUP BY t1.NPK, tm.nama_lengkap, tm.angkatan, tm.jenis_kelas HAVING SUM(biaya-potongan-total_bayar) >= :batas ORDER BY SUM(biaya-potongan-total_bayar) DESC', [
                    'batas' => $batas,
                    'angkatan' => $angkatan
                ]);
            else
                return DB::connection("siakad")->select('SELECT t1.NPK, tm.nama_lengkap, GROUP_CONCAT(CONCAT(t1.kode_biaya," (",t1.semester, ")") SEPARATOR " - ") AS kode, tm.angkatan, tm.jenis_kelas, SUM(biaya-potongan) as total_tanggungan, SUM(total_bayar) as total_bayar, SUM(biaya-potongan-total_bayar) as sisa_tanggungan FROM keu_tblTanggunganMahasiswa t1 JOIN tblMahasiswa tm ON t1.NPK = tm.NPK WHERE tm.NPK NOT LIKE "231%" AND tm.status_aktif LIKE "A%" AND tm.jenis_kelas IN ("REG", "REGM", "TRF", "TRFM") AND tm.angkatan = :angkatan GROUP BY t1.NPK, tm.nama_lengkap, tm.angkatan, tm.jenis_kelas HAVING SUM(biaya-potongan-total_bayar) >= :batas ORDER BY SUM(biaya-potongan-total_bayar) DESC', [
                    'batas' => $batas,
                    'angkatan' => $angkatan
                ]);
        }
    }

    public static function get_jml_records_daftar_tanggungan($batas = 0, $reg = 'REG', $regm = 'REGM', $trf = 'TRF', $trfm = 'TRFM', $search = "")
    {
        return DB::connection("siakad")->select('SELECT t1.NPK FROM keu_tblTanggunganMahasiswa t1 JOIN tblMahasiswa tm ON t1.NPK = tm.NPK WHERE tm.status_aktif LIKE "A%" AND (LOWER(tm.nama_lengkap) LIKE CONCAT("%", LOWER(:nama), "%") OR t1.NPK = :npk) AND tm.jenis_kelas IN (:reg, :regm, :trf, :trfm) GROUP BY t1.NPK, tm.nama_lengkap, tm.angkatan, tm.jenis_kelas HAVING SUM(biaya-potongan-total_bayar) > :batas', [
            'batas' => $batas,
            'nama' => $search,
            'npk' => $search,
            'reg' => $reg,
            'regm' => $regm,
            'trf' => $trf,
            'trfm' => $trfm,
        ]);
    }

    public static function getTanggunganMahasiswaByNPK($npk)
    {
        return DB::connection("siakad")->select('SELECT t1.NPK, tm.nama_lengkap, GROUP_CONCAT(CONCAT(t1.kode_biaya," (",t1.semester, ")") SEPARATOR " - ") AS kode, tm.angkatan, tm.jenis_kelas, SUM(biaya-potongan) as total_tanggungan, SUM(total_bayar) as total_bayar, SUM(biaya-potongan-total_bayar) as sisa_tanggungan FROM keu_tblTanggunganMahasiswa t1 JOIN tblMahasiswa tm ON t1.NPK = tm.NPK WHERE tm.NPK = :npk GROUP BY t1.NPK, tm.nama_lengkap, tm.angkatan, tm.jenis_kelas ORDER BY SUM(biaya-potongan-total_bayar) DESC', [
            'npk' => $npk,
        ]);
    }
}
