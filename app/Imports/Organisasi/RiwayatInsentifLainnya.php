<?php

namespace App\Imports\Organisasi;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RiwayatInsentifLainnya implements ToCollection
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            \App\Models\Organisasi\RiwayatPotonganLainnya::create([
                'id_karyawan' => $row[0],
                'insentif' => $row[2],
                'periode' => $row[3],
                'tahun' => $row[4],
                'keterangan' => $row[5]
            ]);
        }
    }
}
