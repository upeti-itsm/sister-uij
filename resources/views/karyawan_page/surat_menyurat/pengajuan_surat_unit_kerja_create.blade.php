@extends('sidebar')
@section('head-css')
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
            <li class="breadcrumb-item active">{{ $is_edit ? 'Edit Pengajuan Surat' : 'Tambah Pengajuan Surat' }}</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-envelope-open-text"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">{{ $is_edit ? 'Edit Pengajuan Surat' : 'Tambah Pengajuan Surat' }}</h1>
                <small>
                    {{ $is_edit ? 'Perbaiki pengajuan surat sesuai catatan revisi.' : 'Halaman ini digunakan untuk membuat pengajuan surat unit kerja' }}
                </small>
            </div>
        </div>
    </div>
@endsection

@section('body-content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">
                            {{ $is_edit ? 'Form Edit Pengajuan Surat Unit Kerja' : 'Form Pengajuan Surat Unit Kerja' }}
                        </h6>
                        <small class="text-muted">
                            {{ $is_edit ? 'Lengkapi perubahan sebelum diajukan kembali.' : 'Unit kerja mengajukan surat melalui halaman ini' }}
                        </small>
                    </div>
                    <a href="{{ route('karyawan.surat_menyurat.pengajuan_surat.index') }}"
                        class="btn btn-danger mt-2 mt-md-0">
                        <i class="fas fa-backward mr-2"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('karyawan.surat_menyurat.pengajuan_surat.insup') }}" method="POST"
                    enctype="multipart/form-data" id="form-pengajuan-surat-unit-kerja">
                    @csrf
                    <input type="hidden" id="edit-log-surat-id" value="{{ $edit_id ?? '' }}">
                    <input type="hidden" name="id_log_surat" id="form-surat-id_log_surat">
                    <div class="row">
                        <div class="col-md-12" id="catatan-revisi-wrapper" style="display:none">
                            <div class="alert alert-warning" role="alert">
                                <strong>Catatan Revisi:</strong> <span id="catatan-revisi-text">-</span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="font-weight-bold">Perihal <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="perihal" id="form-surat-perihal"
                                    placeholder="Masukkan perihal surat">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Tanggal Surat <span class="text-danger">*</span></label>
                                <input type="text" readonly class="form-control" name="tanggal_surat"
                                    id="form-surat-tanggal" style="cursor:pointer" title="Pilih Tanggal Surat">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Jenis Surat <span class="text-danger">*</span></label>
                                <select class="select2 form-control" name="id_jenis_surat" id="form-surat-jenis_surat">
                                    <option value="">-- Pilih Jenis Surat --</option>
                                    @foreach ($jenis_surat as $item)
                                        <option value="{{ $item->id_jenis_surat }}">{{ $item->jenis_surat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Unit Pengirim <span class="text-danger">*</span></label>
                                <input type="hidden" name="unit_bagian_pengirim" value="{{ $selected_unit }}">
                                <input type="text" class="form-control" id="form-surat-unit_pengirim"
                                    value="{{ $selected_unit_label ?? '-' }}" readonly>
                                <small class="text-muted">Unit pengirim otomatis mengikuti akun yang sedang login.</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Isi Surat <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="isi_surat" id="form-surat-isi_surat" rows="12"
                                    placeholder="Tulis isi surat di sini..."></textarea>
                                <small class="text-muted">Editor dibuat lebih besar agar format surat lebih nyaman
                                    diatur.</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3 mb-0">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('karyawan.surat_menyurat.pengajuan_surat.index') }}"
                                class="btn btn-danger mr-2" id="form-surat-btn-cancel">
                                <i class="fas fa-backward mr-2"></i>Batal
                            </a>
                            <button type="button" class="btn btn-primary" id="form-surat-btn-save">
                                <span class="spinner-border spinner-border-sm mr-2" id="form-surat-loading"
                                    style="display:none" role="status" aria-hidden="true"></span>
                                <i class="fas fa-save mr-2"></i>Simpan Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('adminpage/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.id.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('adminpage/own-js/karyawan_page/surat_menyurat/pengajuan_surat_unit_kerja_create.js') }}">
    </script>
@endpush
