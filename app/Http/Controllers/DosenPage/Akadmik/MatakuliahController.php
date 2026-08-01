<?php

namespace App\Http\Controllers\DosenPage\Akadmik;

use App\Http\Controllers\Controller;
use App\Models\Akademik\Dosen;
use App\Models\Dosen\Akademik\PengajuanJurnalDosen;
use App\Models\MOODLE_MODEL\CourseMoodle;
use App\Models\SIAKAD_MODEL\JadwalDosen;
use App\Models\SIAKAD_MODEL\TahunAkademik;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        try {
            $request->validate([
                'id' => 'required'
            ]);

            $kriteria = JadwalDosen::delete_kriteria($request->id);
            return response()->json([
                'success' => $kriteria->status == 1,
                'message' => $kriteria->keterangan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kriteria: ' . $e->getMessage()
            ], 500);
        }
    }

    public function insup_jurnal_mengajar_dosen(Request $request)
    {
        try {
            $request->validate([
                'id_jadwal' => 'required',
            ], [
                'id_jadwal.required' => 'Mata kuliah / Jadwal kuliah harus dipilih',
            ]);

            // Insert atau update jurnal mengajar
            $id_jurnal = !empty($request->id_jurnal) ? $request->id_jurnal : null;
            $id_jadwal_kuliah = $request->id_jadwal;
            $id_personal = session()->get('user')->id_personal;
            $catatan = $request->catatan;

            $pengajuan_jurnal = PengajuanJurnalDosen::insup_pengajuan_jurnal_mengajar_dosen($id_jurnal, $id_jadwal_kuliah, $id_personal, $catatan);

            $status = true;
            $keterangan = 'Jurnal mengajar berhasil disimpan';

            if ($pengajuan_jurnal) {
                if (isset($pengajuan_jurnal->status)) {
                    $status = ($pengajuan_jurnal->status == 1 || $pengajuan_jurnal->status === true || $pengajuan_jurnal->status === 't');
                }
                if (isset($pengajuan_jurnal->keterangan)) {
                    $keterangan = $pengajuan_jurnal->keterangan;
                }
            }

            return response()->json([
                'success' => $status,
                'message' => $keterangan,
                'data' => $pengajuan_jurnal
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

    public function ajukan_jurnal_mengajar_dosen(Request $request)
    {
        try {
            $request->validate([
                'id_jurnal' => 'required',
            ], [
                'id_jurnal.required' => 'Jurnal mengajar harus dipilih',
            ]);

            $current_status = $request->status; // Status saat ini dari frontend (misal 1 / Draft)
            if ($current_status == 1) {
                $sts_target = 2;
            } else {
                $sts_target = $current_status;
            }
            $id_personal = session()->get('user')->id_personal;
            $catatan = $request->catatan ?? null;

            // Ajukan jurnal mengajar ke database
            $pengajuan_jurnal = PengajuanJurnalDosen::set_status_ajuan($request->id_jurnal, $sts_target, $id_personal, $catatan);

            if ($pengajuan_jurnal) {
                if (isset($pengajuan_jurnal->status)) {
                    $status = ($pengajuan_jurnal->status == 1 || $pengajuan_jurnal->status === true || $pengajuan_jurnal->status === 't');
                }
                if (isset($pengajuan_jurnal->keterangan)) {
                    $keterangan = $pengajuan_jurnal->keterangan;
                }
            }

            return response()->json([
                'success' => $status,
                'message' => $keterangan,
                'data' => $pengajuan_jurnal
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

    public function download_jurnal_mengajar_dosen(Request $request)
    {
        try {
            $request->validate([
                'id_jadwal' => 'required',
                'id_jurnal' => 'required',
            ], [
                'id_jadwal.required' => 'Mata kuliah / Jadwal kuliah harus dipilih',
                'id_jurnal.required' => 'Jurnal mengajar harus dipilih',
            ]);

            Carbon::setLocale('id');

            $id_personal     = session()->get('user')->id_personal;
            $id_jadwal_kuliah = $request->id_jadwal;
            $search           = $request->search ?? '';
            $tahun_akademik   = $request->tahun_akademik ?? $request->tahun ?? '00000';

            // Ambil data jurnal mengajar
            $jurnal_mengajar = PengajuanJurnalDosen::generate_jurnal_mengajar_dosen(
                $id_personal,
                $id_jadwal_kuliah,
                $search,
                0,
                null,
                $tahun_akademik
            );

            if (empty($jurnal_mengajar)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data jurnal mengajar yang dapat dicetak'
                ], 404);
            }

            // Ambil data dosen
            $dosen = Dosen::get_dosen_by_id_personal($id_personal);

            // Data ringkasan dari baris pertama
            $first = $jurnal_mengajar[0];
            $jml_pertemuan   = count($jurnal_mengajar);
            $jml_terlaksana  = $jml_pertemuan;
            $jml_tepat_waktu = $first->jml_tepat_waktu ?? 0;
            $jml_terlambat   = $first->jml_terlambat  ?? 0;
            $jml_reg_p       = $first->jml_reg_p       ?? 0;

            // Rata-rata kehadiran mahasiswa
            $total_hadir = array_sum(array_map(function ($r) {
                return (int)($r->total_mhs_presensi ?? 0);
            }, $jurnal_mengajar));
            if ($jml_pertemuan > 0 && $jml_reg_p > 0) {
                $pct = round(($total_hadir / ($jml_pertemuan * $jml_reg_p)) * 100, 1);
                $rata_kehadiran_mhs = $pct . '%';
            } else {
                $rata_kehadiran_mhs = '-';
            }

            // QR Code — gunakan format SVG sama seperti RekapitulasiAbsenMengajarController (sudah terbukti bekerja)
            $qrCodeDosen      = '';
            $qrCodeKaprodi    = '';
            $qrErrorDosen     = '';
            $qrErrorKaprodi   = '';
            $qrIdDosen        = $first->id_dokumen_penandatanganan ?? null;
            $qrIdKaprodi      = $first->id_dokumen_ditandatangani  ?? null;

            if (!empty($qrIdDosen)) {
                try {
                    $qrCodeDosen = base64_encode(
                        QrCode::format('svg')->size(200)->margin(1)->errorCorrection('H')
                            ->generate(route('frontpage.detail_qr', ['id' => base64_encode($qrIdDosen)]))
                    );
                } catch (\Exception $e) {
                    $qrErrorDosen = $e->getMessage();
                    \Illuminate\Support\Facades\Log::warning('QR Dosen gagal: ' . $e->getMessage());
                }
            }

            if (!empty($qrIdKaprodi)) {
                try {
                    $qrCodeKaprodi = base64_encode(
                        QrCode::format('svg')->size(200)->margin(1)->errorCorrection('H')
                            ->generate(route('frontpage.detail_qr', ['id' => base64_encode($qrIdKaprodi)]))
                    );
                } catch (\Exception $e) {
                    $qrErrorKaprodi = $e->getMessage();
                    \Illuminate\Support\Facades\Log::warning('QR Kaprodi gagal: ' . $e->getMessage());
                }
            }

            // Logo UIJ
            $logoPath   = public_path('image/logo-uij.png');
            $logo       = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';

            $tanggalCetak = Carbon::now()->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY');

            // Nama Kaprodi dan NIDN dari data yang tersedia (dari dosen jika tidak ada field kaprodi)
            $nama_kaprodi = $dosen->nama_ketua_program_studi ?? $dosen->nama_kaprodi ?? '';
            $nidn_kaprodi = $dosen->nidn_ketua_program_studi ?? $dosen->nidn_kaprodi ?? '';

            $data = [
                'jurnal'            => $jurnal_mengajar,
                'dosen'             => $dosen,
                'logo'              => $logo,
                'tahun_akademik'    => $first->tahun_akademik    ?? $tahun_akademik,
                'nama_matakuliah'   => $first->fullname          ?? '-',
                'nama_kelas'        => $first->nama_kelas        ?? '-',
                'waktu_mengajar'    => $first->waktu_mengajar    ?? '-',
                'fakultas'          => 'FAKULTAS ' . strtoupper($first->fakultas        ?? ''),
                'program_studi'     => 'PROGRAM STUDI ' . strtoupper($first->program_studi ?? ''),
                'jml_pertemuan'     => $jml_pertemuan,
                'jml_terlaksana'    => $jml_terlaksana,
                'jml_tepat_waktu'   => $jml_tepat_waktu,
                'jml_terlambat'     => $jml_terlambat,
                'jml_reg_p'         => $jml_reg_p,
                'rata_kehadiran_mhs'=> $rata_kehadiran_mhs,
                'tanggal_cetak'     => $tanggalCetak,
                'qr_dosen'          => $qrCodeDosen,
                'qr_kaprodi'        => $qrCodeKaprodi,
                'qr_error_dosen'    => $qrErrorDosen,
                'qr_error_kaprodi'  => $qrErrorKaprodi,
                'nama_kaprodi'      => $nama_kaprodi,
                'nidn_kaprodi'      => $nidn_kaprodi,
            ];

            $pdf = Facade::loadView('dosen_page.akademik.pdf.jurnal_mengajar_dosen', $data)
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled'      => false,
                    'defaultFont'          => 'sans-serif',
                ]);

            $namaFile = 'Jurnal_Mengajar_' . str_replace(['/', ' '], '_', $first->fullname ?? 'dosen') . '.pdf';

            return $pdf->download($namaFile);
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
}
