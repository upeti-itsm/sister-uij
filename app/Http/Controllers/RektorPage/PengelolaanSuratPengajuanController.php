<?php

namespace App\Http\Controllers\RektorPage;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\Karyawan;
use App\Models\Organisasi\PengajuanSurat;
use App\Models\Organisasi\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PengelolaanSuratPengajuanController extends Controller
{
    public function index()
    {
        $menu = 'Pengelolaan Surat';
        $jenis_surat = PengajuanSurat::daftar_jenis_surat();
        $status_surat = PengajuanSurat::daftar_status_surat();
        $pimpinan_rektorat = PengajuanSurat::daftar_pimpinan_rektorat();
        $karyawan = Karyawan::get_daftar_karyawan();
        $unit_kerja = UnitKerja::get_daftar_unit_kerja('');

        return view(
            'rektor_page/pengajuan_surat/pengajuan_surat',
            compact('menu', 'jenis_surat', 'status_surat', 'pimpinan_rektorat', 'karyawan', 'unit_kerja')
        );
    }

    public function json_daftar_pengajuan_surat(Request $request)
    {
        $length = (int) $request->input('length', 10);
        $start = (int) $request->input('start', 0);
        $search = $request->input('search.value');

        $id_status_surat = $request->input('status_surat');
        $id_jenis_surat = $request->input('jenis_surat');
        $id_unit_filter = $request->input('unit_kerja');
        $tanggal_dari = $request->input('tanggal_dari');
        $tanggal_sampai = $request->input('tanggal_sampai');
        $sts_aktif = $request->input('sts_aktif', true);
        $user = Session::get('user');
        $id_personal = $user->id_personal ?? null;

        if ($length < 1) {
            $length = 10;
        }
        if ($start < 0) {
            $start = 0;
        }
        $page = (int) floor($start / $length) + 1;

        $data_ = PengajuanSurat::daftar_pengajuan_surat(
            $id_personal,
            $id_unit_filter,
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

        $data['draw'] = $request->input('draw');
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0) {
            $data['recordsTotal'] = $data_[0]->jml_record;
        }
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;

        return response()->json($data, 200);
    }

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

    public function set_status_pengajuan_surat(Request $request)
    {
        $request->validate([
            'id_log_surat' => 'required',
            'id_status_surat' => 'required',
        ]);

        $user = Session::get('user');
        $id_personal_aktor = $user->id_personal ?? null;
        if (!$id_personal_aktor) {
            return response()->json([
                'status' => false,
                'keterangan' => 'ID personal aktor tidak ditemukan',
            ], 422);
        }

        $statusResult = PengajuanSurat::set_status_pengajuan_surat_rektorat(
            $request->id_log_surat,
            $request->id_status_surat,
            $id_personal_aktor,
            $id_personal_aktor,
            $request->input('catatan')
        );

        $statusOk = false;
        if ($statusResult && isset($statusResult->status)) {
            $statusOk = in_array($statusResult->status, [true, 1, '1', 't', 'true'], true);
        }

        return response()->json([
            'status' => $statusOk,
            'keterangan' => $statusOk ? 'Status surat berhasil diperbarui' : ($statusResult->keterangan ?? 'Gagal mengubah status'),
            'id_log_surat' => $request->id_log_surat,
        ], $statusOk ? 200 : 422);
    }
}
