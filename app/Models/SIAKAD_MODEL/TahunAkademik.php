<?php

namespace App\Models\SIAKAD_MODEL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TahunAkademik extends Model
{
    use HasFactory;

    protected $connection = "siakad";

    public static function getTahunAkademik()
    {
        return DB::connection('siakad')->select("SELECT thnakademik, stsaktif, kdsemester,thnakademik || kdsemester as kdakademik, CASE kdsemester WHEN '1' THEN thnakademik || ' Gasal' WHEN '2' THEN thnakademik || ' Genap' WHEN '3' THEN thnakademik || ' Pendek' END as akademik, COUNT(*) OVER()::integer AS jml_record FROM akademik.kalender WHERE thnmulai > '2009' ORDER BY tglmulai DESC");
    }

    public static function getTahunAkademikAktif()
    {
        return DB::connection('siakad')->select('SELECT * FROM tblTahunAkademik WHERE status_aktif = "AKTIF"')[0];
    }
}
