@extends('sidebar')
@section('head-css')
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Penggajian</li>
            <li class="breadcrumb-item active">Detail Gaji Bulanan</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Detail Gaji Bulanan</h1>
                <small>Halaman ini digunakan untuk melihat detail gaji bulanan masing-masing pegawai</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <img src="{{asset('image/logo-mandala.png')}}" style="max-height: 100px" class="img-fluid mb-3"
                             alt="">
                    </div>
                    <div class="col-sm-6 text-right">
                        <div>Periode <b>{{strtoupper($rekap->periode_pembayaran).' '.$rekap->tahun}}</b></div>
                        <div class="text-danger m-b-15">Perhitungan berdasarkan data kinerja
                            bulan {{$rekap->periode_rekap}}</div>
                        <address>
                            <strong>{{$karyawan->nama_lengkap}}</strong><br>
                            {{$karyawan->unit_kerja}}<br>
                            Rekening: {{$karyawan->nomor_rekening}}<br>
                            HP: {{$karyawan->no_hp}}
                        </address>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-nowrap">
                        <tbody>
                        <tr>
                            <td>
                                <div><strong>GAJI POKOK</strong>
                                    <small> - {{$karyawan->golongan}}</small>
                                </div>
                            </td>
                            <td style="font-weight: bold; text-align: right">{{"Rp. " . number_format($rekap->gaji_pokok,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div><strong>TOTAL TUNJANGAN</strong></div>
                            </td>
                            <td style="font-weight: bold; text-align: right">{{"Rp. " . number_format($rekap->nominal_total_tunjangan,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Tunjangan Fungsional</div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->tunjangan_fungsional,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Tunjangan Kinerja</div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->nominal_tunjangan_kinerja,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Tunjangan Struktural
                                    <small> - {{'Jabatan Struktural: '.$karyawan->jabatan_struktural ?: '-'}}</small>
                                </div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->tunjangan_struktural,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Tunjangan Pendidikan
                                    <small> - {{'Pendidikan Terakhir: '.$karyawan->pendidikan_terakhir ?: '-'}}</small>
                                </div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->nominal_tunjangan_pendidikan,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Tunjangan Keluarga
                                    <small> - {{'Jumlah Anak: '.$rekap->jml_anak.' orang'}}</small>
                                </div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->nominal_tunjangan_keluarga,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Tunjangan Beras
                                    <small> - {{'Diberikan dalam bentuk '.$rekap->total_beras.' beras'}}</small>
                                </div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->total_harga_beras,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Tunjangan Transport
                                    <small>
                                        - {{$rekap->total_kehadiran.' hari X Rp. '.number_format($rekap->transport_harian,0,',','.')}}</small>
                                </div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->tunjangan_transport,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div><strong>TOTAL POTONGAN</strong></div>
                            </td>
                            <td style="font-weight: bold; text-align: right">{{"Rp. " . number_format($rekap->nominal_total_potongan,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Potongan Infaq
                                    <small> - 2.5% dari gaji kotor</small>
                                </div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->nominal_infaq,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Potongan Koperasi</div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->nominal_koperasi,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Potongan Arisan</div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->nominal_arisan,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Potongan Qurban</div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->nominal_qurban,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Potongan DPLK</div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->nominal_dplk,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Potongan Beras</div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->total_harga_beras,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Potongan BPJS Kesehatan</div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->nominal_kesehatan,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Potongan BPJS Ketenagakerjaan</div>
                                <small>Potongan JHT
                                    - {{"Rp. " . number_format($rekap->nominal_jht,0,',','.').',-'}}</small><br>
                                <small>Potongan JKM
                                    - {{"Rp. " . number_format($rekap->nominal_jkm,0,',','.').',-'}}</small><br>
                                <small>Potongan JKK
                                    - {{"Rp. " . number_format($rekap->nominal_jkk,0,',','.').',-'}}</small><br>
                                <small>Potongan JKP
                                    - {{"Rp. " . number_format($rekap->nominal_jp,0,',','.').',-'}}</small><br>
                            </td>
                            <td style="text-align: left; vertical-align: center">{{"Rp. " . number_format($rekap->nominal_ketenagakerjaan,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold">
                                <div>TOTAL POTONGAN BPJS</div>
                            </td>
                            <td style="font-weight: bold; text-align: left">{{"Rp. " . number_format($rekap->nominal_asuransi,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Potongan Lainnya</div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->nominal_lainnya,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div><strong>TOTAL GAJI DITERIMA</strong></div>
                            </td>
                            <td style="font-weight: bold; text-align: right">{{$rekap->total_nominal_gaji}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer text-right">
                <a class="btn btn-danger-soft mr-2"
                   href="{{route('karyawan.penggajian.gaji_bulanan.index')}}">Kembali</a>
                <a target="_blank"
                   href="{{route('karyawan.penggajian.gaji_bulanan.slip_gaji', ['id_rekap' => $id_rekap])}}"
                   class="btn btn-info mr-2"><span class="fa fa-print"></span>
                </a>
                @if(!$rekap->is_repair)
                    @if($rekap->available_repair)
                        <button type="button" id="btn-ajukan-perbaikan" data-id_rekap="{{$id_rekap}}"
                                class="btn btn-success"><span class='spinner-border spinner-border-sm mr-2'
                                                              id='loading-spin-ajukan-perbaikan'
                                                              style='display: none' role='status'
                                                              aria-hidden='true'></span><i
                                class="fas fa-edit mr-2"></i>Ajukan Perbaikan
                        </button>
                    @endif
                @else
                    <button class="btn btn-info-soft"
                            title="Pengajuan perbaikan telah dikirim ke bagian keuangan dan dalam proses pengecekan"
                            style="cursor: default">Dalam Proses Perbaikan
                    </button>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('modal')
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/numeral/numeral.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/karyawan_page/penggajian/detail_gaji_bulanan.js')}}"></script>
@endpush
