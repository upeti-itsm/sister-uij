@extends('sidebar')
@section('head-css')
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Penggajian</li>
            <li class="breadcrumb-item active">Detail Honorarium</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Detail Honorarium</h1>
                <small>Halaman ini digunakan untuk melihat detail honorarium masing-masing dosen</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        @if($rekap->is_repair)
            <div class="alert alert-danger" role="alert">
                Alasan Perbaikan :
                <hr/>
                {{$rekap->keterangan_repair}}
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Identitas Karyawan</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <input type="hidden" id="id_personal" name="id" value="{{$karyawan->id_personal}}">
                        <img src="{{asset('image/bg-01.jpg')}}" alt="..." class="card-img-top"
                             style="max-height: 90px">
                        <div class="card-body text-center">
                            <a class="avatar avatar-xl card-avatar card-avatar-top mb-5">
                                <img style="width: 150%!important; height: 150%!important;"
                                     src="{{asset('/files/profil_karyawan/'.$karyawan->id_personal.'/'.$karyawan->path_photo)}}"
                                     class="avatar-img rounded-circle border-card"
                                     onerror="this.src='{{asset('adminpage/assets/dist/img/avatar-1.jpg')}}'"
                                     alt="...">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted">Nama Lengkap</label>
                                    <input type="text" required name="nama_lengkap"
                                           value="{{$karyawan->nama_lengkap}}" readonly
                                           placeholder="Nama Lengkap" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted">Nomor Rekening</label>
                                    <input type="text" required name="nomor_rekening"
                                           value="{{$karyawan->nomor_rekening}}" readonly
                                           placeholder="Nomor Rekening" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Periode Gaji</label>
                                    <input type="text" required name="nama_lengkap"
                                           value="{{$rekap->periode_pembayaran}}" readonly
                                           placeholder="Nama Lengkap" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Status Karyawan</label>
                                    <input type="text" required name="nomor_rekening"
                                           value="{{$karyawan->jenis_karyawan}}" readonly
                                           placeholder="Nomor Rekening" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Nomor HP</label>
                                    <input type="text" required name="nomor_hp"
                                           value="{{$karyawan->no_hp}}" readonly
                                           placeholder="Nomor Rekening" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-header bg-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-white">
                        <h6 class="fs-17 font-weight-600 mb-0">Honorarium Mengajar Dosen</h6>
                        <small id="total_nominal_gaji">{{$rekap->total_honorarium_dosen_mengajar}}</small>
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
                                        <h6 class="fs-17 font-weight-600 mb-0">Detail Honor</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Honorarium Mengajar Kelas Malam</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number" id="honor_kelas_malam"
                                                       value="{{str_replace("Rp", "", $rekap->total_honor_kotor_mengajar_malam)}}">
                                            </div>
                                            <small class="text-danger">{{$rekap->detail_honor_mengajar_dosen_malam}}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Honorarium Mengajar Kelas Pagi</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number" id="honor_kelas_pagi"
                                                       value="{{str_replace("Rp", "", $rekap->total_honor_kotor_mengajar_pagi)}}">
                                            </div>
                                            <small class="text-danger">{{$rekap->detail_honor_mengajar_dosen_pagi}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
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
                                            <label>Potongan Infaq</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number" id="potongan_infaq"
                                                       value="{{$rekap->total_nominal_infaq}}">
                                            </div>
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
                    <a href="{{route('keuangan.penggajian.honorarium.honorarium_mengajar.index')}}"
                       class="btn btn-outline-danger">Kembali</a>
                    @if($rekap->is_repair)
                        <a href="{{route('keuangan.penggajian.honorarium.honorarium_mengajar.generate_ulang_gaji', ['id_karyawan' => $karyawan->id_karyawan])}}" class="btn btn-success">Generate Ulang Honor</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/numeral/numeral.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/keuangan_page/penggajian/honorarium/honorarium_mengajar/detail.js')}}"></script>
@endpush
