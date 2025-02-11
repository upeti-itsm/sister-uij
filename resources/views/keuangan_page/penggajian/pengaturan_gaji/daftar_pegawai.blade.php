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
                <h1 class="font-weight-bold">Pengaturan Gaji Individu</h1>
                <small>Halaman ini digunakan untuk melakukan pengaturan konfigurasi gaji masing-masing karyawan</small>
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
                        <h6 class="fs-17 font-weight-600 mb-0">Pengaturan Gaji Individu</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <div class="dropdown action-item" data-toggle="dropdown" aria-expanded="true">
                                <a href="#" class="action-item">Pengaturan Gaji Individu<i class="fas fa-bars fa-fw"></i></a>
                                <div class="dropdown-menu dropdown-menu-right" id="sub-menu">
                                    <a href="{{route('keuangan.penggajian.pengaturan_gaji.gaji_umum.index')}}" class="dropdown-item">Pengaturan Gaji Umum</a>
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
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row" id="main-display">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" placeholder="Cari Nama Karyawan"
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Filtering</label>
                                    <select class="form-control select2 placeholder-multiple"
                                            id="jenis_karyawan"
                                            multiple="multiple">
                                        <option value="1">Dosen Tetap</option>
                                        <option value="6">Dosen Dpk</option>
                                        <option value="2">Dosen Luar Biasa (LB)</option>
                                        <option value="3">Dosen Praktisi</option>
                                        <option value="4">Karyawan Tetap</option>
                                        <option value="5">Karyawan Kontrak</option>
                                        <option value="7">Tetap Kontrak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">&nbsp;</label><br/>
                                    <div class="float-right">
                                        <button class="btn btn-success mr-2" title="Export Data Asuransi"
                                                id="btn_export"><i class="fas fa-file-excel"></i> Export Data Asuransi
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
                                <th class="text-left align-middle">Identitas Pegawai</th>
                                <th class="text-left align-middle">Keterangan</th>
                                <th class="text-center">Asuransi BPJS</th>
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
    <div class="modal modal-primary fade" id="modal-update-data-kinerja" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600" id="insupLabel"></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Silahkan Masukkan Nilai Kinerja Untuk <span id="nama_karyawan" class="font-weight-bold"></span></p>
                    <div class="form-group" style="width: 100%;">
                        <label class="font-weight-bold">Nilai Kinerja</label>
                        <select class="form-control select2" id="modal-nilai-kinerja">
                            <option value="-">-- Pilih Kinerja --</option>
                            <option value="x">-- Tidak Mendapat Tunjangan Kinerja --</option>
                            @foreach($tunjangan_kinerja as $item)
                                <option value="{{$item->kd_kinerja}}">{{$item->kd_kinerja.' ('.$item->nominal_kinerja.')'}}</option>
                            @endforeach
                        </select>
                    </div>
                    <form id="update_kinerja_form" style="display: none" action="{{route('keuangan.penggajian.pengaturan_gaji.daftar_pegawai.update_kinerja')}}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="id_karyawan">
                        <input type="hidden" name="kinerja" id="nilai_kinerja">
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
    <script src="{{asset('adminpage/own-js/keuangan_page/penggajian/pengaturan_gaji/daftar_pegawai.js')}}"></script>
    <script>
        function keyUpNumber (id) {
            var $this = document.getElementById(id);
            var input = $this.value;
            input = input.replace(/[\D\s\\._\-]+/g, "");
            input = input ? parseInt(input, 10) : 0;
            $this.value = input.toLocaleString("id-ID");
        }
    </script>
@endpush
