@extends('sidebar')
@section('head-css')
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
                <h1 class="font-weight-bold">Wisuda</h1>
                <small>Halaman ini digunakan untuk mendaftar dan mengunduh kartu wisuda</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-xl-6 text-center">
                        <h3 class="mb-1 font-weight-600">WISUDA</h3>
                        <h5 class="mb-1 font-weight-600">PERIODE {{$jadwal_wisuda->periode}}
                            Tahun {{$jadwal_wisuda->tahun_pelaksanaan}}</h5>
                        <p class="mb-3">Pastikan anda telah menempuh dan mendapatkan nilai yang sesuai pada
                            Skripsi/Tesis</p>
                        <div class="f1-steps">
                            <div class="f1-progress">
                                <div class="f1-progress-line" data-number-of-steps="3"
                                     style="width: @if(sizeof($pengajuan) > 0) @if($pengajuan[0]->id_status_pendaftaran_wisuda == 2) 100%; @else 50%; @endif @else 16.66% @endif"></div>
                            </div>
                            <div class="f1-step @if(sizeof($pengajuan) > 0) activated @else active @endif">
                                <div class="f1-step-icon"><i class="fas fa-clipboard-check"></i></div>
                                @if(sizeof($pengajuan) > 0)
                                    <p>Pendaftaran Wisuda</p>
                                @else
                                    {{--                                    <button id="btn-ajukan" @if(strtolower($nilai[0]->status_aktif) == 'a' || strtolower($nilai[0]->status_aktif) == 'aktif') title="Daftar Wisuda" class="btn btn-sm btn-success-soft mt-2" @else disabled title="Status Siakad Anda Tidak Aktif" class="btn btn-sm btn-danger mt-2" @endif style="cursor: pointer">Daftar Wisuda--}}
                                    {{--                                    </button>--}}
                                    <button id="btn-ajukan" title="Daftar Wisuda"
                                            class="btn btn-sm btn-success-soft mt-2" style="cursor: pointer">Daftar
                                        Wisuda
                                    </button>
                                @endif
                            </div>
                            <div
                                class="f1-step @if(sizeof($pengajuan) > 0) @if($pengajuan[0]->id_status_pendaftaran_wisuda == 2) activated @else active @endif @endif">
                                <div class="f1-step-icon"><i class="fas fa-user-check"></i></div>
                                @if(sizeof($pengajuan) > 0)
                                    @if($pengajuan[0]->id_status_pendaftaran_wisuda == 1)
                                        <span
                                            class="badge badge-success-soft mt-2">{{$pengajuan[0]->status_pendaftaran_wisuda}}</span>
                                    @elseif($pengajuan[0]->id_status_pendaftaran_wisuda == 2)
                                        <p>Validasi Akademik</p>
                                    @elseif($pengajuan[0]->id_status_pendaftaran_wisuda == 3)
                                        <br/>
                                        <span class="badge badge-danger-soft mt-2"><i class="fas fa-clock mr-2"></i>{{$pengajuan[0]->status_pendaftaran_wisuda}}</span>
                                        <button class="btn btn-sm btn-success-soft mt-2" id="btn-detail">Detail
                                            Perbaikan
                                        </button>
                                    @endif
                                @else
                                    <p>Validasi Akademik</p>
                                @endif
                            </div>
                            <div
                                class="f1-step @if(sizeof($pengajuan) > 0) @if($pengajuan[0]->id_status_pendaftaran_wisuda == 2) active @endif @endif">
                                <div class="f1-step-icon">
                                    <i class="fas fa-cloud-download-alt"></i>
                                </div>
                                @if(sizeof($pengajuan) > 0)
                                    @if($pengajuan[0]->id_status_pendaftaran_wisuda == 2)
                                        <a target="_blank"
                                           href="{{route('mahasiswa.akademik.pendaftaran_wisuda.getKartu')}}"
                                           class="btn btn-sm btn-success-soft mt-2">Unduh Kartu Wisuda</a>
                                    @else
                                        <p>Unduh Kartu Wisuda</p>
                                    @endif
                                @else
                                    <p>Unduh Kartu Wisuda</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
    <div class="modal modal-primary fade" id="modal-ajukan-pendaftaran" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <img src="{{asset('image/bg-03.jpg')}}" alt="..." class="card-img-top" style="max-height: 75px">
                        <div class="card-body text-center">
                            <a href="profile-posts.html" class="avatar avatar-xl card-avatar card-avatar-top mb-4">
                                <img
                                    src="http://siakad.stie-mandala.ac.id/_report/photo_m/{{\Illuminate\Support\Facades\Session::get('user')->nim}}.jpg"
                                    class="avatar-img rounded-circle border-card" alt="...">
                            </a>
                            <h5 class="card-title font-weight-600 mb-2">
                                <a href="profile-posts.html">{{\Illuminate\Support\Facades\Session::get('user')->nama_lengkap}}
                                    - {{\Illuminate\Support\Facades\Session::get('user')->nim}}</a>
                            </h5>
                            <p class="card-text text-muted mb-2">{{$nilai[0]->ttl}}</p>
                            <p class="card-text" style="word-break: break-all; width: 100%">
                                <span class="badge badge-pill badge-success-soft" style="font-size: larger">
                                    {{$nilai[0]->nilai_huruf}}
                                </span>
                                <br/><br/>
                                <small style="font-weight: bold">{{$nilai[0]->judul_skripsi}}</small>
                            </p>
                            <hr/>
                            <form action="{{route('mahasiswa.akademik.pendaftaran_wisuda.add_pengajuan')}}"
                                  enctype="multipart/form-data" method="POST" id="form-pendaftaran-wisuda">
                                @csrf
                                @if(sizeof($pengajuan) > 0)
                                    <div class="form-group" style="text-align: left">
                                        <b class="text-danger">Alasan Penolakan
                                            : </b><br/>
                                        <small>{{$pengajuan[0]->alasan_penolakan}}</small>
                                    </div>
                                @endif
                                <hr/>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" style="text-align: left">
                                            <label>Dosen Pembimbing Utama</label>
                                            <select class="form-control select2" required name="dpu">
                                                <option value="">-- Pilih Dosen Pembimbing --</option>
                                                @foreach($dosen AS $item)
                                                    <option
                                                        @if(sizeof($pengajuan) > 0) @if($pengajuan[0]->id_dpu == $item->id_dosen) selected
                                                        @endif @endif value="{{$item->id_dosen}}">{{$item->nama_dosen}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" style="text-align: left">
                                            <label>Dosen Pembimbing Asisten</label>
                                            <select class="form-control select2" required name="dpa">
                                                <option value="">-- Pilih Dosen Pembimbing --</option>
                                                @foreach($dosen AS $item)
                                                    <option
                                                        @if(sizeof($pengajuan) > 0) @if($pengajuan[0]->id_dpa == $item->id_dosen) selected
                                                        @endif @endif value="{{$item->id_dosen}}">{{$item->nama_dosen}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group" style="text-align: left">
                                            <label>Unggah SKB/Ijazah</label>
                                            <div class="custom-file">
                                                <input type="file" accept=".jpg,.jpeg,.png,.pdf"
                                                       class="custom-file-input"
                                                       required id="customFile" name="dok_pendukung">
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                            </div>
                                        </div>
                                        <div class="form-group" style="text-align: left">
                                            <label>Pesan dan Kesan</label>
                                            <textarea required name="kesan_pesan" class="form-control"
                                                      placeholder="Masukkan Kesan dan Pesan anda selama di Mandala"
                                                      rows="4" maxlength="200">@if(sizeof($pengajuan) > 0){{$pengajuan[0]->kesan_pesan}}@endif</textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    @if(sizeof($pengajuan) > 0)
                        @if($pengajuan[0]->id_status_pendaftaran_wisuda == 3)
                            <button
                                class="btn btn-success"
                                id="modal-btn-ajukan"><i
                                    class="fas fa-clipboard-check mr-2"></i>Perbaiki Data
                            </button>
                        @endif
                    @else
                        <button
                            class="btn btn-success"
                            id="modal-btn-ajukan"><i
                                class="fas fa-clipboard-check mr-2"></i>Daftar Wisuda
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/mahasiswa_page/akademik/wisuda.js')}}"></script>
    <script>
        $(document).ready(function () {
            $(".select2").select2()
            @if ($errors->any())
            var html = "<ul>";
            @foreach($errors->all() as $error)
                html = html + "<li>{{$error}}</li>";
            @endforeach
            $.alert({
                title: 'Informasi',
                type: 'red',
                columnClass: 'medium',
                content: html,
            });
            @endif
        })
    </script>
@endpush
