<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Hasil Studi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            color: #000;
            background: #fff;
        }

        .page {
            width: 100%;
            padding: 20px 35px 80px 35px;
        }

        /* ===== HEADER ===== */
        .header-wrap {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        .header-logo-cell {
            display: table-cell;
            width: 85px;
            vertical-align: middle;
            padding-right: 12px;
        }
        .header-logo-cell img {
            width: 80px;
            height: auto;
        }
        .header-text-cell {
            display: table-cell;
            vertical-align: middle;
        }
        .header-yayasan {
            font-size: 11pt;
            font-weight: bold;
        }
        .header-univ {
            font-size: 14pt;
            font-weight: bold;
        }
        .header-address {
            font-size: 9pt;
            margin-top: 2px;
            line-height: 1.55;
        }

        .header-line {
            border: none;
            border-top: 2.5px solid #000;
            margin: 8px 0 18px 0;
        }

        /* ===== DOCUMENT TITLE ===== */
        .doc-title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 16px;
        }

        /* ===== STUDENT INFO ===== */
        .info-table {
            width: auto;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .info-table td {
            padding: 2px 0;
            font-size: 10.5pt;
            vertical-align: top;
        }
        .info-table .lbl {
            width: 115px;
        }
        .info-table .sep {
            width: 15px;
            text-align: center;
        }
        .info-table .val {
            /* auto */
        }

        /* ===== NILAI TABLE ===== */
        .nilai-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .nilai-table th {
            border: 1px solid #000;
            padding: 5px 7px;
            font-size: 10.5pt;
            font-weight: bold;
            background: #fff;
            text-align: left;
        }
        .nilai-table td {
            border: 1px solid #000;
            padding: 4px 7px;
            font-size: 10.5pt;
            text-align: left;
        }
        .nilai-table .col-no   { width: 6%;  }
        .nilai-table .col-kode { width: 13%; }
        .nilai-table .col-mk   { width: 57%; }
        .nilai-table .col-sks  { width: 12%; text-align: center; }
        .nilai-table .col-nilai{ width: 12%; text-align: center; }
        .nilai-table tfoot td {
            text-align: center;
            font-weight: bold;
        }
        .nilai-table tfoot .total-label {
            text-align: center;
        }

        /* ===== SUMMARY ===== */
        .summary-table {
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .summary-table td {
            padding: 2px 0;
            font-size: 10.5pt;
        }
        .summary-table .lbl { width: 175px; }
        .summary-table .sep { width: 15px; text-align: center; }

        /* ===== BOTTOM SECTION ===== */
        .bottom-wrap {
            display: table;
            width: 100%;
            margin-top: 5px;
        }
        .bottom-left {
            display: table-cell;
            width: 55%;
            vertical-align: top;
        }
        .bottom-right {
            display: table-cell;
            width: 45%;
            vertical-align: top;
            text-align: center;
        }

        .catatan-label {
            font-size: 10.5pt;
            margin-bottom: 8px;
        }
        .catatan-line {
            border-bottom: 1px dotted #555;
            height: 20px;
            margin-bottom: 4px;
            width: 90%;
        }

        .sign-city {
            font-size: 10.5pt;
            margin-bottom: 8px;
            text-align: center;
        }
        .sign-qr {
            width: 95px;
            height: 95px;
            display: block;
            margin: 0 auto 6px auto;
        }
        .sign-name {
            font-size: 10.5pt;
            font-weight: bold;
            text-align: center;
        }
        .sign-nidn {
            font-size: 10.5pt;
            text-align: center;
        }

        /* ===== RANGKAP ===== */
        .rangkap {
            margin-top: 28px;
            font-size: 10.5pt;
            line-height: 1.75;
        }

        /* ===== FOOTER ===== */
        .footer {
            position: fixed;
            bottom: 15px;
            left: 35px;
            right: 35px;
            border-top: 1.5px solid #000;
            padding-top: 5px;
        }
        .footer-inner {
            display: table;
            width: 100%;
        }
        .footer-inner td {
            display: table-cell;
            font-size: 9pt;
        }
        .footer-left  { width: 30%; text-align: left; font-weight: bold; }
        .footer-center{ width: 40%; text-align: center; font-style: italic; }
        .footer-right { width: 30%; text-align: right; font-weight: bold; }
    </style>
</head>
<body>

<div class="page">

    {{-- ===== HEADER ===== --}}
    <div class="header-wrap">
        <div class="header-logo-cell">
            @if($logo)
                <img src="data:image/png;base64,{{ $logo }}" alt="Logo UIJ">
            @endif
        </div>
        <div class="header-text-cell">
            <div class="header-yayasan">YAYASAN PENDIDIKAN NAHDLATUL ULAMA' JEMBER</div>
            <div class="header-univ">UNIVERSITAS ISLAM JEMBER</div>
            <div class="header-address">
                Jl. Kyai Mojo 101<br>
                Telp. (0331) 488675 Fax. (0331) 428732<br>
                Jember (68133)
            </div>
        </div>
    </div>

    <hr class="header-line">

    {{-- ===== JUDUL ===== --}}
    <div class="doc-title">LEMBAR HASIL STUDI</div>

    {{-- ===== INFO MAHASISWA ===== --}}
    <table class="info-table">
        <tr>
            <td class="lbl">Nama</td>
            <td class="sep">:</td>
            <td class="val">{{ $mahasiswa->nim }} - {{ strtoupper($mahasiswa->nama_mahasiswa) }}</td>
        </tr>
        <tr>
            <td class="lbl">Tahun Ajaran</td>
            <td class="sep">:</td>
            <td class="val">{{ $mahasiswa->tahun_ajaran }}</td>
        </tr>
        <tr>
            <td class="lbl">Fakultas</td>
            <td class="sep">:</td>
            <td class="val">{{ $mahasiswa->nama_fakultas }}</td>
        </tr>
        <tr>
            <td class="lbl">Program Studi</td>
            <td class="sep">:</td>
            <td class="val">{{ $mahasiswa->nama_prodi }}</td>
        </tr>
    </table>

    {{-- ===== TABEL NILAI ===== --}}
    <table class="nilai-table">
        <thead>
            <tr>
                <th class="col-no">No.</th>
                <th class="col-kode">Kode</th>
                <th class="col-mk">Matakuliah</th>
                <th class="col-sks">SKS</th>
                <th class="col-nilai">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mata_kuliah as $index => $mk)
            <tr>
                <td class="col-no">{{ $index + 1 }}.</td>
                <td class="col-kode">{{ $mk['kd_matakuliah'] }}</td>
                <td class="col-mk">{{ $mk['matakuliah'] }}</td>
                <td class="col-sks">{{ $mk['sks'] }}</td>
                <td class="col-nilai">
                    @if($mk['sts_nilai'] != '-' && $mk['sts_nilai'] != '')
                        {{ $mk['sts_nilai'] }}
                    @elseif($mk['nilai_angka'] != '-' && $mk['nilai_angka'] != '')
                        {{ $mk['nilai_angka'] }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="total-label"><strong>Total SKS</strong></td>
                <td class="col-sks"><strong>{{ $total_sks }}</strong></td>
                <td class="col-nilai"></td>
            </tr>
        </tfoot>
    </table>

    {{-- ===== RINGKASAN ===== --}}
    <table class="summary-table">
        <tr>
            <td class="lbl">IP Semester</td>
            <td class="sep">:</td>
            <td>{{ $mahasiswa->ips }}</td>
        </tr>
        <tr>
            <td class="lbl">Beban Maksimum y.a.d</td>
            <td class="sep">:</td>
            <td>{{ $mahasiswa->beban_maks_sks }} SKS</td>
        </tr>
    </table>

    {{-- ===== CATATAN + TTD ===== --}}
    <div class="bottom-wrap">
        <div class="bottom-left">
            <p class="catatan-label">Catatan :</p>
            <div class="catatan-line"></div>
            <div class="catatan-line"></div>
            <div class="catatan-line"></div>
            <div class="catatan-line"></div>
        </div>
        <div class="bottom-right">
            <p class="sign-city">Jember, {{ $tanggal_cetak->locale('id')->isoFormat('D MMMM YYYY') }}</p>
            <img src="data:image/svg+xml;base64,{{ $qr_code }}" alt="QR Code" class="sign-qr">
            <p class="sign-name">{{ $mahasiswa->nama_wakil_dekan }}</p>
            <p class="sign-nidn">{{ $mahasiswa->nidn_wakil_dekan }}</p>
        </div>
    </div>

    {{-- ===== RANGKAP ===== --}}
    <div class="rangkap">
        <p>Rangkap 4</p>
        <p>Masing-masing untuk :</p>
        <p>1. Mahasiswa yang Bersangkutan</p>
        <p>2. Dosen Pembimbing Akademik</p>
        <p>3. Fakultas/Program Studi</p>
        <p>4. Orang Tua / Wali</p>
    </div>

</div>

{{-- ===== FOOTER FIXED ===== --}}
<div class="footer">
    <table class="footer-inner">
        <tr>
            <td class="footer-left">{{ $tanggal_cetak->format('d-m-Y H:i') }}</td>
            <td class="footer-center">SIAKAD - Copyright (c) 2015 Universitas<br>Islam Jember</td>
            <td class="footer-right">Halaman 1/1</td>
        </tr>
    </table>
</div>

</body>
</html>
