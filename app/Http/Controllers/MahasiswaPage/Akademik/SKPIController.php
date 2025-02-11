<?php

namespace App\Http\Controllers\MahasiswaPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Kuesioner\DokumenSKPI;
use App\Models\Kuesioner\RekapitulasiKepuasanMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class SKPIController extends Controller
{
    public function index()
    {
        $menu = "Pengisian SPI";
        if (substr(Session::get('user')->nim, 0, 2) != '22') {
            $kuesioner_kepuasan = RekapitulasiKepuasanMahasiswa::cek_status_pengisian(Session::get('user')->id_mhs, 1);
            if ($kuesioner_kepuasan->status == 0)
                return redirect(route('mahasiswa.akademik.kuesioner_kepuasan_mahasiswa.index'));
        }
        return view('mahasiswa_page.akademik.pengisian_skpi', compact('menu'));
    }

    public function json_daftar(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = DokumenSKPI::get_list_dokumen_skpi(Session::get('user')->id_mhs, 0, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function insup(Request $request)
    {
        $request->validate([
            'kartu_prestasi' => 'required|mimes:pdf|max:5000',
        ], [
            'kartu_prestasi.required' => 'Pastikan Kartu Prestasi Sudah Terisi',
            'kartu_prestasi.mimes' => 'Pastikan file Kartu Prestasi memiliki ektensi .pdf',
            'kartu_prestasi.max' => 'Maksimal ukuran file yang di upload adalah 5MB',
        ]);
        if (isset($request->dok_pendukung))
            $request->validate([
                'dok_pendukung' => 'mimes:rar,zip|max:10000',
            ], [
                'dok_pendukung.mimes' => 'Pastikan file Kartu Prestasi memiliki ektensi .rar/.zip',
                'dok_pendukung.max' => 'Maksimal ukuran file dokumen pendukung yang di upload adalah 10MB',
            ]);
        $t = time();
        $dok_kp = $request->file('kartu_prestasi');
        $file_name_kp = date("d-m-Y_h-m-s", $t) . '_kartu_prestasi.' . $dok_kp->getClientOriginalExtension();
        $old_kp = DokumenSKPI::get_list_dokumen_skpi(Session::get('user')->id_mhs);
        $file_name_pend = null;
        $dok_pend = $request->file('dok_pendukung');
        if (isset($request->dok_pendukung)) {
            $file_name_pend = date("d-m-Y_h-m-s", $t) . '_dok_pendukung.' . $dok_pend->getClientOriginalExtension();
        }
        if (sizeof($old_kp) > 0) {
            File::delete('files/dokumen_skpi/' . Session::get('user')->nim . '/' . $old_kp[0]->path_dokumen_kartu_prestasi);
            File::delete('files/dokumen_skpi/' . Session::get('user')->nim . '/' . $old_kp[0]->path_dokumen_pendukung_skpi);
        }
        $data = DokumenSKPI::insup_dokumen_skpi(Session::get('user')->id_mhs, $file_name_kp, $file_name_pend);
        if ($data->status == 1) {
            $destinationPath = 'files/dokumen_skpi/' . Session::get('user')->nim . '/';
            $dok_kp->move($destinationPath, $file_name_kp);
            if (isset($request->dok_pendukung))
                $dok_pend->move($destinationPath, $file_name_pend);
            Session::flash('success_message', "Berhasil Upload SKPI");
            return redirect(route('mahasiswa.akademik.skpi.index'));
        } else {
            Session::flash('failed_message', $data->keterangan);
            return redirect()->back();
        }
    }
}
