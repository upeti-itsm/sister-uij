@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item active">KRS</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Kartu Rencana Studi</h1>
                <small>Halaman ini digunakan untuk memilih mata kuliah yang akan diambil</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-info card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-book"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Total Mata Kuliah</p>
                <h3 class="card-title fs-21 font-weight-bold" id="tot_matkul">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-list mr-2 ml-2"></i>Tersedia
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-success card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-check-circle"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">Mata Kuliah</p>
                <h3 class="card-title fs-21 font-weight-bold" id="tot_dipilih">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-clipboard-check mr-2 ml-2"></i>Terpilih
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-warning card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-calculator"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">SKS Dipilih</p>
                <h3 class="card-title fs-21 font-weight-bold" id="tot_sks">0</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-star mr-2 ml-2"></i><span id="sks-status">Kredit Dipilih</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats statistic-box mb-4">
            <div class="card-header card-header-info card-header-icon position-relative border-0 text-right px-3 py-0">
                <div class="card-icon d-flex align-items-center justify-content-center">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <p class="card-category text-uppercase fs-10 font-weight-bold text-muted">SKS Maksimal</p>
                <h3 class="card-title fs-21 font-weight-bold" id="sks_maksimal">24</h3>
            </div>
            <div class="card-footer p-1">
                <div class="stats">
                    <i class="fas fa-info-circle mr-2 ml-2"></i>Per Semester
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Jadwal Mata Kuliah</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold">Pencarian Mata Kuliah</label>
                            <div class="row">
                                <div class="col-md-10">
                                    <input type="text" class="form-control" placeholder="Cari Nama/Kode Mata Kuliah"
                                           id="cari-matkul">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-block btn-primary" id="btn-cari-data">
                                        <i class="fas fa-search mr-2"></i>Cari
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info mb-3">
                            <div class="row align-items-center">
                                <div class="col-md-1">
                                    <h5 class="mb-0"><i class="fas fa-info-circle text-info"></i></h5>
                                </div>
                                <div class="col-md-11">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h6 class="font-weight-bold mb-1">Informasi SKS</h6>
                                            <p class="mb-0"><small>SKS Maksimal: <strong><span id="sks-maks-info">24</span> SKS</strong> per semester</small></p>
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="font-weight-bold mb-1">SKS Terpilih</h6>
                                            <p class="mb-0"><small>Total dipilih: <strong><span id="sks-terpilih-info">0</span> SKS</strong></small></p>
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="font-weight-bold mb-1">Sisa SKS</h6>
                                            <p class="mb-0"><small>Dapat diambil: <strong><span id="sks-sisa-info">24</span> SKS</strong></small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table-jadwal">
                            <thead>
                            <tr>
                                <th class="text-center" width="5%">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th class="text-center" width="5%">No</th>
                                <th width="15%">Mata Kuliah</th>
                                <th width="10%">Kelas</th>
                                <th width="8%">SKS</th>
                                <th width="10%">Dosen</th>
                                <th width="8%">Hari</th>
                                <th width="12%">Jam</th>
                                <th width="10%">Ruang</th>
                                <th width="8%">Kapasitas</th>
                                <th width="9%">Status</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card untuk menampilkan KRS yang sudah dipilih -->
    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">KRS Terpilih</h6>
                    </div>
                    <div>
                        <button class="btn btn-danger btn-sm" id="btn-hapus-semua">
                            <i class="fas fa-trash mr-2"></i>Hapus Semua
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="table-krs-terpilih">
                        <thead>
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th width="20%">Mata Kuliah</th>
                            <th width="10%">Kelas</th>
                            <th width="8%">SKS</th>
                            <th width="15%">Dosen</th>
                            <th width="8%">Hari</th>
                            <th width="12%">Jam</th>
                            <th width="10%">Ruang</th>
                            <th width="7%">Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                        <tr class="bg-light">
                            <th colspan="3" class="text-right">Total SKS:</th>
                            <th id="total-sks">0</th>
                            <th colspan="5"></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-success btn-lg" id="btn-simpan-krs">
                    <i class="fas fa-save mr-2"></i>Simpan KRS
                </button>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="modal-konfirmasi" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="pesan-konfirmasi">Apakah Anda yakin ingin menyimpan KRS ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn-konfirmasi-ya">Ya, Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Mata Kuliah -->
    <div class="modal fade" id="modal-detail-matkul" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Mata Kuliah</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Kode Mata Kuliah:</strong></td>
                                    <td id="detail-kode-matkul">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Mata Kuliah:</strong></td>
                                    <td id="detail-nama-matkul">-</td>
                                </tr>
                                <tr>
                                    <td><strong>SKS:</strong></td>
                                    <td id="detail-sks">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Kelas:</strong></td>
                                    <td id="detail-kelas">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Dosen:</strong></td>
                                    <td id="detail-dosen">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Ruang:</strong></td>
                                    <td id="detail-ruang">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Kapasitas:</strong></td>
                                    <td id="detail-kapasitas">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Peserta:</strong></td>
                                    <td id="detail-peserta">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Keterangan:</strong>
                            <p id="detail-keterangan">-</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
    <script src="{{ asset('adminpage/own-js/mahasiswa_page/akademik/krs/krs.js') }}"></script>
@endpush
