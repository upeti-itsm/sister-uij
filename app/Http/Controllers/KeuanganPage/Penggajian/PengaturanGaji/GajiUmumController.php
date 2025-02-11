<?php

namespace App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GajiUmumController extends Controller
{
    public function index(){
        $menu = "Pengaturan Gaji - Gaji Umum";
        return view('keuangan_page.penggajian.pengaturan_gaji.gaji_umum', compact('menu'));
    }
}
