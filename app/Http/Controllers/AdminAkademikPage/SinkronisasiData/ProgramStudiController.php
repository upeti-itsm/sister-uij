<?php

namespace App\Http\Controllers\AdminAkademikPage\SinkronisasiData;

use App\Http\Controllers\Controller;
use App\Models\Akademik\ProgramStudi;
use App\Models\Ws_Feeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProgramStudiController extends Controller
{
    public function program_studi()
    {
        $menu = "Syncron Program Studi";
        return view('admin_akademik_page.sinkronisasi_data.program_studi', compact('menu'));
    }

    public function json_get_program_studi()
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = ProgramStudi::get_program_studi("0", "0", $search, true, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function json_perbandingan_data_feeder(Request $request)
    {
        $request->validate([
            'id_program_studi' => 'required',
            'kd_prodi' => 'required'
        ]);
        $data['sipadu'] = ProgramStudi::get_program_studi($request->kd_prodi);
        $data['feeder'] = Ws_Feeder::getData('GetProdi', "id_prodi = '" . $request->id_program_studi . "'");
        return response()->json($data);
    }

    public function sync_data($id = null)
    {
        if (isset($id)) {
            $feeder = Ws_Feeder::getData('GetProdi', "id_prodi = '" . $id . "'")[0];
            $data = ProgramStudi::insup_program_studi($feeder['kode_program_studi'], $feeder['nama_program_studi'], $feeder['id_jenjang_pendidikan'], $feeder['nama_jenjang_pendidikan'], $feeder['status'], $id);
            if ($data->status == 1)
                Session::flash('success_message', $data->keterangan);
            else
                Session::flash('failed_message', $data->keterangan);
        } else {
            $feeder = Ws_Feeder::getData('GetProdi');
            $success['count'] = 0;
            $success['data'] = array();
            $failed['count'] = 0;
            $failed['data'] = array();
            foreach ($feeder as $item) {
                $data = ProgramStudi::insup_program_studi($item['kode_program_studi'], $item['nama_program_studi'], $item['id_jenjang_pendidikan'], $item['nama_jenjang_pendidikan'], $item['status'], $item['id_prodi']);
                if ($data->status == 1) {
                    $success['count']++;
                    $prodi['kd_prodi'] = $item["kode_program_studi"];
                    $prodi['keterangan'] = $data->keterangan;
                    array_push($success['data'], $prodi);
                } else {
                    $failed['count']++;
                    $prodi['kd_prodi'] = $item["kode_program_studi"];
                    $prodi['keterangan'] = $data->keterangan;
                    array_push($failed['data'], $prodi);
                }
            }
            Session::flash("info_message", "Success Syncron Data Sejumlah " . $success['count'] . ", Gagal Sejumlah " . $failed['count']);
        }
        return redirect()->back();
    }

    public function insup(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'kd_prodi' => 'required',
            'nama' => 'required',
            'id_jenjang' => 'required',
            'nama_jenjang' => 'required',
            'sts_aktif' => 'required'
        ]);
        $data = ProgramStudi::insup_program_studi($request->kd_prodi, $request->nama, $request->id_jenjang, $request->nama_jenjang, $request->sts_aktif, $request->id);
        if ($data->status == 1)
            Session::flash('success_message', $data->keterangan);
        else
            Session::flash('failed_message', $data->keterangan);
        return redirect()->back();
    }
}
