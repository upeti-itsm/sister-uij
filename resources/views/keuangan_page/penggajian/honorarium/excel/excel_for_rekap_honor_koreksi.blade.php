
<style>
    tr > td {
        border: 1px solid #000000;
    }
</style>
@if(count($data) > 0)
    <table>
        <thead>
        <tr>
            <th colspan="14" style="text-align: center; border: 1px solid #000000;">LIST HONORARIUM KOREKSI DOSEN</th>
        </tr>
        <tr>
            <th colspan="14" style="text-align: center; border: 1px solid #000000;">INSTITUT TEKNOLOGI DAN SAINS MANDALA</th>
        </tr>
        <tr>
            <th colspan="14" style="text-align: center; border: 1px solid #000000;">SEMESTER: {{$semester}}</th>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                NO
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                NAMA
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                Pembuatan Soal UTS Pg
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                Pembuatan Soal UAS Pg
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                HR Pembuatan Soal Pagi
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                Jml MK Mlm
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                Jml Peserta Pagi
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                Jml Peserta Mlm
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                HR Koreksi
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                HR TM UJIAN MLM
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                Jumlah Kotor
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                Infaq
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                Jml Potongan
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                Jumlah Diterima
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
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->pembuatan_soal_uts_pagi,1,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->pembuatan_soal_uas_pagi,1,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->hr_pembuatan_soal_pagi,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->jml_mk_malam,1,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->jml_peserta_pagi,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->jml_peserta_malam,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->hr_koreksi,0,',','.')}}</td>
                <td style="text-align: right; border: 1px solid #000000;">{{number_format($item->hr_tm_ujian_malam,0,',','.')}}</td>
                <td style="text-align: right; border: 1px solid #000000;">{{number_format($item->jumlah_kotor,0,',','.')}}</td>
                <td style="text-align: right; border: 1px solid #000000;">{{number_format($item->infaq,0,',','.')}}</td>
                <td style="text-align: right; border: 1px solid #000000;">{{number_format($item->infaq,0,',','.')}}</td>
                <td style="text-align: right; border: 1px solid #000000;">{{number_format($item->jumlah_diterima,0,',','.')}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; border: 1px solid #000000;">JUMLAH</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data[0]->total_pembuatan_soal_uts_pagi,1,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data[0]->total_pembuatan_soal_uas_pagi,1,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data[0]->total_hr_pembuatan_soal_pagi,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data[0]->total_jml_mk_malam,1,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data[0]->total_jml_peserta_pagi,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data[0]->total_jml_peserta_malam,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data[0]->total_hr_koreksi,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data[0]->total_hr_tm_ujian_malam,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data[0]->total_jumlah_kotor,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data[0]->total_infaq,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data[0]->total_infaq,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data[0]->total_jumlah_diterima,0,',','.')}}</td>
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
            <th colspan="14" style="text-align: center; border: 1px solid #000000;">LIST HONORARIUM KOREKSI DOSEN</th>
        </tr>
        <tr>
            <th colspan="14" style="text-align: center; border: 1px solid #000000;">INSTITUT TEKNOLOGI DAN SAINS MANDALA</th>
        </tr>
        <tr>
            <th colspan="14" style="text-align: center; border: 1px solid #000000;">SEMESTER: {{$semester}}</th>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                NO
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                NAMA
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                Pembuatan Soal UTS Pg
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                Pembuatan Soal UAS Pg
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                HR Pembuatan Soal Pagi
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                Jml MK Mlm
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                Jml Peserta Pagi
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                Jml Peserta Mlm
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                HR Koreksi
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                HR TM UJIAN MLM
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                Jumlah Kotor
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                Infaq
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                Jml Potongan
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center" >
                Jumlah Diterima
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="14" style="font-weight: bold; text-align: center"><b>TIDAK ADA DATA</b></td>
        </tr>
        </tbody>
    </table>
@endif
