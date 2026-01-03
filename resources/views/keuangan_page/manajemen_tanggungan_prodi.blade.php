@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Keuangan</li>
            <li class="breadcrumb-item active">
                Manajemen Tanggungan Prodi
            </li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Manajemen Tanggungan Prodi</h1>
                <small>Halaman ini digunakan untuk mengelola data ruang perkuliahan</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Tanggungan Prodi</h6>
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
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" placeholder="Cari Tanggungan Prodi"
                                                id="cari-data">
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-block btn-primary" id="btn-cari-data"><i
                                                    class="fas fa-search mr-2"></i>Cari Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 collapse" id="form-collapse">
                        <input type="hidden" id="id_tagihan_prodi">
                        <div class="form-group">
                            <label class="font-weight-bold">Program Studi</label>
                            <select id="prodi" name="prodi" class="select2 form-control">
                                <option>- Semua Program Studi -</option>
                                @foreach ($prodi as $item)
                                    <option value="{{ $item->kd_prodi }}">{{ $item->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Jenis Tagihan</label>
                            <select id="jenis_tagihan" name="jenis_tagihan" class="select2 form-control">
                                <option>- Semua Jenis Tagihan -</option>
                                @foreach ($jenis_tagihan as $item)
                                    <option value="{{ $item->id_jenis_tagihan }}">
                                        {{ $item->jenis_tagihan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Jumlah Tagihan</label>
                            <input type="number" id="jumlah_tagihan" name="jumlah_tagihan" class="form-control"
                                placeholder="Masukkan Jumlah Tagihan">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Tipe Periodisasi</label>
                            <select id="tipe_periodisasi" name="tipe_periodisasi" class="select2 form-control">
                                <option>- Semua Tipe Periodisasi -</option>
                                @foreach ($periodisasi as $item)
                                    <option value="{{ $item->tipe_periodisasi }}">{{ $item->tipe_periodisasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Semester Mulai</label>
                            <input type="number" id="semester_mulai" name="semester_mulai" class="form-control"
                                placeholder="Masukkan Semester Mulai">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Semester Selesai</label>
                            <input type="number" id="semester_selesai" name="semester_selesai" class="form-control"
                                placeholder="Masukkan Semester Selesai">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Status</label>
                            <select id="status_tanggungan" name="status_tanggungan" class="select2 form-control">
                                <option>- Semua Status -</option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <div class="float-right">
                                <a class="btn btn-danger text-white  mr-2" id="btn-cancel">
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
                            <table class="table table-striped table-bordered table-hover" id="table">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Program Studi</th>
                                        <th>Jenis Tagihan</th>
                                        <th>Tagihan</th>
                                        <th>Semester</th>
                                        <th>Periodisasi</th>
                                        <th><i class="fas fa-th-large"></i></th>
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
    <script src="{{ asset('adminpage/own-js/keuangan_page/manajemen_tanggungan_prodi.js') }}"></script>
@endpush
