<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Presensi Mahasiswa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 0;
            size: A4 portrait;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
            color: #000;
            background: #fff;
            line-height: 1.25;
        }

        div.page {
            margin: 8mm 15mm 6mm 15mm;
        }

        /* KOP */
        table.kop {
            width: 100%;
            border-collapse: collapse;
        }

        table.kop td {
            border: none;
            padding: 1px 3px;
            vertical-align: middle;
        }

        .kop-logo {
            width: 64px;
            text-align: center;
            padding: 0 !important;
        }

        .kop-logo img {
            width: 60px;
            height: auto;
        }

        .kop-text {
            text-align: center;
        }

        .kop-text .t1 {
            font-size: 7.5pt;
        }

        .kop-text .t2 {
            font-size: 15pt;
            font-weight: bold;
            margin: 1px 0;
        }

        .kop-text .t3 {
            font-size: 8pt;
        }

        .kop-text .t4 {
            font-size: 8pt;
        }

        .kop-text .t5 {
            font-size: 6.5pt;
            color: #333;
            margin-top: 1px;
        }

        hr.line1 {
            border: none;
            border-top: 3px solid #000;
            margin: 4px 0 1px 0;
        }

        hr.line2 {
            border: none;
            border-top: 1px solid #000;
            margin: 0 0 7px 0;
        }

        /* JUDUL */
        .judul {
            text-align: center;
            font-size: 11.5pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 0 0 5px 0;
        }

        /* META */
        table.meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            font-size: 8.5pt;
        }

        table.meta td {
            border: none;
            padding: 0;
        }

        /* INFO BOX */
        table.info {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 6px;
            font-size: 8.5pt;
        }

        table.info td {
            border: 1px solid #000;
            padding: 2px 5px;
            vertical-align: top;
        }

        /* TABEL PRESENSI */
        table.tp {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
            margin-bottom: 6px;
        }

        table.tp thead tr {
            background-color: #2c3e50;
            color: #fff;
        }

        table.tp th {
            border: 1px solid #000;
            padding: 3px 2px;
            text-align: center;
            font-weight: bold;
            font-size: 7pt;
        }

        table.tp td {
            border: 1px solid #666;
            padding: 2px 3px;
            vertical-align: middle;
        }

        table.tp tbody tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        .tc {
            text-align: center;
        }

        .tl {
            text-align: left;
        }

        .th-col {
            width: 30px;
        }

        .nim-col {
            width: 100px;
        }

        .nama-col {
            width: auto;
        }

        .date-col {
            width: 30px;
        }

        /* TANDA TANGAN */
        table.ttd {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        table.ttd td {
            border: none;
            text-align: center;
            vertical-align: top;
            padding: 0 5px;
        }
    </style>
</head>

<body>
    <div class="page">

        {{-- KOP SURAT UIJ --}}
        <table class="kop">
            <tr>
                <td class="kop-logo">
                    @if (!empty($logo))
                        <img src="data:image/png;base64,{{ $logo }}" alt="Logo UIJ">
                    @endif
                </td>
                <td class="kop-text">
                    <div class="t1">YAYASAN PENDIDIKAN NAHDLATUL ULAMA' JEMBER</div>
                    <div class="t2">UNIVERSITAS ISLAM JEMBER</div>
                    <div class="t3">{{ $fakultas }}</div>
                    <div class="t4">{{ $program_studi }}</div>
                    <div class="t5">Jl. Kyai Mojo 101 Jember - 68136, Telp. (0331) 487550, Fax. (0331) 427784</div>
                </td>
            </tr>
        </table>
        <hr class="line1">
        <hr class="line2">

        {{-- JUDUL --}}
        <div class="judul">DAFTAR PRESENSI MAHASISWA - PERTEMUAN KE {{ $pertemuan_ke }}</div>

        {{-- META --}}
        <table class="meta">
            <tr>
                <td>Semester &nbsp;: &nbsp;<strong>{{ $semester ?? '-' }}</strong></td>
                <td>Tanggal Pertemuan &nbsp;: &nbsp;<strong>{{ $tanggal_pertemuan ?? '-' }}</strong></td>
                <td style="text-align:right;">Tanggal Cetak &nbsp;: &nbsp;{{ $tanggal_cetak }}</td>
            </tr>
        </table>

        {{-- INFO BOX --}}
        <table class="info">
            <tr>
                <td style="width:50%; border-right:2px solid #000;">
                    <table style="width:100%;border-collapse:collapse;">
                        <tr>
                            <td style="border:none;padding:1px 0;width:100px;">Nama Dosen</td>
                            <td style="border:none;padding:1px 0;width:6px;">:</td>
                            <td style="border:none;padding:1px 0;font-weight:bold;">{{ $rekap_detail->nama_dosen ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border:none;padding:1px 0;">NIDN</td>
                            <td style="border:none;padding:1px 0;">:</td>
                            <td style="border:none;padding:1px 0;">{{ $rekap_detail->nidn ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border:none;padding:1px 0;">Mata Kuliah</td>
                            <td style="border:none;padding:1px 0;">:</td>
                            <td style="border:none;padding:1px 0;">{{ $rekap_detail->nama_mata_kuliah ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width:50%;">
                    <table style="width:100%;border-collapse:collapse;">
                        <tr>
                            <td style="border:none;padding:1px 0;width:120px;">Kelas</td>
                            <td style="border:none;padding:1px 0;width:6px;">:</td>
                            <td style="border:none;padding:1px 0;">{{ $nama_kelas ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border:none;padding:1px 0;">Kode Mata Kuliah</td>
                            <td style="border:none;padding:1px 0;">:</td>
                            <td style="border:none;padding:1px 0;">{{ $rekap_detail->kd_mata_kuliah ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border:none;padding:1px 0;">SKS</td>
                            <td style="border:none;padding:1px 0;">:</td>
                            <td style="border:none;padding:1px 0;">{{ $sks ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- TABEL PRESENSI --}}
        <table class="tp">
            <thead>
                <tr>
                    <th class="th-col">No</th>
                    <th class="nim-col">NIM</th>
                    <th class="nama-col">Nama Mahasiswa</th>
                    <th style="width:60px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @if(count($rekap) > 0)
                @foreach($rekap as $index => $mhs)
                    <tr>
                        <td class="tc">{{ $index + 1 }}</td>
                        <td class="tc">{{ $mhs->nim }}</td>
                        <td class="tl">{{ $mhs->nama_mahasiswa }}</td>
                        @if($mhs->status_pertemuan == 1)
                            <td class="tc" style="color:#28a745;font-weight:bold;">Hadir</td>
                        @elseif($mhs->status_pertemuan == 2)
                            <td class="tc" style="color:#ffc107;font-weight:bold;">Ijin</td>
                        @elseif($mhs->status_pertemuan == 3)
                            <td class="tc" style="color:#17a2b8;font-weight:bold;">Sakit</td>
                        @else
                            <td class="tc" style="color:#dc3545;font-weight:bold;">Alpha</td>
                        @endif
                    </tr>
                @endforeach
                @else
                    <tr>
                        <td colspan="4" class="tc" style="padding:5px;">Tidak ada data presensi mahasiswa</td>
                    </tr>
                @endif
            </tbody>
        </table>

        {{-- LEGENDA --}}
        <div style="font-size:7.5pt;margin-bottom:5px;">
            <strong>Keterangan:</strong>
            <span style="color:#28a274;font-weight:bold;">Hadir</span> &nbsp;&nbsp;
            <span style="color:#ffc107;font-weight:bold;">Ijin</span> &nbsp;&nbsp;
            <span style="color:#17a2b8;font-weight:bold;">Sakit</span> &nbsp;&nbsp;
            <span style="color:#dc3545;font-weight:bold;">Alpha</span> &nbsp;&nbsp;
            | Total Mahasiswa: <strong>{{ count($rekap) }}</strong>
            | Hadir: <strong>{{ $rekap_summary['hadir'] }}</strong>
            | Ijin: <strong>{{ $rekap_summary['izin'] }}</strong>
            | Sakit: <strong>{{ $rekap_summary['sakit'] }}</strong>
            | Alpha: <strong>{{ $rekap_summary['alpha'] }}</strong>
        </div>

        {{-- FOOTER --}}
        <div style="border-top:1px solid #aaa;margin-top:5px;padding-top:3px;">
            <table style="width:100%;border-collapse:collapse;font-size:6.5pt;color:#555;">
                <tr>
                    <td style="text-align:left;border:none;">Dicetak pada {{ $tanggal_cetak }}</td>
                    <td style="text-align:center;border:none;">Presensi Mahasiswa - Sistem Informasi Akademik UIJ</td>
                    <td style="text-align:right;border:none;"></td>
                </tr>
            </table>
        </div>

    </div>
</body>

</html>
