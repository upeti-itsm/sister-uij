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
                        <img src="{{asset('image/logo-uij.png')}}" style="max-height: 100px" class="img-fluid mb-3"
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
                                <div>Tunjangan Kinerja
                                    <small> - {{"Kehadiran: ".$rekap->total_kehadiran." (".$rekap->transport_harian."/Jam)"}}</small>
                                </div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->tunjangan_transport,0,',','.').',-'}}</td>
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
                                <div>Tunjangan Jamsos</div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->tunjangan_jaminan_sosial,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Insentif Masa Kerja</div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->nominal_insentif_masa_kerja,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Insentif Masa Kerja</div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->nominal_tunjangan_lembur,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Insentif Mengajar S1
                                    <small> - {{$rekap->ket_insentif_kelebihan_mengajar_s1}}</small>
                                </div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->insentif_kelebihan_mengajar_s1,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Insentif Mengajar S2
                                    <small> - {{$rekap->ket_insentif_kelebihan_mengajar_s2}}</small>
                                </div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->insentif_kelebihan_mengajar_s2,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Insentif Lainnya</div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->nominal_insentif_lainnya,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div><strong>TOTAL POTONGAN</strong></div>
                            </td>
                            <td style="font-weight: bold; text-align: right">{{"Rp. " . number_format($rekap->nominal_total_potongan,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Potongan Pinjaman</div>
                            </td>
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->nominal_koperasi,0,',','.').',-'}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Potongan Paguyuban</div>
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
                            <td style="text-align: left">{{"Rp. " . number_format($rekap->potongan_nominal_lainnya,0,',','.').',-'}}</td>
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
