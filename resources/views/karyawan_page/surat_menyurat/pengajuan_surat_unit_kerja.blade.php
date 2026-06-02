@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(1.5em + .75rem + 2px);
        }

        .select2-container--bootstrap4 .select2-selection__rendered {
            line-height: calc(1.5em + .75rem);
        }

        #table-pengajuan-surat_wrapper {
            position: relative;
        }

        #table-pengajuan-surat_wrapper .dataTables_processing {
            position: absolute;
            top: 80%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 5;
            background: rgba(255, 255, 255, 0.85);
            padding: 6px 12px;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
    </style>
@endsection

@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Surat Menyurat</li>
            <li class="breadcrumb-item active">Pengajuan Surat</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-envelope-open-text"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Pengajuan Surat</h1>
                <small>Halaman ini digunakan untuk mengelola pengajuan surat ke rektorat</small>
            </div>
        </div>
    </div>
@endsection

@section('body-content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-header p-0 mb-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div>
                                    <h6 class="fs-17 font-weight-600 mb-0">Daftar Pengajuan Surat Unit Kerja</h6>
                                    <small class="text-muted">Menampilkan seluruh pengajuan surat yang dibuat unit
                                        kerja</small>
                                </div>
                                <a href="{{ route('karyawan.surat_menyurat.pengajuan_surat.create') }}"
                                    class="btn btn-primary mt-2 mt-md-0">
                                    <i class="fas fa-plus mr-2"></i>Tambah Pengajuan
                                </a>
                            </div>
                        </div>

                        {{-- Filter / Pencarian --}}
                        <div class="row mb-3" id="filter-collapse-surat">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="font-weight-bold">Pencarian</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari perihal surat..."
                                        id="cari-data">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" id="btn-cari-data"><i class="fas fa-search"></i>
                                            Cari</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="font-weight-bold">Filtering</label>
                                <div class="row gap-3">
                                    <div class="col-md-4 mb-2 mb-md-0">
                                        <select class="select2 form-control" id="filtering-jenis-surat">
                                            <option value="">Semua Jenis</option>
                                            @foreach ($jenis_surat as $item)
                                                <option value="{{ $item->id_jenis_surat }}">{{ $item->jenis_surat }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2 mb-md-0">
                                        <select class="select2 form-control" id="filtering-status-surat">
                                            <option value="">Semua Status</option>
                                            @foreach ($status_surat as $item)
                                                <option value="{{ $item->id_status_surat }}">{{ $item->status_surat }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="select2 form-control" id="filtering-unit-kerja">
                                            <option value="">Semua Unit</option>
                                            @foreach ($unit_kerja as $item)
                                                <option value="{{ $item->id_unit_bagian ?? $item->id_unit_kerja }}">
                                                    {{ $item->unit_kerja ?? ($item->nama_unit_kerja ?? $item->nama_unit_bagian) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tabel --}}
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="table-pengajuan-surat">
                                <thead>
                                    <tr>
                                        <th style="width:5%">No</th>
                                        <th style="width:15%">Nomor Surat</th>
                                        <th style="width:30%">Perihal</th>
                                        <th style="width:20%">Pengirim / Tanggal</th>
                                        <th style="width:15%">Status</th>
                                        <th style="width:15%"><i class="fas fa-th-large"></i></th>
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
    {{-- Modal Detail / Preview Isi Surat (Karyawan: view only) --}}
    <div class="modal fade" id="modal-detail-surat" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white font-weight-600">Detail Pengajuan Surat</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width:30%">Nomor Surat</th>
                            <td id="detail-nomor-surat">-</td>
                        </tr>
                        <tr>
                            <th>Perihal</th>
                            <td id="detail-perihal">-</td>
                        </tr>
                        <tr>
                            <th>Tanggal Surat</th>
                            <td id="detail-tanggal">-</td>
                        </tr>
                        <tr>
                            <th>Jenis Surat</th>
                            <td id="detail-jenis">-</td>
                        </tr>
                        <tr>
                            <th>Catatan Revisi</th>
                            <td id="detail-catatan-revisi">-</td>
                        </tr>
                        <tr>
                            <th>Unit Pengirim</th>
                            <td>
                                <input type="text" class="form-control" id="detail-pengirim-input" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th>Isi Surat</th>
                            <td>
                                <textarea name="editor1" id="editor1" rows="10" cols="80"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>Lampiran</th>
                            <td>
                                <a href="#" id="detail-lampiran-link" class="btn btn-sm btn-outline-primary"
                                    target="_blank" style="display:none"><i class="fas fa-paperclip mr-2"></i>Lihat
                                    Lampiran</a>
                                <span id="detail-lampiran-empty" class="text-muted">Tidak ada lampiran</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('adminpage/assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('adminpage/own-js/karyawan_page/surat_menyurat/pengajuan_surat_unit_kerja.js') }}"></script>
@endpush
