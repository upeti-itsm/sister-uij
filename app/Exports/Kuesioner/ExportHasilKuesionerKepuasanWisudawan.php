<?php

namespace App\Exports\Kuesioner;

use App\Models\Kuesioner\RekapitulasiKepuasanWisudawan;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;

class ExportHasilKuesionerKepuasanWisudawan extends DefaultValueBinder implements WithCustomValueBinder, FromView
{
    protected $id_mahasiswa;
    protected $tahun;

    public function __construct($id_mahasiswa = 0)
    {
        $this->id_mahasiswa = $id_mahasiswa;
    }

    public function view(): View
    {
        $data = RekapitulasiKepuasanWisudawan::export_hasil_kuesioner($this->id_mahasiswa);
        $kolom = explode(';', $data[0]->pernyataan);
        $kolom1 = explode(';', $data[0]->unsur_penilaian);
        $header = array();
        foreach ($kolom1 as $item) {
            if (!array_key_exists($item, $header)) {
                $it = explode('#', $item);
                $header[$it[0]] = $it[1];
            }
        }
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        return view('bpm_page.kuesioner.kuesioner_kepuasan_wisudawan.excel', compact('data', 'tgl', 'kolom', 'header'));
    }
}
