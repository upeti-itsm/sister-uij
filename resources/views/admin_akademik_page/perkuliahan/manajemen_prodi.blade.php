@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .btn-sm {
                padding: 0.25rem 0.4rem;
                font-size: 0.75rem;
            }
        }
    </style>
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Perkuliahan</li>
            <li class="breadcrumb-item active">
                Manajemen Prodi
            </li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-university"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Manajemen Program Studi</h1>
                <small>Halaman ini digunakan untuk mengelola data program studi</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Program Studi</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <a id="btn-tambah-data" class="btn btn-sm btn-success text-white">
                                <i class="fas fa-plus-square"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse" id="form-collapse">
                        <input type="hidden" id="id_prodi">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Kode Prodi <span class="text-danger">*</span></label>
                                    <input type="text" id="kd_prodi" name="kd_prodi" class="form-control"
                                        placeholder="Contoh: S1TI">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Nama Program Studi <span class="text-danger">*</span></label>
                                    <input type="text" id="nm_prodi" name="nm_prodi" class="form-control"
                                        placeholder="Contoh: Teknik Informatika">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Kode Dikti <span class="text-danger">*</span></label>
                                    <input type="text" id="kd_dikti" name="kd_dikti" class="form-control"
                                        placeholder="Contoh: 55201">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Jenjang <span class="text-danger">*</span></label>
                                    <select id="jenjang" name="jenjang" class="form-control select2">
                                        <option value="">-- Pilih Jenjang --</option>
                                        <option value="D3">D3</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Fakultas <span class="text-danger">*</span></label>
                                    <select id="kd_fakultas" name="kd_fakultas" class="form-control select2">
                                        <option value="">-- Pilih Fakultas --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Kode NIM <span class="text-danger">*</span></label>
                                    <input type="text" id="kd_nim" name="kd_nim" class="form-control" maxlength="2"
                                        placeholder="2 digit">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Kaprodi ID</label>
                                    <input type="text" id="kaprodi_id" name="kaprodi_id" class="form-control"
                                        placeholder="ID Kaprodi">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">No Urut Wisuda</label>
                                    <input type="number" id="no_urut_wisuda" name="no_urut_wisuda" class="form-control"
                                        placeholder="No urut wisuda">
                                </div>
                            </div>
                            <div class="col-md-4" id="div-status-aktif" style="display:none;">
                                <div class="form-group">
                                    <label class="font-weight-bold">Status Aktif</label>
                                    <select id="sts_aktif" name="sts_aktif" class="form-control">
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Status KIP</label>
                                    <div>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="sts_kip" name="sts_kip" checked>
                                            <label class="custom-control-label" for="sts_kip">Aktif</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">S2</label>
                                    <div>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="is_s2" name="is_s2">
                                            <label class="custom-control-label" for="is_s2">Ya</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="float-right">
                                <a class="btn btn-danger text-white mr-2" id="btn-cancel">
                                    <i class="fas fa-backward mr-2"></i>Batal
                                </a>
                                <a class="btn btn-primary text-white" id="btn-save">
                                    <span class='spinner-border spinner-border-sm mr-2' id='loading-tambah-data'
                                        style='display: none' role='status' aria-hidden='true'></span>
                                    <i class="fas fa-save mr-2"></i>Simpan Data
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-sm" id="table" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th>Kode Prodi</th>
                                        <th>Nama Program Studi</th>
                                        <th>Jenjang</th>
                                        <th>Kode Dikti</th>
                                        <th>Fakultas</th>
                                        <th width="8%" class="text-center">Status</th>
                                        <th width="10%" class="text-center"><i class="fas fa-cog"></i></th>
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
@endsection
@push('scripts')
    <script src="{{ asset('adminpage/assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminpage/own-js/admin_akademik/perkuliahan/manajemen_prodi.js') }}"></script>
@endpush
