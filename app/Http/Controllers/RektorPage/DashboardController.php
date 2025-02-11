<?php

namespace App\Http\Controllers\RektorPage;

use App\Http\Controllers\Controller;
use App\Models\Absensi\RekapitulasiAbsensiKaryawan;
use App\Models\Akademik\Mahasiswa;
use App\Models\Akademik\ProgramStudi;
use App\Models\Organisasi\Karyawan;
use App\Models\PMB\Pendaftar;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function get_daftar_ulang_tahun(){
        $data['ultah'] = Karyawan::get_daftar_ulang_tahun();
        return response()->json($data);
    }

    public function detail_mahasiswa($status = "x"){
        $menu = "Dashboard";
        $program_studi = ProgramStudi::get_program_studi("0", "0", "", true, -1);
        $angkatan = Mahasiswa::get_list_angkatan();
        return view('rektor_page.dashboard.detail_mahasiswa', compact('menu', 'program_studi', 'angkatan', 'status'));
    }

    public function json_get_daftar_mahasiswa(Request $request)
    {
        $request->validate([
            'prodi' => 'required',
            'angkatan' => 'required',
            'status' => 'required',
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = Mahasiswa::get_mahasiswa($request->prodi, "0", $start, $length, $search, 'x', $request->angkatan, -1, $request->status);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data);
    }

    public function detail_maba($filter = "x"){
        $menu = "Dashboard";
        $program_studi = ProgramStudi::get_program_studi("0", "0", "", true, -1);
        return view('rektor_page.dashboard.detail_maba', compact('menu', 'program_studi', 'filter'));
    }

    public function json_get_daftar_maba(Request $request)
    {
        $request->validate([
            'prodi' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = Pendaftar::get_mahasiswa_baru($request->prodi, $start, $length, $search);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data);
    }

    public function detail_karyawan($filter = "-1"){
        $menu = "Dashboard";
        return view('rektor_page.dashboard.detail_karyawan', compact('menu', 'filter'));
    }

    public function json_get_daftar_karyawan(Request $request)
    {
        $request->validate([
            'status_kehadiran' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = RekapitulasiAbsensiKaryawan::get_detail_karyawan($start, $length, $search, $request->status_kehadiran);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data);
    }
}
