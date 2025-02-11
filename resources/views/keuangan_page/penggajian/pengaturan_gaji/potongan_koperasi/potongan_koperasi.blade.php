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
            <li class="breadcrumb-item active">Pengaturan Gaji</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Potongan Koperasi</h1>
                <small>Halama ini digunakan untuk melakukan konfigurasi/upload potongan koperasi masing-masing
                    karyawan</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Potongan Pinjaman</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <div class="dropdown action-item" data-toggle="dropdown" aria-expanded="true">
                                <a href="#" class="action-item">Potongan Pinjaman <i class="fas fa-bars fa-fw"></i></a>
                                <div class="dropdown-menu dropdown-menu-right" id="sub-menu">
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.gaji_umum.index')}}"
                                       class="dropdown-item">Pengaturan Gaji Umum</a>
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.daftar_pegawai.index')}}"
                                       class="dropdown-item">Pengaturan Gaji Individu</a>
                                    <a style="display: none" href="{{route('keuangan.penggajian.pengaturan_gaji.potongan_qurban.index')}}" class="dropdown-item">Potongan Qurban</a>
                                    <a style="display: none" href="{{route('keuangan.penggajian.pengaturan_gaji.potongan_arisan.index')}}" class="dropdown-item">Potongan Arisan</a>
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.potongan_lainnya.index')}}" class="dropdown-item">Potongan Lainnya</a>
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.potongan_bpjs.index')}}" class="dropdown-item">Pengaturan BPJS</a>
                                    <a style="display: none" href="{{route('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_fungsional.index')}}" class="dropdown-item">Tunjangan Fungsional</a>
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_struktural.index')}}" class="dropdown-item">Tunjangan Jabatan</a>
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_kinerja.index')}}" class="dropdown-item">Tunjangan Kinerja</a>
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.pengaturan_umr.index')}}" class="dropdown-item">Pengaturan UMR</a>
                                </div>
                            </div>
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
                            <span id="keterangan-progress">Mohon menunggu hingga proses upload selesai ...</span>
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
                                                   placeholder="Masukkan Nama Pegawai" id="cari-log">
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
                                            <th style="width: 80%">Nama (Potongan)</th>
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
                </div>
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row" id="main-display">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select class="form-control select2" id="bulan">
                                                <option value="0">-- Semua Bulan --</option>
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control select2" id="tahun">
                                                <option value="0">-- Semua Tahun --</option>
                                                <option value="2024">2024</option>
                                                <option value="2023">2023</option>
                                                <option value="2022">2022</option>
                                                <option value="2021">2021</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="font-weight-bold">&nbsp;</label><br/>
                                    <div class="float-right">
                                        <button class="btn btn-success mr-2" title="Upload Potongan Koperasi"
                                                id="btn_upload_potongan"><i class="fas fa-file-excel"></i> Upload
                                            Potongan
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
                                <th class="text-center align-middle">Nomor</th>
                                <th class="text-left align-middle">Nama Data</th>
                                <th class="text-left align-middle">Keterangan</th>
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
    <div class="modal modal-primary fade" id="modal-upload-potongan-koperasi" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600" id="insupLabel"></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" style="text-align: left">
                                <label>Pilih Periode/Bulan</label>
                                <select class="form-control select2" id="add-bulan">
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" style="text-align: left">
                                <label>Pilih Tahun</label>
                                <select class="form-control select2" id="add-tahun">
                                    <option value="2024">2024</option>
                                    <option value="2023">2023</option>
                                    <option value="2022">2022</option>
                                    <option value="2021">2021</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" style="text-align: left">
                                <label>Upload Potongan</label>
                                <div class="custom-file">
                                    <input type="file" accept=".xlsx"
                                           class="custom-file-input"
                                           required id="customFile" name="dok_pendukung">
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                                <small><a href="{{asset('files/penggajian/template/potongan_koperasi_template.xlsx')}}">Download
                                        Template Potongan</a></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="btn-submit-file">Upload
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/keuangan_page/penggajian/pengaturan_gaji/potongan_koperasi/potongan_koperasi.js')}}"></script>
@endpush
