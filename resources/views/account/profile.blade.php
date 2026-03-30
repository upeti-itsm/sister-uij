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
                                    <img
                                        src="http://siakad.stie-mandala.ac.id/_report/photo_m/{{ \Illuminate\Support\Facades\Session::get('user')->nim }}.jpg"
                                        class="img-fluid rounded-circle" style="width:120px;height:120px;object-fit:cover"
                                        onerror="this.src='{{ asset('adminpage/assets/dist/img/avatar-1.jpg') }}'"
                                        alt="">
                                @else
                                    <img
                                        src="/files/profil_karyawan/{{ \Illuminate\Support\Facades\Session::get('user')->id_personal }}/{{ \Illuminate\Support\Facades\Session::get('karyawan')->path_photo ?? '' }}"
                                        class="img-fluid rounded-circle" style="width:120px;height:120px;object-fit:cover"
                                        onerror="this.src='{{ asset('adminpage/assets/dist/img/avatar-1.jpg') }}'"
                                        alt="">
                                @endif
                            </div>
                            <h6 class="mb-1">{{ $user->nama_lengkap ?? '-' }}</h6>
                            <small class="text-muted">{{ \Illuminate\Support\Facades\Session::get('peran')['aktif_'] ?? '' }}</small>
                        </div>
                    </div>

                    <div class="col-md-8">
                        @if (!isset($user->id_personal))
                            <div class="alert alert-info mb-0">
                                Profil mahasiswa ditampilkan dari data akun. Untuk memperbarui data, gunakan menu <b>Sync Profil</b>.
                            </div>
                        @else
                            <form method="POST" action="{{ route('account.profile.update') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Nomor HP</label>
                                            <input type="text" class="form-control" name="no_hp"
                                                   value="{{ old('no_hp') ?? ($karyawan->no_hp ?? '') }}"
                                                   placeholder="Nomor HP">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Email</label>
                                            <input type="email" class="form-control" name="email"
                                                   value="{{ old('email') ?? ($karyawan->email ?? '') }}"
                                                   placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Alamat</label>
                                            <textarea class="form-control" name="alamat" rows="3" placeholder="Alamat">{{ old('alamat') ?? ($karyawan->alamat ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-2"></i>Simpan
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
