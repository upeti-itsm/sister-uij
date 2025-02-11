@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Surat Menyurat</li>
            <li class="breadcrumb-item active">Surat Keputusan</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Surat Keputusan</h1>
                <small>Halaman ini digunakan untuk mengelola surat keputusan di STIE Mandala</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse" id="form-collapse-sk">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fs-17 font-weight-600 text-white mb-0">Tambah Data Surat Keputusan
                                            Baru</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form
                                            action="{{route('sekretaris.surat_menyurat.surat_keputusan.insert_sk')}}"
                                            enctype="multipart/form-data" method="POST"
                                            id="form-collapse-sk-form_submit">
                                            @csrf
                                            <div class="row">
                                                <input type="hidden" id="form-collapse-sk-id_sk">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Nomor SK</label>
                                                        <input type="text" class="form-control"
                                                               placeholder="Masukkan Nomor SK" name="nomor"
                                                               id="form-collapse-sk-nomor_sk">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Tanggal</label>
                                                        <input type="text" readonly class="form-control"
                                                               id="form-collapse-sk-tgl_sk" name="tgl"
                                                               style="cursor: pointer" title="Pilih Tanggal SK">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Nama SK</label>
                                                        <input type="text" class="form-control" name="nama_sk"
                                                               placeholder="Masukkan Nama Surat Keputusan"
                                                               id="form-collapse-sk-nama_sk">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Akses Surat</label>
                                                        <select class="select2 form-control"
                                                                id="pilihan-akses-surat-keputusan"
                                                                name="pilihan_akses">
                                                            <option value="-2">-- Spesifik Civitas Akademik --</option>
                                                            <option value="-1">-- Semua Civitas (Karyawan, Dosen Tetap,
                                                                Dosen LB) --
                                                            </option>
                                                            <option value="1">-- Semua Dosen (Tetap dan LB) --</option>
                                                            <option value="2">-- Dosen Tetap --</option>
                                                            <option value="3">-- Dosen LB --</option>
                                                            <option value="4">-- Semua Karyawan Non-Dosen --</option>
                                                        </select>
                                                        <div class="mt-2" id="akses-display">
                                                            <select class="form-control select2 placeholder-multiple"
                                                                    id="penerima_surat"
                                                                    name="akses[]"
                                                                    multiple="multiple">
                                                                @foreach($karyawan AS $item)
                                                                    <option
                                                                        value="{{$item->id_personal}}">{{$item->nama}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <small class="text-danger">* Civitas akademik yang dipilih akan
                                                            bisa melihat
                                                            dan mengunduh arsip surat</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group" style="text-align: left">
                                                        <label class="font-weight-bold">Unggah File Arsip Surat
                                                            (PDF)</label>
                                                        <div class="custom-file">
                                                            <input type="file" accept=".pdf"
                                                                   class="custom-file-input"
                                                                   required id="SK_File" name="path">
                                                            <label class="custom-file-label" for="customFile">Choose
                                                                file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="float-right">
                                                <button class="btn btn-danger text-white  mr-2"
                                                        id="form-collapse-sk-btn-cancel"><i
                                                        class="fas fa-backward mr-2"></i>Batal
                                                </button>
                                                <button class="btn btn-primary text-white"
                                                        id="form-collapse-sk-btn-save"><span
                                                        class='spinner-border spinner-border-sm mr-2'
                                                        id='form-collapse-keluar-loading-tambah-data'
                                                        style='display: none'
                                                        role='status'
                                                        aria-hidden='true'></span><i
                                                        class="fas fa-save mr-2"></i>Simpan Data
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 collapse show" id="table-display">
                        <div class="row">
                            <div class="col-md-12 collapse show" id="filter-collapse-sk">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Pencarian</label>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control"
                                                           placeholder="Cari Nama SK"
                                                           id="cari-data">
                                                </div>
                                                <div class="col-md-4">
                                                    <button class="btn btn-block btn-primary"
                                                            id="btn-cari-data">
                                                        <i
                                                            class="fas fa-search mr-2"></i>Cari Data
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Filtering</label>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <select class="select2 form-control"
                                                            id="filtering-tahun-sk">
                                                        @if(!empty($tahun_sk))
                                                            @foreach($tahun_sk AS $item)
                                                                <option
                                                                    value="{{$item->tahun_sk}}">{{$item->tahun_sk}}</option>
                                                            @endforeach
                                                        @else
                                                            <option value="">Tidak Ada Data</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-7">
                                                    <button class="btn btn-block btn-danger"
                                                            id="btn-tambah-sk"><i
                                                            class="fas fa-cloud-upload-alt mr-2"></i>Tambah Surat
                                                        Keputusan (SK)
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover"
                                           id="table-sk">
                                        <thead>
                                        <tr>
                                            <th>Nomor</th>
                                            <th>Nomor SK</th>
                                            <th>Nama SK</th>
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
        </div>
    </div>
@endsection
@section('modal')

@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.id.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/sekretaris_page/surat_menyurat/surat_keputusan.js')}}"></script>
@endpush
