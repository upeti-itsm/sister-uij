<?php

namespace App\Models\MOODLE_MODEL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CourseMoodle extends Model
{
    use HasFactory;

    protected $connection = "emane";

    public static function get_course_moodle($tahun_akademik = "...", $search = "", $offset = 0, $limit = 0)
    {
        return DB::connection("emane")->select('SELECT * FROM get_course_moodle_v2(:tahun_akademik, :search, :offset, :limit)', [
            'tahun_akademik' => $tahun_akademik,
            'search' => $search,
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    public static function get_enrolment_moodle($shortname, $role = "..", $search = "", $limit = 10, $offset = 0)
    {
        return DB::connection("emane")->select('SELECT * FROM get_enrolment_moodle_by_shortname(:shortname, :role, :search, :limit, :offset)', [
            'shortname' => $shortname,
            'role' => $role,
            'search' => $search,
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    public static function indel_enrolment($id, $shortname = NULL, $username = NULL, $role = NULL)
    {
        return DB::connection("emane")->select('SELECT * FROM indel_enrolment_moodle(:id, :shortname, :username, :role)', [
            'id' => $id,
            'shortname' => $shortname,
            'username' => $username,
            'role' => $role
        ])[0];
    }

    public static function get_course_by_shortname($shortname)
    {
        return DB::connection("emane")->select('SELECT * FROM get_course_moodle_by_shortname(:shortname)', [
            'shortname' => $shortname
        ])[0];
    }

    public static function get_course_moodle_by_username_dosen($username = "all", $search = "", $limit = 10, $offset = -1)
    {
        return DB::connection("emane")->select('SELECT * FROM get_course_moodle_by_username_dosen(:username, :search, :offset, :limit) WHERE tahun_akademik = :tahun_akademik', [
            'username' => $username,
            'search' => $search,
            'limit' => $limit,
            'offset' => $offset,
            'tahun_akademik' => '20212'
        ]);
    }

    public static function get_peserta_kelas_kuliah($jadwal_id, $search = '', $limit = 10, $offset = -1)
    {
        return DB::connection('emane')->select('SELECT * FROM public.get_enrolment_moodle_by_jadwal_id(?,?,?,?)', [
            $jadwal_id, $search, $limit, $offset
        ]);
    }
}
