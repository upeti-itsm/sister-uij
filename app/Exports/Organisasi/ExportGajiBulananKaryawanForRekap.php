<?php

namespace App\Exports\Organisasi;

use App\Models\Organisasi\RekapitulasiGajiBulanan;
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

class ExportGajiBulananKaryawanForRekap extends DefaultValueBinder implements WithCustomValueBinder, WithColumnFormatting, FromView
{
    protected $periode;
    protected $tahun;

    public function __construct($periode, $tahun)
    {
        $this->periode = $periode;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        $data = RekapitulasiGajiBulanan::export_gaji_bulanan_for_rekap($this->periode, $this->tahun);
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        $tgl_ttd = $tgl->format('d F Y');
        $bulan = $this->periode;
        $tahun = $this->tahun;
        return view('keuangan_page.penggajian.excel.excel_for_rekap', compact('data', 'tgl_ttd', 'bulan', 'tahun'));
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
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'E' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'F' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'G' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'H' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'I' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'J' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'K' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'L' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'M' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'N' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'O' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'P' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'Q' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'R' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'S' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'T' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'U' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'V' => NumberFormat::FORMAT_ACCOUNTING_USD,
            'W' => NumberFormat::FORMAT_ACCOUNTING_USD,
        ];
    }
}
