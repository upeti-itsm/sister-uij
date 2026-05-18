<?php

namespace App\Http\Controllers\MahasiswaPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Akademik\PengajuanSurat;
use App\Models\Akademik\Semester;
use Barryvdh\DomPDF\Facade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PengajuanSuratCutiController extends Controller
{
    public function index()
    {
        $menu = 'Pengajuan Surat Cuti';
        $user = Session::get('user');
        $semesterAktif = null;

        try {
            $nim = $user->nim ?? null;

            if ($nim) {
                $semesterMahasiswa = Semester::get_semester_by_mahasiswa($nim);
                if (!empty($semesterMahasiswa)) {
                    $semesterAktif = (object) ['tahun_akademik' => $semesterMahasiswa[0]->tahun_akademik ?? null];
                }
            }

            if (!$semesterAktif) {
                $semesterAktif = Semester::get_semester()[0] ?? null;
            }
        } catch (\Exception $e) {
            $semesterAktif = null;
        }

        return view('mahasiswa_page.akademik.pengajuan_surat.pengajuan_surat_cuti', compact('menu', 'user', 'semesterAktif'));
    }

    public function json_pengajuan_surat(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json([
                    'draw'            => intval($request->draw ?? 1),
                    'recordsTotal'    => 0,
                    'recordsFiltered' => 0,
                    'data'            => [],
                    'error'           => 'Session user tidak ditemukan',
                ], 401);
            }

            $nim = $user->nim ?? null;
            if (!$nim) {
                return response()->json([
                    'draw'            => intval($request->draw ?? 1),
                    'recordsTotal'    => 0,
                    'recordsFiltered' => 0,
                    'data'            => [],
                    'error'           => 'NIM tidak ditemukan',
                ], 422);
            }

            $status = $request->status ?? null;
            $jenis  = $request->jenis  ?? null;
            $search = trim(is_array($request->search) ? ($request->search['value'] ?? '') : ($request->search ?? ''));
            $start  = $request->start  ?? 0;
            $length = $request->length ?? 10;

            $result = PengajuanSurat::get_list_mahasiswa($nim, $status, $jenis, $search, $start, $length);

            return response()->json([
                'draw'            => intval($request->draw ?? 1),
                'recordsTotal'    => $result['total'],
                'recordsFiltered' => $result['total'],
                'data'            => $result['data'],
                'error'           => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'draw'            => intval($request->draw ?? 1),
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
                'error'           => $e->getMessage(),
            ], 500);
        }
    }

    public function store_pengajuan_surat(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json(['status' => '0', 'keterangan' => 'Session user tidak ditemukan'], 401);
            }

            $nim = $user->nim ?? null;
            if (!$nim) {
                return response()->json(['status' => '0', 'keterangan' => 'NIM tidak ditemukan'], 422);
            }

            if (empty($request->keperluan)) {
                return response()->json(['status' => '0', 'keterangan' => 'Keperluan wajib diisi'], 422);
            }

            $tahunAkademik = null;
            try {
                $semesterMahasiswa = Semester::get_semester_by_mahasiswa($nim);
                $tahunAkademik = $semesterMahasiswa[0]->tahun_akademik ?? null;
            } catch (\Exception $e) {
                $tahunAkademik = null;
            }

            $result = PengajuanSurat::insup_pengajuan(
                $nim,
                $request->keperluan,
                $tahunAkademik
            );

            if ($result && $result->status === true) {
                return response()->json(['status' => '1', 'keterangan' => $result->keterangan], 200);
            }

            return response()->json(['status' => '0', 'keterangan' => $result->keterangan], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => '0', 'keterangan' => $e->getMessage()], 500);
        }
    }

    public function delete_pengajuan_surat(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json(['status' => '0', 'keterangan' => 'Session user tidak ditemukan'], 401);
            }

            $idRiwayat = $request->id_pengajuan;
            if (!$idRiwayat) {
                return response()->json(['status' => '0', 'keterangan' => 'ID pengajuan tidak valid'], 422);
            }

            $result = PengajuanSurat::set_status($idRiwayat, '0');

            if ($result && (string) $result->status === '1') {
                return response()->json(['status' => '1', 'keterangan' => $result->keterangan], 200);
            }

            return response()->json(['status' => '0', 'keterangan' => $result->keterangan ?? 'Gagal menghapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => '0', 'keterangan' => $e->getMessage()], 500);
        }
    }

    public function detail_pengajuan_surat(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json(['status' => '0', 'keterangan' => 'Session user tidak ditemukan'], 401);
            }

            $idRiwayat = $request->id_pengajuan;
            if (!$idRiwayat) {
                return response()->json(['status' => '0', 'keterangan' => 'ID pengajuan tidak valid'], 422);
            }

            $detail = PengajuanSurat::get_detail($idRiwayat);
            if (!$detail) {
                return response()->json(['status' => '0', 'keterangan' => 'Pengajuan tidak ditemukan'], 404);
            }

            return response()->json(['status' => '1', 'data' => $detail], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => '0', 'keterangan' => $e->getMessage()], 500);
        }
    }

    public function download_surat($id_pengajuan)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json(['status' => '0', 'keterangan' => 'Session user tidak ditemukan'], 401);
            }

            $detail = PengajuanSurat::get_detail($id_pengajuan);
            if (!$detail) {
                return response()->json(['status' => '0', 'keterangan' => 'Pengajuan tidak ditemukan'], 404);
            }

            if ((string) $detail->status_pengajuan !== '4') {
                return response()->json(['status' => '0', 'keterangan' => 'Surat hanya dapat diunduh jika sudah disetujui Dekan'], 422);
            }

            $logo = null;
            $logoPath = public_path('image/logo-uij.png');
            if (file_exists($logoPath)) {
                $logo = base64_encode(file_get_contents($logoPath));
            }

            // QR Code DPA
            $qrIdDpa = $detail->id_tandatangan_dokumen_dpa ?? $id_pengajuan;
            $qrCodeDpa = base64_encode(
                QrCode::format('svg')->size(200)->margin(1)->errorCorrection('H')
                    ->generate(route('frontpage.detail_qr', ['id' => base64_encode($qrIdDpa)]))
            );

            // QR Code Dekan
            $qrIdDekan = $detail->id_tandatangan_dokumen_dekan ?? $id_pengajuan;
            $qrCodeDekan = base64_encode(
                QrCode::format('svg')->size(200)->margin(1)->errorCorrection('H')
                    ->generate(route('frontpage.detail_qr', ['id' => base64_encode($qrIdDekan)]))
            );

            // QR Code Mahasiswa
            $qrIdMahasiswa = $detail->id_tandatangan_dokumen_mahasiswa ?? $id_pengajuan;
            $qrCodeMahasiswa = base64_encode(
                QrCode::format('svg')->size(200)->margin(1)->errorCorrection('H')
                    ->generate(route('frontpage.detail_qr', ['id' => base64_encode($qrIdMahasiswa)]))
            );

            $mahasiswa = (object) [
                'nim'            => $detail->nim,
                'nama_mahasiswa' => $detail->nama_mahasiswa  ?? $user->nama_lengkap  ?? '-',
                'nama_prodi'     => $detail->nama_prodi      ?? $user->nama_prodi    ?? '-',
                'nama_fakultas'  => $detail->nama_fakultas   ?? '-',
                'alamat_lengkap' => $detail->alamat_lengkap  ?? '-',
                'no_hp'          => $detail->no_hp           ?? '-',
                'tahun_akademik' => $detail->tahun_akademik  ?? '-',
                'keperluan'      => $detail->keperluan       ?? '-',
            ];

            $tanggalCetak = Carbon::now()->format('d F Y');

            $pdf = Facade::loadView('mahasiswa_page.akademik.pengajuan_surat.pdf_cuti', [
                'pengajuan'       => $detail,
                'mahasiswa'       => $mahasiswa,
                'logo'            => $logo,
                'tanggal_cetak'   => $tanggalCetak,
                'qr_code_dpa'     => $qrCodeDpa,
                'qr_code_dekan'   => $qrCodeDekan,
                'qr_code_mahasiswa' => $qrCodeMahasiswa,
            ])->setPaper('a4', 'portrait');

            $fileName = 'surat-cuti-akademik-' . $detail->nim . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            return response()->json(['status' => '0', 'keterangan' => $e->getMessage()], 500);
        }
    }
}
