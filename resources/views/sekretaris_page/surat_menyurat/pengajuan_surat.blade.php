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

                    {{-- ═══════════ FORM TAMBAH / EDIT ═══════════ --}}
                    <div class="col-md-12 collapse" id="form-collapse-surat">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fs-17 font-weight-600 text-white mb-0" id="form-collapse-surat-title">
                                        Tambah Pengajuan Surat Baru
                                    </h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('sekretaris.surat_menyurat.pengajuan_surat.insup') }}"
                                    enctype="multipart/form-data" method="POST" id="form-collapse-surat-form_submit">
                                    @csrf
                                    {{-- hidden untuk mode edit --}}
                                    <input type="hidden" name="id_log_surat" id="form-surat-id_log_surat">

                                    <div class="row">
                                        {{-- Perihal --}}
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Perihal <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="perihal"
                                                    id="form-surat-perihal" placeholder="Masukkan perihal surat">
                                            </div>
                                        </div>
                                        {{-- Tanggal Surat --}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Tanggal Surat <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" readonly class="form-control" name="tanggal_surat"
                                                    id="form-surat-tanggal" style="cursor:pointer"
                                                    title="Pilih Tanggal Surat">
                                            </div>
                                        </div>
                                        {{-- Jenis Surat --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Jenis Surat <span
                                                        class="text-danger">*</span></label>
                                                <select class="select2 form-control" name="id_jenis_surat"
                                                    id="form-surat-jenis_surat">
                                                    <option value="">-- Pilih Jenis Surat --</option>
                                                    @foreach ($jenis_surat as $item)
                                                        <option value="{{ $item->id_jenis_surat }}">
                                                            {{ $item->jenis_surat }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Unit Bagian Pengirim --}}
                                        {{-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Unit / Bagian Pengirim <span
                                                        class="text-danger">*</span></label>
                                                <select class="select2 form-control" name="unit_bagian_pengirim"
                                                    id="form-surat-unit_pengirim">
                                                    <option value="">-- Pilih Unit Pengirim --</option>
                                                    @foreach ($unit_bagian as $item)
                                                        <option value="{{ $item->id_unit_bagian }}">
                                                            {{ $item->nama_unit_bagian }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --}}
                                        {{-- Pimpinan Rektorat (wajib) --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Pimpinan Rektorat <span
                                                        class="text-danger">*</span></label>
                                                <select class="select2 form-control" name="pimpinan_penerima"
                                                    id="form-surat-pimpinan_penerima">
                                                    <option value="">-- Pilih Pimpinan Rektorat --</option>
                                                    @foreach ($pimpinan_rektorat as $item)
                                                        <option value="{{ $item->id_personal }}">
                                                            {{ $item->jabatan_struktural }} - {{ $item->nama_lengkap }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">Wajib — pilih pimpinan untuk validasi
                                                    surat</small>
                                            </div>
                                        </div>
                                        {{-- Penerima: Personal --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Personal Penerima</label>
                                                <select class="select2 form-control" name="personal_penerima[]"
                                                    id="form-surat-personal_penerima" multiple="multiple">
                                                    @foreach ($karyawan as $item)
                                                        <option value="{{ $item->id_personal }}">
                                                            {{ $item->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">Opsional — pilih individu yang menerima surat
                                                    ini</small>
                                            </div>
                                        </div>
                                        {{-- Isi Surat --}}
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Isi Surat <span
                                                        class="text-danger">*</span></label>
                                                <textarea class="form-control" name="isi_surat" id="form-surat-isi_surat" rows="5"
                                                    placeholder="Tulis isi surat di sini..."></textarea>
                                            </div>
                                        </div>
                                        {{-- Lampiran (PDF, opsional) --}}
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Lampiran (PDF, maks 5MB)</label>
                                                <div class="custom-file">
                                                    <input type="file" accept=".pdf" class="custom-file-input"
                                                        id="surat-lampiran-file" name="lampiran">
                                                    <label class="custom-file-label" for="surat-lampiran-file">
                                                        Pilih file (opsional)
                                                    </label>
                                                </div>
                                                <small class="text-muted" id="info-lampiran-lama" style="display:none">
                                                    <i class="fas fa-paperclip mr-1"></i>
                                                    Sudah ada lampiran. Kosongkan jika tidak ingin mengganti.
                                                </small>
                                            </div>
                                        </div>
                                    </div>{{-- /row --}}

                                    {{-- Tombol aksi --}}
                                    <div class="form-group mt-2">
                                        <div class="float-right">
                                            <button type="button" class="btn btn-danger mr-2"
                                                id="form-surat-btn-cancel">
                                                <i class="fas fa-backward mr-2"></i>Batal
                                            </button>
                                            <button type="button" class="btn btn-primary" id="form-surat-btn-save">
                                                <span class="spinner-border spinner-border-sm mr-2"
                                                    id="form-surat-loading" style="display:none" role="status"
                                                    aria-hidden="true"></span>
                                                <i class="fas fa-save mr-2"></i>Simpan Data
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- ═══════════ /FORM ═══════════ --}}

                    {{-- ═══════════ TABEL ═══════════ --}}
                    <div class="col-md-12 collapse show" id="table-display">
                        <div class="row">
                            {{-- Filter --}}
                            <div class="col-md-12 collapse show" id="filter-collapse-surat">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Pencarian</label>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control"
                                                        placeholder="Cari perihal surat..." id="cari-data">
                                                </div>
                                                <div class="col-md-4">
                                                    <button class="btn btn-block btn-primary" id="btn-cari-data">
                                                        <i class="fas fa-search mr-2"></i>Cari
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Filtering</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <select class="select2 form-control" id="filtering-jenis-surat">
                                                        <option value="">Semua Jenis</option>
                                                        @foreach ($jenis_surat as $item)
                                                            <option value="{{ $item->id_jenis_surat }}">
                                                                {{ $item->jenis_surat }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="select2 form-control" id="filtering-status-surat">
                                                        <option value="">Semua Status</option>
                                                        @foreach ($status_surat as $item)
                                                            <option value="{{ $item->id_status_surat }}">
                                                                {{ $item->status_surat }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Tabel --}}
                            <div class="col-md-12 mt-3">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover"
                                        id="table-pengajuan-surat">
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
                    {{-- ═══════════ /TABEL ═══════════ --}}

                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    {{-- Modal Detail / Preview Isi Surat --}}
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
                            <th>Pimpinan Rektorat</th>
                            <td>
                                <select class="select2 form-control" id="detail-pimpinan-penerima">
                                    <option value="">-- Pilih Pimpinan Rektorat --</option>
                                    @foreach ($pimpinan_rektorat as $item)
                                        <option value="{{ $item->id_personal }}">
                                            {{ $item->jabatan_struktural }} - {{ $item->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btn-teruskan-pimpinan">
                        <i class="fas fa-paper-plane mr-2"></i>Teruskan ke Pimpinan
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
    <script src="{{ asset('adminpage/own-js/sekretaris_page/surat_menyurat/pengajuan_surat.js') }}"></script>
    <script>
        window.CKEDITOR_BASEPATH = "{{ asset('adminpage/assets/plugins/ckeditor/') }}/";
    </script>
    <script src="{{ asset('adminpage/assets/plugins/ckeditor/ckeditor.js') }}"></script>
@endpush
