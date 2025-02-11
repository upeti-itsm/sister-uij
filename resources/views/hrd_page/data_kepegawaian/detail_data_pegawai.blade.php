@extends('sidebar')
@section('head-css')
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Data Kepegawaian</li>
            <li class="breadcrumb-item active">Detail Pegawai</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Data Pegawai</h1>
                <small>Halaman ini digunakan untuk melihat detail data masing-masing pegawai</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-4">
        <div class="card mb-4">
            <img src="{{asset('image/bg-01.jpg')}}" alt="..." class="card-img-top" style="max-height: 90px">
            <div class="card-body text-center">
                <a class="avatar avatar-xl card-avatar card-avatar-top mb-5">
                    <img style="width: 150%!important; height: 150%!important;"
                         src="{{asset('/files/profil_karyawan/'.$karyawan->id_personal.'/'.$karyawan->path_photo)}}"
                         class="avatar-img rounded-circle border-card"
                         onerror="this.src='{{asset('adminpage/assets/dist/img/avatar-1.jpg')}}'" alt="...">
                </a>
                <h6 class="card-title font-weight-600 mb-2">
                    <a href="#">{{$karyawan->nama_lengkap}}</a>
                </h6>
                <p class="card-text text-muted mb-2">@if($karyawan->status_karyawan == 1)
                        NIK. {{is_null($karyawan->nik) ? '-' : $karyawan->nik}}
                        / NIDN. {{is_null($karyawan->nidn) ? '-' : $karyawan->nidn}} @else
                        NIK. {{is_null($karyawan->nik) ? '-' : $karyawan->nik}} @endif</p>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Dokumen Pendukung</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(is_null($karyawan->path_kartu_keluarga) && is_null($karyawan->path_dokumen_pendukung_pendidikan))
                    <p>Tidak Tersedia Dokumen</p>
                @endif
                @if(!is_null($karyawan->path_kartu_keluarga))
                    <div class="card mb-3 border shadow-none">
                        <div class="px-3 py-3">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <img alt="Image placeholder" src="{{asset('adminpage/assets/dist/img/pdf.png')}}"
                                         class="img-fluid"
                                         style="width: 40px;">
                                </div>
                                <div class="col ml-n2">
                                    <h6 class="mb-0">
                                        <a href="{{asset('files/berkas_kepegawaian/'.$karyawan->id_personal.'/'.$karyawan->path_kartu_keluarga)}}"
                                           target="_blank">Kartu Keluarga</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(!is_null($karyawan->path_dokumen_pendukung_pendidikan))
                    <div class="card mb-3 border shadow-none">
                        <div class="px-3 py-3">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <img alt="Image placeholder" src="{{asset('adminpage/assets/dist/img/pdf.png')}}"
                                         class="img-fluid"
                                         style="width: 40px;">
                                </div>
                                <div class="col ml-n2">
                                    <h6 class="mb-0">
                                        <a href="{{asset('files/berkas_kepegawaian/'.$karyawan->id_personal.'/'.$karyawan->path_dokumen_pendukung_pendidikan)}}"
                                           target="_blank">Ijazah Terakhir</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(!is_null($karyawan->path_dokumen_pendukung_golongan))
                    <div class="card mb-3 border shadow-none">
                        <div class="px-3 py-3">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <img alt="Image placeholder" src="{{asset('adminpage/assets/dist/img/pdf.png')}}"
                                         class="img-fluid"
                                         style="width: 40px;">
                                </div>
                                <div class="col ml-n2">
                                    <h6 class="mb-0">
                                        <a href="{{asset('files/berkas_kepegawaian/'.$karyawan->id_personal.'/'.$karyawan->path_dokumen_pendukung_golongan)}}"
                                           target="_blank">SK Golongan Terakhir</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(!is_null($karyawan->path_dokumen_pendukung_riwayat_jabatan_fungsional))
                    <div class="card mb-3 border shadow-none">
                        <div class="px-3 py-3">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <img alt="Image placeholder" src="{{asset('adminpage/assets/dist/img/pdf.png')}}"
                                         class="img-fluid"
                                         style="width: 40px;">
                                </div>
                                <div class="col ml-n2">
                                    <h6 class="mb-0">
                                        <a href="{{asset('files/berkas_kepegawaian/'.$karyawan->id_personal.'/'.$karyawan->path_dokumen_pendukung_riwayat_jabatan_fungsional)}}"
                                           target="_blank">SK Jabatan Fungsional Terakhir</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(!is_null($karyawan->path_dokumen_pendukung_riwayat_jabatan_struktural))
                    <div class="card mb-3 border shadow-none">
                        <div class="px-3 py-3">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <img alt="Image placeholder" src="{{asset('adminpage/assets/dist/img/pdf.png')}}"
                                         class="img-fluid"
                                         style="width: 40px;">
                                </div>
                                <div class="col ml-n2">
                                    <h6 class="mb-0">
                                        <a href="{{asset('files/berkas_kepegawaian/'.$karyawan->id_personal.'/'.$karyawan->path_dokumen_pendukung_riwayat_jabatan_struktural)}}"
                                           target="_blank">SK Jabatan Struktural Terakhir</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-muted">Tempat, Tanggal Lahir</label><br/>
                            <label><b>{{is_null($karyawan->tempat_lahir) ? '-' : $karyawan->tempat_lahir}}
                                    , {{is_null($karyawan->tanggal_lahir_) ? '-' : $karyawan->tanggal_lahir_}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-muted">Pendidikan Terakhir</label><br/>
                            <label><b>{{is_null($karyawan->pendidikan_terakhir) ? '-' : $karyawan->pendidikan_terakhir}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-muted">Golongan</label><br/>
                            <label><b>{{is_null($karyawan->golongan) ? '-' : $karyawan->golongan}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-muted">Alamat</label><br/>
                            <label><b>{{is_null($karyawan->alamat) ? '-' : $karyawan->alamat}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-muted">Jabatan Struktural</label><br/>
                            <label><b>@if(empty($karyawan->jabatan_struktural))
                                        - @else {{$karyawan->jabatan_struktural}} @endif</b></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-muted">Jabatan Fungsional</label><br/>
                            <label><b>{{is_null($karyawan->jabatan_fungsional) ? '-' : $karyawan->jabatan_fungsional}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-muted">Email</label><br/>
                            <label><b>{{is_null($karyawan->email) ? '-' : $karyawan->email}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-muted">No. HP</label><br/>
                            <label><b>{{is_null($karyawan->no_hp) ? '-' : $karyawan->no_hp}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-muted">No. KTP</label><br/>
                            <label><b>{{is_null($karyawan->no_ktp) ? '-' : $karyawan->no_ktp}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-muted">Unit Kerja</label><br/>
                            <label><b>{{is_null($karyawan->unit_kerja) ? '-' : $karyawan->unit_kerja}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-muted">Nomor Rekening</label><br/>
                            <label><b>{{is_null($karyawan->nomor_rekening) ? '-' : $karyawan->nomor_rekening}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-muted">Status Menikah</label><br/>
                            <label><b>{{is_null($karyawan->status_pernikahan) ? '-' : $karyawan->status_pernikahan}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-muted">Jumlah Anak</label><br/>
                            <label><b>{{is_null($karyawan->jumlah_anak) ? '-' : $karyawan->jumlah_anak}}</b></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-muted">Tanggal Aktif</label><br/>
                            <label><b>{{is_null($karyawan->tanggal_masuk) ? '-' : $karyawan->tanggal_masuk}}</b></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="float-right">
                    <a href="{{route('hrd.data_kepegawaian.list_data_pegawai.index')}}" class="btn btn-outline-danger">Kembali</a>
                    <a href="{{route('hrd.data_kepegawaian.list_data_pegawai.edit', ['id' => $karyawan->id_personal])}}"
                       class="btn btn-success">Ubah Data Pegawai</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
@endsection
@push('scripts')
    <script src="{{asset('adminpage/own-js/hrd_page/data_kepegawaian/detail_data_pegawai.js')}}"></script>
@endpush
