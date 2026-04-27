@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">

    <style>
        /* ===================== CARD STATS ===================== */
        .card-stats {
            transition: transform 0.2s;
        }

        .card-stats:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* ===================== BADGE STATUS ===================== */
        .badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35em 0.6em;
            border-radius: 6px;
        }

        .badge-status-draft {
            background-color: #9e9e9e;
            color: white;
        }

        .badge-status-diajukan {
            background-color: #2196f3;
            color: white;
        }

        .badge-status-kaprodi {
            background-color: #ff9800;
            color: white;
        }

        .badge-status-dekan {
            background-color: #4CAF50;
            color: white;
        }

        /* Diubah ke tema hijau */
        .badge-status-disetujui {
            background-color: #4caf50;
            color: white;
        }

        .badge-status-ditolak {
            background-color: #f44336;
            color: white;
        }

        /* ===================== LOADING OVERLAY ===================== */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.45);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .loading-overlay.show {
            display: flex;
        }

        .loading-spinner-box {
            background: white;
            border-radius: 12px;
            padding: 30px 40px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .loading-spinner-box .spinner-border {
            width: 3rem;
            height: 3rem;
            color: #4CAF50;
            /* Tema Hijau */
        }

        .loading-spinner-box p {
            margin-top: 14px;
            margin-bottom: 0;
            font-weight: 600;
            color: #1b5e20;
            /* Tema Hijau Gelap */
            font-size: 0.95rem;
        }

        /* Inline loading (di dalam modal / card) */
        .inline-loading {
            display: none;
            text-align: center;
            padding: 20px;
            color: #4CAF50;
            /* Tema Hijau */
        }

        .inline-loading.show {
            display: block;
        }

        .inline-loading i {
            font-size: 1.8rem;
        }

        .inline-loading p {
            margin-top: 8px;
            margin-bottom: 0;
            font-size: 0.85rem;
            color: #666;
        }

        /* ===================== MODAL ===================== */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-bottom: none;
            padding: 1.5rem;
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            /* Gradien Tema Hijau */
            color: white;
            border-radius: 12px 12px 0 0;
        }

        .modal-title {
            font-weight: 700;
        }

        .modal-body {
            padding: 1.5rem;
        }

        /* ===================== STEP INDICATOR ===================== */
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

        .step-item.done:not(:last-child)::after {
            background: #4caf50;
        }

        .step-item.active:not(:last-child)::after {
            background: #e0e0e0;
        }

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

        .step-item.done .step-circle {
            background: #4caf50;
            color: white;
            border-color: #4caf50;
        }

        .step-item.active .step-circle {
            background: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }

        /* Tema Hijau */
        .step-item.reject .step-circle {
            background: #f44336;
            color: white;
            border-color: #f44336;
        }

        .step-label {
            font-size: 0.75rem;
            margin-top: 6px;
            color: #999;
            text-align: center;
        }

        .step-item.done .step-label {
            color: #4caf50;
            font-weight: 600;
        }

        .step-item.active .step-label {
            color: #4CAF50;
            font-weight: 600;
        }

        /* Tema Hijau */
        .step-item.reject .step-label {
            color: #f44336;
            font-weight: 600;
        }

        /* ===================== TIMELINE ===================== */
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

        .timeline-item.active::before {
            background: #2196f3;
            box-shadow: 0 0 0 2px #2196f3;
        }

        .timeline-item.success::before {
            background: #4caf50;
            box-shadow: 0 0 0 2px #4caf50;
        }

        .timeline-item.danger::before {
            background: #f44336;
            box-shadow: 0 0 0 2px #f44336;
        }

        .timeline-item.warning::before {
            background: #ff9800;
            box-shadow: 0 0 0 2px #ff9800;
        }

        .timeline-item.purple::before {
            background: #4CAF50;
            box-shadow: 0 0 0 2px #4CAF50;
        }

        /* Override nama class purple di JS ke warna hijau */

        /* ===================== EMPTY STATE ===================== */
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

        /* ===================== ACTION BUTTONS ===================== */
        .btn-sahkan {
            background: linear-gradient(135deg, #43a047 0%, #2e7d32 100%);
            border: none;
            color: white;
            transition: all 0.3s;
        }

        .btn-sahkan:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(46, 125, 50, 0.4);
            color: white;
        }

        .btn-tolak {
            background: linear-gradient(135deg, #e53935 0%, #b71c1c 100%);
            border: none;
            color: white;
            transition: all 0.3s;
        }

        .btn-tolak:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(183, 28, 28, 0.4);
            color: white;
        }

        /* ===================== INFO CARDS ===================== */
        .info-mahasiswa-card {
            background: #f8f9fa;
            border-left: 4px solid #4CAF50;
            /* Tema Hijau */
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 16px;
        }

        .info-kaprodi-card {
            background: #fff8e1;
            border-left: 4px solid #ff9800;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 16px;
        }

        /* ===================== MISC ===================== */
        .filter-active-badge {
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 10px;
            background: #4CAF50;
            /* Tema Hijau */
            color: white;
            margin-left: 6px;
            vertical-align: middle;
        }

        .row-menunggu-dekan td {
            background-color: #e8f5e9 !important;
        }

        /* Hijau Sangat Muda */

        .preview-nilai-wrapper {
            max-height: 250px;
            overflow-y: auto;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
        }

        .table-preview-nilai {
            font-size: 0.8rem;
            margin-bottom: 0;
        }

        .table-preview-nilai thead th {
            background: #e8f5e9;
            /* Hijau Sangat Muda */
            color: #1b5e20;
            /* Hijau Gelap */
            font-size: 0.75rem;
            position: sticky;
            top: 0;
        }

        .ipk-mini-card {
            /* Gradien Tema Hijau */
            border-radius: 10px;
            padding: 16px;
            text-align: center;
        }

        .ipk-mini-card .ipk-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.1;
        }

        .ipk-mini-card .ipk-label {
            font-size: 0.75rem;
            opacity: 0.85;
        }

        .ipk-mini-card .ipk-predikat {
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 6px;
            padding: 2px 10px;
            border-radius: 10px;
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
        }

        /* Stat card loading pulse */
        .stat-loading {
            animation: pulse 1.2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }
        }
    </style>
@endsection

@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item active">Pengesahan Transkrip</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-stamp"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Pengesahan Transkrip Nilai</h1>
                <small>Halaman ini digunakan untuk mengesahkan atau menolak pengajuan transkrip nilai yang telah disetujui
                    Kaprodi</small>
            </div>
        </div>
    </div>
@endsection

@section('body-content')
    {{-- ==================== LOADING OVERLAY ==================== --}}
    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-spinner-box">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p id="loading-overlay-text">Memproses...</p>
        </div>
    </div>

    {{-- ==================== STATISTIK CARDS ==================== --}}
    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">
                    Menunggu Pengesahan
                </p>
                <h3 class="card-title fs-21 font-weight-bold stat-loading" id="stat-menunggu">
                    <i class="fas fa-spinner fa-spin fa-sm"></i>
                </h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-clock mr-2 ml-2"></i>Status: Proses Dekan
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-success card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-stamp"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">
                    Disahkan Dekan
                </p>
                <h3 class="card-title fs-21 font-weight-bold stat-loading" id="stat-disahkan">
                    <i class="fas fa-spinner fa-spin fa-sm"></i>
                </h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-check-double mr-2 ml-2"></i>Transkrip Sah
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
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">
                    Ditolak Dekan
                </p>
                <h3 class="card-title fs-21 font-weight-bold stat-loading" id="stat-ditolak">
                    <i class="fas fa-spinner fa-spin fa-sm"></i>
                </h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-ban mr-2 ml-2"></i>Dikembalikan
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-info card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-file-alt"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">
                    Total Pengajuan
                </p>
                <h3 class="card-title fs-21 font-weight-bold stat-loading" id="stat-total">
                    <i class="fas fa-spinner fa-spin fa-sm"></i>
                </h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-list mr-2 ml-2"></i>Semua Status
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== FILTER ==================== --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">
                                <i class="fas fa-filter mr-2"></i>Status
                            </label>
                            <select class="form-control select2" id="filter-status">
                                <option value="">-- Semua Status --</option>
                                <option value="3" selected>Menunggu Dekan</option>
                                <option value="5">Disahkan</option>
                                <option value="6">Ditolak</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">
                                <i class="fas fa-graduation-cap mr-2"></i>Program Studi
                            </label>
                            <select class="form-control select2" id="filter-prodi">
                                <option value="">-- Semua Prodi --</option>
                                {{-- Di-populate via JS --}}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">
                                <i class="fas fa-search mr-2"></i>Cari
                            </label>
                            <input type="text" class="form-control" id="filter-search"
                                placeholder="Cari NIM / nama / no. pengajuan...">
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

    {{-- ==================== TABEL ==================== --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">
                            <i class="fas fa-table mr-2"></i>
                            Daftar Pengajuan Transkrip — Pengesahan Dekan
                            <span class="filter-active-badge d-none" id="badge-filter-aktif">
                                Filter Aktif
                            </span>
                        </h6>
                    </div>
                    <div>
                        <button class="btn btn-warning btn-sm" id="btn-refresh">
                            <i class="fas fa-sync-alt mr-1"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="table-transkrip-dekan">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" width="4%">No</th>
                                <th width="14%">No. Pengajuan</th>
                                <th width="10%">NIM</th>
                                <th width="17%">Nama Mahasiswa</th>
                                <th width="13%">Program Studi</th>
                                <th width="12%">Keperluan</th>
                                <th width="10%" class="text-center">Tgl. Ajuan</th>
                                <th width="10%" class="text-center">Tgl. Diperbarui</th>
                                <th width="10%" class="text-center">Status</th>
                                <th width="6%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="10" class="text-center">
                                    <div class="inline-loading show">
                                        <i class="fas fa-spinner fa-spin" style="color: #4CAF50;"></i>
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
    {{-- ==================== MODAL DETAIL & TINDAKAN DEKAN ==================== --}}
    <div class="modal fade" id="modal-detail-dekan" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-stamp mr-2"></i>Detail Pengajuan — Pengesahan Dekan
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    {{-- Loading inline modal --}}
                    <div class="inline-loading" id="modal-detail-loading">
                        <i class="fas fa-spinner fa-spin" style="font-size:2.5rem;color:#4CAF50;"></i>
                        <p>Memuat detail pengajuan...</p>
                    </div>

                    {{-- Konten modal (tersembunyi saat loading) --}}
                    <div id="modal-detail-content" style="display:none;">

                        {{-- Nomor & Status --}}
                        <div class="row mb-3">
                            <div class="col-md-7">
                                <small class="text-muted">Nomor Pengajuan</small>
                                <h5 class="font-weight-bold mb-0" style="color:#1b5e20;" id="dkn-no-pengajuan">-</h5>
                                <small class="text-muted" id="dkn-id-riwayat">-</small>
                            </div>
                            <div class="col-md-5 text-right">
                                <div id="dkn-status-badge"></div>
                            </div>
                        </div>

                        {{-- Info Mahasiswa --}}
                        <div class="info-mahasiswa-card">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <small class="text-muted d-block">NIM</small>
                                    <strong id="dkn-nim">-</strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Nama Mahasiswa</small>
                                    <strong id="dkn-nama">-</strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Program Studi</small>
                                    <strong id="dkn-prodi">-</strong>
                                </div>
                                {{-- <div class="col-md-2">
                                    <small class="text-muted d-block">Angkatan</small>
                                    <strong id="dkn-angkatan">-</strong>
                                </div> --}}
                                <div class="col-md-2">
                                    <small class="text-muted d-block">IPK</small>
                                    <strong id="dkn-ipk" class="text-primary">-</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Info Persetujuan Kaprodi --}}
                        {{-- <div class="info-kaprodi-card">
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-user-tie mr-1"></i>Disetujui Kaprodi
                                    </small>
                                    <strong id="dkn-nama-kaprodi">-</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">
                                        Tanggal Persetujuan Kaprodi
                                    </small>
                                    <strong id="dkn-tgl-kaprodi">-</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Catatan Kaprodi</small>
                                    <span id="dkn-catatan-kaprodi"
                                          class="text-muted fst-italic">-</span>
                                </div>
                            </div>
                        </div> --}}

                        <div class="row">
                            {{-- Detail Pengajuan --}}
                            <div class="col-md-5">
                                <h6 class="font-weight-bold mb-2">
                                    <i class="fas fa-info-circle mr-2" style="color:#4CAF50;"></i>Detail Pengajuan
                                </h6>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td width="42%"><strong>Keperluan</strong></td>
                                        <td width="5%">:</td>
                                        <td id="dkn-keperluan">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Ajuan</strong></td>
                                        <td>:</td>
                                        <td id="dkn-tgl-ajuan">-</td>
                                    </tr>
                                </table>
                            </div>

                            {{-- Progress & Timeline --}}
                            <div class="col-md-7">
                                <h6 class="font-weight-bold mb-2">
                                    <i class="fas fa-stream mr-2" style="color:#4CAF50;"></i>Progress Persetujuan
                                </h6>
                                <div id="dkn-step-indicator"></div>

                                <h6 class="font-weight-bold mb-2 mt-3">
                                    <i class="fas fa-history mr-2" style="color:#4CAF50;"></i>Riwayat Aktivitas
                                </h6>
                                <div class="timeline-wrapper" id="dkn-timeline">
                                    <div class="text-muted text-center py-3">
                                        Tidak ada riwayat
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Preview Nilai --}}
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h6 class="font-weight-bold mb-2">
                                    <i class="fas fa-list-alt mr-2" style="color:#4CAF50;"></i>Preview Nilai Mahasiswa
                                    <small class="text-muted font-weight-normal ml-2">
                                        (ringkasan transkrip yang akan disahkan)
                                    </small>
                                </h6>

                                {{-- Loading preview nilai --}}
                                <div class="inline-loading" id="preview-nilai-loading">
                                    <i class="fas fa-spinner fa-spin" style="font-size:1.5rem;color:#4CAF50;"></i>
                                    <p>Memuat data nilai...</p>
                                </div>

                                <div id="preview-nilai-wrapper-content" style="display:none;">
                                    <div class="preview-nilai-wrapper">
                                        <table class="table table-striped table-bordered table-preview-nilai mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="5%">No</th>
                                                    <th width="12%">Kode MK</th>
                                                    <th width="35%">Nama Mata Kuliah</th>
                                                    <th class="text-center" width="8%">SKS</th>
                                                    <th class="text-center" width="10%">Nilai Angka</th>
                                                    <th class="text-center" width="10%">Nilai Huruf</th>
                                                    <th class="text-center" width="10%">Bobot</th>
                                                    <th class="text-center" width="10%">Tahun Akademik</th>
                                                </tr>
                                            </thead>
                                            <tbody id="dkn-preview-nilai"></tbody>
                                            <tfoot class="thead-light">
                                                <tr>
                                                    <td colspan="3" class="text-right font-weight-bold">Total</td>
                                                    <td class="text-center font-weight-bold" id="dkn-preview-total-sks">0
                                                    </td>
                                                    <td colspan="2" class="text-center font-weight-bold">IPK</td>
                                                    <td class="text-center font-weight-bold" id="dkn-preview-ipk">0.00
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Form Tindakan — hanya tampil jika status = 3 --}}
                        <div id="section-tindakan-dekan" class="mt-4" style="display:none;">
                            <hr>
                            <h6 class="font-weight-bold mb-3">
                                <i class="fas fa-gavel mr-2" style="color:#4CAF50;"></i>Tindakan Dekan
                            </h6>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            Catatan / Keterangan
                                            <span class="text-muted">
                                                (Opsional untuk pengesahan, wajib untuk penolakan)
                                            </span>
                                        </label>
                                        <textarea class="form-control" id="dekan-catatan" rows="3"
                                            placeholder="Tuliskan catatan atau alasan penolakan jika diperlukan..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>{{-- end modal-detail-content --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sahkan d-none" id="btn-sahkan-dekan">
                        <i class="fas fa-stamp mr-1"></i>Sahkan Transkrip
                    </button>
                    <button type="button" class="btn btn-tolak d-none" id="btn-tolak-dekan">
                        <i class="fas fa-times mr-1"></i>Tolak Pengajuan
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL KONFIRMASI SAHKAN ==================== --}}
    <div class="modal fade" id="modal-konfirmasi-sahkan" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #43a047 0%, #2e7d32 100%);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-stamp mr-2"></i>Konfirmasi Pengesahan
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-stamp text-success" style="font-size:3rem;"></i>
                    <p class="mt-3 font-weight-bold">Sahkan transkrip ini?</p>
                    <p class="text-muted small">
                        Transkrip akan dinyatakan <strong>sah</strong> dan
                        mahasiswa dapat mengunduh dokumen resmi.
                    </p>
                    <p class="text-muted small">
                        No: <strong id="konfirmasi-sahkan-no">-</strong>
                    </p>
                    <p class="text-muted small">
                        Mahasiswa: <strong id="konfirmasi-sahkan-nama">-</strong>
                    </p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-success btn-sm" id="btn-konfirmasi-sahkan">
                        <i class="fas fa-stamp mr-1"></i>Ya, Sahkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL KONFIRMASI TOLAK ==================== --}}
    <div class="modal fade" id="modal-konfirmasi-tolak-dekan" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #e53935 0%, #b71c1c 100%);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-times-circle mr-2"></i>Konfirmasi Penolakan
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-times-circle text-danger" style="font-size:3rem;"></i>
                    <p class="mt-3 font-weight-bold">Tolak pengajuan ini?</p>
                    <p class="text-muted small">
                        No: <strong id="konfirmasi-tolak-dekan-no">-</strong>
                    </p>
                    <p class="text-muted small">
                        Mahasiswa: <strong id="konfirmasi-tolak-dekan-nama">-</strong>
                    </p>
                    <div class="text-left mt-2">
                        <label class="font-weight-bold text-danger small">
                            Alasan Penolakan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control form-control-sm" id="alasan-tolak-dekan-final" rows="3"
                            placeholder="Tuliskan alasan penolakan..."></textarea>
                        <div class="invalid-feedback" id="alasan-tolak-dekan-error">
                            Alasan penolakan wajib diisi
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" id="btn-konfirmasi-tolak-dekan">
                        <i class="fas fa-ban mr-1"></i>Ya, Tolak
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
    <script src="{{ asset('adminpage/own-js/dekan_page/akademik/transkrip/index.js') }}"></script>
@endpush
