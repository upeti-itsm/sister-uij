@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/bootstrap-toogle/css/bootstrap-toogle.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Rekap SDM</li>
            <li class="breadcrumb-item active">Detail Dosen</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-users"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Detail Identitas Dosen</h1>
                <small>Halaman ini digunakan untuk menampilkan Detail Identitas Dosen</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <input type="hidden" id="hak_akses" value="{{\Illuminate\Support\Facades\Session::get('modul')['Rekap SDM']}}">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Dosen</h6>
                    </div>
                    <div class="text-right">
                        @if($get['back'] == 'kecukupan')
                            <a href="{{route('hrd_page.rekap_sdm.kecukupan_dosen')}}" class="btn btn-info rounded-pill"
                               title="Kembali"><i class="fas fa-backward mr-2"></i>Kembali
                            </a>
                        @elseif($get['back'] == 'jafa')
                            <a href="{{route('hrd_page.rekap_sdm.jabatan_akademik_dosen')}}"
                               class="btn btn-info rounded-pill"
                               title="Kembali"><i class="fas fa-backward mr-2"></i>Kembali
                            </a>
                        @elseif($get['back'] == 'serdos')
                            <a href="{{route('hrd_page.rekap_sdm.sertifikasi_dosen')}}"
                               class="btn btn-info rounded-pill"
                               title="Kembali"><i class="fas fa-backward mr-2"></i>Kembali
                            </a>
                        @elseif($get['back'] == 'lb')
                            <a href="{{route('hrd_page.rekap_sdm.dosen_tidak_tetap')}}"
                               class="btn btn-info rounded-pill"
                               title="Kembali"><i class="fas fa-backward mr-2"></i>Kembali
                            </a>
                        @elseif($get['back'] == 'rasio')
                            <a href="{{route('hrd_page.rekap_sdm.rasio_dosen')}}"
                               class="btn btn-info rounded-pill"
                               title="Kembali"><i class="fas fa-backward mr-2"></i>Kembali
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row">
                            <div class="col-md-5" style="display: none">
                                <input type="hidden" value="{{$get['back']}}" id="back">
                                <input type="hidden" value="{{$get['id_prodi']}}" id="id_prodi">
                                <input type="hidden" value="{{$get['jenis']}}" id="jenis">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" placeholder="Cari Nama Mahasiswa"
                                                   id="cari-data">
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-block btn-primary" id="btn-cari-data"><i
                                                    class="fas fa-search mr-2"></i>Cari Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <thead style="font-weight: bold">
                            <tr>
                                <td style="text-align: center; vertical-align: middle">Nomor</td>
                                <td style="text-align: center; vertical-align: middle">Nama</td>
                                @if($get['back'] == 'kecukupan' || $get['back'] == 'rasio')
                                    <td style="text-align: center; vertical-align: middle">Pendidikan Terakhir</td>
                                @elseif($get['back'] == 'jafa' || $get['back'] == 'serdos'|| $get['back'] == 'lb')
                                    <td style="text-align: center; vertical-align: middle">Jabatan Akademik</td>
                                @endif
                                <td style="text-align: center; vertical-align: middle">NIDN</td>
                                <td style="text-align: center; vertical-align: middle">Prodi</td>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/bootstrap-toogle/js/bootstrap-toogle.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/hrd_page/rekap_sdm/detail_dosen.js')}}"></script>
@endpush
