@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Dokumen SPMI</li>
            <li class="breadcrumb-item active">Tata Pamong</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Daftar Dokumen Tata Pamong</h1>
                <small>Halaman ini digunakan untuk mengelola Tata Pamong</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">List Dokumen</h6>
                    </div>
                    <div class="text-right">
                        <button type="button" id="btn-add-document" class="btn btn-info rounded-pill"
                                title="Tambah Dokumen Baru"><i class="fas fa-plus mr-2"></i>Tambah Data
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="collapse" id="form_collapse">
                        <form action="{{route('bpm_page.tata_pamong.insup')}}" method="post" id="form-tata-pamong">
                            @csrf
                            <input type="hidden" id="id_dokumen" name="id" value="0">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Nomor Dokumen</label>
                                        <input type="text" class="form-control" id="nomor_dokumen" name="nomor_dokumen"
                                               placeholder="Masukkan Nomor Dokumen">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Nama Dokumen</label>
                                        <input type="text" class="form-control" name="nama_dokumen" id="nama_dokumen"
                                               placeholder="Masukkan Nama Dokumen">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label class="form-label">Tahun Terbit</label>
                                            <input type="text" class="form-control" name="tahun_terbit"
                                                   placeholder="Masukkan Tahun Terbit">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea class="form-control" id="deskripsi" name="deskripsi"
                                                  placeholder="Masukkan Deskripsi Dokumen"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Link Dokumen</label>
                                        <input type="text" placeholder="Masukkan Link Dokumen" name="link_dokumen" id="link_dokumen"
                                               class="form-control">
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
                                            <input type="text" class="form-control" placeholder="Cari Nama Matakuliah"
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
                                            <select class="select2 form-control" id="tahun_terbit">
                                                @foreach($tahun AS $item)
                                                    <option
                                                        value="{{$item->tahun_terbit}}">{{$item->tahun_terbit}}</option>
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
                                <th class="text-center">No.</th>
                                <th class="text-center">Dokumen</th>
                                <th class="text-center">Tahun</th>
                                <th class="text-center">Deskripsi</th>
                                <th class="text-center">Link Dokumen</th>
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
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/bpm_page/tata_pamong/index.js')}}"></script>
@endpush
