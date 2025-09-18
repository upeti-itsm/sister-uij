@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">

    <style>
        /* Modal general */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .modal-header {
            border-bottom: none;
            padding: 1rem 1.5rem;
        }

        .modal-title {
            font-weight: 700;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }

        .modal-title i {
            font-size: 1.2rem;
            margin-right: 8px;
        }

        .close {
            opacity: 0.8;
            transition: 0.2s;
        }

        .close:hover {
            opacity: 1;
        }

        /* Modal body compact but modern */
        .modal-body {
            padding: 1.5rem;
            background-color: #f9fafb;
        }

        .modal-body h6 {
            font-size: 1rem;
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35em 0.6em;
            border-radius: 6px;
        }

        .badge-warning {
            background-color: #ffb74d;
            color: #fff;
        }

        .badge-success {
            background-color: #4caf50;
        }

        .badge-secondary {
            background-color: #607d8b;
        }

        .badge-primary {
            background-color: #1976d2;
        }

        small.text-muted {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
            display: inline-block;
            color: #6c757d !important;
        }

        .modal-footer {
            border-top: 1px solid #eaeaea;
            background: #fff;
            padding: 0.75rem 1.5rem;
        }

        .btn-sm {
            border-radius: 6px;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
        }

        /* Hover effect on rows */
        .modal-body .row:not(:last-child) {
            padding-bottom: 0.75rem;
            margin-bottom: 0.75rem;
            border-bottom: 1px dashed #e0e0e0;
        }
    </style>
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item active">KRS</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Kartu Rencana Studi</h1>
                <small>Halaman ini digunakan untuk memilih mata kuliah yang akan diambil</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-info card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-book"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Total Mata Kuliah</p>
                <h3 class="card-title fs-21 font-weight-bold" id="tot_matkul">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-list mr-2 ml-2"></i>Tersedia
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-success card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-check-circle"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Mata Kuliah</p>
                <h3 class="card-title fs-21 font-weight-bold" id="tot_dipilih">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-clipboard-check mr-2 ml-2"></i>Terpilih
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-warning card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-calculator"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">SKS Dipilih</p>
                <h3 class="card-title fs-21 font-weight-bold" id="tot_sks">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-star mr-2 ml-2"></i><span id="sks-status">Kredit Dipilih</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-info card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">SKS Maksimal</p>
                <h3 class="card-title fs-21 font-weight-bold" id="sks_maksimal">24</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-info-circle mr-2 ml-2"></i>Per Semester
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Jadwal Mata Kuliah</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold">Pencarian Mata Kuliah</label>
                            <div class="row">
                                <div class="col-md-10">
                                    <input type="text" class="form-control" placeholder="Cari Nama/Kode Mata Kuliah"
                                           id="cari-matkul">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-block btn-primary" id="btn-cari-data">
                                        <i class="fas fa-search mr-2"></i>Cari
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info mb-3">
                            <div class="row align-items-center">
                                <div class="col-md-1">
                                    <h5 class="mb-0"><i class="fas fa-info-circle text-info"></i></h5>
                                </div>
                                <div class="col-md-11">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h6 class="font-weight-bold mb-1">Informasi SKS</h6>
                                            <p class="mb-0"><small>SKS Maksimal: <strong><span id="sks-maks-info">24</span> SKS</strong> per semester</small></p>
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="font-weight-bold mb-1">SKS Terpilih</h6>
                                            <p class="mb-0"><small>Total dipilih: <strong><span id="sks-terpilih-info">0</span> SKS</strong></small></p>
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="font-weight-bold mb-1">Sisa SKS</h6>
                                            <p class="mb-0"><small>Dapat diambil: <strong><span id="sks-sisa-info">24</span> SKS</strong></small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table-jadwal">
                            <thead>
                            <tr>
                                <th class="text-center" width="3%">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th class="text-center" width="3%">No</th>
                                <th width="16%">Mata Kuliah</th>
                                <th width="10%">Kelas</th>
                                <th width="7%">SKS</th>
                                <th width="8%">Hari</th>
                                <th width="12%">Jam</th>
                                <th width="10%">Ruang</th>
                                <th width="9%">Kapasitas</th>
                                <th width="7%">Status</th>
                                <th class="text-center" width="6%">Detail</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card untuk menampilkan KRS yang sudah dipilih -->
    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">KRS Terpilih</h6>
                    </div>
                    <div>
                        <button class="btn btn-danger btn-sm" id="btn-hapus-semua">
                            <i class="fas fa-trash mr-2"></i>Hapus Semua
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="table-krs-terpilih">
                        <thead>
                        <tr>
                            <th class="text-center" width="4%">No</th>
                            <th width="25%">Mata Kuliah</th>
                            <th width="12%">Kelas</th>
                            <th width="8%">SKS</th>
                            <th width="10%">Hari</th>
                            <th width="15%">Jam</th>
                            <th width="15%">Ruang</th>
                            <th width="7%">Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                        <tr class="bg-light">
                            <th colspan="3" class="text-right">Total SKS:</th>
                            <th id="total-sks">0</th>
                            <th colspan="4"></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-success btn-lg" id="btn-simpan-krs">
                    <i class="fas fa-save mr-2"></i>Simpan KRS
                </button>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="modal-konfirmasi" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="pesan-konfirmasi">Apakah Anda yakin ingin menyimpan KRS ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn-konfirmasi-ya">Ya, Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Mata Kuliah -->
    <div class="modal fade" id="modal-detail-matkul" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document"> <!-- saya buat modal-lg biar lega -->
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle"></i> Detail Mata Kuliah
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Informasi Utama -->
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="font-weight-bold text-primary mb-2" id="detail-nama-matkul">-</h6>
                            <div class="d-flex align-items-center mb-2 flex-wrap">
                                <span class="badge badge-secondary mr-2 mb-1" id="detail-kode-matkul">-</span>
                                <span class="badge badge-success mr-2 mb-1" id="detail-sks">-</span>
                                <span class="badge badge-primary mb-1" id="detail-kelas">-</span>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="mb-2">
                                <small class="text-muted d-block">Kapasitas</small>
                                <span class="font-weight-bold" id="detail-peserta">-</span> /
                                <span id="detail-kapasitas">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Jadwal & Ruang -->
                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted">Hari</small>
                            <div class="font-weight-bold" id="detail-hari">-</div>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Waktu</small>
                            <div class="font-weight-bold" id="detail-jam">-</div>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Ruang</small>
                            <div>
                                <span class="badge badge-warning" id="detail-ruang">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="row">
                        <div class="col-12">
                            <small class="text-muted">Keterangan</small>
                            <p class="mb-0 text-secondary" id="detail-keterangan">-</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('adminpage/assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.id.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('adminpage/own-js/mahasiswa_page/akademik/krs/krs.js') }}"></script>
@endpush

