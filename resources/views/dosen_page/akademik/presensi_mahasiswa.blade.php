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
                <small>Halaman ini digunakan monitoring data absensi mengajar dan presensi mahasiswa pada masing-masing perkuliahan</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div
                class="card-header card-header-info card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-users"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Total Mahasiswa</p>
                <h3 class="card-title fs-21 font-weight-bold" id="tot_reg_p">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-cloud-sun mr-2 ml-2"></i>Mahasiswa Hadir
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div
                class="card-header card-header-info card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-users"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Mahasiswa Tidak Hadir</p>
                <h3 class="card-title fs-21 font-weight-bold" id="tot_reg_m">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-cloud-moon mr-2 ml-2"></i>Mahasiswa Alpha
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div
                class="card-header card-header-success card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Total Mahasiswa</p>
                <h3 class="card-title fs-21 font-weight-bold" id="tot_tepat">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-check-square mr-2 ml-2"></i>Mahasiswa Ijin
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div
                class="card-header card-header-danger card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="typcn typcn-info-outline"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Total Mahasiswa</p>
                <h3 class="card-title fs-21 font-weight-bold" id="tot_terlambat">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-clock mr-2 ml-2"></i>Mahasiswa Sakit
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
                            <div class="col-md-2">
                                <label>Exporting</label>
                                <button id="export-to-pdf" class="btn btn-sm btn-block mb-2 btn-danger"><i class="fas fa-file-pdf mr-2"></i>Export To PDF</button>
{{--                                <button id="export-to-excel" class="btn btn-sm btn-block btn-success"><i class="fas fa-file-excel mr-2"></i>Export To Excel</button>--}}
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
