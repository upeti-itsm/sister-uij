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
    <table style="margin-top: 100px">
        <tr>
            <td style="width: 100%; background-color: white">
                <div id="company" style="text-align: center; margin-bottom: 0px; padding-bottom: 0px">
                    <img src="{{asset('image/logo-mandala.png')}}" style="height: 80px; width: 80px">
                </div>
            </td>
        </tr>
        <tr>
            <td style="width: 80%; background-color: white">
                <div id="company" style="text-align: center; margin-bottom: 0px; padding-bottom: 0px">
                    <div style="font-size: large; font-weight: bold; text-align: center">INSTITUT TEKNOLOGI DAN SAINS
                        (ITS) MANDALA
                    </div>
                    <div style="font-size: large; font-weight: bold">( TERAKREDITASI )</div>
                </div>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; margin: 0; padding: 0;">
                <hr/>
                <small>Alamat : Jln. Sumatera No.118 – 120 (0331) 334324, Fax (0331) 330941 JEMBER 68121 e-mail:
                    stie-mj@stie-mandala.ac.id; www.stie-mandala.ac.id</small>
                <hr/>
            </td>
        </tr>
    </table>
    <div id="project">
        <div><span>Nama</span> {{$data['karyawan']->nama_lengkap}}</div>
        <div><span>Unit Kerja</span> {{$data['karyawan']->unit_kerja}}</div>
    </div>
    @if(isset($data['nomor']))
        <div id="company">
            <strong style="font-size: 35px;">{{$data['nomor']}}</strong>
        </div>
    @endif
</header>
<main>
    <table>
        <thead>
        <tr>
            <th colspan="4" style="text-align: center">HONORARIUM MENGAJAR DOSEN:
                <b>{{strtoupper($data['rekap']->periode_pembayaran)}}</b></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="width: 100%; font-weight: bold" colspan="4">HONORARIUM</td>
        </tr>
        <tr>
            <td class="center" style="width: 5%"><small>1</small></td>
            <td style="width: 50%" colspan="2">
                <small>HR REGULER PAGI<br/>
                    (TM: {{$data['rekap']->total_absen_pagi}} | Jml SKS: {{$data['rekap']->beban_sks_pagi}} | SKS
                    Wajib: {{$data['rekap']->sks_wajib}})</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{$data['rekap']->total_honor_kotor_mengajar_pagi}}</small>
            </td>
        </tr>
        <tr>
            <td class="center" style="width: 5%"><small>2</small></td>
            <td style="width: 50%" colspan="2">
                <small>HR REGULER MALAM<br/>
                    (TM: {{$data['rekap']->total_absen_malam}} | Jml SKS: {{$data['rekap']->beban_sks_malam}})</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{$data['rekap']->total_honor_kotor_mengajar_malam}}</small>
            </td>
        </tr>
        <tr>
            <td style="width: 100%; font-weight: bold" colspan="4">POTONGAN</td>
        </tr>
        <tr>
            <td class="center" style="width: 5%"><small>1</small></td>
            <td style="width: 50%" colspan="2">
                <small>POTONGAN INFAQ (2,5% Total Honorarium)</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{$data['rekap']->total_nominal_infaq}}</small>
            </td>
        </tr>
        <tr>
            <td class="center" style="width: 55%; font-weight: bold" colspan="3">TOTAL HONORARIUM DITERIMA</td>
            <td style="width: 45%; text-align: right">
                <b>{{$data['rekap']->total_honorarium_dosen_mengajar}}</b>
            </td>
        </tr>
        <tr>
            <td style="width: 100%; font-weight: bold" colspan="4">Keterangan:<br/>
                <b>HR ini adalah HR mengajar untuk kelas selain kelas dengan kode MF, EB dan BW</b>
            </td>
        </tr>
        </tbody>
    </table>
    <hr/>
    <table>
        <td>
            <div id="company" style="text-align: left; margin-top: 20px">
                <div>Mengetahui</div>
                <div>
                    Wakil Ketua II
                </div>
                <div style="margin-top: 75px">
                    Dr. Yuniorita Indah H., SE., MBA
                </div>
            </div>
        </td>
        <td>
            <div id="company" style="text-align: right; margin-top: 20px">
                <div>Jember, {{$data['tgl']['ttd']}}</div>
                <div>
                    Kabag Keuangan
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
