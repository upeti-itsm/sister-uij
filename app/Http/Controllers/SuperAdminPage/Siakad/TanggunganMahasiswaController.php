<?php

namespace App\Http\Controllers\SuperAdminPage\Siakad;

use App\Exports\Siakad\TanggunganMahasiswa;
use App\Http\Controllers\Controller;
use App\Models\SIAKAD_MODEL\keu_tblTanggunganMahasiswa;
use App\Models\SIAKAD_MODEL\keu_tblTransaksiMahasiswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TanggunganMahasiswaController extends Controller
{
    public function daftar_tanggungan()
    {
        $menu = "Daftar Tanggungan | Siakad";
        return view('super_admin_page.siakad.tanggungan_mahasiswa', compact('menu'));
    }

    public function json_daftar_tanggungan(Request $request)
    {
        $request->validate([
            'batas' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = keu_tblTanggunganMahasiswa::get_daftar_tanggungan($request->batas, $request->reg, $request->regm, $request->trf, $request->trfm, $search, $length, $start);
        $total_ = keu_tblTanggunganMahasiswa::get_jml_records_daftar_tanggungan($request->batas, $request->reg, $request->regm, $request->trf, $request->trfm, $search);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = sizeof($total_);
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function export_tanggungan($batas, $reg = 'REG', $regm = 'REGM', $trf = 'TRF', $trfm = 'TRFM')
    {
        if ($reg == '-')
            $reg = '';
        if ($regm == '-')
            $regm = '';
        if ($trf == '-')
            $trf = '';
        if ($trfm == '-')
            $trfm = '';
        return Excel::download(new TanggunganMahasiswa($batas, $reg, $regm, $trf, $trfm), 'tanggungan_mahasiswa.xlsx');
    }

    public function json_get_transaki_mahasiswa(Request $request)
    {
        $request->validate([
            'npk' => 'required'
        ]);
        $tanggungan = keu_tblTransaksiMahasiswa::get_transaksi_mahasiswa($request->npk);
        return response()->json($tanggungan, 200);
    }
}
