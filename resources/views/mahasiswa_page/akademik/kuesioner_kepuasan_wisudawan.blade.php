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
                    <div class="col-xl-12 text-center">
                        <form method="post"
                              action="{{route('mahasiswa.akademik.kuesioner_kepuasan_wisudawan.insert_response')}}"
                              class="f1">
                            @csrf
                            <h3 class="mb-1 font-weight-600">KUESIONER SURVEI KEPUASAN MAHASISWA</h3>
                            <ol class="mb-4 text-left">
                                <li>Survei ini bertujuan untuk menentukan kebijakan dalam upaya meningkatkan kualitas
                                    layanan akademik, layanan bagian, dan unit-unit yang terdapat di STIE Mandala.
                                </li>
                                <li>Kuesioner ini bersifat <b>ANONIM</b></li>
                                <li>Klik salah satu pada pilihan jawaban yang tersedia</li>
                            </ol>
                            <hr/>
                            @foreach($unsur AS $item)
                                <fieldset id="{{$item->urutan_huruf}}">
                                    <h5 class="mt-4 mb-3 font-weight-600">{{$item->urutan_huruf.'. '.$item->unsur_penilaian}}</h5>
                                    @foreach($sub_unsur[$item->id_unsur] AS $item)
                                        <hr/>
                                        <div class="form-group">
                                            <label
                                                class="font-weight-bold">{{$item->nomor_urut.'. '.$item->pernyataan}}</label><br/>
                                            <div class="ml-3">
                                                @foreach($nilai AS $n)
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" id="{{$n->akronim.'_'.$item->id_sub_unsur}}"
                                                           type="radio" name="{{$item->id_sub_unsur}}"
                                                           value="{{$n->nilai}}" required>
                                                    <label class="form-check-label" for="{{$n->akronim.'_'.$item->id_sub_unsur}}">{{$n->keterangan}}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="f1-buttons">
                                        @if(!$loop->first)
                                            <button type="button" class="btn btn-previous">Previous</button>
                                        @endif
                                        @if($loop->last)
                                            <button type="submit" class="btn btn-success btn-submit">Submit</button>
                                        @else
                                            <button type="button" class="btn btn-success btn-next">Next</button>
                                        @endif
                                    </div>
                                </fieldset>
                            @endforeach
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/bootstrap-wizard/jquery.backstretch.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/mahasiswa_page/akademik/kuesioner_kepuasan_wisudawan.js')}}"></script>
@endpush
