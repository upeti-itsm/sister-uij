@extends('sidebar')
@section('head-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/jquery-confirm/jquery-confirm.min.css')}}" rel="stylesheet">
    <style>
        /* Custom styles untuk input nilai */
        .mk-info-card {
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .mk-info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        .mk-info-value {
            font-size: 1.25rem;
        }

        .mk-info-label {
            font-size: 0.85rem;
            color: #6c757d;
        }

        #table .nilai-input {
            width: 100% !important;
            max-width: 100% !important;
            display: block;
            box-sizing: border-box;
        }

        .table th,
        .table td {
            vertical-align: middle !important;
        }

        .nilai-input {
            transition: all 0.3s ease;
        }

        .nilai-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .nilai-input.border-success {
            border-color: #28a745 !important;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .nilai-input.border-warning {
            border-color: #ffc107 !important;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }

        .nilai-input.border-danger {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        /* Progress indicator untuk auto-save */
        .save-indicator {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #28a745;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .save-indicator.show {
            opacity: 1;
        }

        /* Sticky header enhancement */
        .table-container {
            position: relative;
            max-height: 70vh;
            overflow-y: auto;
        }

        .table thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            z-index: 10;
            border-top: none;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 12px;
            }

            .nilai-input {
                max-width: 50px !important;
                padding: 1px 2px !important;
                font-size: 11px !important;
            }

            .btn-group .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.8rem;
            }
        }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 9999;
        }

        /* Keyboard shortcut hint */
        .shortcut-hint {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 3px;
            padding: 2px 6px;
            font-size: 11px;
            color: #6c757d;
        }

        /* Info mata kuliah styles */
        .mk-info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .mk-info-item {
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }

        .mk-info-item:last-child {
            border-right: none;
        }

        .mk-info-value {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .mk-info-label {
            font-size: 0.8rem;
            opacity: 0.9;
        }
    </style>
@endsection

@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item active">Nilai Mahasiswa</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Nilai Mahasiswa</h1>
                <small>Halaman ini digunakan untuk mengelola Nilai Mahasiswa</small>
            </div>
        </div>
    </div>
@endsection

@section('body-content')
    <div class="col-md-12">
        {{-- Loading Overlay --}}
        <div class="loading-overlay" id="loading-overlay">
            <div class="d-flex justify-content-center align-items-center h-100">
                <div class="text-center text-white">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="mt-2">Sedang memproses...</div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            {{-- Card Petunjuk Penggunaan --}}
            <div class="col-md-8 mb-3">
                <div class="card border-0 shadow-sm rounded-lg">
                    <div class="card-header bg-info text-white font-weight-bold">
                        <i class="fas fa-info-circle mr-2"></i> Petunjuk Penggunaan
                    </div>
                    <div class="card-body">
                        <ul class="mb-0 pl-3">
                            <li>Input nilai pada kolom sesuai kriteria penilaian</li>
                            <li>Gunakan <kbd>Ctrl+S</kbd> untuk menyimpan semua nilai sekaligus</li>
                            <li>Gunakan <kbd>Ctrl+R</kbd> untuk reset semua nilai</li>
                            <li>Range nilai: <span class="text-primary font-weight-bold">0 - 100</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Card Informasi Mata Kuliah --}}
            <div class="col-md-4 mb-3">
                @if(isset($mahasiswa[0]))
                    <div class="card border-0 shadow-sm rounded-lg">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3 text-primary">
                                    <i class="fas fa-book-open fa-2x"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 font-weight-bold text-dark">
                                        {{ $mahasiswa[0]->mk ?? 'Mata Kuliah' }}
                                    </h5>
                                    <div class="d-flex align-items-center">
                                <span class="badge badge-info mr-2">
                                    {{ $mahasiswa[0]->kd_mk ?? '-' }}
                                </span>
                                        <small class="text-muted">
                                            {{ $mahasiswa[0]->program_studi ?? 'Program Studi' }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row text-center">
                                <div class="col-6 border-right">
                                    <div class="mk-info-value text-primary font-weight-bold h5 mb-0">
                                        {{ $mahasiswa[0]->sks ?? 0 }}
                                    </div>
                                    <div class="mk-info-label text-muted small">SKS</div>
                                </div>
                                <div class="col-6">
                                    <div class="mk-info-value text-success font-weight-bold h5 mb-0">
                                        {{ count($mahasiswa) }}
                                    </div>
                                    <div class="mk-info-label text-muted small">Mahasiswa</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card border-0 shadow-sm rounded-lg mt-2">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="badge badge-success" id="connection-status"><i class="fas fa-wifi mr-1"></i> Online </span>
                            <small class="text-muted">
                                <span id="last-save">Belum ada perubahan</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php
            $daftar_kriteria = explode('#', $mahasiswa[0]->daftar_kriteria_text);
            $daftar_bobot = explode('#', $mahasiswa[0]->bobot_per_kriteria);
            $total_bobot = array_sum($daftar_bobot);
        @endphp
        {{-- Tabel Nilai Mahasiswa --}}
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fs-17 font-weight-600 mb-0">
                        <i class="fas fa-table mr-2"></i>Daftar Nilai Mahasiswa
                    </h6>
                    <div class="d-flex align-items-center">
                        <span class="badge badge-light mr-2" id="nilai-count">
                            <i class="fas fa-users mr-1"></i>{{ count($mahasiswa) }} Mahasiswa
                        </span>
                        <span class="badge badge-warning" id="unsaved-count" style="display: none;">
                            <i class="fas fa-clock mr-1"></i><span>0</span> Belum Tersimpan
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                {{-- Area untuk tombol aksi (akan ditambahkan via JavaScript) --}}
                <div id="action-buttons-container" class="p-3 border-bottom bg-light">
                    {{-- Tombol akan ditambahkan otomatis oleh JavaScript --}}
                </div>

                {{-- Container tabel dengan scroll --}}
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover align-middle mb-0" id="table">
                            <thead class="thead-light">
                            <tr class="text-center">
                                <th style="min-width:60px; background-color: #f8f9fa;">
                                    No
                                </th>
                                <th style="min-width:100px; background-color: #f8f9fa;">
                                    <i class="fas fa-id-card"></i> NIM
                                </th>
                                <th style="min-width:200px; background-color: #f8f9fa;" class="text-center">
                                    <i class="fas fa-user"></i> Nama Mahasiswa
                                </th>

                                {{-- Kolom kriteria dinamis --}}
                                @foreach($daftar_kriteria as $index => $kriteria)
                                    <th class="text-center" style="min-width:100px; background-color: #e3f2fd;"
                                        title="{{ trim($kriteria) }} ({{ $daftar_bobot[$index] ?? 0 }}%)">
                                        <div class="text-truncate">
                                            <small>{{ trim($kriteria) }}</small><br>
                                            <span
                                                class="badge badge-info badge-sm">{{ $daftar_bobot[$index] ?? 0 }}%</span>
                                        </div>
                                    </th>
                                @endforeach

                                <th class="text-center bg-success text-white" style="min-width:90px">
                                    <i class="fas fa-calculator"></i> Nilai Akhir
                                </th>
                                <th class="text-center bg-success text-white" style="min-width:80px">
                                    <i class="fas fa-medal"></i> Nilai Mutu
                                </th>
                                <th class="text-center bg-success text-white" style="min-width:70px">
                                    <i class="fas fa-font"></i> Huruf
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($mahasiswa as $item)
                                @php
                                    $kriteria_ids = explode('#', $item->daftar_kriteria_penilaian_id);
                                    $nilai_per_kriteria = isset($item->nilai_per_kriteria) ? explode('#', $item->nilai_per_kriteria) : [];
                                @endphp
                                <tr data-nim="{{ $item->nim }}" class="mahasiswa-row">
                                    <td class="text-center" style="font-size: 12px">{{ $item->nomor }}</td>
                                    <td class="text-center" style="font-size: 12px">{{ $item->nim }}</td>
                                    <td class="text-left" style="font-size: 12px">{{ $item->nama_mahasiswa }}</td>

                                    {{-- Input nilai per kriteria --}}
                                    @foreach($kriteria_ids as $index => $kid)
                                        <td class="text-center position-relative p-1">
                                            <input type="number"
                                                   class="form-control form-control-sm text-center nilai-input"
                                                   name="nilai[{{ $item->nim }}][{{ $kid }}]"
                                                   data-nim="{{ $item->nim }}"
                                                   data-kriteria="{{ $kid }}"
                                                   min="0" max="100" step="0.1"
                                                   value="{{ $nilai_per_kriteria[$index] ?? '' }}"
                                                   placeholder="0-100"
                                                   title="Masukkan nilai untuk {{ trim($daftar_kriteria[$index] ?? '') }}"
                                                   style="font-size:12px; padding:2px 4px;">
                                            <div class="save-indicator"></div>
                                        </td>
                                    @endforeach

                                    {{-- Nilai Akhir --}}
                                    <td class="text-center font-weight-bold bg-light nilai-akhir">
                                        @if(isset($item->nilai_mutu) && $item->nilai_mutu < 2.0)
                                            <span class="text-danger font-weight-bold">
                                                <i class="fas fa-arrow-down mr-1"></i>{{ $item->nilai_akhir ?? '-' }}
                                            </span>
                                        @else
                                            <span class="text-success font-weight-bold">
                                                <i class="fas fa-arrow-up mr-1"></i>{{ $item->nilai_akhir ?? '-' }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Nilai Mutu --}}
                                    <td class="text-center bg-light nilai-mutu">
                                        @if(isset($item->nilai_mutu) && $item->nilai_mutu < 2.0)
                                            <span class="badge badge-danger badge-lg">
                                                <i class="fas fa-times mr-1"></i>{{ $item->nilai_mutu }}
                                            </span>
                                        @else
                                            <span class="badge badge-success badge-lg">
                                                <i class="fas fa-check mr-1"></i>{{ $item->nilai_mutu ?? '-' }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Nilai Huruf --}}
                                    <td class="text-center bg-light nilai-huruf">
                                        @if(isset($item->nilai_mutu) && $item->nilai_mutu < 2.0)
                                            <span class="badge badge-danger badge-lg">
                                                {{ $item->nilai_huruf ?? '-' }}
                                            </span>
                                        @else
                                            <span class="badge badge-success badge-lg">
                                                {{ $item->nilai_huruf ?? '-' }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($daftar_kriteria) + 6 }}" class="text-center text-muted py-4">
                                        <i class="fas fa-users-slash fa-3x mb-3"></i>
                                        <h5>Tidak ada data mahasiswa</h5>
                                        <p>Belum ada mahasiswa yang terdaftar untuk mata kuliah ini</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Summary Footer --}}
                @if(count($mahasiswa) > 0)
                    <div class="card-footer bg-light border-top">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="border-right">
                                    <h6 class="mb-1 text-primary">{{ count($mahasiswa) }}</h6>
                                    <small class="text-muted">Total Mahasiswa</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border-right">
                                    <h6 class="mb-1 text-success" id="lulus-count">-</h6>
                                    <small class="text-muted">Lulus (≥2.0)</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border-right">
                                    <h6 class="mb-1 text-danger" id="tidak-lulus-count">-</h6>
                                    <small class="text-muted">Tidak Lulus (<2.0)</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h6 class="mb-1 text-warning" id="belum-nilai-count">{{ count($mahasiswa) }}</h6>
                                <small class="text-muted">Belum Dinilai</small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Hidden form for CSRF --}}
    <form id="hidden-form" style="display: none;">
        @csrf
    </form>
@endsection

@section('modal')
    {{-- Modal untuk preview export --}}
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="exportModalLabel">
                        <i class="fas fa-download mr-2"></i>Export Nilai Mahasiswa
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        File akan berisi semua data nilai mahasiswa dalam format Excel/CSV
                    </div>
                    <div class="form-group">
                        <label>Format File:</label>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="format-excel" name="export-format" class="custom-control-input"
                                   value="excel" checked>
                            <label class="custom-control-label" for="format-excel">
                                <i class="fas fa-file-excel text-success mr-1"></i>Excel (.xlsx)
                            </label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="format-csv" name="export-format" class="custom-control-input"
                                   value="csv">
                            <label class="custom-control-label" for="format-csv">
                                <i class="fas fa-file-csv text-primary mr-1"></i>CSV (.csv)
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-success" id="btn-confirm-export">
                        <i class="fas fa-download mr-1"></i>Download
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
    <script src="{{asset('adminpage/assets/plugins/sheetjs.min.js')}}"></script>

    {{-- Custom script untuk monitoring connection --}}
    <script>
        // Monitor online/offline status
        function updateConnectionStatus() {
            const statusEl = document.getElementById('connection-status');
            if (navigator.onLine) {
                statusEl.innerHTML = '<i class="fas fa-wifi mr-1"></i>Online';
                statusEl.className = 'badge badge-success';
            } else {
                statusEl.innerHTML = '<i class="fas fa-wifi-slash mr-1"></i>Offline';
                statusEl.className = 'badge badge-danger';
            }
        }

        // Update last save time
        function updateLastSave(message = 'Terakhir disimpan: ' + new Date().toLocaleTimeString()) {
            document.getElementById('last-save').textContent = message;
        }

        // Update summary counts
        function updateSummaryCount() {
            let lulusCount = 0;
            let tidakLulusCount = 0;
            let belumNilaiCount = 0;

            $('.mahasiswa-row').each(function () {
                const nilaiMutu = $(this).find('.nilai-mutu .badge').text().trim();
                if (nilaiMutu === '-' || nilaiMutu === '') {
                    belumNilaiCount++;
                } else {
                    const mutu = parseFloat(nilaiMutu);
                    if (mutu >= 2.0) {
                        lulusCount++;
                    } else {
                        tidakLulusCount++;
                    }
                }
            });

            $('#lulus-count').text(lulusCount);
            $('#tidak-lulus-count').text(tidakLulusCount);
            $('#belum-nilai-count').text(belumNilaiCount);
        }

        // Monitor events
        window.addEventListener('online', updateConnectionStatus);
        window.addEventListener('offline', updateConnectionStatus);

        // Initialize on page load
        $(document).ready(function () {
            updateConnectionStatus();
            updateSummaryCount();

            // Update summary saat ada perubahan nilai
            $(document).on('input', '.nilai-input', function () {
                setTimeout(updateSummaryCount, 100);
            });
        });

        // Expose functions globally for use in nilai_mahasiswa.js
        window.updateLastSave = updateLastSave;
        window.updateSummaryCount = updateSummaryCount;
    </script>

    <script src="{{asset('adminpage/own-js/dosen_page/akademik/nilai_mahasiswa.js')}}"></script>
@endpush
