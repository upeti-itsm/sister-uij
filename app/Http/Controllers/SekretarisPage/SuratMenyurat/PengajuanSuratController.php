<?php

namespace App\Http\Controllers\SekretarisPage\SuratMenyurat;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\Karyawan;
use App\Models\Organisasi\PengajuanSurat;
use App\Models\Organisasi\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class PengajuanSuratController extends Controller
{
    public function index()
    {
        $menu        = 'Pengajuan Surat';
        $jenis_surat = PengajuanSurat::daftar_jenis_surat();
        $status_surat = PengajuanSurat::daftar_status_surat();
        $pimpinan_rektorat = PengajuanSurat::daftar_pimpinan_rektorat();
        $karyawan    = Karyawan::get_daftar_karyawan();

        return view(
            'sekretaris_page/surat_menyurat/pengajuan_surat',
            compact('menu', 'jenis_surat', 'status_surat', 'pimpinan_rektorat', 'karyawan')
        );
    }

    // ─── DataTable JSON ────────────────────────────────────────────────────────

    public function json_daftar_pengajuan_surat(Request $request)
    {
        $length      = (int) $request->input('length', 10);
        $start       = (int) $request->input('start', 0);
        $search      = $request->input('search.value');
        $user        = Session::get('user');
        $id_personal = $user->id_personal ?? null;
        $id_unit_bagian = $user->id_unit_bagian ?? null;

        $akses_all = $request->input('akses_all', true);
        if ($akses_all) {
            $id_personal = null;
            $id_unit_bagian = null;
        }

        $id_status_surat = $request->input('status_surat');
        $id_jenis_surat  = $request->input('jenis_surat');
        $tanggal_dari    = $request->input('tanggal_dari');
        $tanggal_sampai  = $request->input('tanggal_sampai');
        $sts_aktif       = $request->input('sts_aktif', true);

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
            'unit_bagian_pengirim'  => 'required',
            'pimpinan_penerima'      => 'required',
            'lampiran'              => 'nullable|mimes:pdf|max:5000',
        ], [
            'perihal.required'              => 'Pastikan Perihal Surat terisi',
            'tanggal_surat.required'        => 'Pastikan Tanggal Surat terisi',
            'isi_surat.required'            => 'Pastikan Isi Surat terisi',
            'id_jenis_surat.required'       => 'Pastikan Jenis Surat dipilih',
            'unit_bagian_pengirim.required' => 'Pastikan Unit / Bagian Pengirim dipilih',
            'pimpinan_penerima.required'     => 'Pastikan Pimpinan Rektorat dipilih',
            'lampiran.mimes'                => 'File lampiran harus berformat PDF',
            'lampiran.max'                  => 'Ukuran lampiran tidak boleh lebih dari 5MB',
        ]);

        // Penerima: bisa unit bagian dan/atau personal
        $unit_bagian_penerima = $request->unit_bagian_penerima ?? [];
        $personal_penerima    = $request->personal_penerima    ?? [];
        $pimpinan_penerima    = $request->pimpinan_penerima;

        if (!empty($pimpinan_penerima)) {
            $personal_penerima[] = $pimpinan_penerima;
            $personal_penerima = array_values(array_unique($personal_penerima));
        }

        // Jika keduanya kosong anggap kosong (function DB yang validasi)
        $id_log_surat = $request->id_log_surat ?: (string) Uuid::uuid4();

        // Handle file lampiran
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
            $request->unit_bagian_pengirim,
            $lampiran_path
        );

        if ($result->status) {
            Session::flash('success_message', $result->keterangan);
        } else {
            // Hapus file yang sudah terlanjur diupload jika DB gagal
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
            // Hapus file lampiran jika ada
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
}
