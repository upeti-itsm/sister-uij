<?php

namespace App\Http\Controllers\KaryawanPage\SuratMenyurat;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\PengajuanSurat;
use App\Models\Organisasi\UnitKerja;
use Barryvdh\DomPDF\Facade as DomPDFFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf as Facade;

class PengajuanSuratController extends Controller
{
    public function index()
    {
        $menu        = 'Pengajuan Surat';
        $jenis_surat = PengajuanSurat::daftar_jenis_surat();
        $status_surat = PengajuanSurat::daftar_status_surat();
        $unit_kerja  = UnitKerja::get_daftar_unit_kerja('');

        return view(
            'karyawan_page/surat_menyurat/pengajuan_surat_unit_kerja',
            compact('menu', 'jenis_surat', 'unit_kerja', 'status_surat')
        );
    }

    public function create()
    {
        $menu        = 'Pengajuan Surat';
        $jenis_surat = PengajuanSurat::daftar_jenis_surat();

        $unit_kerja = UnitKerja::get_daftar_unit_kerja('');
        $user = Session::get('user');
        $selected_unit = $user->id_unit_bagian ?? $user->id_unit_kerja ?? null;
        $selected_unit_label = null;

        foreach ($unit_kerja as $unit) {
            $unit_id = $unit->id_unit_bagian ?? $unit->id_unit_kerja ?? null;
            if ($selected_unit !== null && (string) $unit_id === (string) $selected_unit) {
                $selected_unit_label = $unit->unit_kerja ?? ($unit->nama_unit_kerja ?? $unit->nama_unit_bagian ?? null);
                break;
            }
        }

        $is_edit = false;
        $edit_id = null;

        return view(
            'karyawan_page/surat_menyurat/pengajuan_surat_unit_kerja_create',
            compact('menu', 'jenis_surat', 'unit_kerja', 'selected_unit', 'selected_unit_label', 'is_edit', 'edit_id')
        );
    }

    public function edit($id_log_surat)
    {
        $menu        = 'Pengajuan Surat';
        $jenis_surat = PengajuanSurat::daftar_jenis_surat();
        $unit_kerja = UnitKerja::get_daftar_unit_kerja('');
        $user = Session::get('user');
        $selected_unit = $user->id_unit_bagian ?? $user->id_unit_kerja ?? null;
        $selected_unit_label = null;

        foreach ($unit_kerja as $unit) {
            $unit_id = $unit->id_unit_bagian ?? $unit->id_unit_kerja ?? null;
            if ($selected_unit !== null && (string) $unit_id === (string) $selected_unit) {
                $selected_unit_label = $unit->unit_kerja ?? ($unit->nama_unit_kerja ?? $unit->nama_unit_bagian ?? null);
                break;
            }
        }

        $detail = PengajuanSurat::detail_pengajuan_surat($id_log_surat);
        if (!$detail) {
            Session::flash('failed_message', 'Pengajuan surat tidak ditemukan.');
            return redirect()->route('karyawan.surat_menyurat.pengajuan_surat.index');
        }

        $status = strtoupper((string) ($detail->status_surat ?? $detail->status ?? ''));
        if (strpos($status, 'REVISI') === false) {
            Session::flash('failed_message', 'Pengajuan surat ini tidak dalam status revisi.');
            return redirect()->route('karyawan.surat_menyurat.pengajuan_surat.index');
        }

        $id_personal_pengaju = $detail->id_personal_pengaju ?? null;
        $user_personal = $user->id_personal ?? null;
        if ($id_personal_pengaju && $user_personal && (string) $id_personal_pengaju !== (string) $user_personal) {
            Session::flash('failed_message', 'Anda tidak memiliki akses untuk mengubah pengajuan ini.');
            return redirect()->route('karyawan.surat_menyurat.pengajuan_surat.index');
        }

        $is_edit = true;
        $edit_id = $id_log_surat;

        return view(
            'karyawan_page/surat_menyurat/pengajuan_surat_unit_kerja_create',
            compact('menu', 'jenis_surat', 'unit_kerja', 'selected_unit', 'selected_unit_label', 'is_edit', 'edit_id')
        );
    }

    // ─── DataTable JSON ────────────────────────────────────────────────────────

    public function json_daftar_pengajuan_surat(Request $request)
    {
        $length = (int) $request->input('length', 10);
        $start = (int) $request->input('start', 0);
        $user = Session::get('user');
        $id_personal = $user->id_personal ?? null;
        $id_unit_bagian = $user->id_unit_bagian ?? null;

        $akses_all = filter_var($request->input('akses_all', false), FILTER_VALIDATE_BOOLEAN);
        if ($akses_all) {
            $id_personal = null;
            $id_unit_bagian = null;
        }

        $id_status_surat = $request->input('status_surat');
        $id_jenis_surat  = $request->input('jenis_surat');
        $id_unit_filter  = $request->input('unit_kerja');
        $tanggal_dari    = $request->input('tanggal_dari');
        $tanggal_sampai  = $request->input('tanggal_sampai');
        $sts_aktif       = $request->input('sts_aktif', true);
        $search          = $request->input('search.value');

        if (!empty($id_unit_filter)) {
            $id_personal = null;
            $id_unit_bagian = $id_unit_filter;
        }

        if ($length < 1) {
            $length = 10;
        }
        if ($start < 0) {
            $start = 0;
        }
        $page = (int) floor($start / $length) + 1;

        $data_ = PengajuanSurat::daftar_pengajuan_surat(
            $id_personal,
            $id_unit_bagian,
            null,
            $id_status_surat,
            $id_jenis_surat,
            $tanggal_dari,
            $tanggal_sampai,
            $sts_aktif,
            $search,
            $page,
            $length
        );

        $unit_map = [];
        foreach (UnitKerja::get_daftar_unit_kerja('') as $unit) {
            if (!empty($unit->id_unit_bagian)) {
                $unit_map[$unit->id_unit_bagian] = $unit->nama_unit_bagian ?? $unit->nama_unit_kerja ?? null;
            }
        }

        foreach ($data_ as $item) {
            if (empty($item->nama_unit_pengirim) && !empty($item->unit_bagian_pengirim)) {
                $item->nama_unit_pengirim = $unit_map[$item->unit_bagian_pengirim] ?? $item->unit_bagian_pengirim;
            }
        }

        $data['draw']            = $request->input('draw');
        $data['recordsTotal']    = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data']            = $data_;
        $data['error']           = null;

        return response()->json($data, 200);
    }

    // ─── Insert / Update ───────────────────────────────────────────────────────

    public function insup_pengajuan_surat(Request $request)
    {
        $request->validate([
            'perihal'               => 'required',
            'tanggal_surat'         => 'required',
            'isi_surat'             => 'required',
            'id_jenis_surat'        => 'required',
            'lampiran'              => 'nullable|mimes:pdf|max:5000',
        ], [
            'perihal.required'              => 'Pastikan Perihal Surat terisi',
            'tanggal_surat.required'        => 'Pastikan Tanggal Surat terisi',
            'isi_surat.required'            => 'Pastikan Isi Surat terisi',
            'id_jenis_surat.required'       => 'Pastikan Jenis Surat dipilih',
            'lampiran.mimes'                => 'File lampiran harus berformat PDF',
            'lampiran.max'                  => 'Ukuran lampiran tidak boleh lebih dari 5MB',
        ]);

        $user = Session::get('user');
        $id_personal_pengaju = $user->id_personal ?? null;
        $unit_bagian_pengirim = $request->input('unit_bagian_pengirim') ?: ($user->id_unit_bagian ?? $user->id_unit_kerja ?? null);

        if (empty($id_personal_pengaju) || empty($unit_bagian_pengirim)) {
            Session::flash('failed_message', 'Data pengaju atau unit kerja tidak ditemukan');
            return redirect()->back();
        }

        $unit_bagian_penerima = [];
        $personal_penerima = [];

        $id_log_surat = $request->id_log_surat ?: null;
        if ($id_log_surat) {
            $detail = PengajuanSurat::detail_pengajuan_surat($id_log_surat);
            if (!$detail) {
                Session::flash('failed_message', 'Pengajuan surat tidak ditemukan.');
                return redirect()->back();
            }

            $status = strtoupper((string) ($detail->status_surat ?? $detail->status ?? ''));
            if (strpos($status, 'REVISI') === false) {
                Session::flash('failed_message', 'Pengajuan surat ini tidak dalam status revisi.');
                return redirect()->back();
            }

            $id_personal_pengaju_db = $detail->id_personal_pengaju ?? null;
            if ($id_personal_pengaju_db && $id_personal_pengaju && (string) $id_personal_pengaju_db !== (string) $id_personal_pengaju) {
                Session::flash('failed_message', 'Anda tidak memiliki akses untuk mengubah pengajuan ini.');
                return redirect()->back();
            }
        }

        $lampiran_path = null;
        if ($request->hasFile('lampiran')) {
            $file       = $request->file('lampiran');
            $file_name  = Uuid::uuid4() . '.' . $file->getClientOriginalExtension();
            $dest       = 'files/arsip_surat_menyurat/pengajuan_surat/';
            $file->storeAs($dest, $file_name, 'public');
            $lampiran_path = $dest . $file_name;
        }

        $result = PengajuanSurat::insup_pengajuan_surat(
            $id_log_surat,
            $request->perihal,
            $unit_bagian_penerima,
            $personal_penerima,
            $request->isi_surat,
            $request->tanggal_surat,
            $request->id_jenis_surat,
            $id_personal_pengaju,
            $unit_bagian_pengirim,
            $lampiran_path,
            false
        );

        if ($result->status) {
            Session::flash('success_message', $result->keterangan);

            return redirect()->route('karyawan.surat_menyurat.pengajuan_surat.index');
        } else {
            if ($lampiran_path && Storage::disk('public')->exists($lampiran_path)) {
                Storage::disk('public')->delete($lampiran_path);
            }
            Session::flash('failed_message', $result->keterangan);
        }

        return redirect()->back();
    }

    // ─── Delete ────────────────────────────────────────────────────────────────

    public function delete_pengajuan_surat(Request $request)
    {
        $request->validate([
            'id_log_surat' => 'required',
        ]);

        $data = PengajuanSurat::delete_pengajuan_surat($request->id_log_surat);

        if ($data->status) {
            if (!empty($data->path_lampiran) && Storage::disk('public')->exists($data->path_lampiran)) {
                Storage::disk('public')->delete($data->path_lampiran);
            }
        }

        return response()->json($data, 200);
    }

    // ─── Detail (untuk modal edit) ─────────────────────────────────────────────

    public function detail_pengajuan_surat(Request $request)
    {
        $request->validate([
            'id_log_surat' => 'required',
        ]);

        $data = PengajuanSurat::detail_pengajuan_surat($request->id_log_surat);

        if ($data && empty($data->nama_unit_pengirim) && !empty($data->unit_bagian_pengirim)) {
            $unit_map = [];
            foreach (UnitKerja::get_daftar_unit_kerja('') as $unit) {
                if (!empty($unit->id_unit_bagian)) {
                    $unit_map[$unit->id_unit_bagian] = $unit->nama_unit_bagian ?? $unit->nama_unit_kerja ?? null;
                }
            }

            $data->nama_unit_pengirim = $unit_map[$data->unit_bagian_pengirim] ?? $data->unit_bagian_pengirim;
        }

        return response()->json($data, 200);
    }

    // ─── Teruskan ke Pimpinan ───────────────────────────────────────────────

    public function teruskan_pimpinan(Request $request)
    {
        $request->validate([
            'id_log_surat' => 'required',
            'id_personal_pimpinan' => 'required',
        ]);

        $user = Session::get('user');
        $id_personal_aktor = $user->id_personal ?? null;
        if (!$id_personal_aktor) {
            return response()->json([
                'status' => false,
                'keterangan' => 'ID personal aktor tidak ditemukan',
            ], 422);
        }

        $detail = PengajuanSurat::detail_pengajuan_surat($request->id_log_surat);
        if (!$detail) {
            return response()->json([
                'status' => false,
                'keterangan' => 'Pengajuan surat tidak ditemukan',
            ], 404);
        }

        if (!empty($detail->id_personal_pimpinan) || !empty($detail->sudah_disetujui)) {
            return response()->json([
                'status' => false,
                'keterangan' => 'Pengajuan sudah diteruskan ke pimpinan',
            ], 422);
        }

        $statusResult = PengajuanSurat::set_status_pengajuan_surat_rektorat(
            $detail->id_log_surat,
            2,
            $id_personal_aktor,
            $request->id_personal_pimpinan
        );

        $statusOk = false;
        if ($statusResult && isset($statusResult->status)) {
            $statusOk = in_array($statusResult->status, [true, 1, '1', 't', 'true'], true);
        }

        return response()->json([
            'status' => $statusOk,
            'keterangan' => $statusOk ? 'Berhasil diteruskan ke pimpinan' : ($statusResult->keterangan ?? 'Gagal mengubah status'),
            'id_log_surat' => $detail->id_log_surat,
            'nomor_surat' => $detail->nomor_surat ?? null,
        ], $statusOk ? 200 : 422);
    }

    public function download_pdf($id_log_surat)
    {
        try {
            $user = Session::get('user');
            if (!$user) {
                return response()->json(['status' => '0', 'keterangan' => 'Session user tidak ditemukan'], 401);
            }

            $detail = PengajuanSurat::detail_pengajuan_surat($id_log_surat);
            if (!$detail) {
                return response()->json(['status' => '0', 'keterangan' => 'Pengajuan tidak ditemukan'], 404);
            }

            $status = strtoupper((string) ($detail->status_surat ?? ''));
            if (strpos($status, 'DISETUJUI') === false) {
                return response()->json(['status' => '0', 'keterangan' => 'Surat hanya dapat diunduh jika sudah disetujui pimpinan'], 422);
            }

            // Logo
            $logo = null;
            $logoPath = public_path('image/logo-uij.png');
            if (file_exists($logoPath)) {
                $logo = base64_encode(file_get_contents($logoPath));
            }

            // QR Code Pimpinan (penandatangan)
            $qrIdPimpinan = $detail->id_tandatangan_dokumen_pimpinan ?? $id_log_surat;
            $qrCodePimpinan = base64_encode(
                QrCode::format('svg')->size(200)->margin(1)->errorCorrection('H')
                    ->generate(route('frontpage.detail_qr', ['id' => base64_encode($qrIdPimpinan)]))
            );

            // QR Code Pengaju (unit kerja pengirim)
            $qrIdPengaju = $detail->id_tandatangan_dokumen_pengaju ?? $id_log_surat;
            $qrCodePengaju = base64_encode(
                QrCode::format('svg')->size(200)->margin(1)->errorCorrection('H')
                    ->generate(route('frontpage.detail_qr', ['id' => base64_encode($qrIdPengaju)]))
            );

            $tanggalCetak = Carbon::now()->translatedFormat('d F Y');

            $pdf = DomPDFFacade::loadView('karyawan_page.surat_menyurat.pdf_pengajuan_surat', [
                'detail'            => $detail,
                'logo'              => $logo,
                'tanggal_cetak'     => $tanggalCetak,
                'qr_code_pimpinan'  => $qrCodePimpinan,
                'qr_code_pengaju'   => $qrCodePengaju,
            ])->setPaper('a4', 'portrait');

            $fileName = 'surat-' . ($detail->nomor_surat
                ? str_replace('/', '-', $detail->nomor_surat)
                : $id_log_surat) . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            return response()->json(['status' => '0', 'keterangan' => $e->getMessage()], 500);
        }
    }
}
