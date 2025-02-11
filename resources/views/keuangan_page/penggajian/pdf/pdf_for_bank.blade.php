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
            padding: 5px 20px;
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
            <td style="width: 20%; background-color: white">
                <div id="logo" style="text-align: center">
                    <img src="{{asset('image/logo-uij.png')}}">
                </div>
            </td>
            <td style="width: 80%; background-color: white">
                <div id="company" style="text-align: center; margin-bottom: 0px; padding-bottom: 0px">
                    <div style="font-size: large; font-weight: bold; text-align: left">UNIVERSITAS ISLAM JEMBER
                    </div>
                </div>
            </td>
        </tr>
    </table>
</header>
<main>
    <table style="margin-top: 0">
        <thead>
        <tr>
            <th colspan="4" style="text-align: center">
                <b>LIST GAJI DOSEN DAN KARYAWAN TETAP</b><br/>
                <b>BULAN: {{$data['rekap'][0]->bulan.' '.$data['rekap'][0]->tahun}}</b>
            </th>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold;">
                NO
            </th>
            <th style="text-align: center; font-weight: bold;">
                NAMA
            </th>
            <th style="text-align: center; font-weight: bold;">
                NOMOR REKENING BNI
            </th>
            <th style="text-align: center; font-weight: bold;">
                GAJI {{$data['rekap'][0]->bulan.' '.$data['rekap'][0]->tahun}}
            </th>
        </tr>
        </thead>
        <tbody>
        <?php $no = 0; ?>
        @foreach($data['rekap'] as $item)
            <?php $no++; ?>
            <tr>
                <td style="text-align: center;">{{$no}}</td>
                <td style="text-align: left;">{{strtoupper($item->nama)}}</td>
                <td style="text-align: center;">{{$item->nomor_rekening}}</td>
                <td style="text-align: center;">{{$item->total_nominal_gaji}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" style="text-align: center; font-weight: bold;">Total Gaji</td>
            <td style="text-align: center; font-weight: bold;">{{$data['rekap'][0]->total_gaji_karyawan}}</td>
        </tr>
        </tbody>
    </table>
    <hr/>
    <div id="company" style="text-align: right; margin-top: 50px">
        <div>Jember, {{$data['tgl']['ttd']}}</div>
        <div>
            Kabag Keuangan
        </div>
        <div style="margin-top: 75px">
            Nurul Lailatul Vitryah, S.E., M.Si
        </div>
    </div>
</main>
</body>
</html>
