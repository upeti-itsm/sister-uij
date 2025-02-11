<?php

namespace App\Http\Controllers\DosenPage\Akadmik;

use App\Exports\Absensi\RekapitulasiAbsensiMengajar;
use App\Http\Controllers\Controller;
use App\Models\Absensi\RekapitulasiAbsensiMengajarDosen;
use App\Models\Akademik\Dosen;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class RekapitulasiAbsenMengajarController extends Controller
{
    public function index()
    {
        $menu = "Melihat Rekap Absensi Mengajar";
        return view('dosen_page.akademik.rekapitulasi_absen_mengajar', compact('menu'));
    }

    public function json_rekapitulasi_absen_mengajar(Request $request)
    {
        $request->validate([
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = RekapitulasiAbsensiMengajarDosen::getRekapitulasiByPersonal($request->tgl_awal, $request->tgl_akhir, Session::get('user')->id_personal, $search, $start, $length, '00000');
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function export_pdf($tgl_awal, $tgl_akhir, $search = "")
    {
        $start = explode('-', $tgl_awal);
        $end = explode('-', $tgl_akhir);
        $bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        $data['tgl']['now'] = $tgl->format('d/m/Y H:i');
        $data['tgl']['ttd'] = $tgl->format('d/m/Y');
        $data['tgl']['start'] = $start[2] . ' ' . $bulan[intval($start[1])] . ' ' . $start[0];
        $data['tgl']['end'] = $end[2] . ' ' . $bulan[intval($end[1])] . ' ' . $end[0];
        $rekap = RekapitulasiAbsensiMengajarDosen::getRekapitulasiByPersonal($tgl_awal, $tgl_akhir, Session::get('user')->id_personal, $search);
        $dosen = Dosen::get_dosen_by_id_personal(Session::get('user')->id_personal);
        $pdf = Facade::loadView("dosen_page.akademik.pdf.rekapitulasi_absen_mengajar", compact('rekap', 'data', 'dosen'))->setPaper('a4', 'landscape');
        return $pdf->download('detail_rekap.pdf');
    }

    public function export_excel($tgl_awal, $tgl_akhir)
    {
        return Excel::download(new RekapitulasiAbsensiMengajar($tgl_awal, $tgl_akhir, Session::get('user')->id_personal), 'details_rekap.xlsx');
    }

    public function delete_rekap(Request $request){
        $request->validate([
            'id_rekap' => 'required'
        ]);
        $del = RekapitulasiAbsensiMengajarDosen::delRekapitulasi($request->id_rekap, Session::get('user')->id_personal);
        return response()->json($del, 200);
    }
}
