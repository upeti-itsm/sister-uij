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
            <li class="breadcrumb-item active">Detail Peran Pengguna</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Detail Peran Pengguna</h1>
                <small>Halaman ini digunakan untuk menampilkan detail peran apa saja yang dapat di akses oleh pengguna yang dikelola</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Peran Pengguna</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nama Karyawan</label>
                            <input type="hidden" id="id_personal" value="{{$personal->id_personal}}">
                            <input type="hidden" id="id_aplikasi" value="{{$aplikasi->id_aplikasi}}">
                            <input type="text" readonly value="{{$personal->nama_lengkap}}" class="form-control" id="nama_personal">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Unit Kerja</label>
                            <input type="text" readonly value="{{$personal->unit_kerja}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Aplikasi</label>
                            <input type="text" readonly value="{{$aplikasi->nama_aplikasi}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr/>
                    </div>
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" placeholder="Cari Nama Modul"
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
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="font-weight-bold">Penambahan Data</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select class="form-control select2" id="peran">
                                                @foreach($peran AS $item)
                                                    <option value="{{$item->id_peran}}">{{$item->nama_peran}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-control select2" id="is_default">
                                                <option value="0">Non-Default</option>
                                                <option value="1">Deafult</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-block btn-primary" id="btn-tambah-data"><span class='spinner-border spinner-border-sm mr-2' id='loading-spin-tambah-data' style='display: none' role='status' aria-hidden='true'></span><i
                                                    class="fas fa-user-plus mr-2"></i>Tambah Peran
                                            </button>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="{{route('peran_pengguna.index')}}" class="btn btn-block btn-danger" id="btn-kembali"><i
                                                    class="fas fa-backward mr-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Nomor</th>
                                    <th class="text-left">Nama Peran</th>
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
    </div>
@endsection
@section('modal')
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/super_admin/konfigurasi_sistem/detail_peran_pengguna.js')}}"></script>
@endpush
