
<style>
    tr > td {
        border: 1px solid #000000;
    }
</style>
@if(count($data) > 0)
    <table>
        <thead>
        <tr>
            <th colspan="16" style="text-align: center; border: 1px solid #000000;">LIST HONORARIUM MENGAJAR DOSEN</th>
        </tr>
        <tr>
            <th colspan="16" style="text-align: center; border: 1px solid #000000;">INSTITUT TEKNOLOGI DAN SAINS MANDALA</th>
        </tr>
        <tr>
            <th colspan="16" style="text-align: center; border: 1px solid #000000;">BULAN: {{$bulan.' '.$tahun}}</th>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" rowspan="2">
                NO
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" rowspan="2">
                NAMA
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" colspan="2">
                JML MK
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" colspan="2">
                TM
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" colspan="3">
                Jml. SKS Reg
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" colspan="2">
                HONOR TATAP MUKA
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" rowspan="2">
                Jumlah Kotor
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" rowspan="2">
                INFAQ
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" rowspan="2">
                JML. DITERIMA
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" rowspan="2" colspan="2">
                TTD
            </th>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">Reg Pagi</th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">Reg Mlm</th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">TM Pagi</th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">TM Mlm</th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">Pagi</th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">Mlm</th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">Jml</th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">REG PAGI</th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">REG MALAM</th>
        </tr>
        </thead>
        <tbody>
        <?php $no = 0; ?>
        @foreach($data as $item)
            <?php $no++; ?>
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{$no}}</td>
                <td style="text-align: left; border: 1px solid #000000;">{{strtoupper($item->nama)}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->jumlah_matkul_pagi,1,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->jumlah_matkul_malam,1,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->total_absen_pagi,1,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->total_absen_malam,1,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->beban_sks_pagi,1,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->beban_sks_malam,1,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->beban_sks_total,1,',','.')}}</td>
                <td style="text-align: right; border: 1px solid #000000;">{{number_format($item->nominal_honor_kotor_mengajar_pagi,0,',','.')}}</td>
                <td style="text-align: right; border: 1px solid #000000;">{{number_format($item->nominal_honor_kotor_mengajar_malam,0,',','.')}}</td>
                <td style="text-align: right; border: 1px solid #000000;">{{number_format($item->jumlah_kotor,0,',','.')}}</td>
                <td style="text-align: right; border: 1px solid #000000;">{{number_format($item->jumlah_infaq,0,',','.')}}</td>
                <td style="text-align: right; border: 1px solid #000000;">{{number_format($item->jumlah_diterima,0,',','.')}}</td>
                <td style="text-align: left; border: 1px solid #000000;">@if($no%2 != 0){{$no}}.@endif</td>
                <td style="text-align: left; border: 1px solid #000000;">@if($no%2 == 0){{$no}}.@endif</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="9" style="text-align: center; font-weight: bold; border: 1px solid #000000;">JUMLAH</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data[0]->total_honor_kotor_mengajar_pagi,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data[0]->total_honor_kotor_mengajar_malam,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data[0]->total_kotor,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data[0]->total_infaq,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data[0]->total_diterima,0,',','.')}}</td>
        </tr>
        <tr>
            <td colspan="14"></td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: left">
                Mengetahui
            </td>
            <td colspan="7" style="text-align: right">
                Jember, {{$tgl_ttd}}
            </td>
        </tr>
        <tr>
            <td colspan="7"  style="text-align: left">WAREK II</td>
            <td colspan="7"  style="text-align: right">Ka. Keuangan</td>
        </tr>
        <tr>
            <td colspan="7"></td>
            <td colspan="7"></td>
        </tr>
        <tr>
            <td colspan="7"></td>
            <td colspan="7"></td>
        </tr>
        <tr>
            <td colspan="7"></td>
            <td colspan="7"></td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: left">
                Dr. Yuniorita Indah H., S.E., M.B.A.
            </td>
            <td colspan="7" style="text-align: right">
                Mika Nurjanah, S.E., M.Ak
            </td>
        </tr>
        </tbody>
    </table>
@else
    <table>
        <thead>
        <tr>
            <th colspan="14" style="text-align: center; border: 1px solid #000000;">LIST HONORARIUM MENGAJAR DOSEN</th>
        </tr>
        <tr>
            <th colspan="14" style="text-align: center; border: 1px solid #000000;">INSTITUT TEKNOLOGI DAN SAINS MANDALA</th>
        </tr>
        <tr>
            <th colspan="14" style="text-align: center; border: 1px solid #000000;">BULAN: {{$bulan.' '.$tahun}}</th>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" rowspan="2">
                NO
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" rowspan="2">
                NAMA
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" colspan="2">
                JML MK
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" colspan="2">
                TM
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" colspan="3">
                Jml. SKS Reg
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" colspan="2">
                HONOR TATAP MUKA
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" rowspan="2">
                Jumlah Kotor
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" rowspan="2">
                INFAQ
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" rowspan="2">
                JML. DITERIMA
            </th>
        </tr>
        <tr>
            <th>Reg Pagi</th>
            <th>Reg Mlm</th>
            <th>TM Pagi</th>
            <th>TM Mlm</th>
            <th>Pagi</th>
            <th>Mlm</th>
            <th>Jml</th>
            <th>REG PAGI</th>
            <th>REG MALAM</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="14" style="font-weight: bold; text-align: center"><b>TIDAK ADA DATA</b></td>
        </tr>
        </tbody>
    </table>
@endif
