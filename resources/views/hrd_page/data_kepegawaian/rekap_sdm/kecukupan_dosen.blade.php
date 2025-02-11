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
            <li class="breadcrumb-item active">Kecukupan Dosen PT</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-users"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Kecukupan Dosen PT</h1>
                <small>Halaman ini digunakan untuk menampilkan kecukupan Dosen pada ITS Mandala</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Kecukupan Dosen Institut Teknologi dan Sains Mandala</h6>
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
                                <td rowspan="2" style="text-align: center; vertical-align: middle">No</td>
                                <td rowspan="2" style="text-align: center; vertical-align: middle">Unit Pengelola<br/>(Fakultas/Departemen/Jurusan)
                                </td>
                                <td colspan="3" style="text-align: center; vertical-align: middle">Pendidikan
                                    Tertinggi
                                </td>
                                <td rowspan="2" style="text-align: center; vertical-align: middle">Jumlah</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; vertical-align: middle">Doktor/Doktor Terapan/Sub
                                    Spesialis
                                </td>
                                <td style="text-align: center; vertical-align: middle">Magister/Magister
                                    Terapan/Spesialis
                                </td>
                                <td style="text-align: center; vertical-align: middle">Profesi</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dosen AS $item)
                                <tr>
                                    <td style="text-align: center">{{$item->nomor}}</td>
                                    <td>{{$item->nama_program_studi}}</td>
                                    <td style="text-align: center"><a
                                            href="/hrd/data-kepegawaian/rekap-sdm/detail-dosen/kecukupan/{{$item->id_program_studi}}/1">{{$item->jml_doktor}}</a>
                                    </td>
                                    <td style="text-align: center"><a
                                            href="/hrd/data-kepegawaian/rekap-sdm/detail-dosen/kecukupan/{{$item->id_program_studi}}/2">{{$item->jml_magister}}</a>
                                    </td>
                                    <td style="text-align: center"><a
                                            href="/hrd/data-kepegawaian/rekap-sdm/detail-dosen/kecukupan/{{$item->id_program_studi}}/3">{{$item->jml_profesi}}</a>
                                    </td>
                                    <td style="text-align: center"><a
                                            href="/hrd/data-kepegawaian/rekap-sdm/detail-dosen/kecukupan/{{$item->id_program_studi}}/0">{{$item->jumlah_baris}}</a>
                                    </td>
                                </tr>
                            @endforeach
                            @if(sizeof($dosen) > 0)
                                <tr>
                                    <td colspan="2" style="text-align: center; font-weight: bold">Total</td>
                                    <td style="text-align: center; font-weight: bold"><a href="/hrd/data-kepegawaian/rekap-sdm/detail-dosen/kecukupan/00000000-0000-0000-0000-000000000000/1">{{$dosen[0]->total_doktor}}</a>
                                    </td>
                                    <td style="text-align: center; font-weight: bold"><a
                                            href="/hrd/data-kepegawaian/rekap-sdm/detail-dosen/kecukupan/00000000-0000-0000-0000-000000000000/2">{{$dosen[0]->total_magister}}</a>
                                    </td>
                                    <td style="text-align: center; font-weight: bold"><a
                                            href="/hrd/data-kepegawaian/rekap-sdm/detail-dosen/kecukupan/00000000-0000-0000-0000-000000000000/3">{{$dosen[0]->total_profesi}}</a>
                                    </td>
                                    <td style="text-align: center; font-weight: bold"><a
                                            href="/hrd/data-kepegawaian/rekap-sdm/detail-dosen/kecukupan/00000000-0000-0000-0000-000000000000/0">{{$dosen[0]->jml_akhir_dosen}}</a>
                                    </td>
                                </tr>
                            @endif
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
