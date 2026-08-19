<?php

namespace App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji;

use App\Exports\Organisasi\TemplateGolonganExport;
use App\Http\Controllers\Controller;
use App\Imports\Organisasi\GolonganImport;
use App\Models\Organisasi\Golongan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PengaturanGajiPokokController extends Controller
{
    public function index()
    {
        $menu = 'Pengaturan Gaji - Gaji Pokok';
        return view('keuangan_page.penggajian.pengaturan_gaji.gaji_pokok', compact('menu'));
    }

    public function get_golongan(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = Golongan::get_golongan(
            $start,
            $length,
            $search,
            $request->status ? $request->status : null
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

    public function insup_golongan(Request $request)
    {
        $request->validate([
            'id_golongan' => 'required',
            'golongan' => 'required',
            'masa_kerja' => 'required|numeric',
            'gaji_pokok' => 'required'
        ], [
            'golongan.required' => 'Golongan Wajib Diisi',
            'masa_kerja.required' => 'Masa Kerja Wajib Diisi',
            'gaji_pokok.required' => 'Gaji Pokok Wajib Diisi'
        ]);

        $gaji_pokok = preg_replace('/[^\d]/', '', (string)$request->gaji_pokok);
        $gaji_pokok = !empty($gaji_pokok) ? (int)$gaji_pokok : 0;

        $res = Golongan::insup_golongan(
            $request->id_golongan,
            $request->golongan,
            $request->masa_kerja,
            $gaji_pokok
        );

        return response()->json([
            'status' => $res->status ?? 0,
            'keterangan' => $res->keterangan ?? 'Terjadi kesalahan sistem'
        ]);
    }

    public function set_status(Request $request)
    {
        $request->validate([
            'id_golongan' => 'required',
            'status' => 'required'
        ]);

        $statusBool = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);

        $res = Golongan::set_status_golongan(
            $request->id_golongan,
            $statusBool
        );

        return response()->json([
            'status' => $res->status ?? 0,
            'keterangan' => $res->keterangan ?? 'Terjadi kesalahan sistem'
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:2048'
        ], [
            'file_excel.required' => 'Pilih file excel terlebih dahulu',
            'file_excel.mimes' => 'Format file harus berupa xlsx, xls, atau csv',
            'file_excel.max' => 'Ukuran file maksimal 2MB'
        ]);

        try {
            Excel::import(new GolonganImport, $request->file('file_excel'));
            session()->flash('success_message', 'Import Data Gaji Pokok Berhasil');
        } catch (\Exception $e) {
            session()->flash('failed_message', 'Gagal mengimpor data: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    public function download_template()
    {
        return Excel::download(new TemplateGolonganExport, 'Template_Import_Gaji_Pokok.xlsx');
    }
}
