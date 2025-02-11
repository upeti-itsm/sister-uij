<style>
    tr > td {
        border: 1px solid #000000;
    }
</style>
@if(count($pendaftar) > 0)
    <table>
        <thead>
        <tr>
            <th colspan="11" style="text-align: center; border: 1px solid #000000;">Daftar Pendaftar WISUDA</th>
        </tr>
        <tr>
            <th colspan="11" style="text-align: center; border: 1px solid #000000;"><small style="font-size: 8pt"><i>Diunduh
                        Pada Tanggal
                        : {{date('d F Y H:i:s', strtotime(now()->timezone('Asia/Jakarta')))}}</i></small></th>
        </tr>
        <tr>
            <td colspan="11" style="text-align: center; border: 1px solid #000000;"></td>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                NO
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                NAMA
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                NIM
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                PRODI
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                KELAS
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                JUDUL SKRIPSI
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                IPK
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                KOTA
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                TEMPAT LAHIR
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                TANGGAL LAHIR
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                KESAN DAN PESAN
            </th>
        </tr>
        </thead>
        <tbody>
            <?php $no = 0; ?>
        @foreach($pendaftar as $item)
                <?php $no++; ?>
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{$no}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{strtoupper($item->nama_mahasiswa)}}</td>
                <td style="text-align: left; border: 1px solid #000000;">{{$item->nim}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{strtoupper($item->nama_prodi)}}</td>
                @if($item->jenis_kelas_siakad == 'REG')
                    <td style="text-align: center; border: 1px solid #000000;">REGULER PAGI</td>
                @elseif($item->jenis_kelas_siakad == 'TRF')
                    <td style="text-align: center; border: 1px solid #000000;">TRANSFER PAGI</td>
                @elseif($item->jenis_kelas_siakad == 'REGM')
                    <td style="text-align: center; border: 1px solid #000000;">REGULER MALAM</td>
                @elseif($item->jenis_kelas_siakad == 'TRFM')
                    <td style="text-align: center; border: 1px solid #000000;">TRANSFER MALAM</td>
                @else
                    <td style="text-align: center; border: 1px solid #000000;">UNDEFINED</td>
                @endif
                <td style="text-align: center; border: 1px solid #000000;" align="center">
                    {{$item->judul_skripsi}}
                </td>
                <td style="text-align: center; border: 1px solid #000000;" align="center">
                    {{$item->ipk}}
                </td>
                <td style="text-align: center; border: 1px solid #000000;" align="center">
                    {{strtoupper($item->kota)}}
                </td>
                <td style="text-align: center; border: 1px solid #000000;" align="center">
                    {{strtoupper($item->tempat_lahir)}}
                </td>
                <td style="text-align: center; border: 1px solid #000000;" align="center">
                    {{strtoupper($item->tanggal_lahir)}}
                </td>
                <td style="text-align: left; border: 1px solid #000000;" align="center">
                    {{$item->kesan_pesan}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <table>
        <thead>
        <tr>
            <th colspan="11" style="text-align: center">Daftar Pendaftar WISUDA
            </th>
        </tr>
        <tr>
            <th colspan="11" style="text-align: center"><small style="font-size: 8pt"><i>Diunduh Pada Tanggal
                        : {{date('d F Y H:i:s', strtotime(now()->timezone('Asia/Jakarta')))}}</i></small></th>
        </tr>
        <tr></tr>
        <tr>
            <th style="text-align: center; font-weight: bold">NO</th>
            <th style="text-align: center; font-weight: bold">NAMA</th>
            <th style="text-align: center; font-weight: bold">NIM</th>
            <th style="text-align: center; font-weight: bold">PRODI</th>
            <th style="text-align: center; font-weight: bold">KELAS</th>
            <th style="text-align: center; font-weight: bold">JUDUL SKRIPSI</th>
            <th style="text-align: center; font-weight: bold">IPK</th>
            <th style="text-align: center; font-weight: bold">KOTA</th>
            <th style="text-align: center; font-weight: bold">TEMPAT LAHIR</th>
            <th style="text-align: center; font-weight: bold">TANGGAL LAHIR</th>
            <th style="text-align: center; font-weight: bold">KESAN DAN PESAN</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="11" style="font-weight: bold; text-align: center"><b>TIDAK ADA DATA</b></td>
        </tr>
        </tbody>
    </table>
@endif
