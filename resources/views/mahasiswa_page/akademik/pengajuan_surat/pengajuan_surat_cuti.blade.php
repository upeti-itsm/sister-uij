@extends('sidebar')

@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        .badge-status-dosen    { background-color: #ff9800; color: #fff; }
        .badge-status-dekan    { background-color: #9c27b0; color: #fff; }
        .badge-status-disetujui{ background-color: #4caf50; color: #fff; }
        .badge-status-ditolak  { background-color: #f44336; color: #fff; }
        #table-pengajuan-surat th,
        #table-pengajuan-surat td {
            vertical-align: middle;
        }
        #table-pengajuan-surat th {
            white-space: nowrap;
        }
    </style>
@endsection

@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item active">Pengajuan Surat</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-envelope"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Pengajuan Surat Cuti</h1>
                <small>Ajukan surat cuti kuliah dan pantau status persetujuannya.</small>
            </div>
        </div>
    </div>
@endsection

@section('body-content')
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fs-17 font-weight-600 mb-0">Daftar Pengajuan</h6>
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-pengajuan-surat">
                        <i class="fas fa-plus mr-2"></i>Ajukan Surat
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="font-weight-bold">Pencarian</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="cari-pengajuan"
                                   placeholder="Cari nomor pengajuan atau keperluan...">
                            <div class="input-group-append">
                                <button class="btn btn-success" id="btn-cari-pengajuan">
                                    <i class="fas fa-search mr-1"></i>Cari
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label clas s="font-weight-bold">Status Pengajuan</label>
                        <select class="select2 form-control" id="filter-status">
                            <option value="">-- Semua Status --</option>
                            <option value="1">Diajukan Mahasiswa</option>
                            <option value="2">Disetujui DPA</option>
                            <option value="3">Ditolak DPA</option>
                            <option value="4">Disetujui Dekan</option>
                            <option value="5">Ditolak Dekan</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="table-pengajuan-surat">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th>No. Pengajuan</th>
                                <th>Keperluan</th>
                                <th>Tahun Akademik</th>
                                <th>Tanggal Ajuan</th>
                                <th class="text-center">Status</th>
                                <th class="text-center"><i class="fas fa-cogs"></i></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    {{-- Modal Ajukan --}}
    <div class="modal fade" id="modal-pengajuan-surat" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajukan Surat Cuti</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="form-pengajuan-surat">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>NIM</label>
                                    <input type="text" class="form-control" value="{{ $user->nim ?? '-' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" class="form-control" value="{{ $user->nama_lengkap ?? '-' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Program Studi</label>
                                    <input type="text" class="form-control" value="{{ $user->nama_prodi ?? '-' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tahun Akademik</label>
                                    <input type="text" class="form-control" value="{{ $semesterAktif->tahun_akademik ?? '-' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Keperluan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="keperluan" rows="3"
                                              placeholder="Tuliskan keperluan surat cuti..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('adminpage/assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminpage/own-js/mahasiswa_page/akademik/pengajuan_surat/pengajuan_surat_cuti.js') }}"></script>
@endpush
