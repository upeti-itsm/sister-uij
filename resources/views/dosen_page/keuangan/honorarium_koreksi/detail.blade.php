@extends('sidebar')
@section('head-css')
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Keuangan</li>
            <li class="breadcrumb-item active">Detail Honorarium</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Detail Honorarium</h1>
                <small>Halaman ini digunakan untuk melihat detail honorarium masing-masing dosen</small>
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
                        <div>Periode <b>{{strtoupper($rekap->periode_pembayaran)}}</b></div>
                        <div class="text-danger m-b-15">Perhitungan berdasarkan data
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
                            <td colspan="2">
                                <div><strong>DETAIL HONORARIUM</strong></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div>Honorarium Pembuatan Soal<br/>
                                    <small class="text-danger">(Jumlah Pembuatan Soal UTS Pagi ({{$rekap->jml_soal_uts_pagi}}) + Jumlah Pembuatan Soal UAS Pagi ({{$rekap->jml_soal_uas_pagi}}) <br/>+ Jumlah Pembuatan Soal UTS Malam ({{$rekap->jml_soal_uts_malam}}) + Jumlah Pembuatan Soal UAS Malam ({{$rekap->jml_soal_uas_malam}})) * {{$rekap->honor_pembuatan_soal}}</small>
                                </div>
                            </td>
                            <td style="text-align: right">{{$rekap->total_honor_kotor_pembuatan_soal}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Honorarium Koreksi Ujian<br/>
                                    <small class="text-danger">(Jumlah Peserta Pagi ({{$rekap->jml_peserta_pagi}}) + Jumlah Peserta Malam ({{$rekap->jml_peserta_malam}})) * {{$rekap->honor_koreksi}}</small>
                                </div>
                            </td>
                            <td style="text-align: right">{{$rekap->total_honor_kotor_honorarium_koreksi}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div>Honorarium TM Ujian Malam<br/>
                                    <small class="text-danger">(Jumlah Matakuliah Malam({{$rekap->jml_matakuliah_malam}})*2)*{{$rekap->honor_ujian_malam}}</small>
                                </div>
                            </td>
                            <td style="text-align: right">{{$rekap->total_honor_kotor_honorarium_ujian_malam}}</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div><strong>POTONGAN</strong></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div>Potongan Infaq
                                    <small> - 2.5% dari Total Honorarium</small>
                                </div>
                            </td>
                            <td style="text-align: right">{{$rekap->total_nominal_infaq}}</td>
                        </tr>
                        <tr>
                            <td>
                                <div><strong>TOTAL HONORARIUM DITERIMA</strong></div>
                            </td>
                            <td style="font-weight: bold; text-align: right">{{$rekap->total_honorarium_koreksi}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer text-right">
                <a class="btn btn-danger-soft mr-2"
                   href="{{route('dosen_page.keuangan.honorarium.honorarium_koreksi.index')}}">Kembali</a>
                <a target="_blank"
                   href="{{route('dosen_page.keuangan.honorarium.honorarium_koreksi.slip_gaji', ['id_honorarium' => $id_honorarium])}}"
                   class="btn btn-info mr-2"><span class="fa fa-print"></span>
                </a>
                @if(!$rekap->is_repair)
                    @if($rekap->available_repair)
                        <button type="button" id="btn-ajukan-perbaikan" data-id_rekap="{{$id_honorarium}}"
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
    <script src="{{asset('adminpage/own-js/dosen_page/keuangan/honorarium_koreksi/detail.js')}}"></script>
@endpush
