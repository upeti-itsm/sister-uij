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
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Pengaturan Gaji Pokok</h1>
                <small>
                    Halaman ini digunakan untuk melakukan pengaturan konfigurasi gaji pokok pada masing-masing golongan
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
                        <h6 class="fs-17 font-weight-600 mb-0">Pengaturan Gaji Pokok</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            @include('partials.pengaturan_gaji_dropdown', ['title' => 'Pengaturan Gaji Pokok'])
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row align-items-end" id="main-display">
                            <div class="col-md-5">
                                <div class="form-group mb-md-0">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari Golongan..." id="cari-data">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" id="btn-cari-data" type="button">
                                                <i class="fas fa-search mr-1"></i> Cari
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-md-0">
                                    <label class="font-weight-bold">Status</label>
                                    <select class="form-control select2" id="status">
                                        <option value=""></option>
                                        <option value="true">Aktif</option>
                                        <option value="false">Nonaktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-md-0">
                                    <button class="btn btn-success btn-block" title="Import Gaji Pokok" id="btn-import" type="button">
                                        <i class="fas fa-file-excel mr-1"></i> Import Gaji Pokok
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table id="ohmytable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">#</th>
                                    <th class="text-center align-middle">INFORMASI GOLONGAN</th>
                                    <th class="text-left align-middle">GAJI POKOK</th>
                                    <th class="text-left align-middle">STATUS</th>
                                    <th class="text-center">AKSI</th>
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
    <div class="modal modal-primary fade" id="modal-insup-golongan" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600" id="insupGolonganLabel">Ubah Data Golongan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-insup-golongan">
                        @csrf
                        <input type="hidden" name="id_golongan" id="insup_id_golongan">
                        <div class="form-group">
                            <label class="font-weight-bold">Golongan</label>
                            <input type="text" class="form-control" name="golongan" id="insup_golongan" placeholder="Contoh: III/A" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Masa Kerja (Tahun)</label>
                            <input type="number" class="form-control" name="masa_kerja" id="insup_masa_kerja" min="0" placeholder="Contoh: 4" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Gaji Pokok (Rp)</label>
                            <input type="text" class="form-control" name="gaji_pokok" id="insup_gaji_pokok" onkeyup="keyUpNumber('insup_gaji_pokok')" placeholder="Contoh: 3.500.000" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn-simpan-golongan">
                        <i class="fas fa-save mr-1"></i> Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-primary fade" id="modal-import-excel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600">Import Data Gaji Pokok</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('keuangan.penggajian.pengaturan_gaji.pengaturan_gaji_pokok.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <a href="{{ route('keuangan.penggajian.pengaturan_gaji.pengaturan_gaji_pokok.download_template') }}" class="btn btn-info btn-block font-weight-bold">
                                <i class="fas fa-file-download mr-2"></i> Download Template Excel
                            </a>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-1"></i> <strong>Petunjuk Format Excel:</strong>
                            <ul class="mb-0 pl-3 mt-1">
                                <li><strong>Kolom A:</strong> Golongan (contoh: <code>III/A</code>)</li>
                                <li><strong>Kolom B:</strong> Masa Kerja dalam Tahun (contoh: <code>4</code>)</li>
                                <li><strong>Kolom C:</strong> Gaji Pokok (contoh: <code>3500000</code>)</li>
                            </ul>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Pilih File Excel (.xlsx, .xls, .csv)</label>
                            <div class="custom-file">
                                <input type="file" name="file_excel" class="custom-file-input" id="file_excel_input" accept=".xlsx,.xls,.csv" required>
                                <label class="custom-file-label" for="file_excel_input">Pilih file Excel...</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-upload mr-1"></i> Upload & Import
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
    <script src="{{ asset('adminpage/assets/plugins/numeral/numeral.min.js') }}"></script>
    <script src="{{ asset('adminpage/own-js/keuangan_page/penggajian/pengaturan_gaji/gaji_pokok.js') }}"></script>
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
