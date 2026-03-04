@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .btn-sm {
                padding: 0.25rem 0.4rem;
                font-size: 0.75rem;
            }
        }
        /* Style untuk list program studi */
        #table tbody td ol {
            margin-bottom: 0;
            padding-left: 1.2rem;
            font-size: 0.9rem;
        }
        #table tbody td ol li {
            padding: 2px 0;
            color: #495057;
        }
        #table tbody td ol li:hover {
            color: #007bff;
        }
    </style>
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Perkuliahan</li>
            <li class="breadcrumb-item active">
                Manajemen Fakultas
            </li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-building"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Manajemen Fakultas</h1>
                <small>Halaman ini digunakan untuk mengelola data fakultas</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Fakultas</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <a id="btn-tambah-data" class="btn btn-sm btn-success text-white">
                                <i class="fas fa-plus-square"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse" id="form-collapse">
                        <input type="hidden" id="kd_fakultas_old">
                        <input type="hidden" id="is_data_aktif_old">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Kode Fakultas <span class="text-danger">*</span></label>
                                    <input type="text" id="kd_fakultas" name="kd_fakultas" class="form-control"
                                        placeholder="Contoh: FT" maxlength="10">
                                    <small class="form-text text-muted">Kode unik untuk fakultas</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Nama Fakultas <span class="text-danger">*</span></label>
                                    <input type="text" id="nama_fakultas" name="nama_fakultas" class="form-control"
                                        placeholder="Contoh: Fakultas Teknik">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="font-weight-bold">Dekan</label>
                                    <input type="text" id="dekan" name="dekan" class="form-control"
                                        placeholder="Nama Dekan (opsional)">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Kode NIM <span class="text-danger">*</span></label>
                                    <input type="text" id="kd_nim_fak" name="kd_nim_fak" class="form-control"
                                        placeholder="Contoh: 01" maxlength="2">
                                    <small class="form-text text-muted">2 digit untuk NIM</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">Program Studi yang Terkait</label>
                                    <select id="prodi_list" name="prodi_list[]" class="form-control select2" multiple="multiple" style="width: 100%">
                                        @foreach($all_prodi as $prodi)
                                            <option value="{{ $prodi->kd_program_studi }}">
                                                {{ $prodi->nama_program_studi }} ({{ $prodi->kd_jenjang_didik }})
                                                @if($prodi->kd_fakultas)
                                                    - <em class="text-muted">Sudah di fakultas lain</em>
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Pilih program studi yang akan diklaim oleh fakultas ini. Anda dapat memilih lebih dari satu.</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="float-right">
                                <a class="btn btn-danger text-white mr-2" id="btn-cancel">
                                    <i class="fas fa-backward mr-2"></i>Batal
                                </a>
                                <a class="btn btn-primary text-white" id="btn-save">
                                    <span class='spinner-border spinner-border-sm mr-2' id='loading-tambah-data'
                                        style='display: none' role='status' aria-hidden='true'></span>
                                    <i class="fas fa-save mr-2"></i>Simpan Data
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="font-weight-bold">Pencarian</label>
                                <div class="input-group">
                                    <input type="text" id="search_input" class="form-control" placeholder="Cari Nama Fakultas">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button" id="btn-search">
                                            <i class="fas fa-search"></i> Cari Data
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="font-weight-bold">Filtering</label>
                                <div class="input-group">
                                    <select id="filter_status" class="form-control">
                                        <option value="2" selected>-- Semua Status --</option>
                                        <option value="1">Aktif</option>
                                        <option value="0">Nonaktif</option>
                                    </select>
                                    <div class="input-group-append mx-2">
                                        <button class="btn btn-primary" type="button" id="btn-filter">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-sm" id="table" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th>Kode Fakultas</th>
                                        <th>Nama Fakultas</th>
                                        <th>Program Studi</th>
                                        <th>Dekan</th>
                                        <th>Kode NIM</th>
                                        <th width="8%" class="text-center">Status</th>
                                        <th width="10%" class="text-center"><i class="fas fa-cog"></i></th>
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
    <script src="{{ asset('adminpage/assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminpage/own-js/admin_akademik/perkuliahan/manajemen_fakultas.js') }}"></script>
@endpush
