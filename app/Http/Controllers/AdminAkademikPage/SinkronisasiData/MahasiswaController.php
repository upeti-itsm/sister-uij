<?php

namespace App\Http\Controllers\AdminAkademikPage\SinkronisasiData;

use App\Http\Controllers\Controller;
use App\Models\Akademik\Mahasiswa;
use App\Models\Akademik\ProgramStudi;
use App\Models\Ws_Feeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MahasiswaController extends Controller
{
    public function mahasiswa()
    {
        $menu = 'Syncron Mahasiswa';
        $program_studi = ProgramStudi::get_program_studi("0", "0", "", true, -1);
        return view('admin_akademik_page.sinkronisasi_data.mahasiswa', compact('menu', 'program_studi'));
    }

    public function json_get_mahasiswa(Request $request)
    {
        $request->validate([
            'prodi' => 'required',
            'semester' => 'required',
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = Mahasiswa::get_mahasiswa($request->prodi, "0", $request->semester, $start, $length, $search);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function json_get_mahasiswa_feeder($nim = null)
    {
        if (isset($nim))
            $data = Ws_Feeder::getData('GetListMahasiswa', "nim = '" . $nim . "'", "", 20);
        else
            $data = Ws_Feeder::getData('GetListMahasiswa', "", "nim ASC", 0, 0);
        return response()->json($data, 200);
    }

    public function json_syncron(Request $request)
    {
        $request->validate([
            'nim' => 'required',
            'id_perguruan_tinggi' => 'required',
            'id_mahasiswa' => 'required',
            'id_prodi' => 'required',
            'id_periode' => 'required',
            'id_registrasi_mahasiswa' => 'required',
            'nama_status_mahasiswa' => 'required',
        ]);
        $feeder = Ws_Feeder::getData('GetBiodataMahasiswa', "id_mahasiswa = '" . $request->id_mahasiswa . "'", "", 1, 0);
        if (!is_null($feeder))
            if ($feeder != "") {
                $feeder = $feeder[0];
            } else
                return response()->json($request, 500);
        else
            return response()->json($feeder, 500);
        $data = Mahasiswa::insup_mahasiswa($request->nim, $feeder['nisn'], $request->id_prodi, $request->id_mahasiswa, $request->nama_status_mahasiswa,
            $request->id_perguruan_tinggi, $request->id_registrasi_mahasiswa, $request->id_periode, $feeder['nama_mahasiswa'],
            $feeder['jenis_kelamin'], $feeder['tempat_lahir'], $feeder['tanggal_lahir'], $feeder['id_agama'], $feeder['nik'],
            $feeder['npwp'], $feeder['id_negara'], $feeder['jalan'], $feeder['dusun'], $feeder['rt'], $feeder['rw'], $feeder['kelurahan'], $feeder['kode_pos'],
            $feeder['id_wilayah'], $feeder['id_jenis_tinggal'], $feeder['id_alat_transportasi'], $feeder['telepon'],
            $feeder['handphone'], $feeder['email'], $feeder['penerima_kps'], $feeder['nomor_kps'], $feeder['nik_ayah'], $feeder['nama_ayah'],
            $feeder['tanggal_lahir_ayah'], $feeder['id_pendidikan_ayah'],
            $feeder['id_pekerjaan_ayah'], $feeder['id_penghasilan_ayah'], $feeder['nik_ibu'],
            $feeder['nama_ibu'], $feeder['tanggal_lahir_ibu'], $feeder['id_pendidikan_ibu'],
            $feeder['id_pekerjaan_ibu'], $feeder['id_penghasilan_ibu'],
            $feeder['nama_wali'], $feeder['tanggal_lahir_wali'], $feeder['id_pendidikan_wali'],
            $feeder['id_pekerjaan_wali'], $feeder['id_penghasilan_wali'], $feeder['id_kebutuhan_khusus_mahasiswa'], $feeder['id_kebutuhan_khusus_ayah'], $feeder['id_kebutuhan_khusus_ibu']);
        if ($data->status)
            return response()->json($data, 200);
        else
            return response()->json($data, 500);
    }

    public function json_perbandingan_data_feeder(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'required'
        ]);
        $data['sipadu'] = Mahasiswa::get_mahasiswa($request->id_mahasiswa);
        $data['feeder'] = Ws_Feeder::getData('GetListMahasiswa', "id_mahasiswa = '" . $request->id_mahasiswa . "'");
        return response()->json($data);
    }

    public function sync_data($id = null)
    {
        if (isset($id)) {
            $feeder = Ws_Feeder::getData('GetListMahasiswa', "id_mahasiswa = '" . $id . "'")[0];
            $data = Mahasiswa::insup_mahasiswa();
            if ($data->status == 1)
                Session::flash('success_message', $data->keterangan);
            else
                Session::flash('failed_message', $data->keterangan);
        } else {
            $feeder = Ws_Feeder::getData('GetListMahasiswa');
            $success['count'] = 0;
            $success['data'] = array();
            $failed['count'] = 0;
            $failed['data'] = array();
            foreach ($feeder as $item) {
                $data = Mahasiswa::insup_mahasiswa();
                if ($data->status == 1) {
                    $success['count']++;
                    $prodi['nim'] = $item["nim"];
                    $prodi['keterangan'] = $data->keterangan;
                    array_push($success['data'], $prodi);
                } else {
                    $failed['count']++;
                    $prodi['nim'] = $item["nim"];
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
