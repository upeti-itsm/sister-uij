@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Data Kepegawaian</li>
            <li class="breadcrumb-item active">Ubah Data</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Ubah Data Pegawai</h1>
                <small>Halaman ini digunakan untuk mengubah data pegawai</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <div class="card mb-4">

        </div>
    </div>
    <div class="col-md-12 mt-4">
        <form action="{{route('hrd.data_kepegawaian.list_data_pegawai.update')}}" method="POST"
              enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h6 class="fs-17 font-weight-600 mb-0">Identitas Pribadi</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
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
                                        <input type="file" style="display: none" id="photo_profile"
                                               accept=".jpg,.jpeg,.png">
                                        <button type="button" class="btn btn-block btn-outline-success mb-2"
                                                id="btn_pilih_foto"><span
                                                class="spinner-border spinner-border-sm text-info" style="display: none"
                                                id="photo_profile_loading" role="status"></span>Pilih Foto
                                        </button>
                                        <small class="text-muted">
                                            Besar file: maksimum 10.000.000 bytes (10 MB)<br/>
                                            Ekstensi file yang diperbolehkan: .JPG/.JPEG/.PNG
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="text-muted">Nomor KTP <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" required name="no_ktp"
                                                       value="{{old('no_ktp') ? old('no_ktp') : $karyawan->no_ktp}}"
                                                       placeholder="Nomor KTP" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="text-muted">Jenis Kelamin <span
                                                        class="text-danger">*</span></label>
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
                                                <label class="text-muted">Nama Lengkap <span
                                                        class="text-danger">*</span></label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <input type="text" class="form-control"
                                                               placeholder="Gelar Depan"
                                                               name="gelar_depan"
                                                               value="{{old('gelar_depan') ? old('gelar_depan') : $karyawan->gelar_depan}}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control"
                                                               placeholder="Nama Sesuai KTP"
                                                               name="nama" required
                                                               value="{{old('nama') ? old('nama') : $karyawan->nama}}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="text" class="form-control"
                                                               placeholder="Gelar Belakang"
                                                               name="gelar_belakang"
                                                               value="{{old('gelar_belakang') ? old('gelar_belakang') : $karyawan->gelar_belakang}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="text-muted">Bank Penggajian<span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control select2" name="jenis_bank" required>
                                                    <option>-- Pilih Jenis Bank --</option>
                                                    <option value="1"
                                                            @if(old('jenis_bank') == "1" || $karyawan->id_nama_rekening_bank == "1") selected @endif>Bank Rakyat Indonesia
                                                    </option>
                                                    <option value="2"
                                                            @if(old('jenis_bank') == "2" || $karyawan->id_nama_rekening_bank == "2") selected @endif>Bank Syariah Indonesia
                                                    </option>
                                                    <option value="3"
                                                            @if(old('jenis_bank') == "3" || $karyawan->id_nama_rekening_bank == "3") selected @endif>Bank Jatim Syariah
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="text-muted">Tempat, Tanggal Lahir <span
                                                        class="text-danger">*</span></label>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control"
                                                               placeholder="Tempat Lahir"
                                                               name="tempat_lahir" required
                                                               value="{{old('tempat_lahir') ? old('tempat_lahir') : $karyawan->tempat_lahir}}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" readonly class="form-control"
                                                               id="tanggal_lahir"
                                                               placeholder="Tanggal Lahir">
                                                        <input type="hidden" id="tgl_lahir" required name="tgl_lahir"
                                                               value="{{old('tgl_lahir') ? old('tgl_lahir') : $karyawan->tanggal_lahir}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-muted">Alamat <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="alamat" required
                                                  placeholder="Alamat Lengkap Sesuai KTP">{{old('alamat') ? old('alamat') : $karyawan->alamat}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="text-muted">Agama <span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="id_agama">
                                            <option>-- Pilih Agama --</option>
                                            @foreach($agama AS $item)
                                                <option value="{{$item->id_agama}}"
                                                        @if(old('id_agama') == $item->id_agama || $karyawan->id_agama == $item->id_agama) selected @endif>{{$item->agama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="text-muted">Nomor HP <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Nomor HP" name="no_hp"
                                               value="{{old('no_hp') ? old('no_hp') : $karyawan->no_hp}}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="text-muted">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" placeholder="Email" name="email"
                                               value="{{old('email') ? old('email') : $karyawan->email}}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="text-muted">IP Absensi <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" placeholder="IP untuk absensi" name="ip_absensi"
                                               value="{{old('ip_absensi') ? old('ip_absensi') : $karyawan->ip}}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="fs-17 font-weight-600 mb-0">Identitas Kepegawaian</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">NIK</label>
                                <input type="text" class="form-control" placeholder="Nomor Induk Karyawan"
                                       name="nik" value="{{old('nik') ? old('nik') : $karyawan->nik}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Nomor Rekening</label>
                                <input type="text" class="form-control" placeholder="Nomor Rekening" name="no_rekening"
                                       value="{{old('no_rekening') ? old('no_rekening') : $karyawan->nomor_rekening}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Tanggal Aktif <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="tanggal_aktif"
                                       placeholder="Tanggal Aktif" readonly>
                                <input type="hidden" class="form-control" id="tgl_aktif" required
                                       placeholder="Tanggal Aktif" name="tgl_aktif"
                                       value="{{old('tgl_aktif') ? old('tgl_aktif') : $karyawan->tanggal_masuk}}">
                            </div>
                        </div>
                        @if($karyawan->id_jenis_karyawan == 4 || $karyawan->id_jenis_karyawan == 5)
                            <input type="hidden" name="jenis_karyawan" value="{{$karyawan->id_jenis_karyawan}}">
                        @else
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">NIDN</label>
                                    <input type="text" class="form-control" placeholder="Nomor Induk Dosen Nasional"
                                           name="nidn" value="{{old('nidn') ? old('nidn') : $karyawan->nidn}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">NIP (<small>Di isi jika yang bersangkutan adalah
                                            DPK</small>)</label>
                                    <input type="text" class="form-control" placeholder="Nomor Induk Pegawai"
                                           name="nip" value="{{old('nip') ? old('nip') : $karyawan->nip}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Jenis Dosen <small class="text-danger">*</small></label>
                                    <select class="form-control select2" name="jenis_karyawan">
                                        <option>-- Pilih Jenis Dosen --</option>
                                        <option value="1"
                                                @if(old('jenis_karyawan') == 1 || $karyawan->id_jenis_karyawan == 1) selected @endif>
                                            Dosen Tetap
                                        </option>
                                        <option value="2"
                                                @if(old('jenis_karyawan') == 2 || $karyawan->id_jenis_karyawan == 2) selected @endif>
                                            Dosen LB
                                        </option>
                                        <option value="3"
                                                @if(old('jenis_karyawan') == 3 || $karyawan->id_jenis_karyawan == 3) selected @endif>
                                            Dosen Praktisi
                                        </option>
                                        <option value="6"
                                                @if(old('jenis_karyawan') == 6 || $karyawan->id_jenis_karyawan == 6) selected @endif>
                                            Dosen Dpk
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" style="text-align: left;">
                                    <label class="text-muted">Sinta ID</label>
                                    <div class="custom-file">
                                        <input type="text" class="form-control" name="id_sinta" id="id_sinta"
                                               placeholder="Masukkan ID Sinta"
                                               value="{{old('id_sinta', $karyawan->id_sinta)}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Home Base <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2" id="home_base"
                                            name="home_base" required>
                                        <option value="0">Tidak Ada Homebase</option>
                                        @foreach($prodi AS $item)
                                            <option
                                                value="{{$item->kd_program_studi}}"
                                                @if(old('home_base', $karyawan->kd_program_studi) == $item->kd_program_studi) selected @endif>{{$item->jenjang_didik.'-'.$item->nama_program_studi}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Status Sertifikasi <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2" id="status_sertifikasi"
                                            name="status_sertifikasi[]" required multiple>
                                        <option value="0"
                                                @if(old('status_sertifikasi', $karyawan->sts_sertifikasi) == 0) selected @endif>
                                            Belum
                                            Sertifikasi
                                        </option>
                                        <option value="1" @if(old('status_sertifikasi') == 1) selected @endif>
                                            Pekerti
                                        </option>
                                        <option value="2" @if(old('status_sertifikasi') == 2) selected @endif>
                                            Serdos
                                        </option>
                                        <option value="3" @if(old('status_sertifikasi') == 3) selected @endif>
                                            Profesional
                                        </option>
                                        <option value="4" @if(old('status_sertifikasi') == 4) selected @endif>
                                            BNSP
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4"
                                 @if(old('status_sertifikasi', $karyawan->sts_sertifikasi) != 1) style="text-align: left; display: none"
                                 @endif id="unggah_sertifikat_area">
                                <div class="form-group">
                                    <label class="text-muted">Unggah Sertifikat</label>
                                    <div class="custom-file">
                                        <input type="file" accept=".pdf"
                                               class="custom-file-input"
                                               id="file_sertifikat" name="file_sertifikat">
                                        <label class="custom-file-label" for="customFile">Pilih file</label>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Golongan</label>
                                <select class="form-control select2" name="golongan">
                                    <option value="0">-- Pilih Golongan --</option>
                                    @foreach($golongan AS $item)
                                        <option value="{{$item->id_golongan}}"
                                                @if(old('golongan') == $item->id_golongan || $karyawan->id_golongan == $item->id_golongan) selected @endif>{{$item->golongan}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="text-align: left">
                                <label class="text-muted">Unggah SK Golongan</label>
                                <div class="custom-file">
                                    <input type="file" accept=".pdf"
                                           class="custom-file-input"
                                           id="file_sk_golongan" name="file_sk_golongan">
                                    <label class="custom-file-label" for="customFile">Pilih file</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">TMT Golongan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="tgl_tmt_golongan"
                                       placeholder="TMT Golongan" readonly>
                                <input type="hidden" class="form-control" id="tmt_golongan" required
                                       placeholder="Tanggal Lahir" name="tmt_golongan"
                                       value="{{old('tmt_golongan') ? old('tmt_golongan') : $karyawan->tmt_golongan}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Jabatan Struktural</label>
                                <select class="form-control select2" name="jastruk">
                                    <option value="0">-- Pilih Jabatan Struktural --</option>
                                    @foreach($jabatan_struktural AS $item)
                                        <option value="{{$item->id_jabatan_struktural}}"
                                                @if(old('jabatan_struktural') == $item->id_jabatan_struktural || $karyawan->id_jabatan_struktural == $item->id_jabatan_struktural) selected @endif>{{$item->jabatan_struktural}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="text-align: left">
                                <label class="text-muted">Unggah SK Jabatan Struktural</label>
                                <div class="custom-file">
                                    <input type="file" accept=".pdf"
                                           class="custom-file-input"
                                           id="file_sk_jabatan_struktural" name="file_sk_jastruk">
                                    <label class="custom-file-label" for="customFile">Pilih file</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">TMT Jabatan Struktural <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="tgl_tmt_jabatan_struktural"
                                       placeholder="TMT Golongan" readonly>
                                <input type="hidden" class="form-control" id="tmt_jabatan_struktural" required
                                       placeholder="Tanggal Lahir" name="tmt_jastruk"
                                       value="{{old('tmt_jastruk') ? old('tmt_jastruk') : $karyawan->tmt_jabatan_struktural}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Jabatan Fungsional</label>
                                <select class="form-control select2" name="jafung">
                                    <option value="0">-- Pilih Jabatan Fungsional --</option>
                                    @foreach($jabatan_fungsional AS $item)
                                        <option value="{{$item->id_jabatan_fungsional}}"
                                                @if(old('jabatan_fungsional') == $item->id_jabatan_fungsional || $karyawan->id_jabatan_fungsional == $item->id_jabatan_fungsional) selected @endif>{{$item->jabatan_fungsional}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="text-align: left">
                                <label class="text-muted">Unggah SK Jabatan Fungsional</label>
                                <div class="custom-file">
                                    <input type="file" accept=".pdf"
                                           class="custom-file-input"
                                           id="file_sk_jabatan_fungsional" name="file_sk_jafung">
                                    <label class="custom-file-label" for="customFile">Pilih file</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">TMT Jabatan Fungsional <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="tgl_tmt_jabatan_fungsional"
                                       placeholder="TMT Jabatan Fungsional" readonly>
                                <input type="hidden" class="form-control" id="tmt_jabatan_fungsional" required
                                       placeholder="Tanggal Lahir" name="tmt_jafung"
                                       value="{{old('tmt_jafung') ? old('tmt_jafung') : $karyawan->tmt_jabatan_fungsional}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Pendidikan Terakhir <span class="text-danger">*</span></label>
                                <select class="form-control select2" required name="pendidikan">
                                    <option value="">-- Pilih Pendidikan Terakhir --</option>
                                    @foreach($pendidikan AS $item)
                                        <option value="{{$item->kd_pendidikan_terakhir}}"
                                                @if(old('pendidikan_terakhir') == $item->kd_pendidikan_terakhir || $karyawan->kd_pendidikan == $item->kd_pendidikan_terakhir) selected @endif>{{$item->pendidikan}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="text-align: left">
                                <label class="text-muted">Unggah Ijazah</label>
                                <div class="custom-file">
                                    <input type="file" accept=".pdf"
                                           class="custom-file-input"
                                           id="file_ijazah" name="file_ijazah">
                                    <label class="custom-file-label" for="customFile">Pilih file</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Tanggal Lulus <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="tanggal_lulus"
                                       placeholder="Tanggal Lulus" readonly>
                                <input type="hidden" class="form-control" id="tgl_lulus" required
                                       placeholder="Tanggal Lulus" name="tgl_lulus"
                                       value="{{old('tgl_lulus') ? old('tgl_lullus') : $karyawan->tanggal_lulus}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Unit Kerja <span class="text-danger">*</span></label>
                                <select class="form-control select2" required name="unit_kerja">
                                    <option>-- Pilih Unit Kerja --</option>
                                    @foreach($unit_kerja AS $item)
                                        <option value="{{$item->id_unit_kerja}}"
                                                @if(old('unit_kerja') == $item->id_unit_kerja || $karyawan->id_unit_kerja == $item->id_unit_kerja) selected @endif>{{$item->unit_kerja}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-muted">Status Menikah <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="status_menikah" name="status_menikah" required>
                                    <option value="">-- Pilih Status Menikah --</option>
                                    <option value="1"
                                            @if(old('status_menikah') == 1 || $karyawan->id_status_pernikahan == 1) selected @endif>
                                        Belum Kawin
                                    </option>
                                    <option value="2"
                                            @if(old('status_menikah') == 2 || $karyawan->id_status_pernikahan == 2) selected @endif>
                                        Kawin
                                    </option>
                                    <option value="3"
                                            @if(old('status_menikah') == 3 || $karyawan->id_status_pernikahan == 3) selected @endif>
                                        Cerai Hidup
                                    </option>
                                    <option value="4"
                                            @if(old('status_menikah') == 4 || $karyawan->id_status_pernikahan == 4) selected @endif>
                                        Cerai Mati
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="text-align: left">
                                <label class="text-muted">Unggah Scan KK</label>
                                <div class="custom-file">
                                    <input type="file" accept=".pdf"
                                           class="custom-file-input"
                                           id="file_kk" name="file_kk">
                                    <label class="custom-file-label" for="customFile">Pilih file</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="float-right">
                                <a href="{{route('hrd.data_kepegawaian.list_data_pegawai.detail', ['id' => $karyawan->id_personal])}}"
                                   class="btn btn-outline-danger">Batal</a>
                                <button class="btn btn-success">Simpan Data</button>

                                <a href="{{route('hrd.data_kepegawaian.data_anak_karyawan.index', ['id' => $karyawan->id_personal])}}"
                                   class="btn btn-primary" id="btn-tambah-anak"
                                   @if($karyawan->id_status_pernikahan == 1 || is_null($karyawan->id_status_pernikahan)) style="display: none" @endif><i
                                        class="fas fa-users mr-2"></i>Tambah
                                    Data Anak</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('modal')
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.id.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/hrd_page/data_kepegawaian/edit_data_pegawai.js')}}"></script>
@endpush
