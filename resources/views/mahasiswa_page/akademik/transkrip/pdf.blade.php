<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Transkrip Nilai - {{ $mahasiswa->nim }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1a1a1a;
            background: #fff;
        }

        /* ===================== LAYOUT ===================== */
        .page {
            width: 100%;
            padding: 20px 30px;
        }

        /* ===================== HEADER ===================== */
        .header-wrapper {
            width: 100%;
            border-bottom: 3px solid #1a237e;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }

        .header-table {
            width: 100%;
        }

        .header-logo {
            width: 70px;
            text-align: center;
            vertical-align: middle;
        }

        .header-logo img {
            width: 60px;
            height: auto;
        }

        .header-text {
            text-align: center;
            vertical-align: middle;
        }

        .header-text .universitas {
            font-size: 13px;
            font-weight: bold;
            color: #1a237e;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-text .fakultas {
            font-size: 10px;
            color: #333;
            margin-top: 2px;
        }

        .header-text .alamat {
            font-size: 8px;
            color: #666;
            margin-top: 2px;
        }

        .header-qr {
            width: 70px;
            text-align: center;
            vertical-align: middle;
        }

        .header-qr img {
            width: 55px;
            height: 55px;
        }

        .header-qr small {
            font-size: 7px;
            color: #666;
            display: block;
            margin-top: 2px;
        }

        /* ===================== JUDUL ===================== */
        .title-wrapper {
            text-align: center;
            margin-bottom: 14px;
        }

        .title-wrapper .title-main {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #1a237e;
            border: 2px solid #1a237e;
            display: inline-block;
            padding: 5px 20px;
            border-radius: 4px;
        }

        .title-wrapper .title-sub {
            font-size: 9px;
            color: #555;
            margin-top: 4px;
        }

        /* ===================== INFO MAHASISWA ===================== */
        .info-wrapper {
            width: 100%;
            margin-bottom: 14px;
            border: 1px solid #c5cae9;
            border-radius: 4px;
            padding: 10px 12px;
            background-color: #f8f9ff;
        }

        .info-table {
            width: 100%;
        }

        .info-table td {
            font-size: 9.5px;
            padding: 2px 4px;
            vertical-align: top;
        }

        .info-table .label {
            width: 22%;
            color: #555;
            font-weight: bold;
        }

        .info-table .sep {
            width: 2%;
            color: #555;
        }

        .info-table .value {
            width: 26%;
            color: #1a1a1a;
        }

        /* ===================== TABEL NILAI ===================== */
        .semester-title {
            background-color: #1a237e;
            color: white;
            font-size: 9.5px;
            font-weight: bold;
            padding: 5px 10px;
            margin-top: 10px;
            margin-bottom: 0;
            border-radius: 3px 3px 0 0;
        }

        .table-nilai {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            font-size: 9px;
        }

        .table-nilai thead tr th {
            background-color: #e8eaf6;
            color: #1a237e;
            font-weight: bold;
            text-align: center;
            padding: 5px 4px;
            border: 1px solid #c5cae9;
            font-size: 8.5px;
        }

        .table-nilai tbody tr td {
            padding: 4px 5px;
            border: 1px solid #e0e0e0;
            vertical-align: middle;
            color: #1a1a1a;
        }

        .table-nilai tbody tr:nth-child(even) td {
            background-color: #f5f5ff;
        }

        .table-nilai tbody tr:nth-child(odd) td {
            background-color: #ffffff;
        }

        .table-nilai .text-center { text-align: center; }
        .table-nilai .text-right  { text-align: right; }
        .table-nilai .text-left   { text-align: left; }

        /* Footer baris total per semester */
        .table-nilai tfoot tr td {
            background-color: #e8eaf6;
            font-weight: bold;
            font-size: 8.5px;
            padding: 4px 5px;
            border: 1px solid #c5cae9;
            color: #1a237e;
        }

        /* ===================== BADGE NILAI ===================== */
        .badge-nilai {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8.5px;
            color: white;
        }

        .badge-a   { background-color: #4caf50; }
        .badge-ab  { background-color: #66bb6a; }
        .badge-b   { background-color: #8bc34a; color: #fff; }
        .badge-bc  { background-color: #cddc39; color: #333; }
        .badge-c   { background-color: #ffeb3b; color: #333; }
        .badge-d   { background-color: #ff9800; }
        .badge-e   { background-color: #f44336; }
        .badge-def { background-color: #9e9e9e; }

        /* ===================== REKAP IPK ===================== */
        .rekap-wrapper {
            margin-top: 14px;
            width: 100%;
        }

        .rekap-table {
            width: 100%;
            border-collapse: collapse;
        }

        .rekap-left {
            width: 60%;
            vertical-align: top;
            padding-right: 10px;
        }

        .rekap-right {
            width: 40%;
            vertical-align: top;
        }

        /* Tabel keterangan nilai */
        .table-keterangan {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
        }

        .table-keterangan th {
            background-color: #e8eaf6;
            color: #1a237e;
            font-weight: bold;
            text-align: center;
            padding: 4px;
            border: 1px solid #c5cae9;
        }

        .table-keterangan td {
            padding: 3px 5px;
            border: 1px solid #e0e0e0;
            text-align: center;
        }

        /* Kotak IPK */
        .ipk-box {
            border: 2px solid #1a237e;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
            background: linear-gradient(135deg, #e8eaf6 0%, #f8f9ff 100%);
        }

        .ipk-box .ipk-label {
            font-size: 8.5px;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
        }

        .ipk-box .ipk-value {
            font-size: 28px;
            font-weight: bold;
            color: #1a237e;
            line-height: 1.1;
            margin: 4px 0;
        }

        .ipk-box .ipk-scale {
            font-size: 8px;
            color: #777;
        }

        .ipk-box .ipk-predikat {
            font-size: 9.5px;
            font-weight: bold;
            margin-top: 6px;
            padding: 3px 10px;
            border-radius: 10px;
            display: inline-block;
        }

        .predikat-cumlaude    { background-color: #ffd700; color: #5d4037; }
        .predikat-sangat-baik { background-color: #4caf50; color: white; }
        .predikat-baik        { background-color: #2196f3; color: white; }
        .predikat-cukup       { background-color: #ff9800; color: white; }

        /* Rekap SKS */
        .table-rekap {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin-bottom: 8px;
        }

        .table-rekap td {
            padding: 4px 6px;
            border: 1px solid #e0e0e0;
        }

        .table-rekap .rekap-label {
            width: 65%;
            color: #555;
            font-weight: bold;
        }

        .table-rekap .rekap-value {
            width: 35%;
            text-align: center;
            font-weight: bold;
            color: #1a237e;
        }

        /* ===================== TTANDA TANGAN ===================== */
        .ttd-wrapper {
            margin-top: 20px;
            width: 100%;
        }

        .ttd-table {
            width: 100%;
        }

        .ttd-col {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }

        .ttd-col .ttd-title {
            font-size: 9px;
            color: #555;
            margin-bottom: 50px;
        }

        .ttd-col .ttd-name {
            font-size: 9.5px;
            font-weight: bold;
            color: #1a1a1a;
            border-top: 1px solid #333;
            padding-top: 4px;
        }

        .ttd-col .ttd-nip {
            font-size: 8px;
            color: #666;
        }

        /* ===================== FOOTER ===================== */
        .footer-wrapper {
            margin-top: 16px;
            border-top: 1px solid #c5cae9;
            padding-top: 6px;
            text-align: center;
        }

        .footer-wrapper p {
            font-size: 7.5px;
            color: #888;
        }

        .footer-wrapper .tanggal-cetak {
            font-size: 8px;
            color: #555;
            font-style: italic;
        }

        /* ===================== PAGE BREAK ===================== */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- ==================== HEADER ==================== --}}
    <div class="header-wrapper">
        <table class="header-table">
            <tr>
                {{-- Logo --}}
                <td class="header-logo">
                    @if($logo)
                        <img src="data:image/png;base64,{{ $logo }}" alt="Logo">
                    @endif
                </td>

                {{-- Teks Institusi --}}
                <td class="header-text">
                    <div class="universitas">Universitas Islam Jakarta</div>
                    <div class="fakultas">{{ $mahasiswa->nama_prodi ?? 'Program Studi' }}</div>
                    <div class="alamat">
                        Jl. Balai Rakyat No.9, Utan Kayu Utara, Matraman, Jakarta Timur 13120
                    </div>
                    <div class="alamat">Telp. (021) 8191059 &nbsp;|&nbsp; www.uij.ac.id</div>
                </td>

                {{-- QR Code --}}
                <td class="header-qr">
                    @if($qr_code)
                        <img src="data:image/svg+xml;base64,{{ $qr_code }}" alt="QR Code">
                        <small>Scan untuk verifikasi</small>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- ==================== JUDUL ==================== --}}
    <div class="title-wrapper">
        <div class="title-main">Transkrip Nilai</div>
        <div class="title-sub">
            {{ $pengajuan->bahasa === 'Inggris' ? 'Academic Transcript' : 'Dokumen Resmi Akademik' }}
            &nbsp;&bull;&nbsp; No. {{ $pengajuan->no_pengajuan ?? '-' }}
        </div>
    </div>

    {{-- ==================== INFO MAHASISWA ==================== --}}
    <div class="info-wrapper">
        <table class="info-table">
            <tr>
                <td class="label">Nama Mahasiswa</td>
                <td class="sep">:</td>
                <td class="value"><strong>{{ $mahasiswa->nama_mahasiswa ?? '-' }}</strong></td>
                <td class="label">Program Studi</td>
                <td class="sep">:</td>
                <td class="value">{{ $mahasiswa->nama_prodi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">NIM</td>
                <td class="sep">:</td>
                <td class="value">{{ $mahasiswa->nim ?? '-' }}</td>
                <td class="label">Jenjang</td>
                <td class="sep">:</td>
                <td class="value">{{ $mahasiswa->jenjang ?? 'S1' }}</td>
            </tr>
            <tr>
                <td class="label">Angkatan</td>
                <td class="sep">:</td>
                <td class="value">{{ $mahasiswa->angkatan ?? '-' }}</td>
                <td class="label">Keperluan</td>
                <td class="sep">:</td>
                <td class="value">{{ $pengajuan->keperluan ?? '-' }}</td>
            </tr>
        </table>
    </div>

    {{-- ==================== TABEL NILAI PER SEMESTER ==================== --}}
    @foreach($nilai_per_semester as $semester)
        <div class="semester-title">
            Semester {{ $semester['semester'] }}
            &nbsp;&bull;&nbsp;
            Tahun Akademik {{ $semester['nama'] }}
        </div>

        <table class="table-nilai">
            <thead>
            <tr>
                <th class="text-center" width="4%">No</th>
                <th class="text-left"   width="13%">Kode MK</th>
                <th class="text-left"   width="38%">Nama Mata Kuliah</th>
                <th class="text-center" width="8%">SKS</th>
                <th class="text-center" width="11%">Nilai Angka</th>
                <th class="text-center" width="10%">Nilai Huruf</th>
                <th class="text-center" width="10%">Bobot</th>
                <th class="text-center" width="6%">Ket.</th>
            </tr>
            </thead>
            <tbody>
            @foreach($semester['mata_kuliah'] as $no => $mk)
                <tr>
                    <td class="text-center">{{ $no + 1 }}</td>
                    <td class="text-left">{{ $mk['kd_mata_kuliah'] ?? '-' }}</td>
                    <td class="text-left">{{ $mk['nama_mata_kuliah'] ?? '-' }}</td>
                    <td class="text-center">{{ $mk['sks'] ?? 0 }}</td>
                    <td class="text-center">{{ $mk['nilai_angka'] ?? '-' }}</td>
                    <td class="text-center">
                        @php
                            $nh = strtoupper($mk['nilai_huruf'] ?? '');
                            $badgeMap = [
                                'A'  => 'badge-a',
                                'AB' => 'badge-ab',
                                'B'  => 'badge-b',
                                'BC' => 'badge-bc',
                                'C'  => 'badge-c',
                                'D'  => 'badge-d',
                                'E'  => 'badge-e',
                            ];
                            $badgeClass = $badgeMap[$nh] ?? 'badge-def';
                        @endphp
                        @if($nh && $nh !== '-')
                            <span class="badge-nilai {{ $badgeClass }}">{{ $nh }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">{{ $mk['bobot'] ?? '-' }}</td>
                    <td class="text-center">
                        @php $sts = strtoupper($mk['sts_nilai'] ?? ''); @endphp
                        @if($sts === 'LULUS')
                            <span style="color:#4caf50;font-weight:bold;">L</span>
                        @elseif($sts === 'TIDAK LULUS')
                            <span style="color:#f44336;font-weight:bold;">TL</span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3" class="text-right">Total Semester</td>
                <td class="text-center">{{ $semester['total_sks'] }}</td>
                <td colspan="2" class="text-center">IP Semester</td>
                <td class="text-center">{{ $semester['ips'] }}</td>
                <td></td>
            </tr>
            </tfoot>
        </table>
    @endforeach

    {{-- ==================== REKAP & IPK ==================== --}}
    <div class="rekap-wrapper">
        <table class="rekap-table">
            <tr>
                {{-- Kiri: rekap SKS + keterangan nilai --}}
                <td class="rekap-left">

                    {{-- Rekap SKS --}}
                    <table class="table-rekap">
                        <tr>
                            <td class="rekap-label">Total SKS Ditempuh</td>
                            <td class="rekap-value">{{ $total_sks }} SKS</td>
                        </tr>
                        <tr>
                            <td class="rekap-label">Total SKS Lulus</td>
                            <td class="rekap-value">{{ $sks_lulus }} SKS</td>
                        </tr>
                        <tr>
                            <td class="rekap-label">Jumlah Mata Kuliah</td>
                            <td class="rekap-value">{{ $total_mk }} MK</td>
                        </tr>
                    </table>

                    {{-- Keterangan Nilai --}}
                    <table class="table-keterangan">
                        <thead>
                        <tr>
                            <th>Nilai Huruf</th>
                            <th>Bobot</th>
                            <th>Rentang</th>
                            <th>Keterangan</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>A</td>
                            <td>4.00</td>
                            <td>85 – 100</td>
                            <td>Istimewa</td>
                        </tr>
                        <tr>
                            <td>AB</td>
                            <td>3.50</td>
                            <td>80 – 84</td>
                            <td>Sangat Baik</td>
                        </tr>
                        <tr>
                            <td>B</td>
                            <td>3.00</td>
                            <td>70 – 79</td>
                            <td>Baik</td>
                        </tr>
                        <tr>
                            <td>BC</td>
                            <td>2.50</td>
                            <td>65 – 69</td>
                            <td>Cukup Baik</td>
                        </tr>
                        <tr>
                            <td>C</td>
                            <td>2.00</td>
                            <td>56 – 64</td>
                            <td>Cukup</td>
                        </tr>
                        <tr>
                            <td>D</td>
                            <td>1.00</td>
                            <td>45 – 55</td>
                            <td>Kurang</td>
                        </tr>
                        <tr>
                            <td>E</td>
                            <td>0.00</td>
                            <td>0 – 44</td>
                            <td>Tidak Lulus</td>
                        </tr>
                        </tbody>
                    </table>

                </td>

                {{-- Kanan: Kotak IPK --}}
                <td class="rekap-right">
                    <div class="ipk-box">
                        <div class="ipk-label">Indeks Prestasi Kumulatif</div>
                        <div class="ipk-value">{{ $ipk }}</div>
                        <div class="ipk-scale">dari skala 4.00</div>

                        @php
                            $ipkFloat = floatval($ipk);
                            if ($ipkFloat >= 3.51) {
                                $predikat     = 'Cum Laude';
                                $predikatClass = 'predikat-cumlaude';
                            } elseif ($ipkFloat >= 3.01) {
                                $predikat      = 'Sangat Memuaskan';
                                $predikatClass = 'predikat-sangat-baik';
                            } elseif ($ipkFloat >= 2.76) {
                                $predikat      = 'Memuaskan';
                                $predikatClass = 'predikat-baik';
                            } else {
                                $predikat      = 'Cukup';
                                $predikatClass = 'predikat-cukup';
                            }
                        @endphp

                        <div class="ipk-predikat {{ $predikatClass }}">
                            {{ $predikat }}
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ==================== TANDA TANGAN ==================== --}}
    <div class="ttd-wrapper">
        <table class="ttd-table">
            <tr>
                {{-- Kaprodi --}}
                <td class="ttd-col">
                    <div class="ttd-title">
                        Mengetahui,<br>Ketua Program Studi
                    </div>
                    <div class="ttd-name">
                        {{ $pengajuan->nama_kaprodi ?? '( _________________ )' }}
                    </div>
                    <div class="ttd-nip">
                        NIDN. {{ $pengajuan->nidn_kaprodi ?? '-' }}
                    </div>
                </td>

                {{-- Dekan --}}
                <td class="ttd-col">
                    <div class="ttd-title">
                        Menyetujui,<br>Dekan Fakultas
                    </div>
                    <div class="ttd-name">
                        {{ $pengajuan->nama_dekan ?? '( _________________ )' }}
                    </div>
                    <div class="ttd-nip">
                        NIDN. {{ $pengajuan->nidn_dekan ?? '-' }}
                    </div>
                </td>

                {{-- Mahasiswa --}}
                <td class="ttd-col">
                    <div class="ttd-title">
                        Jakarta, {{ $tanggal_cetak }}<br>Mahasiswa
                    </div>
                    <div class="ttd-name">
                        {{ $mahasiswa->nama_mahasiswa ?? '-' }}
                    </div>
                    <div class="ttd-nip">
                        NIM. {{ $mahasiswa->nim ?? '-' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ==================== FOOTER ==================== --}}
    <div class="footer-wrapper">
        <p class="tanggal-cetak">
            Dicetak pada: {{ $tanggal_cetak }}
            &nbsp;&bull;&nbsp;
            Keperluan: {{ $pengajuan->keperluan ?? '-' }}
            &nbsp;&bull;&nbsp;
            Dokumen ini sah tanpa tanda tangan basah apabila QR Code valid
        </p>
        <p>
            &copy; {{ date('Y') }} Universitas Islam Jakarta &mdash; Sistem Informasi Akademik
        </p>
    </div>

</div>
</body>
</html>
