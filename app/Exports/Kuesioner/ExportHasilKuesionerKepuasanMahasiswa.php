<?php

namespace App\Exports\Kuesioner;

use App\Models\Kuesioner\RekapitulasiKepuasanMahasiswa;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;

class ExportHasilKuesionerKepuasanMahasiswa extends DefaultValueBinder implements WithCustomValueBinder, FromView
{
    protected $id_jenis;
    protected $id_semester;

    public function __construct($id_jenis = 1, $id_semester = 0)
    {
        $this->id_jenis = $id_jenis;
        $this->id_semester = $id_semester;
    }

    public function view(): View
    {
        $data = RekapitulasiKepuasanMahasiswa::export_hasil_kuesioner($this->id_jenis, $this->id_semester);
        $kolom = explode(';', $data[0]->pernyataan);
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        return view('bpm_page.kuesioner.kuesioner_kepuasan_mahasiswa.excel.kepuasan_mahasiswa_terhadap_kinerja_manajemen', compact('data', 'tgl', 'kolom'));
    }
}
