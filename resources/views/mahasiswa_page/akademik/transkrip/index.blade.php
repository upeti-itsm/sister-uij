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

        .badge-status-draft       { background-color: #9e9e9e; color: white; }
        .badge-status-diajukan    { background-color: #2196f3; color: white; }
        .badge-status-kaprodi     { background-color: #ff9800; color: white; }
        .badge-status-dekan       { background-color: #9c27b0; color: white; }
        .badge-status-disetujui   { background-color: #4caf50; color: white; }
        .badge-status-ditolak     { background-color: #f44336; color: white; }

        /* Timeline */
        .timeline-wrapper {
            position: relative;
            padding-left: 30px;
        }

        .timeline-wrapper::before {
            content: '';
            position: absolute;
            left: 12px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e0e0e0;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -24px;
            top: 6px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #ccc;
            border: 2px solid white;
            box-shadow: 0 0 0 2px #ccc;
        }

        .timeline-item.active::before  { background: #2196f3; box-shadow: 0 0 0 2px #2196f3; }
        .timeline-item.success::before { background: #4caf50; box-shadow: 0 0 0 2px #4caf50; }
        .timeline-item.danger::before  { background: #f44336; box-shadow: 0 0 0 2px #f44336; }
        .timeline-item.warning::before { background: #ff9800; box-shadow: 0 0 0 2px #ff9800; }

        /* Filter Section */
        .filter-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            padding: 20px;
            color: white;
            margin-bottom: 20px;
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

        .modal-title { font-weight: 700; }

        .modal-body { padding: 1.5rem; }

        /* Status */
        .status-disetujui { color: #4caf50; font-weight: 600; }
        .status-ditolak   { color: #f44336; font-weight: 600; }
        .status-proses    { color: #2196f3; font-weight: 600; }

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

        /* Ajukan button */
        #btn-ajukan-transkrip {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            transition: all 0.3s;
        }

        #btn-ajukan-transkrip:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        /* Step indicator */
        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin: 20px 0;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }

        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 18px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #e0e0e0;
            z-index: 0;
        }

        .step-item.done:not(:last-child)::after   { background: #4caf50; }
        .step-item.active:not(:last-child)::after { background: #e0e0e0; }

        .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #e0e0e0;
            color: #999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            z-index: 1;
            border: 3px solid #e0e0e0;
        }

        .step-item.done   .step-circle { background: #4caf50; color: white; border-color: #4caf50; }
        .step-item.active .step-circle { background: #2196f3; color: white; border-color: #2196f3; }
        .step-item.reject .step-circle { background: #f44336; color: white; border-color: #f44336; }

        .step-label {
            font-size: 0.75rem;
            margin-top: 6px;
            color: #999;
            text-align: center;
        }

        .step-item.done   .step-label  { color: #4caf50; font-weight: 600; }
        .step-item.active .step-label  { color: #2196f3; font-weight: 600; }
        .step-item.reject .step-label  { color: #f44336; font-weight: 600; }

        /* Info alert */
        .alert-info-custom {
            border-left: 4px solid #667eea;
            background: #f0f0ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        /* Loading Overlay */
        #global-loading-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.45);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
    </style>
@endsection

@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item active">Pengajuan Transkrip</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-file-alt"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Pengajuan Transkrip Nilai</h1>
                <small>Halaman ini digunakan untuk mengajukan cetak transkrip nilai secara resmi</small>
            </div>
        </div>
    </div>
@endsection

@section('body-content')

    <!-- Statistik Cards -->
    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-info card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-file-alt"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Total Pengajuan</p>
                <h3 class="card-title fs-21 font-weight-bold" id="stat-total-pengajuan">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-list mr-2 ml-2"></i>Semua Status
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-warning card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Sedang Diproses</p>
                <h3 class="card-title fs-21 font-weight-bold" id="stat-diproses">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-spinner mr-2 ml-2"></i>Menunggu Persetujuan
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
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Disetujui</p>
                <h3 class="card-title fs-21 font-weight-bold" id="stat-disetujui">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-check mr-2 ml-2"></i>Selesai Diproses
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-danger card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-times-circle"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Ditolak</p>
                <h3 class="card-title fs-21 font-weight-bold" id="stat-ditolak">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-ban mr-2 ml-2"></i>Perlu Pengajuan Ulang
                </div>
            </div>
        </div>
    </div>

    <!-- Info & Tombol Ajukan -->
    <div class="col-md-12">
        <div class="alert-info-custom">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h6 class="font-weight-bold mb-1">
                        <i class="fas fa-info-circle text-primary mr-2"></i>
                        Informasi Pengajuan Transkrip
                    </h6>
                    <small class="text-muted">
                        Pengajuan transkrip nilai akan melalui alur persetujuan:
                        <strong>Mahasiswa → Kaprodi → Dekan</strong>.
                        Pastikan data akademik Anda sudah lengkap sebelum mengajukan.
                    </small>
                </div>
                <div class="col-md-3 text-right">
                    <button class="btn btn-primary" id="btn-ajukan-transkrip">
                        <i class="fas fa-plus mr-2"></i>Ajukan Transkrip Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">
                                <i class="fas fa-filter mr-2"></i>Status Pengajuan
                            </label>
                            <select class="form-control select2" id="filter-status">
                                <option value="">-- Semua Status --</option>
                                <option value="draft">Draft</option>
                                <option value="diajukan">Diajukan</option>
                                <option value="proses_kaprodi">Proses Kaprodi</option>
                                <option value="proses_dekan">Proses Dekan</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">
                                <i class="fas fa-calendar-alt mr-2"></i>Tahun Pengajuan
                            </label>
                            <select class="form-control select2" id="filter-tahun">
                                <option value="">-- Semua Tahun --</option>
                                @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">
                                <i class="fas fa-search mr-2"></i>Cari
                            </label>
                            <input type="text" class="form-control" id="filter-search"
                                   placeholder="Cari nomor/keperluan pengajuan...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-primary" id="btn-filter">
                            <i class="fas fa-filter mr-2"></i>Terapkan Filter
                        </button>
                        <button class="btn btn-secondary" id="btn-reset-filter">
                            <i class="fas fa-redo mr-2"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Pengajuan -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">
                            <i class="fas fa-table mr-2"></i>Riwayat Pengajuan Transkrip
                        </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover"
                           id="table-transkrip">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th width="15%">No. Pengajuan</th>
                            <th width="20%">Keperluan</th>
                            <th width="20%">Nama Prodi</th>
                            <th width="12%" class="text-center">Tanggal Ajuan</th>
                            <th width="13%" class="text-center">Status</th>
                            <th width="15%" class="text-center">Progress</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Memuat data...</p>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('modal')

    {{-- ==================== MODAL AJUKAN TRANSKRIP ==================== --}}
    <div class="modal fade" id="modal-ajukan-transkrip" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-file-plus mr-2"></i>Ajukan Transkrip Nilai
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    {{-- Info Mahasiswa (read-only) --}}
                    <div class="card bg-light mb-4">
                        <div class="card-body py-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">NIM</small>
                                    <p class="font-weight-bold mb-1" id="form-nim">-</p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Nama Mahasiswa</small>
                                    <p class="font-weight-bold mb-1" id="form-nama">-</p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Program Studi</small>
                                    <p class="font-weight-bold mb-0" id="form-prodi">-</p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">IPK</small>
                                    <p class="font-weight-bold mb-0" id="form-ipk">0.00</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Pengajuan --}}
                    <form id="form-ajukan-transkrip" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-tag mr-1"></i>Keperluan Transkrip
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select2" id="keperluan" name="keperluan" required>
                                        <option value="">-- Pilih Keperluan --</option>
                                        <option value="Melamar Pekerjaan">Melamar Pekerjaan</option>
                                        <option value="Beasiswa">Beasiswa</option>
                                        <option value="Melanjutkan Studi">Melanjutkan Studi (S2/S3)</option>
                                        <option value="Keperluan Pribadi">Keperluan Pribadi</option>
                                        <option value="Keperluan Institusi">Keperluan Institusi</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                    <div class="invalid-feedback">Keperluan wajib dipilih</div>
                                </div>
                            </div>
                        </div>

                        {{-- Alur Persetujuan --}}
                        <div class="card border-0 bg-light">
                            <div class="card-body py-3">
                                <h6 class="font-weight-bold mb-3">
                                    <i class="fas fa-route mr-2"></i>Alur Persetujuan
                                </h6>
                                <div class="step-indicator">
                                    <div class="step-item done">
                                        <div class="step-circle"><i class="fas fa-user"></i></div>
                                        <div class="step-label">Mahasiswa</div>
                                    </div>
                                    <div class="step-item">
                                        <div class="step-circle"><i class="fas fa-user-tie"></i></div>
                                        <div class="step-label">Kaprodi</div>
                                    </div>
                                    <div class="step-item">
                                        <div class="step-circle"><i class="fas fa-user-shield"></i></div>
                                        <div class="step-label">Dekan</div>
                                    </div>
                                    <div class="step-item">
                                        <div class="step-circle"><i class="fas fa-check"></i></div>
                                        <div class="step-label">Selesai</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-simpan-ajuan">
                        <i class="fas fa-paper-plane mr-1"></i>Kirim Pengajuan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL DETAIL PENGAJUAN ==================== --}}
    <div class="modal fade" id="modal-detail-pengajuan" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-search mr-2"></i>Detail Pengajuan Transkrip
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    {{-- Nomor & Status --}}
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <small class="text-muted">Nomor Pengajuan</small>
                            <h5 class="font-weight-bold text-primary" id="detail-no-pengajuan">-</h5>
                        </div>
                        <div class="col-md-4 text-right">
                            <div id="detail-status-badge"></div>
                        </div>
                    </div>

                    <hr>

                    <!-- Informasi Detail (Rapi & Sejajar) -->
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td width="20%"><strong>Keperluan</strong></td>
                                    <td width="2%">:</td>
                                    <td width="28%" id="detail-keperluan">-</td>

                                    <td width="20%"><strong>Tanggal Ajuan</strong></td>
                                    <td width="2%">:</td>
                                    <td width="28%" id="detail-tgl-ajuan">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Tgl. Selesai</strong></td>
                                    <td>:</td>
                                    <td id="detail-tgl-selesai">-</td>

                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Alasan Tolak --}}
                    <div id="section-alasan-tolak" style="display:none;">
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Alasan Penolakan:</strong>
                            <p class="mb-0 mt-1" id="detail-alasan-tolak">-</p>
                        </div>
                    </div>

                    {{-- Progress / Timeline --}}
                    <hr>
                    <h6 class="font-weight-bold mb-3">
                        <i class="fas fa-stream mr-2"></i>Progress Persetujuan
                    </h6>
                    <div class="step-indicator" id="detail-step-indicator">
                        {{-- Rendered via JS --}}
                    </div>

                    {{-- Riwayat Aktivitas --}}
                    <hr>
                    <h6 class="font-weight-bold mb-3">
                        <i class="fas fa-history mr-2"></i>Riwayat Aktivitas
                    </h6>
                    <div class="timeline-wrapper" id="detail-timeline">
                        <div class="text-muted text-center py-3">Tidak ada riwayat</div>
                    </div>

                </div>
                <div class="modal-footer" id="detail-modal-footer">
                    {{-- Tombol Download muncul jika status disetujui --}}
                    <button type="button" class="btn btn-success d-none" id="btn-download-transkrip">
                        <i class="fas fa-download mr-1"></i>Download Transkrip
                    </button>
                    {{-- Tombol Batalkan muncul jika status masih diajukan --}}
                    <button type="button" class="btn btn-danger d-none" id="btn-batalkan-pengajuan">
                        <i class="fas fa-ban mr-1"></i>Batalkan Pengajuan
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- ==================== GLOBAL LOADING OVERLAY ==================== --}}
    <div id="global-loading-overlay" style="
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.45);
    z-index: 9999;
    justify-content: center;
    align-items: center;
">
        <div style="
        background: white;
        border-radius: 12px;
        padding: 30px 40px;
        text-align: center;
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    ">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-3 mb-0 font-weight-bold text-dark" id="loading-message">Sedang memproses...</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('adminpage/assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('adminpage/own-js/mahasiswa_page/akademik/transkrip/index.js') }}"></script>
@endpush
