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
            <li class="breadcrumb-item active">Rasio Dosen</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-users"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Rasio Dosen</h1>
                <small>Halaman ini digunakan untuk menampilkan Rasio Dosen terhadap mahasiswa</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Rasio Dosen Institut Teknologi dan Sains Mandala</h6>
                    </div>
                </div>
            </div>
            <div class="card-body row">
                <div class="col-md-4">
                    <div class="collapse show" id="filter-collapse">
                        <div class="form-group">
                            <label class="font-weight-bold">Tahun Akademik (TA)</label>
                            <select class="select2 form-select" id="tahun_akademik">
                                @foreach($tahun_akademik AS $item)
                                    <option value="{{$item->angkatan}}">{{$item->angkatan}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <thead style="font-weight: bold">
                            <tr>
                                <td style="text-align: center; vertical-align: middle">Nomor</td>
                                <td style="text-align: center; vertical-align: middle">Unit Pengelola
                                    (Fakultas/Departemen/Jurusan)
                                </td>
                                <td style="text-align: center; vertical-align: middle">Jumlah Dosen</td>
                                <td style="text-align: center; vertical-align: middle">Jumlah Mahasiswa</td>
                                <td style="text-align: center; vertical-align: middle">Jumlah Mahasiswa TA</td>
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
    <script src="{{asset('adminpage/assets/plugins/bootstrap-toogle/js/bootstrap-toogle.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/hrd_page/rekap_sdm/rasio_dosen.js')}}"></script>
@endpush
