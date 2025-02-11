@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Moodle</li>
            <li class="breadcrumb-item active">Mahasiswa</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Mahasiswa</h1>
                <small>Halaman ini digunakan untuk mengelola sinkronisasi mahasiswa antara moodle dan siakad</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link show active" id="pills-home-tab" data-toggle="pill" href="#mahasiswa-elearning" role="tab" aria-controls="mahasiswa-elearning" aria-selected="true">Elearning</a>
            </li>
            <li class="nav-item">
                <a class="nav-link show" id="pills-profile-tab" data-toggle="pill" href="#mahasiswa-data_center" role="tab" aria-controls="mahasiswa-data_center" aria-selected="false">Data Center</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade active show" id="mahasiswa-elearning" role="tabpanel" aria-labelledby="mahasiswa-elearning-tab">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fs-17 font-weight-600 mb-0">Daftar Mahasiswa</h6>
                            </div>
                            <div class="text-right">
                                <div class="actions">
                                    <button class="btn btn-danger-soft btn-sync-ulang mr-2" id="btn-sync-ulang"
                                            title="Syncron Dengan Siakad"><i
                                            class="fas fa-cloud-download-alt"></i> Synchron Dengan Siakad
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mt-3" id="progress-bar-syncron-ulang" style="display: none">
                                <button class="btn btn-primary mr-1 mb-2" type="button" disabled="">
                            <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"
                                  id="loading-progress"></span>
                                    <span id="keterangan-progress">Mohon menunggu hingga proses sinkronisasi selesai ...</span>
                                </button>
                                <button class="btn btn-danger-soft mr-1 mb-2" id="btn-cancel-syncron-ulang"><i
                                        class="fas fa-window-close mr-2"></i>Batal
                                </button>
                                <div class="progress progress-lg mb-3">
                                    <div
                                        class="progress-bar progress-bar-violet progress-bar-striped progress-bar-animated"
                                        role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                        style="width: 0" id="progress-bar">
                                        <span id="progress-text"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3" id="log-syncron-ulang" style="display: none">
                                <div class="card bg-light">
                                    <div class="card-header bg-info text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="fs-17 font-weight-600 mb-0">Log Sinkronisasi</h6>
                                            </div>
                                            <div class="text-right">
                                                <div class="actions">
                                                    <button class="btn btn-primary-soft mr-3 text-white" id="btn-failed-log">
                                                        Failed : 0
                                                    </button>
                                                    <button class="btn btn-primary-soft mr-3 text-white" id="btn-inserted-log">
                                                        Inserted : 0
                                                    </button>
                                                    <button class="btn btn-primary-soft mr-3 text-white" id="btn-updated-log">
                                                        Updated : 0
                                                    </button>
                                                    <button class="action-item text-white" title="Tutup Log" id="btn-tutup-log">
                                                        <i class="fas fa-times-circle"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control"
                                                           placeholder="Masukkan Nama Mahasiswa" id="cari-log">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" id="btn-cari-log"><i
                                                                class="fas fa-search mr-2"></i>Cari
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select class="form-control select2" id="status-log">
                                                        <option value="">-- All Of Them --</option>
                                                        <option value="inserted">-- Inserted --</option>
                                                        <option value="updated">-- Updated --</option>
                                                        <option value="failed">-- Failed --</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover" id="log-table">
                                                <thead>
                                                <tr>
                                                    <th style="width: 5%">Nomor</th>
                                                    <th style="width: 80%">Nama - (NIM)</th>
                                                    <th style="width: 15%">Status</th>
                                                </tr>
                                                </thead>
                                                <tbody id="log-table-tbody">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 collapse show" id="filter-collapse">
                                <div class="row">
                                    <div class="col-md-5">
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
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Filtering</label>
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <select class="select2 form-control" id="angkatan">
                                                        @foreach($angkatan AS $item)
                                                            <option
                                                                value="{{$item->angkatan}}">{{$item->angkatan}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <button class="btn btn-block btn-primary" id="btn-filter"><i
                                                            class="fas fa-filter mr-2"></i>Filter
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
                                    <thead>
                                    <tr>
                                        <th class="text-center">Nomor</th>
                                        <th>Identitas Mahasiswa</th>
                                        <th>Keterangan</th>
                                        <th class="text-center"><i class="fas fa-th"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="mahasiswa-data_center" role="tabpanel" aria-labelledby="mahasiswa-data_center-tab">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fs-17 font-weight-600 mb-0">Daftar Mahasiswa</h6>
                            </div>
                            <div class="text-right">
                                <div class="actions">
                                    <button class="btn btn-danger-soft btn-sync-ulang-data-center mr-2" id="btn-sync-ulang-data-center"
                                            title="Syncron Dengan Siakad"><i
                                            class="fas fa-cloud-download-alt"></i> Synchron Dengan Siakad
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mt-3" id="progress-bar-syncron-ulang-data-center" style="display: none">
                                <button class="btn btn-primary mr-1 mb-2" type="button" disabled="">
                            <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"
                                  id="loading-progress-data-center"></span>
                                    <span id="keterangan-progress-data-center">Mohon menunggu hingga proses sinkronisasi selesai ...</span>
                                </button>
                                <button class="btn btn-danger-soft mr-1 mb-2" id="btn-cancel-syncron-ulang-data-center"><i
                                        class="fas fa-window-close mr-2"></i>Batal
                                </button>
                                <div class="progress progress-lg mb-3">
                                    <div
                                        class="progress-bar progress-bar-violet progress-bar-striped progress-bar-animated"
                                        role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                        style="width: 0" id="progress-bar-data-center">
                                        <span id="progress-text-data-center"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3" id="log-syncron-ulang-data-center" style="display: none">
                                <div class="card bg-light">
                                    <div class="card-header bg-info text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="fs-17 font-weight-600 mb-0">Log Sinkronisasi</h6>
                                            </div>
                                            <div class="text-right">
                                                <div class="actions">
                                                    <button class="btn btn-primary-soft mr-3 text-white" id="btn-failed-log-data-center">
                                                        Failed : 0
                                                    </button>
                                                    <button class="btn btn-primary-soft mr-3 text-white" id="btn-inserted-log-data-center">
                                                        Inserted : 0
                                                    </button>
                                                    <button class="btn btn-primary-soft mr-3 text-white" id="btn-updated-log-data-center">
                                                        Updated : 0
                                                    </button>
                                                    <button class="action-item text-white" title="Tutup Log" id="btn-tutup-log-data-center">
                                                        <i class="fas fa-times-circle"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control"
                                                           placeholder="Masukkan Nama Mahasiswa" id="cari-log-data-center">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" id="btn-cari-log-data-center"><i
                                                                class="fas fa-search mr-2"></i>Cari
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select class="form-control select2" id="status-log-data-center">
                                                        <option value="">-- All Of Them --</option>
                                                        <option value="inserted">-- Inserted --</option>
                                                        <option value="updated">-- Updated --</option>
                                                        <option value="failed">-- Failed --</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover" id="log-table-data-center">
                                                <thead>
                                                <tr>
                                                    <th style="width: 5%">Nomor</th>
                                                    <th style="width: 80%">Nama - (NIM)</th>
                                                    <th style="width: 15%">Status</th>
                                                </tr>
                                                </thead>
                                                <tbody id="log-table-tbody-data-center">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 collapse show" id="filter-collapse-data-center">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Pencarian</label>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" placeholder="Cari Nama Mahasiswa"
                                                           id="cari-data-data-center">
                                                </div>
                                                <div class="col-md-4">
                                                    <button class="btn btn-block btn-primary" id="btn-cari-data-data-center"><i
                                                            class="fas fa-search mr-2"></i>Cari Data
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Filtering</label>
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <select class="select2 form-control" id="angkatan-data-center">
                                                        @foreach($angkatan AS $item)
                                                            <option
                                                                value="{{$item->angkatan}}">{{$item->angkatan}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <button class="btn btn-block btn-primary" id="btn-filter-data-center"><i
                                                            class="fas fa-filter mr-2"></i>Filter
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
                                <table class="table table-striped table-bordered table-hover" id="table-data-center">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Nomor</th>
                                        <th>Identitas Mahasiswa</th>
                                        <th>Keterangan</th>
                                        <th class="text-center"><i class="fas fa-th"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
    <div class="modal modal-primary fade" id="modal-sync-mahasiswa" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600">Konfirmasi Sinkronisasi Mahasiswa</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Jika dilakukan proses sinkronisasi, mahasiswa yang ada di siakad akan di import ke database
                        external moodle, namun belum menjadi <b>User</b> di moodle sebelum dilakukan proses
                        sinkronisasi user oleh pengguna, silahkan pilih angkatan untuk proses sinkronisasi
                        mahasiswa</p>
                    <div class="form-group">
                        <select class="select2 form-control" id="angkatan_sync">
                            @foreach($angkatan AS $item)
                                <option value="{{$item->angkatan}}">{{$item->angkatan}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="modal-btn-sync"><i
                            class="fas fa-sync mr-2"></i>Sinkronisasi Mahasiswa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-primary fade" id="modal-sync-mahasiswa-data-center" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600">Konfirmasi Sinkronisasi Mahasiswa</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Jika dilakukan proses sinkronisasi, mahasiswa yang ada di siakad akan di import ke database stie_mandala, silahkan pilih angkatan untuk proses sinkronisasi
                        mahasiswa</p>
                    <div class="form-group">
                        <select class="select2 form-control" id="angkatan_sync-data-center">
                            @foreach($angkatan AS $item)
                                <option value="{{$item->angkatan}}">{{$item->angkatan}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="modal-btn-sync-data-center"><i
                            class="fas fa-sync mr-2"></i>Sinkronisasi Mahasiswa
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/super_admin/moodle/mahasiswa.js')}}"></script>
@endpush
