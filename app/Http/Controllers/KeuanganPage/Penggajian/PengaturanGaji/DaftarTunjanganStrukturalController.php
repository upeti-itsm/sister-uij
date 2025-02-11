<?php

namespace App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\JabatanStruktural;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DaftarTunjanganStrukturalController extends Controller
{
    public function index()
    {
        $menu = 'Pengaturan Gaji - Daftar Tunjangan Struktural';
        return view('keuangan_page.penggajian.pengaturan_gaji.daftar_tunjangan_struktural', compact('menu'));
    }

    public function json_get_daftar(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = JabatanStruktural::get_daftar_jabatan_struktural($search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function insup_jabatan_struktural(Request $request)
    {
        $request->validate([
            'jabatan_struktural' => 'required',
            'nominal_tunjangan' => 'required',
            'sks_ekivalen' => 'required',
        ], [
            'jabatan_struktural.required' => 'Pastikan Jabatan Stuktural Terisi',
            'nominal_tunjangan.required' => 'Pastikan Nominal Tunjangan Terisi',
            'sks_ekivalen.required' => 'Pastikan Ekivalen SKS Terisi'
        ]);
        $result = JabatanStruktural::insup_jabatan_struktural($request->jabatan_struktural, $request->nominal_tunjangan, $request->sks_ekivalen, $request->id_jabatan_struktural);
        Session::flash($result->status == 1 ? "success_message" : "failed_message", $result->keterangan);
        return redirect()->back();
    }
}
