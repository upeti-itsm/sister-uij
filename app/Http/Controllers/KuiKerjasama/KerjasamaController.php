<?php

namespace App\Http\Controllers\KuiKerjasama;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\Kerjasama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class KerjasamaController extends Controller
{
    public function index(){
        $menu = "Daftar Kerjasama";
        $institusi = Kerjasama::get_level_institusi();
        return view('kui_kerjasama.kerjasama.index', compact('menu', 'institusi'));
    }

    public function json_get_daftar_kerjasama(Request $request)
    {
        $request->validate([
            'level' => 'required',
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Kerjasama::get_daftar_kerjasama($request->level, $search, $start, $length);
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
            'lembaga_mitra' => 'required',
            'tingkat_kerjasama' => 'required',
            'bentuk_kegiatan' => 'required',
            'tgl_kegiatan' => 'required',
            'masa_berlaku' => 'required',
            'tingkatan_level' => 'required',
            'link_dokumen' => 'required',
            'bukti_kerjasama' => 'required',
            'id' => 'required',
        ]);
        $data = Kerjasama::insup($request->lembaga_mitra, $request->tingkat_kerjasama, $request->bentuk_kegiatan, $request->tgl_kegiatan, $request->masa_berlaku, $request->tingkatan_level, $request->link_dokumen, $request->bukti_kerjasama, $request->id);
        Session::flash($data->status == 1 ? 'success_message' : 'failed_message', $data->keterangan);
        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $data = Kerjasama::delete_kerjasama($request->id);
        Session::flash($data->status == 1 ? 'success_message' : 'failed_message', $data->keterangan);
        return redirect()->back();
    }
}
