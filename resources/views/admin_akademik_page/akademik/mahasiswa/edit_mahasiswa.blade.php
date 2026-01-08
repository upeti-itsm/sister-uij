@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <style>
        .form-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius:  5px;
            margin-bottom:  20px;
            border-left: 4px solid #007bff;
        }
        .form-section-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 15px;
            color: #007bff;
        }
        .required-label:after {
            content: " *";
            color: red;
        }
        .is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .alert {
            animation: slideDown 0.3s ease-out;
        }
        /* Loading Overlay AJAX */
        .loading-overlay-ajax {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:  rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(3px);
        }

        .loading-spinner-ajax {
            background: white;
            padding: 40px 60px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: scaleIn 0.3s ease-out;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .loading-spinner-ajax .spinner-border {
            border-width: 0.4rem;
        }

        .loading-spinner-ajax h5 {
            margin-top: 20px;
            font-weight: 600;
            color: #007bff;
        }

        .loading-spinner-ajax p {
            margin-top: 10px;
            margin-bottom: 0;
            font-size: 14px;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform:  translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('admin_akademik.akademik.mahasiswa.sinkronisasi_mahasiswa_siakad.index')}}">Mahasiswa</a></li>
            <li class="breadcrumb-item active">Edit Mahasiswa</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-primary mr-3"><i class="fas fa-user-edit"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Edit Data Mahasiswa</h1>
                <small>Form untuk mengubah data mahasiswa</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>{!!  session('success') !!}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>{!! session('error') !!}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">
                            <i class="fas fa-edit mr-2"></i>Form Edit Mahasiswa
                        </h6>
                    </div>
                    <div class="text-right">
                        <a href="{{route('admin_akademik.akademik.mahasiswa.sinkronisasi_mahasiswa_siakad.index')}}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{route('mahasiswa.update', $mahasiswa->nim)}}" method="POST" id="form-edit-mahasiswa">
                    @csrf
                    @method('PUT')
                    <!-- Hidden Fields untuk data yang tidak diubah -->
                    <input type="hidden" name="angkatan" value="{{$mahasiswa->angkatan}}">
                    <input type="hidden" name="kd_status_mahasiswa" value="{{$mahasiswa->kd_status_mahasiswa}}">
                    <input type="hidden" name="dosen_wali" value="{{$mahasiswa->dosen_wali}}">
                    <input type="hidden" name="jenis_kelas_siakad" value="{{$mahasiswa->jenis_kelas_siakad}}">
                    <!-- Info Mahasiswa -->
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>NIM:</strong> {{$mahasiswa->nim}}
                            </div>
                            <div class="col-md-3">
                                <strong>Angkatan:</strong> {{$mahasiswa->angkatan}}
                            </div>
                            <div class="col-md-3">
                                <strong>Program Studi:</strong> {{$mahasiswa->nama_prodi}}
                            </div>
                            <div class="col-md-3">
                                <strong>Status: </strong>
                                @if($mahasiswa->status_mahasiswa_label == 'Aktif')
                                    <span class="badge badge-success">{{$mahasiswa->status_mahasiswa_label}}</span>
                                @else
                                    <span class="badge badge-secondary">{{$mahasiswa->status_mahasiswa_label}}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- DATA PRIBADI -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-user mr-2"></i>DATA PRIBADI
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required-label">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('nama_mahasiswa') is-invalid @enderror"
                                           name="nama_mahasiswa" value="{{old('nama_mahasiswa', $mahasiswa->nama_mahasiswa)}}" required>
                                    @error('nama_mahasiswa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="required-label">Jenis Kelamin</label>
                                    <select class="form-control @error('kd_jenis_kelamin') is-invalid @enderror"
                                            name="kd_jenis_kelamin" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="lk" {{old('kd_jenis_kelamin', $mahasiswa->kd_jenis_kelamin) == 'lk' ? 'selected' : ''}}>Laki-laki</option>
                                        <option value="pr" {{old('kd_jenis_kelamin', $mahasiswa->kd_jenis_kelamin) == 'pr' ? 'selected' : ''}}>Perempuan</option>
                                    </select>
                                    @error('kd_jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>NIK</label>
                                    <input type="text" class="form-control" name="nik"
                                           value="{{old('nik', $mahasiswa->nik)}}" maxlength="16">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required-label">Tempat Lahir</label>
                                    <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                                           name="tempat_lahir" value="{{old('tempat_lahir', $mahasiswa->tempat_lahir)}}" required>
                                    @error('tempat_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                           name="tanggal_lahir" value="{{old('tanggal_lahir', $mahasiswa->tanggal_lahir)}}" required>
                                    @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required-label">Agama</label>
                                    <select class="form-control select2 @error('id_agama') is-invalid @enderror"
                                            name="id_agama" required>
                                        <option value="">-- Pilih Agama --</option>
                                        @foreach($agama as $item)
                                            <option value="{{$item->id_agama}}"
                                                {{old('id_agama', $mahasiswa->id_agama) == $item->id_agama ? 'selected' : ''}}>
                                                {{$item->agama}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_agama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>NISN</label>
                                    <input type="text" class="form-control" name="nisn"
                                           value="{{old('nisn', $mahasiswa->nisn)}}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Kewarganegaraan</label>
                                    <select class="form-control" name="kewarganegaraan">
                                        <option value="WNI" {{old('kewarganegaraan', $mahasiswa->kewarganegaraan) == 'WNI' ? 'selected' : ''}}>WNI</option>
                                        <option value="WNA" {{old('kewarganegaraan', $mahasiswa->kewarganegaraan) == 'WNA' ? 'selected' : ''}}>WNA</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status Menikah</label>
                                    <select class="form-control" name="is_kawin">
                                        <option value="">-- Pilih --</option>
                                        <option value="0" {{old('is_kawin', $mahasiswa->is_kawin) === '0' || old('is_kawin', $mahasiswa->is_kawin) === false ? 'selected' : ''}}>Belum Menikah</option>
                                        <option value="1" {{old('is_kawin', $mahasiswa->is_kawin) === '1' || old('is_kawin', $mahasiswa->is_kawin) === true ? 'selected' : ''}}>Menikah</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Jenis Tinggal</label>
                                    <select class="form-control select2" name="id_jenis_tinggal">
                                        <option value="">-- Pilih --</option>
                                        @foreach($jenis_tinggal as $item)
                                            <option value="{{$item->id_jenis_tinggal}}"
                                                {{old('id_jenis_tinggal', $mahasiswa->id_jenis_tinggal ??  '') == $item->id_jenis_tinggal ? 'selected' : ''}}>
                                                {{$item->jenis_tinggal}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KONTAK & ALAMAT -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-address-card mr-2"></i>KONTAK & ALAMAT
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Alamat Lengkap</label>
                                    <textarea class="form-control" name="alamat" rows="3">{{old('alamat', $mahasiswa->alamat)}}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>RT</label>
                                    <input type="text" class="form-control" name="rt"
                                           value="{{old('rt', $mahasiswa->rt)}}" maxlength="3">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>RW</label>
                                    <input type="text" class="form-control" name="rw"
                                           value="{{old('rw', $mahasiswa->rw)}}" maxlength="3">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Kelurahan/Desa</label>
                                    <input type="text" class="form-control" name="kelurahan"
                                           value="{{old('kelurahan', $mahasiswa->kelurahan)}}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Kota/Kabupaten</label>
                                    <input type="text" class="form-control" name="kota_rumah"
                                           value="{{old('kota_rumah', $mahasiswa->kota_rumah)}}">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Kode Pos</label>
                                    <input type="text" class="form-control" name="kode_pos"
                                           value="{{old('kode_pos', $mahasiswa->kode_pos)}}" maxlength="5">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Alat Transportasi</label>
                                    <select class="form-control select2" name="id_alat_transportasi">
                                        <option value="">-- Pilih --</option>
                                        @foreach($alat_transportasi as $item)
                                            <option value="{{$item->id_alat_transportasi}}"
                                                {{old('id_alat_transportasi', $mahasiswa->id_alat_transportasi ??  '') == $item->id_alat_transportasi ? 'selected' : ''}}>
                                                {{$item->alat_transportasi}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Telepon Rumah</label>
                                    <input type="text" class="form-control" name="telepon"
                                           value="{{old('telepon', $mahasiswa->telepon)}}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Handphone</label>
                                    <input type="text" class="form-control" name="handphone"
                                           value="{{old('handphone', $mahasiswa->handphone)}}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email"
                                           value="{{old('email', $mahasiswa->email)}}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DATA KELUARGA -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-users mr-2"></i>DATA KELUARGA
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Ibu Kandung</label>
                                    <input type="text" class="form-control" name="nama_ibu"
                                           value="{{old('nama_ibu', $mahasiswa->nama_ibu)}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Wali</label>
                                    <input type="text" class="form-control" name="nama_wali"
                                           value="{{old('nama_wali', $mahasiswa->nama_wali)}}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DATA AKADEMIK -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-graduation-cap mr-2"></i>DATA AKADEMIK
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required-label">Program Studi</label>
                                    <select class="form-control select2 @error('kd_prodi') is-invalid @enderror"
                                            name="kd_prodi" required>
                                        <option value="">-- Pilih Program Studi --</option>
                                        @foreach($program_studi as $item)
                                            <option value="{{$item->kd_program_studi}}"
                                                {{old('kd_prodi', $mahasiswa->kd_prodi) == $item->kd_program_studi ? 'selected' : ''}}>
                                                {{$item->jenjang_didik}} - {{$item->nama_program_studi}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kd_prodi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Pendanaan</label>
                                    <select class="form-control select2" name="kd_jenis_pendanaan">
                                        <option value="">-- Pilih --</option>
                                        @foreach($jenis_pendanaan as $item)
                                            <option value="{{$item->kd_jenis_pendanaan}}"
                                                {{old('kd_jenis_pendanaan', $mahasiswa->kd_jenis_pendanaan) == $item->kd_jenis_pendanaan ? 'selected' : ''}}>
                                                {{$item->jenis_pendanaan}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Dosen Wali</label>
                                    <select class="form-control select2" name="dosen_wali">
                                        <option value="">-- Pilih --</option>
                                        @foreach($dosen as $item)
                                            <option value="{{$item->nidn}}"
                                                {{old('dosen_wali', $mahasiswa->dosen_wali ??  '') == $item->nidn ? 'selected' : ''}}>
                                                {{$item->nama_dosen}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Mahasiswa</label>
                                    <select class="form-control" name="kd_jenis_mahasiswa">
                                        <option value="">-- Pilih --</option>
                                        <option value="1" {{old('kd_jenis_mahasiswa', $mahasiswa->kd_jenis_mahasiswa) == '1' ? 'selected' : ''}}>Reguler</option>
                                        <option value="2" {{old('kd_jenis_mahasiswa', $mahasiswa->kd_jenis_mahasiswa) == '2' ? 'selected' : ''}}>Transfer</option>
                                        <option value="3" {{old('kd_jenis_mahasiswa', $mahasiswa->kd_jenis_mahasiswa) == '3' ? 'selected' :  ''}}>Alih Program</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DATA PENDIDIKAN SEBELUMNYA -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-school mr-2"></i>DATA PENDIDIKAN SEBELUMNYA
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sekolah Asal (SMA/SMK)</label>
                                    <input type="text" class="form-control" name="sekolah_asal"
                                           value="{{old('sekolah_asal', $mahasiswa->sekolah_asal)}}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Jurusan SMA/SMK</label>
                                    <input type="text" class="form-control" name="jurusan_sma"
                                           value="{{old('jurusan_sma', $mahasiswa->jurusan_sma)}}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal Lulus SMA/SMK</label>
                                    <input type="date" class="form-control" name="tgl_lulus_sma"
                                           value="{{old('tgl_lulus_sma', $mahasiswa->tgl_lulus_sma)}}">
                                </div>
                            </div>
                        </div>
                        <!-- TAMBAHAN:  Nomor Transkrip -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nomor Transkrip</label>
                                    <input type="text" class="form-control" name="nomor_transkrip"
                                           value="{{old('nomor_transkrip', $mahasiswa->nomor_transkrip)}}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DATA KELULUSAN (Jika sudah lulus) -->
                    @if($mahasiswa->kd_status_mahasiswa == '3')
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="fas fa-certificate mr-2"></i>DATA KELULUSAN
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tanggal Lulus</label>
                                        <input type="date" class="form-control" name="tgl_lulus"
                                               value="{{old('tgl_lulus', $mahasiswa->tgl_lulus)}}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nomor Ijazah</label>
                                        <input type="text" class="form-control" name="nomor_ijazah"
                                               value="{{old('nomor_ijazah', $mahasiswa->nomor_ijazah)}}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nomor Seri Ijazah</label>
                                        <input type="text" class="form-control" name="nomor_seri_ijazah"
                                               value="{{old('nomor_seri_ijazah', $mahasiswa->nomor_seri_ijazah)}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>IPK</label>
                                        <input type="number" step="0.01" class="form-control" name="ipk"
                                               value="{{old('ipk', $mahasiswa->ipk)}}">
                                    </div>
                                </div>

                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label>Judul Skripsi/Tugas Akhir</label>
                                        <textarea class="form-control" name="judul_skripsi" rows="2">{{old('judul_skripsi', $mahasiswa->judul_skripsi)}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- TOMBOL AKSI -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="float-right">
                                <a href="{{route('admin_akademik.akademik.mahasiswa.sinkronisasi_mahasiswa_siakad.index')}}" class="btn btn-secondary">
                                    <i class="fas fa-times mr-2"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="btn-submit">
                                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/admin_akademik/akademik/mahasiswa/edit_mahasiswa.js')}}"></script>
@endpush
