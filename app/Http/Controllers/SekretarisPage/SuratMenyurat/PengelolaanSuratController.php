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
}
