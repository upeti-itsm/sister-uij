@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Penggajian</li>
            <li class="breadcrumb-item active">Gaji Bulanan</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Daftar Gaji Bulanan</h1>
                <small>Halaman ini digunakan untuk mengelola Gaji Bulananan untuk Dosen/Karyawan</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fs-17 font-weight-600 mb-0">Daftar Gaji Bulanan</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="float-right">
                            <button class="btn btn-primary" id="btn_buat_gaji" title="Buat Gaji Bulan Ini">
                                <span class='spinner-border spinner-border-sm mr-2' id='loading-spin-buat-gaji'
                                      style='display: none' role='status' aria-hidden='true'></span><i
                                    class="fas fa-plus-square"></i> Buat Gaji
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row" id="main-display">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" placeholder="Cari Nama Karyawan"
                                                   id="cari-data">
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-block btn-primary" id="btn-cari-data"><i
                                                    class="fas fa-search mr-2"></i>Cari
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Filtering</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select class="select2 form-control" id="filter_bulan">
                                                <option value="-1">-- Semua Bulan --</option>
                                                <option value="1" @if($bulan == 1) selected @endif>Januari</option>
                                                <option value="2" @if($bulan == 2) selected @endif>Februari</option>
                                                <option value="3" @if($bulan == 3) selected @endif>Maret</option>
                                                <option value="4" @if($bulan == 4) selected @endif>April</option>
                                                <option value="5" @if($bulan == 5) selected @endif>Mei</option>
                                                <option value="6" @if($bulan == 6) selected @endif>Juni</option>
                                                <option value="7" @if($bulan == 7) selected @endif>Juli</option>
                                                <option value="8" @if($bulan == 8) selected @endif>Agustus</option>
                                                <option value="9" @if($bulan == 9) selected @endif>September</option>
                                                <option value="10" @if($bulan == 10) selected @endif>Oktober</option>
                                                <option value="11" @if($bulan == 11) selected @endif>November</option>
                                                <option value="12" @if($bulan == 12) selected @endif>Desember</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="select2 form-control" id="filter_tahun">
                                                <option value="2024">2024</option>
                                                <option value="2023">2023</option>
                                                <option value="2022">2022</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="select2 form-control" id="filter_repair">
                                                <option value="all">Semua</option>
                                                <option value="1">Butuh Perbaikan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="font-weight-bold">&nbsp;</label><br/>
                                    <div class="float-right">
                                        <button class="btn btn-success mr-2" title="Export ke Excel" id="btn_export_excel"><i
                                                class="fas fa-file-excel"></i>
                                        </button>
                                        <button class="btn btn-danger mr-2" title="Export ke PDF" id="btn_export_pdf"><i
                                                class="fas fa-file-pdf"></i>
                                        </button>
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
                                <th class="text-left">Identitas Pegawai</th>
                                <th class="text-left">Gaji</th>
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
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/keuangan_page/penggajian/daftar_gaji_bulanan.js')}}"></script>
@endpush
