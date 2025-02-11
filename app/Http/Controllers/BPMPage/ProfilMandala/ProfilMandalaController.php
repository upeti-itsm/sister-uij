<?php

namespace App\Http\Controllers\BPMPage\ProfilMandala;

use App\Http\Controllers\Controller;
use App\Models\Organisasi\Misi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class ProfilMandalaController extends Controller
{
    public function index()
    {
        $menu = "Profil Mandala";
        $misi = Misi::get_misi();
        $visi = Misi::get_visi();
        $dokumen = Misi::get_struktur();
        return view('bpm_page.profil_mandala.profil_mandala', compact('menu', 'misi', 'visi', 'dokumen'));
    }

    public function update_visi(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'visi' => 'required'
        ]);
        $data = Misi::update_visi($request->id, $request->visi);
        Session::flash($data->status == 1 ? 'success_message' : 'failed_message', $data->keterangan);
        return redirect()->back();
    }

    public function insup_misi(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'misi' => 'required',
            'nomor' => 'required'
        ]);
        $data = Misi::insup_misi($request->nomor, $request->misi, $request->id);
        Session::flash($data->status == 1 ? 'success_message' : 'failed_message', $data->keterangan);
        return redirect()->back();
    }

    public function delete_misi(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $data = Misi::delete_misi($request->id);
        Session::flash($data->status == 1 ? 'success_message' : 'failed_message', $data->keterangan);
        return redirect()->back();
    }

    public function update_struktur(Request $request)
    {
        $request->validate([
            'file_so' => 'required|max:10000|mimes:jpg,jpeg,png'
        ], [
            'file_so.required' => 'Pastikan file Struktur Organisasi sudah di upload',
            'file_so.max' => 'Pastikan ukuran file Struktur Organisasi tidak lebih dari 10Mb',
            'file_so.mimes' => 'Pastikan file Struktur Organisasi dalam format jpg/jpeg/png',
        ]);

        $file_name_so = null;
        if (!is_null($request->file_so)) {
            $t = time();
            $file_so = $request->file('file_so');
            $file_name_so = 'struktur_organisasi_' . date("Y_m_d_h_m_s", $t) . '.' . $file_so->getClientOriginalExtension();
        }

        $dokumen = Misi::update_dokumen('files/profil_mandala/'.$file_name_so);
        if ($dokumen->status == 1) {
            if (!is_null($request->file_so)) {
                // File SO
                $destinationPath = 'files/profil_mandala/';
                File::makeDirectory($destinationPath, $mode = 0777, true, true);
                $file_so->move($destinationPath, $file_name_so);

                if (File::exists(public_path($dokumen->nama_struktur_organisasi))) {
                    File::delete(public_path($dokumen->nama_struktur_organisasi));
                }
            }
            Session::flash('success_message', $dokumen->keterangan);
            return redirect()->back();
        } else {
            Session::flash('failed_message', $dokumen->keterangan);
            return redirect()->back()->withInput();
        }
    }
}
