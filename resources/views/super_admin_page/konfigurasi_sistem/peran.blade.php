@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Konfigurasi Sistem</li>
            <li class="breadcrumb-item active">Peran</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Peran</h1>
                <small>Halaman ini digunakan untuk mengelola data Peran pada setiap aplikasi yang ada</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Peran</h6>
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
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" placeholder="Cari Nama Peran"
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
                                        <div class="col-md-5">
                                            <select class="select2 form-control" id="id_aplikasi">
                                                <option value="00000000-0000-0000-0000-000000000000">-- Semua Aplikasi
                                                    --
                                                </option>
                                                @foreach($aplikasi AS $item)
                                                    <option
                                                        value="{{$item->id_aplikasi}}">{{$item->nama_aplikasi}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="select2 form-control" id="kd_kelompok_peran">
                                                <option value="..">-- Semua Kelompok Peran
                                                    --
                                                </option>
                                                @foreach($kelompok_peran AS $item)
                                                    <option
                                                        value="{{$item->kd_kelompok_peran}}">{{$item->kelompok_peran}}</option>
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
                    <div class="col-md-12 collapse" id="form-collapse">
                        <input type="hidden" id="id_peran">
                        <div class="form-group">
                            <label class="font-weight-bold">Nama Aplikasi</label>
                            <select class="select2 form-control" id="id_aplikasi_add">
                                <option value="00000000-0000-0000-0000-000000000000">-- Pilih Aplikasi
                                    --
                                </option>
                                @foreach($aplikasi AS $item)
                                    <option
                                        value="{{$item->id_aplikasi}}">{{$item->nama_aplikasi}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Kelompok Peran</label>
                            <select class="select2 form-control" id="kd_kelompok_peran_add">
                                <option value="..">-- Pilih Kelompok Peran
                                    --
                                </option>
                                @foreach($kelompok_peran AS $item)
                                    <option
                                        value="{{$item->kd_kelompok_peran}}">{{$item->kelompok_peran}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Nama Peran</label>
                            <input type="text" class="form-control" placeholder="Masukkan Nama Peran"
                                   id="nama_peran">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Keterangan</label>
                            <textarea class="form-control" id="keterangan"
                                      placeholder="Masukkan Keterangan Peran"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="float-right">
                                <a class="btn btn-danger text-white  mr-2" id="btn-cancel"><i
                                        class="fas fa-backward mr-2"></i>Batal</a>
                                <a class="btn btn-primary text-white" id="btn-save-peran"><span
                                        class='spinner-border spinner-border-sm mr-2'
                                        id='loading-tambah-data' style='display: none' role='status'
                                        aria-hidden='true'></span><i
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
                                    <th>Nama Peran (Kelompok Peran)</th>
                                    <th>Keterangan</th>
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
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/super_admin/konfigurasi_sistem/peran.js')}}"></script>
@endpush
