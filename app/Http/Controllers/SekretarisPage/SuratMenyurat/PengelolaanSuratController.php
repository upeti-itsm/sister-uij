<?php

namespace App\Http\Controllers\SekretarisPage\SuratMenyurat;

use App\Http\Controllers\Controller;
use App\Models\Sekretaris\PengelolaanSurat;
use Illuminate\Http\Request;

class PengelolaanSuratController extends Controller
{
    public function index()
    {
        $menu = 'Pengelolaan Surat';
        $status = PengelolaanSurat::get_all_status_pengajuan();
        $status = collect($status);

        return view('sekretaris_page.surat_menyurat.pengelolaan_surat.index', compact('menu', 'status'));
    }

    public function create()
    {
        $menu = 'Pengelolaan Surat';
        $jenis_surat = PengelolaanSurat::get_all_jenis_surat();
        $jenis_surat = collect($jenis_surat);

        $pimpinan_rektorat = PengelolaanSurat::get_all_pimpinan_rektorat();
        $pimpinan_rektorat = collect($pimpinan_rektorat);

        return view('sekretaris_page.surat_menyurat.pengelolaan_surat.create', compact('menu', 'jenis_surat', 'pimpinan_rektorat'));
    }

    public function insup_surat(Request $request)
    {
        $request->validate([
            'perihal_surat' => 'required|string|max:255',
            'tanggal_surat' => 'nullable|date',
            'jenis_surat' => 'required|integer',
            'isi_surat' => 'required|string'
        ], [
            'perihal_surat.required' => 'Perihal surat harus diisi.',
            'perihal_surat.string' => 'Perihal surat harus berupa teks.',
            'perihal_surat.max' => 'Perihal surat tidak boleh lebih dari 255 karakter.',
            'tanggal_surat.date' => 'Tanggal surat harus berupa tanggal yang valid.',
            'jenis_surat.required' => 'Jenis surat harus dipilih.',
            'jenis_surat.integer' => 'Jenis surat harus berupa angka.',
            'isi_surat.required' => 'Isi surat harus diisi.',
            'isi_surat.string' => 'Isi surat harus berupa teks.'
        ]);

        $personal = session()->get('user')->id_personal;
        $unit_bagian = session()->get('user')->id_unit_kerja;

        $result = PengelolaanSurat::insup_surat(
            null,
            $request->perihal_surat,
            null,
            null,
            $request->isi_surat,
            $request->tanggal_surat,
            $request->jenis_surat,
            $personal,
            $unit_bagian,
            null,
            true
        );

        dd($result);
    }
}
