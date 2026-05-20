<?php

namespace App\Http\Controllers\SekretarisPage\SuratMenyurat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengelolaanSuratController extends Controller
{
    public function index()
    {
        $menu = 'Pengelolaan Surat';
        return view('sekretaris_page.surat_menyurat.pengelolaan_surat.index', compact('menu'));
    }
}
