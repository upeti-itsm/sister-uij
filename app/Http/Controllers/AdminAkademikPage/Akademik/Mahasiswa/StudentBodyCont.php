<?php

namespace App\Http\Controllers\AdminAkademikPage\Akademik\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Akademik\Mahasiswa;
use App\Models\Akademik\ProgramStudi;
use Illuminate\Http\Request;

class StudentBodyCont extends Controller
{
    public function index()
    {
        $menu = 'Student Body';
        $program_studi = ProgramStudi::get_program_studi("0", "0", "", true, -1);
        $angkatan = Mahasiswa::get_list_angkatan();
        return view('admin_akademik_page.akademik.mahasiswa.student_body', compact('menu', 'program_studi', 'angkatan'));
    }

    public function json(Request $request)
    {
        $request->validate([
            'prodi' => 'required',
            'angkatan' => 'required',
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = Mahasiswa::get_student_body($request->angkatan, $request->prodi);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = 100;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function pendaftar($jenis, $tahun, $prodi)
    {
        $menu = 'Student body';
        $mahasiswa = Mahasiswa::get_detail_student_body($jenis, $tahun, $prodi);
        return view('admin_akademik_page.akademik.mahasiswa.pendaftar', compact('menu', 'tahun', 'jenis', 'mahasiswa'));
    }

    public function maba($jenis, $tahun, $prodi)
    {
        $menu = 'Student body';
        $mahasiswa = Mahasiswa::get_detail_student_body($jenis, $tahun, $prodi);
        return view('admin_akademik_page.akademik.mahasiswa.maba', compact('menu', 'tahun', 'jenis', 'mahasiswa'));
    }

    public function mahasiswa($jenis, $tahun, $prodi)
    {
        $menu = 'Student body';
        $mahasiswa = Mahasiswa::get_detail_student_body($jenis, $tahun, $prodi);
        return view('admin_akademik_page.akademik.mahasiswa.mahasiswa', compact('menu', 'tahun', 'jenis', 'mahasiswa'));
    }
}
