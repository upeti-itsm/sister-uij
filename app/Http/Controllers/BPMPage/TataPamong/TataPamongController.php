<?php

namespace App\Http\Controllers\BPMPage\TataPamong;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\TataPamong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TataPamongController extends Controller
{
    public function index()
    {
        $menu = "Tata Pamong";
        $tahun = TataPamong::get_tahun_terbit();
        return view('bpm_page.tata_pamong.index', compact('menu', 'tahun'));
    }

    public function insup(Request $request)
    {
        $request->validate([
            'tahun_terbit' => 'required',
            'nomor_dokumen' => 'required',
            'nama_dokumen' => 'required',
            'deskripsi' => 'required',
            'link_dokumen' => 'required',
            'id' => 'required',
        ]);
        $data = TataPamong::insup_tata_pamong($request->tahun_terbit, $request->nomor_dokumen, $request->nama_dokumen, $request->deskripsi, $request->link_dokumen, Session::get('user')->id_personal, $request->id);
        Session::flash($data->status == 1 ? 'success_message' : 'failed_message', $data->keterangan);
        return redirect()->back();
    }

    public function json_get_daftar_tatapamong(Request $request)
    {
        $request->validate([
            'tahun_terbit' => 'required',
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = TataPamong::daftar_tata_pamong($request->tahun_terbit, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }
}
