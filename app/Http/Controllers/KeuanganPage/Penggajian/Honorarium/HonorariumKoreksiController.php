<?php

namespace App\Http\Controllers\KeuanganPage\Penggajian\Honorarium;

use App\Exports\Akademik\RekapInternalHonorariumKoreksi;
use App\Http\Controllers\Controller;
use App\Models\Akademik\Semester;
use App\Models\Organisasi\HonorariumKoreksi;
use App\Models\Organisasi\Karyawan;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class HonorariumKoreksiController extends Controller
{
    public static function index()
    {
        $menu = "Pengelolaan Honorarium Koreksi";
        $data = HonorariumKoreksi::get_informasi_periode_honor_koreksi_terakhir();
        if (sizeof($data) > 0) {
            $tahun = $data[0]->tahun;
            $bulan = $data[0]->bulan;
        } else {
            $tgl = Carbon::now('Asia/Jakarta');
            $tahun = $tgl->year;
            $bulan = $tgl->month;
        }
        return view('keuangan_page.penggajian.honorarium.honorarium_koreksi.daftar', compact('menu', 'bulan', 'tahun'));
    }

    public function json(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = HonorariumKoreksi::get_daftar_honorarium_koreksi('00000000-0000-0000-0000-000000000000', $request->bulan, $request->tahun, $search, $start, $length, "1,2,3,6,7,4");
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function set_honorarium()
    {
        $gajiBulanan = HonorariumKoreksi::set_honorarium_koreksi();
        return response()->json($gajiBulanan);
    }

    public function detail($id_honorarium)
    {
        $rekap = HonorariumKoreksi::get_detail_honorarium_koreksi($id_honorarium);
        $karyawan = Karyawan::get_detail_karyawan_by_id_personal($rekap->id_personal);
        $menu = "Pengelolaan Honorarium Koreksi";
        return view('keuangan_page.penggajian.honorarium.honorarium_koreksi.detail', compact('menu', 'rekap', 'karyawan'));
    }

    public function slip_gaji($id_honorarium)
    {
        $rekap = HonorariumKoreksi::get_detail_honorarium_koreksi($id_honorarium);
        $karyawan = Karyawan::get_detail_karyawan_by_id_personal($rekap->id_personal);
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        $data['tgl']['now'] = $tgl->format('d/m/Y H:i');
        $data['tgl']['ttd'] = $tgl->format('d F Y');
        $data['karyawan'] = $karyawan;
        $data['rekap'] = $rekap;
        $pdf = Facade::loadView("keuangan_page.penggajian.honorarium.pdf.slip_honor_koreksi", compact('data'))->setPaper('legal', 'portrait');
        return $pdf->download('slip_honor_koreksi_' . $karyawan->nama . '.pdf');
    }

    public function generate_ulang_honor($id_karyawan)
    {
        $response = HonorariumKoreksi::generate_ulang_honor_koreksi($id_karyawan);
        if ($response->status == 1) {
            Session::flash('success_message', $response->keterangan);
            return redirect(route('keuangan.penggajian.honorarium.honorarium_koreksi.index'));
        } else {
            Session::flash('failed_message', $response->keterangan);
            return redirect()->back();
        }
    }

    public function export_pdf_for_rekap($ta)
    {
        $rekap = HonorariumKoreksi::export_honorarium_for_rekap($ta);
        if (sizeof($rekap) > 0) {
            Carbon::setLocale('id');
            $tgl = Carbon::now('Asia/Jakarta');
            $data['tgl']['now'] = $tgl->format('d/m/Y H:i');
            $data['tgl']['ttd'] = $tgl->format('d F Y');
            $data['rekap'] = $rekap;
            $tahun_akademik = "Undefined";
            $semester = Semester::get_semester(0, 1, $ta);
            if (sizeof($semester) > 0)
                $tahun_akademik = $semester[0]->nama_tahun_akademik;
            $pdf = Facade::loadView("keuangan_page.penggajian.honorarium.pdf.pdf_for_rekap_honor_koreksi", compact('data', 'tahun_akademik'))->setPaper([0, 0, 623.622, 1133.86], 'landscape');
            return $pdf->download('honorarium_koreksi_' . $ta . '.pdf');
        } else {
            Session::flash('failed_message', 'Tidak Ditemukan Data Untuk Di Export');
            return redirect()->back();
        }
    }

    public function export_excel_for_rekap($ta)
    {
        $rekap = HonorariumKoreksi::export_honorarium_for_rekap($ta);
        if (sizeof($rekap) > 0) {
            $tahun_akademik = "Undefined";
            $semester = Semester::get_semester(0, 1, $ta);
            if (sizeof($semester) > 0)
                $tahun_akademik = $semester[0]->nama_tahun_akademik;
            return Excel::download(new RekapInternalHonorariumKoreksi($ta), 'honorarium_koreksi_' . $ta . '.xlsx');
        } else {
            Session::flash('failed_message', 'Tidak Ditemukan Data Untuk Di Export');
            return redirect()->back();
        }
    }
}
