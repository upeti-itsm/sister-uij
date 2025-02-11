@extends('sidebar')
@section('head-css')
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item active">Sertifikat Labkom</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Sertifikat Labkom</h1>
                <small>Halaman ini digunakan untuk mengajukan/mengunduh Sertifikat untuk matakuliah Laboratorium
                    Komputer</small>
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
                        <h3 class="mb-1 font-weight-600">Laboratorium Komputer</h3>
                        <p class="mb-5">Pastikan anda telah menempuh dan mendapatkan nilai yang sesuai</p>
                        <div class="f1-steps">
                            <div class="f1-progress">
                                <div class="f1-progress-line" data-number-of-steps="3"
                                     style="width: @if(sizeof($pengajuan) > 0) @if($pengajuan[0]->id_status_pengajuan == 2) 100%; @else 50%; @endif @else 16.66% @endif"></div>
                            </div>
                            <div class="f1-step @if(sizeof($pengajuan) > 0) activated @else active @endif">
                                <div class="f1-step-icon"><i class="fas fa-clipboard-check"></i></div>
                                @if(sizeof($pengajuan) > 0)
                                    <p>Pengajuan Sertifikat</p>
                                @else
                                    <button class="btn btn-sm btn-success-soft mt-2" id="btn-ajukan">Ajukan Sertifikat
                                    </button>
                                @endif
                            </div>
                            <div
                                class="f1-step @if(sizeof($pengajuan) > 0) @if($pengajuan[0]->id_status_pengajuan == 2) activated @else active @endif @endif">
                                <div class="f1-step-icon"><i class="fas fa-user-check"></i></div>
                                @if(sizeof($pengajuan) > 0)
                                    @if($pengajuan[0]->id_status_pengajuan == 1)
                                        <span class="badge badge-success-soft mt-2"><i class="fas fa-clock mr-2"></i>{{$pengajuan[0]->status_pengajuan}}</span>
                                    @elseif($pengajuan[0]->id_status_pengajuan == 2)
                                        <p>Persetujuan Admin</p>
                                    @elseif($pengajuan[0]->id_status_pengajuan == 3)
                                        <br/>
                                        <span class="badge badge-danger-soft mt-2"><i class="fas fa-clock mr-2"></i>{{$pengajuan[0]->status_pengajuan}}</span>
                                        <button class="btn btn-sm btn-success-soft mt-2" id="btn-detail">Detail
                                            Penolakan
                                        </button>
                                    @endif
                                @else
                                    <p>Persetujuan Admin</p>
                                @endif
                            </div>
                            <div
                                class="f1-step @if(sizeof($pengajuan) > 0) @if($pengajuan[0]->id_status_pengajuan == 2) active @endif @endif">
                                <div class="f1-step-icon">
                                    <i class="fas fa-cloud-download-alt"></i>
                                </div>
                                @if(sizeof($pengajuan) > 0)
                                    @if($pengajuan[0]->id_status_pengajuan == 2)
                                        <a target="_blank"
                                           href="{{route('mahasiswa.akademik.sertifikat_labkom.generate', ['id_sertifikat' => $sertifikat->id_sertifikat])}}"
                                           class="btn btn-sm btn-success-soft mt-2">Unduh Sertifikat</a>
                                    @else
                                        <p>Unduh Sertifikat</p>
                                    @endif
                                @else
                                    <p>Unduh Sertifikat</p>
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
    <div class="modal modal-primary fade" id="modal-ajukan-sertifikat" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <img src="{{asset('image/bg-03.jpg')}}" alt="..." class="card-img-top" style="max-height: 50%">
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
                            <p class="card-text">
                                <span class="badge badge-pill badge-success-soft" style="font-size: larger">
                                    {{$nilai[0]->nilai_huruf}}
                                </span>
                            <hr/>
                            <span class="text-danger">
                                @if(sizeof($pengajuan) > 0)
                                    {{$pengajuan[0]->alasan_penolakan}}
                                @else
                                    Sebelum Mengajukan Sertifikat, Pastikan Nama, NIM, Tempat Tanggal Lahir, dan Nilai
                                    sudah sesuai
                                @endif
                            </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    @if(sizeof($pengajuan) > 0)
                        @if($pengajuan[0]->id_status_pengajuan == 3)
                            <a href="{{route('mahasiswa.akademik.sertifikat_labkom.add_pengajuan')}}"
                               class="btn btn-success"
                               id="modal-btn-ajukan"><i
                                    class="fas fa-clipboard-check mr-2"></i>Ajukan Ulang
                            </a>
                        @endif
                    @else
                        <a href="{{route('mahasiswa.akademik.sertifikat_labkom.add_pengajuan')}}"
                           class="btn btn-success"
                           id="modal-btn-ajukan"><i
                                class="fas fa-clipboard-check mr-2"></i>Ajukan Sertifikat
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('adminpage/own-js/mahasiswa_page/akademik/sertifikat_labkom.js')}}"></script>
@endpush
