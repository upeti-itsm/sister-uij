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
            <th colspan="14" style="text-align: center; border: 1px solid #000000;">LIST HONORARIUM KOREKSI DOSEN</th>
        </tr>
        <tr>
            <th colspan="14" style="text-align: center; border: 1px solid #000000;">INSTITUT TEKNOLOGI DAN SAINS
                MANDALA
            </th>
        </tr>
        <tr>
            <th colspan="14" style="text-align: center; border: 1px solid #000000;">SEMESTER: {{$tahun_akademik}}</th>
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
        @foreach($data['rekap'] as $item)
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
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_pembuatan_soal_uts_pagi,1,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_pembuatan_soal_uas_pagi,1,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_hr_pembuatan_soal_pagi,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_jml_mk_malam,1,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_jml_peserta_pagi,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_jml_peserta_malam,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_hr_koreksi,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_hr_tm_ujian_malam,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_jumlah_kotor,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_infaq,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_infaq,0,',','.')}}</td>
            <td style="text-align: right; font-weight: bold; border: 1px solid #000000;">{{number_format($data['rekap'][0]->total_jumlah_diterima,0,',','.')}}</td>
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
