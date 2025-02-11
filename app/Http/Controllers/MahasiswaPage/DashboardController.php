<?php

namespace App\Http\Controllers\MahasiswaPage;

use App\Http\Controllers\Controller;
use App\Models\SIAKAD_MODEL\tblMahasiswa;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function get_index_prestasi(Request $request){
        $request->validate([
            'nim' => 'required'
        ]);
        $index = tblMahasiswa::getIndexPrestasi($request->nim);
        return response()->json($index);
    }
}
