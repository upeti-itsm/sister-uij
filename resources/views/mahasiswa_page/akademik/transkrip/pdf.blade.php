<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Transkrip - {{ $mahasiswa->nim }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15px 25px 20px 25px;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 8pt;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* ===== KOP ===== */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }
        .kop-table td {
            vertical-align: middle;
            padding: 0;
        }
        .kop-logo-col {
            width: 84px;
        }
        .kop-spacer-col {
            width: 84px;
        }
        .kop-center {
            text-align: center;
        }
        .kop-yayasan {
            font-size: 11pt;
            font-weight: bold;
            line-height: 1.3;
        }
        .kop-univ {
            font-size: 20pt;
            font-weight: bold;
            line-height: 1.1;
        }
        .kop-alamat {
            font-size: 7.5pt;
            line-height: 1.5;
            margin-top: 2px;
        }

        hr.garis-kop {
            border: none;
            border-top: 1.5px solid #000;
            margin: 5px 0 5px 0;
        }

        /* ===== JUDUL ===== */
        .judul {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 3px;
            margin: 6px 0 6px 0;
        }

        /* ===== INFO MAHASISWA ===== */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            font-size: 7.5pt;
            padding: 1px 0;
            vertical-align: top;
        }

        /* ===== TABEL NILAI ===== */
        /*
         * Proporsi kolom dihitung dari HTML referensi (total 100%):
         * Kolom KIRI: NO=3%, MK=30%, SKS=3%, HURUF=5%, ANGKA=4%, NxK=5% => 50%
         * Kolom KANAN: MK=32%, SKS=5%, HURUF=5%, ANGKA=4%, NxK=4%      => 50%
         */
        .nilai-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            font-size: 6.5pt;
        }
        .nilai-table th,
        .nilai-table td {
            border: 0.5px solid #000;
            padding: 1px 2px;
            vertical-align: middle;
        }
        .nilai-table th {
            text-align: center;
            font-weight: bold;
            font-size: 6pt;
        }
        .tc { text-align: center; }
        .bold { font-weight: bold; }

        /* ===== REKAP ===== */
        .rekap-wrap {
            /* Rekap berada di bagian kanan-tengah, sesuai referensi left~25.91 dari 51em */
            /* 25.91/51 ≈ 50.8% dari kiri */
            padding-left: 50%;
            margin-top: 4px;
            margin-bottom: 14px;
        }
        .rekap-table {
            border-collapse: collapse;
        }
        .rekap-table td {
            font-size: 7.5pt;
            padding: 1px 2px 1px 0;
            vertical-align: top;
        }

        /* ===== TTD ===== */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
            table-layout: fixed;
        }
        .ttd-table td {
            font-size: 7.5pt;
            vertical-align: top;
            width: 50%;
            padding: 0 12px;
        }
        .ttd-head {
            display: block;
            min-height: 32px;
        }
        .ttd-head .ttd-line {
            margin-bottom: 1px;
        }
        .ttd-line {
            display: block;
            line-height: 1.25;
        }
        .ttd-qr-wrap {
            display: block;
            height: 68px;
            margin: 2px 0 5px 0;
            overflow: hidden;
        }
        .qr-sign {
            width: 66px;
            height: 66px;
            display: block;
            margin: 0;
        }
        .ttd-empty {
            display: block;
            height: 66px;
        }
        .ttd-name {
            display: block;
            font-size: 8.5pt;
            font-weight: bold;
            text-decoration: underline;
            line-height: 1.2;
            margin-top: 1px;
        }
        .ttd-nidn {
            display: block;
            margin-top: 2px;
        }
        .ttd-date-wrap {
            width: 50%;
            margin-left: 50%;
            text-align: left;
            font-size: 7.5pt;
            line-height: 1.2;
            padding-left: 12px;
            margin-top: 2px;
            margin-bottom: 2px;
        }
    </style>
</head>
<body>

{{-- ==================== KOP ==================== --}}
<table class="kop-table">
    <tr>
        <td class="kop-logo-col">
            @if($logo)
                <img src="data:image/png;base64,{{ $logo }}" style="width:68px; height:auto; display:block;">
            @endif
        </td>
        <td class="kop-center">
            <div class="kop-yayasan">YAYASAN PENDIDIKAN NAHDLATUL ULAMA JEMBER</div>
            <div class="kop-univ">UNIVERSITAS ISLAM JEMBER</div>
            <div class="kop-alamat">
                Jl. Kyai Mojo 101<br>
                Telp. (0331) 488675 Fax. (0331) 428732<br>
                Jember (68133)
            </div>
        </td>
        <td class="kop-spacer-col"></td>
    </tr>
</table>

<hr class="garis-kop">

{{-- ==================== JUDUL ==================== --}}
<div class="judul">TRANSKRIP</div>

{{-- ==================== INFO MAHASISWA ==================== --}}
<table class="info-table">
    <tr>
        <td style="width:18%;">FAKULTAS</td>
        <td style="width:1%;">:</td>
        <td style="width:43%;">{{ strtoupper($mahasiswa->kd_fakultas   ?? '-') }}</td>
        <td style="width:13%;">NIM</td>
        <td style="width:1%;">:</td>
        <td>{{ $mahasiswa->nim }}</td>
    </tr>
    <tr>
        <td>PROGRAM STUDI</td>
        <td>:</td>
        <td>{{ strtoupper($mahasiswa->nama_prodi ?? '-') }}</td>
        <td>TAHUN MASUK</td>
        <td>:</td>
        <td>{{ $mahasiswa->angkatan }}</td>
    </tr>
    <tr>
        <td>NAMA</td>
        <td>:</td>
        <td colspan="4" class="text-capitalize">{{ strtoupper($mahasiswa->nama_mahasiswa ?? '-') }}</td>
    </tr>
    <tr>
        <td>TEMPAT, TANGGAL LAHIR</td>
        <td>:</td>
        <td colspan="4">{{ strtoupper($mahasiswa->ttl ?? '-') }}</td>
    </tr>
</table>

{{-- ==================== TABEL NILAI ==================== --}}
@php
    // Flatten semua MK dari semua semester
    $semuaMK = [];
    foreach ($nilai_per_semester as $sem) {
        foreach ($sem['mata_kuliah'] as $mk) {
            $semuaMK[] = $mk;
        }
    }
    $totalMK  = count($semuaMK);
    // Bagi dua: kiri dan kanan
    $setengah = (int) ceil($totalMK / 2);
    $kiri     = array_slice($semuaMK, 0, $setengah);
    $kanan    = array_slice($semuaMK, $setengah);
    $maxBaris = max(count($kiri), count($kanan));

    // Sub-total per sisi
    $totalSKS_kiri  = array_sum(array_map(fn($m) => $m['sks'], $kiri));
    $totalSKS_kanan = array_sum(array_map(fn($m) => $m['sks'], $kanan));
    $totalNxK_kiri  = array_sum(array_map(fn($m) => $m['sks'] * $m['bobot'], $kiri));
    $totalNxK_kanan = array_sum(array_map(fn($m) => $m['sks'] * $m['bobot'], $kanan));
@endphp

<table class="nilai-table">
    <thead>
        <tr>
            {{-- ===== Header kiri ===== --}}
            <th rowspan="2" style="width:3%;">NO</th>
            <th rowspan="2" style="width:30%;">MATAKULIAH</th>
            <th rowspan="2" style="width:3%;">SKS</th>
            <th colspan="2" style="width:9%;">NILAI</th>
            <th rowspan="2" style="width:5%;">N x K</th>
            {{-- ===== Header kanan ===== --}}
            <th rowspan="2" style="width:32%;">MATAKULIAH</th>
            <th rowspan="2" style="width:5%;">SKS</th>
            <th colspan="2" style="width:9%;">NILAI</th>
            <th rowspan="2" style="width:4%;">N x K</th>
        </tr>
        <tr>
            <th style="width:4.5%;">HURUF</th>
            <th style="width:4.5%;">ANGKA</th>
            <th style="width:4.5%;">HURUF</th>
            <th style="width:4.5%;">ANGKA</th>
        </tr>
    </thead>
    <tbody>
        @for ($i = 0; $i < $maxBaris; $i++)
            @php
                $mkK = $kiri[$i]  ?? null;
                $mkR = $kanan[$i] ?? null;
                $noK = $i + 1;
                $noR = $setengah + $i + 1;
            @endphp
            <tr>
                {{-- Baris kiri --}}
                @if ($mkK)
                    <td class="tc">{{ $noK }}</td>
                    <td>{{ $mkK['nama_matakuliah'] }}</td>
                    <td class="tc">{{ $mkK['sks'] }}</td>
                    <td class="tc">{{ $mkK['nilai_huruf'] }}</td>
                    <td class="tc">{{ number_format($mkK['bobot'], 2) }}</td>
                    <td class="tc">{{ number_format($mkK['sks'] * $mkK['bobot'], 2) }}</td>
                @else
                    <td></td><td></td><td></td><td></td><td></td><td></td>
                @endif
                {{-- Baris kanan --}}
                @if ($mkR)
                    <td>{{ $noR }} {{ $mkR['nama_mata_kuliah'] }}</td>
                    <td class="tc">{{ $mkR['sks'] }}</td>
                    <td class="tc">{{ $mkR['nilai_huruf'] }}</td>
                    <td class="tc">{{ number_format($mkR['bobot'], 2) }}</td>
                    <td class="tc">{{ number_format($mkR['sks'] * $mkR['bobot'], 2) }}</td>
                @else
                    <td></td><td></td><td></td><td></td><td></td>
                @endif
            </tr>
        @endfor

        {{-- Baris JUMLAH --}}
        <tr>
            <td colspan="2" class="tc bold">JUMLAH</td>
            <td class="tc bold">{{ $totalSKS_kiri }}</td>
            <td></td>
            <td></td>
            <td class="tc bold">{{ number_format($totalNxK_kiri, 1) }}</td>

            <td class="tc bold">JUMLAH</td>
            <td class="tc bold">{{ $totalSKS_kanan }}</td>
            <td></td>
            <td></td>
            <td class="tc bold">{{ number_format($totalNxK_kanan, 1) }}</td>
        </tr>
    </tbody>
</table>

{{-- ==================== REKAP ==================== --}}
{{-- Posisi rekap di referensi: left=25.91 dari 51em total = ~50.8% dari kiri --}}
<div class="rekap-wrap">
    <table class="rekap-table">
        <tr>
            <td style="width:90px;">Jumlah SKS</td>
            <td style="width:8px;">:</td>
            <td>{{ $total_sks }}</td>
        </tr>
        <tr>
            <td>Jumlah N x K</td>
            <td>:</td>
            <td>{{ number_format($total_sks * $ipk, 1) }}</td>
        </tr>
        <tr>
            <td>IP Kumulatif</td>
            <td>:</td>
            <td>{{ $ipk }}</td>
        </tr>
    </table>
</div>

{{-- ==================== TTD ==================== --}}
{{--
  Referensi:
  - Dekan kiri  : top=52.65 (teks "Dekan,"), nama top=57.15, NIDN top=58.05
  - Kaprodi kanan: top=51.75 (tanggal), top=52.65 ("Ketua Program Studi,"),
                   nama top=57.15, NIDN top=58.05
  Jarak tanda tangan = 57.15 - 52.65 = 4.5em ≈ 4-5 baris ≈ spasi tanda tangan
--}}
@php
    $qrTtdDekan = $qr_code_dekan ?? ($qr_code ?? null);
    $qrTtdKaprodi = $qr_code_prodi ?? ($qr_code ?? null);
@endphp
<div class="ttd-date-wrap">Jember, {{ $tanggal_cetak }}</div>
<table class="ttd-table">
    <tr>
        <td style="text-align:left;">
            <span class="ttd-head">
                <span class="ttd-line">Dekan,</span>
                <span class="ttd-line">&nbsp;</span>
            </span>
            <div class="ttd-qr-wrap">
                @if(!empty($qrTtdDekan))
                    <img class="qr-sign" src="data:image/svg+xml;base64,{{ $qrTtdDekan }}" alt="QR TTD Dekan">
                @else
                    <span class="ttd-empty"></span>
                @endif
            </div>
            <span class="ttd-name">{{ strtoupper($pengajuan->nama_dekan ?? '-') }}</span>
            <span class="ttd-nidn">NIDN. {{ $pengajuan->nidn_dekan ?? '-' }}</span>
        </td>
        <td style="text-align:left;">
            <span class="ttd-head">
                <span class="ttd-line">Ketua Program Studi,</span>
                <span class="ttd-line">&nbsp;</span>
            </span>
            <div class="ttd-qr-wrap">
                @if(!empty($qrTtdKaprodi))
                    <img class="qr-sign" src="data:image/svg+xml;base64,{{ $qrTtdKaprodi }}" alt="QR TTD Kaprodi">
                @else
                    <span class="ttd-empty"></span>
                @endif
            </div>
            <span class="ttd-name">{{ strtoupper($pengajuan->nama_kaprodi ?? '-') }}</span>
            <span class="ttd-nidn">NIDN. {{ $pengajuan->nidn_kaprodi ?? '-' }}</span>
        </td>
    </tr>
</table>

</body>
</html>
