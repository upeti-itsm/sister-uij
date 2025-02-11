<?php

namespace App\Exports\Organisasi;

use App\Models\Organisasi\RekapitulasiGajiBulanan;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ExportGajiBulananKaryawanForBank extends DefaultValueBinder implements WithCustomValueBinder, WithColumnFormatting, FromView
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
        $data = RekapitulasiGajiBulanan::export_gaji_bulanan_for_bank($this->periode, $this->tahun);
        Carbon::setLocale('id');
        $tgl = Carbon::now('Asia/Jakarta');
        $tgl_ttd = $tgl->format('d F Y');
        return view('keuangan_page.penggajian.excel.excel_for_bank', compact('data', 'tgl_ttd'));
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
            'D' => NumberFormat::FORMAT_ACCOUNTING_USD
        ];
    }
}
