<?php

namespace App\Exports\Akademik;

use App\Models\Akademik\Semester;
use App\Models\Organisasi\HonorariumKoreksi;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RekapInternalHonorariumKoreksi extends DefaultValueBinder implements WithCustomValueBinder, WithColumnFormatting, FromView
{
    protected $ta;

    public function __construct($ta)
    {
        $this->ta = $ta;
    }

    public function view(): View
    {
        $data = HonorariumKoreksi::export_honorarium_for_rekap($this->ta);
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        $tgl_ttd = $tgl->format('d F Y');
        $semester = Semester::get_semester(0, 1, $this->ta)[0]->nama_tahun_akademik;
        return view('keuangan_page.penggajian.honorarium.excel.excel_for_rekap_honor_koreksi', compact('data', 'tgl_ttd', 'semester'));
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
            'A' => NumberFormat::FORMAT_NUMBER,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_NUMBER_00,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'H' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'J' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'K' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'L' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'M' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'N' => NumberFormat::FORMAT_ACCOUNTING_USD,
        ];
    }
}
