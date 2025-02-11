<?php

namespace App\Http\Controllers\KeuanganPage\Penggajian\Honorarium;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\HonorariumPengawas;
use App\Models\Organisasi\Karyawan;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HonorariumPengawasController extends Controller
{
    public static function index(){
        $menu = "Pengelolaan Honorarium Pengawas";
        $tgl = Carbon::now();
        $tahun = $tgl->year;
        $bulan = ($tgl->month - 1);
        if ($bulan == 0) {
            $bulan = 12;
            $tahun = $tahun - 1;
        }
        return view('keuangan_page.penggajian.honorarium.honorarium_pengawas.daftar', compact('menu', 'bulan', 'tahun'));
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
        $data_ = HonorariumPengawas::get_daftar_honorarium_pengawas('00000000-0000-0000-0000-000000000000', $request->bulan, $request->tahun, $search, $start, $length, "1,4,5,6,7");
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
        $gajiBulanan = HonorariumPengawas::set_honorarium_pengawas();
        return response()->json($gajiBulanan);
    }

    public function detail($id_honorarium)
    {
        $rekap = HonorariumPengawas::get_detail_honorarium_pengawas($id_honorarium);
        $karyawan = Karyawan::get_detail_karyawan_by_id_personal($rekap->id_personal);
        $menu = "Detail - Honorarium Pengawas";
        return view('keuangan_page.penggajian.honorarium.honorarium_pengawas.detail', compact('menu', 'rekap', 'karyawan'));
    }

    public function slip_gaji($id_honorarium)
    {
        $rekap = HonorariumPengawas::get_detail_honorarium_pengawas($id_honorarium);
        $karyawan = Karyawan::get_detail_karyawan_by_id_personal($rekap->id_personal);
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        $data['tgl']['now'] = $tgl->format('d/m/Y H:i');
        $data['tgl']['ttd'] = $tgl->format('d F Y');
        $data['karyawan'] = $karyawan;
        $data['rekap'] = $rekap;
        $pdf = Facade::loadView("keuangan_page.penggajian.honorarium.pdf.slip_honor_pengawas", compact('data'))->setPaper('legal', 'portrait');
        return $pdf->download('slip_' . $karyawan->nama . '.pdf');
    }
}
