<?php

namespace App\Models\SIAKAD_MODEL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class TahunAkademik extends Model
{
    use HasFactory;

    protected $connection = "siakad";

    public static function getTahunAkademik($id_semester = null)
    {
        if (isNull($id_semester)) {
            return DB::connection('siakad')->select("SELECT kdsemester AS p_id_semester, thnakademik AS p_nama_semester, stsaktif AS p_is_periode_aktif, tglmulai AS p_tgl_awal_perkuliahan, tglakhirrencanastudi AS p_tgl_akhir_perkuliahan, thnakademik AS p_tahun_akademik, tglmulairencanastudi AS p_tgl_mulai_krs, tglakhirrencanastudi AS p_tgl_akhir_krs, tglmulaipenilaian AS p_tgl_mulai_input_nilai, tglakhirpenilaian AS p_tgl_akhir_input_nilai, CASE kdsemester WHEN '1' THEN 'Gasal' WHEN '2' THEN 'Genap' WHEN '3' THEN 'Pendek' END AS p_nama_semester_teks FROM akademik.kalender;");
        } else {
            return DB::connection('siakad')->select("SELECT kdsemester AS p_id_semester, thnakademik AS p_nama_semester, stsaktif AS p_is_periode_aktif, tglmulai AS p_tgl_awal_perkuliahan, tglakhirrencanastudi AS p_tgl_akhir_perkuliahan, thnakademik AS p_tahun_akademik, 	tglmulairencanastudi AS p_tgl_mulai_krs, tglakhirrencanastudi AS p_tgl_akhir_krs, tglmulaipenilaian AS p_tgl_mulai_input_nilai, tglakhirpenilaian AS p_tgl_akhir_input_nilai, CASE kdsemester WHEN '1' THEN 'Gasal' WHEN '2' THEN 'Genap' WHEN '3' THEN 'Pendek' END AS p_nama_semester_teks FROM akademik.kalender where thnakademik = ?;", [$id_semester]);
        }
    }

    public static function getTahunAkademikAktif()
    {
        return DB::connection('siakad')->select('SELECT * FROM tblTahunAkademik WHERE status_aktif = "AKTIF"')[0];
    }
}
