<?php

namespace App\Http\Controllers\DosenPage\Akadmik;

use App\Http\Controllers\Controller;
use App\Models\Akademik\Dosen;
use App\Models\Akademik\JadwalMataKuliah;
use App\Models\MOODLE_MODEL\CourseMoodle;
use App\Models\SIAKAD_MODEL\JadwalDosen;
use App\Models\SIAKAD_MODEL\TahunAkademik;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MatakuliahController extends Controller
{
    public function index()
    {
        $menu = 'Dosen - Akademik - Melihat Daftar Matakuliah';
        $tahun_akademik = TahunAkademik::get_tahun_akademik_sister();
//        dd($tahun_akademik);
        return view('dosen_page.akademik.daftar_matakuliah', compact('menu', 'tahun_akademik'));
    }

    public function json_daftar_matakuliah(Request $request)
    {
        $request->validate([
            'tahun' => 'required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = JadwalDosen::getMatakuliahByDosen($request->tahun, Session::get('user')->id_personal, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function export_pdf($tahun_akademik, $search = "")
    {
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        $data['tgl']['now'] = $tgl->format('d/m/Y H:i');
        $data['tahun_akademik'] = $tahun_akademik;
        $matakuliah = JadwalDosen::getMatakuliahByDosen(Session::get('user')->nomor_induk_karyawan, $tahun_akademik, $search);
        $dosen = Dosen::get_dosen_by_id_personal(Session::get('user')->id_personal);
        $pdf = Facade::loadView("dosen_page.akademik.pdf.daftar_matakuliah", compact('matakuliah', 'data', 'dosen'))->setPaper('a4', 'landscape');
        return $pdf->download('daftar_matakuliah.pdf');
    }

    public function export_peserta_pdf($jadwal_kuliah)
    {
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        $data['tgl']['now'] = $tgl->format('d/m/Y H:i');
        $matakuliah = JadwalDosen::getDetailJadwalKuliah($jadwal_kuliah);
        $dosen = Dosen::get_dosen_by_id_personal(Session::get('user')->id_personal);
        $mahasiswa = CourseMoodle::get_peserta_kelas_kuliah($jadwal_kuliah);
        $pdf = Facade::loadView("dosen_page.akademik.pdf.peserta_matakuliah", compact('mahasiswa', 'matakuliah', 'data', 'dosen'))->setPaper('a4', 'landscape');
        return $pdf->download($matakuliah->nama_mata_kuliah . '_' . $matakuliah->kelas_id . '.pdf');
    }

    public function export_presensi_mahasiswa($id_jadwal_kuliah)
    {
        $tanggal = [];
        $rekap = JadwalDosen::export_presensi($id_jadwal_kuliah);
        if (sizeof($rekap) > 0) {
            for ($i = 1; $i <= 16; $i++) {
                $field = "tgl_pertemuan_" . $i;
                $tanggal[] = $rekap[0]->$field;
            }
        }
        $pdf = Facade::loadView("dosen_page.akademik.pdf.presensi_mahasiswa", compact('tanggal', 'rekap'))->setPaper('a4', 'landscape');
        return $pdf->download('presensi_mahasiswa_'.$rekap[0]->nama_mata_kuliah.'.pdf');
    }
}
