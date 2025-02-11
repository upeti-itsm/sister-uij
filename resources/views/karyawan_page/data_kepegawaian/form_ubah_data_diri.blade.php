@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Data Kepegawaian</li>
            <li class="breadcrumb-item"><a href="{{route('karyawan.data_kepegawaian.data_diri.index')}}">Data Diri</a>
            </li>
            <li class="breadcrumb-item active">Ubah Data</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Ubah Data Diri</h1>
                <small>Halaman ini digunakan untuk mengubah data diri masing-masing pegawai</small>
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
                    <img id="user_profile" style="width: 150%!important; height: 150%!important;"
                         src="{{asset('/files/profil_karyawan/'.$karyawan->id_personal."/".$karyawan->path_photo)}}"
                         class="avatar-img rounded-circle border-card"
                         onerror="this.src='{{asset('adminpage/assets/dist/img/avatar-1.jpg')}}'" alt="...">
                </a>
                <input type="file" style="display: none" id="photo_profile" name="photo_profile"
                       accept=".jpg,.jpeg,.png">
                <button class="btn btn-block btn-outline-success mb-2" id="btn_pilih_foto"><span
                        class="spinner-border spinner-border-sm text-info" style="display: none"
                        id="photo_profile_loading" role="status"></span>Pilih Foto
                </button>
                <small class="text-muted">
                    Besar file: maksimum 10.000.000 bytes (10 MB)<br/>
                    Ekstensi file yang diperbolehkan: .JPG/.JPEG/.PNG
                </small>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="fs-17 font-weight-600 mb-0">Identitas Pribadi</h6>
            </div>
            <div class="card-body">
                <form action="{{route('karyawan.data_kepegawaian.data_diri.update_data_personal')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id_personal" id="id_personal" value="{{$karyawan->id_personal}}">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="text-muted">Nomor KTP</label>
                                <input type="text" class="form-control" placeholder="Nomor KTP" name="no_ktp"
                                       value="{{ old('no_ktp') ? old('no_ktp') : $karyawan->no_ktp}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="jenis_kelamin" required>
                                    <option>-- Pilih Jenis Kelamin --</option>
                                    <option value="lk"
                                            @if(old('jenis_kelamin') == "lk" || $karyawan->kd_jenis_kelamin == "lk") selected @endif>
                                        Laki-Laki
                                    </option>
                                    <option value="pr"
                                            @if(old('jenis_kelamin') == "pr" || $karyawan->kd_jenis_kelamin == "pr") selected @endif>
                                        Perempuan
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="text-muted">Nama Lengkap</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" placeholder="Gelar Depan"
                                               name="gelar_depan"
                                               value="{{old('gelar_depan') ? old('gelar_depan') : $karyawan->gelar_depan}}">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Nama Sesuai KTP"
                                               name="nama" value="{{old('nama') ? old('nama') : $karyawan->nama}}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" placeholder="Gelar Belakang"
                                               name="gelar_belakang"
                                               value="{{old('gelar_belakang') ? old('gelar_belakang') : $karyawan->gelar_belakang}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="text-muted">Tempat, Tanggal Lahir</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Tempat Lahir"
                                               name="tempat_lahir"
                                               value="{{old('tempat_lahir') ? old('tempat_lahir') : $karyawan->tempat_lahir}}">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" readonly class="form-control" id="tanggal_lahir"
                                               placeholder="Tanggal Lahir">
                                        <input type="hidden"
                                               value="{{old('tgl_lahir') ? old('tgl_lahir') : $karyawan->tanggal_lahir}}"
                                               class="form-control"
                                               id="tgl_lahir" name="tgl_lahir" placeholder="Tanggal Lahir">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="text-muted">Alamat <span class="text-danger">*</span></label>
                                <textarea name="alamat" class="form-control">{{old('alamat') ? old('alamat') : $karyawan->alamat}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Agama <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="agama" required>
                                    <option>-- Pilih Agama --</option>
                                    @foreach($agama AS $item)
                                        <option value="{{$item->id_agama}}"
                                                @if(old('agama') == $item->id_agama || $karyawan->id_agama == $item->id_agama) selected @endif>
                                            {{$item->agama}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Nomor HP</label>
                                <input type="text" class="form-control" placeholder="Nomor HP" name="no_hp"
                                       value="{{old('no_hp') ? old('no_hp') : $karyawan->no_hp}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Email</label>
                                <input type="email" class="form-control" placeholder="Email" name="email"
                                       value="{{old('email') ? old('email') : $karyawan->email}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group float-right">
                                <a href="{{route('karyawan.data_kepegawaian.data_diri.index')}}" class="btn btn-danger">Batal</a>
                                <input type="submit" class="btn btn-success" value="Simpan">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h6 class="fs-17 font-weight-600 mb-0">Identitas Kepegawaian</h6>
                <small>Data dibawah hanya bisa di ubah oleh HRD</small>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($karyawan->status_karyawan == 1)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Nomor Induk Karyawan (NIK)</label>
                                <input type="text" class="form-control" value="{{$karyawan->nik}}"
                                       placeholder="Nomor Induk Karyawan" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">Nomor Induk Dosen Nasional (NIDN)</label>
                                <input type="text" class="form-control" placeholder="Nomor Induk Dosen Nasional"
                                       readonly value="{{$karyawan->nidn}}">
                            </div>
                        </div>
                    @else
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="text-muted">Nomor Induk Karyawan (NIK)</label>
                                <input value="{{$karyawan->nik}}" type="text" class="form-control"
                                       placeholder="Nomor Induk Karyawan" readonly>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-muted">Nomor Rekening</label>
                            <input value="{{$karyawan->nomor_rekening}}" type="text" class="form-control"
                                   placeholder="Nomor Rekening" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-muted">Tanggal Masuk</label>
                            <input type="text" class="form-control" readonly
                                   placeholder="Tanggal Aktif" value="{{$karyawan->tanggal_masuk_}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-muted">Unit Kerja</label>
                            <input type="text" class="form-control" readonly
                                   placeholder="Unit Kerja" value="{{$karyawan->unit_kerja}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-muted">Pendidikan Terakhir</label>
                            <input type="text" class="form-control" readonly
                                   placeholder="Pendidikan Terakhir" value="{{$karyawan->pendidikan_terakhir}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-muted">Status Menikah</label>
                            <input type="text" class="form-control" readonly
                                   placeholder="Status Menikah" value="{{$karyawan->status_pernikahan}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-muted">Jumlah Anak</label>
                            <input type="text" class="form-control" readonly
                                   placeholder="Jumlah Anak" value="{{$karyawan->jumlah_anak}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-muted">Jabatan Struktural</label>
                            <input type="text" class="form-control" readonly
                                   placeholder="Jabatan Struktural"
                                   value="{{!is_null($karyawan->jabatan_struktural) ? $karyawan->jabatan_struktural : '-'}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-muted">Jabatan Fungsional</label>
                            <input type="text" class="form-control" readonly
                                   placeholder="Jabatan Fungsional" value="{{$karyawan->jabatan_fungsional}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-muted">Golongan</label>
                            <input type="text" class="form-control" readonly
                                   placeholder="Golongan Pegawai" value="{{$karyawan->golongan}}">
                        </div>
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
    <script src="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.id.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/karyawan_page/data_kepegawaian/form_ubah_data_diri.js')}}"></script>
@endpush
