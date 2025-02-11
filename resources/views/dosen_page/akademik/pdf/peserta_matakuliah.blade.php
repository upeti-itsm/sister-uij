<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Peserta Matakuliah</title>
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
            background: url("/var/www/html/sipadu/public/image/dimension.png");
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
                    www.itsm.ac.id
                </div>
                <div>
                    <a href="mailto:itsmandala@itsm.ac.id">itsmandala@itsm.ac.id</a>
                </div>
            </div>
        </td>
    </table>
    <div id="project">
        <div><span>Dosen</span> {{$dosen->nama_dosen}}</div>
        <div><span>Matakul </span> {{$matakuliah->nama_mata_kuliah.' ('.$matakuliah->kelas_id.')'}}</div>
        <div><span>Tgl Export</span> {{$data['tgl']['now']}}</div>
    </div>
</header>
<main>
    <table>
        <thead>
        <tr>
            <th style="width: 5%">No.</th>
            <th style="width: 45%">NIM</th>
            <th style="width: 25%">Nama</th>
            <th style="width: 25%">Status</th>
        </tr>
        </thead>
        <tbody>
        @php($no = 1)
        @foreach($mahasiswa as $item)
            <tr>
                <td class="center"
                    style="width: 5%; @if($item->status_suspend) background-color: #e5c7ca; @endif">{{$no++}}</td>
                <td class="center" style="width: 25%; @if($item->status_suspend) background-color: #e5c7ca; @endif">
                    <b>{{$item->username}}</b>
                </td>
                <td style="width: 45%; @if($item->status_suspend) background-color: #e5c7ca; @endif">
                    <small>{{$item->nama_user}}</small>
                </td>
                <td style="width: 25%; @if($item->status_suspend) background-color: #e5c7ca; @endif">
                    <b>{{$item->status_suspend ? 'Suspended' : 'Active'}}</b>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <hr/>
</main>
<footer>
    Di unduh/generate berdasarkan data absen yang tersimpan pada situs staff.stie-mandala.ac.id per
    tanggal {{$data['tgl']['now']}}.
</footer>
</body>
</html>
