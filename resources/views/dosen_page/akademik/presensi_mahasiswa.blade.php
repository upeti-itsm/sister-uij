@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item active">Rekapitulasi Presensi Mahasiswa</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Rekapitulasi Absen Presensi Mahasiswa</h1>
                <small>Halaman ini digunakan monitoring data absensi mengajar dan presensi mahasiswa pada masing-masing
                    perkuliahan</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-4">
        <div class="card card-stats statistic-box mb-4">
            <div
                class="card-header card-header-info card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-users"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Total Mahasiswa</p>
                <h3 class="card-title fs-21 font-weight-bold" id="tot_mahasiswa">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-user-graduate mr-2 ml-2"></i>Keseluruhan Mahasiswa
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stats statistic-box mb-4">
            <div
                class="card-header card-header-success card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-user-check"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Mahasiswa Hadir</p>
                <h3 class="card-title fs-21 font-weight-bold" id="tot_hadir">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-user-check mr-2 ml-2"></i>Hadir Dikelas
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stats statistic-box mb-4">
            <div
                class="card-header card-header-danger card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="typcn typcn-info-outline"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Mahasiswa Alpha</p>
                <h3 class="card-title fs-21 font-weight-bold" id="tot_alpha">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-clock mr-2 ml-2"></i>Mahasiswa Tanpa Keterangan
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-stats statistic-box mb-4">
            <div
                class="card-header card-header-warning card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Mahasiswa Izin</p>
                <h3 class="card-title fs-21 font-weight-bold" id="tot_ijin">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-check-square mr-2 ml-2"></i>Mahasiswa Ijin
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-stats statistic-box mb-4">
            <div
                class="card-header card-header-warning card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-user-injured"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Mahasiswa Sakit</p>
                <h3 class="card-title fs-21 font-weight-bold" id="tot_sakit">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-user-injured mr-2 ml-2"></i>Mahasiswa Sakit
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Rekapitulasi Presensi Mahasiswa</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <input type="hidden" id="id_rekap" value="{{$id}}">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control form-control-sm" placeholder="Cari Nama Mahasiswa"
                                                   id="cari-data">
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-block btn-sm btn-primary" id="btn-cari-data"><i
                                                    class="fas fa-search mr-2"></i>Cari Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label>Pengaturan Absensi</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <button id="btn-hadir-semua" class="btn btn-block btn-sm btn-info"><i
                                                class="fas fa-user-check mr-2"></i>Set Hadir Semua
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button id="btn-simpan-presensi"
                                                class="btn btn-block btn-sm btn-success"><i class="fas fa-save mr-2"></i>
                                            <span class="btn-text">Simpan Presensi</span>
                                            <div class="spinner-border spinner-border-sm text-light ms-2 d-none" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>Exporting</label>
                                <button type="button" id="export-to-pdf" class="btn btn-sm btn-block mb-2 btn-danger"><i
                                        class="fas fa-file-pdf mr-2"></i>Export To PDF
                                </button>
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
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                                <th class="text-center">Status</th>
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

@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.id.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/dosen_page/akademik/presensi_mahasiswa.js')}}"></script>
@endpush
