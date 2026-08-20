<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Cuti Akademik</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 20mm 20mm 20mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 100%;
        }

        .page-break {
            page-break-after: always;
        }

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

        .title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 4px;
        }

        .subtitle {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .info-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .info-table .label {
            width: 150px;
        }

        .info-table .sep {
            width: 10px;
            text-align: center;
        }

        .body-text {
            text-align: justify;
            margin-bottom: 14px;
            line-height: 1.6;
        }

        .sign-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
        }

        .sign-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .sign-name {
            margin-top: 4px;
            font-weight: bold;
            text-decoration: underline;
        }

        .qr-ttd {
            width: 73px;
            height: 73px;
            display: block;
            margin: 8px 0 4px 0;
        }

        .sign-nidn {
            margin-top: 4px;
        }

        .tembusan {
            margin-top: 30px;
            font-size: 10pt;
        }

        .tembusan ol {
            margin: 4px 0 0 0;
            padding-left: 20px;
        }

        /* Halaman 2 */
        .hal-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .hal-table td {
            padding: 1px 0;
            vertical-align: top;
        }

        .hal-table .hal-label {
            width: 40px;
        }

        .hal-table .hal-sep {
            width: 10px;
        }

        .alasan-list {
            margin: 8px 0 16px 0;
            padding-left: 24px;
            line-height: 1.8;
        }

        .salam {
            margin-bottom: 8px;
        }
    </style>
</head>

<body>
    <!-- Page 1: Surat Keterangan Cuti Akademik -->
    <div class="page page-break">
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

        <div class="title">SURAT KETERANGAN CUTI AKADEMIK</div>
        <div class="subtitle">NOMOR : {{ $pengajuan->nomor_pengajuan ?? '……………..' }}</div>

        <div class="body-text">
            Dekan {{ $mahasiswa->nama_fakultas ?? '………' }} Universitas Islam Jember, setelah membaca surat
            permohonan Cuti Akademik dengan persetujuan Dosen Wali yang bersangkutan, maka Mahasiswa tersebut di
            bawah ini :
        </div>

        <table class="info-table">
            <tr>
                <td class="label">Nama</td>
                <td class="sep">:</td>
                <td>{{ $mahasiswa->nama_mahasiswa ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">N I M</td>
                <td class="sep">:</td>
                <td>{{ $mahasiswa->nim ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Program Studi</td>
                <td class="sep">:</td>
                <td>{{ $mahasiswa->nama_prodi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat Lengkap</td>
                <td class="sep">:</td>
                <td>{{ $mahasiswa->alamat_lengkap ?? '-' }}</td>
            </tr>
        </table>

        <div class="body-text">
            Dapat diberikan izin Cuti Akademik pada tahun semester {{ $mahasiswa->tahun_akademik ?? '…../…….' }}.
            Surat keterangan Cuti Akademik ini, diberikan kepada mahasiswa yang bersangkutan untuk
            dapat dipergunakan pada waktu registrasi sebagai salah satu tanda bukti.
        </div>

        <table class="sign-table">
            <tr>
                <td></td>
                <td>
                    Jember, {{ $tanggal_cetak }}<br>
                    Dekan,<br>
                    @if (!empty($qr_code_dekan))
                        <img src="data:image/svg+xml;base64,{{ $qr_code_dekan }}" class="qr-ttd">
                    @else
                        <div style="height:55px;"></div>
                    @endif
                    <div class="sign-name">{{ $pengajuan->nama_dekan ?? '-' }}</div>
                    <div class="sign-nidn">NIDN. {{ $pengajuan->nidn_dekan ?? '-' }}</div>
                </td>
            </tr>
        </table>

        <div class="tembusan">
            Tembusan:
            <ol>
                <li>Kabag Akademik</li>
                <li>Kabag Keuangan</li>
                <li>Ketua Program Studi</li>
                <li>Dosen Wali</li>
            </ol>
        </div>
    </div>

    <!-- Page 2: Permohonan Cuti Akademik -->
    <div class="page">
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

        <table class="hal-table">
            <tr>
                <td class="hal-label">Hal</td>
                <td class="hal-sep">:</td>
                <td>Permohonan Cuti Akademik</td>
            </tr>
        </table>

        <div class="body-text">
            Kepada Yth<br>
            Bapak / Ibu Dekan {{ $mahasiswa->nama_fakultas ?? '___________________' }}<br>
            Universitas Islam Jember<br>
            Di<br>
            Jember
        </div>

        <div class="body-text salam">Assalamu'alaikum Wr. Wb.</div>

        <div class="body-text">
            Dengan hormat, yang bertanda tangan di bawah ini
        </div>

        <table class="info-table">
            <tr>
                <td class="label">Nama</td>
                <td class="sep">:</td>
                <td>{{ $mahasiswa->nama_mahasiswa ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">N I M</td>
                <td class="sep">:</td>
                <td>{{ $mahasiswa->nim ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Prodi</td>
                <td class="sep">:</td>
                <td>{{ $mahasiswa->nama_prodi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="sep">:</td>
                <td>{{ $mahasiswa->alamat_lengkap ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Telpon/HP</td>
                <td class="sep">:</td>
                <td>{{ $mahasiswa->no_hp ?? '-' }}</td>
            </tr>
        </table>

        <div class="body-text">
            Bermaksud mengajukan permohonan Cuti Akademik pada tahun Semester
            {{ $mahasiswa->tahun_akademik ?? '……… /…….' }}.
        </div>

        <div class="body-text">
            Permohonan Cuti Akademik ini saya sampaikan karena :
        </div>

        <div class="body-text">
            {{ $mahasiswa->keperluan ?? '-' }}
        </div>

        <div class="body-text">
            Demikian permohonan Cuti Akademik ini saya sampaikan, atas perhatian dan
            dikabulkannya permohonan ini saya ucapkan terima kasih.
        </div>

        <div class="body-text">Wassalamu'alaikum Wr. Wb.</div>

        <table class="sign-table">
            <tr>
                <td>
                    Mengetahui<br>
                    Dosen Penasihat Akademik<br>
                    @if (!empty($qr_code_dpa))
                        <img src="data:image/svg+xml;base64,{{ $qr_code_dpa }}" class="qr-ttd">
                    @else
                        <div style="height:55px;"></div>
                    @endif
                    <div class="sign-name">{{ $pengajuan->nama_dpa ?? '-' }}</div>
                    <div class="sign-nidn">NIDN. {{ $pengajuan->nidn_dpa ?? '-' }}</div>
                </td>
                <td>
                    Jember, {{ $tanggal_cetak }}<br>
                    Hormat Saya,<br>
                    @if (!empty($qr_code_mahasiswa))
                        <img src="data:image/svg+xml;base64,{{ $qr_code_mahasiswa }}" class="qr-ttd">
                    @else
                        <div style="height:55px;"></div>
                    @endif
                    <div class="sign-name">{{ $mahasiswa->nama_mahasiswa ?? '-' }}</div>
                </td>
            </tr>
        </table>

        <div class="tembusan">
            Tembusan disampaikan pada:
            <ol>
                <li>Kabag Akademik</li>
                <li>Kabag Keuangan</li>
            </ol>
        </div>
    </div>
</body>

</html>
