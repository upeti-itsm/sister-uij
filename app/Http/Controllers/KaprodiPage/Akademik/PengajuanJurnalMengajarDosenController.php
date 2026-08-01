<?php

namespace App\Http\Controllers\KaprodiPage\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Kaprodi\PengajuanJurnalMengajarDosen;
use Illuminate\Http\Request;

class PengajuanJurnalMengajarDosenController extends Controller
{
    public function index()
    {
        $menu = 'Pengajuan Jurnal Mengajar Dosen';
        return view('kaprodi_page.akademik.pengajuan_jurnal_mengajar_dosen.index', compact('menu'));
    }

    public function json(Request $request)
    {
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $record = PengajuanJurnalMengajarDosen::get_jurnal_mengajar_dosen(session()->get('user')->id_personal, 'kaprodi', null, $request->status, $search, $start, $length);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($record) > 0)
            $data['recordsTotal'] = $record[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $record;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function set_status_ajuan(Request $request)
    {
        $request->validate([
            'id_jurnal' => 'required|uuid',
            'status' => 'required|integer',
        ], [
            'id_jurnal.required' => 'Jurnal mengajar harus dipilih',
            'status.required' => 'Status pengajuan harus dipilih',
        ]);

        $id_personal = session()->get('user')->id_personal;
        $catatan = $request->catatan ?? null;

        // Ajukan jurnal mengajar ke database
        $pengajuan_jurnal = PengajuanJurnalMengajarDosen::set_status_ajuan_oleh_kaprodi($request->id_jurnal, $request->status, $id_personal, $catatan);

        if ($pengajuan_jurnal) {
            if (isset($pengajuan_jurnal->status)) {
                if ($pengajuan_jurnal->status == 1 || $pengajuan_jurnal->status === true || $pengajuan_jurnal->status === 't') {
                    return response()->json(['status' => true, 'keterangan' => 'Status pengajuan jurnal mengajar berhasil diubah'], 200);
                } else {
                    return response()->json(['status' => false, 'keterangan' => $pengajuan_jurnal->keterangan], 200);
                }
            } else {
                return response()->json(['status' => false, 'keterangan' => 'Terjadi kesalahan saat mengubah status pengajuan jurnal mengajar'], 200);
            }
        } else {
            return response()->json(['status' => false, 'keterangan' => 'Terjadi kesalahan saat mengubah status pengajuan jurnal mengajar'], 200);
        }
    }
}
