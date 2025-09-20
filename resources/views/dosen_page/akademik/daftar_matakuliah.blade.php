@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/jquery-confirm/jquery-confirm.min.css')}}" rel="stylesheet">
    <style>
        .criteria-detail {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            margin: 10px 0;
            padding: 15px;
            border-radius: 0 5px 5px 0;
        }
        .criteria-item {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 8px;
            padding: 12px;
            position: relative;
        }
        .weight-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background-color: #007bff;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .total-weight {
            border-top: 2px solid #007bff;
            padding-top: 10px;
            margin-top: 10px;
            font-weight: bold;
        }
        .weight-warning {
            color: #dc3545;
            font-size: 12px;
        }
        .weight-success {
            color: #28a745;
            font-size: 12px;
        }
        .expandable-row {
            cursor: pointer;
        }
        .expand-icon {
            transition: transform 0.2s;
        }
        .expand-icon.rotated {
            transform: rotate(90deg);
        }
        .btn-group-vertical .btn {
            margin-bottom: 2px;
        }
        .modal-lg {
            max-width: 900px;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        .table-responsive {
            border-radius: 0.375rem;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0,0,0,.125);
        }
        .badge {
            font-size: 0.75em;
        }
        .btn-soft {
            border: 1px solid transparent;
        }
        .btn-primary-soft {
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
            border-color: rgba(13, 110, 253, 0.1);
        }
        .btn-primary-soft:hover {
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .btn-info-soft {
            color: #0dcaf0;
            background-color: rgba(13, 202, 240, 0.1);
            border-color: rgba(13, 202, 240, 0.1);
        }
        .btn-info-soft:hover {
            color: #000;
            background-color: #0dcaf0;
            border-color: #0dcaf0;
        }
        .btn-danger-soft {
            color: #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
            border-color: rgba(220, 53, 69, 0.1);
        }
        .btn-danger-soft:hover {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }
    </style>
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item active">Daftar Matakuliah</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Daftar Matakuliah</h1>
                <small>Halaman ini digunakan monitoring daftar matakuliah yang diampu masing-masing dosen dan pengelolaan kriteria penilaian</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Matakuliah & Kriteria Penilaian</h6>
                    </div>
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Klik tombol <span class="badge badge-primary"><i class="fas fa-cogs"></i></span> untuk mengelola kriteria penilaian
                        </small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" placeholder="Cari Nama Matakuliah"
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
                                        <div class="col-md-5">
                                            <select class="select2 form-control" id="tahun_akademik">
                                                @foreach($tahun_akademik AS $item)
                                                    <option
                                                        value="{{$item->tahun_akademik}}">{{$item->tahun_akademik}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-block btn-primary" id="btn-filter"><i
                                                    class="fas fa-filter mr-2"></i>Filter
                                            </button>
                                        </div>
                                        <div class="col-md-4" style="display: none">
                                            <button class="btn btn-block btn-danger" id="btn-export-pdf"><i
                                                    class="fas fa-file-pdf mr-2"></i>Export To PDF
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
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th class="text-center" width="40%">Nama Matakuliah (Kelas)</th>
                                <th class="text-center" width="25%">Prodi</th>
                                <th class="text-center" width="30%">
                                    <i class="fas fa-cogs mr-1"></i>Aksi
                                </th>
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
    <!-- Modal Kelola Kriteria Penilaian (Tambah & Hapus) -->
    <div class="modal fade" id="modalKriteria" tabindex="-1" role="dialog" aria-labelledby="modalKriteriaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalKriteriaLabel">
                        <i class="fas fa-list-ol mr-2"></i>Kelola Kriteria Penilaian
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Info Mata Kuliah -->
                    <div class="card mb-3">
                        <div class="card-body bg-light">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="card-title mb-1" id="matkul-name">Nama Mata Kuliah</h6>
                                    <small class="text-muted" id="matkul-prodi">Program Studi</small>
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="total-weight-info">
                                        <span class="badge badge-primary" id="total-weight-badge">Total: 0%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Tambah Kriteria -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-plus-circle mr-2"></i>Tambah Kriteria Penilaian
                            </h6>
                        </div>
                        <div class="card-body">
                            <form id="formKriteria">
                                <input type="hidden" id="matkul_id" name="id_jadwal">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nama_kriteria" class="font-weight-bold">
                                                Kriteria <span class="text-danger">*</span>
                                            </label>
                                            <select class="select2 form-control" id="id_kriteria" name="id_kriteria">
                                                @foreach($kriteria as $item)
                                                    <option value="{{$item->id_kriteria_penilaian}}">{{$item->kriteria_penilaian}}</option>
                                                @endforeach
                                                    <option value="0">Kriteria Lain</option>
                                            </select>
                                            <small class="form-text text-muted">Pilih Kriteria</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nama_kriteria" class="font-weight-bold">
                                                Nama Kriteria <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria"
                                                   placeholder="Contoh: UTS, UAS, Tugas, Quiz, Praktikum" required>
                                            <small class="form-text text-muted">Masukkan nama komponen penilaian</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="bobot" class="font-weight-bold">
                                                Bobot (%) <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" class="form-control" id="bobot" name="bobot"
                                                   min="1" max="100" placeholder="0" required>
                                            <small class="form-text text-muted">Total harus 100%</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-weight-bold">&nbsp;</label>
                                            <button type="submit" class="btn btn-success btn-block" id="btnSimpanKriteria">
                                                <i class="fas fa-plus mr-2"></i>Tambah Kriteria
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Daftar Kriteria -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-list mr-2"></i>Daftar Kriteria Penilaian
                                </h6>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>Klik tombol <span class="badge badge-danger badge-sm"><i class="fas fa-trash"></i></span> untuk menghapus kriteria
                                </small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="kriteriaList">
                                <div class="text-center text-muted py-3" id="no-criteria">
                                    <i class="fas fa-info-circle mr-2"></i>Belum ada kriteria penilaian
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/jquery-confirm/jquery-confirm.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/dosen_page/akademik/daftar_matakuliah.js')}}"></script>
@endpush
