<style>
    tr > td {
        border: 1px solid #000000;
    }
</style>
@if(count($rekap) > 0)
    <table>
        <thead>
        <tr>
            <th colspan="8" style="text-align: center; border: 1px solid #000000;">REKAP ABSENSI DOSEN
            </th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center; border: 1px solid #000000;"><b>{{date('d F Y', strtotime($tgl_awal))}}</b>&nbsp;&nbsp;s/d&nbsp;&nbsp;<b>{{date('d F Y', strtotime($tgl_akhir))}}</b>
            </th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center; border: 1px solid #000000;"><small style="font-size: 8pt"><i>Diunduh
                        Pada Tanggal
                        : {{date('d F Y H:i:s', strtotime(now()->timezone('Asia/Jakarta')))}}</i></small></th>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center; border: 1px solid #000000;"></td>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">NO
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                TANGGAL ABSENSI
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                WAKTU MENGAJAR
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                NAMA DOSEN
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                NAMA MATAKULIAH
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                PERTEMUAN KE
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                MATERI
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                STATUS
            </th>
        </tr>
        </thead>
        <tbody>
        <?php $no = 0; ?>
        @foreach($rekap as $item)
            <?php $no++; ?>
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{$no}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->tanggal_absen}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->tgl_pelaksanaan_}} | {{$item->waktu_mengajar}}</td>
                <td style="text-align: left; border: 1px solid #000000;">{{$item->nama_dosen}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->fullname}}</td>
                <td style="text-align: center; border: 1px solid #000000;">Pertemuan Ke-{{$item->pertemuan_ke}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->materi}}</td>
                <td style="text-align: left; border: 1px solid #000000;">{{$item->status_absen}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <table>
        <thead>
        <tr>
            <th colspan="8" style="text-align: center; border: 1px solid #000000;">REKAP ABSENSI DOSEN
            </th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center; border: 1px solid #000000;">Mulai tanggal&nbsp;<b>{{date('d F Y', strtotime($tgl_awal))}}</b>&nbsp;s/d&nbsp;<b>{{date('d F Y', strtotime($tgl_akhir))}}</b>
            </th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center; border: 1px solid #000000;"><small style="font-size: 8pt"><i>Diunduh
                        Pada Tanggal
                        : {{date('d F Y H:i:s', strtotime(now()->timezone('Asia/Jakarta')))}}</i></small></th>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center; border: 1px solid #000000;"></td>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">NO
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                TANGGAL ABSENSI
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                WAKTU MENGAJAR
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                NAMA DOSEN
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                NAMA MATAKULIAH
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                PERTEMUAN KE
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                MATERI
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                STATUS
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="8" style="font-weight: bold; text-align: center"><b>TIDAK ADA DATA</b></td>
        </tr>
        </tbody>
    </table>
@endif
