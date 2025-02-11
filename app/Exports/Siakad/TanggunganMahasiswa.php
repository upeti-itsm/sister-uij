<?php

namespace App\Exports\Siakad;

use App\Models\SIAKAD_MODEL\keu_tblTanggunganMahasiswa;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TanggunganMahasiswa extends DefaultValueBinder implements WithCustomValueBinder, WithColumnFormatting, FromView
{
    protected $batas;
    protected $reg;
    protected $regm;
    protected $trf;
    protected $trfm;

    public function __construct($batas = 0, $reg = 'REG', $regm = 'REGM', $trf = 'TRF', $trfm = 'TRFM')
    {
        $this->batas = $batas;
        $this->reg = $reg;
        $this->regm = $regm;
        $this->trf = $trf;
        $this->trfm = $trfm;
    }

    public function view(): View
    {
        $mahasiswa = keu_tblTanggunganMahasiswa::get_daftar_tanggungan($this->batas, $this->reg, $this->regm, $this->trf, $this->trfm, "", -1, 0);
        return view('super_admin_page.siakad.export.tanggungan_mahasiswa', compact('mahasiswa'));
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
            'F' => NumberFormat::FORMAT_TEXT
        ];
    }
}
