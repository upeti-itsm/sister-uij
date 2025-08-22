<?php

namespace App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\RiwayatInsentifLainnya;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class InsentifLainnyaController extends Controller
{
    public function index()
    {
        $menu = 'Pengaturan Gaji - Insentif Lainnya';
        return view('keuangan_page.penggajian.pengaturan_gaji.insentif_lainnya.insentif_lainnya', compact('menu'));
    }

    public function json_get_daftar_insentif_lainnya(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = RiwayatInsentifLainnya::get_daftar_insentif_lainnya($request->bulan, $request->tahun, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function upload(Request $request)
    {
        $collection = Excel::toArray(new \App\Imports\Organisasi\RiwayatInsentifLainnya(), $request->file('file'));
        return response()->json($collection[0]);
    }

    public function insert_insentif(Request $request)
    {
        $request->validate([
            'id_karyawan' => 'required',
            'insentif' => 'required',
            'periode' => 'required',
            'tahun' => 'required',
            'keterangan' => 'required'
        ]);
        $result = RiwayatInsentifLainnya::insert_insentif_lainnya($request->id_karyawan, $request->insentif, $request->periode, $request->tahun, $request->keterangan);
        return response()->json($result);
    }

    public function get_detail_insentif($bulan, $tahun)
    {
        $menu = 'Pengaturan Gaji - Insentif Lainnya';
        return view('keuangan_page.penggajian.pengaturan_gaji.insentif_lainnya.detail_insentif_lainnya', compact('menu', 'bulan', 'tahun'));
    }

    public function json_get_detail_insentif_lainnya(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = RiwayatInsentifLainnya::get_detail_insentif_lainnya($request->bulan, $request->tahun, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function update_insentif(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'nominal' => 'required',
            'keterangan' => 'required'
        ]);
        $result = RiwayatInsentifLainnya::update_insentif_lainnya($request->id, $request->nominal, $request->keterangan);
        return response()->json($result);
    }

    public function delete_insentif(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $result = RiwayatInsentifLainnya::delete_insentif_lainnya($request->id);
        return response()->json($result);
    }

    public function export_pdf_detail_insentif($periode, $tahun)
    {
        $rekap = RiwayatInsentifLainnya::get_detail_insentif_lainnya($periode, $tahun);
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        $data['tgl']['now'] = $tgl->format('d/m/Y H:i');
        $data['tgl']['ttd'] = $tgl->format('d F Y');
        $data['rekap'] = $rekap;
        $data['bulan'] = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        if (sizeof($rekap) <= 0) {
            Session::flash('failed_message', 'Tidak Ditemukan Insentif Lainnya Pada Bulan ' . $data['bulan'][$periode - 1] . ' Tahun ' . $tahun);
            return redirect()->back();
        }
        $pdf = Facade::loadView("keuangan_page.penggajian.pdf.insentif_lainnya", compact('data'))->setPaper('legal', 'portrait');
        return $pdf->download($data['rekap'][0]->nama_insentif . '.pdf');
    }
}
