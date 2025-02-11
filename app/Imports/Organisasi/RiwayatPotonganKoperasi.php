<?php

namespace App\Imports\Organisasi;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RiwayatPotonganKoperasi implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            \App\Models\Organisasi\RiwayatPotonganKoperasi::create([
                'id_karyawan' => $row[0],
                'potongan' => $row[2],
                'periode' => $row[3],
                'tahun' => $row[4]
            ]);
        }
    }
}
