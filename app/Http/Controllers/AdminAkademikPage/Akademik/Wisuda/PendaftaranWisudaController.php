<?php

namespace App\Http\Controllers\AdminAkademikPage\Akademik\Wisuda;

use App\Http\Controllers\Controller;
use App\Models\Akademik\DaftarHadirWisuda;
use App\Models\Akademik\Dosen;
use App\Models\Akademik\PendaftarWisuda;
use App\Models\Akademik\ProgramStudi;
use App\Models\SIAKAD_MODEL\tblMahasiswa;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PendaftaranWisudaController extends Controller
{
    public function index()
    {
        $menu = 'Validasi Pendaftaran Wisuda';
        $prodi = ProgramStudi::get_program_studi();
        $dosen = Dosen::get_dosen();
        return view('admin_akademik_page.akademik.wisuda.pendaftaran_wisuda', compact('menu', 'prodi', 'dosen'));
    }

    public function json_pendaftaran_wisuda(Request $request)
    {
        $request->validate([
            'status_pengajuan' => 'required',
            'kd_prodi' => 'required',
            'konsentrasi' => ' required'
        ]);
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = PendaftarWisuda::get_daftar_pengajuan_wisuda('00000000-0000-0000-0000-000000000000', $search, $start, $length, $request->status_pengajuan, $request->kd_prodi, $request->konsentrasi);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function denied_pendaftaran(Request $request)
    {
        $request->validate([
            'id_pendaftaran' => 'required',
            'alasan' => 'required',
        ]);
        $data = PendaftarWisuda::validasi_pendaftaran_wisuda($request->id_pendaftaran, Session::get('user')->id_personal, 3, $request->alasan);
        return response()->json($data, 200);
    }

    public function accept_pendaftaran(Request $request)
    {
        $request->validate([
            'id_pendaftaran' => 'required',
            'ipk' => 'required',
            'dpa' => 'required',
            'dpu' => 'required',
        ]);

        $data = PendaftarWisuda::validasi_pendaftaran_wisuda($request->id_pendaftaran, Session::get('user')->id_personal, 2, '', str_replace(',', '.', $request->ipk), $request->dpu, $request->dpa);
        $mahasiswa = tblMahasiswa::getMahasiswaByNpk($data->nim);
        $pass = 'null';
        if (!empty($mahasiswa->password))
            $pass = $mahasiswa->password;

        $update = \App\Models\Akademik\Mahasiswa::sync_mahasiswa_with_siakad($mahasiswa->NPK, $mahasiswa->nisn, $mahasiswa->dosen_wali, $mahasiswa->tgl_lulus_sma, $mahasiswa->inf_jurusan_sma, $mahasiswa->sekolah_asal, $mahasiswa->inf_tgl_lulus, $mahasiswa->inf_nomor_ijazah, $mahasiswa->inf_nomor_transkrip, $mahasiswa->status_aktif, $mahasiswa->program_id, $mahasiswa->konsentrasi_id,
            $mahasiswa->nama_wali, $mahasiswa->pekerjaan_wali, $mahasiswa->jenis_mahasiswa, $mahasiswa->jenis_pendanaan, $mahasiswa->no_seri_ijazah, $mahasiswa->nama_lengkap, $mahasiswa->tempat_lahir, $mahasiswa->tgl_lahir, $mahasiswa->jenis_kelamin, $mahasiswa->agama_id, $mahasiswa->status_menikah, $mahasiswa->hp, $mahasiswa->telepon_rumah, $mahasiswa->alamat_rumah,
            $mahasiswa->kode_pos_rumah, $mahasiswa->kewarganegaraan, $mahasiswa->email, $mahasiswa->nik, $mahasiswa->rt, $mahasiswa->rw, $mahasiswa->ds_kel, $mahasiswa->nama_ibu, $pass, $mahasiswa->angkatan, $mahasiswa->jenis_kelas, $mahasiswa->judul_skripsi, $mahasiswa->ipk, $mahasiswa->kota_rumah);
        if ($data->status == 1)
            Session::flash('success_message', $data->keterangan);
        else
            Session::flash('failed_message', $data->keterangan);
        return redirect()->back();
    }

    public function export_pendaftar($status_pengajuan, $kd_prodi, $kd_konsen)
    {
        return Excel::download(new \App\Exports\Wisuda\PendaftarWisuda($status_pengajuan, $kd_prodi), 'pendaftar_wisuda.xlsx');
    }

    public function export_pdf($status_pengajuan, $kd_prodi, $kd_konsen)
    {
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        $data['tgl']['now'] = $tgl->format('d/m/Y H:i');
        $data['tgl']['ttd'] = $tgl->format('d/m/Y');
        $pendaftar = PendaftarWisuda::get_daftar_pengajuan_wisuda('00000000-0000-0000-0000-000000000000', '', 0, -1, $status_pengajuan, $kd_prodi, $kd_konsen);
        $pdf = Facade::loadView("admin_akademik_page.akademik.wisuda.export.pdf.pendaftar_wisuda", compact('pendaftar', 'data'))->setPaper('a4', 'portrait');
        return $pdf->download('pendaftar.pdf');
    }

    public function export_barcode($offset, $limit)
    {
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        $data['tgl']['now'] = $tgl->format('d/m/Y H:i');
        $data['tgl']['ttd'] = $tgl->format('d/m/Y');
        $pendaftar = DaftarHadirWisuda::get_tamu($offset, $limit);
        $pdf = Facade::loadView("admin_akademik_page.akademik.wisuda.export.pdf.barcode_undangan", compact('pendaftar', 'data'))->setPaper('a4', 'portrait');
        return $pdf->download(($offset + 1) . '_' . $limit . '.pdf');
    }

    public function export_barcode_vvip($offset, $limit)
    {
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        $data['tgl']['now'] = $tgl->format('d/m/Y H:i');
        $data['tgl']['ttd'] = $tgl->format('d/m/Y');
        $pendaftar = DaftarHadirWisuda::get_tamu_vvip($offset, $limit);
        foreach ($pendaftar as $item) {
            $qr = base64_encode(QrCode::format('svg')->size(500)->errorCorrection('H')->generate($item->code_barcode));
            $item->qrcode = $qr;
        }
        $pdf = Facade::loadView("admin_akademik_page.akademik.wisuda.export.pdf.barcode_undangan_vvip", compact('pendaftar', 'data'))->setPaper('a4', 'portrait');
        return $pdf->download(($offset + 1) . '_' . $limit . '.pdf');
    }
}
