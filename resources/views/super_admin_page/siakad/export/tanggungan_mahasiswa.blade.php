<style>
    tr > td {
        border: 1px solid #000000;
    }
</style>
@if(count($mahasiswa) > 0)
    <table>
        <thead>
        <tr>
            <th colspan="6" style="text-align: center; border: 1px solid #000000;">Daftar Tanggungan Mahasiswa
                STIE-Mandala
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center; border: 1px solid #000000;"><small style="font-size: 8pt"><i>Diunduh
                        Pada Tanggal
                        : {{date('d F Y H:i:s', strtotime(now()->timezone('Asia/Jakarta')))}}</i></small></th>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center; border: 1px solid #000000;"></td>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">NO
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                NAMA
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                NIM
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                ANGKATAN
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                JENIS KELAS
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                BESAR TANGGUNGAN (Rp)
            </th>
        </tr>
        </thead>
        <tbody>
        <?php $no = 0; ?>
        @foreach($mahasiswa as $item)
            <?php $no++; ?>
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{$no}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->NPK}}</td>
                <td style="text-align: left; border: 1px solid #000000;">{{$item->nama_lengkap}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->angkatan}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->jenis_kelas}}</td>
                <td style="text-align: left; border: 1px solid #000000;">Rp. {{number_format($item->sisa_tanggungan,2,',','.')}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <table>
        <thead>
        <tr>
            <th colspan="6" style="text-align: center">Daftar Tanggungan Mahasiswa
                STIE-Mandala</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center"><small style="font-size: 8pt"><i>Diunduh Pada Tanggal
                        : {{date('d F Y H:i:s', strtotime(now()->timezone('Asia/Jakarta')))}}</i></small></th>
        </tr>
        <tr></tr>
        <tr>
            <th style="text-align: center; font-weight: bold">NO</th>
            <th style="text-align: center; font-weight: bold">NAMA</th>
            <th style="text-align: center; font-weight: bold">NIM</th>
            <th style="text-align: center; font-weight: bold">ANGKATAN</th>
            <th style="text-align: center; font-weight: bold">JENIS KELAS</th>
            <th style="text-align: center; font-weight: bold">SISA TANGGUNGAN (Rp)</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="6" style="font-weight: bold; text-align: center"><b>TIDAK ADA DATA</b></td>
        </tr>
        </tbody>
    </table>
@endif
