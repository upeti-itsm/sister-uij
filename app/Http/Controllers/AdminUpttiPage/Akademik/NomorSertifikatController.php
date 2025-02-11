<?php

namespace App\Http\Controllers\AdminUpttiPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\SertifikatUpeti\NomorSertifikat;
use Illuminate\Http\Request;

class NomorSertifikatController extends Controller
{
    public function index()
    {
        $menu = "Pengelolaan Nomor Sertifikat";
        return view('admin_uptti.akademik.nomor_sertifikat', compact('menu'));
    }

    public function json_nomor_sertifikat(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = NomorSertifikat::get_daftar_nomor_sertifikat($start, $length, $search);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_sertifikat' => 'required'
        ]);
        $data = NomorSertifikat::insup_nomor_sertifikat($request->nomor_sertifikat);
        return response()->json($data, 200);
    }

    public function update(Request $request){
        $request->validate([
            'id' => 'required',
            'nomor_sertifikat' => 'required'
        ]);
        $data = NomorSertifikat::insup_nomor_sertifikat($request->nomor_sertifikat, $request->id);
        return response()->json($data, 200);
    }
}
