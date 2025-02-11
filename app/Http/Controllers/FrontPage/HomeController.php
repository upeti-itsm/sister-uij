<?php

namespace App\Http\Controllers\FrontPage;

use App\Http\Controllers\Controller;
use App\Models\Absensi\RekapitulasiAbsensiMengajarDosen;
use App\Models\Akademik\DaftarHadirWisuda;
use App\Models\MOODLE_MODEL\CourseMoodle;
use App\Models\MOODLE_MODEL\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Console\Input\Input;

class HomeController extends Controller
{
    public function home()
    {
        return view('front_page.home');
    }

    public function absensi_ngajar()
    {
        $dosen = Dosen::get_daftar_dosen($_GET['dosen']);
        if (sizeof($dosen) > 0) {
            $dosen = $dosen[0];
            $course = CourseMoodle::get_course_moodle_by_username_dosen($_GET['dosen']);
            return view('front_page.absensi_ngajar', compact('dosen', 'course'));
        } else
            return redirect('/');
    }

    public function store_absensi_ngajar(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'id_matakuliah' => 'required',
            'nama_mata_kuliah' => 'required',
            'jenis_kelas' => 'required',
            'pertemuan_ke' => 'required',
            'tgl_pelaksanaan' => 'required',
            'jam_ke' => 'required',
            'materi_pembelajaran' => 'required',
        ]);

        $rekap = RekapitulasiAbsensiMengajarDosen::addAbsensi($request->username, $request->id_matakuliah, $request->nama_mata_kuliah, $request->keterangan, $request->pertemuan_ke, $request->tgl_pelaksanaan, $request->jam_ke, $request->jml_mahasiswa_hadir, $request->jml_mahasiswa_alpha, null, null, $request->materi_pembelajaran, '20212');
        if ($rekap->status == 1) {
            $dosen = Dosen::get_daftar_dosen($request->username)[0];
            $rekap = RekapitulasiAbsensiMengajarDosen::getRekapitulasiByUsername($request->username, $request->id_matakuliah);
            Session::flash('success_message', "Berhasil Menyimpan Absensi Mengajar");
            return view('front_page.rekap_absensi_ngajar', compact('dosen', 'rekap'));
        } else {
            Session::flash('failed_message', $rekap->keterangan);
            return redirect()->back()->withInput();
        }
    }

    public function store_absensi_ngajar_old(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'id_matakuliah' => 'required',
            'nama_mata_kuliah' => 'required',
            'jenis_kelas' => 'required',
            'pertemuan_ke' => 'required',
            'tgl_pelaksanaan' => 'required',
            'jam_ke' => 'required',
            'bukti_ajar' => 'required',
            'jml_mahasiswa_hadir' => 'required',
            'jml_mahasiswa_alpha' => 'required',
            'bukti_absensi' => 'required',
            'materi_pembelajaran' => 'required',
        ]);
        $t = time();
        // Bukti Ajar
        $bukti_ajar = $request->file('bukti_ajar');
        $file_name_bukti_ajar = $request->username . date("_d-m-Y_h-m-s", $t) . '_bukti-ajar.' . $bukti_ajar->getClientOriginalExtension();
        // Bukti Absensi
        $bukti_absensi = $request->file('bukti_absensi');
        $file_name_bukti_absensi = $request->username . date("_d-m-Y_h-m-s", $t) . '_bukti-absensi.' . $bukti_absensi->getClientOriginalExtension();
        // Max file 3Mb
        if ($bukti_ajar->getSize() <= 3000000 && $bukti_absensi->getSize() <= 3000000) {
            $rekap = RekapitulasiAbsensiMengajarDosen::addAbsensi($request->username, $request->id_matakuliah, $request->nama_mata_kuliah, $request->keterangan, $request->pertemuan_ke, $request->tgl_pelaksanaan, $request->jam_ke, $request->jml_mahasiswa_hadir, $request->jml_mahasiswa_alpha, $file_name_bukti_ajar, $file_name_bukti_absensi, $request->materi_pembelajaran);
            if ($rekap->status == 1) {
                $destinationPath = 'files/absensi_mengajar_dosen/';
                $bukti_ajar->move($destinationPath, $file_name_bukti_ajar);
                $bukti_absensi->move($destinationPath, $file_name_bukti_absensi);

                $dosen = Dosen::get_daftar_dosen($request->username)[0];
                $rekap = RekapitulasiAbsensiMengajarDosen::getRekapitulasiByUsername($request->username, $request->id_matakuliah);
                Session::flash('success_message', "Berhasil Menyimpan Absensi Mengajar");
                return view('front_page.rekap_absensi_ngajar', compact('dosen', 'rekap'));
            } else {
                Session::flash('failed_message', $rekap->keterangan);
                return redirect()->back()->withInput();
            }
        } else {
            Session::flash('failed_message', "Pastikan Ukuran File Tidak Lebih dari 3Mb");
            return redirect()->back()->withInput();
        }
    }

    // Tamu Wisuda
    public function buku_tamu()
    {
        return view('front_page.buku_tamu');
    }

    public function json_tamu(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = DaftarHadirWisuda::get_daftar_hadir('0', $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data);
    }

    public function detail_tamu(Request $request)
    {
        $request->validate([
            'barcode' => 'required',
        ]);
        $barcode = explode('-', $request->barcode);
        $data = DaftarHadirWisuda::detail_tamu($barcode[0]);
        if (sizeof($data) > 0)
            $data = $data[0];
        else
            $data = [];
        return response()->json($data);
    }

    public function insert_tamu(Request $request)
    {
        $barcode = explode('-', $request->barcode);
        if (isset($barcode[3]))
            $response = DaftarHadirWisuda::insert_tamu($barcode[0], $barcode[1], $barcode[2], $barcode[3]);
        else
            $response = DaftarHadirWisuda::insert_tamu('0', '0', $request->barcode, '0');
//            $response = DaftarHadirWisuda::insert_tamu('-', $barcode[0], $barcode[1], $barcode[2]);
        Session::flash($response->status == 1 ? "success_message" : "failed_message", $response->keterangan);
        return redirect()->back();
    }

    public function buku_tamu_self()
    {
        return view('front_page.buku_tamu_self');
    }

    public function insert_tamu_self(Request $request)
    {
        $request->validate([
            'barcode' => 'required'
        ]);
        $barcode = explode('-', $request->barcode);
        if (isset($barcode[3]))
            $response = DaftarHadirWisuda::insert_tamu($barcode[0], $barcode[1], $barcode[2], $barcode[3]);
        else
            $response = DaftarHadirWisuda::insert_tamu('-', $barcode[0], $barcode[1], $barcode[2]);
        Session::flash($response->status == 1 ? "success_message" : "failed_message", $response->keterangan);
        return redirect()->back()->with(['response' => $response]);
    }
}
