@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">

    <style>
        /* Card Stats Custom */
        .card-stats {
            transition: transform 0.2s;
        }

        .card-stats:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* Badge styling */
        .badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35em 0.6em;
            border-radius: 6px;
        }

        .badge-nilai-a {
            background-color: #4caf50;
            color: white;
        }

        .badge-nilai-ab {
            background-color: #66bb6a;
            color: white;
        }

        .badge-nilai-b {
            background-color: #8bc34a;
            color: white;
        }

        .badge-nilai-bc {
            background-color: #cddc39;
            color: #333;
        }

        .badge-nilai-c {
            background-color: #ffeb3b;
            color: #333;
        }

        .badge-nilai-d {
            background-color: #ff9800;
            color: white;
        }

        .badge-nilai-e {
            background-color: #f44336;
            color: white;
        }

        /* Table styling */
        .table-khs tbody tr {
            transition: background-color 0.2s;
        }

        .table-khs tbody tr:hover {
            background-color: #f5f5f5;
        }

        /* Filter Section */
        .filter-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            padding: 20px;
            color: white;
            margin-bottom: 20px;
        }

        .filter-section .form-control,
        .filter-section .select2-container--default .select2-selection--single {
            border-radius: 8px;
        }

        /* IP Card Styling */
        .ip-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .ip-card h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 10px 0;
        }

        .ip-card small {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Modal styling */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-bottom: none;
            padding: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0;
        }

        .modal-title {
            font-weight: 700;
        }

        .modal-body {
            padding: 1.5rem;
        }

        /* Status Lulus/Tidak Lulus */
        .status-lulus {
            color: #4caf50;
            font-weight: 600;
        }

        .status-tidak-lulus {
            color: #f44336;
            font-weight: 600;
        }

        /* Semester Badge */
        .semester-badge {
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }

        /* Print button */
        #btn-download-khs {
            border: none;
            color: white;
            transition: all 0.3s;
        }

        #btn-download-khs:hover {
            transform: translateY(-2px);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        /* Semester Info Card */
        .semester-info-card {
            border-left: 4px solid #667eea;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item active">Hasil Studi</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-chart-line"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Hasil Studi</h1>
                <small>Halaman ini menampilkan hasil studi dan nilai mata kuliah yang telah diambil</small>
            </div>
        </div>
    </div>
@endsection

@section('body-content')
    <div class="col-md-6">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-info card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-star"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">IP Semester</p>
                <h3 class="card-title fs-21 font-weight-bold" id="ips">0.00</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats d-flex align-items-center">
                    <i class="fas fa-chart-line mr-2"></i>
                    <span>IPS</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-success card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-trophy"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">IP Kumulatif</p>
                <h3 class="card-title fs-21 font-weight-bold" id="ipk">0.00</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats d-flex align-items-center">
                    <i class="fas fa-chart-bar mr-2"></i>
                    <span>IPK</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="col-md-12">
        <div class="card mb-2">
            <div class="card-body">

                <div class="form-row align-items-end">

                    <!-- Tahun Akademik -->
                    <div class="col-md-3 mb-3">
                        <label class="font-weight-bold text-dark">
                            <i class="fas fa-calendar-alt mr-2"></i>Tahun Akademik
                        </label>
                        <select class="form-control select2" id="filter-tahun-akademik">
                            <option value="">-- Semua Tahun Akademik --</option>
                            <!-- populated via JS -->
                        </select>
                    </div>

                    <!-- Semester -->
                    <div class="col-md-3 mb-3">
                        <label class="font-weight-bold text-dark">
                            <i class="fas fa-layer-group mr-2"></i>Semester
                        </label>
                        <select class="form-control select2" id="filter-semester">
                            <option value="">-- Semua Semester --</option>
                            <!-- populated via JS -->
                        </select>
                    </div>

                    <!-- Search -->
                    <div class="col-md-3 mb-3">
                        <label class="font-weight-bold text-dark">
                            <i class="fas fa-search mr-2"></i>Cari Mata Kuliah
                        </label>
                        <input type="text" class="form-control" id="filter-search"
                            placeholder="Cari nama/kode mata kuliah...">
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="d-flex">
                            <button class="btn btn-primary flex-fill mr-2" id="btn-filter">
                                <i class="fas fa-filter mr-1"></i>Terapkan
                            </button>

                            <button class="btn btn-secondary flex-fill" id="btn-reset-filter">
                                <i class="fas fa-redo mr-1"></i>Reset
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Semester Info Card -->
    <div class="col-md-12">
        <div class="semester-info-card" id="semester-info" style="display: none;">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h6 class="font-weight-bold mb-1">
                        <i class="fas fa-info-circle text-primary mr-2"></i>
                        Semester <span id="semester-nama">-</span>
                    </h6>
                    <small class="text-muted">
                        Tahun Akademik: <strong id="semester-tahun">-</strong> |
                        Total MK: <strong id="semester-total-mk">0</strong> |
                        Total SKS: <strong id="semester-total-sks">0</strong>
                    </small>
                </div>
                <div class="col-md-4 text-right">
                    <span class="badge badge-primary semester-badge">
                        IPS: <strong id="semester-ips-badge">0.00</strong>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Hasil Studi -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">
                            <i class="fas fa-table mr-2"></i>Mata Kuliah & Nilai
                        </h6>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-primary"
                            onclick="window.location.href='{{ route('mahasiswa.akademik.khs.riwayat_pengajuan') }}'">
                            <i class="fas fa-list mr-2"></i>Riwayat Pengajuan LHS
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-khs" id="table-khs">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="16%">Kode MK</th>
                                <th width="29%">Nama Mata Kuliah</th>
                                <th class="text-center" width="8%">SKS</th>
                                <th class="text-center" width="10%">Nilai Angka</th>
                                <th class="text-center" width="10%">Nilai Huruf</th>
                                <th class="text-center" width="12%">Tahun Akademik</th>
                                <th class="text-center" width="10%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="9" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>Memuat data...</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="thead-light">
                            <tr>
                                <th colspan="3" class="text-right">Total</th>
                                <th class="text-center" id="footer-total-sks">0</th>
                                <th colspan="3" class="text-center">IP Semester</th>
                                <th class="text-center" id="footer-ips">0.00</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Transkrip Lengkap -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h6 class="fs-17 font-weight-600 mb-0">
                    <i class="fas fa-file-alt mr-2"></i>Transkrip Nilai
                </h6>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="border rounded p-3">
                            <h6 class="mb-3">Ringkasan</h6>
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td width="45%"><strong>Total SKS Saat Ini</strong></td>
                                    <td width="5%">:</td>
                                    <td id="transkrip-total-sks">0 SKS</td>
                                </tr>
                                <tr>
                                    <td><strong>Total SKS</strong></td>
                                    <td>:</td>
                                    <td id="transkrip-sks-lulus">0 SKS</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Mata Kuliah</strong></td>
                                    <td>:</td>
                                    <td id="transkrip-total-mk">0 Mata Kuliah</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-5 mt-3 mt-md-0">
                        <div class="border rounded p-3 text-center bg-light">
                            <small class="text-muted">INDEKS PRESTASI KUMULATIF</small>
                            <div id="transkrip-ipk" class="display-3 mb-1" style="line-height:1;">0.00</div>
                            <small class="text-muted">dari skala 4.00</small>
                        </div>
                    </div>
                </div>
            </div><!-- /card-body -->
        </div><!-- /card -->
    </div>
@endsection

@section('modal')
    <!-- Modal Detail Nilai -->
    <div class="modal fade" id="modal-detail-nilai" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle mr-2"></i>Detail Nilai Mata Kuliah
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="font-weight-bold text-primary mb-3" id="detail-nama-mk">-</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="40%"><strong>Kode MK</strong></td>
                                    <td width="5%">:</td>
                                    <td id="detail-kode-mk">-</td>
                                </tr>
                                <tr>
                                    <td><strong>SKS</strong></td>
                                    <td>:</td>
                                    <td id="detail-sks">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Semester</strong></td>
                                    <td>:</td>
                                    <td id="detail-semester">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Tahun Akademik</strong></td>
                                    <td>:</td>
                                    <td id="detail-tahun-akademik">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="40%"><strong>Nilai Angka</strong></td>
                                    <td width="5%">:</td>
                                    <td id="detail-nilai-angka">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Nilai Huruf</strong></td>
                                    <td>:</td>
                                    <td id="detail-nilai-huruf">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Bobot</strong></td>
                                    <td>:</td>
                                    <td id="detail-bobot">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>:</td>
                                    <td id="detail-status">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6 class="font-weight-bold">Dosen Pengampu:</h6>
                            <p id="detail-dosen">-</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('adminpage/assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('adminpage/own-js/mahasiswa_page/akademik/khs/index.js') }}"></script>
@endpush
