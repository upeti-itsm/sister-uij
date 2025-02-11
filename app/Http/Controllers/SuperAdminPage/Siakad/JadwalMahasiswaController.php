<?php

namespace App\Http\Controllers\SuperAdminPage\Siakad;

use App\Http\Controllers\Controller;
use App\Models\SIAKAD_MODEL\TahunAkademik;
use App\Models\SIAKAD_MODEL\vwJadwalKuliahMahasiswa;
use Illuminate\Http\Request;

class JadwalMahasiswaController extends Controller
{
    public function vwJadwalMahasiswa()
    {
        $menu = "Jadwal Mahasiswa | Siakad";
        $tahun_akademik = TahunAkademik::getTahunAkademik();
        return view('super_admin_page.siakad.vw_jadwal_mahasiswa', compact('menu', 'tahun_akademik'));
    }

    public function json_vwJadwalMahasiswa(Request $request)
    {
        $request->validate([
            'tahun' => 'required',
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = vwJadwalKuliahMahasiswa::getDaftarVwJadwalKuliahMahasiswa($request->tahun, $search, $length, $start);
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
