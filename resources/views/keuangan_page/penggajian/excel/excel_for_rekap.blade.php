
<style>
    tr > td {
        border: 1px solid #000000;
    }
</style>
@if(count($data) > 0)
    <table>
        <thead>
        <tr>
            <th colspan="21" style="text-align: center; border: 1px solid #000000;">LIST GAJI DOSEN DAN KARYAWAN TETAP</th>
        </tr>
        <tr>
            <th colspan="21" style="text-align: center; border: 1px solid #000000;">INSTITUT TEKNOLOGI DAN SAINS MANDALA</th>
        </tr>
        <tr>
            <th colspan="12" style="text-align: center; border: 1px solid #000000;">BULAN: {{$bulan.' '.$tahun}}</th>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                NO
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                NAMA
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                GOL
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                GAJI POKOK
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                TUNJANGAN KELUARGA
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                TUNJANGAN FUNGSIONAL
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                TUNJANGAN STRUKTURAL
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                TUNJANGAN PENDIDIKAN (S3)
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                TUNJANGAN BERAS
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                TRANSPORT
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                KINERJA DOSEN
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                JUMLAH KOTOR
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                POTONGAN BERAS
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                POTONGAN KOPERASI
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                BPJS
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                DPLK
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                ARISAN
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                QURBAN
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                POTONGAN LAIN
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                JUMLAH INFAQ
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                JUMLAH POTONGAN
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                JUMLAH DITERIMA
            </th>
        </tr>
        </thead>
        <tbody>
        <?php $no = 0; ?>
        @foreach($data as $item)
            <?php $no++; ?>
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{$no}}</td>
                <td style="text-align: left; border: 1px solid #000000;">{{strtoupper($item->nama)}}</td>
                <td style="text-align: left; border: 1px solid #000000;">{{$item->golongan}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->nominal_gaji_pokok}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->nominal_tunjangan_keluarga}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->nominal_tunjangan_fungsional}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->nominal_tunjangan_struktural}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->nominal_tunjangan_pendidikan}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->nominal_tunjangan_beras}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->nominal_transport}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->nominal_kinerja_dosen}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->total_kotor_karyawan}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->nominal_tunjangan_beras}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->nominal_potongan_koperasi}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->nominal_potongan_bpjs}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->nominal_dplk}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->nominal_potongan_arisan}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->nominal_potongan_qurban}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->nominal_potongan_lainnya}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->nominal_infaq}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->nominal_potongan_karyawan}}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{$item->nominal_gaji_karyawan}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" style="text-align: center; font-weight: bold; border: 1px solid #000000;">JUMLAH</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_gaji_pokok}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_tunjangan_keluarga}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_tunjangan_fungsional}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_tunjangan_struktural}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_tunjangan_pendidikan}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_tunjangan_beras}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_transport}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_kinerja_dosen}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_gaji_kotor}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_potongan_beras}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_potongan_koperasi }}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_potongan_bpjs}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_potongan_dplk}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_potongan_arisan}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_potongan_qurban}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_potongan_lainnya}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_infaq_karyawan}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_potongan_karyawan}}</td>
            <td style="text-align: center; font-weight: bold; border: 1px solid #000000;">{{$data[0]->total_nominal_gaji_diterima}}</td>
        </tr>
        <tr>
            <td colspan="21"></td>
        </tr>
        <tr>
            <td colspan="10" style="text-align: left">
                Mengetahui
            </td>
            <td colspan="11" style="text-align: right">
                Jember, {{$tgl_ttd}}
            </td>
        </tr>
        <tr>
            <td colspan="10"  style="text-align: left">WAREK II</td>
            <td colspan="11"  style="text-align: right">Kabag Keuangan</td>
        </tr>
        <tr>
            <td colspan="10"></td>
            <td colspan="11"></td>
        </tr>
        <tr>
            <td colspan="10"></td>
            <td colspan="11"></td>
        </tr>
        <tr>
            <td colspan="10"></td>
            <td colspan="11"></td>
        </tr>
        <tr>
            <td colspan="10" style="text-align: left">
                Dr. Yuniorita Indah H.,S.E.,M.B.A
            </td>
            <td colspan="11" style="text-align: right">
                Mika Nurjanah, S.E., M.Ak
            </td>
        </tr>
        </tbody>
    </table>
@else
    <table>
        <thead>
        <tr>
            <th colspan="21" style="text-align: center; border: 1px solid #000000;">LIST GAJI DOSEN DAN KARYAWAN TETAP</th>
        </tr>
        <tr>
            <th colspan="21" style="text-align: center; border: 1px solid #000000;">SEKOLAH TINGGI ILMU EKONOMI MANDALA</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                NO
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                NAMA
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                GOL
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                GAJI POKOK
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                TUNJANGAN KELUARGA
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                TUNJANGAN FUNGSIONAL
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                TUNJANGAN STRUKTURAL
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                TUNJANGAN PENDIDIKAN (S3)
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                TUNJANGAN BERAS
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                TRANSPORT
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                KINERJA DOSEN
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                JUMLAH KOTOR
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                POTONGAN BERAS
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                POTONGAN KOPERASI
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                BPJS
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                DPLK
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                ARISAN
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                QURBAN
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                POTONGAN LAIN
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                JUMLAH INFAQ
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                JUMLAH POTONGAN
            </th>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                JUMLAH DITERIMA
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="21" style="font-weight: bold; text-align: center"><b>TIDAK ADA DATA</b></td>
        </tr>
        </tbody>
    </table>
@endif
