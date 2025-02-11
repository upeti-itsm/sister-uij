@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item active">SKPI</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">SKPI</h1>
                <small>Halaman ini digunakan untuk Pengelolaan SKPI</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar SKPI</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="table">
                        <thead>
                        <tr>
                            <th class="text-center">Tgl Upload</th>
                            <th class="text-center">Kartu Prestasi</th>
                            <th class="text-center">Dokumen Pendukung</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Surat Keterangan Pendamping Ijazah (SKPI)</h6>
                        <small><a href="{{asset('files/panduan/pedoman_skpi.pdf')}}">Unduh Pedoman Pengisian SKPI</a></small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{route('mahasiswa.akademik.skpi.insup')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group" style="text-align: left">
                        <label>Unggah Scan Kartu Hasil Prestasi Mahasiswa</label>
                        <div class="custom-file">
                            <input type="file" accept=".pdf"
                                   class="custom-file-input"
                                   required id="kartu_prestasi" name="kartu_prestasi">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                        <small><a href="{{asset('files/panduan/kartu_prestasi_baru.docx')}}">Unduh Kartu Hasil Prestasi
                                Mahasiswa</a></small>
                    </div>
                    <div class="form-group" style="text-align: left">
                        <label>Unggah File Penunjang Kartu Hasil Prestasi</label>
                        <div class="custom-file">
                            <input type="file" accept=".rar,.zip"
                                   class="custom-file-input"
                                   id="dok_pendukung" name="dok_pendukung">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                        <small class="text-danger">File dalam bentuk zip/rar, maksimal 10Mb</small>
                    </div>
                    <div class="form-group">
                        <div class="float-right">
                            <button class="btn btn-success" type="submit"><i class="fas fa-save mr-2"></i>Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('modal')

@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/mahasiswa_page/akademik/pengisian_skpi.js')}}"></script>
@endpush
