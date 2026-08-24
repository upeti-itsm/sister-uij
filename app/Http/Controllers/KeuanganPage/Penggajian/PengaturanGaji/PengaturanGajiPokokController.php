<?php

namespace App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\GajiPokok;
use Illuminate\Http\Request;

class PengaturanGajiPokokController extends Controller
{
    public function index()
    {
        $menu = 'Pengaturan Gaji Pokok';
        return view('keuangan_page.penggajian.pengaturan_gaji.gaji_pokok', compact('menu'));
    }

    public function get_data(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];

        $data_ = GajiPokok::get_tunjangan_pendidikans(
            null,
            $request->id_jenis_karyawan ? $request->id_jenis_karyawan : null,
            $search,
            $start,
            $length
        );

        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function get_jenis_karyawan(Request $request)
    {
        try {
            $data_ = GajiPokok::list_jenis_karyawan();
        } catch (\Exception $e) {
            \Log::error('Gagal mengambil list jenis karyawan: ' . $e->getMessage());
            return response()->json(['results' => [], 'error' => $e->getMessage()], 500);
        }

        $results = collect($data_)->map(function ($item) {
            return [
                'id' => $item->id_jenis_karyawan,
                'text' => $item->jenis_karyawan
            ];
        });

        return response()->json(['results' => $results]);
    }

    public function get_pendidikan(Request $request)
    {
        $search = $request->search ?? '';

        try {
            $data_ = GajiPokok::list_pendidikan($search);
        } catch (\Exception $e) {
            \Log::error('Gagal mengambil list pendidikan: ' . $e->getMessage());
            return response()->json(['results' => [], 'error' => $e->getMessage()], 500);
        }

        $results = collect($data_)->map(function ($item) {
            return [
                'id' => $item->kd_pendidikan_terakhir,
                'text' => $item->pendidikan
            ];
        });

        return response()->json(['results' => $results]);
    }

    public function insert_nominal(Request $request)
    {
        $request->validate([
            'id_jenis_karyawan' => 'required',
            'kd_pendidikan' => 'required',
            'nominal_tunjangan' => 'required'
        ], [
            'id_jenis_karyawan.required' => 'Jenis Karyawan Wajib Dipilih',
            'kd_pendidikan.required' => 'Jenis Pendidikan Wajib Dipilih',
            'nominal_tunjangan.required' => 'Nominal Tunjangan Wajib Diisi'
        ]);

        $nominal = preg_replace('/[^\d]/', '', (string)$request->nominal_tunjangan);
        $nominal = !empty($nominal) ? (int)$nominal : 0;

        $res = GajiPokok::insert_tunjangan_pendidikan(
            $request->id_jenis_karyawan,
            $request->kd_pendidikan,
            $nominal,
            true // data baru selalu aktif
        );

        return response()->json([
            'status' => isset($res->status) ? (int)$res->status : 0,
            'keterangan' => $res->keterangan ?? 'Terjadi kesalahan sistem'
        ]);
    }

    public function update_nominal(Request $request)
    {
        $request->validate([
            'id_config_tunjangan_pendidikan' => 'required',
            'nominal_tunjangan' => 'required',
            'sts_aktif' => 'required'
        ], [
            'nominal_tunjangan.required' => 'Nominal Tunjangan Wajib Diisi'
        ]);

        $nominal = preg_replace('/[^\d]/', '', (string)$request->nominal_tunjangan);
        $nominal = !empty($nominal) ? (int)$nominal : 0;
        $statusBool = filter_var($request->sts_aktif, FILTER_VALIDATE_BOOLEAN);

        $res = GajiPokok::update_tunjangan_pendidikan(
            $request->id_config_tunjangan_pendidikan,
            $nominal,
            $statusBool
        );

        return response()->json([
            'status' => isset($res->status) ? (int)$res->status : 0,
            'keterangan' => $res->keterangan ?? 'Terjadi kesalahan sistem'
        ]);
    }

    public function set_status(Request $request)
    {
        $request->validate([
            'id_config_tunjangan_pendidikan' => 'required',
            'nominal_tunjangan' => 'required',
            'sts_aktif' => 'required'
        ]);

        $statusBool = filter_var($request->sts_aktif, FILTER_VALIDATE_BOOLEAN);

        $res = GajiPokok::update_tunjangan_pendidikan(
            $request->id_config_tunjangan_pendidikan,
            $request->nominal_tunjangan,
            $statusBool
        );

        return response()->json([
            'status' => isset($res->status) ? (int)$res->status : 0,
            'keterangan' => $res->keterangan ?? 'Terjadi kesalahan sistem'
        ]);
    }
}
