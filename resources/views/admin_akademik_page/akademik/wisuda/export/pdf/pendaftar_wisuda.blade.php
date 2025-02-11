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
</header>
<main>
    <table>
        <tbody>
        @php($no = 1)
        @foreach($pendaftar as $item)
            @if($no == 1)
                <tr>
                    @if($item->kd_prodi == "61201")
                        @if(strtoupper($item->id_konsentrasi) == "MI")
                            <td class="center" colspan="2"><b>MANAJEMEN INFORMATIKA</b></td>
                        @else
                            <td class="center" colspan="2"><b>MANAJEMEN BISNIS</b></td>
                        @endif
                    @else
                        <td class="center" colspan="2"><b>{{strtoupper($item->nama_prodi)}}</b></td>
                    @endif
                </tr>
                <tr>
                    <td colspan="2">
                        <div style="width: 100%;">
                            <div style="width: 100%; text-align: center">
                                <b>{{$item->nomor_urut}}</b><br/>
                                <img data-nomor="{{$no++}}" data-nama_prodi="{{$nama_prodi = $item->nama_prodi}}"
                                     src="http://siakad.stie-mandala.ac.id/_report/photo_m/{{$item->nim}}.jpg" height="150"><br/>
                                <b>{{$item->nomor_pendaftaran_wisuda}}</b>
                                <hr/>
                            </div>
                            <div style="width: 100%;">
                                <div style=" width: 100%">
                                    <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                        <b>Nama
                                            Lengkap</b></div>
                                    <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                    </div>
                                    <div
                                        style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->nama_mahasiswa}}</div>
                                </div>
                                <div style=" width: 100%">
                                    <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                        <b>NIM</b>
                                    </div>
                                    <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                    </div>
                                    <div
                                        style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->nim}}</div>
                                </div>
                                <div style=" width: 100%">
                                    <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                        <b>Tempat
                                            / Tanggal Lahir</b></div>
                                    <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                    </div>
                                    <div
                                        style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->tempat_tanggal_lahir}}</div>
                                </div>
                                <div style=" width: 100%">
                                    <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                        <b>Alamat</b>
                                    </div>
                                    <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                    </div>
                                    <div
                                        style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->alamat}}</div>
                                </div>
                                <div style=" width: 100%">
                                    <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                        <b>Nomor
                                            Hand Phone</b></div>
                                    <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                    </div>
                                    <div
                                        style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->handphone}}</div>
                                </div>
                                <div style=" width: 100%">
                                    <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                        <b>Email</b>
                                    </div>
                                    <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                    </div>
                                    <div
                                        style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->email}}</div>
                                </div>
                                <div style=" width: 100%">
                                    <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                        <b>Dosen
                                            Pembimbing Utama</b></div>
                                    <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                    </div>
                                    <div style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                        {{$item->dpu}}
                                    </div>
                                </div>
                                <div style=" width: 100%">
                                    <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                        <b>Dosen
                                            Pembimbing Asisten</b></div>
                                    <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                    </div>
                                    <div style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                        {{$item->dpa}}
                                    </div>
                                </div>
                                <div style=" width: 100%">
                                    <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                        <b>IP
                                            Kumulatif</b></div>
                                    <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                    </div>
                                    <div
                                        style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->ipk}}</div>
                                </div>
                                <div style=" width: 100%">
                                    <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                        <b>Judul
                                            Tugas Akhir</b></div>
                                    <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                    </div>
                                    <div
                                        style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{strtoupper($item->judul_skripsi)}}</div>
                                </div>
                                <div style=" width: 100%">
                                    <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                        <b>Kesan & Pesan selama kuliah di ITS Mandala</b></div>
                                    <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                    </div>
                                    <div
                                        style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->kesan_pesan}}</div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @else
                @if($nama_prodi == $item->nama_prodi)
                    <tr>
                        <td colspan="2">
                            <div style="width: 100%;">
                                <div style="width: 100%; text-align: center">
                                    <b>{{$item->nomor_urut}}</b><br/>
                                    <img data-nomor="{{$no++}}" data-nama_prodi="{{$nama_prodi = $item->nama_prodi}}"
                                         src="http://siakad.stie-mandala.ac.id/_report/photo_m/{{$item->nim}}.jpg" height="150"><br/>
                                    <b>{{$item->nomor_pendaftaran_wisuda}}</b>
                                    <hr/>
                                </div>
                                <div style="width: 100%;">
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>Nama
                                                Lengkap</b></div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div
                                            style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->nama_mahasiswa}}</div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>NIM</b>
                                        </div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div
                                            style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->nim}}</div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>Tempat
                                                / Tanggal Lahir</b></div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div
                                            style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->tempat_tanggal_lahir}}</div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>Alamat</b>
                                        </div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div
                                            style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->alamat}}</div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>Nomor
                                                Hand Phone</b></div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div
                                            style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->handphone}}</div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>Email</b>
                                        </div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div
                                            style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->email}}</div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>Dosen
                                                Pembimbing Utama</b></div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            {{$item->dpu}}
                                        </div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>Dosen
                                                Pembimbing Asisten</b></div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            {{$item->dpa}}
                                        </div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>IP
                                                Kumulatif</b></div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div
                                            style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->ipk}}</div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>Judul
                                                Tugas Akhir</b></div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div
                                            style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{strtoupper($item->judul_skripsi)}}</div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>Kesan & Pesan selama kuliah di ITS Mandala</b></div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div
                                            style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->kesan_pesan}}</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td class="center" colspan="2"><b>{{strtoupper($item->nama_prodi)}}</b></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div style="width: 100%;">
                                <div style="width: 100%; text-align: center">
                                    <b>{{$item->nomor_urut}}</b><br/>
                                    <img data-nomor="{{$no++}}" data-nama_prodi="{{$nama_prodi = $item->nama_prodi}}"
                                         src="http://siakad.stie-mandala.ac.id/_report/photo_m/{{$item->nim}}.jpg" height="150"><br/>
                                    <b>{{$item->nomor_pendaftaran_wisuda}}</b>
                                    <hr/>
                                </div>
                                <div style="width: 100%;">
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>Nama
                                                Lengkap</b></div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div
                                            style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->nama_mahasiswa}}</div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>NIM</b>
                                        </div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div
                                            style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->nim}}</div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>Tempat
                                                / Tanggal Lahir</b></div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div
                                            style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->tempat_tanggal_lahir}}</div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>Alamat</b>
                                        </div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div
                                            style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->alamat}}</div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>Nomor
                                                Hand Phone</b></div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div
                                            style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->handphone}}</div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>Email</b>
                                        </div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div
                                            style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->email}}</div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>Dosen
                                                Pembimbing Utama</b></div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            {{$item->dpu}}
                                        </div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>Dosen
                                                Pembimbing Asisten</b></div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            {{$item->dpa}}
                                        </div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>IP
                                                Kumulatif</b></div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div
                                            style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->ipk}}</div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>Judul
                                                Tugas Akhir</b></div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div
                                            style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{strtoupper($item->judul_skripsi)}}</div>
                                    </div>
                                    <div style=" width: 100%">
                                        <div style="width: 30%; display: inline-block; vertical-align: top; margin-bottom: 5px">
                                            <b>Kesan & Pesan selama kuliah di ITS Mandala</b></div>
                                        <div style="width: 3%; display: inline-block; vertical-align: top; margin-bottom: 5px">:
                                        </div>
                                        <div
                                            style="width: 65%; display: inline-block; vertical-align: top; margin-bottom: 5px">{{$item->kesan_pesan}}</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endif
            @endif
        @endforeach
        </tbody>
    </table>
</main>
<footer>
    Di unduh/generate berdasarkan data pendaftaran yang tersimpan pada situs sipadu.stie-mandala.ac.id per
    tanggal {{$data['tgl']['now']}}.
</footer>
</body>
</html>
