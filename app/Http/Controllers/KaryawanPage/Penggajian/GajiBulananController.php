<?php

namespace App\Http\Controllers\KaryawanPage\Penggajian;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\Karyawan;
use App\Models\Organisasi\RekapitulasiGajiBulanan;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GajiBulananController extends Controller
{
    public function index(){
        $menu = "Melihat Gaji Bulanan";
        return view('karyawan_page.penggajian.daftar_gaji_bulanan', compact('menu'));
    }

    public function json_get_rekapitulasi_gaji_bulanan(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = RekapitulasiGajiBulanan::get_daftar_gaji_bulanan(Session::get('user')->id_personal, $request->bulan, $request->tahun, $search, $start, $length, "1,4,5,6,7,8");
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function slip_gaji($id_rekap)
    {
        $rekap = RekapitulasiGajiBulanan::get_detail_gaji_bulanan_karyawan($id_rekap);
        $karyawan = Karyawan::get_detail_karyawan_by_id_personal(Session::get('user')->id_personal);
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        $data['tgl']['now'] = $tgl->format('d/m/Y H:i');
        $data['tgl']['ttd'] = $tgl->format('d F Y');
        $data['karyawan'] = $karyawan;
        $data['rekap'] = $rekap;
        $pdf = Facade::loadView("karyawan_page.penggajian.pdf.slip_gaji", compact('data'))->setPaper('legal', 'portrait');
        return $pdf->download('slip_' . $karyawan->nama . '.pdf');
    }

    public function detail($id_rekap)
    {
        $rekap = RekapitulasiGajiBulanan::get_detail_gaji_bulanan_karyawan($id_rekap);
        $karyawan = Karyawan::get_detail_karyawan_by_id_personal(Session::get('user')->id_personal);
        $menu = "Melihat Gaji Bulanan";
        return view('karyawan_page.penggajian.detail_gaji_bulanan', compact('menu', 'rekap', 'karyawan', 'id_rekap'));
    }

    public function ajukan_perbaikan(Request $request){
        $request->validate([
            'id' => 'required',
            'keterangan' => 'required'
        ]);
        $rekap = RekapitulasiGajiBulanan::set_pengajuan_perbaikan_gaji_karyawan($request->id, true, $request->keterangan);
        return response()->json($rekap);
    }
}
