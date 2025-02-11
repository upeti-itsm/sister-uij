<?php

namespace App\Http\Controllers\AdminAkademikPage\Akademik\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Akademik\Mahasiswa;
use App\Models\Akademik\ProgramStudi;
use Illuminate\Http\Request;

class LPPPIController extends Controller
{
    public function index()
    {
        $menu = 'Pengelolaan Mahasiswa LP3I Banyuwangi';
        $program_studi = ProgramStudi::get_program_studi("0", "0", "", true, -1);
        $angkatan = Mahasiswa::get_list_angkatan();
        return view('admin_akademik_page.akademik.mahasiswa.daftar_mahasiswa_lpppi', compact('menu', 'program_studi', 'angkatan'));
    }

    public function json_get_mahasiswa_lpppi(Request $request)
    {
        $request->validate([
            'prodi' => 'required',
            'angkatan' => 'required',
            'is_lp3i' => 'required',
        ]);
        $is_lp3i = -1;
        if ($request->is_lp3i == "true")
            $is_lp3i = 1;
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = Mahasiswa::get_mahasiswa($request->prodi, "0", $start, $length, $search, 'x', $request->angkatan, $is_lp3i);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function update_lp3i(Request $request){
        $request->validate([
            'nim' => 'required',
            'status' => 'required',
        ], [
            'nim.required' => 'Pastikan NIM Terisi',
            'status.required' => 'Pastikan Status Terisi',
        ]);
        $data = Mahasiswa::set_mahasiswa_lp3i($request->nim, $request->status);
        return response()->json($data);
    }
}
