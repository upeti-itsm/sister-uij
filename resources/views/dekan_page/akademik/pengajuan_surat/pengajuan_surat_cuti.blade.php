@extends('sidebar')

@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        .badge-status-dosen {
            background-color: #ff9800;
            color: #fff;
        }

        .badge-status-dekan {
            background-color: #4caf50;
            color: #fff;
        }

        .badge-status-disetujui {
            background-color: #4caf50;
            color: #fff;
        }

        .badge-status-ditolak {
            background-color: #f44336;
            color: #fff;
        }
    </style>
@endsection

@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item active">Persetujuan Surat</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-file-signature"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Pengesahan Surat Cuti (Dekan)</h1>
                <small>Pengesahan akhir untuk surat cuti/aktif mahasiswa.</small>
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
                            Daftar Pengajuan
                        </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label class="font-weight-bold">Pencarian</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="cari-pengajuan-dekan"
                                placeholder="Cari nomor, mahasiswa, atau NIM...">
                            <div class="input-group-append">
                                <button class="btn btn-success" id="btn-cari-dekan">
                                    <i class="fas fa-search mr-1"></i>Cari
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="font-weight-bold">Status</label>
                        <select class="select2 form-control" id="filter-status-dekan">
                            <option value="">-- Semua --</option>
                            <option value="2">Menunggu Dekan</option>
                            <option value="4">Disetujui Dekan</option>
                            <option value="5">Ditolak Dekan</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="table-pengajuan-surat-dekan">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th>No. Pengajuan</th>
                                <th>Mahasiswa</th>
                                <th>Keperluan</th>
                                <th>Tahun Akademik</th>
                                <th>Tanggal</th>
                                <th>Status</th>
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
    <div class="modal fade" id="modal-detail-dekan" tabindex="-1" role="dialog" aria-labelledby="modalDetailDekanLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailDekanLabel">Detail Pengajuan Surat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="35%">No. Pengajuan</th>
                            <td id="detail-dekan-nomor">-</td>
                        </tr>
                        <tr>
                            <th>Mahasiswa</th>
                            <td id="detail-dekan-mahasiswa">-</td>
                        </tr>
                        <tr>
                            <th>Keperluan</th>
                            <td id="detail-dekan-keperluan">-</td>
                        </tr>
                        <tr>
                            <th>Tahun Akademik</th>
                            <td id="detail-dekan-tahun-akademik">-</td>
                        </tr>
                        <tr>
                            <th>Tanggal Ajuan</th>
                            <td id="detail-dekan-tgl">-</td>
                        </tr>
                        <tr>
                            <th>Dosen PA</th>
                            <td id="detail-dekan-dpa">-</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td id="detail-dekan-status">-</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <div id="section-aksi-dekan" style="display:none;">
                        <button type="button" class="btn btn-success mr-2" id="btn-approve-dekan">
                            <i class="fas fa-check mr-1"></i>Setujui
                        </button>
                        <button type="button" class="btn btn-danger mr-2" id="btn-reject-dekan">
                            <i class="fas fa-times mr-1"></i>Tolak
                        </button>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Tutup
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
    <script src="{{ asset('adminpage/own-js/dekan_page/akademik/pengajuan_surat/pengajuan_surat_cuti.js') }}"></script>
@endpush
