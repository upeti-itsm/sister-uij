<?php

namespace App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah;

use App\Http\Controllers\Controller;
use App\Models\Absensi\IpPresensiPerkuliahan;
use Illuminate\Http\Request;

class IpPresensiPerkuliahanController extends Controller
{
    public function index()
    {
        $menu = 'IP Presensi Perkuliahan';
        return view('admin_akademik_page.akademik.matakuliah.ip_presensi_perkuliahan', compact('menu'));
    }

    public function json(Request $request)
    {
        $status = $request->status;
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];
        $data_ = IpPresensiPerkuliahan::get_data($start, $length, $search, $status);
        $data['draw'] = $_REQUEST['draw'];
        $data['recordsTotal'] = 0;
        if (sizeof($data_) > 0)
            $data['recordsTotal'] = $data_[0]->jml_record;
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $data_;
        $data['error'] = null;
        return response()->json($data, 200);
    }

    public function insup(Request $request)
    {
        $request->validate([
            'alamat_ip' => 'required',
            'sts_aktif' => 'required'
        ]);

        $id = $request->id ?? 0;
        $data = IpPresensiPerkuliahan::insup($id, $request->alamat_ip, $request->sts_aktif);

        if ($data->status === 1) {
            session()->flash('success_message', $data->keterangan);
        } else {
            session()->flash('failed_message', $data->keterangan);
        }

        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $data = IpPresensiPerkuliahan::hapus($request->id);

        if ($data->status == 1) {
            session()->flash('success_message', $data->keterangan);
        } else {
            session()->flash('failed_message', $data->keterangan);
        }

        return redirect()->back();
    }
}
