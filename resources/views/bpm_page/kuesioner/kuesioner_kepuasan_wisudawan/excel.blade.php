<style>
    tr > td {
        border: 1px solid #000000;
    }
</style>
@if(count($data) > 0)
    <table>
        <thead>
        <tr>
            <th colspan="{{sizeof(explode(';', $data[0]->pernyataan))}}"
                style="text-align: center; border: 1px solid #000000;">Response
                Kepuasan Wisudawan
            </th>
        </tr>
        <tr>
            <th colspan="{{sizeof(explode(';', $data[0]->pernyataan))}}"
                style="text-align: center; border: 1px solid #000000;"><small
                    style="font-size: 8pt"><i>Diunduh
                        Pada Tanggal
                        : {{date('d F Y H:i:s', strtotime(now()->timezone('Asia/Jakarta')))}}</i></small></th>
        </tr>
        <tr>
            <td colspan="{{sizeof(explode(';', $data[0]->pernyataan))}}"
                style="text-align: center; border: 1px solid #000000;"></td>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center"
                rowspan="2">Waktu Pengisian
            </th>
            @foreach($header as $key => $item)
                <th style="text-align: center; font-weight: bold; border: 1px solid #000000; height: 25px; vertical-align: center"
                    colspan="{{$item}}">
                    {{$key}}
                </th>
            @endforeach
        </tr>
        <tr>
            @foreach($kolom as $item)
                <th style="text-align: center; font-weight: bold; background-color: #ffb804; border: 1px solid #000000; height: 25px; vertical-align: center">
                    {{$item}}
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($data AS $item)
            <tr>
                <td style="text-align: right; border: 1px solid #000000; vertical-align: center">{{$item->tgl_created}}</td>
                @foreach(explode(';', $item->nilai) as $jawaban)
                    <td style="text-align: right; border: 1px solid #000000; vertical-align: center">
                        {{$jawaban}}
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <table>
        <thead>
        <tr>
            <th colspan="4" style="text-align: center">TIDAK ADA RESPON
            </th>
        </tr>
    </table>
@endif
