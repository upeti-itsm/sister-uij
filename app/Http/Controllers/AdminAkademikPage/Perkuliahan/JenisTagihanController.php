<?php

namespace App\Http\Controllers\AdminAkademikPage\Perkuliahan;

use App\Http\Controllers\Controller;
use App\Models\Akademik\JenisTagihan;
use Illuminate\Http\Request;

class JenisTagihanController extends Controller
{
    /**
     * Display the main page for managing billing types.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $menu = 'Pengelolaan Jenis Tagihan';
        return view('admin_akademik_page.perkuliahan.jenis_tagihan', compact('menu'));
    }

    /**
     * Fetch billing types in JSON format for DataTables.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function json(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $id_jenis_tagihan = $request->id_jenis_tagihan ?? 0;
        $status_aktif = $request->status_aktif;
        $data_ = JenisTagihan::get_daftar($id_jenis_tagihan, $status_aktif, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_tagihan' => 'required',
            'tipe_periodisasi' => 'required',
            'deskripsi' => 'required'
        ]);

        $status = ($request->status_jenis_tagihan == '1') ? true : false;

        $data = JenisTagihan::insup($request->jenis_tagihan, $request->tipe_periodisasi, $status, $request->deskripsi, 0);

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $request->validate([
            'jenis_tagihan' => 'required',
            'tipe_periodisasi' => 'required',
            'deskripsi' => 'required',
            'id' => 'required',
        ]);

        $data = JenisTagihan::insup($request->jenis_tagihan, $request->tipe_periodisasi, $request->status_jenis_tagihan, $request->deskripsi, $request->id);

        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $data = JenisTagihan::delete_tagihan($request->id);
        return response()->json($data);
    }
}
