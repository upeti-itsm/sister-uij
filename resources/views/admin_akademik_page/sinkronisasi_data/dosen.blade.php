@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Sinkronisasi Data</li>
            <li class="breadcrumb-item active">Dosen</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-th-large"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Dosen</h1>
                <small>Halaman ini digunakan untuk mengelola data Dosen (Syncronisasi dengan
                    feeder.stie-mandala.ac.id)</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fs-17 font-weight-600 mb-0">Daftar Dosen</h6>
                            </div>
                            <div class="text-right">
                                <div class="actions">
                                    <button class="btn btn-info-soft btn-sync-ulang" id="btn-sync-ulang"
                                            title="Syncron Dengan Feeder"><i
                                            class="fas fa-sync"></i> Update Data dari Feeder
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3" id="progress-bar-syncron-ulang" style="display: none">
                        <button class="btn btn-primary mr-1 mb-2" type="button" disabled="">
                            <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true" id="loading-progress"></span>
                            <span id="keterangan-progress">Mohon menunggu hingga proses sinkronisasi selesai ...</span>
                        </button>
                        <button class="btn btn-danger-soft mr-1 mb-2" id="btn-cancel-syncron-ulang"><i class="fas fa-window-close mr-2"></i>Batal</button>
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
                                            <button class="btn btn-primary-soft mr-3 text-white" id="btn-gagal-log">Gagal : 0</button>
                                            <button class="btn btn-primary-soft mr-3 text-white" id="btn-sukses-log">Sukses : 0</button>
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
                                            <input type="text" class="form-control" placeholder="Masukkan Nama Dosen/NIDN" id="cari-log">
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
                                                <option value="">-- Semua --</option>
                                                <option value="sukses">-- Sukses --</option>
                                                <option value="gagal">-- Gagal --</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="log-table">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%">Nomor</th>
                                            <th style="width: 80%">Nama (NIDN)</th>
                                            <th style="width: 15%">STATUS</th>
                                        </tr>
                                        </thead>
                                        <tbody id="log-table-tbody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row" id="row-list-data">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Pencarian</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" class="form-control" placeholder="Cari Nama Dosen"
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Filtering</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <select class="form-control select2" name="prodi" id="prodi">
                                        <option value="00000000-0000-0000-0000-000000000000">-- Semua Program Studi --
                                        </option>
                                        @foreach($program_studi as $item)
                                            <option
                                                value="{{$item->id_program_studi}}">{{$item->nama_program_studi.' ('.$item->jenjang_didik.')'}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-block btn-primary" id="btn-filter"><i
                                            class="fas fa-filter mr-2"></i>Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="table">
                                <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Nama Dosen</th>
                                    <th>Keterangan</th>
                                    <th><i class="fas fa-th-large"></i></th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Nama Dosen</th>
                                    <th>Keterangan</th>
                                    <th><i class="fas fa-th-large"></i></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
    <div class="modal modal-primary fade" id="modal-check-feeder" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600">Perbandingan Data Feeder</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modal-check-id_program_studi">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="text-center">Atribut</th>
                            <th class="text-center">SIPADU</th>
                            <th class="text-center">FEEDER</th>
                            <th class="text-center"><i class="fas fa-th-large"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Kode Program Studi</td>
                            <td><span id="modal-check-kd_prodi_sipadu"></span></td>
                            <td><span id="modal-check-kd_prodi_feeder"></span></td>
                            <td><span id="modal-check-perbandingan_kd_prodi"></span></td>
                        </tr>
                        <tr>
                            <td>Nama Program Studi</td>
                            <td><span id="modal-check-nama_prodi_sipadu"></span></td>
                            <td><span id="modal-check-nama_prodi_feeder"></span></td>
                            <td><span id="modal-check-perbandingan_nama_prodi"></span></td>
                        </tr>
                        <tr>
                            <td>Jenjang Pendidikan</td>
                            <td><span id="modal-check-jenjang_didik_sipadu"></span></td>
                            <td><span id="modal-check-jenjang_didik_feeder"></span></td>
                            <td><span id="modal-check-perbandingan_jenjang_didik"></span></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td><span id="modal-check-status_sipadu"></span></td>
                            <td><span id="modal-check-status_feeder"></span></td>
                            <td><span id="modal-check-perbandingan_status"></span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="modal-check-btn-sync"><i
                            class="fas fa-sync mr-2"></i>Syncron Ulang
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/admin_akademik/sinkronisasi_data/dosen.js')}}"></script>
@endpush
