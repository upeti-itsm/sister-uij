@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item active">Absen Mengajar</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">QR Mengajar</h1>
                <small>Halaman ini digunakan menampilkan QR untuk absensi masing-masing mahasiswa</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body center-block">
                <div class="center-block">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($rekap->id_rekap) !!}
                </div>
            </div>
            <div class="card-footer">
                <p class="text-info text-center" style="width: 100%">{{$rekap->keterangan}}</p>
                <a class="btn btn-danger-soft"
                   href="{{route('dosen.akademik.absen_mengajar.absensi_ngajar')}}">Kembali</a>
            </div>
        </div>
    </div>
@endsection
@section('modal')
@endsection
@push('scripts')
@endpush
