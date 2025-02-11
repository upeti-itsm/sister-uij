<?php

namespace App\Http\Controllers\DosenPage\CV;

use App\Http\Controllers\Controller;
use App\Models\Akademik\Dosen;
use App\Models\Organisasi\Karyawan;
use App\Models\Referensi\Pendidikan;
use App\Models\SIAKAD_MODEL\JadwalDosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CurriculumVitaeController extends Controller
{
    public function index()
    {
        $menu = 'Curriculum Vitae';
        $personal = Karyawan::get_daftar_pegawai('', -1, 0, null, Session::get('user')->id_personal)[0];
        $id_personal = Session::get('user')->id_personal;
        $username = Karyawan::get_username_by_id_personal($id_personal)->username;
        $course[0] = JadwalDosen::getMatkulDosen($username, '20221');
        $course[1] = JadwalDosen::getMatkulDosen($username, '20222');
        $pendidikan = Dosen::get_riwayat_pendidikan($id_personal);
        return view('dosen_page.cv.index', compact('menu', 'personal', 'course', 'pendidikan'));
    }

    public function update_pendidikan(Request $req)
    {
        $req->validate([
            'jenjang_pendidikan' => 'required',
            'nama_pt' => 'required',
            'bid_ilmu' => 'required',
            'tgl_lulus' => 'required',
            'prodi' => 'required',
        ], [
            'jenjang_pendidikan.required' => 'Pastikan Sudah Memilih Jenjang Pendidikan',
            'nama_pt.required' => 'Pastikan Sudah Mengisi Nama Perguruan Tinggi',
            'bid_ilmu.required' => 'Pastikan Sudah Mengisi Bidang Ilmu',
            'tgl_lulus.required' => 'Pastikan Sudah Memilih Tanggal Lulus',
            'prodi.required' => 'Pastikan Sudah Mengisi Nama Program Studi',
        ]);
        $data = Dosen::update_riwayat_pendidikan(Session::get('user')->id_karyawan, $req->jenjang_pendidikan, $req->nama_pt, $req->bid_ilmu, $req->tgl_lulus, $req->prodi);
        Session::flash($data->status == 1 ? 'success_message' : 'failed_message', $data->keterangan);
        return redirect()->back();
    }

    public function riwayat_publikasi_jurnal(Request $request)
    {
        $request->validate([
            'jenis_jurnal' => 'required',
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = Dosen::get_riwayat_publikasi_jurnal(Session::get('user')->id_karyawan, $search, $start, $length, $request->jenis_jurnal);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function store_publikasi_jurnal(Request $request)
    {
        $request->validate([
            'jenis_jurnal' => 'required',
            'tahun_publikasi' => 'required',
            'judul_artikel' => 'required',
            'nama_jurnal' => 'required',
            'volume' => 'required',
            'nomor' => 'required',
        ]);
        $data = Dosen::insup_riwayat_publikasi_jurnal($request->id_riwayat, Session::get('user')->id_karyawan, $request->tahun_publikasi, $request->judul_artikel, $request->nama_jurnal, $request->volume, $request->nomor, $request->jenis_jurnal);
        Session::flash($data->status == 1 ? 'success_message' : 'failed_message', $data->keterangan);
        return redirect()->back();
    }
}
