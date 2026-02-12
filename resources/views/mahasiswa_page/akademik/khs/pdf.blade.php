<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Hasil Studi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #333;
            padding-bottom: 10px;
        }

        .header-logo {
            width: 60px;
            height: auto;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 16px;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 14px;
            margin-bottom: 3px;
        }

        .header p {
            font-size: 10px;
            color: #666;
        }

        /* Document Title */
        .doc-title {
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background: #f0f0f0;
            border-left: 4px solid #667eea;
        }

        .doc-title h3 {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .doc-title p {
            font-size: 11px;
            margin-top: 3px;
        }

        /* Student Info */
        .student-info {
            margin-bottom: 15px;
        }

        .student-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .student-info td {
            padding: 3px 5px;
            font-size: 10px;
        }

        .student-info td:first-child {
            width: 150px;
            font-weight: bold;
        }

        .student-info td:nth-child(2) {
            width: 10px;
        }

        /* Nilai Table */
        .nilai-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .nilai-table th,
        .nilai-table td {
            border: 1px solid #333;
            padding: 5px;
            text-align: center;
            font-size: 10px;
        }

        .nilai-table th {
            background: #667eea;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        .nilai-table td:nth-child(2),
        .nilai-table td:nth-child(3) {
            text-align: left;
            padding-left: 8px;
        }

        .nilai-table tfoot {
            font-weight: bold;
            background: #f0f0f0;
        }

        /* Summary */
        .summary {
            margin-top: 20px;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
        }

        .summary table {
            width: 100%;
        }

        .summary td {
            padding: 5px;
            font-size: 11px;
        }

        .summary td:first-child {
            width: 200px;
            font-weight: bold;
        }

        .summary td:nth-child(2) {
            width: 10px;
        }

        /* QR Code */
        .qr-section {
            margin-top: 30px;
            text-align: center;
        }

        .qr-code {
            width: 100px;
            height: 100px;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            font-size: 9px;
            text-align: center;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        /* Signature */
        .signature {
            margin-top: 40px;
            text-align: right;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            min-width: 200px;
        }

        .signature-box p {
            margin: 5px 0;
            font-size: 10px;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            padding-top: 3px;
        }

        /* Page Break */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="header">
        @if($logo)
            <img src="data:image/png;base64,{{ $logo }}" alt="Logo" class="header-logo">
        @endif
        <h1>UNIVERSITAS ISLAM JAKARTA</h1>
        <h2>FAKULTAS {{ strtoupper($mahasiswa->jenjang) }}</h2>
        <p>Jl. Raya Cipondoh No.77, Kota Tangerang - Indonesia</p>
    </div>

    <!-- Document Title -->
    <div class="doc-title">
        <h3>KARTU HASIL STUDI (KHS)</h3>
        <p>Semester {{ $tahun_akademik->semester }} - Tahun Akademik {{ $tahun_akademik->nama }}</p>
    </div>

    <!-- Student Info -->
    <div class="student-info">
        <table>
            <tr>
                <td>NIM</td>
                <td>:</td>
                <td>{{ $mahasiswa->nim }}</td>
            </tr>
            <tr>
                <td>Nama Mahasiswa</td>
                <td>:</td>
                <td>{{ $mahasiswa->nama_mahasiswa }}</td>
            </tr>
            <tr>
                <td>Program Studi</td>
                <td>:</td>
                <td>{{ $mahasiswa->nama_prodi }}</td>
            </tr>
            <tr>
                <td>Angkatan</td>
                <td>:</td>
                <td>{{ $mahasiswa->angkatan }}</td>
            </tr>
            <tr>
                <td>Dosen Pembimbing Akademik</td>
                <td>:</td>
                <td>{{ $mahasiswa->nama_dps }} ({{ $mahasiswa->nidn }})</td>
            </tr>
        </table>
    </div>

    <!-- Nilai Table -->
    <table class="nilai-table">
        <thead>
        <tr>
            <th style="width: 5%;">NO</th>
            <th style="width: 12%;">KODE MK</th>
            <th style="width: 35%;">NAMA MATA KULIAH</th>
            <th style="width: 8%;">SKS</th>
            <th style="width: 12%;">NILAI ANGKA</th>
            <th style="width: 10%;">NILAI HURUF</th>
            <th style="width: 10%;">BOBOT</th>
            <th style="width: 8%;">SKS x BOBOT</th>
        </tr>
        </thead>
        <tbody>
        @foreach($mata_kuliah as $index => $mk)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $mk['kd_mata_kuliah'] }}</td>
                <td style="text-align: left; padding-left: 8px;">{{ $mk['nama_mata_kuliah'] }}</td>
                <td>{{ $mk['sks'] }}</td>
                <td>{{ $mk['nilai_angka'] }}</td>
                <td><strong>{{ $mk['nilai_huruf'] }}</strong></td>
                <td>{{ $mk['bobot'] }}</td>
                <td>{{ number_format($mk['sks'] * floatval($mk['bobot']), 2) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3" style="text-align: right; padding-right: 10px;">TOTAL</td>
            <td>{{ $total_sks }}</td>
            <td colspan="3">INDEKS PRESTASI SEMESTER (IPS)</td>
            <td>{{ $ips }}</td>
        </tr>
        </tfoot>
    </table>

    <!-- Summary -->
    <div class="summary">
        <table>
            <tr>
                <td>Jumlah Mata Kuliah</td>
                <td>:</td>
                <td>{{ $total_mk }} Mata Kuliah</td>
            </tr>
            <tr>
                <td>Total SKS Semester Ini</td>
                <td>:</td>
                <td>{{ $total_sks }} SKS</td>
            </tr>
            <tr>
                <td>Indeks Prestasi Semester (IPS)</td>
                <td>:</td>
                <td><strong>{{ $ips }}</strong></td>
            </tr>
            <tr>
                <td>Indeks Prestasi Kumulatif (IPK)</td>
                <td>:</td>
                <td><strong>{{ $ipk }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- QR Code & Signature -->
    <table style="width: 100%; margin-top: 30px;">
        <tr>
            <td style="width: 50%; text-align: center; vertical-align: top;">
                <div class="qr-section">
                    <p style="font-size: 9px; margin-bottom: 5px;">Verifikasi Dokumen:</p>
                    <img src="data:image/svg+xml;base64,{{ $qr_code }}" alt="QR Code" class="qr-code">
                    <p style="font-size: 8px; margin-top: 5px; color: #666;">Scan untuk verifikasi keaslian dokumen</p>
                </div>
            </td>
            <td style="width: 50%; text-align: center; vertical-align: top;">
                <div class="signature">
                    <p>Tangerang, {{ date('d F Y') }}</p>
                    <p style="margin-top: 5px;">Dosen Pembimbing Akademik</p>
                    <div class="signature-line">
                        <p><strong>{{ $mahasiswa->nama_dps }}</strong></p>
                        <p>NIDN. {{ $mahasiswa->nidn }}</p>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Dicetak pada: {{ $tanggal_cetak }} WIB</p>
        <p>Dokumen ini dicetak secara otomatis dan sah tanpa tanda tangan basah</p>
    </div>
</div>
</body>
</html>
