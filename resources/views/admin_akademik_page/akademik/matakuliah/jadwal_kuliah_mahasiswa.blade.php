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
            <li class="breadcrumb-item active">Sinkronisasi Jadwal Mahasiswa</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Sinkronisasi Jadwal Mahasiswa</h1>
                <small>Halaman ini digunakan untuk mengelola sinkronisasi jadwal kuliah mahasiswa antara sipadu dan
                    siakad</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <input type="hidden" id="hak_akses"
           value="{{\Illuminate\Support\Facades\Session::get('modul')['Sinkronisasi Jadwal Mahasiswa dengan Siakad']}}">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Jadwal Mahasiswa</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <button class="btn btn-danger-soft btn-sync-ulang-jadwal-kuliah mr-2"
                                    id="btn-sync-ulang-jadwal-kuliah"
                                    title="Syncron Dengan Siakad"><i
                                    class="fas fa-cloud-download-alt"></i> Synchron Dengan Siakad
                            </button>
                            <button class="btn btn-danger-soft btn-sync-ulang-jadwal-kuliah mr-2"
                                    id="btn-sync-ulang-jadwal-kuliah-by-nim"
                                    title="Syncron Dengan Siakad"><i
                                    class="fas fa-cloud-download-alt"></i> Synchron By NIM
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mt-3" id="progress-bar-syncron-ulang-jadwal-kuliah" style="display: none">
                        <button class="btn btn-primary mr-1 mb-2" type="button" disabled="">
                            <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"
                                  id="loading-progress-jadwal-kuliah"></span>
                            <span id="keterangan-progress-jadwal-kuliah">Mohon menunggu hingga proses sinkronisasi selesai ...</span>
                        </button>
                        <button class="btn btn-danger-soft mr-1 mb-2" id="btn-cancel-syncron-ulang-jadwal-kuliah"><i
                                class="fas fa-window-close mr-2"></i>Batal
                        </button>
                        <div class="progress progress-lg mb-3">
                            <div
                                class="progress-bar progress-bar-violet progress-bar-striped progress-bar-animated"
                                role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                style="width: 0" id="progress-bar-jadwal-kuliah">
                                <span id="progress-text-jadwal-kuliah"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3" id="log-syncron-ulang-jadwal-kuliah" style="display: none">
                        <div class="card bg-light">
                            <div class="card-header bg-info text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fs-17 font-weight-600 mb-0">Log Sinkronisasi</h6>
                                    </div>
                                    <div class="text-right">
                                        <div class="actions">
                                            <button class="btn btn-primary-soft mr-3 text-white"
                                                    id="btn-failed-log-jadwal-kuliah">
                                                Failed : 0
                                            </button>
                                            <button class="btn btn-primary-soft mr-3 text-white"
                                                    id="btn-inserted-log-jadwal-kuliah">
                                                Inserted : 0
                                            </button>
                                            <button class="btn btn-primary-soft mr-3 text-white"
                                                    id="btn-updated-log-jadwal-kuliah">
                                                Updated : 0
                                            </button>
                                            <button class="action-item text-white" title="Tutup Log"
                                                    id="btn-tutup-log-jadwal-kuliah">
                                                <i class="fas fa-times-circle"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control"
                                                   placeholder="Masukkan Nama Matakuliah" id="cari-log-jadwal-kuliah">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" id="btn-cari-log-jadwal-kuliah"><i
                                                        class="fas fa-search mr-2"></i>Cari
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select class="form-control select2" id="status-log-jadwal-kuliah">
                                                <option value="">-- All Of Them --</option>
                                                <option value="inserted">-- Inserted --</option>
                                                <option value="updated">-- Updated --</option>
                                                <option value="failed">-- Failed --</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover"
                                           id="log-table-jadwal-kuliah">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%">Nomor</th>
                                            <th style="width: 80%">Nama Matakuliah - (Kelas)</th>
                                            <th style="width: 15%">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody id="log-table-tbody-jadwal-kuliah">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 collapse show" id="filter-collapse-jadwal-kuliah">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control"
                                                   placeholder="Cari Nama Matakuliah/NIM"
                                                   id="cari-data-jadwal-kuliah">
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-block btn-primary" id="btn-cari-data-jadwal-kuliah">
                                                <i
                                                    class="fas fa-search mr-2"></i>Cari Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="font-weight-bold">Filtering</label>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <select class="select2 form-control" id="tahun_akademik">
                                                @if(sizeof($tahun_akademik) > 0)
                                                    @foreach($tahun_akademik AS $item)
                                                        <option
                                                            value="{{$item->tahun_akademik}}">{{$item->tahun_akademik}}</option>
                                                    @endforeach
                                                @else
                                                    <option value="all">-- Tidak Ada Data --</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-7">
                                            <select class="select2 form-control" id="prodi">
                                                <option value="all">-- Semua Prodi --</option>
                                                @foreach($program_studi AS $item)
                                                    <option
                                                        value="{{$item->kd_program_studi}}">{{$item->nama_program_studi.' ('.$item->jenjang_didik.')'}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table-jadwal-kuliah">
                            <thead>
                            <tr>
                                <th class="text-center">Nomor</th>
                                <th>NIM</th>
                                <th>Mata Kuliah</th>
                                <th>SKS</th>
                                <th>Hari (Jam)</th>
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
    <div class="modal modal-primary fade" id="modal-sync-jadwal-kuliah" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600">Konfirmasi Sinkronisasi Jadwal Mahasiswa</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Jika dilakukan proses sinkronisasi, jadwal kuliah yang ada di siakad akan di import ke
                        sipadu.itsm.ac.id, silahkan pilih tahun akademik untuk proses sinkronisasi
                        jadwal kuliah</p>
                    <div class="form-group">
                        <select class="select2 form-control" id="tahun_akademik_sync-jadwal-kuliah">
                            @foreach($tahun_akademik_siakad AS $item)
                                <option value="{{$item->tahun_akademik}}">{{$item->nama_tahun_akademik}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="modal-btn-sync-jadwal-kuliah"><i
                            class="fas fa-sync mr-2"></i>Sinkronisasi Jadwal Mahasiswa
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-primary fade" id="modal-sync-jadwal-kuliah-by-nim" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600">Konfirmasi Sinkronisasi Jadwal Mahasiswa</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Jika dilakukan proses sinkronisasi, jadwal kuliah yang ada di siakad akan di import ke
                        sipadu.itsm.ac.id, silahkan masukkan nim dan pilih tahun akademik untuk proses sinkronisasi
                        jadwal kuliah</p>
                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" id="nim_sync-jadwal-kuliah-nim" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Tahun Akademik</label>
                        <select class="select2 form-control" id="tahun_akademik_sync-jadwal-kuliah-nim">
                            @foreach($tahun_akademik_siakad AS $item)
                                <option value="{{$item->tahun_akademik}}">{{$item->nama_tahun_akademik}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="modal-btn-sync-jadwal-kuliah-nim"><i
                            class="fas fa-sync mr-2"></i>Sinkronisasi Jadwal Mahasiswa
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script
        src="{{asset('adminpage/own-js/admin_akademik/akademik/matakuliah/jadwal_kuliah_mahasiswa.js')}}"></script>
@endpush
