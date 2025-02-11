<?php

namespace App\Http\Controllers\AdminAkademikPage\Perkuliahan;

use App\Http\Controllers\Controller;
use App\Models\Akademik\KonsentrasiJurusan;
use App\Models\Akademik\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class KonsentrasiJurusanController extends Controller
{
    public function konsentrasi_jurusan()
    {
        $menu = "Konsentrasi Jurusan";
        $program_studi = ProgramStudi::get_program_studi("0", "0", "", true, -1);
        return view('admin_akademik_page.perkuliahan.konsentrasi_jurusan', compact('menu', 'program_studi'));
    }

    public function json_get_konsentrasi_jurusan(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $konsentrasi_jurusan = KonsentrasiJurusan::get_konsentrasi_jurusan($request->id, $request->prodi, $start, $length, $search);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($konsentrasi_jurusan) > 0)
            $data['recordsTotal'] = $konsentrasi_jurusan[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $konsentrasi_jurusan;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function insup(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'kd_prodi' => 'required',
            'nama_konsentrasi' => 'required',
            'tahun_dibuka' => 'required'
        ]);
        $data = KonsentrasiJurusan::insup_konsentrasi_jurusan($request->kd_prodi, $request->nama_konsentrasi, $request->tahun_dibuka, $request->id);
        if ($data->status == 1)
            Session::flash('success_message', $data->keterangan);
        else
            Session::flash('failed_message', $data->keterangan);
        return redirect()->back();
    }

    public function delete(Request $request){
        $request->validate([
            'id' => 'required'
        ]);
        $data = KonsentrasiJurusan::set_status_konsentrasi_jurusan($request->id, false);
        if ($data->status == 1)
            Session::flash('success_message', $data->keterangan);
        else
            Session::flash('failed_message', $data->keterangan);
        return redirect()->back();
    }
}
