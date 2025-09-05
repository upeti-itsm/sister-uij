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
            background: #f0f0f0;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #666;
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
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
<!-- Header dengan Kop Universitas -->
<div class="header">
    <div class="header-content">
        <div class="logo">
            LOGO UIJ
        </div>
        <div class="header-text">
            <h1>UNIVERSITAS ISLAM JEMBER</h1>
            <p>Jl. Kyai Mojo No. 101, Kaliwates, Jember, Jawa Timur 68136</p>
            <p>Telp: (0331) 487550 | Email: info@unisma.ac.id | Website: www.unisma.ac.id</p>
        </div>
    </div>
</div>

<!-- Informasi Mata Kuliah -->
<div class="course-info">
    <table>
        <tr>
            <td class="label">Mata Kuliah</td>
            <td>: {{ $matakuliah ?? 'Pemrograman Web Lanjut' }}</td>
            <td class="label">Semester</td>
            <td>: {{ $semester ?? 'Ganjil 2024/2025' }}</td>
        </tr>
        <tr>
            <td class="label">Dosen Pengampu</td>
            <td>: {{ $dosen ?? 'Dr. Ahmad Fadli, M.Kom' }}</td>
            <td class="label">Kelas</td>
            <td>: {{ $kelas ?? 'A - Informatika' }}</td>
        </tr>
        <tr>
            <td class="label">Kode Mata Kuliah</td>
            <td>: {{ $kode_matkul ?? 'INF301' }}</td>
            <td class="label">SKS</td>
            <td>: {{ $sks ?? '3' }}</td>
        </tr>
    </table>
</div>

<!-- Tabel Presensi -->
<table class="attendance-table">
    <thead>
    <tr>
        <th class="no-col">No</th>
        <th class="nim-col">NIM</th>
        <th class="nama-col">Nama Mahasiswa</th>
        @for($i = 1; $i <= 14; $i++)
            <th class="date-col">{{ $i }}</th>
        @endfor
    </tr>
    <tr>
        <th colspan="3">Tanggal Pertemuan</th>
        @php
            $tanggal_mulai = $tanggal_mulai ?? '2024-09-02';
            $start_date = new DateTime($tanggal_mulai);
        @endphp
        @for($i = 0; $i < 14; $i++)
            @php
                $current_date = clone $start_date;
                $current_date->add(new DateInterval('P' . ($i * 7) . 'D'));
            @endphp
            <th class="date-col">{{ $current_date->format('d/m') }}</th>
        @endfor
    </tr>
    </thead>
    <tbody>
    @php
        $mahasiswa_dummy = [
            ['nim' => '220101001', 'nama' => 'Ahmad Rizki Pratama'],
            ['nim' => '220101002', 'nama' => 'Siti Nurhaliza'],
            ['nim' => '220101003', 'nama' => 'Budi Santoso'],
            ['nim' => '220101004', 'nama' => 'Dewi Sartika'],
            ['nim' => '220101005', 'nama' => 'Eko Prasetyo'],
            ['nim' => '220101006', 'nama' => 'Fatimah Azzahra'],
            ['nim' => '220101007', 'nama' => 'Gilang Ramadhan'],
            ['nim' => '220101008', 'nama' => 'Hani Safitri'],
            ['nim' => '220101009', 'nama' => 'Indra Kusuma'],
            ['nim' => '220101010', 'nama' => 'Joko Widodo'],
        ];

        $mahasiswa_list = $mahasiswa ?? $mahasiswa_dummy;
    @endphp

    @foreach($mahasiswa_list as $index => $mhs)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $mhs['nim'] }}</td>
            <td class="nama-col">{{ $mhs['nama'] }}</td>
            @for($i = 1; $i <= 14; $i++)
                <td class="attendance-cell"></td>
            @endfor
        </tr>
    @endforeach

    <!-- Tambahan baris kosong untuk mahasiswa tambahan -->
    @for($i = count($mahasiswa_list); $i < 30; $i++)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td class="attendance-cell"></td>
            <td class="attendance-cell nama-col"></td>
            @for($j = 1; $j <= 14; $j++)
                <td class="attendance-cell"></td>
            @endfor
        </tr>
    @endfor
    </tbody>
</table>

<!-- Footer dengan Tanda Tangan -->
<div class="footer">
    <div class="signature-section">
        <p>Jember, {{ date('d F Y') }}</p>
        <p>Dosen Pengampu,</p>
        <div class="signature-line"></div>
        <p><strong>{{ $dosen ?? 'Dr. Ahmad Fadli, M.Kom' }}</strong></p>
        <p>NIDN: {{ $nidn ?? '0123456789' }}</p>
    </div>
</div>
</body>
</html>
