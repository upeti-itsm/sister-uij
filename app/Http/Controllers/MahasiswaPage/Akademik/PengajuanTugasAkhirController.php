<?php

namespace App\Http\Controllers\MahasiswaPage\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengajuanTugasAkhirController extends Controller
{
    public function index(){
        $menu = "Pengajuan Tugas Akhir";
        return view('mahasiswa_page.akademik.tugas_akhir', compact('menu'));
    }
}
