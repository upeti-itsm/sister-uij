@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item active">
                Pengaturan SKS
            </li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Pengaturan SKS</h1>
                <small>Halaman ini digunakan untuk mengelola aturan pengambilan SKS berdasarkan IPS mahasiswa</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Pengaturan SKS</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <a id="btn-tambah-data" class="btn btn-sm btn-success text-white">
                                <i class="fas fa-plus-square"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse" id="form-collapse">
                        <input type="hidden" id="id_aturan">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">IPS Min <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" id="ips_min" name="ips_min" class="form-control"
                                        placeholder="Contoh: 3.00" min="0" max="4">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">IPS Max <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" id="ips_max" name="ips_max" class="form-control"
                                        placeholder="Contoh: 4.00" min="0" max="4">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">SKS Maksimal <span class="text-danger">*</span></label>
                                    <input type="number" id="sks" name="sks" class="form-control"
                                        placeholder="Contoh: 24" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="float-right">
                                <a class="btn btn-danger text-white mr-2" id="btn-cancel">
                                    <i class="fas fa-backward mr-2"></i>Batal
                                </a>
                                <a class="btn btn-primary text-white" id="btn-save">
                                    <span class='spinner-border spinner-border-sm mr-2' id='loading-tambah-data'
                                        style='display: none' role='status' aria-hidden='true'></span>
                                    <i class="fas fa-save mr-2"></i>Simpan Data
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="table">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>IPS Min</th>
                                        <th>IPS Max</th>
                                        <th>SKS Maksimal</th>
                                        <th>Status</th>
                                        <th width="15%"><i class="fas fa-th-large"></i></th>
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
@endsection
@section('modal')
@endsection
@push('scripts')
    <script src="{{ asset('adminpage/assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminpage/own-js/admin_akademik/perkuliahan/pengaturan_sks.js') }}"></script>
@endpush
