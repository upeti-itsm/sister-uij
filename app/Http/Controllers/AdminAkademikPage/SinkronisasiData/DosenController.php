<?php

namespace App\Http\Controllers\AdminAkademikPage\SinkronisasiData;

use App\Http\Controllers\Controller;
use App\Models\Akademik\Dosen;
use App\Models\Akademik\ProgramStudi;
use App\Models\Ws_Feeder;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function dosen()
    {
        $menu = "Syncron Dosen";
        $program_studi = ProgramStudi::get_program_studi("0", "0", "", true, -1);
        return view('admin_akademik_page.sinkronisasi_data.dosen', compact('menu', 'program_studi'));
    }

    public function json_get_dosen(Request $request)
    {
        $request->validate([
            'prodi' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = Dosen::get_dosen($request->prodi, $start, $length, $search);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function json_get_dosen_feeder($nidn = null)
    {
        if (isset($nidn))
            $data = Ws_Feeder::getData('GetListDosen', "nidn = '" . $nidn . "'", "", 20);
        else
            $data = Ws_Feeder::getData('GetListDosen', "", "nidn ASC", 0, 0);
        return response()->json($data, 200);
    }

    public function json_syncron(Request $request)
    {
        $request->validate([
            'id_dosen' => 'required',
            'jenis_kelamin' => 'required'
        ]);
        $feeder = Ws_Feeder::getData('DetailBiodataDosen', "id_dosen = '" . $request->id_dosen . "'", "", 1, 0);
        if (!is_null($feeder))
            if ($feeder != "")
                $feeder = $feeder[0];
            else
                return response()->json($request, 500);
        else
            return response()->json($feeder, 500);
        $data = Dosen::insup_dosen($request->id_dosen, $feeder['nama_dosen'], $feeder['nidn'], $feeder['nip'], $request->jenis_kelamin, $feeder['id_agama'], $feeder['tempat_lahir'], $feeder['tanggal_lahir'], $feeder['id_status_aktif'],
            $feeder['nama_ibu'], $feeder['nik'], $feeder['npwp'], $feeder['id_jenis_sdm'], $feeder['no_sk_cpns'], $feeder['tanggal_sk_cpns'], $feeder['no_sk_pengangkatan'], $feeder['mulai_sk_pengangkatan'], $feeder['id_lembaga_pengangkatan'],
            $feeder['id_pangkat_golongan'], $feeder['id_sumber_gaji'], $feeder['jalan'], $feeder['dusun'], $feeder['rt'], $feeder['rw'], $feeder['kode_pos'], $feeder['id_wilayah'],
            $feeder['telepon'], $feeder['handphone'], $feeder['email'], $feeder['status_pernikahan'], $feeder['nama_suami_istri'], $feeder['nip_suami_istri'], $feeder['tanggal_mulai_pns'], $feeder['id_pekerjaan_suami_istri']);
        return response()->json($data, 200);
    }
}
