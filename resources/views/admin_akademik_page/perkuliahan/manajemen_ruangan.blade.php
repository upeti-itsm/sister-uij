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
            <li class="breadcrumb-item active">Kurikulum</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Kurikulum</h1>
                <small>Halaman ini digunakan untuk mengelola data kurikulum pada masing-masing program studi</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Kurikulum</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <a id="btn-tambah-data" class="btn btn-sm btn-success text-white"><i
                                    class="fas fa-plus-square"></i> Tambah
                                Data</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" placeholder="Cari Nama Kurikulum"
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
                                            <select class="select2 form-control" id="kd_prodi">
                                                <option>- Semua Status -</option>
                                                <option value="1">Aktif</option>
                                                <option value="0">Tidak Aktif</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-block btn-primary" id="btn-filter">
                                                <i class="fas fa-filter mr-2"></i>Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 collapse" id="form-collapse">
                        <input type="hidden" id="id_kurikulum">
                        <div class="form-group">
                            <label class="font-weight-bold">Program Studi</label>
                            <select class="select2 form-control" id="id_prodi_add">
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Nama Kurikulum</label>
                            <input type="text" class="form-control" placeholder="Masukkan Nama Kurikulum"
                                id="nama_kurikulum">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Tahun Kurikulum</label>
                            <input type="text" maxlength="4" minlength="4" class="form-control"
                                placeholder="Masukkan Tahun Kurikulum" id="tahun_kurikulum">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">SKS Lulus</label>
                            <input type="number" min="0" class="form-control" placeholder="Masukkan SKS Lulus"
                                id="sks_lulus">
                        </div>
                        <div class="form-group">
                            <div class="float-right">
                                <a class="btn btn-danger text-white  mr-2" id="btn-cancel"><i
                                        class="fas fa-backward mr-2"></i>Batal</a>
                                <a class="btn btn-primary text-white" id="btn-save"><span
                                        class='spinner-border spinner-border-sm mr-2' id='loading-tambah-data'
                                        style='display: none' role='status' aria-hidden='true'></span><i
                                        class="fas fa-save mr-2"></i>Simpan Data</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="table">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Kode</th>
                                        <th>Ruang Perkuliahan</th>
                                        <th>Kapasitas</th>
                                        <th>Informasi Kelas</th>
                                        <th>Status</th>
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
@endsection
@section('modal')
@endsection
@push('scripts')
    <script src="{{ asset('adminpage/assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminpage/own-js/admin_akademik/akademik/manajemen_ruangan.js') }}"></script>
@endpush
