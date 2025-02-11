@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Penggajian</li>
            <li class="breadcrumb-item active">Pengaturan Gaji</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Pengaturan UMR</h1>
                <small>Halama ini digunakan untuk melakukan konfigurasi UMR yang berlaku pada saat karyawan baru
                    didaftarkan sebagai peserta BPJS</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Pengaturan UMR</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <div class="dropdown action-item" data-toggle="dropdown" aria-expanded="true">
                                <a href="#" class="action-item">Pengaturan UMR <i class="fas fa-bars fa-fw"></i></a>
                                <div class="dropdown-menu dropdown-menu-right" id="sub-menu">
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.gaji_umum.index')}}"
                                       class="dropdown-item">Pengaturan Gaji Umum</a>
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.daftar_pegawai.index')}}"
                                       class="dropdown-item">Pengaturan Gaji Individu</a>
                                    <a style="display: none" href="{{route('keuangan.penggajian.pengaturan_gaji.potongan_qurban.index')}}"
                                       class="dropdown-item">Potongan Qurban</a>
                                    <a style="display: none" href="{{route('keuangan.penggajian.pengaturan_gaji.potongan_koperasi.index')}}"
                                       class="dropdown-item">Potongan Pinjaman</a>
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.potongan_lainnya.index')}}" class="dropdown-item">Potongan Lainnya</a>
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.potongan_bpjs.index')}}" class="dropdown-item">Pengaturan BPJS</a>
                                    <a style="display: none" href="{{route('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_fungsional.index')}}" class="dropdown-item">Tunjangan Fungsional</a>
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_struktural.index')}}" class="dropdown-item">Tunjangan Jabatan</a>
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_kinerja.index')}}" class="dropdown-item">Tunjangan Kinerja</a>
                                    <a style="display: none" href="{{route('keuangan.penggajian.pengaturan_gaji.potongan_arisan.index')}}"
                                       class="dropdown-item">Pengaturan Arisan</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row" id="main-display">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select class="form-control select2" id="bulan">
                                                <option value="0">-- Semua Bulan --</option>
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control select2" id="tahun">
                                                <option value="0">-- Semua Tahun --</option>
                                                <option value="2024">2024</option>
                                                <option value="2023">2023</option>
                                                <option value="2022">2022</option>
                                                <option value="2021">2021</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="font-weight-bold">&nbsp;</label><br/>
                                    <div class="float-right">
                                        <button class="btn btn-primary" title="Tambah Data UMR"
                                                id="btn_add_data_umr"><i class="fas fa-plus-square"></i> Tambah Data UMR
                                        </button>
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
                                <th class="text-center align-middle">Nomor</th>
                                <th class="text-left align-middle">Data UMR</th>
                                <th class="text-left align-middle">Tanggal Dibuat</th>
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
    <div class="modal modal-primary fade" id="modal-tambah-data-umr" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600" id="insupLabel"></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group" style="width: 100%;">
                        <label class="font-weight-bold">Nilai UMR</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Rp.</div>
                            </div>
                            <input type="text" class="form-control number" id="add_nilai_umr"
                                   placeholder="Masukkan Nilai UMR">
                        </div>
                    </div>
                    <form id="add_form" style="display: none" action="{{route('keuangan.penggajian.pengaturan_gaji.pengaturan_umr.insert_umr')}}" method="POST">
                        @csrf
                        <input type="hidden" name="nilai_umr" id="nilai_umr">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-secondary disabled" id="btn-simpan-data">Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/numeral/numeral.min.js')}}"></script>
    <script
        src="{{asset('adminpage/own-js/keuangan_page/penggajian/pengaturan_gaji/pengaturan_umr/daftar_umr.js')}}"></script>
@endpush
