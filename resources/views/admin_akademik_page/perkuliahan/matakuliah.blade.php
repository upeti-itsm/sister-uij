@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <style>
        /* Header tabel lebih kontras */
        #table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
            font-size: 13px;
        }

        /* Sel tabel lebih rapi */
        #table td {
            vertical-align: middle;
            padding: 10px 8px;
            font-size: 13px;
        }

        /* Tombol aksi sejajar */
        #table td .btn {
            margin: 0 1px;
        }

        /* Badge styling untuk status dan kategori */
        .badge-semester { background-color: #17a2b8; color: white; }
        .badge-sks { background-color: #28a745; color: white; }
        .badge-wajib { background-color: #dc3545; color: white; }
        .badge-pilihan { background-color: #ffc107; color: #000; }
        .badge-teori { background-color: #6f42c1; color: white; }
        .badge-praktek { background-color: #fd7e14; color: white; }
        .badge-gabungan { background-color: #20c997; color: white; }

        /* Multi-line info styling */
        .matakuliah-info {
            line-height: 1.4;
        }
        .matakuliah-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 2px;
            font-size: 14px;
        }
        .matakuliah-subtitle {
            font-size: 11px;
            color: #6c757d;
        }

        .prodi-info {
            line-height: 1.3;
        }
        .prodi-name {
            font-weight: 500;
            margin-bottom: 2px;
            font-size: 13px;
        }
        .konsentrasi-name {
            font-size: 11px;
            color: #6c757d;
        }

        /* Prasyarat styling */
        .prasyarat-list {
            max-height: 60px;
            overflow-y: auto;
            font-size: 11px;
        }
        .prasyarat-item {
            background-color: #e9ecef;
            padding: 2px 6px;
            margin: 1px 2px 1px 0;
            border-radius: 3px;
            display: inline-block;
            font-size: 10px;
        }

        /* Filter section styling */
        .filter-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .filter-section .form-group label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }

        /* Auto-refresh indicator */
        .auto-refresh-info {
            font-size: 11px;
            color: #6c757d;
            font-style: italic;
        }

        /* Loading states */
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered[title*="Memuat"] {
            color: #007bff;
            font-style: italic;
        }

        /* Form validation styling */
        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        /* Modal enhancements */
        .modal-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
        }

        .modal-title {
            color: white;
        }

        .modal-body {
            background-color: #fff;
        }

        .required-field {
            color: #dc3545;
            font-weight: bold;
        }

        /* Upload Excel Styles */
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 15px;
        }

        .upload-area:hover {
            border-color: #007bff;
            background-color: #e3f2fd;
        }

        .upload-area.dragover {
            border-color: #28a745;
            background-color: #d4edda;
        }

        .upload-icon {
            font-size: 2rem;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .progress-container {
            display: none;
            margin-top: 15px;
        }

        .progress {
            height: 20px;
            border-radius: 10px;
            background-color: #e9ecef;
        }

        .progress-bar {
            border-radius: 10px;
            font-weight: 600;
            transition: width 0.3s ease;
        }

        .status-container {
            display: none;
            margin-top: 15px;
        }

        .log-item {
            padding: 6px 10px;
            margin: 2px 0;
            border-radius: 4px;
            font-size: 12px;
        }

        .log-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 3px solid #28a745;
        }

        .log-error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 3px solid #dc3545;
        }

        .log-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border-left: 3px solid #17a2b8;
        }

        .template-section {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 12px;
            margin-bottom: 15px;
        }

        .file-info {
            background-color: #e9ecef;
            padding: 8px;
            border-radius: 5px;
            margin: 10px 0;
        }

        .validation-item {
            display: flex;
            align-items: center;
            margin: 3px 0;
            font-size: 13px;
        }

        .validation-icon {
            margin-right: 6px;
            width: 16px;
        }

        .summary-stats {
            display: none;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 12px;
            margin-top: 10px;
        }

        .stat-item {
            text-align: center;
            padding: 8px;
        }

        .stat-number {
            font-size: 1.3rem;
            font-weight: bold;
            display: block;
        }

        #file-input {
            display: none;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            #table td {
                padding: 6px 4px;
                font-size: 12px;
            }
            .btn-sm {
                padding: 0.2rem 0.4rem;
                font-size: 11px;
            }
            .filter-section {
                padding: 10px;
            }
            .matakuliah-title {
                font-size: 13px;
            }
            .upload-area {
                padding: 20px;
            }
        }

        /* Custom select2 styling for cascade */
        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(1.5em + 0.75rem + 2px);
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            padding-left: 12px;
            padding-right: 20px;
        }
    </style>
@endsection

@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Perkuliahan</li>
            <li class="breadcrumb-item active">Matakuliah</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Manajemen Matakuliah</h1>
                <small>Kelola matakuliah berdasarkan kurikulum dan program studi dengan sistem cascade filtering</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0 text-primary">
                            <i class="fas fa-list-alt mr-2"></i>Daftar Matakuliah
                        </h6>
                        <small class="text-muted">Total matakuliah akan ditampilkan sesuai filter yang dipilih</small>
                    </div>
                    <div class="text-right">
                        <button id="btn-upload-excel" class="btn btn-info btn-sm shadow-sm mr-2">
                            <i class="fas fa-file-excel mr-1"></i>Upload Excel
                        </button>
                        <button id="btn-tambah-data" class="btn btn-success btn-sm shadow-sm">
                            <i class="fas fa-plus-circle mr-1"></i>Tambah Matakuliah
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Filter Section dengan Cascade System -->
                <div class="filter-section">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">
                                    <i class="fas fa-search mr-1"></i>Pencarian Matakuliah
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Ketik nama atau kode matakuliah..." id="cari-data">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" id="btn-cari-data" title="Cari Data">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <label class="font-weight-bold">
                                <i class="fas fa-filter mr-1"></i>Filter Data
                            </label>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <select class="form-control select2" name="kd_prodi" id="kd_prodi">
                                            <option value="all">-- Semua Program Studi --</option>
                                            @foreach($program_studi as $item)
                                                <option value="{{$item->kd_program_studi}}">{{$item->nama_program_studi}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <select class="form-control select2" name="id_kurikulum" id="id_kurikulum">
                                            <option value="">-- Semua Kurikulum --</option>
                                            @foreach($kurikulum as $item)
                                                <option value="{{$item->id_kurikulum}}">{{$item->nama_kurikulum}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary btn-block" id="btn-filter" title="Refresh Manual">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-bordered table-hover" id="table" style="width:100%">
                        <thead class="thead-light">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="25%">
                                <i class="fas fa-book mr-1"></i>Matakuliah
                                <small class="d-block font-weight-normal">Nama & Kode</small>
                            </th>
                            <th width="8%" class="text-center">
                                <i class="fas fa-graduation-cap mr-1"></i>SKS/Semester
                                <small class="d-block font-weight-normal">Kredit & Semester</small>
                            </th>
                            <th width="20%">
                                <i class="fas fa-university mr-1"></i>Program Studi
                                <small class="d-block font-weight-normal">Prodi & Konsentrasi</small>
                            </th>
                            <th width="15%">
                                <i class="fas fa-clipboard-list mr-1"></i>Kurikulum
                                <small class="d-block font-weight-normal">Tahun/Nama</small>
                            </th>
                            <th width="12%" class="text-center">
                                <i class="fas fa-tags mr-1"></i>Kategori
                                <small class="d-block font-weight-normal">Jenis & Pelaksanaan</small>
                            </th>
                            <th width="10%">
                                <i class="fas fa-link mr-1"></i>Prasyarat
                                <small class="d-block font-weight-normal">Matakuliah Pendahulu</small>
                            </th>
                            <th width="5%" class="text-center">
                                <i class="fas fa-cogs mr-1"></i>Aksi
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Data akan dimuat via AJAX -->
                        </tbody>
                        <tfoot class="thead-light">
                        <tr>
                            <th colspan="8" class="text-center">
                                <small class="text-muted">Data akan ditampilkan sesuai filter yang dipilih</small>
                            </th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Form for Delete Action -->
    <form style="display: none" action="#" method="POST" id="delete-form">
        @csrf
        @method('DELETE')
        <input type="hidden" id="delete-id_matakuliah" name="id">
        <input type="hidden" id="delete-status" name="status" value="delete">
    </form>
@endsection

@section('modal')
    <!-- Modal Insert/Update dengan Enhanced UI -->
    <div class="modal fade" id="modal-insup" tabindex="-1" role="dialog" aria-labelledby="insupLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600" id="insupLabel">
                        <i class="fas fa-plus-circle mr-2"></i>Form Matakuliah
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="#" method="POST" id="insup-form">
                        @csrf
                        <input type="hidden" id="insup-id" name="id" value="00000000-0000-0000-0000-000000000000">

                        <!-- Alert Info -->
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Informasi:</strong> Field bertanda <span class="required-field">*</span> wajib diisi.
                            Pilih program studi untuk memuat konsentrasi, dan kurikulum untuk memuat prasyarat.
                            <button type="button" class="close" data-dismiss="alert">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <!-- Section 1: Program Studi & Konsentrasi & Kurikulum -->
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-university mr-2"></i>Informasi Program Studi
                                </h6>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-university mr-1"></i>Program Studi
                                        <span class="required-field">*</span>
                                    </label>
                                    <select class="form-control select2" name="kd_prodi" id="insup-kd_prodi" required>
                                        <option value="all">-- Pilih Program Studi --</option>
                                        @foreach($program_studi as $item)
                                            <option value="{{$item->kd_program_studi}}">{{$item->nama_program_studi}}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Pilih program studi untuk memuat konsentrasi</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-graduation-cap mr-1"></i>Konsentrasi
                                        <span class="required-field">*</span>
                                    </label>
                                    <select class="form-control select2" name="id_konsentrasi" id="insup-id_konsentrasi" required>
                                        <option value="">-- Pilih Program Studi Terlebih Dahulu --</option>
                                    </select>
                                    <small class="form-text text-muted">Konsentrasi akan dimuat sesuai prodi</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-clipboard-list mr-1"></i>Kurikulum
                                        <span class="required-field">*</span>
                                    </label>
                                    <select class="form-control select2" name="id_kurikulum" id="insup-id_kurikulum" required>
                                        <option value="">-- Pilih Kurikulum --</option>
                                        @foreach($kurikulum as $item)
                                            <option value="{{$item->id_kurikulum}}">{{$item->nama_kurikulum}}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Pilih kurikulum untuk memuat prasyarat</small>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Identitas Matakuliah -->
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-book mr-2"></i>Identitas Matakuliah
                                </h6>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-font mr-1"></i>Nama Matakuliah
                                        <span class="required-field">*</span>
                                    </label>
                                    <input type="text" name="nama_matakuliah" class="form-control" id="insup-nama_matakuliah"
                                           required placeholder="Contoh: Pemrograman Web Lanjut">
                                    <small class="form-text text-muted">Nama lengkap matakuliah sesuai kurikulum</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-code mr-1"></i>Kode Matakuliah
                                        <span class="required-field">*</span>
                                    </label>
                                    <input type="text" name="kode_matakuliah" class="form-control" id="insup-kode_matakuliah"
                                           required placeholder="Contoh: TI301">
                                    <small class="form-text text-muted">Kode unik matakuliah</small>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Detail Akademik -->
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-cog mr-2"></i>Detail Akademik
                                </h6>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-calculator mr-1"></i>Jumlah SKS
                                        <span class="required-field">*</span>
                                    </label>
                                    <input type="number" name="jumlah_sks" class="form-control" id="insup-jumlah_sks"
                                           required min="1" max="6" placeholder="3">
                                    <small class="form-text text-muted">Rentang: 1-6 SKS</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-calendar-alt mr-1"></i>Semester
                                        <span class="required-field">*</span>
                                    </label>
                                    <input type="number" name="semester" class="form-control" id="insup-semester"
                                           required min="1" max="8" placeholder="5">
                                    <small class="form-text text-muted">Semester: 1-8</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-tag mr-1"></i>Jenis Matakuliah
                                        <span class="required-field">*</span>
                                    </label>
                                    <select class="form-control select2" name="id_jenis_matakuliah" id="insup-id_jenis_matakuliah" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        @foreach($jenis_matakuliah as $item)
                                            <option value="{{$item->kd_jenis_matakuliah}}">{{$item->nama_jenis}}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Wajib/Pilihan</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-chalkboard mr-1"></i>Jenis Pelaksanaan
                                        <span class="required-field">*</span>
                                    </label>
                                    <select class="form-control select2" name="id_jenis_pelaksanaan" id="insup-id_jenis_pelaksanaan" required>
                                        <option value="">-- Pilih Jenis Pelaksanaan --</option>
                                        @foreach($jenis_pelaksanaan as $item)
                                            <option value="{{$item->kd_jenis_pelaksanaan_matakuliah}}">{{$item->jenis_pelaksanaan_matakuliah}}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Teori/Praktik/Gabungan</small>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Prasyarat -->
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-link mr-2"></i>Matakuliah Prasyarat
                                </h6>
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-list-ul mr-1"></i>Pilih Matakuliah Prasyarat
                                        <small class="text-muted font-weight-normal">(opsional)</small>
                                    </label>
                                    <select multiple class="form-control select2" name="id_matakuliah_prasyarat[]" id="insup-id_matakuliah_prasyarat">
                                        <option value="">-- Pilih Kurikulum Terlebih Dahulu --</option>
                                    </select>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Matakuliah yang harus diselesaikan terlebih dahulu.
                                        Pilih kurikulum di atas untuk memuat daftar matakuliah.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-success" id="btn-simpan">
                        <i class="fas fa-save mr-1"></i>Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Excel -->
    <div class="modal fade" id="modal-upload-excel" tabindex="-1" role="dialog" aria-labelledby="uploadExcelLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-600" id="uploadExcelLabel">
                        <i class="fas fa-file-excel mr-2"></i>Upload Data Matakuliah dari Excel
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!-- Template Info -->
                    <div class="template-section">
                        <div class="row">
                            <div class="col-md-8">
                                <h6><i class="fas fa-info-circle mr-2"></i>Persyaratan File Excel:</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="validation-item">
                                            <i class="fas fa-check text-success validation-icon"></i>
                                            Format: .xlsx atau .xls
                                        </div>
                                        <div class="validation-item">
                                            <i class="fas fa-check text-success validation-icon"></i>
                                            Header harus ada di baris pertama
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="validation-item">
                                            <i class="fas fa-check text-success validation-icon"></i>
                                            Maksimal 1000 baris data
                                        </div>
                                        <div class="validation-item">
                                            <i class="fas fa-check text-success validation-icon"></i>
                                            Kode matakuliah harus unik
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-right">
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#templateModal">
                                    <i class="fas fa-download mr-1"></i>Download Template
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Upload Area -->
                        <div class="col-md-6">
                            <div class="upload-area" id="uploadArea">
                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                <h6>Pilih File Excel atau Drag & Drop</h6>
                                <p class="text-muted mb-3 small">File maksimal 5MB (.xlsx, .xls)</p>
                                <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('file-input').click()">
                                    <i class="fas fa-folder-open mr-1"></i>Pilih File
                                </button>
                                <input type="file" id="file-input" accept=".xlsx,.xls" />
                            </div>

                            <div id="fileInfo" class="file-info" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong id="fileName"></strong>
                                        <small class="d-block text-muted" id="fileSize"></small>
                                    </div>
                                    <button class="btn btn-sm btn-danger" onclick="clearFile()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Validation Controls -->
                            <!-- Validation Controls -->
                            <div id="validationControls" style="display: none;">
                                <hr>
                                <div class="alert alert-warning" role="alert">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Penting!</strong> Anda HARUS memilih Kurikulum sebelum melakukan validasi data.
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold small">
                                                Program Studi Default
                                                <small class="text-muted">(opsional)</small>
                                            </label>
                                            <select class="form-control select2" id="defaultProdi">
                                                <option value="all">-- Pilih Program Studi --</option>
                                                @foreach($program_studi as $item)
                                                    <option value="{{$item->kd_program_studi}}">{{$item->nama_program_studi}}</option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">Akan digunakan jika kolom kd_prodi kosong</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold small">
                                                Kurikulum <span class="text-danger">*</span>
                                                <strong class="text-danger">(WAJIB)</strong>
                                            </label>
                                            <select class="form-control select2" id="defaultKurikulum" required>
                                                <option value="">-- Pilih Kurikulum --</option>
                                            </select>
                                            <small class="form-text text-danger font-weight-bold">
                                                <i class="fas fa-info-circle"></i> Wajib dipilih! Semua matakuliah akan menggunakan kurikulum ini
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-info btn-sm" onclick="validateData()">
                                        <i class="fas fa-check-circle mr-1"></i>Validasi Data
                                    </button>
                                    <button class="btn btn-success btn-sm" onclick="startUpload()" disabled id="btnStartUpload">
                                        <i class="fas fa-upload mr-1"></i>Mulai Upload
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Preview & Progress -->
                        <div class="col-md-6">
                            <!-- Preview Container -->
                            <div id="previewContainer" style="display: none;">
                                <h6 class="small">Preview Data (5 baris pertama):</h6>
                                <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                                    <table class="table table-sm table-bordered" id="previewTable">
                                        <thead class="thead-light"></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Progress Container -->
                            <div class="progress-container" id="progressContainer">
                                <h6 class="small"><i class="fas fa-tasks mr-2"></i>Progress Upload</h6>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                         role="progressbar" id="progressBar" style="width: 0%">
                                        0%
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <small id="progressText">Menunggu...</small>
                                    <small id="progressCount">0 / 0</small>
                                </div>
                            </div>

                            <!-- Summary Stats -->
                            <div class="summary-stats" id="summaryStats">
                                <h6 class="small">Ringkasan Upload:</h6>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="stat-item text-success">
                                            <span class="stat-number" id="successCount">0</span>
                                            <small>Berhasil</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-item text-danger">
                                            <span class="stat-number" id="errorCount">0</span>
                                            <small>Gagal</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-item text-info">
                                            <span class="stat-number" id="totalCount">0</span>
                                            <small>Total</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Log -->
                    <div class="status-container" id="statusContainer">
                        <h6 class="small mt-3"><i class="fas fa-list-alt mr-2"></i>Log Proses Upload</h6>
                        <div style="max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 4px; padding: 8px;">
                            <div id="logContainer"></div>
                        </div>
                        <button class="btn btn-sm btn-secondary mt-2" onclick="clearLog()">
                            <i class="fas fa-broom mr-1"></i>Bersihkan Log
                        </button>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Template Modal -->
    <div class="modal fade" id="templateModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-file-excel mr-2"></i>Template Excel Matakuliah</h5>
                    <button type="button" class="close text-dark" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Struktur Kolom Wajib:</h6>
                            <table class="table table-sm table-bordered">
                                <thead class="thead-light">
                                <tr>
                                    <th>Kolom</th>
                                    <th>Tipe Data</th>
                                    <th>Contoh</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr><td>kode_matakuliah</td><td>Text</td><td>TI301</td></tr>
                                <tr><td>nama_matakuliah</td><td>Text</td><td>Pemrograman Web</td></tr>
                                <tr><td>jumlah_sks</td><td>Number (1-6)</td><td>3</td></tr>
                                <tr><td>semester</td><td>Number (1-8)</td><td>5</td></tr>
                                <tr><td>kd_prodi</td><td>Text</td><td>TI</td></tr>
                                <tr><td>id_konsentrasi</td><td>Number</td><td>1</td></tr>
                                <tr class="table-warning">
                                    <td>id_kurikulum</td>
                                    <td colspan="2">
                                        <strong>Akan diisi otomatis dari pilihan Anda</strong>
                                    </td>
                                </tr>
                                <tr><td>id_jenis_matakuliah</td><td>Text</td><td>WAJIB</td></tr>
                                <tr><td>id_jenis_pelaksanaan</td><td>Text</td><td>TEORI</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Kolom Opsional:</h6>
                            <table class="table table-sm table-bordered">
                                <thead class="thead-light">
                                <tr><th>Kolom</th><th>Keterangan</th></tr>
                                </thead>
                                <tbody>
                                <tr><td>prasyarat</td><td>Pisahkan dengan koma jika lebih dari 1</td></tr>
                                </tbody>
                            </table>

                            <h6 class="mt-3">Nilai yang Diperbolehkan:</h6>
                            <div class="row">
                                <div class="col-6">
                                    <strong>Jenis Matakuliah:</strong>
                                    <ul class="small">
                                        <li>WAJIB</li>
                                        <li>PILIHAN</li>
                                    </ul>
                                </div>
                                <div class="col-6">
                                    <strong>Jenis Pelaksanaan:</strong>
                                    <ul class="small">
                                        <li>TEORI</li>
                                        <li>PRAKTEK</li>
                                        <li>GABUNGAN</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{asset('template/template_matakuliah.xlsx')}}" class="btn btn-success" download="template_matakuliah.xlsx">
                        <i class="fas fa-download mr-1"></i>Download Template
                    </a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="{{asset('adminpage/own-js/admin_akademik/perkuliahan/matakuliah.js')}}"></script>

    <script>
        // Additional UI enhancements
        $(document).ready(function() {
            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                $('.alert-dismissible').fadeOut('slow');
            }, 5000);

            // Tooltip initialization
            $('[title]').tooltip();

            // Form validation visual feedback
            $('.form-control').on('blur', function() {
                if ($(this).prop('required') && !$(this).val()) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
        });
    </script>
@endpush
