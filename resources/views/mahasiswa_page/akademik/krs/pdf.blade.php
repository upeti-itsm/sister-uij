<! DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KRS - {{ $mahasiswa->nim }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #000;
            padding: 15px;
        }

        .header {
            position: relative;
            margin-bottom: 10px;
            border-bottom:  2px solid #000;
            padding-bottom: 8px;
            min-height: 80px;
        }

        .header-logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 70px;
            height: 70px;
        }

        .header-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .header-content {
            text-align: center;
            padding: 0 80px;
        }

        .header-content h1 {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .header-content p {
            font-size: 9pt;
            margin: 1px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 12px;
            background-color: #28a745;
            color: white;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8pt;
            margin-top: 3px;
        }

        .info-box {
            width: 100%;
            margin:  8px 0;
            border: 1px solid #000;
            background-color: #f8f9fa;
            border-collapse: collapse;
        }

        .info-box td {
            padding: 3px 8px;
            font-size: 8pt;
            border: 1px solid #ddd;
        }

        .info-box td.label {
            font-weight: bold;
            width: 25%;
            background-color: #e9ecef;
        }

        .data-table {
            width:  100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 8pt;
        }

        .data-table thead {
            background-color:  #343a40;
            color: white;
        }

        .data-table th {
            border: 1px solid #000;
            padding: 4px 3px;
            text-align: center;
            font-weight: bold;
        }

        .data-table td {
            border: 1px solid #666;
            padding: 3px;
        }

        . data-table td.center {
            text-align: center;
        }

        .data-table tfoot td {
            background-color: #f0f0f0;
            font-weight:  bold;
            padding: 4px;
        }

        . comment-box {
            margin:  5px 0;
            padding: 5px 8px;
            border: 1px solid #ccc;
            background-color: #fff8dc;
            font-size: 7.5pt;
            line-height: 1.4;
        }

        .comment-box strong {
            font-size:  8pt;
        }

        .signature-section {
            margin-top: 10px;
            width: 100%;
            border-collapse: collapse;
        }

        .signature-section td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 5px;
            border: none;
        }

        .signature-section p {
            margin:  2px 0;
            font-size: 7.5pt;
        }

        .signature-section .name {
            font-weight: bold;
            margin-top: 3px;
            font-size: 8pt;
        }

        .signature-section .nidn {
            font-size: 7pt;
            color: #666;
        }

        .qr-code {
            width: 70px;
            height: 70px;
            margin: 5px auto;
            display:  block;
            border: 1px solid #ddd;
        }

        .footer {
            margin-top: 8px;
            text-align: center;
            font-size: 7pt;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

        @page {
            margin: 15mm;
            size: A4 portrait;
        }
    </style>
</head>
<body>
<!-- Header dengan Logo -->
<div class="header">
    @if(!empty($logo))
        <div class="header-logo">
            <img src="data:image/png;base64,{{ $logo }}" alt="Logo">
        </div>
    @endif
    <div class="header-content">
        <h1>KARTU RENCANA STUDI (KRS)</h1>
        <p><strong>{{ $mahasiswa->jenjang }} {{ $mahasiswa->nama_prodi }}</strong></p>
        <p>T.A {{ $tahun_akademik->nama }} - Semester {{ $tahun_akademik->semester }}</p>
        <span class="status-badge">TELAH DISETUJUI</span>
    </div>
</div>

<!-- Info Mahasiswa Compact -->
<table class="info-box">
    <tr>
        <td class="label">NIM</td>
        <td>{{ $mahasiswa->nim }}</td>
        <td class="label">Angkatan</td>
        <td>{{ $mahasiswa->angkatan }}</td>
    </tr>
    <tr>
        <td class="label">Nama</td>
        <td colspan="3">{{ $mahasiswa->nama_mahasiswa }}</td>
    </tr>
    <tr>
        <td class="label">Dosen PA</td>
        <td>{{ $mahasiswa->nama_dps }}</td>
        <td class="label">Tgl Pengajuan</td>
        <td>{{ $tgl_pengajuan }}</td>
    </tr>
</table>

<!-- Tabel Mata Kuliah Compact -->
<table class="data-table">
    <thead>
    <tr>
        <th width="4%">No</th>
        <th width="10%">Kode</th>
        <th width="28%">Mata Kuliah</th>
        <th width="6%">Kls</th>
        <th width="5%">SKS</th>
        <th width="8%">Hari</th>
        <th width="12%">Jam</th>
        <th width="8%">Ruang</th>
        <th width="19%">Dosen</th>
    </tr>
    </thead>
    <tbody>
    @foreach($mata_kuliah as $index => $mk)
        <tr>
            <td class="center">{{ $index + 1 }}</td>
            <td class="center">{{ $mk['kd_mata_kuliah'] }}</td>
            <td>{{ $mk['nama_mata_kuliah'] }}</td>
            <td class="center">{{ $mk['nama_kelas'] }}</td>
            <td class="center">{{ $mk['sks'] }}</td>
            <td>{{ $mk['hari'] }}</td>
            <td class="center">{{ $mk['jam'] }}</td>
            <td class="center">{{ $mk['ruang'] }}</td>
            <td>{{ $mk['nama_dosen'] }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="2" class="center">TOTAL</td>
        <td><strong>{{ $total_mk }} Mata Kuliah</strong></td>
        <td colspan="1"></td>
        <td class="center"><strong>{{ $total_sks }}</strong></td>
        <td colspan="4"></td>
    </tr>
    </tfoot>
</table>

<!-- Komentar Compact -->
<div class="comment-box">
    <strong>Catatan DPS:</strong> {{ $komentar_dps }}
</div>

<!-- Tanda Tangan dengan QR Code Base64 PNG -->
<table class="signature-section">
    <tr>
        <td>
            <p><br/><strong>Mahasiswa</strong></p>
            <img src="data:image/png;base64,{{ $qr_mahasiswa }}" class="qr-code" alt="QR Mahasiswa">
            <p class="name">{{ $mahasiswa->nama_mahasiswa }}</p>
            <p class="nidn">NIM: {{ $mahasiswa->nim }}</p>
            <p style="font-size: 6.5pt; color: #888;">{{ $tgl_pengajuan }}</p>
        </td>
        <td>
            <p><br/><strong>Dosen Pembimbing Akademik</strong></p>
            <img src="data:image/png;base64,{{ $qr_dps }}" class="qr-code" alt="QR DPS">
            <p class="name">{{ $mahasiswa->nama_dps }}</p>
            <p class="nidn">NIDN: {{ $mahasiswa->nidn }}</p>
            <p style="font-size: 6.5pt; color: #888;">{{ $tgl_verifikasi_dps }}</p>
        </td>
        <td>
            <p><strong>Mengetahui,<br>Ketua Program Studi</strong></p>
            <img src="data:image/png;base64,{{ $qr_kaprodi }}" class="qr-code" alt="QR Kaprodi">
            <p class="name">{{ $mahasiswa->nama_kaprodi }}</p>
            <p class="nidn">NIDN: {{ $mahasiswa->nidn_kaprodi }}</p>
            <p style="font-size: 6.5pt; color: #888;">{{ $tgl_verifikasi_kaprodi }}</p>
        </td>
    </tr>
</table>

<!-- Footer -->
<div class="footer">
    <p>Dokumen ini dicetak pada {{ $tanggal_cetak }} | KRS - Sistem Informasi Akademik</p>
    <p style="font-size: 6.5pt;">Dokumen ini sah dan dilindungi dengan QR Code digital signature</p>
</div>
</body>
</html>
