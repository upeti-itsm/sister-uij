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
                UNIVERSITAS ISLAM JEMBER
            </td>
        </tr>
    </table>
</header>
<main style="margin-top: 0">
    <table style="margin-top: 0;">
        <thead>
        <tr>
            <th colspan="22" style="text-align: center">
                <b>LIST GAJI DOSEN DAN KARYAWAN TETAP</b><br/>
                <b>BULAN: {{$bulan.' '.$tahun}}</b>
            </th>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                No
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                Nama
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                Gol
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                Gj. Pokok
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                T. Kel
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                T. Fung
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                T. Struk
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                T. Pend
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                T. Beras
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                Transport
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                Knj. Dosen
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                Gj. Kotor
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                P. Beras
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                P. Koperasi
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                BPJS
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                DPLK
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                Arisan
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                Qurban
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                P. Lain
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                Infaq
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                Jml Pot
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                Jml Trma
            </th>
        </tr>
        </thead>
        <tbody>
        <?php $no = 0; ?>
        <?php $no = 0; ?>
        @foreach($data['rekap'] as $item)
            <?php $no++; ?>
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{$no}}</td>
                <td style="text-align: left; border: 1px solid #000000;">{{strtoupper($item->nama)}}</td>
                <td style="text-align: left; border: 1px solid #000000;">{{$item->golongan}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->nominal_gaji_pokok,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->nominal_tunjangan_keluarga,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->nominal_tunjangan_fungsional,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->nominal_tunjangan_struktural,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->nominal_tunjangan_pendidikan,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->nominal_tunjangan_beras,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->nominal_transport,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->nominal_kinerja_dosen,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->total_kotor_karyawan,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->nominal_tunjangan_beras,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->nominal_potongan_koperasi,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->nominal_potongan_bpjs,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->nominal_dplk,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->nominal_potongan_arisan,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->nominal_potongan_qurban,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->nominal_potongan_lainnya,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->nominal_infaq,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->nominal_potongan_karyawan,0,',','.')}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{number_format($item->nominal_gaji_karyawan,0,',','.')}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" style="text-align: center; font-weight: bold; border: 1px solid #000000;">JUMLAH</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_gaji_pokok,0,',','.')}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_tunjangan_keluarga,0,',','.')}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_tunjangan_fungsional,0,',','.')}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_tunjangan_struktural,0,',','.')}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_tunjangan_pendidikan,0,',','.')}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_tunjangan_beras,0,',','.')}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_transport,0,',','.')}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_kinerja_dosen,0,',','.')}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_gaji_kotor,0,',','.')}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_potongan_beras,0,',','.')}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_potongan_koperasi,0,',','.') }}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_potongan_bpjs,0,',','.')}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_potongan_dplk,0,',','.')}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_potongan_arisan,0,',','.')}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_potongan_qurban,0,',','.')}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_potongan_lainnya,0,',','.')}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_infaq_karyawan,0,',','.')}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_potongan_karyawan,0,',','.')}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_nominal_gaji_diterima,0,',','.')}}</td>
        </tr>
        </tbody>
    </table>
    <hr/>
    <table>
        <td>
            <div id="company" style="text-align: left; margin-top: 50px">
                <div>Mengetahui</div>
                <div>
                    Ka. Biro
                </div>
                <div style="margin-top: 75px">
                    ACH ILYASI S.Pd., M.AP
                </div>
            </div>
        </td>
        <td>
            <div id="company" style="text-align: right; margin-top: 50px">
                <div>Jember, {{$data['tgl']['ttd']}}</div>
                <div>
                    Kabag Keuangan
                </div>
                <div style="margin-top: 75px">
                    Nurul Lailatul Vitryah, S.E., M.Si
                </div>
            </div>
        </td>
    </table>
</main>
</body>
</html>
