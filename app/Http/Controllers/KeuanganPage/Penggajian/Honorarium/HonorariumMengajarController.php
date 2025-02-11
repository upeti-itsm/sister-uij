<?php

namespace App\Http\Controllers\KeuanganPage\Penggajian\Honorarium;

use App\Exports\Akademik\RekapInternalHonorariumMengajar;
use App\Http\Controllers\Controller;
use App\Models\Organisasi\HonorariumMengajar;
use App\Models\Organisasi\Karyawan;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Webklex\PDFMerger\Facades\PDFMergerFacade;

class HonorariumMengajarController extends Controller
{
    public static function index()
    {
        $menu = "Pengelolaan Honorarium Mengajar";
        $data = HonorariumMengajar::get_informasi_periode_honor_mengajar_terakhir();
        if (sizeof($data) > 0) {
            $tahun = $data[0]->tahun;
            $bulan = $data[0]->bulan;
        } else {
            $tgl = Carbon::now('Asia/Jakarta');
            $tahun = $tgl->year;
            $bulan = $tgl->month;
        }
        return view('keuangan_page.penggajian.honorarium.honorarium_mengajar.daftar', compact('menu', 'bulan', 'tahun'));
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
        $data_ = HonorariumMengajar::get_daftar_honorarium_mengajar('00000000-0000-0000-0000-000000000000', $request->bulan, $request->tahun, $search, $start, $length);
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
        $gajiBulanan = HonorariumMengajar::set_honorarium_mengajar();
        return response()->json($gajiBulanan);
    }

    public function detail($id_honorarium)
    {
        $rekap = HonorariumMengajar::get_detail_honorarium_mengajar($id_honorarium);
        $karyawan = Karyawan::get_detail_karyawan_by_id_personal($rekap->id_personal);
        $menu = "Pengelolaan Honorarium Mengajar";
        return view('keuangan_page.penggajian.honorarium.honorarium_mengajar.detail', compact('menu', 'rekap', 'karyawan'));
    }

    public function slip_gaji($id_honorarium)
    {
        $rekap = HonorariumMengajar::get_detail_honorarium_mengajar($id_honorarium);
        $karyawan = Karyawan::get_detail_karyawan_by_id_personal($rekap->id_personal);
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        $data['tgl']['now'] = $tgl->format('d/m/Y H:i');
        $data['tgl']['ttd'] = $tgl->format('d F Y');
        $data['karyawan'] = $karyawan;
        $data['rekap'] = $rekap;
        $pdf = Facade::loadView("keuangan_page.penggajian.honorarium.pdf.slip_honor_mengajar", compact('data'))->setPaper('letter', 'portrait');
        return $pdf->download('slip_honor_mengajar_' . $karyawan->nama . '.pdf');
    }

    public function generate_ulang_gaji($id_karyawan)
    {
        $response = HonorariumMengajar::generate_ulang_honor_mengajar($id_karyawan);
        if ($response->status == 1) {
            Session::flash('success_message', $response->keterangan);
            return redirect(route('keuangan.penggajian.honorarium.honorarium_mengajar.index'));
        } else {
            Session::flash('failed_message', $response->keterangan);
            return redirect()->back();
        }
    }

    public function export_pdf_for_rekap($periode, $tahun)
    {
        $rekap = HonorariumMengajar::export_honorarium_for_rekap($periode, $tahun);
        if (sizeof($rekap) > 0) {
            Carbon::setLocale('id');
            $tgl = Carbon::now('Asia/Jakarta');
            $data['tgl']['now'] = $tgl->format('d/m/Y H:i');
            $data['tgl']['ttd'] = $tgl->format('d F Y');
            $data['rekap'] = $rekap;
            $data['bulan'] = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            $bulan = "Semua Bulan";
            if ($periode != -1)
                $bulan = $data['bulan'][$periode - 1];
            $pdf = Facade::loadView("keuangan_page.penggajian.honorarium.pdf.pdf_for_rekap_honor_mengajar", compact('data', 'bulan', 'tahun'))->setPaper([0, 0, 623.622, 1133.86], 'landscape');
            return $pdf->download('honorarium_mengajar_' . $bulan . '_' . $tahun . '.pdf');
        } else {
            Session::flash('failed_message', 'Tidak Ditemukan Data Untuk Di Export');
            return redirect()->back();
        }
    }

    public function export_excel_for_rekap($periode, $tahun)
    {
        $rekap = HonorariumMengajar::export_honorarium_for_rekap($periode, $tahun);
        if (sizeof($rekap) > 0) {
            $bulan = "Semua Bulan";
            $data = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            if ($periode != -1)
                $bulan = $data[$periode - 1];
            return Excel::download(new RekapInternalHonorariumMengajar($periode, $tahun), 'honorarium_mengajar_' . $bulan . '_' . $tahun . '.xlsx');
        } else {
            Session::flash('failed_message', 'Tidak Ditemukan Data Untuk Di Export');
            return redirect()->back();
        }
    }

    public function export_zip_rekap_slip($periode, $tahun)
    {
        $rekap = HonorariumMengajar::export_honorarium_for_rekap($periode, $tahun);
        if (sizeof($rekap) > 0) {
            $slip_all = PDFMergerFacade::init();
            $path = public_path('files/temp_slip/');
            $bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            foreach ($rekap as $slip) {
                $data_slip = HonorariumMengajar::get_detail_honorarium_mengajar($slip->id_rekapitulasi_honorarium_dosen_mengajar);
                $karyawan = Karyawan::get_detail_karyawan_by_id_personal($data_slip->id_personal);
                Carbon::setLocale('id');
                $tgl = Carbon::now('Asia/Jakarta');
                $data['tgl']['now'] = $tgl->format('d/m/Y H:i');
                $data['tgl']['ttd'] = $tgl->format('d F Y');
                $data['karyawan'] = $karyawan;
                $data['rekap'] = $data_slip;
                $data['nomor'] = $slip->nomor;
                $pdf = Facade::loadView("keuangan_page.penggajian.honorarium.pdf.slip_honor_mengajar", compact('data'))->setPaper('letter', 'portrait');
                $pdf->save($path . $slip->nomor . '_' . $karyawan->nama . '.pdf');
                $slip_all->addPDF($path . $slip->nomor . '_' . $karyawan->nama . '.pdf');
            }
            $slip_all->merge();
            $slip_all->save($path . 'slip_HR_mengajar_' . $bulan[$periode - 1] . '_' . $tahun . '.pdf');
            foreach ($rekap as $slip) {
                File::delete($path . $slip->nomor . '_' . $karyawan->nama . '.pdf');
            }
            return response()->download($path . 'slip_HR_mengajar_' . $bulan[$periode - 1] . '_' . $tahun . '.pdf')->deleteFileAfterSend();
        } else {
            Session::flash('failed_message', 'Tidak Ditemukan Data Untuk Di Export');
            return redirect()->back();
        }
    }
}
