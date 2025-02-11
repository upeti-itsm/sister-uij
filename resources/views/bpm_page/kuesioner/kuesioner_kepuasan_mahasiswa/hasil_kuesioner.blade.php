@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <style>
        .chartdiv {
            width: 100%;
            height: 200px;
        }
    </style>
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Kuesioner</li>
            <li class="breadcrumb-item active">Kepuasan Mahasiswa</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Kepuasan Mahasiswa</h1>
                <small>Halaman ini digunakan untuk melihat hasil kuesioner Kepuasan Mahasiswa terhadap kinerja Manajemen
                    ITS Mandala</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Hasil Kuesioner</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <div class="row">
                                <div class="col-md-7">
                                    <select class="form-control select2" id="id_semester" >
                                        <option value="0">-- Semua TA --</option>
                                        @foreach($semester AS $item)
                                            <option
                                                value="{{$item->id_semester}}">{{$item->nama_semester}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <a id="btn-kelola-kuesioner" class="btn btn-block btn-success text-white"><i
                                            class="fas fa-file-excel"></i> Export
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12" id="filter-collapse">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">Jenis Kuesioner</label>
                                    <select class="select2 form-control" id="id_jenis">
                                        @foreach($jenis_kuesioner AS $item)
                                            <option
                                                value="{{$item->id}}">{{$item->jenis_kuesioner}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Unsur Penilaian</label><br/>
                                    <button class="btn btn-success-soft" style="display: none" type="button" disabled id="loading-unsur">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                    <select class="select2 form-control" id="id_unsur">
                                        @foreach($unsur AS $item)
                                            <option
                                                value="{{$item->id_unsur}}">{{$item->unsur_penilaian}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <button class="btn btn-success-soft" style="display: none" type="button" disabled id="loading">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </button>
                        <div id="sub-unsur-area">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')

@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/amchart5/index.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/amchart5/xy.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/amchart5/themes/Animated.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script
        src="{{asset('adminpage/own-js/bpm_page/kuesioner/kuesioner_kepuasan_mahasiswa/hasil_kuesioner.js')}}"></script>
@endpush
