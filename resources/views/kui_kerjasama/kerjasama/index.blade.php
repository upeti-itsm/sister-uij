@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">KUI & Kerjasama</li>
            <li class="breadcrumb-item active">Kerjasama</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Daftar Kerjasama</h1>
                <small>Halaman ini digunakan untuk mengelola Kerjasama</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">List Kerjasama</h6>
                    </div>
                    <div class="text-right">
                        <button type="button" id="btn-add-kerjasama" class="btn btn-info rounded-pill"
                                title="Tambah Kerjasama Baru"><i class="fas fa-plus mr-2"></i>Tambah Data
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="collapse" id="form_collapse">
                        <form action="{{route('kui_kerjasama.kerjasama.insup')}}" method="post" id="form-data">
                            @csrf
                            <input type="hidden" id="id_dokumen" name="id" value="0">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Lembaga Mitra</label>
                                        <input type="text" class="form-control" id="lembaga_mitra" name="lembaga_mitra"
                                               placeholder="Masukkan Nama Mitra Kerjasama">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Tingkat Kerjasama</label>
                                        <select class="form-select select2" id="tingkat_kerjasama"
                                                name="tingkat_kerjasama">
                                            <option value="1">Internasional</option>
                                            <option value="2">Nasioanl</option>
                                            <option value="3">Wilayah/Lokal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label class="form-label">Bentuk Kegiatan</label>
                                            <input type="text" class="form-control" id="bentuk_kegiatan" name="bentuk_kegiatan"
                                                   placeholder="Masukkan Bentuk Kegiatan">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Tanggal Kegiatan</label>
                                        <input type="text" readonly class="form-control"
                                               placeholder="Masukkan Tanggal Kegiatan" id="tgl_kegiatan_">
                                        <input type="hidden" class="form-control" name="tgl_kegiatan" id="tgl_kegiatan"
                                               value="{{old('tgl_kegiatan', now('Asia/Jakarta')->format('Y-m-d'))}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Berlaku Sampai Dengan</label>
                                        <input type="text" readonly class="form-control"
                                               placeholder="Masukkan Masa Berlaku" id="tgl_berlaku_">
                                        <input type="hidden" class="form-control" name="masa_berlaku"
                                               id="masa_berlaku"
                                               value="{{old('masa_berlaku', now('Asia/Jakarta')->format('Y'))}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Cakupan Kerjasama</label>
                                        <select class="form-select select2" id="tingkatan_level"
                                                name="tingkatan_level">
                                            <option value="1">Institusi</option>
                                            <option value="2">Program Studi</option>
                                            <option value="3">Unit Bagian</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label class="form-label">Bukti Kerjasama</label>
                                            <input type="text" class="form-control" id="bukti_kerjasama" name="bukti_kerjasama"
                                                   placeholder="Masukkan Bukti Kerjasama">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label class="form-label">Link Bukti Kerjasama</label>
                                            <input type="text" class="form-control" name="link_dokumen" id="link_dokumen"
                                                   placeholder="Masukkan Link Bukti Kerjasama">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="button" id="btn-cancel"
                                            class="btn btn-labeled btn-danger right ml-2">
                                        <span class="btn-label"><i class="fas fa-times"></i></span>Batal
                                    </button>
                                    <button type="button" id="btn-save"
                                            class="btn btn-labeled btn-primary right">
                                        <span class="btn-label"><i class="fas fa-save"></i></span>Simpan
                                    </button>
                                </div>
                            </div>
                        </form>
                        <hr/>
                    </div>
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" placeholder="Cari Nama Kerjasama"
                                                   id="cari-data">
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-block btn-primary" id="btn-cari-data"><i
                                                    class="fas fa-search mr-2"></i>Cari Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="font-weight-bold">Filtering</label>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <select class="select2 form-control" id="level">
                                                @foreach($institusi AS $item)
                                                    <option
                                                        value="{{$item->id_level_institusi}}">{{$item->level_institusi}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-block btn-primary" id="btn-filter"><i
                                                    class="fas fa-filter mr-2"></i>Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">No.</th>
                                <th class="text-center" style="width: 25%">Lembaga Mitra</th>
                                <th class="text-center" style="width: 20%">Bentuk Kegiatan</th>
                                <th class="text-center" style="width: 25%">Cakupan Kerjasama</th>
                                <th class="text-center" style="width: 25%"><i class="fas fa-th"></i></th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form style="display: none" action="{{route('kui_kerjasama.kerjasama.delete')}}" id="form-delete" method="POST">
        @csrf
        <input type="hidden" name="id" id="id_kerjasama_del">
    </form>
@endsection
@section('modal')
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.id.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/kui_kerjasama/kerjasama/index.js')}}"></script>
@endpush
