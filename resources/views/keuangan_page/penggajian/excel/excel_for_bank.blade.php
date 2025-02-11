<style>
    tr > td {
        border: 1px solid #000000;
    }
</style>
@if(count($data) > 0)
    <table>
        <thead>
        <tr>
            <th colspan="4" style="text-align: center; border: 1px solid #000000;">LIST GAJI DOSEN DAN KARYAWAN TETAP</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center; border: 1px solid #000000;">SEKOLAH TINGGI ILMU EKONOMI MANDALA</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center; border: 1px solid #000000;">BULAN: {{$data[0]->bulan.' '.$data[0]->tahun}}</th>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                NO
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                NAMA
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                NOMOR REKENING BNI
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                GAJI (Rp) {{$data[0]->bulan.' '.$data[0]->tahun}}
            </th>
        </tr>
        </thead>
        <tbody>
        <?php $no = 0; ?>
        @foreach($data as $item)
            <?php $no++; ?>
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{$no}}</td>
                <td style="text-align: left; border: 1px solid #000000;">{{strtoupper($item->nama)}}</td>
                <td style="text-align: left; border: 1px solid #000000;">{{$item->nomor_rekening}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->total_nominal_gaji_}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" style="text-align: center; font-weight: bold; border: 1px solid #000000;">Total Gaji</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_gaji_karyawan}}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: right">
                Jember, {{$tgl_ttd}}
            </td>
        </tr>
        <tr>
            <td colspan="4"  style="text-align: right">Kabag Keuangan</td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: right">
                Mika Nurjanah, S.E., M.Ak
            </td>
        </tr>
        </tbody>
    </table>
@else
    <table>
        <thead>
        <tr>
            <th colspan="4" style="text-align: center; border: 1px solid #000000;">LIST GAJI DOSEN DAN KARYAWAN TETAP</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center; border: 1px solid #000000;">SEKOLAH TINGGI ILMU EKONOMI MANDALA</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="text-align: center; font-weight: bold">NO</th>
            <th style="text-align: center; font-weight: bold">NAMA</th>
            <th style="text-align: center; font-weight: bold">NOMOR REKENING</th>
            <th style="text-align: center; font-weight: bold">GAJI</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="4" style="font-weight: bold; text-align: center"><b>TIDAK ADA DATA</b></td>
        </tr>
        </tbody>
    </table>
@endif
