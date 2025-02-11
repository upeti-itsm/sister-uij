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
        <tr>
            <td style="text-align: center; margin: 0; padding: 0;" colspan="2">
                <hr/>
            </td>
        </tr>
    </table>
    <div id="project">
        <div><span>Nama</span> {{$data['karyawan']->nama_lengkap}}</div>
        <div><span>Unit Kerja</span> {{$data['karyawan']->unit_kerja}}</div>
        <div><span>Email </span> <a href="mailto:{{$data['karyawan']->email}}">{{$data['karyawan']->email}}</a></div>
        <div><span>Nomor HP </span> {{$data['karyawan']->no_hp}}</div>
        <div><span>Tgl Export</span> {{$data['tgl']['now']}}</div>
    </div>
</header>
<main>
    <table style="width: 100%">
        <thead style="width: 100%!important;">
        <tr>
            <th colspan="4" style="text-align: center">SLIP GAJI PERIODE:
                <b>{{strtoupper($data['rekap']->periode_pembayaran).' '.$data['rekap']->tahun}}</b></th>
        </tr>
        </thead>
        <tbody style="width: 100%!important;">
        <tr>
            <td class="center" style="width: 5%"><b>1</b></td>
            <td style="width: 50%" colspan="2">
                <b>Gaji Pokok</b>
            </td>
            <td style="width: 45%; text-align: right">
                <b>{{"Rp. " . number_format($data['rekap']->gaji_pokok,0,',','.').',-'}}</b>
            </td>
        </tr>
        <tr>
            <td class="center" style="width: 55%; font-weight: bold" colspan="3">TOTAL TUNJANGAN (Rincian Tunjangan
                Lihat Dibawah Ini)
            </td>
            <td style="width: 45%; text-align: right">
                <b>{{"Rp. " . number_format($data['rekap']->nominal_total_tunjangan,0,',','.').',-'}}</b>
            </td>
        </tr>
        <tr style="display: none">
            <td class="center" style="width: 5%"><small>1</small></td>
            <td style="width: 50%" colspan="2">
                <small>Tunjangan Fungsional</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->tunjangan_fungsional,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr>
            <td class="center" style="width: 5%"><small>2</small></td>
            <td style="width: 50%" colspan="2">
                <small>Tunjangan Jabatan</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->tunjangan_struktural,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr>
            <td class="center" style="width: 5%"><small>2</small></td>
            <td style="width: 50%" colspan="2">
                <small>Tunjangan Jamsos</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->tunjangan_struktural,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr>
            <td class="center" style="width: 5%"><small>2</small></td>
            <td style="width: 50%" colspan="2">
                <small>Insentif Masa Kerja</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->tunjangan_struktural,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr>
            <td class="center" style="width: 5%"><small>2</small></td>
            <td style="width: 50%" colspan="2">
                <small>Insentif Lembur</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->nominal_tunjangan_lembur,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr>
            <td class="center" style="width: 5%"><small>2</small></td>
            <td style="width: 50%" colspan="2">
                <small>Insentif Kelebihan Mengajar S1/D3</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->tunjangan_struktural,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr>
            <td class="center" style="width: 5%"><small>2</small></td>
            <td style="width: 50%" colspan="2">
                <small>Insentif Kelebihan Mengajar S2</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->tunjangan_struktural,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr style="display: none">
            <td class="center" style="width: 5%"><small>3</small></td>
            <td style="width: 50%" colspan="2">
                <small>Tunjangan Pendidikan</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->nominal_tunjangan_pendidikan,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr style="display: none">
            <td class="center" style="width: 5%"><small>5</small></td>
            <td style="width: 50%" colspan="2">
                <small>Tunjangan Keluarga
                    @if($data['rekap']->jml_anak > 0)
                        ( Jumlah Anak : {{$data['rekap']->jml_anak.' orang'}} )
                    @endif
                </small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->nominal_tunjangan_keluarga,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr style="display: none">
            <td class="center" style="width: 5%"><small>6</small></td>
            <td style="width: 50%" colspan="2">
                <small>Tunjangan Beras ( {{'Diberikan dalam bentuk '.$data['rekap']->total_beras.' beras'}} )</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->total_harga_beras,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr>
            <td class="center" style="width: 5%"><small>7</small></td>
            <td style="width: 50%" colspan="2">
                <small>Tunjangan Kinerja
                    ( {{$data['rekap']->total_kehadiran.' hari X Rp. '. number_format($data['rekap']->transport_harian,0,',','.')}}
                    )</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->tunjangan_transport,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr>
            <td class="center" style="width: 5%"><small>2</small></td>
            <td style="width: 50%" colspan="2">
                <small>Insentif Lainnya</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->tunjangan_struktural,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr>
            <td class="center" style="width: 55%; font-weight: bold" colspan="3">TOTAL POTONGAN (Rincian Potongan Lihat
                Dibawah Ini)
            </td>
            <td style="width: 45%; text-align: right">
                <b>{{"Rp. " . number_format($data['rekap']->nominal_total_potongan,0,',','.').',-'}}</b>
            </td>
        </tr>
        <tr style="display: none">
            <td class="center" style="width: 5%"><small>1</small></td>
            <td style="width: 50%" colspan="2">
                <small>Potongan Infaq ( 2,5% Gaji Kotor )</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->nominal_infaq,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr>
            <td class="center" style="width: 5%"><small>2</small></td>
            <td style="width: 50%" colspan="2">
                <small>Potongan Pinjaman</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->nominal_koperasi,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr style="display: none">
            <td class="center" style="width: 5%"><small>3</small></td>
            <td style="width: 50%" colspan="2">
                <small>Potongan Arisan</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->nominal_arisan,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr style="display: none">
            <td class="center" style="width: 5%"><small>4</small></td>
            <td style="width: 50%" colspan="2">
                <small>Potongan Qurban</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->nominal_qurban,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr>
            <td class="center" style="width: 5%"><small>5</small></td>
            <td style="width: 50%" colspan="2">
                <small>Potongan Paguyuban Cooper</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->nominal_dplk,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr style="display: none">
            <td class="center" style="width: 5%"><small>6</small></td>
            <td style="width: 50%" colspan="2">
                <small>Potongan Beras</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->total_harga_beras,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr>
            <td class="center" style="width: 5%" rowspan="2"><small>7</small></td>
            <td style="width: 50%" colspan="2">
                <small>BPJS Kesehatan</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->nominal_kesehatan,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr>
            <td style="width: 50%" colspan="2">
                <small>BPJS Ketenagakerjaan (Rincian Potongan Lihat Dibawah)</small>
                <hr/>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->nominal_ketenagakerjaan,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr style="display: none">
            <td style="width: 25%; background-color: white">
                <small>Potongan JHT</small>
            </td>
            <td style="width: 25%; text-align: left; background-color: white">
                <small>{{"Rp. " . number_format($data['rekap']->nominal_jht,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr style="display: none">
            <td style="width: 25%">
                <small>Potongan JKM</small>
            </td>
            <td style="width: 25%; text-align: left">
                <small>{{"Rp. " . number_format($data['rekap']->nominal_jkm,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr style="display: none">
            <td style="width: 25%; background-color: white">
                <small>Potongan JKK</small>
            </td>
            <td style="width: 25%; text-align: left; background-color: white">
                <small>{{"Rp. " . number_format($data['rekap']->nominal_jkk,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr style="display: none">
            <td style="width: 25%">
                <small>Potongan JP</small>
            </td>
            <td style="width: 25%; text-align: left">
                <small>{{"Rp. " . number_format($data['rekap']->nominal_jp,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr>
            <td style="width: 50%" colspan="3">
                <small><b>TOTAL POTONGAN BPJS</b></small>
            </td>
            <td style="width: 45%; text-align: right">
                <small><b>{{"Rp. " . number_format($data['rekap']->nominal_asuransi,0,',','.').',-'}}</b></small>
            </td>
        </tr>
        <tr>
            <td class="center" style="width: 5%"><small>8</small></td>
            <td style="width: 50%" colspan="2">
                <small>Potongan Lainnya</small>
            </td>
            <td style="width: 45%; text-align: right">
                <small>{{"Rp. " . number_format($data['rekap']->nominal_lainnya,0,',','.').',-'}}</small>
            </td>
        </tr>
        <tr>
            <td class="center" style="width: 55%; font-weight: bold" colspan="3">TOTAL GAJI DITERIMA</td>
            <td style="width: 45%; text-align: right">
                <b>{{$data['rekap']->total_nominal_gaji}}</b>
            </td>
        </tr>
        </tbody>
    </table>
    <hr/>
    <table style="background-color: white!important;">
        <tr style="background-color: white!important;">
            <td style="background-color: white!important;">
                <div id="company" style="text-align: left; margin-top: 50px">
                    <div>Mengetahui</div>
                    <div>
                        Kepala Bagian Keuangan
                    </div>
                    <div style="margin-top: 75px">
                        Nurul Lailatul Vitryah, S.E., M.Si
                    </div>
                </div>
            </td>
            <td style="background-color: white!important;">
                <div id="company" style="text-align: right; margin-top: 50px">
                    <div>Jember, {{$data['tgl']['ttd']}}</div>
                    <div>
                        Penerima
                    </div>
                    <div style="margin-top: 75px">
                        {{$data['karyawan']->nama_lengkap}}
                    </div>
                </div>
            </td>
        </tr>
    </table>
</main>
</body>
</html>
