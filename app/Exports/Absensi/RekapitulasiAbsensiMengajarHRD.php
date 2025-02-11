<?php

namespace App\Exports\Absensi;

use App\Models\Absensi\RekapitulasiAbsensiMengajarDosen;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RekapitulasiAbsensiMengajarHRD extends DefaultValueBinder implements WithCustomValueBinder, WithColumnFormatting, FromView
{
    protected $tgl_awal;
    protected $tgl_akhir;
    protected $id_personal;

    public function __construct($tgl_awal, $tgl_akhir, $id_personal = NULL)
    {
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
        $this->id_personal = $id_personal;
    }

    public function view(): View
    {
        $rekap = RekapitulasiAbsensiMengajarDosen::getRekapitulasiByPersonalOnHrd($this->tgl_awal, $this->tgl_akhir, $this->id_personal);
        $tgl_awal = $this->tgl_awal;
        $tgl_akhir = $this->tgl_akhir;
        return view('hrd_page.akademik.excel.rekapitulasi_absensi_mengajar_hrd', compact('rekap', 'tgl_awal', 'tgl_akhir'));
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }
        return parent::bindValue($cell, $value);
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER
        ];
    }
}
