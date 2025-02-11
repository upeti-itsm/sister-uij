<?php

namespace App\Http\Controllers\KeuanganPage\Penggajian;

use App\Exports\Organisasi\ExportGajiBulananKaryawanForBank;
use App\Exports\Organisasi\ExportGajiBulananKaryawanForRekap;
use App\Http\Controllers\Controller;
use App\Models\Organisasi\Karyawan;
use App\Models\Organisasi\RekapitulasiGajiBulanan;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class GajiBulananController extends Controller
{
    public function index()
    {
        $menu = "Pengelolaan Gaji Bulanan";
        $data = RekapitulasiGajiBulanan::get_last_gaji();
        if (sizeof($data) > 0) {
            $tahun = $data[0]->tahun;
            $bulan = $data[0]->bulan;
        } else {
            $tgl = Carbon::now('Asia/Jakarta');
            $tahun = $tgl->year;
            $bulan = $tgl->month;
        }
        return view('keuangan_page.penggajian.daftar_gaji_bulanan', compact('menu', 'bulan', 'tahun'));
    }

    public function json_get_rekapitulasi_gaji_bulanan(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
            'is_repair' => 'required',
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = RekapitulasiGajiBulanan::get_daftar_gaji_bulanan('00000000-0000-0000-0000-000000000000', $request->bulan, $request->tahun, $search, $start, $length, "1,4,5,6,7", $request->is_repair == "all" ? NULL : true);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function set_rekapitulasi_bulanan()
    {
        $gajiBulanan = RekapitulasiGajiBulanan::set_rekapitulasi_gaji_bulanan();
        return response()->json($gajiBulanan);
    }

    public function detail($id_rekap)
    {
        $rekap = RekapitulasiGajiBulanan::get_detail_gaji_bulanan_karyawan($id_rekap);
        $karyawan = Karyawan::get_detail_karyawan_by_id_personal($rekap->id_personal);
        $menu = "Pengelolaan Gaji Bulanan";
        return view('keuangan_page.penggajian.detail_gaji_bulanan', compact('menu', 'rekap', 'karyawan'));
    }

    public function slip_gaji($id_rekap)
    {
        $rekap = RekapitulasiGajiBulanan::get_detail_gaji_bulanan_karyawan($id_rekap);
        $karyawan = Karyawan::get_detail_karyawan_by_id_personal($rekap->id_personal);
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        $data['tgl']['now'] = $tgl->format('d/m/Y H:i');
        $data['tgl']['ttd'] = $tgl->format('d F Y');
        $data['karyawan'] = $karyawan;
        $data['rekap'] = $rekap;
        $pdf = Facade::loadView("keuangan_page.penggajian.pdf.slip_gaji", compact('data'))->setPaper('legal', 'portrait');
        return $pdf->download('slip_' . $karyawan->nama . '.pdf');
    }

    public function export_pdf_for_bank($periode, $tahun)
    {
        $rekap = RekapitulasiGajiBulanan::export_gaji_bulanan_for_bank($periode, $tahun);
        if (sizeof($rekap) > 0) {
            if ($rekap[0]->status == 0) {
                Session::flash('failed_message', $rekap[0]->keterangan);
                return redirect()->back();
            }
            Carbon::setLocale('id');
            $tgl = Carbon::now('Asia/Jakarta');
            $data['tgl']['now'] = $tgl->format('d/m/Y H:i');
            $data['tgl']['ttd'] = $tgl->format('d F Y');
            $data['rekap'] = $rekap;
            $data['bulan'] = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            $bulan = "Semua Bulan";
            if ($periode != -1)
                $bulan = $data['bulan'][$periode - 1];
            $pdf = Facade::loadView("keuangan_page.penggajian.pdf.pdf_for_bank", compact('data'))->setPaper('legal', 'portrait');
            return $pdf->download('gaji_' . $bulan . '_' . $tahun . '.pdf');
        } else {
            Session::flash('failed_message', 'Tidak Ditemukan Data Untuk Di Export');
            return redirect()->back();
        }
    }

    public function export_pdf_for_rekap($periode, $tahun)
    {
        $rekap = RekapitulasiGajiBulanan::export_gaji_bulanan_for_rekap($periode, $tahun);
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
            $pdf = Facade::loadView("keuangan_page.penggajian.pdf.pdf_for_rekap", compact('data', 'bulan', 'tahun'))->setPaper([0, 0, 623.622, 1133.86], 'landscape');
            return $pdf->download('gaji_' . $bulan . '_' . $tahun . '.pdf');
        } else {
            Session::flash('failed_message', 'Tidak Ditemukan Data Untuk Di Export');
            return redirect()->back();
        }
    }

    public function export_excel_for_bank($periode, $tahun)
    {
        $rekap = RekapitulasiGajiBulanan::export_gaji_bulanan_for_bank($periode, $tahun);
        if (sizeof($rekap) > 0) {
            if ($rekap[0]->status == 0) {
                Session::flash('failed_message', $rekap[0]->keterangan);
                return redirect()->back();
            }
            $bulan = "Semua Bulan";
            $data = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            if ($periode != -1)
                $bulan = $data[$periode - 1];
            return Excel::download(new ExportGajiBulananKaryawanForBank($periode, $tahun), 'gaji_' . $bulan . '_' . $tahun . '.xlsx');
        } else {
            Session::flash('failed_message', 'Tidak Ditemukan Data Untuk Di Export');
            return redirect()->back();
        }
    }

    public function export_excel_for_rekap($periode, $tahun)
    {
        $rekap = RekapitulasiGajiBulanan::export_gaji_bulanan_for_rekap($periode, $tahun);
        if (sizeof($rekap) > 0) {
            $bulan = "Semua Bulan";
            $data = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            if ($periode != -1)
                $bulan = $data[$periode - 1];
            return Excel::download(new ExportGajiBulananKaryawanForRekap($periode, $tahun), 'gaji_' . $bulan . '_' . $tahun . '.xlsx');
        } else {
            Session::flash('failed_message', 'Tidak Ditemukan Data Untuk Di Export');
            return redirect()->back();
        }
    }

    public function generate_ulang_gaji($id_karyawan)
    {
        $response = RekapitulasiGajiBulanan::generate_ulang_gaji($id_karyawan);
        Session::flash($response->status == 1 ? 'success_message' : 'failed_message', $response->keterangan);
        return redirect(route('keuangan.penggajian.gaji_bulanan.index'));
    }
}
