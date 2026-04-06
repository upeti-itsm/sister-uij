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
                                    <form method="POST" action="{{ route('account.profile.update') }}"
                                        enctype="multipart/form-data" id="form-upload-avatar-mahasiswa">
                                        @csrf
                                        <div class="position-relative d-inline-block" style="width:120px;height:120px;">
                                            @php
                                                $nimMahasiswa = \Illuminate\Support\Facades\Session::get('user')->nim;
                                                $avatarMahasiswa = 'http://siakad.stie-mandala.ac.id/_report/photo_m/' . $nimMahasiswa . '.jpg';

                                                $photoUrl = trim($detail->photo_url ?? '');
                                                if (!empty($photoUrl) && $photoUrl !== '-') {
                                                    if (filter_var($photoUrl, FILTER_VALIDATE_URL)) {
                                                        $avatarMahasiswa = $photoUrl;
                                                    } else {
                                                        $avatarMahasiswa = asset('files/profil_mahasiswa/' . $nimMahasiswa . '/' . ltrim($photoUrl, '/'));
                                                    }
                                                } elseif (!empty($detail->path_photo ?? null)) {
                                                    $avatarMahasiswa = asset('files/profil_mahasiswa/' . $nimMahasiswa . '/' . $detail->path_photo);
                                                }
                                            @endphp
                                            <img src="{{ $avatarMahasiswa }}" class="img-fluid rounded-circle"
                                                style="width:120px;height:120px;object-fit:cover"
                                                onerror="this.src='{{ asset('adminpage/assets/dist/img/avatar-1.jpg') }}'"
                                                alt="Foto Profil">

                                            <label for="avatar-mahasiswa-input"
                                                class="position-absolute d-flex align-items-center justify-content-center"
                                                style="right:0;bottom:0;width:34px;height:34px;border-radius:50%;background:#28a745;color:#fff;cursor:pointer;border:2px solid #fff;"
                                                title="Ubah foto profil">
                                                <i class="fas fa-camera"></i>
                                            </label>
                                            <input id="avatar-mahasiswa-input" type="file" name="path_photo"
                                                accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="d-none"
                                                onchange="handleAvatarFileChange(this)">
                                        </div>
                                    </form>
                                    <small class="d-block text-muted mt-2">Klik ikon kamera untuk upload foto</small>
                                @else
                                    <img src="/files/profil_karyawan/{{ \Illuminate\Support\Facades\Session::get('user')->id_personal }}/{{ \Illuminate\Support\Facades\Session::get('karyawan')->path_photo ?? '' }}"
                                        class="img-fluid rounded-circle" style="width:120px;height:120px;object-fit:cover"
                                        onerror="this.src='{{ asset('adminpage/assets/dist/img/avatar-1.jpg') }}'"
                                        alt="">
                                @endif
                            </div>
                            <h6 class="mb-1">{{ ucfirst($detail->nama ?? ($detail->nama_mahasiswa ?? '-')) }}</h6>
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
                                            <p class="text-muted mb-0">{{ $detail->nama ?? ($detail->nama_mahasiswa ?? '-') }}</p>
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
                                            <p class="text-muted mb-0">{{ $detail->jenis_kelamin ?? ($detail->jenis_kelamin_label ?? '-') }}</p>
                                        </div>
                                    </div>
                                    <hr class="my-2">

                                    <div class="row mb-3">
                                        <div class="col-sm-5">
                                            <h6 class="mb-0 font-weight-600">Tempat, Tanggal Lahir</h6>
                                        </div>
                                        <div class="col-sm-7">
                                            @php
                                                $ttl = $detail->ttl ?? null;
                                                if (empty($ttl)) {
                                                    $ttlParts = [];

                                                    if (!empty($detail->tempat_lahir ?? null)) {
                                                        $ttlParts[] = $detail->tempat_lahir;
                                                    }

                                                    if (!empty($detail->tanggal_lahir ?? null) && strtotime($detail->tanggal_lahir)) {
                                                        $ttlParts[] = date('d-m-Y', strtotime($detail->tanggal_lahir));
                                                    }

                                                    $ttl = !empty($ttlParts) ? implode(', ', $ttlParts) : '-';
                                                }
                                            @endphp
                                            <p class="text-muted mb-0">{{ $ttl }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab: Kontak & Alamat -->
                            <div class="tab-pane fade" id="kontak" role="tabpanel" aria-labelledby="kontak-tab">
                                <div class="pt-3">
                                    @if (isset(\Illuminate\Support\Facades\Session::get('user')->id_mhs))
                                        <form method="POST" action="{{ route('account.profile.update') }}"
                                            enctype="multipart/form-data">
                                            @csrf

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-5 col-form-label font-weight-600">Email</label>
                                                <div class="col-sm-7">
                                                    <input type="email" class="form-control" name="email"
                                                        value="{{ old('email', $detail->email ?? '') }}"
                                                        placeholder="Masukkan email aktif">
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-5 col-form-label font-weight-600">No. Telepon</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="no_hp"
                                                        value="{{ old('no_hp', $detail->telp ?? ($detail->handphone ?? ($detail->telepon ?? '')) ) }}"
                                                        placeholder="Masukkan nomor telepon">
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-5 col-form-label font-weight-600">Alamat</label>
                                                <div class="col-sm-7">
                                                    <textarea class="form-control" name="alamat" rows="3" placeholder="Masukkan alamat lengkap">{{ old('alamat', $detail->alamat ?? '') }}</textarea>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    @else
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
                                                    @php
                                                        $telp = $detail->telp ?? ($detail->handphone ?? ($detail->telepon ?? null));
                                                    @endphp
                                                    <a href="tel:{{ $telp }}">{{ $telp ?? '-' }}</a>
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
                                    @endif
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

@section('modal')
    <div class="modal fade" id="modal-konfirmasi-upload-avatar" tabindex="-1" role="dialog"
        aria-labelledby="modal-konfirmasi-upload-avatar-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-konfirmasi-upload-avatar-label">Konfirmasi Upload Foto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="avatar-preview-upload" src="{{ asset('adminpage/assets/dist/img/avatar-1.jpg') }}"
                        class="img-fluid rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover"
                        alt="Preview Foto">
                    <p class="mb-1">Apakah Anda yakin ingin upload foto ini?</p>
                    <small id="avatar-upload-filename" class="text-muted"></small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btn-batal-upload-avatar"
                        data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="btn-konfirmasi-upload-avatar">Upload</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function handleAvatarFileChange(input) {
            if (!input || !input.files || !input.files.length) {
                return;
            }

            var file = input.files[0];
            var preview = document.getElementById('avatar-preview-upload');
            var fileName = document.getElementById('avatar-upload-filename');
            var reader = new FileReader();

            reader.onload = function(e) {
                if (preview) {
                    preview.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);

            if (fileName) {
                fileName.textContent = file.name;
            }

            $('#modal-konfirmasi-upload-avatar').modal('show');
        }

        (function() {
            var btnConfirm = document.getElementById('btn-konfirmasi-upload-avatar');
            var btnCancel = document.getElementById('btn-batal-upload-avatar');
            var fileInput = document.getElementById('avatar-mahasiswa-input');
            var form = document.getElementById('form-upload-avatar-mahasiswa');

            if (btnConfirm && form) {
                btnConfirm.addEventListener('click', function() {
                    $('#modal-konfirmasi-upload-avatar').modal('hide');
                    form.submit();
                });
            }

            if (btnCancel && fileInput) {
                btnCancel.addEventListener('click', function() {
                    fileInput.value = '';
                });
            }

            $('#modal-konfirmasi-upload-avatar').on('hidden.bs.modal', function() {
                if (fileInput && document.activeElement !== btnConfirm) {
                    fileInput.value = '';
                }
            });
        })();
    </script>
@endpush
