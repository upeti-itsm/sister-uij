@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Plotting</li>
            <li class="breadcrumb-item active">Plotting Perkuliahan</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-calendar-alt"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Plotting Perkuliahan</h1>
                <small>Halaman ini digunakan untuk mengelola plotting mata kuliah dan dosen pengampu</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Plotting Perkuliahan</h6>
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
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Program Studi <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="filter_kd_prodi" style="width: 100%">
                                        <option value="">Pilih Program Studi</option>
                                        @foreach($program_studi as $prodi)
                                            <option value="{{$prodi->kd_program_studi}}">{{$prodi->nama_program_studi}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Tahun Akademik <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="filter_tahun_akademik" style="width: 100%">
                                        <option value="">Pilih Tahun Akademik</option>
                                        @foreach($tahun_akademik as $item)
                                            <option value="{{$item->tahun_akademik.$item->id_semester_uij}}">{{$item->tahun_akademik." ".$item->nama_tahun_akademik}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" placeholder="Cari Mata Kuliah atau Dosen"
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
                    <div class="col-md-12 collapse" id="form-collapse">
                        <input type="hidden" value="" id="id_plotting">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Program Studi <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="kd_prodi" style="width: 100%" required>
                                        <option value="">Pilih Program Studi</option>
                                        @foreach($program_studi as $prodi)
                                            <option value="{{$prodi->kd_program_studi}}">{{$prodi->nama_program_studi}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Tahun Akademik <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="tahun_akademik" style="width: 100%" required>
                                        <option value="">Pilih Tahun Akademik</option>
                                        @foreach($tahun_akademik as $item)
                                            <option value="{{$item->tahun_akademik.$item->id_semester_uij}}">{{$item->tahun_akademik." ".$item->nama_tahun_akademik}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Kurikulum <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="id_kurikulum" style="width: 100%" required>
                                        <option value="">Pilih Kurikulum</option>
                                        @foreach($kurikulum as $item)
                                            <option value="{{$item->id_kurikulum}}">{{$item->nama_kurikulum}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Mata Kuliah <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="id_matakuliah" style="width: 100%" required>
                                        <option value="">Pilih Mata Kuliah</option>
                                        @foreach($matakuliah as $item)
                                            <option value="{{$item->id_matakuliah}}">{{$item->kd_matakuliah}} - {{$item->nama_matakuliah}} ({{$item->sks}} SKS)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Dosen Pengampu <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="id_karyawan" style="width: 100%" required>
                                        <option value="">Pilih Dosen</option>
                                        @foreach($dosen as $item)
                                            <option value="{{$item->id_karyawan}}">{{$item->nama_karyawan}} ({{$item->nip}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Jenis Pengajaran <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="jenis_pengajaran" style="width: 100%" required>
                                        <option value="">Pilih Jenis Pengajaran</option>
                                        @foreach($jenis_pengajaran as $item)
                                            <option value="{{$item->id_jenis_pengajaran}}">{{$item->jenis_pengajaran}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Kelas <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="id_kelas" style="width: 100%" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas as $item)
                                    <option value="{{$item->id_kelas}}">{{$item->kode_kelas}} - {{$item->nama_kelas}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="float-right">
                                <a class="btn btn-danger text-white mr-2" id="btn-cancel"><i
                                        class="fas fa-backward mr-2"></i>Batal</a>
                                <a class="btn btn-primary text-white" id="btn-save"><span
                                        class='spinner-border spinner-border-sm mr-2'
                                        id='loading-tambah-data' style='display: none' role='status'
                                        aria-hidden='true'></span><i
                                        class="fas fa-save mr-2"></i>Simpan Data</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="table">
                                <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Kode MK</th>
                                    <th width="20%">Mata Kuliah</th>
                                    <th width="20%">Dosen Pengampu</th>
                                    <th width="12%">Jenis Pengajaran</th>
                                    <th width="10%">Kelas</th>
                                    <th width="8%">Status</th>
                                    <th width="10%"><i class="fas fa-th-large"></i></th>
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
    <script src="{{asset('adminpage/own-js/admin_akademik/plotting/plotting_perkuliahan.js')}}"></script>
@endpush<?php
