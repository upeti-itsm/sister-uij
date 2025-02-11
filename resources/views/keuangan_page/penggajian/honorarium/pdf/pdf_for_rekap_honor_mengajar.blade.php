<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Slip Gaji</title>
    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #5D6975;
            text-decoration: underline;
        }

        body {
            position: relative;
            width: auto;
            height: 29.7cm;
            margin: 0 auto;
            color: #001028;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 12px;
            font-family: Arial;
        }

        header {
            padding: 10px 0;
            margin-bottom: 10px;
        }

        #logo {
            text-align: left;
            margin-bottom: 10px;
        }

        #logo img {
            width: 90px;
        }

        h1 {
            border-top: 1px solid #5D6975;
            border-bottom: 1px solid #5D6975;
            color: #5D6975;
            font-size: 2.4em;
            line-height: 1.4em;
            font-weight: normal;
            text-align: center;
            margin: 0 0 5px 0;
            background: url("{{asset('image/dimension.png')}}");
        }

        #project {
            float: left;
        }

        #project span {
            color: #5D6975;
            text-align: left;
            width: 52px;
            margin-right: 10px;
            display: inline-block;
            font-size: 0.8em;
        }

        #company {
            text-align: right;
        }

        #project div,
        #company div {
            white-space: nowrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        table th,
        table td {
            text-align: center;
        }

        table th {
            color: #0b0b0b;
            white-space: nowrap;
            font-weight: normal;
            text-align: center;
            border: 1px solid #C1CED9;
        }

        table .service,
        table .desc {
            text-align: left;
        }

        table td {
            padding: 5px;
            color: #5D6975;
            text-align: left;
        }

        table td.center {
            text-align: center;
        }

        table td.service,
        table td.desc {
            vertical-align: top;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
        }

        table td.grand {
            border-top: 1px solid #5D6975;;
        }

        #notices .notice {
            color: #5D6975;
            font-size: 1.2em;
        }

        footer {
            color: #5D6975;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #C1CED9;
            padding: 8px 0;
            text-align: center;
        }
    </style>
    <style>
        @page {
            margin: 50px;
        }
    </style>
</head>
<body>
<header class="clearfix">
    <table>
        <tr>
            <td style="text-align: center; margin: 0; padding: 0;" colspan="2">
                <hr/>
                <small>Alamat : Jln. Sumatera No.118 – 120 (0331) 334324, Fax (0331) 330941 JEMBER 68121 e-mail:
                    stie-mj@stie-mandala.ac.id; www.stie-mandala.ac.id</small>
                <hr/>
            </td>
        </tr>
    </table>
</header>
<main style="margin-top: 0">
    <table style="margin-top: 0;">
        <thead>
        <tr>
            <th colspan="17" style="text-align: center; border: 1px solid #000000;">LIST HONORARIUM MENGAJAR DOSEN</th>
        </tr>
        <tr>
            <th colspan="17" style="text-align: center; border: 1px solid #000000;">INSTITUT TEKNOLOGI DAN SAINS
                MANDALA
            </th>
        </tr>
        <tr>
            <th colspan="17" style="text-align: center; border: 1px solid #000000;">BULAN: {{$bulan.' '.$tahun}}</th>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center"
                rowspan="2">
                NO
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center"
                rowspan="2">
                NAMA
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center"
                colspan="2">
                JML MK
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center"
                colspan="2">
                TM
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center"
                colspan="3">
                Jml. SKS Reg
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center"
                colspan="2">
                HONOR TATAP MUKA
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center"
                rowspan="2">
                Jumlah Kotor
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center"
                rowspan="2">
                INFAQ
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center"
                rowspan="2">
                JML. DITERIMA
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center"
                rowspan="2" colspan="2">
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
        @foreach($data['rekap'] as $item)
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
                <td style="text-align: right; border: 1px solid #000000;">Rp. {{number_format($item->nominal_honor_kotor_mengajar_pagi,0,',','.')}}</td>
                <td style="text-align: right; border: 1px solid #000000;">Rp. {{number_format($item->nominal_honor_kotor_mengajar_malam,0,',','.')}}</td>
                <td style="text-align: right; border: 1px solid #000000;">Rp. {{number_format($item->jumlah_kotor,0,',','.')}}</td>
                <td style="text-align: right; border: 1px solid #000000;">Rp. {{number_format($item->jumlah_infaq,0,',','.')}}</td>
                <td style="text-align: right; border: 1px solid #000000;">Rp. {{number_format($item->jumlah_diterima,0,',','.')}}</td>
                <td style="text-align: right; border: 1px solid #000000;">@if($no%2 != 0){{$no}}@endif</td>
                <td style="text-align: right; border: 1px solid #000000;">@if($no%2 == 0){{$no}}@endif</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="9" style="text-align: center; font-weight: bold; border: 1px solid #000000;">JUMLAH</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">Rp. {{number_format($data['rekap'][0]->total_honor_kotor_mengajar_pagi,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">Rp. {{number_format($data['rekap'][0]->total_honor_kotor_mengajar_malam,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">Rp. {{number_format($data['rekap'][0]->total_kotor,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">Rp. {{number_format($data['rekap'][0]->total_infaq,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">Rp. {{number_format($data['rekap'][0]->total_diterima,0,',','.')}}</td>
        </tr>
        </tbody>
    </table>
    <hr/>
    <table>
        <td>
            <div id="company" style="text-align: left; margin-top: 50px">
                <div>Mengetahui</div>
                <div>
                    WAREK II
                </div>
                <div style="margin-top: 75px">
                    Dr. Yuniorita Indah H., SE., MBA
                </div>
            </div>
        </td>
        <td>
            <div id="company" style="text-align: right; margin-top: 50px">
                <div>Jember, {{$data['tgl']['ttd']}}</div>
                <div>
                    Ka. Keuangan
                </div>
                <div style="margin-top: 75px">
                    Mika Nurjanah, S.E., M.Ak
                </div>
            </div>
        </td>
    </table>
</main>
</body>
</html>
