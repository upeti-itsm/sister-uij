<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Presensi Mahasiswa</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            line-height: 1.2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .logo {
            width: 60px;
            height: 60px;
            /*background: #f0f0f0;*/
            /*border: 1px solid #ccc;*/
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #666;
        }

        .header-text {
            flex: 1;
        }

        .header-text h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            color: #000;
        }

        .header-text p {
            margin: 2px 0;
            font-size: 10px;
        }

        .course-info {
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 10px;
            border: 1px solid #dee2e6;
        }

        .course-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .course-info td {
            padding: 3px 8px;
            font-size: 11px;
        }

        .course-info .label {
            font-weight: bold;
            width: 120px;
        }

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .attendance-table th,
        .attendance-table td {
            border: 1px solid #000;
            padding: 4px 2px;
            text-align: center;
            font-size: 9px;
        }

        .attendance-table th {
            background-color: #e9ecef;
            font-weight: bold;
            font-size: 8px;
        }

        .no-col {
            width: 30px;
        }

        .nim-col {
            width: 80px;
        }

        .nama-col {
            width: 150px;
            text-align: left !important;
        }

        .date-col {
            width: 35px;
        }

        .attendance-cell {
            height: 25px;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }

        .signature-section {
            display: inline-block;
            text-align: center;
            margin-left: 50px;
        }

        .signature-line {
            width: 200px;
            border-bottom: 1px solid #000;
            margin: 50px auto 5px auto;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
<!-- Header dengan Kop Universitas -->
<div class="header">
    <div class="header-content">
        <table>
            <tbody>
            <tr>
                <td style="width: 20%">
                    <div class="logo">
                        <img src="{{ asset('image/logo-uij.png') }}" alt="Logo UIJ" style="max-height: 100%">
                    </div>
                </td>
                <td style="width: 80%">
                    <div class="header-text" style="padding-left: 15px">
                        <h1>UNIVERSITAS ISLAM JEMBER</h1>
                        <p>Jl. Kyai Mojo No. 101, Kaliwates, Jember, Jawa Timur 68133</p>
                        <p>Telp: (0331) 488675 | Email: pmb@uij.ac.id | Website: www.uij.ac.id</p>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Informasi Mata Kuliah -->
<div class="course-info">
    <table>
        <tr>
            <td class="label">Mata Kuliah</td>
            <td>: {{ $rekap[0]->nama_mata_kuliah }}</td>
            <td class="label">Semester</td>
            <td>: {{ $rekap[0]->semester }}</td>
        </tr>
        <tr>
            <td class="label">Dosen Pengampu</td>
            <td>: {{ $rekap[0]->nama_dosen }}</td>
            <td class="label">Kelas</td>
            <td>: {{$rekap[0]->nama_kelas}} - {{$rekap[0]->nama_program_studi}}</td>
        </tr>
        <tr>
            <td class="label">Kode Mata Kuliah</td>
            <td>: {{ $rekap[0]->kd_mata_kuliah }}</td>
            <td class="label">SKS</td>
            <td>: {{ $rekap[0]->sks}}</td>
        </tr>
    </table>
</div>
<table style="width: 100%">
    <tr>
        <td>
            <small><em>Diunduh berdasarkan data sister.uij.ac.id per tanggal {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y H:i:s') }}</em></small>
        </td>
        <td style="text-align: right">
            <img src="{{ asset('image/hadir.png') }}" width="12"> : Hadir | <b style="color: #902b2b"><i>I</i></b> : Ijin | <b style="color: #902b2b"><i>S</i></b> : Sakit | <img src="{{ asset('image/alpha.png') }}" width="12"> : Alpha
        </td>
    </tr>
</table>
<!-- Tabel Presensi -->
<table class="attendance-table">
    <thead>
    <tr>
        <th colspan="3">Tanggal Pertemuan</th>
        @for($i = 0; $i < 16; $i++)
            <th class="date-col">{{ is_null($tanggal[$i]) ? "-" : $tanggal[$i] }}</th>
        @endfor
        <th rowspan="2">Total Kehadiran</th>
    </tr>
    <tr>
        <th class="no-col">No</th>
        <th class="nim-col">NIM</th>
        <th class="nama-col" style="text-align: center!important;">Nama Mahasiswa</th>
        @for($i = 1; $i <= 16; $i++)
            <th class="date-col">{{ $i }}</th>
        @endfor
    </tr>
    </thead>
    <tbody>
    @foreach($rekap as $index => $mhs)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $mhs->nim }}</td>
            <td class="nama-col">{{ $mhs->nama_mahasiswa }}</td>
            @for($i = 1; $i <= 16; $i++)
                @php($field = "pertemuan_".$i)
                @if(!is_null($tanggal[$i-1]))
                    @if($mhs->$field == 1)
                        <td class="attendance-cell">
                            <img src="{{ asset('image/hadir.png') }}" width="12">
                        </td>
                    @elseif($mhs->$field == 2)
                        <td class="attendance-cell">
                            <b style="color: #902b2b"><i>I</i></b>
                        </td>
                    @elseif($mhs->$field == 3)
                        <td class="attendance-cell">
                            <b style="color: #902b2b"><i>S</i></b>
                        </td>
                    @else
                        <td class="attendance-cell">
                            <img src="{{ asset('image/alpha.png') }}" width="12">
                        </td>
                    @endif
                @else
                    <td class="attendance-cell"></td>
                @endif
            @endfor
            <td class="attendance-cell">{{ $mhs->total_hadir }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<!-- Footer dengan Tanda Tangan -->
<div class="footer">
    <div class="signature-section">
        <p>Jember, {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y') }}</p>
        <p>Dosen Pengampu,</p>
        <div class="signature-line"></div>
        <p><strong>{{ $rekap[0]->nama_dosen }}</strong></p>
        <p>NIDN: {{ $rekap[0]->nidn }}</p>
    </div>
</div>
</body>
</html>
