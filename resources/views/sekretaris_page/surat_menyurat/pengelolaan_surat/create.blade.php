@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Sekretaris</li>
            <li class="breadcrumb-item">
                <a href="{{ route('sek.surat_menyurat.buat_surat.index') }}" class="text-decoration-none">
                    {{ $menu }}
                </a>
            </li>
            <li class="breadcrumb-item active">Buat Surat</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-file-alt"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">
                    Buat Surat
                </h1>
                <small>
                    Halaman ini digunakan untuk mengelola surat menyurat yang digunakan oleh sekretaris dalam menjalankan
                    tugasnya.
                </small>
            </div>
        </div>
    </div>
@endsection

@section('body-content')
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">
                            <i class="fas fa-envelope-open-text mr-2"></i> Buat Surat
                        </h6>
                    </div>
                </div>
            </div>
            <form action="">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    Perihal Surat <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="perihal_surat" id="perihal-surat" class="form-control"
                                    placeholder="Masukkan perihal surat...">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    Jenis Surat <span class="text-danger">*</span>
                                </label>
                                <select class="select2 form-control" name="jenis_surat" id="jenis-surat">
                                    <option></option>
                                    @foreach ($jenis_surat as $item)
                                        <option value="{{ $item->id_jenis_surat }}">{{ $item->jenis_surat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    Tujuan Surat <span class="text-danger">*</span>
                                </label>
                                <select class="select2 form-control" name="tujuan_surat" id="tujuan-surat">
                                    <option></option>
                                    @foreach ($pimpinan_rektorat as $item)
                                        <option value="{{ $item->id_personal }}">
                                            {{ $item->jabatan_struktural }} - {{ $item->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    Isi Surat <span class="text-danger">*</span>
                                </label>
                                <textarea name="isi_surat" id="isi-surat" rows="10" cols="80"></textarea>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    Lampiran Surat <small class="text-muted">(opsional)</small>
                                </label>
                                <input type="file" name="lampiran_surat" id="lampiran-surat" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Surat
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('modal')
@endsection

@push('scripts')
    <script>
        var user = @json(session()->get('user'));
    </script>

    <script src="{{ asset('adminpage/assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('adminpage/own-js/sekretaris_page/pengelolaan_surat/create_page.js') }}"></script>
@endpush
