<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Nilai Mahasiswa</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 14px; /* dinaikkan dari 13px */
            margin: 0;
            padding: 0;
            line-height: 1.4;
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
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #666;
        }

        .header-text {
            flex: 1;
        }

        .header-text h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #000;
        }

        .header-text p {
            margin: 3px 0;
            font-size: 12px;
        }

        .course-info {
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 12px;
            border: 1px solid #dee2e6;
        }

        .course-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .course-info td {
            padding: 4px 10px;
            font-size: 14px; /* dinaikkan dari 13px */
        }

        .course-info .label {
            font-weight: bold;
            width: 130px;
        }

        /* Info section dengan layout yang lebih baik */
        .info-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 8px 10px;
            font-size: 12px; /* ukuran font yang lebih besar dari small */
        }

        .info-left {
            flex: 1;
            font-style: italic;
        }

        .info-right {
            font-weight: bold;
            white-space: nowrap; /* mencegah text wrap */
            margin-left: 20px;
        }

        .nilai-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 13px; /* dinaikkan dari 12px */
        }

        .nilai-table th,
        .nilai-table td {
            border: 1px solid #000;
            padding: 4px 5px; /* sedikit diperbesar padding */
            text-align: center;
            vertical-align: middle;
        }

        .nilai-table th {
            background-color: #e9ecef;
            font-weight: bold;
            font-size: 13px; /* dinaikkan dan konsisten */
        }

        .nilai-table th.header-main {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }

        .nilai-table th.header-kriteria {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .nilai-table th.header-hasil {
            background-color: #27ae60;
            color: white;
        }

        /* Optimasi lebar kolom untuk mengurangi ruang kosong */
        .no-col {
            width: 30px; /* sedikit diperbesar untuk readability */
        }

        .nim-col {
            width: 75px; /* sedikit diperbesar */
        }

        .nama-col {
            width: 140px; /* diperbesar sedikit untuk nama panjang */
            text-align: left !important;
        }

        .kriteria-col {
            width: 45px; /* sedikit diperbesar */
        }

        .hasil-col {
            width: 55px; /* sedikit diperbesar */
        }

        .nilai-cell {
            height: auto;
            font-size: 13px; /* dinaikkan dari 12px */
            padding: 4px 5px;
        }

        .nilai-cell.lulus {
            background-color: #d4edda;
            color: #155724;
            font-weight: bold;
        }

        .nilai-cell.tidak-lulus {
            background-color: #f8d7da;
            color: #721c24;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }

        .signature-section {
            display: inline-block;
            text-align: right;
            margin-left: 50px;
        }

        .signature-line {
            width: 200px;
            border-bottom: 1px solid #000;
            margin: 50px auto 5px auto;
        }

        .summary-info {
            margin-top: 18px;
            font-size: 12px; /* dinaikkan dari 11px */
        }

        .summary-table {
            border-collapse: collapse;
            margin-top: 12px;
        }

        .summary-table td {
            border: 1px solid #ccc;
            padding: 6px 10px;
            background-color: #f9f9f9;
            font-size: 12px; /* dinaikkan dari 11px */
        }

        .summary-table .summary-label {
            font-weight: bold;
            background-color: #e9ecef;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }

            .info-section {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
@php
    $logoPath = public_path('image/logo-uij.png');
    $logoBase64 = file_exists($logoPath) ? 'data:' . mime_content_type($logoPath) . ';base64,' . base64_encode(file_get_contents($logoPath)) : '';
@endphp
<!-- Header dengan Kop Universitas -->
<div class="header" style="width: 100%">
    <div class="header-content">
        <table>
            <tbody>
            <tr>
                <td style="width: 20%">
                    <div class="logo">
                        <img src="{{ $logoBase64 }}" alt="Logo UIJ" style="max-height: 100%">
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
<div class="course-info" style="width: 100%">
    @php
        $dosen_list = explode('#', $mahasiswa[0]->nama_dosen ?? 'Nama Dosen');
        $nidn_list = explode('#', $mahasiswa[0]->nidn_dosen ?? 'Nama Dosen');
    @endphp
    <table>
        <tr>
            <td class="label">Mata Kuliah</td>
            <td>: {{ $mahasiswa[0]->mk ?? 'Mata Kuliah' }}</td>
            <td class="label">Semester</td>
            <td>: {{ $mahasiswa[0]->semester ?? 'Ganjil 2024/2025' }}</td>
        </tr>
        <tr>
            <td class="label">Dosen Pengampu</td>
            <td>:
                @if(count($dosen_list) == 1)
                    {{ trim($dosen_list[0]) }}
                @else
                    @foreach($dosen_list as $i => $dosen)
                        Dosen {{ $i+1 }}: {{ trim($dosen) }}@if($i < count($dosen_list)-1)
                            dan
                        @endif
                    @endforeach
                @endif
            </td>
            <td class="label">Kelas</td>
            <td>: {{ $mahasiswa[0]->nama_kelas ?? 'A' }} - {{ $mahasiswa[0]->program_studi ?? 'Program Studi' }}</td>
        </tr>
        <tr>
            <td class="label">Kode Mata Kuliah</td>
            <td>: {{ $mahasiswa[0]->kd_mk ?? 'MK001' }}</td>
            <td class="label">SKS</td>
            <td>: {{ $mahasiswa[0]->sks ?? '3' }}</td>
        </tr>
    </table>
</div>

<!-- Info section dengan layout yang diperbaiki -->
<div class="info-section" style="width: 100%">
    <em style="float: left">Diunduh berdasarkan data sister.uij.ac.id per
        tanggal {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y H:i:s') }}</em>
    <em style="float: right">Keterangan: Lulus: >= 2.0 | Tidak Lulus: &lt;2.0</em>
</div>

@php
    $daftar_kriteria = explode('#', $mahasiswa[0]->daftar_kriteria_text ?? '');
    $daftar_bobot = explode('#', $mahasiswa[0]->bobot_per_kriteria ?? '');
    $jumlah_kriteria = count($daftar_kriteria);
@endphp
    <!-- Tabel Nilai -->
<table class="nilai-table">
    <thead>
    <tr>
        <th rowspan="2" class="no-col header-main">No</th>
        <th rowspan="2" class="nim-col header-main">NIM</th>
        <th rowspan="2" class="nama-col header-main" style="text-align: center!important;">Nama Mahasiswa</th>

        @if($jumlah_kriteria > 0)
            <th colspan="{{ $jumlah_kriteria }}" class="header-kriteria">Nilai Kriteria</th>
        @endif

        <!-- Hanya 2 kolom hasil -->
        <th colspan="2" class="header-hasil">Hasil Akhir</th>
    </tr>
    <tr>
        @foreach($daftar_kriteria as $index => $kriteria)
            <th class="kriteria-col header-kriteria" title="{{ trim($kriteria) }}">
                {{ substr(trim($kriteria), 0, 8) }}{{ strlen(trim($kriteria)) > 8 ? '...' : '' }}<br>
                <small>({{ $daftar_bobot[$index] ?? 0 }}%)</small>
            </th>
        @endforeach

        <th class="hasil-col header-hasil">Nilai<br>Akhir</th>
        <th class="hasil-col header-hasil">Huruf</th>
    </tr>
    </thead>
    <tbody>
    @forelse($mahasiswa as $index => $mhs)
        @php
            $kriteria_ids = explode('#', $mhs->daftar_kriteria_penilaian_id ?? '');
            $nilai_per_kriteria = isset($mhs->nilai_per_kriteria) ? explode('#', $mhs->nilai_per_kriteria) : [];
            $is_lulus = isset($mhs->nilai_akhir) && $mhs->nilai_akhir >= 2.0;
        @endphp
        <tr>
            <td class="nilai-cell">{{ $index + 1 }}</td>
            <td class="nilai-cell">{{ $mhs->nim }}</td>
            <td class="nama-col nilai-cell" style="text-align: left; padding-left: 4px;">{{ $mhs->nama_mahasiswa }}</td>

            @foreach($kriteria_ids as $idx => $kid)
                <td class="nilai-cell">{{ $nilai_per_kriteria[$idx] ?? '-' }}</td>
            @endforeach

            <td class="nilai-cell {{ $is_lulus ? 'lulus' : 'tidak-lulus' }}">
                {{ $mhs->nilai_akhir ?? '-' }}
            </td>
            <td class="nilai-cell {{ $is_lulus ? 'lulus' : 'tidak-lulus' }}">
                {{ $mhs->nilai_huruf ?? '-' }}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="{{ 3 + $jumlah_kriteria + 2 }}" class="nilai-cell">
                Tidak ada data mahasiswa
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
@php
    $total_mahasiswa = count($mahasiswa);
    $lulus_count = 0;
    $tidak_lulus_count = 0;
    $belum_dinilai_count = 0;

    foreach($mahasiswa as $mhs) {
        if (!isset($mhs->nilai_mutu) || $mhs->nilai_mutu === null || $mhs->nilai_mutu === '') {
            $belum_dinilai_count++;
        } elseif ($mhs->nilai_mutu >= 2.0) {
            $lulus_count++;
        } else {
            $tidak_lulus_count++;
        }
    }
@endphp

    <!-- Summary Information -->
<div class="summary-info">
    <table class="summary-table">
        <tr>
            <td class="summary-label">Total Mahasiswa:</td>
            <td>{{ $total_mahasiswa }}</td>
            <td class="summary-label">Lulus (≥2.0):</td>
            <td style="color: #27ae60; font-weight: bold;">{{ $lulus_count }}</td>
            <td class="summary-label">Tidak Lulus (<2.0):</td>
            <td style="color: #e74c3c; font-weight: bold;">{{ $tidak_lulus_count }}</td>
            <td class="summary-label">Belum Dinilai:</td>
            <td style="color: #f39c12; font-weight: bold;">{{ $belum_dinilai_count }}</td>
        </tr>
    </table>
</div>

<!-- Footer dengan Tanda Tangan -->
<div class="footer">
    <div class="signature-section">
        <p>Jember, {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y') }}</p>
        <p>@if(count($dosen_list) == 2) Koordinator @endif Dosen Pengampu,</p>
        <br/>
        <br/>
        <br/>
        <p><strong>{{ $dosen_list[0] ?? 'Nama Dosen' }}</strong><br/>
            NIDN: {{ $nidn_list[0] ?? '-' }}
        </p>
    </div>
</div>
</body>
</html>
