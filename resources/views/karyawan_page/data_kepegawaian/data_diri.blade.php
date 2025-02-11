@extends('sidebar')
@section('head-css')
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Data Kepegawaian</li>
            <li class="breadcrumb-item active">Data Diri</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Data Diri</h1>
                <small>Halaman ini digunakan untuk melihat data diri masing-masing pegawai</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-4">
        <div class="card mb-4">
            <img src="{{asset('image/bg-01.jpg')}}" alt="..." class="card-img-top" style="max-height: 90px">
            <div class="card-body text-center">
                <a class="avatar avatar-xl card-avatar card-avatar-top mb-5">
                    <img style="width: 150%!important; height: 150%!important;"
                         src="{{asset('files/profil_karyawan/'.$karyawan->id_personal.'/'.$karyawan->path_photo)}}"
                         class="avatar-img rounded-circle border-card"
                         onerror="this.src='{{asset('adminpage/assets/dist/img/avatar-1.jpg')}}'" alt="...">
                </a>
                <h6 class="card-title font-weight-600 mb-2">
                    <a href="#">{{$karyawan->nama_lengkap}}</a>
                </h6>
                <p class="card-text text-muted mb-2">{{$karyawan->status_karyawan == 1 ? $karyawan->nik.'/'.$karyawan->nidn : $karyawan->nik}}</p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-muted">Tempat, Tanggal Lahir</label><br/>
                            <label><b>{{$karyawan->tempat_lahir}}, {{$karyawan->tanggal_lahir_}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-muted">Pendidikan</label><br/>
                            <label><b>{{$karyawan->pendidikan_terakhir}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-muted">Golongan</label><br/>
                            <label><b>{{$karyawan->golongan}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-muted">Alamat</label><br/>
                            <label><b>{{$karyawan->alamat}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-muted">Jabatan Struktural</label><br/>
                            <label><b>@if(empty($karyawan->jabatan_struktural))
                                        - @else {{$karyawan->jabatan_struktural}} @endif</b></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-muted">Jabatan Fungsional</label><br/>
                            <label><b>{{$karyawan->jabatan_fungsional}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-muted">No. HP</label><br/>
                            <label><b>{{$karyawan->no_hp}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-muted">No. KTP</label><br/>
                            <label><b>{{$karyawan->no_ktp}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-muted">Tanggal Aktif</label><br/>
                            <label><b>{{$karyawan->tanggal_masuk}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-muted">Jenis Kelamin</label><br/>
                            <label><b>{{$karyawan->jenis_kelamin}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-muted">Nomor Rekening / Bank</label><br/>
                            <label><b>{{$karyawan->nomor_rekening_dan_nama_bank}}</b></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="float-right">
                    <a href="{{route('karyawan.data_kepegawaian.data_diri.ubah_data_diri')}}" class="btn btn-success">Ubah
                        Data Diri</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
@endsection
@push('scripts')
    <script src="{{asset('adminpage/own-js/karyawan_page/data_kepegawaian/data_diri.js')}}"></script>
@endpush
