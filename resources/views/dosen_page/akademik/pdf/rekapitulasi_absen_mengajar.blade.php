<! DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Hadir Perkuliahan Mahasiswa</title>
    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
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
        }

        header {
            padding: 10px 0;
            margin-bottom: 10px;
        }

        #logo {
            text-align: center;
        }

        #logo img {
            width: 90px;
        }

        h1 {
            border-top: 1px solid #5D6975;
            border-bottom: 1px solid #5D6975;
            color: #5D6975;
            font-size: 1.5em;
            line-height: 1.4em;
            font-weight: bold;
            text-align: center;
            margin:  10px 0;
        }

        .info-section {
            margin-bottom: 20px;
            clear: both;
            font-size: 12px;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 3px;
        }

        .info-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-left: 30px;
        }

        .info-label {
            display:  inline-block;
            width: 150px;
            color: #5D6975;
        }

        .info-label-right {
            display: inline-block;
            width: 100px;
            color: #5D6975;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
            font-size:  11px;
        }

        table tr:nth-child(2n) td {
                                    background:  #F5F5F5;
                                }

        table th,
        table td {
            text-align: left;
            border:  1px solid #000;
        }

        table th {
            padding: 8px;
            color: #0b0b0b;
            font-weight: bold;
            background-color: #f0f0f0;
        }

        table td {
            padding: 8px;
            color: #5D6975;
        }

        table td.text-left {
            text-align: left;
        }

        table td.center {
            text-align: center;
        }

        .number-col {
            width: 40px;
        }

        .date-col {
            width: 80px;
        }

        .presence-cols {
            width: 60px;
        }

        .signature-col {
            width: 100px;
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
            font-size: 11px;
        }

        @page {
            margin: 30px;
            size: A4 landscape;
        }
    </style>
</head>
<body>
<header class="clearfix">
    <table style="border:  none;">
        <tr>
            <td style="width: 10%; background-color: white; border: none;">
                <div id="logo">
                    <img src="{{secure_asset('image/logo-uij.png')}}" alt="Logo UIJ">
                </div>
            </td>
            <td style="width: 90%; background-color: white; border:  none;">
                <div style="text-align: left;">
                    <div style="font-size: 16px; font-weight: bold;">YAYASAN PENDIDIKAN NAHDLATUL ULAMA' JEMBER</div>
                    <div style="font-size: 14px; font-weight: bold;">UNIVERSITAS ISLAM JEMBER</div>
                    <div style="font-size: 18px; font-weight: bold;">DAFTAR HADIR PERKULIAHAN MAHASISWA</div>
                </div>
            </td>
        </tr>
        <tr>
            <td style="background-color: white!important; text-align: center; margin:  0; padding: 5px 0; border: none;" colspan="2">
                <hr style="border: 1px solid #5D6975;"/>
            </td>
        </tr>
    </table>
    <div class="info-section">
        <div class="info-row">
            <div class="info-left">
                <span class="info-label">FAK/PROG. STUDI</span> :  {{$rekap[0]->fakultas ? $rekap[0]->fakultas.'/'.$dosen->nama_program_studi :  'FEB/ ADMINISTRASI PUBLIK'}}
            </div>
            <div class="info-right">
                <span class="info-label-right">AKADEMIK</span> : {{$rekap[0]->tahun_akademik ?? '2022 / GENAP'}}
            </div>
        </div>
        <div class="info-row">
            <div class="info-left">
                <span class="info-label">MATAKULIAH/KELAS</span> :  {{$rekap[0]->fullname ?? 'SNG215 / PENGANTAR ILMU PEMERINTAHAN / Kelas B'}}
            </div>
            <div class="info-right">
                <span class="info-label-right">DOSEN</span> : {{$dosen->nama_dosen ?? 'ACH SYASI M. AP'}}
            </div>
        </div>
    </div>
</header>
<main>
    <table>
        <thead>
        <tr>
            <th style="width: 5%">No.</th>
            <th style="width: 30%">Absensi</th>
            <th style="width: 25%">Pelaksaan Kuliah</th>
            <th style="width: 30%">Keterangan</th>
            <th style="width: 10%">Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rekap as $item)
            <tr>
                <td class="center" style="width: 5%">{{$item->nomor}}</td>
                <td style="width: 30%">
                    <b>{{$item->fullname}}</b><br/>
                    <small>{{$item->tanggal_absen}}</small>
                    @if(isset($item->keterangan))
                        <br/><small>Keterangan : <br/>{{$item->keterangan}}</small>
                    @endif
                </td>
                <td style="width: 25%">
                    <small>Tanggal : {{$item->tgl_pelaksanaan_}}</small><br/>
                    <small>{{$item->waktu_mengajar}}</small>
                </td>
                <td style="width: 30%">
                    <small>Pertemuan Ke-{{$item->pertemuan_ke}}</small><br/>
                    <small>Materi : <br/>{{$item->materi}}</small>
                </td>
                <td class="center" style="width: 10%">
                    <b>{{$item->status_absen}}</b>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @if(sizeof($rekap) > 0)
        <hr/>
        <table>
            <tr>
                <td style="text-align: left">
                    Jumlah Absensi Tepat Waktu : <b>{{$rekap[0]->jml_tepat_waktu}}</b>
                </td>
                <td style="text-align: right">
                    Jumlah Absensi Terlambat : <b>{{$rekap[0]->jml_terlambat}}</b>
                </td>
            </tr>
        </table>
    @endif
    <div id="company" style="text-align: right">
        <div>Jember, {{$data['tgl']['ttd']}}</div>
        <br/>
        @if(!empty($data['qr_code_dosen']))
            <img src="data:image/svg+xml;base64,{{ $data['qr_code_dosen'] }}" alt="QR TTD Dosen" style="width: 130px; height: 130px; display: block; margin-left: auto; margin-right: 0;">
        @else
            <br/>
        @endif
        <br/>
        <div style="text-decoration: underline; font-weight: bold;">
            {{$dosen->nama_dosen}}
        </div>
        @if(!empty($dosen->nidn))
            <div>NIDN: {{$dosen->nidn}}</div>
        @endif
    </div>
    <hr/>
</main>
<footer>
    Di unduh/generate berdasarkan data absen yang tersimpan pada situs sister.uij.ac.id per
    tanggal {{$data['tgl']['now']}}.
</footer>
</body>
</html>
