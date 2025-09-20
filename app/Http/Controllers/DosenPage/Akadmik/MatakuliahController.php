<?php

namespace App\Http\Controllers\DosenPage\Akadmik;

use App\Http\Controllers\Controller;
use App\Models\Akademik\Dosen;
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
        $kriteria = JadwalDosen::get_kriteria();
        return view('dosen_page.akademik.daftar_matakuliah', compact('menu', 'tahun_akademik', 'kriteria'));
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
        return $pdf->download('presensi_mahasiswa_' . $rekap[0]->nama_mata_kuliah . '.pdf');
    }

    public function json_kriteria_penilaian(Request $request)
    {
        try {
            $request->validate([
                'id_jadwal' => 'required', // JavaScript mengirim matkul_id, bukan id_jadwal
            ]);
            // Ambil data kriteria berdasarkan matkul_id
            $kriteria = JadwalDosen::get_kriteria_penilaian($request->id_jadwal);
            return response()->json([
                'success' => true,
                'data' => $kriteria
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat daftar kriteria: ' . $e->getMessage()
            ], 500);
        }
    }

    // 2. FUNGSI STORE KRITERIA (untuk /dosen/akademik/kriteria-penilaian/store)
    public function json_kriteria_penilaian_store(Request $request)
    {
        try {
            $request->validate([
                'id_jadwal' => 'required',
                'id_kriteria' => 'required',
                'nama_kriteria' => 'required|string|max:100',
                'bobot' => 'required|numeric|min:1|max:100',
            ], [
                'id_jadwal.required' => 'Mata kuliah harus dipilih',
                'id_kriteria.required' => 'Kriteria harus dipilih',
                'nama_kriteria.required' => 'Nama kriteria harus diisi',
                'nama_kriteria.max' => 'Nama kriteria maksimal 100 karakter',
                'bobot.required' => 'Bobot harus diisi',
                'bobot.numeric' => 'Bobot harus berupa angka',
                'bobot.min' => 'Bobot minimal 1%',
                'bobot.max' => 'Bobot maksimal 100%',
            ]);

            // Simpan kriteria baru
            $kriteria = JadwalDosen::insert_kriteria_penilaian($request->id_jadwal, $request->id_kriteria, $request->nama_kriteria, $request->bobot);

            return response()->json([
                'success' => $kriteria->status == 1,
                'message' => $kriteria->keterangan,
                'data' => $kriteria
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete_kriteria(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $kriteria = JadwalDosen::delete_kriteria($request->id);
        return response()->json([
            'success' => $kriteria->status == 1,
            'message' => 'Kriteria penilaian "' . $kriteria->keterangan . '" berhasil dihapus'
        ]);
    }
}
