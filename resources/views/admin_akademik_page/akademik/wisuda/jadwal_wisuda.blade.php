@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/animate-css/animate.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Perkuliahan</li>
            <li class="breadcrumb-item active">Jadwal Wisuda</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Jadwal Wisuda</h1>
                <small>Halaman ini digunakan untuk mengelola jadwal wisuda yang diselenggarakan Mandala</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Jadwal Wisuda</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <a id="btn-tambah-data" class="btn btn-sm btn-success text-white"><i
                                    class="fas fa-plus-square"></i> Tambah
                                Jadwal</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Filtering</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <select class="select2 form-control" id="tahun_pelaksanaan_filter">
                                                <option value="all">-- Semua Tahun Pelaksanaan
                                                    --
                                                </option>
                                                @foreach($tahun_pelaksanaan AS $item)
                                                    <option
                                                        value="{{$item->tahun_pelaksanaan}}">{{$item->tahun_pelaksanaan}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
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
                        <input type="hidden" id="id">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Periode</label>
                                    <select class="select2 form-control" id="periode">
                                        <option value="I">Periode I</option>
                                        <option value="II">Periode II</option>
                                        <option value="III">Periode III</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Kuota</label>
                                    <input type="text" value="150" placeholder="Masukkan Kuota Wisuda" id="kuota" name="kuota"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="font-weight-bold">Tanggal Pelaksanaan</label>
                                    <input type="text" placeholder="Tanggal Pelaksanaan Wisuda"
                                           id="tgl_pelaksanaan"
                                           readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12 animate__animated" style="display: none" id="pendaftaran-area">
                                <hr/>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Tanggal Pendaftaran Dibuka</label>
                                            <input type="text" placeholder="Tanggal Pendaftaran Dibuka"
                                                   id="tgl_pendaftaran_dibuka" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Tanggal Pendaftaran Ditutup</label>
                                            <input type="text" placeholder="Tanggal Pendaftaran Ditutup"
                                                   id="tgl_pendaftaran_ditutup" readonly class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="float-right">
                                        <a class="btn btn-danger text-white  mr-2" id="btn-cancel"><i
                                                class="fas fa-backward mr-2"></i>Batal</a>
                                        <a class="btn btn-secondary text-white disabled" id="btn-save-jadwal"><span
                                                class='spinner-border spinner-border-sm mr-2'
                                                id='loading-tambah-data' style='display: none' role='status'
                                                aria-hidden='true'></span><i
                                                class="fas fa-save mr-2"></i>Simpan Data</a>
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
                                    <th>Nomor</th>
                                    <th>Keterangan</th>
                                    <th>Peserta/Kuota</th>
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
    <script src="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.id.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/numeral/numeral.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/admin_akademik/akademik/wisuda/jadwal_wisuda.js')}}"></script>
@endpush
