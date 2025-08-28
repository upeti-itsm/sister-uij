<?php

namespace App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\JabatanStruktural;
use App\Models\Organisasi\PengaturanTransportasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PengaturanTransportasiController extends Controller
{
    public function index()
    {
        $menu = 'Pengaturan Transportasi - S2';
        return view('keuangan_page.penggajian.pengaturan_gaji.pengaturan_transportasi', compact('menu'));
    }

    public function json_get_pengaturan()
    {
        $data_ = PengaturanTransportasi::get_pengaturan();
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function edit(Request $request)
    {
        $request->validate([
            'nominal_tunjangan' => 'required'
        ], [
            'nominal_tunjangan.required' => 'Pastikan Nominal Tunjangan Terisi'
        ]);

        $result = PengaturanTransportasi::edit($request->id_jabatan_fungsional, $request->nominal_tunjangan);

        Session::flash($result->status == 1 ? "success_message" : "failed_message", $result->keterangan);
        return redirect()->back();
    }
}
