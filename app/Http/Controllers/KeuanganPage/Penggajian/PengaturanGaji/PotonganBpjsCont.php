<?php

namespace App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\RiwayatPotonganBpjs;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class PotonganBpjsCont extends Controller
{
    public function index()
    {
        $menu = 'Pengaturan Gaji - Potongan BPJS';
        return view('keuangan_page.penggajian.pengaturan_gaji.potongan_bpjs.potongan_bpjs', compact('menu'));
    }

    public function json_get_daftar_potongan_bpjs(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = RiwayatPotonganBpjs::get_daftar_potongan_bpjs($request->bulan, $request->tahun, $start, $length);
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
        $collection = Excel::toArray(new \App\Imports\Organisasi\RiwayatPotonganBpjs(), $request->file('file'));
        return response()->json($collection[0]);
    }

    public function insert_potongan(Request $request)
    {
        $request->validate([
            'id_karyawan' => 'required',
            'tunjangan' => 'required',
            'potongan_kesehatan' => 'required',
            'potongan_ketenagakerjaan' => 'required',
            'periode' => 'required',
            'tahun' => 'required'
        ]);
        $result = RiwayatPotonganBpjs::insert_potongan_bpjs($request->id_karyawan, $request->potongan_kesehatan, $request->potongan_ketenagakerjaan, $request->tunjangan, $request->periode, $request->tahun);
        return response()->json($result);
    }

    public function get_detail_potongan($bulan, $tahun)
    {
        $menu = 'Pengaturan Gaji - Potongan BPJS';
        return view('keuangan_page.penggajian.pengaturan_gaji.potongan_bpjs.detail_potongan_bpjs', compact('menu', 'bulan', 'tahun'));
    }

    public function json_get_detail_potongan_bpjs(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = RiwayatPotonganBpjs::get_detail_potongan_bpjs($request->bulan, $request->tahun, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function update_potongan(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'nominal' => 'required',
            'tunjangan' => 'required',
            'kerja' => 'required'
        ]);
        $result = RiwayatPotonganBpjs::update_potongan_bpjs($request->id, $request->nominal, $request->kerja, $request->tunjangan);
        return response()->json($result);
    }

    public function delete_potongan(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $result = RiwayatPotonganBpjs::delete_potongan_bpjs($request->id);
        return response()->json($result);
    }

    public function export_pdf_detail_potongan($periode, $tahun)
    {
        $rekap = RiwayatPotonganBpjs::get_detail_potongan_bpjs($periode, $tahun);
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        $data['tgl']['now'] = $tgl->format('d/m/Y H:i');
        $data['tgl']['ttd'] = $tgl->format('d F Y');
        $data['rekap'] = $rekap;
        $data['bulan'] = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        if (sizeof($rekap) <= 0) {
            Session::flash('failed_message', 'Tidak Ditemukan Potongan BPJS Pada Bulan ' . $data['bulan'][$periode - 1] . ' Tahun ' . $tahun);
            return redirect()->back();
        }
        $pdf = Facade::loadView("keuangan_page.penggajian.pdf.potongan_bpjs", compact('data'))->setPaper('legal', 'portrait');
        return $pdf->download($data['rekap'][0]->nama_potongan . '.pdf');
    }
}
