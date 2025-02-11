<?php

namespace App\Http\Controllers\SuperAdminPage\Moodle;

use App\Http\Controllers\Controller;
use App\Models\Akademik\Mahasiswa;
use App\Models\MOODLE_MODEL\CourseMoodle;
use App\Models\MOODLE_MODEL\Dosen;
use App\Models\SIAKAD_MODEL\TahunAkademik;
use Illuminate\Http\Request;

class EnrolmentController extends Controller
{
    public function daftar_course()
    {
        $menu = "Daftar Course | Moodle";
        $tahun_akademik = TahunAkademik::getTahunAkademik();
        return view('super_admin_page.moodle.course_moodle', compact('menu', 'tahun_akademik'));
    }

    public function json_get_daftar_course(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = CourseMoodle::get_course_moodle($request->tahun_akademik, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function daftar_partisipan($shortname)
    {
        $menu = "Daftar Course | Moodle";
        $course = CourseMoodle::get_course_by_shortname($shortname);
        $dosen = Dosen::get_daftar_dosen();
        $angkatan = Mahasiswa::get_list_angkatan();
        $mahasiswa = Mahasiswa::get_mahasiswa(0, 0, -1, 10, '', 'x', $angkatan[0]->angkatan);
        return view('super_admin_page.moodle.detail_enrolment', compact('menu', 'course', 'dosen', 'angkatan', 'mahasiswa'));
    }

    public function json_get_daftar_enrolment(Request $request)
    {
        $request->validate([
            'shortname' => 'required',
            'role' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = CourseMoodle::get_enrolment_moodle($request->shortname, $request->role, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function add_enrolment(Request $request)
    {
        $request->validate([
            'shortname' => 'required',
            'username' => 'required',
            'role' => 'required'
        ]);
        $data = CourseMoodle::indel_enrolment(0, $request->shortname, $request->username, $request->role);
        return response()->json($data, 200);
    }

    public function delete_enrolment(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $data = CourseMoodle::indel_enrolment($request->id);
        return response()->json($data, 200);
    }

    public function get_mahasiswa_by_angkatan(Request $request)
    {
        $request->validate([
            'angkatan' => 'required'
        ]);
        $mahasiswa = Mahasiswa::get_mahasiswa(0, 0, -1, 10, '', 'x', $request->angkatan);
        return response()->json($mahasiswa);
    }
}
