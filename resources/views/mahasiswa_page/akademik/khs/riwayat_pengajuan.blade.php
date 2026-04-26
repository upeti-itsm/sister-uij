@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item">Hasil Studi</li>
            <li class="breadcrumb-item active">
                Riwayat Pengajuan LHS
            </li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-file-alt"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">
                    Riwayat Pengajuan LHS
                </h1>
                <small>
                    Daftar pengajuan LHS yang telah dilakukan oleh mahasiswa selama masa studi.
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
                            <i class="fas fa-history mr-2"></i>Riwayat Pengajuan
                        </h6>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-tambah-pengajuan">
                            <i class="fas fa-plus mr-2"></i>Pengajuan
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="row">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Status Pengajuan</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <select class="select2 form-control" id="filter-pengajuan">
                                                <option></option>
                                                <option value="c89f80f6-305b-4e89-b004-49c7180c7bfe">Sistem Informasi Tugas
                                                    Akhir Mahasiswa</option>
                                                <option value="7d7c0b9a-6c3b-4b75-ab50-9b74877b860a">Sistem Informasi
                                                    Terpadu Mandala</option>
                                                <option value="3c2279f3-ef87-4b6c-9a0f-c5b757cd913c">Sistem informasi
                                                    keuangan internal mandala</option>
                                                <option value="0c6ea48a-df4d-460d-9b50-55da7ac69544">Sistem Pengelolaan
                                                    Absensi Karyawan</option>
                                                <option value="519d6fa1-27e5-4ee8-b684-9ed005bbbd21">Sistem Penerimaan
                                                    Mahasiswa Baru</option>
                                                <option value="7a083612-9dbf-4e65-9ac2-9906c769841a">Sistem Pengelolaan
                                                    Sertifikat Lab Kom</option>
                                                <option value="45c36e77-7f4c-45ff-8169-e4fb606c2a65">Sistem Penganggaran
                                                    Dana</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-block btn-primary" id="btn-filter"><i
                                                    class="fas fa-filter mr-2"></i>Filter
                                            </button>
                                        </div>
                                    </div>
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
    <!-- Modal Tambah Pengajuan -->
    <div class="modal fade" id="modal-tambah-pengajuan" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahPengajuanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase" id="modalTambahPengajuanLabel">
                        Tambah Pengajuan LHS
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-tambah-pengajuan">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="tahun-akademik">Tahun Akademik</label>
                            <select class="form-control select2" id="tahun-akademik" name="tahun_akademik" required>
                                <option></option>
                                @foreach ($tahunAkademik as $tahun)
                                    <option value="{{ $tahun->id_tahun_akademik }}">
                                        {{ $tahun->tahun_akademik }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane mr-2"></i>Ajukan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Pengajuan -->
    <div class="modal fade" id="modal-detail-pengajuan" tabindex="-1" role="dialog"
        aria-labelledby="modalDetailPengajuanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase" id="modalDetailPengajuanLabel">
                        Detail Pengajuan LHS
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="detail-pengajuan-content">
                        <table class="table table-bordered">
                            <tr>
                                <th>No. Pengajuan</th>
                                <td id="detail-nomor-pengajuan">-</td>
                            </tr>
                            <tr>
                                <th>NIM</th>
                                <td id="detail-nim">-</td>
                            </tr>
                            <tr>
                                <th>Nama Mahasiswa</th>
                                <td id="detail-nama-mahasiswa">-</td>
                            </tr>
                            <tr>
                                <th>Program Studi</th>
                                <td id="detail-nama-prodi">-</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pengajuan</th>
                                <td id="detail-tanggal-pengajuan">-</td>
                            </tr>
                            <tr>
                                <th>Tahun Akademik</th>
                                <td id="detail-tahun-akademik">-</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td id="detail-status">-</td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td id="detail-keterangan">-</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
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
    <script src="{{ asset('adminpage/own-js/mahasiswa_page/akademik/khs/riwayat_pengajuan.js') }}"></script>
@endpush
