@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Sinkronisasi Data</li>
            <li class="breadcrumb-item active">Program Studi</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-th-large"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Program Studi</h1>
                <small>Halaman ini digunakan untuk mengelola data program studi (Syncronisasi dengan
                    feeder.stie-mandala.ac.id)</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Program Studi</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <button class="btn btn-info-soft btn-sync-ulang" id="btn-sync-ulang" title="Syncron Dengan Feeder"><i
                                    class="fas fa-sync"></i> Syncron Ulang Program Studi</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold">Pencarian</label>
                            <div class="row">
                                <div class="col-md-10">
                                    <input type="text" class="form-control" placeholder="Cari Nama Konsentrasi"
                                           id="cari-data">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-block btn-primary" id="btn-cari-data"><i
                                            class="fas fa-search mr-2"></i>Cari Data
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
                                    <th>Nama Program Studi</th>
                                    <th>Keterangan</th>
                                    <th><i class="fas fa-th-large"></i></th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Nama Program Studi</th>
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
@endsection
@section('modal')
    <div class="modal modal-primary fade" id="modal-check-feeder" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600">Perbandingan Data Feeder</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modal-check-id_program_studi">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="text-center">Atribut</th>
                            <th class="text-center">SIPADU</th>
                            <th class="text-center">FEEDER</th>
                            <th class="text-center"><i class="fas fa-th-large"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Kode Program Studi</td>
                            <td><span id="modal-check-kd_prodi_sipadu"></span></td>
                            <td><span id="modal-check-kd_prodi_feeder"></span></td>
                            <td><span id="modal-check-perbandingan_kd_prodi"></span></td>
                        </tr>
                        <tr>
                            <td>Nama Program Studi</td>
                            <td><span id="modal-check-nama_prodi_sipadu"></span></td>
                            <td><span id="modal-check-nama_prodi_feeder"></span></td>
                            <td><span id="modal-check-perbandingan_nama_prodi"></span></td>
                        </tr>
                        <tr>
                            <td>Jenjang Pendidikan</td>
                            <td><span id="modal-check-jenjang_didik_sipadu"></span></td>
                            <td><span id="modal-check-jenjang_didik_feeder"></span></td>
                            <td><span id="modal-check-perbandingan_jenjang_didik"></span></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td><span id="modal-check-status_sipadu"></span></td>
                            <td><span id="modal-check-status_feeder"></span></td>
                            <td><span id="modal-check-perbandingan_status"></span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="modal-check-btn-sync"><i
                            class="fas fa-sync mr-2"></i>Syncron Ulang
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/admin_akademik/sinkronisasi_data/program_studi.js')}}"></script>
@endpush
