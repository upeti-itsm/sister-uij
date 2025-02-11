<?php

namespace App\Exports\Wisuda;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class PendaftarWisuda extends DefaultValueBinder implements WithCustomValueBinder, WithColumnFormatting, FromView
{
    protected $status_pengajuan;
    protected $kd_prodi;
    protected $konsen;

    public function __construct($status_pengajuan, $kd_prodi, $kd_konsen = 'all')
    {
        $this->status_pengajuan = $status_pengajuan;
        $this->kd_prodi = $kd_prodi;
        $this->kd_konsen = $kd_konsen;
    }

    public function view(): View
    {
        $pendaftar = \App\Models\Akademik\PendaftarWisuda::get_daftar_pengajuan_wisuda('00000000-0000-0000-0000-000000000000', '', 0, -1, $this->status_pengajuan, $this->kd_prodi, $this->kd_konsen);
        return view('admin_akademik_page.akademik.wisuda.export.excel.pendaftar_wisuda', compact('pendaftar'));
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

        ];
    }
}
