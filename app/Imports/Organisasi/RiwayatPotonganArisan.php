<?php

namespace App\Imports\Organisasi;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RiwayatPotonganArisan implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            \App\Models\Organisasi\RiwayatPotonganArisan::create([
                'id_karyawan' => $row[0],
                'potongan' => $row[2]
            ]);
        }
    }
}
