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
        //$username = Karyawan::get_username_by_id_personal(Session::get('user')->id_personal)->username;
        //$dosen = \App\Models\Akademik\Dosen::get_dosen_by_id_personal(Session::get('user')->id_personal);
//        dd($dosen);
        //if (sizeof($dosen) > 0) {
        //  $dosen = $dosen[0];
//            $course = CourseMoodle::get_course_moodle_by_username_dosen($username);
        $course = JadwalDosen::getMatkulDosen(Session::get('user')->id_staf_dosen);
//        $course = JadwalDosen::getMatkulDosen();
//        dd($course);
        return view('dosen_page.akademik.absensi_mengajar', compact('course', 'menu'));
        //} else
        //return redirect('/');
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
            'jenis_pertemuan' => 'required',
        ]);

        $rekap = RekapitulasiAbsensiMengajarDosen::addAbsensi($request->username, $request->id_matakuliah, $request->nama_mata_kuliah, $request->keterangan, $request->pertemuan_ke, $request->tgl_pelaksanaan, $request->jam_ke, $request->jml_mahasiswa_hadir, $request->jml_mahasiswa_alpha, null, null, $request->materi_pembelajaran, $request->tahun_akademik, $request->jenis_pertemuan);
        if ($rekap->status == 1) {
            Session::flash('success_message', "Berhasil Menyimpan Absensi Mengajar");
            return redirect(route('dosen.akademik.rekapitulasi_absen_mengajar.index'));
        } else {
            Session::flash('failed_message', $rekap->keterangan);
            return redirect()->back()->withInput();
        }
    }
}
