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
            <li class="breadcrumb-item">Rekap SDM</li>
            <li class="breadcrumb-item active">Sertifikasi Dosen</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-users"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Sertifikasi Dosen</h1>
                <small>Halaman ini digunakan untuk menampilkan Sertifikasi Dosen (Pendidik
                    Profesional/Profesi/Industri/Kompetensi) yang masih berlaku dalam 3 tahun terakhir</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <input type="hidden" id="hak_akses" value="{{\Illuminate\Support\Facades\Session::get('modul')['Rekap SDM']}}">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Sertifikasi Dosen Institut Teknologi dan Sains
                            Mandala</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row">
                            <div class="col-md-5" style="display: none">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" placeholder="Cari Nama Mahasiswa"
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
                            <thead style="font-weight: bold">
                            <tr>
                                <td style="text-align: center; vertical-align: middle">Nomor</td>
                                <td style="text-align: center; vertical-align: middle">Unit Pengelola<br/>(Fakultas/Departemen/Jurusan)
                                </td>
                                <td style="text-align: center; vertical-align: middle">Jumlah Dosen</td>
                                <td style="text-align: center; vertical-align: middle">Jumlah Dosen Bersertifikat</td>
                            </tr>
                            </thead>
                            <tbody>
                            @for($i = 0; $i < sizeof($dosen); $i++)
                                @if($i < sizeof($dosen) - 1)
                                    <tr>
                                        <td style="text-align: center">{{$dosen[$i]->nomor}}</td>
                                        <td>{{$dosen[$i]->nama_program_studi}}</td>
                                        <td style="text-align: center"><a
                                                href="/hrd/data-kepegawaian/rekap-sdm/detail-dosen/serdos/{{$dosen[$i]->id_program_studi}}/1">{{$dosen[$i]->jml_dosen}}</a>
                                        </td>
                                        <td style="text-align: center"><a
                                                href="/hrd/data-kepegawaian/rekap-sdm/detail-dosen/serdos/{{$dosen[$i]->id_program_studi}}/2">{{$dosen[$i]->jml_dosen_sertifikasi}}</a>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="2" style="text-align: center; font-weight: bold">Total</td>
                                        <td style="text-align: center; font-weight: bold"><a
                                                href="/hrd/data-kepegawaian/rekap-sdm/detail-dosen/serdos/00000000-0000-0000-0000-000000000000/1">{{$dosen[$i]->jml_dosen}}</a>
                                        </td>
                                        <td style="text-align: center; font-weight: bold"><a
                                                href="/hrd/data-kepegawaian/rekap-sdm/detail-dosen/serdos/00000000-0000-0000-0000-000000000000/2">{{$dosen[$i]->jml_dosen_sertifikasi}}</a>
                                        </td>
                                    </tr>
                                @endif
                            @endfor
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
@endpush
