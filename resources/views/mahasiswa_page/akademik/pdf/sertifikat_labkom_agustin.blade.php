<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style>
        @page {
            margin: 0px;
        }

        body {
            background-image: url("/var/www/html/sipadu/public/image/sertifikat_labkom/sertifikat-agustin.png");
            background-size: cover;
            background-repeat: no-repeat;
            margin: 0px;
        }

        table.blueTable {
            border: 1px solid #1C6EA4;
            background-color: #EEEEEE;
            text-align: center;
            border-collapse: collapse;
            width: 400px;
            margin-top:100px;
            margin-left:auto;
            margin-right:auto;
        }

        table.blueTable td, table.blueTable th {
            border: 1px solid #AAAAAA;
            padding: 3px 2px;
        }

        table.blueTable tbody td {
            font-size: 18px;
        }

        table.blueTable thead {
            background: #1C6EA4;
            background: -moz-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
            background: -webkit-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
            background: linear-gradient(to bottom, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
            border-bottom: 2px solid #444444;
        }

        table.blueTable thead th {
            font-size: 18px;
            font-weight: bold;
            color: #FFFFFF;
            text-align: center;
            border-left: 2px solid #D0E4F5;
        }

        table.blueTable thead th:first-child {
            border-left: none;
        }
    </style>
</head>
<body>
<img style="width:113.3872552725px; height:151.18300703px; padding-left: 200px; position: absolute; padding-top: 120px;"
     src="https://siakad.itsm.ac.id/_report/photo_m/{{$sertifikat->nim_penerima}}.jpg">
<img style="width:113.3872552725px; height:135px; padding-left: 780px; padding-top: 130px; position: absolute;"
     src="data:image/png;base64, {!! $qrcode !!}">
<p style="float: right; font-size: 12px; margin-right: 10px"><i>Diunduh pada tanggal {{$sertifikat->last_generate}}</i></p>
<p style="padding-top: 140px; font-size: 19px; text-align: center; padding-bottom: 0px; font-weight: bold;">{{$sertifikat->nomor_sertifikat}}</p>
<p style="padding-top: 10px; text-align: center; line-height: 1; font-size: 18px;"><i>Diberikan kepada :</i></p>
<p style="text-align: center; font-size: 23px; line-height: 0.7; font-weight: bold; ">{{$sertifikat->nama_mahasiswa}} </p>
<p style="padding-top: 10px; text-align: center; line-height: 0.7; font-size: 18px;"><i>Tempat, Tanggal Lahir : </i></p>
<p style="text-align: center; font-size: 23px; line-height: 0.7; font-weight: bold;">{{$sertifikat->tempat_lahir}}
    , {{date('d F Y', strtotime($sertifikat->tanggal_lahir))}}</p>
<table class="blueTable">
    <thead style="background-color: #0a6aa1">
    <tr>
        <th>Program Level Basic</th>
        <th>Nilai Akhir</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Microsoft Word</td>
        <td>{{$sertifikat->nilai_angka}}</td>
    </tr>
    <tr>
        <td>Microsoft Excel</td>
        <td>{{$sertifikat->nilai_angka}}</td>
    </tr>
    <tr>
        <td>Microsoft Power Point</td>
        <td>{{$sertifikat->nilai_angka}}</td>
    </tr>
    </tbody>
</table>
</body>
</html>
