<?php

namespace App\Imports\Organisasi;

use App\Models\Organisasi\Golongan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class GolonganImport implements ToCollection
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row)
        {
            // Skip header if first row contains 'Golongan' or non-data text
            if ($index === 0 && (str_contains(strtolower($row[0] ?? ''), 'golongan') || str_contains(strtolower($row[1] ?? ''), 'masa'))) {
                continue;
            }

            $golongan = trim($row[0] ?? '');
            if (empty($golongan)) {
                continue;
            }

            // Clean masa_kerja
            $masa_kerja = preg_replace('/[^\d]/', '', (string)($row[1] ?? '0'));
            $masa_kerja = !empty($masa_kerja) ? (int)$masa_kerja : 0;

            // Clean gaji_pokok
            $gaji_pokok = preg_replace('/[^\d]/', '', (string)($row[2] ?? '0'));
            $gaji_pokok = !empty($gaji_pokok) ? (int)$gaji_pokok : 0;

            if ($gaji_pokok > 0) {
                Golongan::insup_golongan(0, $golongan, $masa_kerja, $gaji_pokok);
            }
        }
    }
}
