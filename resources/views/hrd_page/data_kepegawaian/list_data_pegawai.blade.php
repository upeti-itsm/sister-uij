@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Data Kepegawaian</li>
            <li class="breadcrumb-item active">Data Pegawai</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-users"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Data Pegawai</h1>
                <small>Halaman ini digunakan untuk pengelolaan data kepegawaian</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Data Pegawai</h6>
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
                                            <input type="text" class="form-control"
                                                   placeholder="Cari Nama/NIK/NIDN"
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Filtering</label>
                                    <select class="form-control select2 placeholder-multiple"
                                            id="jenis_karyawan"
                                            multiple="multiple">
                                        <option value="1">Dosen Tetap</option>
                                        <option value="6">Dosen Dpk</option>
                                        <option value="2">Dosen Luar Biasa (LB)</option>
                                        <option value="3">Dosen Praktisi</option>
                                        <option value="4">Karyawan Tetap</option>
                                        <option value="5">Karyawan Kontrak</option>
                                        <option value="7">Tetap Kontrak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">&nbsp;</label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="float-right">
                                                <a href="{{route('hrd.data_kepegawaian.list_data_pegawai.create', ['jenis_karyawan' => 2])}}"
                                                   class="btn btn-info" id="btn-tambah-data"><i
                                                        class="fas fa-user-plus mr-2"></i>Tambah Karyawan
                                                </a>
                                                <a href="{{route('hrd.data_kepegawaian.list_data_pegawai.create', ['jenis_karyawan' => 1])}}"
                                                   class="btn btn-purple" id="btn-tambah-data"><i
                                                        class="fas fa-user-graduate mr-2"></i>Tambah Dosen
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table-daftar-pegawai">
                            <thead>
                            <tr>
                                <th class="text-center">Nomor</th>
                                <th class="text-center">Identitas Pegawai</th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center"><i class="fas fa-th"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
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
    <script src="{{asset('adminpage/own-js/hrd_page/data_kepegawaian/list_data_pegawai.js')}}"></script>
@endpush
