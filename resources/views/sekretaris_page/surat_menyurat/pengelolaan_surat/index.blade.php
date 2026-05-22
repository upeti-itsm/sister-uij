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
            <li class="breadcrumb-item active">{{ $menu }}</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-file-alt"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">
                    {{ $menu }}
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
                            <i class="fas fa-history mr-2"></i>Riwayat Surat Menyurat
                        </h6>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-primary"
                            onclick="window.location='{{ route('sek.surat_menyurat.buat_surat.create') }}'">
                            <i class="fas fa-plus mr-2"></i>Buat Surat
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-9">
                                            <input type="text" id="cari-data" class="form-control"
                                                placeholder="Ketik di sini untuk mencari...">
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-block btn-primary" id="btn-cari-data">
                                                <i class="fas fa-search mr-2"></i>Cari
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Status Pengajuan</label>
                                    <select class="select2 form-control" id="filter-pengajuan">
                                        <option></option>
                                        @foreach ($status as $item)
                                            <option value="{{ $item->id_status_surat }}">{{ $item->status_surat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-riwayat-pengajuan"
                                id="table-riwayat-pengajuan">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center fw-bold text-uppercase">No</th>
                                        <th class="text-left fw-bold text-uppercase">No. Pengajuan</th>
                                        <th class="text-left fw-bold text-uppercase">Tanggal Pengajuan</th>
                                        <th class="text-left fw-bold text-uppercase">Tahun Akademik</th>
                                        <th class="text-left fw-bold text-uppercase">Status</th>
                                        <th class="text-left fw-bold text-uppercase">Catatan</th>
                                        <th class="text-center fw-bold text-uppercase">
                                            <i class="fas fa-cogs"></i>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
@endpush
