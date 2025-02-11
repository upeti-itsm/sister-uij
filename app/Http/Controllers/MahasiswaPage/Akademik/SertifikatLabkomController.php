<?php

namespace App\Http\Controllers\MahasiswaPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\SertifikatUpeti\PengajuanSertifikat;
use App\Models\SertifikatUpeti\Sertifikat;
use App\Models\SIAKAD_MODEL\tblMahasiswa;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SertifikatLabkomController extends Controller
{
    public function index()
    {
        $nilai = tblMahasiswa::getNilaiLabkom(Session::get('user')->nim);
        $menu = 'Pengajuan Sertifikat Laboratorium Komputer';
        if (sizeof($nilai) == 0) {
            Session::flash('failed_message', "Tidak Ditemukan Nilai untuk Matakuliah Laboratorium Komputer");
            return redirect()->back();
        }
        $pengajuan = PengajuanSertifikat::get_daftar_pengajuan_sertifikat('00000000-0000-0000-0000-000000000000', Session::get('user')->nim, 0, 1);
        $sertifikat = Sertifikat::get_sertifikat_by_nim(Session::get('user')->nim);
        return view('mahasiswa_page.akademik.sertifikat_labkom', compact('menu', 'nilai', 'pengajuan', 'sertifikat'));
    }

    public function add_pengajuan()
    {
        $pengajuan = PengajuanSertifikat::add_pengajuan(Session::get('user')->nim);
        if ($pengajuan->is_success)
            Session::flash('success_message', $pengajuan->result);
        else
            Session::flash('failed_message', $pengajuan->result);
        return redirect()->back();
    }

    public function generate_sertifikat($id_sertifikat)
    {
        $sertifikat = Sertifikat::generate_sertifikat($id_sertifikat);
        if ($sertifikat->tgl_created <= '2020-08-10') {
            $qrcode = base64_encode(QrCode::color(255, 0, 0)->format('svg')->size(500)->errorCorrection('H')->generate('http://sipadu.stie-mandala.ac.id/mhs/sertifikat-labkom/download/'.$sertifikat->id_sertifikat));
            $pdf = Facade::loadView("mahasiswa_page.akademik.pdf.sertifikat_labkom_agustin", compact('sertifikat', 'qrcode'))->setPaper('a4', 'landscape');
        } else {
            $qrcode = base64_encode(QrCode::color(255, 0, 0)->format('svg')->size(500)->errorCorrection('H')->generate('http://sipadu.stie-mandala.ac.id/mhs/sertifikat-labkom/download/'.$sertifikat->id_sertifikat));
            $pdf = Facade::loadView("mahasiswa_page.akademik.pdf.sertifikat_labkom_suwignyo", compact('sertifikat', 'qrcode'))->setPaper('a4', 'landscape');
        }
        return $pdf->download($sertifikat->nim_penerima . '-sertifikat.pdf');
    }
}
