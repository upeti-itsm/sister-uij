@extends('sidebar')

@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akun</li>
            <li class="breadcrumb-item active">Profil</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Profil</h1>
                <small>Halaman ini digunakan untuk melihat dan mengubah profil</small>
            </div>
        </div>
    </div>
@endsection

@section('body-content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="mb-3">
                                @if (isset(\Illuminate\Support\Facades\Session::get('user')->id_mhs))
                                    <img src="http://siakad.stie-mandala.ac.id/_report/photo_m/{{ \Illuminate\Support\Facades\Session::get('user')->nim }}.jpg"
                                        class="img-fluid rounded-circle" style="width:120px;height:120px;object-fit:cover"
                                        onerror="this.src='{{ asset('adminpage/assets/dist/img/avatar-1.jpg') }}'"
                                        alt="">
                                @else
                                    <img src="/files/profil_karyawan/{{ \Illuminate\Support\Facades\Session::get('user')->id_personal }}/{{ \Illuminate\Support\Facades\Session::get('karyawan')->path_photo ?? '' }}"
                                        class="img-fluid rounded-circle" style="width:120px;height:120px;object-fit:cover"
                                        onerror="this.src='{{ asset('adminpage/assets/dist/img/avatar-1.jpg') }}'"
                                        alt="">
                                @endif
                            </div>
                            <h6 class="mb-1">{{ ucfirst($detail->nama ?? '-') }}</h6>
                            <small class="text-muted">
                                {{ \Illuminate\Support\Facades\Session::get('peran')['aktif_'] ?? '' }}
                            </small>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab"
                                    aria-controls="info" aria-selected="true">
                                    <i class="fas fa-info-circle mr-2"></i>Informasi Pribadi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="kontak-tab" data-toggle="tab" href="#kontak" role="tab"
                                    aria-controls="kontak" aria-selected="false">
                                    <i class="fas fa-phone mr-2"></i>Kontak & Alamat
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="keluarga-tab" data-toggle="tab" href="#keluarga" role="tab"
                                    aria-controls="keluarga" aria-selected="false">
                                    <i class="fas fa-home mr-2"></i>Keluarga
                                </a>
                            </li>
                        </ul>

                        <!-- Tab content -->
                        <div class="tab-content" id="profileTabsContent">
                            <!-- Tab: Informasi Pribadi -->
                            <div class="tab-pane fade show active" id="info" role="tabpanel"
                                aria-labelledby="info-tab">
                                <div class="pt-3">
                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 font-weight-600">Nama</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0">{{ $detail->nama ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <hr class="my-2">

                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 font-weight-600">NIM</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0">{{ $detail->nim ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <hr class="my-2">

                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 font-weight-600">Jenis Kelamin</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0">{{ $detail->jenis_kelamin ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <hr class="my-2">

                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 font-weight-600">Tempat, Tanggal Lahir</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0">{{ $detail->ttl ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab: Kontak & Alamat -->
                            <div class="tab-pane fade" id="kontak" role="tabpanel" aria-labelledby="kontak-tab">
                                <div class="pt-3">
                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 font-weight-600">Email</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0">
                                                <a href="mailto:{{ $detail->email }}">{{ $detail->email ?? '-' }}</a>
                                            </p>
                                        </div>
                                    </div>
                                    <hr class="my-2">

                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 font-weight-600">No. Telepon</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0">
                                                <a href="tel:{{ $detail->telp }}">{{ $detail->telp ?? '-' }}</a>
                                            </p>
                                        </div>
                                    </div>
                                    <hr class="my-2">

                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 font-weight-600">Alamat</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0">{{ $detail->alamat ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab: Keluarga -->
                            <div class="tab-pane fade" id="keluarga" role="tabpanel" aria-labelledby="keluarga-tab">
                                <div class="pt-3">
                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 font-weight-600">Nama Ibu</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0">{{ $detail->nama_ibu ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <hr class="my-2">

                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 font-weight-600">Nama Wali</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            <p class="text-muted mb-0">{{ $detail->nama_wali ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
