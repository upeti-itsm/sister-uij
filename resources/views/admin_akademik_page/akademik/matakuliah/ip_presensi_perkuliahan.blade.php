@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Perkuliahan</li>
            <li class="breadcrumb-item active">{{ $menu }}</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">IP Presensi</h1>
                <small>Halaman ini digunakan untuk mengelola ip presensi untuk perkuliahan mahasiswa.</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">
                            Daftar Pengaturan IP Presensi
                        </h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <a id="btn-tambah-data" class="btn btn-sm btn-success text-white">
                                <i class="fas fa-plus-square mr-2"></i> Tambah IP Presensi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" placeholder="Cari Alamat IP"
                                                id="cari-data">
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-block btn-primary" id="btn-cari-data">
                                                <i class="fas fa-search mr-2"></i>Cari Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        Filter Status
                                    </label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <select class="select2 form-control" id="status">
                                                <option></option>
                                                <option value="1">Aktif</option>
                                                <option value="0">Tidak Aktif</option>
                                            </select>
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
                                        <th>No</th>
                                        <th>Alamat IP</th>
                                        <th>Status</th>
                                        <th>Ditambahkan Pada Tanggal</th>
                                        <th><i class="fas fa-th-large"></i></th>
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

    <form style="display: none" action="{{ route('admin_akademik.akademik.jadwal_kuliah.ip_presensi_perkuliahan.delete') }}"
        method="POST" id="delete-form">
        @csrf
        <input type="hidden" id="delete-id_ip_address_presensi_perkuliahan" name="id">
    </form>
@endsection
@section('modal')
    <div class="modal modal-primary fade" id="modal-insup-ip-presensi" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600" id="insupLabel"></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin_akademik.akademik.jadwal_kuliah.ip_presensi_perkuliahan.store_update') }}"
                        method="POST" id="insup-form">
                        @csrf
                        <input type="hidden" id="id_ip_address_presensi_perkuliahan" name="id">
                        <div class="form-group">
                            <label>Alamat IP</label>
                            <input type="text" id="alamat_ip" name="alamat_ip" class="form-control"
                                placeholder="Masukkan Alamat IP" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="select2 form-control" id="sts_aktif" name="sts_aktif">
                                <option></option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="btn-simpan">Simpan Data</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('adminpage/assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminpage/own-js/admin_akademik/akademik/matakuliah/ip_presensi_perkuliahan.js') }}"></script>
@endpush
