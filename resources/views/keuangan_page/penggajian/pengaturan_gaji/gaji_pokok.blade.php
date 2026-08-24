@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Penggajian</li>
            <li class="breadcrumb-item active">Pengaturan Gaji Pokok</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-money-check-alt"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Pengaturan Gaji Pokok</h1>
                <small>Halaman ini digunakan untuk mengatur nominal tunjangan pendidikan per jenis karyawan</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fs-17 font-weight-600 mb-0">Pengaturan Gaji Pokok</h6>
                    <button type="button" class="btn btn-primary" id="btn-tambah-data">
                        <i class="fas fa-plus mr-1"></i> Tambah Data
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Pencarian</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" class="form-control" placeholder="Cari Data" id="cari-data">
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-block btn-primary" id="btn-cari-data"><i
                                            class="fas fa-search mr-2"></i>Cari</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Jenis Karyawan</label>
                            <select class="form-control" id="id_jenis_karyawan" style="width: 100%;"></select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">Nomor</th>
                                    <th class="text-left align-middle">Jenis Pendidikan</th>
                                    <th class="text-left align-middle">Nominal Tunjangan</th>
                                    <th class="text-center align-middle">Status</th>
                                    <th class="text-center align-middle">Pengaturan</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
    <div class="modal modal-primary fade" id="modal-tambah-data" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600">Tambah Data Tunjangan Pendidikan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Jenis Karyawan</label>
                        <select class="form-control" id="insert_id_jenis_karyawan" style="width: 100%;"></select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Jenis Pendidikan</label>
                        <select class="form-control" id="insert_kd_pendidikan" style="width: 100%;"></select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Nominal Tunjangan</label>
                        <input type="text" class="form-control" id="insert_nominal_tunjangan"
                            onkeyup="keyUpNumber('insert_nominal_tunjangan')">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn-simpan-tambah">Simpan Data</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-primary fade" id="modal-update-nominal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600">Ubah Nominal Tunjangan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Silahkan Masukkan Nominal Tunjangan Untuk <span id="nama_jenis_karyawan"
                            class="font-weight-bold"></span></p>
                    <input type="hidden" id="update_id_config_tunjangan_pendidikan">
                    <input type="hidden" id="update_sts_aktif">
                    <div class="form-group">
                        <label class="font-weight-bold">Nominal Tunjangan</label>
                        <input type="text" class="form-control" id="update_nominal_tunjangan"
                            onkeyup="keyUpNumber('update_nominal_tunjangan')">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn-simpan-nominal">Simpan Data</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('adminpage/assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminpage/own-js/keuangan_page/penggajian/pengaturan_gaji/gaji_pokok_baru.js') }}"></script>
    <script>
        function keyUpNumber(id) {
            var $this = document.getElementById(id);
            var input = $this.value;
            input = input.replace(/[\D\s\\._\-]+/g, "");
            input = input ? parseInt(input, 10) : 0;
            $this.value = input.toLocaleString("id-ID");
        }
    </script>
@endpush
