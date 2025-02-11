@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Perkuliahan</li>
            <li class="breadcrumb-item active">Konsentrasi Jurusan</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Konsentrasi Jurusan</h1>
                <small>Halaman ini digunakan untuk mengelola Konsentrasi Jurusan yang ditawarkan pada masing-masing
                    Program Studi</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Konsentrasi</h6>
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Pencarian</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" class="form-control" placeholder="Cari Nama Konsentrasi"
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Filtering</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <select class="form-control select2" name="kd_prodi" id="kd_prodi">
                                        <option value="0">-- Semua Program Studi --</option>
                                        @foreach($program_studi as $item)
                                            <option
                                                value="{{$item->kd_program_studi}}">{{$item->nama_program_studi}}</option>
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
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="table">
                                <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Nama Konsentrasi Jurusan</th>
                                    <th>Keterangan</th>
                                    <th><i class="fas fa-th-large"></i></th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Nama Konsentrasi Jurusan</th>
                                    <th>Keterangan</th>
                                    <th><i class="fas fa-th-large"></i></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form style="display: none" action="{{route('konsentrasi_jurusan.delete')}}" method="POST" id="delete-form">
        @csrf
        <input type="hidden" id="delete-id_konsentrasi_jurusan" name="id">
    </form>
@endsection
@section('modal')
    <div class="modal modal-primary fade" id="modal-insup-konsentrasi-jurusan" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600" id="insupLabel"></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('konsentrasi_jurusan.insup')}}" method="POST" id="insup-form">
                        @csrf
                        <input type="hidden" id="insup-id_konsentrasi_jurusan" name="id"
                               value="00000000-0000-0000-0000-000000000000">
                        <div class="form-group">
                            <label>Program Studi</label>
                            <select class="form-control select2" name="kd_prodi" id="insup-kd_prodi" required>
                                <option value="">-- Pilih Program Studi --</option>
                                @foreach($program_studi as $item)
                                    <option value="{{$item->kd_program_studi}}">{{$item->nama_program_studi}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nama Konsentrasi Jurusan</label>
                            <input type="text" name="nama_konsentrasi" class="form-control"
                                   placeholder="Masukkan Nama Konsentrasi Jurusan" id="insup-nama_konsentrasi" required>
                        </div>
                        <div class="form-group">
                            <label>Tahun Dibuka</label>
                            <input type="number" name="tahun_dibuka" class="form-control" id="insup-tahun_dibuka"
                                   required
                                   placeholder="Masukkan Tahun Dibuka">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="btn-simpan">Simpan Data</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/admin_akademik/perkuliahan/konsentrasi_jurusan.js')}}"></script>
@endpush
