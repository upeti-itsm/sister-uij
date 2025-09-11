<?php

namespace App\Http\Controllers\DosenPage\Akadmik;

use App\Http\Controllers\Controller;
use App\Models\Absensi\RekapitulasiAbsensiMengajarDosen;
use App\Models\MOODLE_MODEL\CourseMoodle;
use App\Models\MOODLE_MODEL\Dosen;
use App\Models\Organisasi\Karyawan;
use App\Models\SIAKAD_MODEL\JadwalDosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AbsensiMengajarController extends Controller
{
    public function absensi_ngajar()
    {
        $menu = 'Absensi Mengajar';
        $course = JadwalDosen::getMatkulDosen(Session::get('user')->nidn);
        return view('dosen_page.akademik.absensi_mengajar', compact('course', 'menu'));
    }

    public function store_absensi_ngajar(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required',
            'username' => 'required',
            'id_matakuliah' => 'required',
            'nama_mata_kuliah' => 'required',
            'pertemuan_ke' => 'required',
            'tgl_pelaksanaan' => 'required',
            'jam_ke' => 'required',
            'materi_pembelajaran' => 'required',
            'link_materi_pembelajaran' => 'required',
            'jenis_pertemuan' => 'required',
        ]);
        $menu = 'Absensi Mengajar';
        $rekap = RekapitulasiAbsensiMengajarDosen::addAbsensi($request->username, $request->id_matakuliah, $request->nama_mata_kuliah, $request->keterangan, $request->pertemuan_ke, $request->tgl_pelaksanaan, $request->jam_ke, $request->jml_mahasiswa_hadir, $request->jml_mahasiswa_alpha, null, null, $request->materi_pembelajaran, $request->tahun_akademik, $request->jenis_pertemuan, $request->link_materi);
        if ($rekap->status == 1) {
            Session::flash('success_message', "Berhasil Menyimpan Absensi Mengajar");
            return view('dosen_page.akademik.qr_mengajar', compact('rekap', 'menu'));
        } else {
            Session::flash('failed_message', $rekap->keterangan);
            return redirect()->back()->withInput();
        }
    }
}
