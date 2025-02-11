@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Moodle</li>
            <li class="breadcrumb-item"><a href="{{route('moodle.enrolment.daftar_course')}}">Course</a></li>
            <li class="breadcrumb-item active">Partisipan</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Partisipan</h1>
                <small>Halaman ini digunakan untuk mengelola Partisipan pada mata kuliah di moodle</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Partisipan</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <a id="btn-enrol-dosen" class="btn btn-sm btn-success text-white mr-2"><i
                                    class="fas fa-plus-square"></i> Enrol Dosen</a>
                            <a id="btn-enrol-mahasiswa" class="btn btn-sm btn-danger text-white"><i
                                    class="fas fa-plus-square"></i> Enrol Mahasiswa</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse" id="form-collapse">
                        <div class="form-group">
                            <label class="font-weight-bold">Nama Dosen</label>
                            <select class="select2 form-control" id="add_username">
                                @foreach($dosen AS $item)
                                    <option
                                        value="{{$item->username}}">{{$item->nama_lengkap}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="float-right">
                                <a class="btn btn-danger text-white  mr-2" id="btn-cancel"><i
                                        class="fas fa-backward mr-2"></i>Batal</a>
                                <a class="btn btn-primary text-white" id="btn-save-enrol"><span
                                        class='spinner-border spinner-border-sm mr-2'
                                        id='loading-tambah-data' style='display: none' role='status'
                                        aria-hidden='true'></span><i
                                        class="fas fa-save mr-2"></i>Enrol Dosen</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 collapse" id="form-collapse-mahasiswa">
                        <div class="form-group">
                            <label class="font-weight-bold">Angkatan</label>
                            <select class="select2 form-control" id="angkatan">
                                @foreach($angkatan AS $item)
                                    <option value="{{$item->angkatan}}">{{$item->angkatan}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold mr-2">Nama Mahasiswa <div id="loading_mahasiswa" style="display: none" class="text-danger spinner-border spinner-border-sm" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div></label>
                            <select class="select2 form-control" id="add_mahasiswa">
                                @foreach($mahasiswa AS $item)
                                    <option
                                        value="{{$item->nim}}">{{$item->nama_mahasiswa.' ('.$item->nim.')'}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="float-right">
                                <a class="btn btn-danger text-white  mr-2" id="btn-cancel-mahasiswa"><i
                                        class="fas fa-backward mr-2"></i>Batal</a>
                                <a class="btn btn-primary text-white" id="btn-save-enrol-mahasiswa"><span
                                        class='spinner-border spinner-border-sm mr-2'
                                        id='loading-tambah-data' style='display: none' role='status'
                                        aria-hidden='true'></span><i
                                        class="fas fa-save mr-2"></i>Enrol Mahasiswa</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row">
                            <div class="col-md-12">
                                <address>
                                    <strong>{{$course->fullname}} ({{$course->shortname}})</strong><br>
                                    Dosen Pengampu : <b>{{$course->nama_pengajar}}</b> | Asisten
                                    : <b>@if(is_null($course->nama_asisten))
                                            -- Tidak Ada
                                            --
                                        @else
                                            {{$course->nama_asisten}}
                                        @endif</b>
                                </address>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control"
                                                   placeholder="Cari Berdasarkan Username atau Nama Partisipan"
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
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="font-weight-bold">Filtering</label>
                                    <div class="row">
                                        <div class="col-md-9">
                                            <input type="hidden" id="shortname" value="{{$course->shortname}}">
                                            <select class="select2 form-control" id="role">
                                                <option value="editingteacher">-- Dosen --</option>
                                                <option value="..">-- All --</option>
                                                <option value="student">-- Mahasiswa --</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-block btn-primary" id="btn-filter"><i
                                                    class="fas fa-filter mr-2"></i>Filter
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
                            <thead>
                            <tr>
                                <th class="text-center">Nomor</th>
                                <th>Nama Partuisipan</th>
                                <th>Role</th>
                                <th class="text-center"><i class="fas fa-th"></i></th>
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
    <script src="{{asset('adminpage/own-js/super_admin/moodle/detail_enrolement.js')}}"></script>
@endpush
