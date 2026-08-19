<?php

namespace App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\JabatanFungsional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DaftarTunjanganFungsionalController extends Controller
{
    public function index()
    {
        $menu = 'Pengaturan Gaji - Daftar Tunjangan Fungsional';
        return view('keuangan_page.penggajian.pengaturan_gaji.daftar_tunjangan_fungsional', compact('menu'));
    }

    public function json(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = JabatanFungsional::get_daftar_jabatan_fungsional($search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data);
    }

    public function insup(Request $request)
    {
        $request->validate([
            'jabatan_fungsional' => 'required',
            'nominal_tunjangan' => 'required'
        ], [
            'jabatan_fungsional.required' => 'Pastikan Jabatan Fungsional Terisi',
            'nominal_tunjangan.required' => 'Pastikan Nominal Tunjangan Terisi'
        ]);
        $result = JabatanFungsional::insup_tunjangan_fungsional($request->jabatan_fungsional, $request->nominal_tunjangan, $request->id_jabatan_fungsional);
        Session::flash($result->status == 1 ? 'success_message' : 'failed_message', $result->keterangan);
        return redirect()->back();
    }

    public function set_active(Request $request)
    {
        $request->validate([
            'id_jafung' => 'required',
            'status' => 'required|boolean'
        ], [
            'id_jafung.required' => 'Pastikan Jabatan Fungsional Terisi',
            'status.required' => 'Pastikan Status Terisi',
            'status.boolean' => 'Pastikan Status Berupa Boolean'
        ]);
        $result = JabatanFungsional::set_active_tunjangan_fungsional($request->id_jafung, $request->status);
        return response()->json($result);
    }
}
