@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">

    <style>
        .badge-tahun {
            font-size: 0.85rem;
            padding: 0.45em 0.75em;
            border-radius: 6px;
        }

        .table-krs tbody tr:hover {
            background-color: #f0f4ff;
            transition: background-color 0.2s ease;
        }

        .btn-action {
            border-radius: 6px;
            font-weight: 600;
            padding: 0.3rem 0.65rem;
            font-size: 0.8rem;
        }

        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35em 0.7em;
            border-radius: 20px;
        }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: #aaa;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #d0d0d0;
        }

        .empty-state p {
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        .card-header-action {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        @keyframes fadeInRow {
            from {
                opacity: 0;
                transform: translateY(6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .table-krs tbody tr {
            animation: fadeInRow 0.3s ease both;
        }

        .sks-bar-wrap {
            width: 100%;
            background-color: #e9ecef;
            border-radius: 4px;
            height: 6px;
            margin-top: 4px;
        }

        .sks-bar {
            height: 6px;
            border-radius: 4px;
            background-color: #1976d2;
        }

        .sks-bar.warning {
            background-color: #ff9800;
        }

        .sks-bar.danger {
            background-color: #f44336;
        }
    </style>
@endsection

@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item active">Riwayat KRS</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="media-body">
                <h1 class="font-weight-bold">Kartu Rencana Studi</h1>
                <small>Daftar riwayat KRS yang telah diproses selama masa studi Anda</small>
            </div>
        </div>
    </div>
@endsection

@section('body-content')

    {{-- ===== SUMMARY CARDS ===== --}}
    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-info card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-history"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Total Semester</p>
                <h3 class="card-title fs-21 font-weight-bold" id="tot-semester">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-list mr-2 ml-2"></i>Sudah Ditempuh
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div
                class="card-header card-header-success card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-star"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">IPK Terakhir</p>
                <h3 class="card-title fs-21 font-weight-bold" id="ipk-terakhir">0.00</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-award mr-2 ml-2"></i>Indeks Prestasi Kumulatif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div
                class="card-header card-header-warning card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-calculator"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Total SKS Lulus</p>
                <h3 class="card-title fs-21 font-weight-bold" id="tot-sks-lulus">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-check-circle mr-2 ml-2"></i>SKS Terkumpul
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div
                class="card-header card-header-danger card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-book-open"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Total MK Diambil</p>
                <h3 class="card-title fs-21 font-weight-bold" id="tot-mk">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-book mr-2 ml-2"></i>Seluruh Semester
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TABEL RIWAYAT KRS ===== --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-action">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        <i class="fas fa-list-alt mr-2 text-primary"></i>Riwayat Kartu Rencana Studi
                    </h6>
                    <small class="text-muted">Daftar KRS yang telah diproses di setiap tahun akademik</small>
                </div>
                @if($is_krs->status)
                    <div>
                        <a href="{{ route('mahasiswa.akademik.krs.index') }}"
                           class="btn btn-primary btn-sm px-4"
                           id="btn-tambah-krs"
                           data-url="{{ route('mahasiswa.akademik.krs.index') }}">
                            <i class="fas fa-plus mr-2"></i>Tambah KRS Baru
                        </a>
                    </div>
                @endif
            </div>

            <div class="card-body">

                {{-- ===== FILTER ===== --}}
                <div class="row mb-3">
                    {{-- Dropdown Tahun Akademik --}}
                    <div class="col-md-4">
                        <label class="font-weight-bold">Tahun Akademik</label>
                        <select class="form-control select2" id="filter-tahun-akademik">
                            @foreach($tahun_akademik as $item)
                                <option value="{{$item->tahun_akademik_}}">{{$item->tahun_akademik_}}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Spacer --}}
                    <div class="col-md-5"></div>

                    {{-- Tombol Cari & Reset --}}
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-primary mr-2 flex-fill" id="btn-cari-riwayat">
                            <i class="fas fa-search mr-1"></i>Cari
                        </button>
                        <button class="btn btn-secondary flex-fill" id="btn-reset-filter">
                            <i class="fas fa-undo mr-1"></i>Reset
                        </button>
                    </div>
                </div>

                {{-- Tabel --}}
                <div class="table-responsive" id="wrap-table-riwayat">
                    <table class="table table-striped table-bordered table-hover table-krs"
                           id="table-riwayat-krs">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center" width="4%">No</th>
                            <th width="18%">Tahun Akademik</th>
                            <th class="text-center" width="9%">IPS</th>
                            <th class="text-center" width="9%">IPK</th>
                            <th class="text-center" width="11%">SKS Maks</th>
                            <th class="text-center" width="13%">SKS Ditempuh</th>
                            <th class="text-center" width="12%">Jml Mata Kuliah</th>
                            <th class="text-center" width="12%">Status</th>
                            <th class="text-center" width="10%">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="9" class="text-center">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Memuat data...
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            @if($is_krs->status)
                <div class="card-footer text-right" style="display: none">
                    <a href="{{ route('mahasiswa.akademik.krs.index') }}"
                       class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-plus mr-2"></i>Tambah KRS Baru
                    </a>
                </div>
            @endif
        </div>
    </div>

@endsection

@section('modal')
@endsection

@push('scripts')
    <script src="{{ asset('adminpage/assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminpage/own-js/mahasiswa_page/akademik/krs/list_krs.js') }}"></script>
@endpush
