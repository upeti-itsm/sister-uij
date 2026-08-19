<?php

namespace App\Imports\Organisasi;

use App\Models\Organisasi\Golongan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class GolonganImport implements ToCollection
{
    public $totalProcessed = 0;
    public $totalSuccess = 0;
    public $totalFailed = 0;
    public $details = [];

    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row)
        {
            // Skip header if first row contains 'Golongan' or non-data text
            if ($index === 0 && (str_contains(strtolower((string)($row[0] ?? '')), 'golongan') || str_contains(strtolower((string)($row[1] ?? '')), 'masa'))) {
                continue;
            }

            $golongan = trim((string)($row[0] ?? ''));
            if (empty($golongan)) {
                continue;
            }

            // Clean masa_kerja
            $raw_masa = (string)($row[1] ?? '0');
            $masa_kerja = preg_replace('/[^\d]/', '', $raw_masa);
            $masa_kerja = ($masa_kerja !== '') ? (int)$masa_kerja : 0;

            // Clean gaji_pokok
            $raw_gaji = (string)($row[2] ?? '0');
            $gaji_pokok = preg_replace('/[^\d]/', '', $raw_gaji);
            $gaji_pokok = ($gaji_pokok !== '') ? (int)$gaji_pokok : 0;

            $this->totalProcessed++;
            $excelRowNumber = $index + 1;

            try {
                $res = Golongan::import_golongan($golongan, $masa_kerja, $gaji_pokok);
                $status = $res->status ?? 0;
                $keterangan = $res->keterangan ?? ($status == 1 ? 'Import Berhasil' : 'Import Gagal');

                if ($status == 1) {
                    $this->totalSuccess++;
                } else {
                    $this->totalFailed++;
                }

                $this->details[] = [
                    'baris' => $excelRowNumber,
                    'golongan' => $golongan,
                    'masa_kerja' => $masa_kerja,
                    'gaji_pokok' => $gaji_pokok,
                    'status' => $status,
                    'keterangan' => $keterangan
                ];
            } catch (\Exception $e) {
                $this->totalFailed++;
                $this->details[] = [
                    'baris' => $excelRowNumber,
                    'golongan' => $golongan,
                    'masa_kerja' => $masa_kerja,
                    'gaji_pokok' => $gaji_pokok,
                    'status' => 0,
                    'keterangan' => 'Error: ' . $e->getMessage()
                ];
            }
        }
    }
}
