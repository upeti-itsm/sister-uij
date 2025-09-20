@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Keuangan</li>
            <li class="breadcrumb-item active">Tanggungan Mahasiswa</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-money-bill-wave"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Daftar Tanggungan Mahasiswa</h1>
                <small>Halaman ini digunakan untuk mengelola tagihan dan riwayat pembayaran mahasiswa</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <!-- Filter Card -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Filter & Pencarian</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse-tanggungan">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" placeholder="Cari NIM atau Nama Tagihan"
                                                   id="cari-data-tanggungan">
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-block btn-primary" id="btn-cari-data-tanggungan">
                                                <i class="fas fa-search mr-2"></i>Cari Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Filter Status</label>
                                    <select class="form-control select2" id="filter-status-lunas">
                                        <option value="false">Belum Lunas</option>
                                        <option value="true">Sudah Lunas</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card Tabel Tagihan -->
        <div class="card mt-3 shadow-sm border-0 rounded-lg">
            <!-- Header -->
            <div class="card-header bg-info text-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold">
                        <i class="fas fa-file-invoice mr-2"></i> Daftar Tagihan
                    </h6>
                    <button class="btn btn-light btn-sm text-primary font-weight-bold" id="btn-lihat-riwayat-pembayaran">
                        <i class="fas fa-history mr-1"></i> Lihat Riwayat
                    </button>
                </div>
            </div>
            <!-- Body -->
            <div class="card-body">
                <!-- Summary -->
                <div class="row mb-4">
                    <!-- Total Tagihan -->
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm border-0 rounded-lg" style="background:#e8f0fe;">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="small text-uppercase font-weight-bold text-primary">Total Tagihan</div>
                                        <div class="h5 mb-0 font-weight-bold text-dark" id="total-tagihan">Rp 0</div>
                                    </div>
                                    <i class="fas fa-file-invoice-dollar fa-2x text-primary" style="opacity:0.7;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Sudah Lunas -->
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm border-0 rounded-lg" style="background:#e6f4ea;">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="small text-uppercase font-weight-bold text-success">Sudah Lunas</div>
                                        <div class="h5 mb-0 font-weight-bold text-dark" id="tagihan-lunas">Rp 0</div>
                                    </div>
                                    <i class="fas fa-check-circle fa-2x text-success" style="opacity:0.7;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Sisa Tagihan -->
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm border-0 rounded-lg" style="background:#fce8e6;">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="small text-uppercase font-weight-bold text-danger">Sisa Tagihan</div>
                                        <div class="h5 mb-0 font-weight-bold text-dark" id="sisa-tagihan">Rp 0</div>
                                    </div>
                                    <i class="fas fa-exclamation-triangle fa-2x text-danger" style="opacity:0.7;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0" id="table-daftar-tanggungan">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center" width="50">No</th>
                            <th>Nama Tagihan</th>
                            <th class="text-center">TA / Semester</th>
                            <th class="text-right">Tagihan</th>
                            <th class="text-center">Status & Jatuh Tempo</th>
                            <th class="text-center">Tipe</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
    <!-- Modal Riwayat Pembayaran -->
    <div class="modal fade" id="modal-riwayat-pembayaran" tabindex="-1" role="dialog" aria-labelledby="modalRiwayatPembayaranLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content rounded-lg shadow-lg border-0">
                <!-- Header -->
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalRiwayatPembayaranLabel">
                        <i class="fas fa-history mr-2"></i> Riwayat Pembayaran
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Body -->
                <div class="modal-body">
                    <!-- Filter Pencarian -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="font-weight-bold mb-2">Pencarian Riwayat Pembayaran</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari No. Referensi" id="cari-data-pembayaran">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="btn-cari-data-pembayaran">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <!-- Total Pembayaran -->
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-sm h-100 border-0 rounded-lg" style="background: #e8f0fe;">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="text-uppercase small font-weight-bold text-primary mb-1">Total Pembayaran</div>
                                            <div class="h4 mb-0 font-weight-bold text-dark" id="total-pembayaran">Rp 0</div>
                                        </div>
                                        <i class="fas fa-money-bill-wave fa-2x text-primary" style="opacity: 0.75;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Jumlah Transaksi -->
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-sm h-100 border-0 rounded-lg" style="background: #e6f4ea;">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="text-uppercase small font-weight-bold text-success mb-1">Jumlah Transaksi</div>
                                            <div class="h4 mb-0 font-weight-bold text-dark" id="jumlah-transaksi">0</div>
                                        </div>
                                        <i class="fas fa-list fa-2x text-success" style="opacity: 0.75;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Tabel Riwayat Pembayaran -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover mb-0" id="table-riwayat-pembayaran">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center" width="50">No</th>
                                <th class="text-right">Jumlah Bayar</th>
                                <th>Metode & Referensi</th>
                                <th class="text-center">Tanggal Bayar</th>
                                <th class="text-center">Jenis Tanggungan</th>
                                <th>Keterangan</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <!-- Footer -->
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/mahasiswa_page/keuangan/tanggungan.js')}}"></script>
@endpush
