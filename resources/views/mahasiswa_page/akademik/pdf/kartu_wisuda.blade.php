<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Kartu Wisuda</title>
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
            margin: 30px;
        }
    </style>
</head>
<body>
<header class="clearfix">
    <table>
        <td>
            <div id="logo">
                <img src="{{asset('image/logo-mandala.png')}}">
            </div>
        </td>
        <td>
            <div id="company">
                <div>Institut Teknologi dan Sains Mandala</div>
                <div>
                    Jl. Sumatra No.118-120, Tegal Boto Lor, Sumbersari, Kec. Sumbersari<br/>
                    Kabupaten Jember, Jawa Timur ( 68121 )<br/>
                </div>
                <div>
                    Telp: (0331) 334324<br/>
                    Fax: (0331) 3304941<br/>
                    https://itsm.ac.id/
                </div>
                <div>
                    <a href="mailto:itsm@itsm.ac.id">itsm@itsm.ac.id</a>
                </div>
            </div>
        </td>
    </table>
    <h1>KARTU PESERTA WISUDA</h1>
</header>
<main>
    <table>
        <tbody>
        <tr>
            <th style="width: 25%">{{$mahasiswa->fakultas}}</th>
            <th style="width: 75%; text-align: right">{{$mahasiswa->nomor_pendaftaran_wisuda}}</th>
        </tr>
        <tr>
            <td style="width: 25%; vertical-align: top; text-align: center">
                <img src="http://siakad.stie-mandala.ac.id/_report/photo_m/{{$mahasiswa->nim}}.jpg"
                     style="height: 180px; width: 175px">
            </td>
            <td style="width: 75%">
                <table style="width: 100%">
                    <tbody>
                    <tr>
                        <td style="width: 30%">Nama / NIM</td>
                        <td style="width: 5%">:</td>
                        <td style="width: 65%">{{$mahasiswa->nama_mahasiswa}}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Jenis Kelamin</td>
                        <td style="width: 5%">:</td>
                        <td style="width: 65%">{{$mahasiswa->jenis_kelamin}}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Tempat / Tanggal Lahir</td>
                        <td style="width: 5%">:</td>
                        <td style="width: 65%">{{$mahasiswa->tempat_tanggal_lahir}}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Jurusan / Program Studi</td>
                        <td style="width: 5%">:</td>
                        <td style="width: 65%">{{$mahasiswa->nama_prodi}}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Status Menikah</td>
                        <td style="width: 5%">:</td>
                        <td style="width: 65%">{{$mahasiswa->status_mahasiswa}}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%">IPK</td>
                        <td style="width: 5%">:</td>
                        <td style="width: 65%">{{$mahasiswa->ipk}}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Alamat</td>
                        <td style="width: 5%">:</td>
                        <td style="width: 65%">{{$mahasiswa->alamat}}</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 25%">Judul Skripsi</td>
            <td style="width: 75%">{{$mahasiswa->judul_skripsi}}</td>
        </tr>
        </tbody>
    </table>
    <hr/>
    <div id="company" style="text-align: right">
        <div>Jember, {{$data['tgl']['ttd']}}</div>
        <div>
            Kepala Bagian Akademik
        </div>
        <div style="margin-top: 50px">
            YULIAS PRIMITA, S.T.,M.M
        </div>
    </div>
    <hr/>
</main>
<footer>
    Di unduh/generate berdasarkan data pendaftaran wisuda yang tersimpan pada situs sipadu.stie-mandala.ac.id per
    tanggal {{$data['tgl']['now']}}.
</footer>
</body>
</html>
