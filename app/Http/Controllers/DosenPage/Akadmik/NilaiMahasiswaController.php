<?php

namespace App\Http\Controllers\DosenPage\Akadmik;

use App\Http\Controllers\Controller;
use App\Models\SIAKAD_MODEL\JadwalDosen;
use Illuminate\Http\Request;

class NilaiMahasiswaController extends Controller
{
    public function index($id)
    {
        $menu = 'Dosen - Akademik - Melihat Daftar Matakuliah';
        $mahasiswa = JadwalDosen::get_list_mahasiswa_by_jadwal($id);
//        dd($mahasiswa);
        return view('dosen_page.akademik.nilai_mahasiswa', compact('menu', 'mahasiswa'));
    }

    public function store_nilai(Request $request) {
        $request->validate([
            'id_kriteria' => 'required',
            'nim' => 'required',
            'nilai' => 'required',
        ]);

        $result = JadwalDosen::insup_nilai($request->id_kriteria, $request->nim, $request->nilai);
        return response()->json($result);
    }
}
