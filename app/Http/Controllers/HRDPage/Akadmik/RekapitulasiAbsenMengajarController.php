<?php

namespace App\Http\Controllers\HRDPage\Akadmik;

use App\Exports\Absensi\RekapitulasiAbsensiMengajar;
use App\Exports\Absensi\RekapitulasiAbsensiMengajarHRD;
use App\Http\Controllers\Controller;
use App\Models\Absensi\RekapitulasiAbsensiMengajarDosen;
use App\Models\Akademik\Dosen;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RekapitulasiAbsenMengajarController extends Controller
{
    public function index(){
        $menu = "Pengelolaan Rekap Absensi Mengajar";
        return view('hrd_page.akademik.rekapitulasi_absen_mengajar_hrd', compact('menu'));
    }

    public function detail($id_personal){
        $menu = "Pengelolaan Rekap Absensi Mengajar";
        $dosen = Dosen::get_dosen_by_id_personal($id_personal);
        return view('hrd_page.akademik.rekapitulasi_absen_mengajar', compact('menu', 'dosen'));
    }

    public function json_rekapitulasi_absen_mengajar(Request $request)
    {
        $request->validate([
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = RekapitulasiAbsensiMengajarDosen::getRekapitulasiByPersonalOnHrd($request->tgl_awal, $request->tgl_akhir, $request->id_personal, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function json_rekapitulasi_absen_mengajar_detail(Request $request)
    {
        $request->validate([
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
            'id_personal' => 'required',
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = RekapitulasiAbsensiMengajarDosen::getRekapitulasiByPersonal($request->tgl_awal, $request->tgl_akhir, $request->id_personal, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function export($tgl_awal, $tgl_akhir, $id_personal = NULL){
        return Excel::download(new RekapitulasiAbsensiMengajarHRD($tgl_awal, $tgl_akhir, $id_personal), 'rekap.xlsx');
    }

    public function export_details($tgl_awal, $tgl_akhir, $id_personal){
        return Excel::download(new RekapitulasiAbsensiMengajar($tgl_awal, $tgl_akhir, $id_personal), 'details_rekap.xlsx');
    }
}
