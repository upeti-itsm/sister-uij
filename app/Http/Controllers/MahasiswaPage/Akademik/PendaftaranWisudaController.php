<?php

namespace App\Http\Controllers\MahasiswaPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Akademik\Dosen;
use App\Models\Akademik\JadwalWisuda;
use App\Models\Akademik\Mahasiswa;
use App\Models\Akademik\PendaftarWisuda;
use App\Models\Kuesioner\RekapitulasiKepuasanWisudawan;
use App\Models\SertifikatUpeti\Sertifikat;
use App\Models\SIAKAD_MODEL\tblMahasiswa;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PendaftaranWisudaController extends Controller
{
    public function index()
    {
        $nilai = tblMahasiswa::getNilaiSkripsi(Session::get('user')->nim);
        $jadwal_wisuda = JadwalWisuda::get_daftar_jadwal_wisuda("all", 0, 1);
        if (sizeof($jadwal_wisuda) > 0) {
            $jadwal_wisuda = JadwalWisuda::get_daftar_jadwal_wisuda("all", 0, 1)[0];
            $cek = JadwalWisuda::check_mahasiswa(Session::get('user')->id_mhs);
            if ($jadwal_wisuda->kuota <= $jadwal_wisuda->peserta && $cek->status != 1) {
                Session::flash('failed_message', "Mohon Maaf, Kuota Wisuda ".$jadwal_wisuda->tgl_pelaksanaan_." Sudah Penuh - Ticket Sold Out and Have a Nice Day :)");
                return redirect()->back();
            }
            $dosen = Dosen::get_dosen();
            $menu = 'Pendaftaran Wisuda';
            if (sizeof($nilai) == 0) {
                Session::flash('failed_message', "Tidak Ditemukan Nilai Tugas Akhir");
                return redirect()->back();
            } elseif ($nilai[0]->nilai_huruf == "-" && $nilai[0]->nilai_huruf != "E") {
                Session::flash('failed_message', "Tidak Ditemukan Nilai Tugas Akhir");
                return redirect()->back();
            } else {
                $pengajuan = PendaftarWisuda::get_last_pengajuan(Session::get('user')->nim);
                $sertifikat = Sertifikat::get_sertifikat_by_nim(Session::get('user')->nim);
                $kuesioner = RekapitulasiKepuasanWisudawan::cek_status_pengisian(Session::get("user")->id_mhs);
                if ($kuesioner->status == 1)
                    return view('mahasiswa_page.akademik.wisuda', compact('menu', 'nilai', 'pengajuan', 'sertifikat', 'jadwal_wisuda', 'dosen'));
                else
                    return redirect(route('mahasiswa.akademik.kuesioner_kepuasan_wisudawan.index'));
            }
        } else {
            Session::flash('failed_message', "Tidak Ditemukan Jadwal Wisuda");
            return redirect()->back();
        }
    }

    public function add_pengajuan(Request $request)
    {
        $request->validate([
            'dok_pendukung' => 'required|mimes:jpeg,jpg,png,pdf|max:5000',
            'dpa' => 'required',
            'dpu' => 'required',
            'kesan_pesan' => 'required',
        ], [
            'dpu.required' => 'Pastikan Sudah memilih DPU',
            'dpa.required' => 'Pastikan Sudah memilih DPA',
            'dok_pendukung.required' => 'Pastikan SKB/Ijazah Terisi',
            'dok_pendukung.mimes' => 'Pastikan file SKB/Ijazah memiliki ektensi .jpeg/.jpg/.png/.pdf',
            'dok_pendukung.max' => 'Maksimal ukuran file yang di upload adalah 5MB',
            'kesan_pesan.required' => 'Pastikan Anda Mengisi Kesan dan Pesan',
        ]);
        $t = time();
        $dok = $request->file('dok_pendukung');
        $file_name = date("d-m-Y_h-m-s", $t) . '_dok.' . $dok->getClientOriginalExtension();
        $pendaftar = PendaftarWisuda::add_pengajuan(Session::get('user')->id_mhs, $file_name, $request->dpu, $request->dpa, $request->kesan_pesan);
        if ($pendaftar->status == 1) {
            $destinationPath = 'files/dokumen_pendukung_wisuda/' . Session::get('user')->nim . '/';
            $dok->move($destinationPath, $file_name);
            Session::flash('success_message', "Berhasil Melakukan Pendaftaran Wisuda");
            return redirect('/mhs/pendaftaran-wisuda');
        } else {
            Session::flash('failed_message', $pendaftar->keterangan);
            return redirect()->back();
        }
    }

    public function getKartu()
    {
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        $data['tgl']['now'] = $tgl->format('d/m/Y H:i');
        $data['tgl']['ttd'] = $tgl->format('d/m/Y');
        $mahasiswa = PendaftarWisuda::get_identitas_kartu_wisuda(Session::get('user')->nim);
        $pdf = Facade::loadView('mahasiswa_page.akademik.pdf.kartu_wisuda', compact('data', 'mahasiswa'))->setPaper('A4', 'portrait');
        return $pdf->download('kartu.pdf');
    }
}
