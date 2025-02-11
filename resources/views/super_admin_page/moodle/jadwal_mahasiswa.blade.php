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
            <li class="breadcrumb-item active">Jadwal/KRS Mahasiswa</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Jadwal/KRS Mahasiswa</h1>
                <small>Halaman ini digunakan untuk mengelola sinkronisasi jadwal mahasiswa/KRS antara moodle dan
                    siakad</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Jadwal/KRS Mahasiswa</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <button class="btn btn-danger-soft btn-sync-ulang mr-2" id="btn-sync-ulang"
                                    title="Sinkronisasi Dengan Siakad"><i
                                    class="fas fa-cloud-download-alt"></i> Sinkronisasi Dengan Siakad
                            </button>
                            <button class="btn btn-danger-soft btn-sync-ulang mr-2" id="btn-sync-ulang-by-nim"
                                    title="Sinkronisasi Dengan Siakad"><i
                                    class="fas fa-cloud-download-alt"></i> Sinkronisasi By NIM
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
                                                   placeholder="Masukkan Nama Dosen/Mata Kuliah" id="cari-log">
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
                                            <th style="width: 80%">Mahasiswa - (Matakuliah)</th>
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
                                            <input type="text" class="form-control" placeholder="Cari NIM Mahasiswa"
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
                                            <select class="select2 form-control" id="tahun_akademik">
                                                @foreach($tahun_akademik AS $item)
                                                    <option
                                                        value="{{$item->tahun_akademik}}">{{$item->tahun_akademik}}</option>
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
                                <th>Mahasiswa</th>
                                <th>Matakuliah</th>
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
@endsection
@section('modal')
    <div class="modal modal-primary fade" id="modal-sync-jadwal" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600">Konfirmasi Sinkronisasi Jadwal/KRS Mahsiswa</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Jika dilakukan proses sinkronisasi, jadwal mahasiswa yang ada di siakad akan di import ke database
                        external moodle dan langsung di Enrol ke matakuliah masing-masing</p>
                    <div class="form-group">
                        <select class="select2 form-control" id="tahun_akademik_sync">
                            @foreach($tahun_akademik AS $item)
                                <option value="{{$item->tahun_akademik}}">{{$item->tahun_akademik}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="modal-btn-sync"><i
                            class="fas fa-sync mr-2"></i>Sinkronisasi Jadwal/KRS Mahasiswa
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-primary fade" id="modal-sync-jadwal-by-nim" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600">Konfirmasi Sinkronisasi Jadwal/KRS Mahsiswa</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Jika dilakukan proses sinkronisasi, jadwal siakad dan enrolment sebelumnya sesuai tahun akademik yang dipilih akan dihapus dan digantikan oleh data baru</p>
                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" class="form-control" placeholder="Masukkan NIM" id="nim_sync_by_nim">
                    </div>
                    <div class="form-group">
                        <label>Tahun Akademik</label>
                        <select class="select2 form-control" id="tahun_akademik_sync_by_nim">
                            @foreach($tahun_akademik AS $item)
                                <option value="{{$item->tahun_akademik}}">{{$item->tahun_akademik}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="modal-btn-sync-by-nim"><i
                            class="fas fa-sync mr-2"></i>Sinkronisasi Jadwal/KRS Mahasiswa
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/super_admin/moodle/jadwal_mahasiswa.js')}}"></script>
@endpush
