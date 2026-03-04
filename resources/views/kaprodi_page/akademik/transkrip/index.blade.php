@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">

    <style>
        /* ===== Loading Overlay ===== */
        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        .loading-overlay .spinner-box {
            background: white;
            border-radius: 12px;
            padding: 32px 48px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0,0,0,.2);
        }
        .loading-overlay .spinner-box p {
            margin: 12px 0 0;
            font-weight: 600;
            color: #555;
        }

        /* ===== Card Stats ===== */
        .card-stats { transition: transform .2s; }
        .card-stats:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0,0,0,.1);
        }

        /* ===== Badge Status ===== */
        .badge { font-size:.75rem; font-weight:600; padding:.35em .6em; border-radius:6px; }
        .badge-status-draft      { background-color:#607d8b; color:white; }
        .badge-status-diajukan   { background-color:#2196f3; color:white; }
        .badge-status-kaprodi    { background-color:#ff9800; color:white; }
        .badge-status-dekan      { background-color:#9c27b0; color:white; }
        .badge-status-disetujui  { background-color:#4caf50; color:white; }
        .badge-status-ditolak    { background-color:#f44336; color:white; }
        .badge-status-dibatalkan { background-color:#9e9e9e; color:white; }

        /* ===== Modal ===== */
        .modal-content {
            border-radius:12px; border:none;
            box-shadow:0 6px 25px rgba(0,0,0,.15);
        }
        .modal-header {
            border-bottom:none; padding:1.5rem;
            background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
            color:white; border-radius:12px 12px 0 0;
        }
        .modal-title { font-weight:700; }
        .modal-body  { padding:1.5rem; }

        /* ===== Step Indicator ===== */
        .step-indicator {
            display:flex; align-items:center; justify-content:center;
            gap:0; margin:20px 0;
        }
        .step-item {
            display:flex; flex-direction:column; align-items:center;
            flex:1; position:relative;
        }
        .step-item:not(:last-child)::after {
            content:''; position:absolute; top:18px; left:50%;
            width:100%; height:2px; background:#e0e0e0; z-index:0;
        }
        .step-item.done:not(:last-child)::after   { background:#4caf50; }
        .step-item.active:not(:last-child)::after { background:#e0e0e0; }

        .step-circle {
            width:36px; height:36px; border-radius:50%;
            background:#e0e0e0; color:#999;
            display:flex; align-items:center; justify-content:center;
            font-weight:700; font-size:.85rem; z-index:1;
            border:3px solid #e0e0e0;
        }
        .step-item.done   .step-circle { background:#4caf50; color:white; border-color:#4caf50; }
        .step-item.active .step-circle { background:#ff9800; color:white; border-color:#ff9800; }
        .step-item.reject .step-circle { background:#f44336; color:white; border-color:#f44336; }

        .step-label { font-size:.75rem; margin-top:6px; color:#999; text-align:center; }
        .step-item.done   .step-label { color:#4caf50; font-weight:600; }
        .step-item.active .step-label { color:#ff9800; font-weight:600; }
        .step-item.reject .step-label { color:#f44336; font-weight:600; }

        /* ===== Timeline ===== */
        .timeline-wrapper { position:relative; padding-left:30px; }
        .timeline-wrapper::before {
            content:''; position:absolute; left:12px; top:0; bottom:0;
            width:2px; background:#e0e0e0;
        }
        .timeline-item { position:relative; margin-bottom:20px; }
        .timeline-item::before {
            content:''; position:absolute; left:-24px; top:6px;
            width:14px; height:14px; border-radius:50%;
            background:#ccc; border:2px solid white; box-shadow:0 0 0 2px #ccc;
        }
        .timeline-item.active::before  { background:#2196f3; box-shadow:0 0 0 2px #2196f3; }
        .timeline-item.success::before { background:#4caf50; box-shadow:0 0 0 2px #4caf50; }
        .timeline-item.danger::before  { background:#f44336; box-shadow:0 0 0 2px #f44336; }
        .timeline-item.warning::before { background:#ff9800; box-shadow:0 0 0 2px #ff9800; }

        /* ===== Empty State ===== */
        .empty-state { text-align:center; padding:40px; color:#999; }
        .empty-state i { font-size:4rem; margin-bottom:20px; opacity:.3; }

        /* ===== Action Buttons ===== */
        .btn-setujui {
            background:linear-gradient(135deg,#43a047 0%,#2e7d32 100%);
            border:none; color:white; transition:all .3s;
        }
        .btn-setujui:hover {
            transform:translateY(-2px);
            box-shadow:0 4px 15px rgba(46,125,50,.4); color:white;
        }
        .btn-tolak {
            background:linear-gradient(135deg,#e53935 0%,#b71c1c 100%);
            border:none; color:white; transition:all .3s;
        }
        .btn-tolak:hover {
            transform:translateY(-2px);
            box-shadow:0 4px 15px rgba(183,28,28,.4); color:white;
        }

        /* ===== Info Card ===== */
        .info-mahasiswa-card {
            background:#f8f9fa; border-left:4px solid #667eea;
            border-radius:6px; padding:12px 16px; margin-bottom:16px;
        }

        /* ===== Filter ===== */
        .filter-active-badge {
            font-size:.7rem; padding:2px 8px; border-radius:10px;
            background:#667eea; color:white; margin-left:6px; vertical-align:middle;
        }

        /* ===== Highlight Menunggu ===== */
        .row-menunggu td { background-color:#fff8e1 !important; }

        /* ===== Preview Nilai ===== */
        .preview-nilai-wrapper {
            max-height:250px; overflow-y:auto;
            border:1px solid #e0e0e0; border-radius:6px;
        }
        .table-preview-nilai { font-size:.8rem; margin-bottom:0; }
        .table-preview-nilai thead th {
            background:#e8eaf6; color:#1a237e; font-size:.75rem;
            position:sticky; top:0;
        }
    </style>
@endsection

@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item active">Persetujuan Transkrip</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-file-signature"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Persetujuan Transkrip Nilai</h1>
                <small>Halaman ini digunakan untuk menyetujui atau menolak pengajuan transkrip nilai mahasiswa</small>
            </div>
        </div>
    </div>
@endsection

@section('body-content')

    {{-- ==================== LOADING OVERLAY ==================== --}}
    <div class="loading-overlay" id="global-loading" style="display:none;">
        <div class="spinner-box">
            <div class="spinner-border text-primary" style="width:3rem;height:3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p id="global-loading-text">Memuat data...</p>
        </div>
    </div>

    {{-- ==================== STATISTIK CARDS ==================== --}}
    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-warning card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Menunggu Persetujuan</p>
                <h3 class="card-title fs-21 font-weight-bold" id="stat-menunggu">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats"><i class="fas fa-clock mr-2 ml-2"></i>Perlu Ditindaklanjuti</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-success card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-check-double"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Disetujui Kaprodi</p>
                <h3 class="card-title fs-21 font-weight-bold" id="stat-disetujui">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats"><i class="fas fa-check mr-2 ml-2"></i>Diteruskan ke Dekan</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-danger card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-times-circle"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Ditolak Kaprodi</p>
                <h3 class="card-title fs-21 font-weight-bold" id="stat-ditolak">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats"><i class="fas fa-ban mr-2 ml-2"></i>Dikembalikan ke Mahasiswa</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-info card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-file-alt"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Total Pengajuan</p>
                <h3 class="card-title fs-21 font-weight-bold" id="stat-total">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats"><i class="fas fa-list mr-2 ml-2"></i>Semua Status</div>
            </div>
        </div>
    </div>

    {{-- ==================== FILTER ==================== --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">
                                <i class="fas fa-filter mr-2"></i>Status
                            </label>
                            <select class="form-control select2" id="filter-status">
                                <option value="">-- Semua Status --</option>
                                <option value="1">Draft</option>
                                <option value="2" selected>Diajukan</option>
                                <option value="3">Proses Kaprodi</option>
                                <option value="4">Proses Dekan</option>
                                <option value="5">Disetujui</option>
                                <option value="6">Ditolak</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
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
                                <i class="fas fa-university mr-2"></i>Program Studi
                            </label>
                            <select class="form-control select2" id="filter-prodi">
                                <option value="">-- Semua Prodi --</option>
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
                    <div class="col-md-2 d-flex align-items-end pb-3">
                        <button class="btn btn-primary mr-2" id="btn-filter">
                            <i class="fas fa-filter mr-1"></i>Filter
                        </button>
                        <button class="btn btn-secondary" id="btn-reset-filter">
                            <i class="fas fa-redo mr-1"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== TABEL PENGAJUAN ==================== --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">
                            <i class="fas fa-table mr-2"></i>
                            Daftar Pengajuan Transkrip Mahasiswa
                            <span class="filter-active-badge d-none" id="badge-filter-aktif">Filter Aktif</span>
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
                    <table class="table table-striped table-bordered table-hover"
                           id="table-transkrip-kaprodi">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center" width="4%">No</th>
                            <th width="14%">No. Pengajuan</th>
                            <th width="10%">NIM</th>
                            <th width="20%">Nama Mahasiswa</th>
                            <th width="15%">Program Studi</th>
                            <th width="14%">Keperluan</th>
                            <th class="text-center" width="10%">Tgl. Ajuan</th>
                            <th class="text-center" width="10%">Status</th>
                            <th class="text-center" width="8%">Aksi</th>
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
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('modal')

    {{-- ==================== MODAL DETAIL & TINDAKAN ==================== --}}
    <div class="modal fade" id="modal-detail-kaprodi" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-file-signature mr-2"></i>Detail Pengajuan Transkrip
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
                            <h5 class="font-weight-bold text-primary mb-0" id="kpd-no-pengajuan">-</h5>
                            <small class="text-muted" id="kpd-id-pengajuan">-</small>
                        </div>
                        <div class="col-md-4 text-right">
                            <div id="kpd-status-badge"></div>
                        </div>
                    </div>

                    {{-- Info Mahasiswa --}}
                    <div class="info-mahasiswa-card">
                        <div class="row">
                            <div class="col-md-3">
                                <small class="text-muted d-block">NIM</small>
                                <strong id="kpd-nim">-</strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block">Nama Mahasiswa</small>
                                <strong id="kpd-nama">-</strong>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block">Program Studi</small>
                                <strong id="kpd-prodi">-</strong>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted d-block">IPK</small>
                                <strong id="kpd-ipk" class="text-primary">-</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Detail Pengajuan --}}
                        <div class="col-md-6">
                            <h6 class="font-weight-bold mb-2">
                                <i class="fas fa-info-circle text-primary mr-2"></i>Detail Pengajuan
                            </h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="40%"><strong>Keperluan</strong></td>
                                    <td width="5%">:</td>
                                    <td id="kpd-keperluan">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Bahasa</strong></td>
                                    <td>:</td>
                                    <td id="kpd-bahasa">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Lembar</strong></td>
                                    <td>:</td>
                                    <td id="kpd-jumlah-lembar">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Email Tujuan</strong></td>
                                    <td>:</td>
                                    <td id="kpd-email-tujuan">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Ajuan</strong></td>
                                    <td>:</td>
                                    <td id="kpd-tgl-ajuan">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Terakhir Diperbarui</strong></td>
                                    <td>:</td>
                                    <td id="kpd-tgl-updated">-</td>
                                </tr>
                            </table>
                            <label class="font-weight-bold">Catatan Mahasiswa:</label>
                            <p class="text-muted border rounded p-2" id="kpd-catatan-mhs">-</p>
                        </div>

                        {{-- Progress --}}
                        <div class="col-md-6">
                            <h6 class="font-weight-bold mb-2">
                                <i class="fas fa-stream text-primary mr-2"></i>Progress Persetujuan
                            </h6>
                            <div id="kpd-step-indicator"></div>

                            <h6 class="font-weight-bold mb-2 mt-3">
                                <i class="fas fa-history text-primary mr-2"></i>Riwayat Aktivitas
                            </h6>
                            <div class="timeline-wrapper" id="kpd-timeline">
                                <div class="text-muted text-center py-3">Tidak ada riwayat</div>
                            </div>
                        </div>
                    </div>

                    {{-- Preview Nilai Mahasiswa --}}
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6 class="font-weight-bold mb-2">
                                <i class="fas fa-list-alt text-primary mr-2"></i>
                                Preview Nilai Mahasiswa
                                <small class="text-muted font-weight-normal ml-2">
                                    (ringkasan transkrip yang akan dicetak)
                                </small>
                            </h6>
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
                                    <tbody id="kpd-preview-nilai">
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-3">
                                            <i class="fas fa-spinner fa-spin mr-2"></i>Memuat data nilai...
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot class="thead-light">
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold">Total</td>
                                        <td class="text-center font-weight-bold" id="kpd-preview-total-sks">0</td>
                                        <td colspan="2" class="text-center font-weight-bold">IPK</td>
                                        <td class="text-center font-weight-bold" id="kpd-preview-ipk">0.00</td>
                                        <td></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Form Tindakan (hanya tampil jika status = 2 / Diajukan) --}}
                    <div id="section-tindakan-kaprodi" class="mt-4" style="display:none;">
                        <hr>
                        <h6 class="font-weight-bold mb-3">
                            <i class="fas fa-gavel text-warning mr-2"></i>Tindakan Kaprodi
                        </h6>
                        <div class="form-group">
                            <label class="font-weight-bold">
                                Catatan / Keterangan
                                <span class="text-muted font-weight-normal">
                                    (Opsional untuk persetujuan, wajib untuk penolakan)
                                </span>
                            </label>
                            <textarea class="form-control" id="kaprodi-catatan" rows="3"
                                      placeholder="Tuliskan catatan atau alasan penolakan jika diperlukan..."></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-setujui d-none" id="btn-setujui-kaprodi">
                        <i class="fas fa-check mr-1"></i>Setujui & Teruskan ke Dekan
                    </button>
                    <button type="button" class="btn btn-tolak d-none" id="btn-tolak-kaprodi">
                        <i class="fas fa-times mr-1"></i>Tolak Pengajuan
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL KONFIRMASI SETUJUI ==================== --}}
    <div class="modal fade" id="modal-konfirmasi-setujui" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header"
                     style="background:linear-gradient(135deg,#43a047 0%,#2e7d32 100%);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-check-circle mr-2"></i>Konfirmasi Persetujuan
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-check-circle text-success" style="font-size:3rem;"></i>
                    <p class="mt-3 font-weight-bold">Setujui pengajuan ini?</p>
                    <p class="text-muted small">
                        Pengajuan akan diteruskan ke <strong>Dekan</strong>
                        untuk persetujuan selanjutnya.
                    </p>
                    <p class="text-muted small">
                        No: <strong id="konfirmasi-no-pengajuan">-</strong><br>
                        Nama: <strong id="konfirmasi-nama-mahasiswa">-</strong>
                    </p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-success btn-sm" id="btn-konfirmasi-setujui">
                        <i class="fas fa-check mr-1"></i>Ya, Setujui
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL KONFIRMASI TOLAK ==================== --}}
    <div class="modal fade" id="modal-konfirmasi-tolak" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header"
                     style="background:linear-gradient(135deg,#e53935 0%,#b71c1c 100%);">
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
                        No: <strong id="konfirmasi-tolak-no">-</strong><br>
                        Nama: <strong id="konfirmasi-tolak-nama">-</strong>
                    </p>
                    <div class="text-left mt-2">
                        <label class="font-weight-bold text-danger small">
                            Alasan Penolakan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control form-control-sm" id="alasan-tolak-final"
                                  rows="3"
                                  placeholder="Tuliskan alasan penolakan..."></textarea>
                        <div class="invalid-feedback" id="alasan-tolak-error"
                             style="display:none;">
                            Alasan penolakan wajib diisi
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" id="btn-konfirmasi-tolak">
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
    <script src="{{ asset('adminpage/own-js/kaprodi_page/akademik/transkrip/index.js') }}"></script>
@endpush
