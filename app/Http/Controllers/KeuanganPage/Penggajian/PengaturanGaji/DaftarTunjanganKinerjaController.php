<?php

namespace App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\TunjanganKinerjaDosen;
use Illuminate\Http\Request;

class DaftarTunjanganKinerjaController extends Controller
{
    public function index()
    {
        $menu = 'Pengaturan Gaji - Daftar Tunjangan Kinerja';
        return view('keuangan_page.penggajian.pengaturan_gaji.daftar_tunjangan_kinerja', compact('menu'));
    }

    public function json_get_daftar(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = TunjanganKinerjaDosen::get_tunjangan_kinerja($search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function insup_kinerja(Request $request)
    {
        $request->validate([
            'nilai_kinerja' => 'required',
            'nominal_kinerja' => 'required',
            'keterangan' => 'required'
        ], [
            'nilai_kinerja.required' => 'Pastikan Nilai Kinerja Terisi',
            'nominal_kinerja.required' => 'Pastikan Nominal Tunjangan Terisi',
            'keterangan.required' => 'Pastikan Keterangan Terisi',
        ]);
        $result = TunjanganKinerjaDosen::insup_tunjangan_kinerja($request->nilai_kinerja, $request->keterangan, $request->nominal_kinerja);
        return response()->json($result);
    }

}
