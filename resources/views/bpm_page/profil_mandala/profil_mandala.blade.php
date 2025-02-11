@extends('sidebar')
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item active">Profil Mandala</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-th-large"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Profil Mandala</h1>
                <small>Modul ini digunakan untuk mengelola profil institusi mandala</small>
            </div>
        </div>
    </div>
@endsection
@section('head-css')
@endsection
@section('body-content')
    <div class="col-md-4">
        <div class="card mb-4">
            <img src="{{asset('image/bg-01.jpg')}}" alt="..." class="card-img-top" style="max-height: 90px">
            <div class="card-body text-center">
                <a class="avatar avatar-xl card-avatar card-avatar-top mb-3" style="cursor: pointer" title="Ganti Logo">
                    <img style="max-width: 100%!important;"
                         src="{{asset('image/logo-mandala.png')}}"
                         onerror="this.src='{{asset('adminpage/assets/dist/img/avatar-1.jpg')}}'" alt="...">
                </a>
                <h6 class="card-title font-weight-600 mb-2">
                    <a href="#">Institut Teknologi dan Sains Mandala</a>
                </h6>
                <small class="card-text text-muted mb-2">Jl. Sumatra No.118-120, Tegal Boto Lor, Sumbersari, Kec.
                    Sumbersari, Kabupaten Jember, Jawa Timur 68121</small>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form id="so_form" method="post" enctype="multipart/form-data"
                      action="{{route('bpm_page.profil_mandala.update_struktur')}}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Struktur Organisasi</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" accept=".jpg,.jpeg,.png" id="file_struktur"
                                   name="file_so">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                        <button type="button" class="btn btn-success-soft btn-block mt-2" id="btn-simpan-so">Update Struktur Organisasi</button>
                        <a class="btn btn-info-soft btn-block mt-2" href="{{asset('/'.$dokumen->link_dokumen)}}">Unduh Struktur Organisasi</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Visi</label>
                    <textarea class="form-control" placeholder="Masukkan Visi Institusi" id="visi_"
                              name="visi">{{$visi->visi}}</textarea>
                    <button class="btn btn-outline-success right mt-2" id="btn-simpan-visi">Simpan Data</button>
                </div>
                <div class="form-group" style="margin-top: 75px">
                    <label class="form-label">Misi</label>
                    <hr/>
                    <div class="table-responsive">
                        <table class="table table-hover" id="table">
                            <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Misi</th>
                                <th class="text-center"><i class="fas fa-th"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($misi AS $item)
                                <tr>
                                    <td style="width: 5%" class="text-center">{{$item->nomor_urut}}</td>
                                    <td style="width: 75%">{{$item->misi}}</td>
                                    <td style="width: 20%" class="text-center">
                                        <button class="btn btn-sm btn-delete btn-danger mr-1" data-id="{{$item->id}}"
                                                data-nomor="{{$item->nomor_urut}}" data-misi="{{$item->misi}}"
                                                title="Delete Misi">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button class="btn btn-sm btn-edit btn-purple" data-id="{{$item->id}}"
                                                data-nomor="{{$item->nomor_urut}}" data-misi="{{$item->misi}}"
                                                title="Edit Misi">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button class="btn btn-outline-success right mt-2" id="btn-tambah-misi">Tambah Misi</button>
                </div>
            </div>
        </div>
    </div>
    <form action="{{route('bpm_page.profil_mandala.insup_misis')}}" id="form-misi" style="display: none" method="POST">
        @csrf
        <input type="hidden" id="id_misi" name="id">
        <input type="hidden" id="nomor" name="nomor">
        <input type="hidden" id="misi" name="misi">
    </form>
    <form action="{{route('bpm_page.profil_mandala.delete_misis')}}" id="form-misi-del" style="display: none"
          method="POST">
        @csrf
        <input type="hidden" id="id_misi_del" name="id">
    </form>
    <form action="{{route('bpm_page.profil_mandala.update_visis')}}" id="form-visi" style="display: none" method="POST">
        @csrf
        <input type="hidden" id="id_visi" name="id" value="{{$visi->id}}">
        <input type="hidden" id="visi" name="visi">
    </form>
@endsection
@section('modal')
@endsection
@push('scripts')
    <script src="{{asset('adminpage/own-js/bpm_page/profile_mandala/profile.js')}}"></script>
@endpush
