<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Jurnal Mengajar Dosen</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* @page set 0 — margin dikelola via div.page agar reliable di semua versi DomPDF */
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

        /* WRAPPER — margin dokumen yang sesungguhnya */
        div.page {
            margin: 10mm 20mm 8mm 20mm;
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

        /* SECTION TITLE */
        .stitle {
            font-size: 8.5pt;
            font-weight: bold;
            margin-bottom: 3px;
        }

        /* TABEL JURNAL */
        table.tj {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            margin-bottom: 6px;
        }

        table.tj thead tr {
            background-color: #2c3e50;
            color: #fff;
        }

        table.tj th {
            border: 1px solid #000;
            padding: 3px 2px;
            text-align: center;
            font-weight: bold;
        }

        table.tj td {
            border: 1px solid #666;
            padding: 2px 3px;
            vertical-align: middle;
        }

        table.tj tbody tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        .tc {
            text-align: center;
        }

        /* RINGKASAN — 1 kolom sesuai referensi */
        table.rks {
            width: 55%;
            border-collapse: collapse;
            font-size: 8.5pt;
            border: 1px solid #000;
            margin-bottom: 6px;
        }

        table.rks td {
            border: 1px solid #000;
            padding: 1px 5px;
        }

        table.rks .rl {
            width: 170px;
        }

        table.rks .rs {
            width: 8px;
            text-align: center;
        }

        /* TTD */
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
        <div class="judul">JURNAL MENGAJAR DOSEN</div>

        {{-- META --}}
        <table class="meta">
            <tr>
                <td>Tahun Akademik &nbsp;: &nbsp;<strong>{{ $tahun_akademik ?? '-' }}</strong></td>
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
                            <td style="border:none;padding:1px 0;font-weight:bold;">{{ $dosen->nama_dosen ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border:none;padding:1px 0;">NIDN</td>
                            <td style="border:none;padding:1px 0;">:</td>
                            <td style="border:none;padding:1px 0;">{{ $dosen->nidn ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border:none;padding:1px 0;">Mata Kuliah</td>
                            <td style="border:none;padding:1px 0;">:</td>
                            <td style="border:none;padding:1px 0;">{{ $nama_matakuliah ?? '-' }}</td>
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
                            <td style="border:none;padding:1px 0;">Waktu Mengajar</td>
                            <td style="border:none;padding:1px 0;">:</td>
                            <td style="border:none;padding:1px 0;">{{ $waktu_mengajar ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border:none;padding:1px 0;">Jml Mahasiswa Terdaftar</td>
                            <td style="border:none;padding:1px 0;">:</td>
                            <td style="border:none;padding:1px 0;">{{ $jml_total_mhs_kelas ?? ($jml_reg_p ?? 0) }}
                                Mahasiswa</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- TABEL JURNAL --}}
        <div class="stitle">DAFTAR JURNAL MENGAJAR</div>
        <table class="tj">
            <thead>
                <tr>
                    <th style="width:4%">No</th>
                    <th style="width:10%">Tanggal</th>
                    <th style="width:8%">Pertemuan</th>
                    <th style="width:48%">Pokok Bahasan / Materi</th>
                    <th style="width:8%">Hadir</th>
                    <th style="width:7%">Izin</th>
                    <th style="width:7%">Sakit</th>
                    <th style="width:8%">Tidak<br>Hadir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jurnal as $row)
                    @php
                        $hadir = (int) ($row->jml_mhs_hadir ?? ($row->total_mhs_presensi ?? 0));
                        $izin = (int) ($row->jml_mhs_izin ?? 0);
                        $sakit = (int) ($row->jml_mhs_sakit ?? 0);
                        $tdk_hadir = (int) ($row->jml_mhs_tidak_hadir ?? 0);
                    @endphp
                    <tr>
                        <td class="tc">{{ $row->nomor }}</td>
                        <td class="tc" style="font-size:7.5pt;">
                            {{ \Carbon\Carbon::parse($row->tgl_pelaksanaan)->format('d/m/Y') }}</td>
                        <td class="tc">{{ $row->pertemuan_ke ?? '-' }}</td>
                        <td>{{ $row->materi ?? '-' }}</td>
                        <td class="tc">{{ $hadir }}</td>
                        <td class="tc">{{ $izin }}</td>
                        <td class="tc">{{ $sakit }}</td>
                        <td class="tc">{{ $tdk_hadir }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="tc" style="padding:5px;">Tidak ada data jurnal mengajar</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- RINGKASAN — 1 kolom sesuai referensi --}}
        <div class="stitle">RINGKASAN</div>
        <table class="rks">
            <tr>
                <td class="rl">Jumlah Pertemuan</td>
                <td class="rs">:</td>
                <td>{{ $jml_pertemuan }}</td>
            </tr>
            <tr>
                <td class="rl">Pertemuan Terlaksana</td>
                <td class="rs">:</td>
                <td>{{ $jml_terlaksana }}</td>
            </tr>
            <tr>
                <td class="rl">Absensi Tepat Waktu</td>
                <td class="rs">:</td>
                <td>{{ $jml_tepat_waktu }} Kali</td>
            </tr>
            <tr>
                <td class="rl">Absensi Terlambat</td>
                <td class="rs">:</td>
                <td>{{ $jml_terlambat }} Kali</td>
            </tr>
            <tr>
                <td class="rl">Rata-rata Kehadiran Mhs</td>
                <td class="rs">:</td>
                <td>{{ $rata_kehadiran_mhs }}</td>
            </tr>
        </table>

        {{-- TANGGAL --}}
        <div style="font-size:8.5pt;margin-bottom:3px;">Jember, {{ $tanggal_cetak }}</div>

        {{-- TANDA TANGAN --}}
        <table class="ttd">
            <tr>
                <td style="width:45%; text-align:center;">
                    <div style="font-weight:bold;font-size:8.5pt;">Mengetahui,</div>
                    <div style="font-size:8pt;margin-bottom:3px;">Ketua Program Studi</div>
                    @if (!empty($qr_kaprodi))
                        <img src="data:image/svg+xml;base64,{{ $qr_kaprodi }}"
                            style="width:65px;height:65px;margin:3px auto;display:block;border:1px solid #ddd;"
                            alt="QR Kaprodi">
                    @elseif(!empty($qr_error_kaprodi))
                        <div style="height:60px;font-size:5pt;color:red;word-break:break-all;">ERR:
                            {{ $qr_error_kaprodi }}</div>
                    @else
                        <div style="height:60px;"></div>
                    @endif
                    <div style="font-weight:bold;text-decoration:underline;font-size:8.5pt;margin-top:3px;">
                        {{ !empty($nama_kaprodi) ? $nama_kaprodi : '___________________________' }}
                    </div>
                    @if (!empty($nidn_kaprodi))
                        <div style="font-size:7pt;color:#444;">NIDN. {{ $nidn_kaprodi }}</div>
                    @endif
                </td>

                <td style="width:10%;"></td>

                <td style="width:45%; text-align:center;">
                    <div style="font-weight:bold;font-size:8.5pt;">Dosen Pengampu</div>
                    <div style="font-size:8pt;margin-bottom:3px;">&nbsp;</div>
                    @if (!empty($qr_dosen))
                        <img src="data:image/svg+xml;base64,{{ $qr_dosen }}"
                            style="width:65px;height:65px;margin:3px auto;display:block;border:1px solid #ddd;"
                            alt="QR Dosen">
                    @elseif(!empty($qr_error_dosen))
                        <div style="height:60px;font-size:5pt;color:red;word-break:break-all;">ERR:
                            {{ $qr_error_dosen }}</div>
                    @else
                        <div style="height:60px;"></div>
                    @endif
                    <div style="font-weight:bold;text-decoration:underline;font-size:8.5pt;margin-top:3px;">
                        {{ $nama_dosen ?? '___________________________' }}
                    </div>
                    @if (!empty($nidn_dosen))
                        <div style="font-size:7pt;color:#444;">NIDN. {{ $nidn_dosen }}</div>
                    @endif
                </td>
            </tr>
        </table>

        {{-- FOOTER --}}
        <div style="border-top:1px solid #aaa;margin-top:5px;padding-top:3px;">
            <table style="width:100%;border-collapse:collapse;font-size:6.5pt;color:#555;">
                <tr>
                    <td style="text-align:left;border:none;">Dicetak pada {{ $tanggal_cetak }}</td>
                    <td style="text-align:center;border:none;">Jurnal Mengajar Dosen - Sistem Informasi Akademik UIJ
                    </td>
                    <td style="text-align:right;border:none;">Dokumen sah dilindungi QR Code digital signature</td>
                </tr>
            </table>
        </div>

    </div>{{-- end .page --}}
</body>

</html>
