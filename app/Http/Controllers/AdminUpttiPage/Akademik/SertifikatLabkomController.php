<?php

namespace App\Http\Controllers\AdminUpttiPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\SertifikatUpeti\PengajuanSertifikat;
use App\Models\SIAKAD_MODEL\tblMahasiswa;
use Illuminate\Http\Request;

class SertifikatLabkomController extends Controller
{
    public function pengajuan_sertifikat()
    {
        $menu = 'Validasi Pengajuan Sertifikat Laboratorium Komputer';
        return view('admin_uptti.akademik.sertifikat_labkom', compact('menu'));
    }

    public function json_pengajuan_sertifikat(Request $request)
    {
        $request->validate([
            'status_pengajuan' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = PengajuanSertifikat::get_daftar_pengajuan_sertifikat('00000000-0000-0000-0000-000000000000', $search, $start, $length, $request->status_pengajuan);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function tolak_pengajuan(Request $request){
        $request->validate([
            'id_pengajuan' => 'required',
            'alasan' => 'required',
        ]);
        $data = PengajuanSertifikat::denied_pengajuan($request->id_pengajuan, $request->alasan);
        if ($data->is_success)
            return response()->json($data, 200);
        else
            return response()->json($data, 500);
    }

    public function accept_pengajuan(Request $request){
        $request->validate([
            'id_pengajuan' => 'required',
            'nim' => 'required',
        ]);
        $nilai = tblMahasiswa::getNilaiLabkom($request->nim);
        $data = PengajuanSertifikat::accept_pengajuan($request->id_pengajuan, $nilai[0]->nilai_huruf, $nilai[0]->nilai_angka);
        if ($data->is_success)
            return response()->json($data, 200);
        else
            return response()->json($data, 500);
    }
}
