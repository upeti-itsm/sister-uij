<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* ── Header ── */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .header-logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }

        .header-logo img {
            width: 70px;
            height: auto;
        }

        .header-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .header-text .yayasan {
            font-size: 11pt;
            font-weight: bold;
        }

        .header-text .univ {
            font-size: 14pt;
            font-weight: bold;
        }

        .header-text .alamat {
            font-size: 9pt;
            line-height: 1.4;
        }

        .line {
            border: none;
            border-top: 3px double #000;
            margin: 6px 0 14px 0;
        }

        /* ── Info table ── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .info-table td {
            padding: 2px 4px;
            vertical-align: top;
            font-size: 10pt;
        }

        .info-table .label {
            width: 140px;
            font-weight: bold;
        }

        .info-table .sep {
            width: 10px;
            text-align: center;
        }

        /* ── Isi Surat (preserve semua format CKEditor) ── */
        .isi-surat {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
        }

        .isi-surat p {
            margin: 0 0 8px 0;
        }

        .isi-surat ul {
            margin: 4px 0 8px 0;
            padding-left: 20px;
        }

        .isi-surat ol {
            margin: 4px 0 8px 0;
            padding-left: 20px;
        }

        .isi-surat li {
            margin-bottom: 3px;
        }

        .isi-surat strong,
        .isi-surat b {
            font-weight: bold;
        }

        .isi-surat em,
        .isi-surat i {
            font-style: italic;
        }

        .isi-surat u {
            text-decoration: underline;
        }

        .isi-surat s {
            text-decoration: line-through;
        }

        .isi-surat h1,
        .isi-surat h2,
        .isi-surat h3 {
            font-weight: bold;
            margin: 8px 0 4px 0;
        }

        .isi-surat table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 8px;
        }

        .isi-surat table td,
        .isi-surat table th {
            border: 1px solid #000;
            padding: 4px 6px;
        }

        /* Alignment dari inline style CKEditor */
        .isi-surat p[style*="text-align:center"],
        .isi-surat p[style*="text-align: center"] {
            text-align: center;
        }

        .isi-surat p[style*="text-align:right"],
        .isi-surat p[style*="text-align: right"] {
            text-align: right;
        }

        .isi-surat p[style*="text-align:left"],
        .isi-surat p[style*="text-align: left"] {
            text-align: left;
        }

        .isi-surat p[style*="text-align:justify"],
        .isi-surat p[style*="text-align: justify"] {
            text-align: justify;
        }

        /* ── Tanda Tangan ── */
        .sign-section {
            margin-top: 30px;
        }

        .sign-table {
            width: 100%;
            border-collapse: collapse;
        }

        .sign-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }

        .sign-title {
            font-size: 10pt;
            margin-bottom: 4px;
        }

        .qr-ttd {
            width: 80px;
            height: 80px;
            display: block;
            margin: 6px auto;
        }

        .sign-name {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 2px;
            font-size: 10pt;
        }

        .sign-jabatan {
            font-size: 9pt;
            margin-top: 2px;
        }

        /* ── Footer ── */
        .footer-info {
            font-size: 8pt;
            color: #777;
            margin-top: 24px;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
    </style>
</head>

<body>

    {{-- Header Kop Surat --}}
    <div class="header">
        <div class="header-logo">
            @if (!empty($logo))
                <img src="data:image/png;base64,{{ $logo }}" alt="Logo">
            @endif
        </div>
        <div class="header-text">
            <div class="yayasan">YAYASAN PENDIDIKAN NAHDLATUL ULAMA (YPNU) JEMBER</div>
            <div class="univ">UNIVERSITAS ISLAM JEMBER</div>
            <div class="alamat">
                Jl. Kyai Mojo No. 101 Telp. (0331) 488675, Fax. 428732, Jember 68133<br>
                Website: www.uij.ac.id &nbsp; E-mail : uijember@gmail.com
            </div>
        </div>
    </div>
    <hr class="line">

    {{-- Info Surat --}}
    <table class="info-table">
        <tr>
            <td class="label">Nomor Surat</td>
            <td class="sep">:</td>
            <td>{{ $detail->nomor_surat ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Perihal</td>
            <td class="sep">:</td>
            <td>{{ $detail->perihal ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Surat</td>
            <td class="sep">:</td>
            <td>{{ $detail->tanggal_surat_ ?? ($detail->tanggal_surat ?? '-') }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Surat</td>
            <td class="sep">:</td>
            <td>{{ $detail->jenis_surat ?? ($detail->nama_jenis_surat ?? '-') }}</td>
        </tr>
        <tr>
            <td class="label">Unit Pengirim</td>
            <td class="sep">:</td>
            <td>{{ $detail->nama_unit_pengirim ?? ($detail->nama_unit_bagian_pengirim ?? '-') }}</td>
        </tr>
    </table>

    {{-- Isi Surat dari CKEditor — {!! !!} agar HTML dirender --}}
    <div class="isi-surat">
        {!! $detail->isi_surat ?? '-' !!}
    </div>

    {{-- Tanda Tangan --}}
    <div class="sign-section">
        <table class="sign-table">
            <tr>
                {{-- Kiri: Pengaju / Unit Kerja --}}
                <td>
                    <div class="sign-title">
                        Pengaju,<br>
                        {{ $detail->nama_unit_bagian_pengirim ?? ($detail->nama_unit_pengirim ?? '-') }}
                    </div>
                    @if (!empty($qr_code_pengaju))
                        <img src="data:image/svg+xml;base64,{{ $qr_code_pengaju }}" class="qr-ttd">
                    @else
                        <div style="height:80px;"></div>
                    @endif
                    {{-- Coba semua kemungkinan nama field --}}
                    <div class="sign-name">
                        {{ $detail->nama_lengkap_pengaju ??
                            ($detail->nama_personal_pengaju ?? ($detail->nama_pengaju ?? ($detail->nama_lengkap ?? '-'))) }}
                    </div>
                    <div class="sign-jabatan">
                        {{ $detail->jabatan_pengaju ?? ($detail->jabatan_struktural_pengaju ?? '') }}
                    </div>
                </td>

                {{-- Kanan: Pimpinan Rektorat --}}
                <td>
                    <div class="sign-title">
                        {{ $detail->jabatan_pimpinan ?? ($detail->jabatan_struktural_pimpinan ?? 'Pimpinan Rektorat') }},<br>
                        Jember, {{ $tanggal_cetak }}
                    </div>
                    @if (!empty($qr_code_pimpinan))
                        <img src="data:image/svg+xml;base64,{{ $qr_code_pimpinan }}" class="qr-ttd">
                    @else
                        <div style="height:80px;"></div>
                    @endif
                    {{-- Coba semua kemungkinan nama field --}}
                    <div class="sign-name">
                        {{ $detail->nama_personal_mengetahui ??
                            ($detail->nama_personal_mengetahui ??
                                ($detail->nama_personal_mengetahui ?? ($detail->nama_personal_mengetahui ?? '-'))) }}
                    </div>
                    <div class="sign-jabatan">
                        {{ $detail->jabatan_pimpinan ?? ($detail->jabatan_struktural_pimpinan ?? '') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-info">
        Dicetak pada: {{ $tanggal_cetak }} &nbsp;|&nbsp;
        Nomor: {{ $detail->nomor_surat ?? '-' }} &nbsp;|&nbsp;
        Status: {{ $detail->status_surat ?? '-' }}
    </div>

</body>

</html>
