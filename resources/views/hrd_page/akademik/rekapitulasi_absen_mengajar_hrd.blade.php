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
            <li class="breadcrumb-item active">Rekapitulasi Absen Mengajar</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Rekapitulasi Absen Mengajar</h1>
                <small>Halaman ini digunakan monitoring data absensi mengajar dosen oleh HRD</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Rekapitulasi Absen Mengajar</h6>
                        <small class="text-danger">* Berdasarkan tanggal pengisian absensi</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" readonly class="form-control" id="tgl_awal"
                                               style="cursor: pointer" title="Pilih Tanggal Awal Rekap">
                                    </div>
                                    <div class="col-md-1">Sampai Dengan</div>
                                    <div class="col">
                                        <input type="text" readonly class="form-control" id="tgl_akhir"
                                               style="cursor: pointer" title="Pilih Tanggal Akhir Rekap">
                                    </div>
                                </div>
                                <hr/>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" placeholder="Cari Nama Dosen"
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Exporting</label>
                                    <button class="btn btn-md btn-success" id="btn-export-excel"><i
                                            class="fas fa-file-excel mr-2"></i>Export to Excel
                                    </button>
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
                                <th class="text-center" style="vertical-align: middle" rowspan="2">Nomor</th>
                                <th rowspan="2" style="vertical-align: middle">Nama Dosen</th>
                                <th colspan="2" style="vertical-align: middle" class="text-center">Jumlah Absensi</th>
                                <th class="text-center" rowspan="2" style="vertical-align: middle"><i
                                        class="fas fa-th"></i></th>
                            </tr>
                            <tr>
                                <td class="text-center" style="font-weight: bold">Reguler Pagi</td>
                                <td class="text-center" style="font-weight: bold">Reguler Malam</td>
                            </tr>
                            </thead>
                            <tbody></tbody>
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
    <script src="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.id.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/hrd_page/akademik/rekapitulasi_absen_mengajar_hrd.js')}}"></script>
@endpush
