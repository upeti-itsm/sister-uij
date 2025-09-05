<?php

namespace App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\JabatanStruktural;
use App\Models\Organisasi\PengaturanHr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PengaturanHrController extends Controller
{
    public function index()
    {
        $menu = 'Pengaturan HR Mengajar';
        return view('keuangan_page.penggajian.pengaturan_gaji.pengaturan_hr', compact('menu'));
    }

    public function json_get_pengaturan(Request $request)
    {
        $jenis = $request->jenis_jenjang ?? "";
        $data_ = PengaturanHr::get_pengaturan($jenis);
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

        $is_s2 = $request->input_jenis_jenjang !== "S1";

        $result = PengaturanHr::edit(
            $request->id_jabatan_fungsional,
            $request->nominal_tunjangan,
            $is_s2
        );

        Session::flash(
            $result->status == 1 ? "success_message" : "failed_message",
            $result->keterangan
        );

        return redirect()->back();
    }
}
