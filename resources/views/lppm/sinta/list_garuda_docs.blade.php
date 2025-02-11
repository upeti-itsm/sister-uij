@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Data Sinta</li>
            <li class="breadcrumb-item active">List Garuda Docs</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-users"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">List Garuda Docs</h1>
                <small>Halaman ini digunakan untuk menampilkan data documents yang sudah masuk ke sinta</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">List Garuda Docs</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <button class="btn btn-danger-soft btn-sync-ulang mr-2" id="btn-sync"
                                    title="Sync Doc Garuda"><i
                                    class="fas fa-user-lock"></i> Sync Garuda
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
                                        <h6 class="fs-17 font-weight-600 mb-0">Log Sinkronisasis</h6>
                                    </div>
                                    <div class="text-right">
                                        <div class="actions">
                                            <button class="btn btn-primary-soft mr-3 text-white" id="btn-failed-log">
                                                Failed : 0
                                            </button>
                                            <button class="btn btn-primary-soft mr-3 text-white" id="btn-inserted-log">
                                                Success : 0
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
                                                <option value="success">-- Success --</option>
                                                <option value="failed">-- Failed --</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="log-table">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%">No</th>
                                            <th style="width: 50%">Title</th>
                                            <th style="width: 35%">Source</th>
                                            <th style="width: 10%">Status</th>
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
                        <input type="hidden" id="id_sinta" value="{{$id_sinta}}">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control"
                                                   placeholder="Cari Nama/NIK/NIDN"
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
                            <div class="col-md-3">

                            </div>
                            <div class="col-md-4">

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
                                <th class="text-center">Identitas Author</th>
                                <th class="text-center">Judul</th>
                                <th class="text-center">Jml Sitasi</th>
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
    <script src="{{asset('adminpage/own-js/lppm/sinta/list_garuda_docs.js')}}"></script>
@endpush
