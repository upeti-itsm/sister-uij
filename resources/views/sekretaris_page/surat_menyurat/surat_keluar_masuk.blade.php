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
            <li class="breadcrumb-item active">Surat Keluar Masuk</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Surat Keluar Masuk</h1>
                <small>Halaman ini digunakan untuk mengelola surat keluar masuk di STIE Mandala</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse" id="form-collapse-keluar">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fs-17 font-weight-600 text-white mb-0">Tambah Data Surat Keluar
                                            Baru</h6>
                                        <input type="hidden" id="jenis_surat" value="{{$jenis_surat}}">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form
                                            action="{{route('sekretaris.surat_menyurat.surat_keluar_masuk.insert_surat_keluar')}}"
                                            enctype="multipart/form-data" method="POST"
                                            id="form-collapse-keluar-form_submit">
                                            @csrf
                                            <div class="row">
                                                <input type="hidden" id="form-collapse-keluar-id_surat">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Nomor Surat</label>
                                                        <input type="text" class="form-control"
                                                               placeholder="Masukkan Nomor Surat" name="nomor"
                                                               id="form-collapse-keluar-nomor_surat">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Tanggal</label>
                                                        <input type="text" readonly class="form-control"
                                                               id="form-collapse-keluar-tgl_surat" name="tgl"
                                                               style="cursor: pointer" title="Pilih Tanggal Surat">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Kode</label>
                                                        <input type="text" class="form-control" name="kode"
                                                               placeholder="Masukkan Kode"
                                                               id="form-collapse-keluar-kode">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Perihal</label>
                                                        <input type="text" class="form-control" name="perihal"
                                                               placeholder="Masukkan Perihal Surat"
                                                               id="form-collapse-keluar-perihal">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Penerima</label>
                                                        <input type="text" class="form-control"
                                                               id="form-collapse-keluar-alamat_tujuan" name="penerima"
                                                               placeholder="Masukkan Penerima/Tujuan Surat"/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Akses Surat</label>
                                                        <select class="select2 form-control" id="pilihan-akses"
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
                                                        <div class="mt-2" id="akses-surat-display">
                                                            <label class="font-weight-bold">Pilih Civitas
                                                                Akademik</label>
                                                            <select class="form-control placeholder-multiple"
                                                                    id="form-collapse-keluar-penerima_surat"
                                                                    name="akses[]"
                                                                    multiple="multiple">
                                                                @foreach($karyawan AS $item)
                                                                    <option
                                                                        value="{{$item->id_personal}}">{{$item->nama}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <small class="text-danger">* Civitas akademik yang dipilih akan
                                                            bisa
                                                            melihat
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
                                                                   required id="suratKeluarFile" name="path">
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
                                                        id="form-collapse-keluar-btn-cancel"><i
                                                        class="fas fa-backward mr-2"></i>Batal
                                                </button>
                                                <button class="btn btn-primary text-white"
                                                        id="form-collapse-keluar-btn-save"><span
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
                    <div class="col-md-12 collapse" id="form-collapse-masuk">
                        <input type="hidden" id="form-collapse-masuk-id_surat">
                        <div class="card">
                            <div class="card-header bg-success">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fs-17 font-weight-600 mb-0 text-white">Tambah Data Surat Masuk
                                            Baru</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form
                                            action="{{route('sekretaris.surat_menyurat.surat_keluar_masuk.insert_surat_masuk')}}"
                                            enctype="multipart/form-data" method="POST"
                                            id="form-collapse-masuk-form_submit">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Nomor Berkas</label>
                                                        <input type="text" class="form-control"
                                                               placeholder="Masukkan Nomor Berkas" name="nomor_berkas"
                                                               id="form-collapse-masuk-nomor_berkas">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Tanggal Diterima</label>
                                                        <input type="text" readonly class="form-control"
                                                               id="form-collapse-masuk-tgl_diterima"
                                                               style="cursor: pointer" title="Pilih Tanggal Diterima"
                                                               name="tgl_diterima">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Kode</label>
                                                        <input type="text" class="form-control"
                                                               placeholder="Masukkan Kode"
                                                               id="form-collapse-masuk-kode" name="kode">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Nomor Surat</label>
                                                        <input type="text" class="form-control"
                                                               placeholder="Masukkan Nomor Surat"
                                                               id="form-collapse-masuk-nomor_surat" name="nomor">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Tanggal Surat</label>
                                                        <input type="text" readonly class="form-control"
                                                               id="form-collapse-masuk-tgl_surat"
                                                               style="cursor: pointer" title="Pilih Tanggal Surat"
                                                               name="tgl_surat">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Perihal</label>
                                                        <input type="text" class="form-control"
                                                               placeholder="Masukkan Perihal Surat"
                                                               id="form-collapse-masuk-perihal" name="perihal">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Pengirim</label>
                                                        <input class="form-control"
                                                               id="form-collapse-masuk-pengirim"
                                                               placeholder="Masukkan Alamat Pengirim" name="pengirim"/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Akses Surat</label>
                                                        <select class="select2 form-control"
                                                                id="pilihan-akses-surat-masuk"
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
                                                        <div class="mt-2" id="akses-surat-masuk-display">
                                                            <select class="form-control placeholder-multiple"
                                                                    id="form-collapse-masuk-penerima_surat"
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
                                                    <div class="form-group" style="text-align: left">
                                                        <label class="font-weight-bold">Unggah File Arsip Surat
                                                            (PDF)</label>
                                                        <div class="custom-file">
                                                            <input type="file" accept=".pdf"
                                                                   class="custom-file-input"
                                                                   required id="suratMasukFile" name="path">
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
                                                        id="form-collapse-masuk-btn-cancel"><i
                                                        class="fas fa-backward mr-2"></i>Batal
                                                </button>
                                                <button class="btn btn-primary text-white"
                                                        id="form-collapse-masuk-btn-save"><span
                                                        class='spinner-border spinner-border-sm mr-2'
                                                        id='form-collapse-masuk-loading-tambah-data'
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
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link show active" id="pills-surat-keluar-tab" data-toggle="pill"
                                   href="#"
                                   role="tab" aria-controls="pills-surat-keluar" aria-selected="true"><i
                                        class="fas fa-cloud-upload-alt mr-2"></i>Surat Keluar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link show" id="pills-surat-masuk-tab" data-toggle="pill"
                                   href="#"
                                   role="tab" aria-controls="pills-surat-masuk" aria-selected="false"><i
                                        class="fas fa-cloud-download-alt mr-2"></i>Surat Masuk</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade active show" id="pills-surat-keluar" role="tabpanel"
                                 aria-labelledby="pills-surat-keluar-tab">
                                <div class="row">
                                    <div class="col-md-12 collapse show" id="filter-collapse-keluar">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Pencarian</label>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control"
                                                                   placeholder="Cari Perihal Surat Keluar"
                                                                   id="cari-data-keluar">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <button class="btn btn-block btn-primary"
                                                                    id="btn-cari-data-keluar">
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
                                                                    id="filtering-tahun-surat-keluar">
                                                                @if(!empty($tahun_surat_keluar))
                                                                    @foreach($tahun_surat_keluar AS $item)
                                                                        <option
                                                                            value="{{$item->tahun_surat}}">{{$item->tahun_surat}}</option>
                                                                    @endforeach
                                                                @else
                                                                    <option value="">Tidak Ada Data</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <button class="btn btn-block btn-danger"
                                                                    id="btn-tambah-surat-keluar"><i
                                                                    class="fas fa-cloud-upload-alt mr-2"></i>Tambah
                                                                Surat Keluar
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
                                                   id="table-surat-keluar">
                                                <thead>
                                                <tr>
                                                    <th>Nomor</th>
                                                    <th>Nomor dan Perihal</th>
                                                    <th>Keterangan</th>
                                                    <th><i class="fas fa-th-large"></i></th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-surat-masuk" role="tabpanel"
                                 aria-labelledby="pills-surat-masuk-tab">
                                <div class="row">
                                    <div class="col-md-12 collapse show" id="filter-collapse-masuk">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Pencarian</label>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control"
                                                                   placeholder="Cari Perihal Surat Masuk"
                                                                   id="cari-data-masuk">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <button class="btn btn-block btn-primary"
                                                                    id="btn-cari-data-masuk">
                                                                <i
                                                                    class="fas fa-search mr-2"></i>Cari Data
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Filtering Berdasarkan Tahun
                                                        Penerimaan</label>
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <select class="select2 form-control"
                                                                    id="filtering-tahun-surat-masuk">
                                                                @if(!empty($tahun_surat_masuk))
                                                                    @foreach($tahun_surat_masuk AS $item)
                                                                        <option
                                                                            value="{{$item->tahun_surat}}">{{$item->tahun_surat}}</option>
                                                                    @endforeach
                                                                @else
                                                                    <option>Tidak Ada Data</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <button class="btn btn-block btn-success"
                                                                    id="btn-tambah-surat-masuk"><i
                                                                    class="fas fa-cloud-download-alt mr-2"></i>Tambah
                                                                Surat Masuk
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
                                                   id="table-surat-masuk" style="width: 100%">
                                                <thead>
                                                <tr>
                                                    <th>Nomor</th>
                                                    <th>Nomor dan Perihal</th>
                                                    <th>Keterangan</th>
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
    <script src="{{asset('adminpage/own-js/sekretaris_page/surat_menyurat/surat_keluar_masuk.js')}}"></script>
@endpush
