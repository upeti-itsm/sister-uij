<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Daftar Pendaftar</title>
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

        h2 {
            margin: 0 0 5px 0;
            color: #5D6975;
            line-height: 1.4em;
            border-top: 1px solid #5D6975;
            border-bottom: 1px solid #5D6975;
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
            page-break-before: auto;
            page-break-after: auto;
        }

        table tr td {
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
            color: #000000;
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

        table, th, td {
            border: 1px solid black;
        }

        table.remove, th.remove, td.remove {
            border: 0px solid black;
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
    <table class="remove">
        <td class="remove">
            <div id="logo">
                <img src="{{asset('image/logo-mandala.png')}}">
            </div>
        </td>
        <td class="remove">
            <div id="company">
                <div>STIE Mandala Jember</div>
                <div>
                    Jl. Sumatra No.118-120, Tegal Boto Lor, Sumbersari, Kec. Sumbersari<br/>
                    Kabupaten Jember, Jawa Timur ( 68121 )<br/>
                </div>
                <div>
                    Telp: (0331) 334324<br/>
                    Fax: (0331) 3304941<br/>
                    www.stie-mandala.ac.id
                </div>
                <div>
                    <a href="mailto:stie-mj@stie-mandala.ac.id">stie-mj@stie-mandala.ac.id</a>
                </div>
            </div>
        </td>
    </table>
</header>
<main>
    <table>
        <tbody>
        @php($no = 1)
        @for($i=0; $i < sizeof($pendaftar); $i+=3)
            <tr>
                <td class="center" style="width: 40%; vertical-align: top; text-align: center">
                    <img src="data:image/png;base64,{{$pendaftar[$i]->qrcode}}" alt="barcode"
                         style="height: 150px; margin: 1px"/>
                    <h5 style="margin: 1px">{{$pendaftar[$i]->code_barcode}}</h5>
                    <h3 style="margin-top: 15px; margin-bottom: 1px">{{$pendaftar[$i]->nama_tamu}}</h3>
                    <h2 style="margin: 1px">{{$pendaftar[$i]->jabatan}}</h2>
                </td>
                <td class="center" style="width: 40%; vertical-align: top; text-align: center">
                    @if(isset($pendaftar[$i+1]))
                        <img src="data:image/png;base64,{{$pendaftar[$i+1]->qrcode}}" alt="barcode"
                             style="height: 150px; margin: 1px"/>
                        <h5 style="margin: 1px">{{$pendaftar[$i+1]->code_barcode}}</h5>
                        <h3 style="margin-top: 15px; margin-bottom: 1px">{{$pendaftar[$i+1]->nama_tamu}}</h3>
                        <h2 style="margin: 1px">{{$pendaftar[$i+1]->jabatan}}</h2>
                    @endif
                </td>
                <td class="center" style="width: 40%; vertical-align: top; text-align: center">
                    @if(isset($pendaftar[$i+2]))
                        <img src="data:image/png;base64,{{$pendaftar[$i+2]->qrcode}}" alt="barcode"
                             style="height: 150px; margin: 1px"/>
                        <h5 style="margin: 1px">{{$pendaftar[$i+2]->code_barcode}}</h5>
                        <h3 style="margin-top: 15px; margin-bottom: 1px">{{$pendaftar[$i+2]->nama_tamu}}</h3>
                        <h2 style="margin: 1px">{{$pendaftar[$i+2]->jabatan}}</h2>
                    @endif
                </td>
            </tr>
        @endfor
        </tbody>
    </table>
</main>
<footer>
</footer>
</body>
</html>
