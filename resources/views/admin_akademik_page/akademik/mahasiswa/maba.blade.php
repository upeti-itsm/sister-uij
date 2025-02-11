@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/bootstrap-toogle/css/bootstrap-toogle.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Mahasiswa Baru</li>
            <li class="breadcrumb-item active">@if($jenis == 1)
                    Reguler
                @else
                    Transfer
                @endif</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-users"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">@if($jenis == 1)
                        Mahasiswa Baru Reguler
                    @else
                        Mahasiswa Baru Transfer
                    @endif</h1>
                <small>Halaman ini digunakan untuk menampilkan data @if($jenis == 1)
                        Mahasiswa Baru Reguler
                    @else
                        Mahasiswa Baru Transfer
                    @endif pada Tahun {{$tahun}}</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <input type="hidden" id="hak_akses" value="{{\Illuminate\Support\Facades\Session::get('modul')['Student Body']}}">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar @if($jenis == 1)
                                Mahasiswa Baru Reguler
                            @else
                                Mahasiswa Baru Transfer
                            @endif</h6>
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
                                            <input type="text" class="form-control" placeholder="Cari Nama Camaba"
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
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <thead>
                            <tr>
                                <th class="text-center">Nomor</th>
                                <th class="text-center">NIM</th>
                                <th class="text-center">Nama Mahasiswa</th>
                                <th class="text-center">Jenis Kelamin</th>
                                <th class="text-center">Prodi</th>
                                <th class="text-center">Kelas</th>
                                <th class="text-center">Angkatan</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($no=1)
                            @foreach($mahasiswa AS $item)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{$item->nama_pendaftar}}</td>
                                    <td>{{$item->jenis_kelamin}}</td>
                                    <td>{{$item->kewarganegaraan}}</td>
                                </tr>
                            @endforeach
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
    <script src="{{asset('adminpage/assets/plugins/bootstrap-toogle/js/bootstrap-toogle.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/admin_akademik/akademik/mahasiswa/maba.js')}}"></script>
@endpush
