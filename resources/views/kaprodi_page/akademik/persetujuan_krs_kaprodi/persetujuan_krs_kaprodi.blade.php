@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">

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
            opacity:  1;
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
            border-radius:  6px;
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

        .badge-danger {
            background-color: #f44336;
        }

        .badge-info {
            background-color: #00bcd4;
        }

        small.text-muted {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
            display: inline-block;
            color: #6c757d ! important;
        }

        .modal-footer {
            border-top: 1px solid #eaeaea;
            background:  #fff;
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

        /* Status badge colors */
        .status-draft {
            background-color: #9e9e9e;
            color: white;
        }

        .status-menunggu {
            background-color: #ff9800;
            color: white;
        }

        .status-disetujui {
            background-color: #4caf50;
            color: white;
        }

        .status-ditolak {
            background-color: #f44336;
            color: white;
        }

        .status-selesai {
            background-color: #2196f3;
            color: white;
        }

        /* Table responsive */
        .table-responsive {
            overflow-x: auto;
        }

        /* Card stats custom */
        .card-stats {
            transition: transform 0.2s;
        }

        .card-stats:hover {
            transform:  translateY(-5px);
        }
    </style>
@endsection

@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Kaprodi</li>
            <li class="breadcrumb-item active">Persetujuan KRS</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-check-double"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Persetujuan KRS Program Studi</h1>
                <small>Halaman ini digunakan untuk persetujuan final KRS mahasiswa program studi</small>
            </div>
        </div>
    </div>
@endsection

@section('body-content')
    <!-- Filter Tahun Akademik -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-filter mr-2"></i>Filter Tahun Akademik</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label class="font-weight-bold">Pilih Tahun Akademik</label>
                        <select class="form-control select2" id="filter-tahun-akademik">
                            @if(isset($semester) && count($semester) > 0)
                                @foreach($semester as $sem)
                                    <option value="{{ $sem->id_semester }}"
                                        {{ $sem->sts_aktif == 1 ? 'selected' : '' }}>
                                        {{ $sem->tahun_akademik.' ('.$sem->nama_tahun_akademik.')' }}
                                    </option>
                                @endforeach
                            @else
                                <option value="1">Tahun Akademik Aktif</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="font-weight-bold">&nbsp;</label>
                        <button class="btn btn-block btn-primary" id="btn-filter-tahun">
                            <i class="fas fa-sync mr-2"></i>Terapkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rekap Statistics -->
    <div class="col-md-12">
        <div class="alert alert-info">
            <div class="row align-items-center">
                <div class="col-md-1 text-center">
                    <h3 class="mb-0"><i class="fas fa-info-circle"></i></h3>
                </div>
                <div class="col-md-11">
                    <div class="row">
                        <div class="col-md-3">
                            <h6 class="font-weight-bold mb-1">Total Mahasiswa Prodi</h6>
                            <p class="mb-0"><strong><span id="rekap-total-mhs">0</span> Mahasiswa</strong></p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="font-weight-bold mb-1">Sudah Mengisi KRS</h6>
                            <p class="mb-0">
                                <strong><span id="rekap-sudah-krs">0</span> Mahasiswa</strong>
                                <small class="text-muted"> (<span id="rekap-persen-sudah">0</span>%)</small>
                            </p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="font-weight-bold mb-1">Belum Mengisi KRS</h6>
                            <p class="mb-0">
                                <strong><span id="rekap-belum-krs">0</span> Mahasiswa</strong>
                                <small class="text-muted"> (<span id="rekap-persen-belum">0</span>%)</small>
                            </p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="font-weight-bold mb-1">Progress Verifikasi</h6>
                            <p class="mb-0">
                                <strong><span id="rekap-persen-verifikasi">0</span>%</strong>
                                <small class="text-muted"> Selesai</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-secondary card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-file-alt"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Draft</p>
                <h3 class="card-title fs-21 font-weight-bold" id="stat-draft">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-edit mr-2 ml-2"></i>Belum Diajukan
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-warning card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-clock"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Menunggu DPS</p>
                <h3 class="card-title fs-21 font-weight-bold" id="stat-menunggu-dps">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-user-tie mr-2 ml-2"></i>Persetujuan DPS
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-info card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Menunggu</p>
                <h3 class="card-title fs-21 font-weight-bold" id="stat-menunggu">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-clock mr-2 ml-2"></i>Persetujuan Kaprodi
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
                    <i class="fas fa-thumbs-down mr-2 ml-2"></i>Perlu Perbaikan
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-success card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-check-double"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Selesai</p>
                <h3 class="card-title fs-21 font-weight-bold" id="stat-selesai">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-flag-checkered mr-2 ml-2"></i>Disetujui
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-primary card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-list-alt"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Total KRS</p>
                <h3 class="card-title fs-21 font-weight-bold" id="stat-total">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-clipboard-list mr-2 ml-2"></i>Semua Status
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Table -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar KRS Mahasiswa Program Studi</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="font-weight-bold">Filter Status KRS</label>
                        <select class="form-control" id="filter-status">
                            <option value="">Semua Status</option>
                            <option value="0">Draft</option>
                            <option value="1">Menunggu Persetujuan DPS</option>
                            <option value="2">Disetujui DPS (Menunggu Kaprodi)</option>
                            <option value="3">Ditolak</option>
                            <option value="4">Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="font-weight-bold">Pencarian Mahasiswa</label>
                        <input type="text" class="form-control" placeholder="Cari NIM/Nama Mahasiswa" id="cari-mahasiswa">
                    </div>
                    <div class="col-md-2">
                        <label class="font-weight-bold">&nbsp;</label>
                        <button class="btn btn-block btn-primary" id="btn-cari-data">
                            <i class="fas fa-search mr-2"></i>Cari
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="table-krs-kaprodi">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center" width="3%">No</th>
                            <th width="12%">NIM</th>
                            <th width="18%">Nama Mahasiswa</th>
                            <th width="12%">DPS</th>
                            <th class="text-center" width="8%">Total MK</th>
                            <th class="text-center" width="8%">Total SKS</th>
                            <th class="text-center" width="12%">Tgl Pengajuan</th>
                            <th class="text-center" width="10%">Status</th>
                            <th class="text-center" width="12%">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="9" class="text-center">Loading...</td>
                        </tr>
                        </tbody>
                        <tfoot class="thead-light"></tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- Modal Detail KRS -->
    <div class="modal fade" id="modal-detail-krs" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-clipboard-list"></i> Detail KRS Mahasiswa
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Info Mahasiswa -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-primary mb-2">Informasi Mahasiswa</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="30%"><small class="text-muted">NIM</small></td>
                                    <td width="5%">:</td>
                                    <td><strong id="detail-nim">-</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Nama</small></td>
                                    <td>:</td>
                                    <td><strong id="detail-nama">-</strong></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Program Studi</small></td>
                                    <td>: </td>
                                    <td id="detail-prodi">-</td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Dosen Wali (DPS)</small></td>
                                    <td>:</td>
                                    <td id="detail-dps">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-primary mb-2">Informasi KRS</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="30%"><small class="text-muted">Tahun Akademik</small></td>
                                    <td width="5%">:</td>
                                    <td id="detail-ta">-</td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Tanggal Pengajuan</small></td>
                                    <td>:</td>
                                    <td id="detail-tgl-pengajuan">-</td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">Status</small></td>
                                    <td>:</td>
                                    <td><span class="badge" id="detail-status">-</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <!-- Daftar Mata Kuliah -->
                    <h6 class="font-weight-bold text-primary mb-3">Daftar Mata Kuliah</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-bordered" id="table-detail-matkul">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="15%">Kode MK</th>
                                <th width="35%">Nama Mata Kuliah</th>
                                <th class="text-center" width="10%">Kelas</th>
                                <th class="text-center" width="8%">SKS</th>
                                <th width="12%">Hari</th>
                                <th width="15%">Jam</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data</td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr class="bg-light">
                                <th colspan="4" class="text-right">Total SKS:  </th>
                                <th class="text-center" id="detail-total-sks">0</th>
                                <th colspan="2"></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Komentar DPS -->
                    <div class="row mt-3" id="section-komentar-dps">
                        <div class="col-md-12">
                            <h6 class="font-weight-bold text-primary mb-2">Komentar DPS</h6>
                            <div class="alert alert-info" id="detail-komentar-dps">
                                <i class="fas fa-comment-dots mr-2"></i>
                                <span>Belum ada komentar dari DPS</span>
                            </div>
                        </div>
                    </div>

                    <!-- Komentar Kaprodi -->
                    <div class="row mt-3" id="section-komentar-kaprodi">
                        <div class="col-md-12">
                            <h6 class="font-weight-bold text-primary mb-2">Komentar Kaprodi</h6>
                            <div class="alert alert-warning" id="detail-komentar-kaprodi">
                                <i class="fas fa-comment-dots mr-2"></i>
                                <span>Belum ada komentar dari Kaprodi</span>
                            </div>
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

    <!-- Modal Persetujuan -->
    <div class="modal fade" id="modal-persetujuan" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check-double"></i> Persetujuan Final KRS
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="approval-id-krs">

                    <div class="alert alert-info">
                        <strong>Mahasiswa:</strong> <span id="approval-nama-mhs">-</span><br>
                        <strong>NIM:</strong> <span id="approval-nim-mhs">-</span><br>
                        <strong>Total: </strong> <span id="approval-total-mk">0</span> MK | <span id="approval-total-sks">0</span> SKS
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Komentar/Catatan (Opsional)</label>
                        <textarea class="form-control" id="approval-komentar" rows="4" placeholder="Berikan catatan atau komentar untuk mahasiswa..."></textarea>
                        <small class="text-muted">Komentar ini akan dilihat oleh mahasiswa</small>
                    </div>

                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle mr-2"></i>
                        <strong>Perhatian:</strong> Setelah KRS disetujui oleh Kaprodi, KRS akan berstatus SELESAI dan mahasiswa dapat mengikuti perkuliahan sesuai KRS yang telah disetujui.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="button" class="btn btn-success" id="btn-setujui-krs">
                        <i class="fas fa-check mr-1"></i> Setujui KRS
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Penolakan -->
    <div class="modal fade" id="modal-penolakan" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-times-circle"></i> Penolakan KRS
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="reject-id-krs">

                    <div class="alert alert-info">
                        <strong>Mahasiswa:</strong> <span id="reject-nama-mhs">-</span><br>
                        <strong>NIM:</strong> <span id="reject-nim-mhs">-</span><br>
                        <strong>Total:</strong> <span id="reject-total-mk">0</span> MK | <span id="reject-total-sks">0</span> SKS
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reject-alasan" rows="4" placeholder="Jelaskan alasan penolakan KRS ini..." required></textarea>
                        <small class="text-muted">Alasan penolakan wajib diisi dan akan dilihat oleh mahasiswa dan DPS</small>
                    </div>

                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Perhatian:</strong> Setelah KRS ditolak, status KRS akan dikembalikan ke DPS untuk dilakukan revisi bersama mahasiswa.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="button" class="btn btn-danger" id="btn-tolak-krs">
                        <i class="fas fa-ban mr-1"></i> Tolak KRS
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
    <script src="{{ asset('adminpage/own-js/kaprodi_page/akademik/persetujuan_krs_kaprodi/persetujuan_krs_kaprodi.js') }}"></script>
@endpush
