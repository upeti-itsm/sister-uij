<?php

namespace App\Http\Controllers\DosenPage\Akadmik;

use App\Http\Controllers\Controller;
use App\Models\Akademik\Mahasiswa;
use App\Models\Akademik\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PerwalianController extends Controller
{
    public function daftar_mahasiswa(){
        $menu = 'Perwalian - Daftar Mahasiswa';
        $semester = Semester::get_semester();
        return view('dosen_page.akademik.perwalian.list_mahasiswa', compact('menu', 'semester'));
    }

    public function json_daftar_mahasiswa(Request $request)
    {
        $request->validate([
            'semester' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Mahasiswa::get_mahasiswa_by_id_personal_dosen_wali(Session::get('user')->id_personal, 1, $request->semester, $start, $length, $search);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }
}
