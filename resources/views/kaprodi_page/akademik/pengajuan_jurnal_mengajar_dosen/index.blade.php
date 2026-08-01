@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akdemik</li>
            <li class="breadcrumb-item active">{{ $menu }}</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-folder"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">{{ $menu }}</h1>
                <small>
                    Halaman ini digunakan untuk melihat dan memvalidasi pengajuan jurnal mengajar dosen.
                </small>
            </div>
        </div>
    </div>
@endsection

@section('body-content')
<div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Jurnal Mengajar Dosen</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    {{-- <div class="col-md-6">
                        <label class="font-weight-bold">Pencarian Dosen</label>
                        <input type="text" class="form-control" placeholder="Cari Nama Dosen" id="cari-dosen">
                    </div> --}}

                    <div class="col-12">
                        <label class="font-weight-bold">Status Pengajuan</label>
                        <select class="form-control" id="filter-status">
                            <option value="">Semua Status</option>
                            <option value="1">Draft</option>
                            <option value="2">Menunggu Persetujuan</option>
                            <option value="3">Disetujui</option>
                            <option value="5">Ditolak</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="table-pengajuan-jurnal-mengajar-dosen">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="25%">Mata Kuliah</th>
                                <th width="17%">Dosen</th>
                                <th width="15%">Catatan Pengajuan</th>
                                <th class="text-center" width="15%">Tanggal Pengajuan</th>
                                <th class="text-center" width="10%">Status</th>
                                <th class="text-center" width="13%">
                                    <i class="typcn typcn-cog" title="Aksi"></i>
                                </th>
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

@push('scripts')
    <script src="{{ asset('adminpage/assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('adminpage/own-js/kaprodi_page/akademik/pengajuan_jurnal_mengajar_dosen/page.js') }}"></script>
@endpush
