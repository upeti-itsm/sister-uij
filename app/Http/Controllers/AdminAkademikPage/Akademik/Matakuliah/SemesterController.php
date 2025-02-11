<?php

namespace App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah;

use App\Http\Controllers\Controller;
use App\Models\Akademik\Semester;
use App\Models\SIAKAD_MODEL\TahunAkademik;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        $menu = 'Sinkronisasi Tahun Akademik dengan Siakad';
        return view('admin_akademik_page.akademik.matakuliah.tahun_akademik', compact('menu'));
    }

    public function json_get_daftar(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = Semester::get_semester($start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function json_get_tahun_akademik(Request $request)
    {
        $data = \App\Models\SIAKAD_MODEL\TahunAkademik::getTahunAkademik();
        return response()->json($data, 200);
    }

    public function json_get_tahun_akademik_by_tahun_akademik(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required'
        ]);
        $data = TahunAkademik::getTahunAkademik($request->tahun_akademik);
        return response()->json($data);
    }

    public function json_syncron_data(Request $request)
    {
        $request->validate([
            'fd_id_smt' => 'required',
            'nama_tahun_akademik' => 'required',
            'status_aktif' => 'required',
            'tanggal_awal_perkuliahan' => 'required',
            'tanggal_akhir_perkuliahan' => 'required',
            'tahun_akademik' => 'required',
            'tgl_mulai_krs' => 'required',
            'tgl_selesai_krs' => 'required',
            'tgl_mulai_input_nilai' => 'required',
            'tgl_selesai_input_nilai' => 'required'
        ]);

        $data = Semester::sync_semester_with_siakad($request->fd_id_smt, $request->nama_tahun_akademik, $request->status_aktif,
            $request->tanggal_awal_perkuliahan, $request->tanggal_akhir_perkuliahan, $request->tahun_akademik, $request->tgl_mulai_krs,
            $request->tgl_selesai_krs, $request->tgl_mulai_input_nilai, $request->tgl_selesai_input_nilai);
        if ($data->status)
            return response()->json($data);
        else
            return response()->json($data, 500);
    }
}
