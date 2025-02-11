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
            <li class="breadcrumb-item active">Wisuda</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Pendaftaran Wisuda</h1>
                <small>Halaman ini digunakan untuk validasi pendaftaran wisuda</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Pendaftaran Wisuda</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control"
                                                   placeholder="Cari Nama/NIM Mahasiswa"
                                                   id="cari-data">
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-block btn-primary" id="btn-cari-data"><i
                                                    class="fas fa-search mr-2"></i>Cari
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
                                            <select class="select2 form-control" id="status_pengajuan">
                                                <option value="1">-- Menunggu Persetujuan --</option>
                                                <option value="3">-- Ditolak --</option>
                                                <option value="2">-- Diterima --</option>
                                                <option value="0">-- Semua --</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <select class="select2 form-control" id="kd_prodi">
                                                <option value="all">-- Semua Prodi--</option>
                                                @foreach($prodi AS $item)
                                                    <option
                                                        value="{{$item->kd_program_studi}}">{{$item->nama_program_studi}}
                                                        ({{$item->jenjang_didik}})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="select2 form-control" id="kd_konsen">
                                                <option value="all">-- Semua --</option>
                                                <option value="-">-</option>
                                                <option value="MI">MI</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="font-weight-bold">Exporting</label><br/>
                                    <button class="btn btn-danger btn-sm" id="btn-export-pdf"><i
                                            class="fas fa-file-pdf mr-2"></i>Pdf
                                    </button>
                                    <button class="btn btn-success btn-sm" id="btn-export-excel"><i
                                            class="fas fa-file-excel mr-2"></i>Excel
                                    </button>
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
                                <th class="text-center">Nomor</th>
                                <th>Mahasiswa</th>
                                <th>Foto</th>
                                <th>SKB/Ijazah</th>
                                <th class="text-center"><i class="fas fa-th"></i></th>
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
    <div class="modal modal-primary fade" id="modal-persetujuan-pendaftaran" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-header">
                <h4 class="text-white">Konfirmasi</h4>
            </div>
            <div class="modal-content">
                <div class="modal-body">
                    <p>Apakah anda yakin menyetujui pendaftaran ini, Nomor Pendaftaran akan otomatis di generate
                        berdasarkan urutan mendaftar, kode prodi dan tahun akademik
                    </p>
                    <form action="{{route('admin_akademik.akademik.wisuda.pendaftaran_wisuda.accept_pendaftaran')}}"
                          enctype="multipart/form-data" method="POST" id="form-persetujuan-wisuda">
                        @csrf
                        <hr/>
                        <input type="hidden" id="id_pendaftaran" name="id_pendaftaran">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" style="text-align: left">
                                    <label>Dosen Pembimbing Utama</label>
                                    <select class="form-control select2" id="dpu" required name="dpu">
                                        <option value="">-- Pilih Dosen Pembimbing --</option>
                                        @foreach($dosen AS $item)
                                            <option value="{{$item->id_dosen}}">{{$item->nama_dosen}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="text-align: left">
                                    <label>Dosen Pembimbing Asisten</label>
                                    <select class="form-control select2" id="dpa" required name="dpa">
                                        <option value="">-- Pilih Dosen Pembimbing --</option>
                                        @foreach($dosen AS $item)
                                            <option value="{{$item->id_dosen}}">{{$item->nama_dosen}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group" style="text-align: left">
                                    <label>IPK</label>
                                    <input type="text" class="form-control" required id="ipk" name="ipk"
                                           placeholder="Masukkan Nilai IPK">
                                    <small class="text-danger">* Wajib diisi, gunakan tanda titik (.) sebagai pemisah desimal</small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    <button
                        class="btn btn-success"
                        id="modal-btn-setujui"><i
                            class="fas fa-clipboard-check mr-2"></i>Setujui
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/admin_akademik/akademik/wisuda/pendaftaran_wisuda.js')}}"></script>
@endpush
