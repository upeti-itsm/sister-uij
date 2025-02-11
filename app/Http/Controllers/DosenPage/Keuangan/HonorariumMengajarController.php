<?php

namespace App\Http\Controllers\DosenPage\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\HonorariumMengajar;
use App\Models\Organisasi\Karyawan;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HonorariumMengajarController extends Controller
{
    public static function index(){
        $menu = "Melihat Honorarium Mengajar";
        $data = HonorariumMengajar::get_informasi_periode_honor_mengajar_terakhir();
        if (sizeof($data) > 0) {
            $tahun = $data[0]->tahun;
            $bulan = $data[0]->bulan;
        } else {
            $tgl = Carbon::now('Asia/Jakarta');
            $tahun = $tgl->year;
            $bulan = $tgl->month;
        }
        return view('dosen_page.keuangan.honorarium_mengajar.daftar', compact('menu', 'bulan', 'tahun'));
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
        $data_ = HonorariumMengajar::get_daftar_honorarium_mengajar(Session::get('user')->id_personal, $request->bulan, $request->tahun, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function detail($id_honorarium)
    {
        $rekap = HonorariumMengajar::get_detail_honorarium_mengajar($id_honorarium);
        $karyawan = Karyawan::get_detail_karyawan_by_id_personal($rekap->id_personal);
        $menu = "Melihat Honorarium Mengajar";
        return view('dosen_page.keuangan.honorarium_mengajar.detail', compact('menu', 'rekap', 'karyawan', 'id_honorarium'));
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
        $pdf = Facade::loadView("dosen_page.keuangan.honorarium_mengajar.slip", compact('data'))->setPaper('letter', 'portrait');
        return $pdf->download('slip_honor_mengajar_' . $karyawan->nama . '.pdf');
    }

    public function ajukan_perbaikan(Request $request){
        $request->validate([
            'id' => 'required',
            'keterangan' => 'required'
        ]);
        $rekap = HonorariumMengajar::set_pengajuan_perbaikan_honor_mengajar($request->id, true, $request->keterangan);
        return response()->json($rekap);
    }
}
