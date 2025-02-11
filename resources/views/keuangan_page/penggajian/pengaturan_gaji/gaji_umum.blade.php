@extends('sidebar')
@section('head-css')
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
            <div class="header-icon text-success mr-3"><i class="fas fa-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Pengaturan Gaji Umum</h1>
                <small>Halaman ini digunakan untuk mengatur konfigurasi gaji secara umum/keseluruhan</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Pengaturan Gaji Umum</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions show">
                            <div class="dropdown action-item" data-toggle="dropdown" aria-expanded="true">
                                <a href="#" class="action-item">Pengaturan Gaji Umum <i class="fas fa-bars fa-fw"></i></a>
                                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                                     style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-162px, 21px, 0px);" id="sub-menu">
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.daftar_pegawai.index')}}" class="dropdown-item">Pengaturan Gaji Individu</a>
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.potongan_koperasi.index')}}" class="dropdown-item">Potongan Pinjaman</a>
                                    <a style="display: none" href="{{route('keuangan.penggajian.pengaturan_gaji.potongan_qurban.index')}}" class="dropdown-item">Potongan Qurban</a>
                                    <a style="display: none" href="{{route('keuangan.penggajian.pengaturan_gaji.potongan_arisan.index')}}" class="dropdown-item">Potongan Arisan</a>
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.potongan_lainnya.index')}}" class="dropdown-item">Potongan Lainnya</a>
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.potongan_bpjs.index')}}" class="dropdown-item">Pengaturan BPJS</a>
                                    <a style="display: none" href="{{route('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_fungsional.index')}}" class="dropdown-item">Tunjangan Fungsional</a>
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_struktural.index')}}" class="dropdown-item">Tunjangan Jabatan</a>
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_kinerja.index')}}" class="dropdown-item">Tunjangan Kinerja</a>
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.pengaturan_umr.index')}}" class="dropdown-item">Pengaturan UMR</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-white">
                                        <h6 class="fs-17 font-weight-600 mb-0">Tunjangan</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tunjangan Transport (Rp)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tunjangan Pendidikan S3 (Rp)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <hr/>
                                        <label class="font-weight-bold">Tunjangan Beras</label>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Karyawan (Kg)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Pasangan (Kg)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Anak (Kg)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <hr/>
                                        <label class="font-weight-bold">Tunjangan Keluarga</label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pasangan (% dari Gaji Pokok)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Anak (% dari Gaji Pokok)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3">
                            <div class="card-header bg-danger">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-white">
                                        <h6 class="fs-17 font-weight-600 mb-0">Potongan</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Potongan Infaq (% dari Gaji pokok)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Potongan DPLK (Rp)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Harga Beras per Kilogram (Rp)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-white">
                                        <h6 class="fs-17 font-weight-600 mb-0">Asuransi</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>UMR (Rp)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <hr/>
                                        <label class="font-weight-bold">BPJS Kesehatan</label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Karyawan (%)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Perusahaan (%)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <hr/>
                                        <label class="font-weight-bold">Jaminan Hari Tua (JHT)</label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Karyawan (%)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Perusahaan (%)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <hr/>
                                        <label class="font-weight-bold">Jaminan Kematian (JK)</label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Karyawan (%)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Perusahaan (%)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <hr/>
                                        <label class="font-weight-bold">Jaminan Kecelakaan Kerja (JKK)</label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Karyawan (%)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Perusahaan (%)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <hr/>
                                        <label class="font-weight-bold">Jaminan Pensiun (JP)</label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Karyawan (%)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Perusahaan (%)</label>
                                            <input type="text" class="form-control"
                                                   value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="float-right">
                    <a href="{{route('keuangan.penggajian.gaji_bulanan.index')}}"
                       class="btn btn-outline-danger">Kembali</a>
                    <a href="#" class="btn btn-success">Simpan Gaji</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/keuangan_page/penggajian/pengaturan_gaji/pengaturan_gaji_umum.js')}}"></script>
@endpush
