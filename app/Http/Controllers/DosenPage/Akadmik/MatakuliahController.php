<?php

namespace App\Http\Controllers\DosenPage\Akadmik;

use App\Http\Controllers\Controller;
use App\Models\Absensi\RekapitulasiAbsensiMengajarDosen;
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

    public function export_presensi_mahasiswa($id)
    {
        $rekapRow = JadwalDosen::getRekapPertemuan($id);

        if (!$rekapRow) {
            return response()->json(['success' => false, 'message' => 'Data rekap tidak ditemukan'], 404);
        }

        $jadwal_kuliah_id = $rekapRow->jadwal_id;
        $pertemuan_ke     = (int) $rekapRow->pertemuan_ke;

        $rekap = JadwalDosen::export_presensi($jadwal_kuliah_id);

        if (sizeof($rekap) == 0) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $tanggal_pertemuan = $rekap[0]->{"tgl_pertemuan_" . $pertemuan_ke} ?? null;

        $rekap_filtered = [];
        foreach ($rekap as $mhs) {
            $row = new \stdClass();
            $row->nim              = $mhs->nim;
            $row->nama_mahasiswa   = $mhs->nama_mahasiswa;
            $row->nama_program_studi = $mhs->nama_program_studi;
            $row->status_pertemuan = $mhs->{"pertemuan_" . $pertemuan_ke} ?? 0;
            $rekap_filtered[] = $row;
        }

        $rekap_summary = [
            'hadir'  => $rekap[0]->{"jml_mhs_hadir_" . $pertemuan_ke} ?? 0,
            'izin'   => $rekap[0]->{"jml_mhs_izin_" . $pertemuan_ke} ?? 0,
            'sakit'  => $rekap[0]->{"jml_mhs_sakit_" . $pertemuan_ke} ?? 0,
            'alpha'  => $rekap[0]->{"jml_mhs_tidak_hadir_" . $pertemuan_ke} ?? 0,
        ];

        $logoPath = public_path('image/logo-uij.png');
        $logo     = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';

        Carbon::setLocale('id');
        $tanggal_cetak = Carbon::now()->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY');

        $qrCodeDosen   = '';
        $qrCodeKaprodi = '';
        $qrErrorDosen  = '';
        $qrErrorKaprodi = '';

        $qrIdDosen   = $rekap[0]->id_dokumen_ditandatangani_dosen ?? ($rekap[0]->id_dokumen_penandatanganan ?? null);
        $qrIdKaprodi = $rekap[0]->id_dokumen_ditandatangani_kaprodi ?? null;

        if (!empty($qrIdDosen)) {
            try {
                $qrCodeDosen = base64_encode(
                    QrCode::format('svg')->size(200)->margin(1)->errorCorrection('H')
                        ->generate(route('frontpage.detail_qr', ['id' => base64_encode($qrIdDosen)]))
                );
            } catch (\Exception $e) {
                $qrErrorDosen = $e->getMessage();
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
            }
        }

        $fakultas      = strtoupper($rekap[0]->fakultas ?? '');
        $program_studi = strtoupper($rekap[0]->nama_program_studi ?? '');
        $nama_kelas    = $rekap[0]->nama_kelas ?? '-';
        $sks           = $rekap[0]->sks ?? '-';
        $semester      = $rekap[0]->semester ?? '-';

        $data = [
            'rekap'             => $rekap_filtered,
            'rekap_detail'      => $rekap[0],
            'pertemuan_ke'      => $pertemuan_ke,
            'tanggal_pertemuan' => $tanggal_pertemuan,
            'logo'              => $logo,
            'fakultas'          => 'FAKULTAS ' . $fakultas,
            'program_studi'     => 'PROGRAM STUDI ' . $program_studi,
            'nama_kelas'        => $nama_kelas,
            'sks'               => $sks,
            'semester'          => $semester,
            'tanggal_cetak'     => $tanggal_cetak,
            'qr_dosen'          => $qrCodeDosen,
            'qr_kaprodi'        => $qrCodeKaprodi,
            'qr_error_dosen'    => $qrErrorDosen,
            'qr_error_kaprodi'  => $qrErrorKaprodi,
            'nama_kaprodi'      => $rekap[0]->nama_kaprodi ?? null,
            'nidn_kaprodi'      => $rekap[0]->nidn_kaprodi ?? null,
            'rekap_summary'     => $rekap_summary,
        ];

        $pdf = Facade::loadView("dosen_page.akademik.pdf.presensi_mahasiswa", $data)->setPaper('a4', 'portrait');
        return $pdf->download('presensi_mahasiswa_Pertemuan_' . $pertemuan_ke . '_' . $rekap[0]->nama_mata_kuliah . '.pdf');
    }

    public function json_kriteria_penilaian(Request $request)
    {
        try {
            $request->validate([
                'id_jadwal' => 'required',
            ]);

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

            $current_status = $request->status;

            if (in_array($current_status, [1, 4])) {
                $sts_target = 2;
            } else {
                $sts_target = $current_status;
            }

            $id_personal = session()->get('user')->id_personal;

            $pengajuan_jurnal = PengajuanJurnalDosen::set_status_ajuan($request->id_jurnal, $sts_target, $id_personal);

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
            $qrIdDosen        = $first->id_dokumen_ditandatangani_dosen ?? null;
            $qrIdKaprodi      = $first->id_dokumen_ditandatangani_kaprodi  ?? null;

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
            $nama_kaprodi = $first->nama_kaprodi;
            $nidn_kaprodi = $first->nidn_kaprodi;

            $nama_dosen = $first->nama_dosen;
            $nidn_dosen = $first->nidn_dosen;

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
                'rata_kehadiran_mhs' => $rata_kehadiran_mhs,
                'tanggal_cetak'     => $tanggalCetak,
                'qr_dosen'          => $qrCodeDosen,
                'qr_kaprodi'        => $qrCodeKaprodi,
                'qr_error_dosen'    => $qrErrorDosen,
                'qr_error_kaprodi'  => $qrErrorKaprodi,
                'nama_kaprodi'      => $nama_kaprodi,
                'nidn_kaprodi'      => $nidn_kaprodi,
                'nama_dosen'        => $nama_dosen,
                'nidn_dosen'        => $nidn_dosen
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
