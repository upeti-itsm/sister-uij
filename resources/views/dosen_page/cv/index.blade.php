@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item active">Curriculum Vitae</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Daftar Riwayat Hidup</h1>
                <small>Halaman ini digunakan untuk mengelola daftar riwayat hidup dan portofolio masing-masing
                    dosen</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Data Pribadi</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <tr>
                        <th>Nama</th>
                        <th>{{$personal->nama_lengkap}}</th>
                    </tr>
                    <tr>
                        <th>Program Studi</th>
                        <th>{{str_replace('Program Studi ', '', $personal->unit_kerja)}}</th>
                    </tr>
                    <tr>
                        <th>NIDN</th>
                        <th>{{$personal->nidn}}</th>
                    </tr>
                    <tr>
                        <th>NIP/NIK</th>
                        <th>{{$personal->nik_mandala}}</th>
                    </tr>
                    <tr>
                        <th>Jabatan Fungsional</th>
                        <th>{{$personal->jabatan_fungsional}}</th>
                    </tr>
                    <tr>
                        <th>Tempat, Tanggal Lahir</th>
                        <th>{{$personal->tempat_tanggal_lahir}}</th>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <th>{{$personal->email}}</th>
                    </tr>
                    <tr>
                        <th>Nomor Telpon / HP</th>
                        <th>{{$personal->nomor_hp}}</th>
                    </tr>
                    <tr>
                        <th>Alamat Rumah</th>
                        <th>{{$personal->alamat}}</th>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Matakuliah yang diampu</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">No</th>
                                <th class="text-center" style="width: 95%">Matakuliah</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="2">SEMESTER GANJIL</td>
                            </tr>
                            @php($no = 1)
                            @foreach($course[0] AS $item)
                                <tr>
                                    <td style="width: 5%">{{$no++}}</td>
                                    <td style="width: 95%">{{$item->fullname}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2">SEMESTER GENAP</td>
                            </tr>
                            @php($no = 1)
                            @foreach($course[1] AS $item)
                                <tr>
                                    <td style="width: 5%">{{$no++}}</td>
                                    <td style="width: 95%">{{$item->fullname}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Riwayat Pendidikan</h6>
                    </div>
                    <div class="text-right">
                        <button type="button" id="btn-add-pendidikan" class="btn btn-info rounded-pill"
                                title="Ubah Pendidikan"><i class="fas fa-plus mr-2"></i>Ubah Data
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row">
                            <div class="col-md-5">

                            </div>
                            <div class="col-md-7">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="collapse" id="form_collapse">
                            <form action="{{route('dosen.cv.curriculum_vitae.update_pendidikan')}}" method="post" id="form-pendidikan">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Jenjang Pendidikan</label>
                                            <select class="form-control select2" name="jenjang_pendidikan"
                                                    id="jenjang_pendidikan" data-placeholder="Pilih Jenjang Pendidikan">
                                                <option></option>
                                                @if($pendidikan[3]->isi_s1 != '-')
                                                    <option value="7">Sarjana - S1</option>
                                                @endif
                                                @if($pendidikan[3]->isi_s2 != '-')
                                                    <option value="8">Magister - S2</option>
                                                @endif
                                                @if($pendidikan[3]->isi_s3 != '-')
                                                    <option value="9">Magister - S3</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Nama Perguruan Tinggi</label>
                                            <input type="text" class="form-control" name="nama_pt" id="nama_pt"
                                                   placeholder="Masukkan Nama Perguruan Tinggi">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="form-label">Nama Program Studi</label>
                                                <input type="text" class="form-control" name="prodi"
                                                       id="nama_prodi" placeholder="Masukkan Nama Program Studi Anda">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label">Bidang Ilmu</label>
                                            <input type="text" class="form-control"
                                                   placeholder="Masukkan Bidang Ilmu Anda" name="bid_ilmu"
                                                   id="bidang_ilmu">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Tanggal Lulus</label>
                                            <input type="text" readonly placeholder="Pilih Tanggal Lulus" id="tanggal_lulus" class="form-control">
                                            <input type="hidden" name="tgl_lulus" id="tgl_lulus" value="{{old('tgl_lulus', now('Asia/Jakarta')->format('Y-m-d'))}}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" id="btn-cancel-pendidikan"
                                                class="btn btn-labeled btn-danger right ml-2">
                                            <span class="btn-label"><i class="fas fa-times"></i></span>Batal
                                        </button>
                                        <button type="button" id="btn-save-pendidikan"
                                                class="btn btn-labeled btn-primary right">
                                            <span class="btn-label"><i class="fas fa-save"></i></span>Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <hr/>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <tbody>
                            @foreach($pendidikan AS $item)
                                <tr>
                                    <td>{{$item->jud_keterangan}}</td>
                                    <td>{{$item->isi_s1}}</td>
                                    <td>{{$item->isi_s2}}</td>
                                    <td>{{$item->isi_s3}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Publikasi di Jurnal Nasional dalam 5 tahun terakhir</h6>
                    </div>
                    <div class="text-right">
                        <button type="button" id="btn-add-jurnal-nasional" class="btn btn-info rounded-pill"
                                title="Tambah Publikasi"><i class="fas fa-plus mr-2"></i>Tambah Data
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse_jurnal">
                        <div class="row">
                            <div class="col-md-5">

                            </div>
                            <div class="col-md-7">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="collapse" id="form_collapse_jurnal">
                            <form action="{{route('dosen.cv.curriculum_vitae.store_publikasi_jurnal')}}" method="post" id="form-jurnal-nasional">
                                @csrf
                                <input type="hidden" name="id_riwayat" value="0" id="id_riwayat_jurnal_nasional">
                                <input type="hidden" name="jenis_jurnal" value="1">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Judul Artikel</label>
                                            <input type="text" class="form-control" name="judul_artikel"
                                                   id="judul_artikel"
                                                   placeholder="Masukkan Judul Artikel">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="form-label">Nama Jurnal</label>
                                                <input type="text" class="form-control" name="nama_jurnal"
                                                       id="nama_jurnal" placeholder="Masukkan Nama Jurnal">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="form-label">Volume</label>
                                                <input type="text" class="form-control" name="volume"
                                                       id="vol_jurnal" placeholder="Masukkan Volume Jurnal">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="form-label">Nomor</label>
                                                <input type="text" class="form-control" name="nomor"
                                                       id="no_jurnal" placeholder="Masukkan Nomor Jurnal">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Tahun Terbit</label>
                                            <select class="form-select select2" name="tahun_publikasi"
                                                    id="tahun_terbit" data-placeholder="Pilih Tahun">
                                                <option></option>
                                                @php($year = (now()->year - 6))
                                                @for($i = 0; $i < 6; $i++)
                                                    @php($year++)
                                                    <option
                                                        value="{{$year}}">{{$year}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" id="btn-cancel-jurnal-nasional"
                                                class="btn btn-labeled btn-danger right ml-2">
                                            <span class="btn-label"><i class="fas fa-times"></i></span>Batal
                                        </button>
                                        <button type="button" id="btn-save-jurnal-nasional"
                                                class="btn btn-labeled btn-primary right">
                                            <span class="btn-label"><i class="fas fa-save"></i></span>Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <hr/>
                            <form type="hidden" action="#" id="form-delete-jurnal-nasional" method="post">
                                @csrf
                                <input type="hidden" name="id" id="del_id_riwayat_jurnal_nasional">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table-jurnal-nasional">
                            <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Tahun</th>
                                <th class="text-center">Judul Artikel</th>
                                <th class="text-center">Nama Jurnal/Volume/Nomor</th>
                                <th class="text-center"><i class="fas fa-th"></i></th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Publikasi di Jurnal Internasional dalam 5 tahun
                            terakhir</h6>
                    </div>
                    <div class="text-right">
                        <button type="button" id="btn-add-jurnal-internasional" class="btn btn-info rounded-pill"
                                title="Tambah Publikasi"><i class="fas fa-plus mr-2"></i>Tambah Data
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse_jurnal_internasional">
                        <div class="row">
                            <div class="col-md-5">

                            </div>
                            <div class="col-md-7">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="collapse" id="form_collapse_jurnal_internasional">
                            <form action="{{route('dosen.cv.curriculum_vitae.store_publikasi_jurnal')}}" method="post" id="form-jurnal-internasional">
                                @csrf
                                <input type="hidden" name="id_riwayat" value="0" id="id_riwayat_jurnal_internasional">
                                <input type="hidden" name="jenis_jurnal" value="0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Judul Artikel</label>
                                            <input type="text" class="form-control" name="judul_artikel"
                                                   id="judul_artikel_internasional"
                                                   placeholder="Masukkan Judul Artikel">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="form-label">Nama Jurnal</label>
                                                <input type="text" class="form-control" name="nama_jurnal"
                                                       id="nama_jurnal_internasional"
                                                       placeholder="Masukkan Nama Jurnal">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="form-label">Volume</label>
                                                <input type="text" class="form-control" name="volume"
                                                       id="vol_jurnal_internasional"
                                                       placeholder="Masukkan Volume Jurnal">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="form-label">Nomor</label>
                                                <input type="text" class="form-control" name="nomor"
                                                       id="no_jurnal_internasional" placeholder="Masukkan Nomor Jurnal">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Tahun Terbit</label>
                                            <select class="form-select select2" name="tahun_publikasi"
                                                    id="tahun_terbit_internasional" data-placeholder="Pilih Tahun">
                                                <option></option>
                                                @php($year = (now()->year - 6))
                                                @for($i = 0; $i < 6; $i++)
                                                    @php($year++)
                                                    <option
                                                        value="{{$year}}">{{$year}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" id="btn-cancel-jurnal-internasional"
                                                class="btn btn-labeled btn-danger right ml-2">
                                            <span class="btn-label"><i class="fas fa-times"></i></span>Batal
                                        </button>
                                        <button type="button" id="btn-save-jurnal-internasional"
                                                class="btn btn-labeled btn-primary right">
                                            <span class="btn-label"><i class="fas fa-save"></i></span>Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <hr/>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table-jurnal-internasional">
                            <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Tahun</th>
                                <th class="text-center">Judul Artikel</th>
                                <th class="text-center">Nama Jurnal/Volume/Issue</th>
                                <th class="text-center"><i class="fas fa-th"></i></th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Publikasi/Kegiatan Pengabdian kepada Masyarakat dalam 5
                            tahun terakhir</h6>
                    </div>
                    <div class="text-right">
                        <button type="button" id="btn-add-pengabdian" class="btn btn-info rounded-pill"
                                title="Tambah Pengabdian"><i class="fas fa-plus mr-2"></i>Tambah Data
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse_pengabdian">
                        <div class="row">
                            <div class="col-md-5">

                            </div>
                            <div class="col-md-7">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="collapse" id="form_collapse_pengabdian">
                            <form action="{{route('dosen.cv.curriculum_vitae.store_publikasi_jurnal')}}" method="post" id="form-pengabdian">
                                @csrf
                                <input type="hidden" name="id_riwayat" value="0" id="id_riwayat_pengabdian">
                                <input type="hidden" name="jenis_jurnal" value="2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Judul Pengabdian</label>
                                            <input type="text" class="form-control" name="judul_artikel"
                                                   id="judul_pengabdian"
                                                   placeholder="Masukkan Judul Penelitian">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="form-label">Nama Jurnal</label>
                                                <input type="text" class="form-control" name="nama_jurnal"
                                                       id="nama_jurnal_pengabdian"
                                                       placeholder="Masukkan Nama Jurnal">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="form-label">Volume</label>
                                                <input type="text" class="form-control" name="volume"
                                                       id="vol_jurnal_pengabdian"
                                                       placeholder="Masukkan Volume Jurnal">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="form-label">Nomor</label>
                                                <input type="text" class="form-control" name="nomor"
                                                       id="no_jurnal_pengabdian" placeholder="Masukkan Nomor Jurnal">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Tahun</label>
                                            <select class="form-select select2" name="tahun_publikasi"
                                                    id="tahun_pengabdian" data-placeholder="Pilih Tahun">
                                                <option></option>
                                                @php($year = (now()->year - 6))
                                                @for($i = 0; $i < 6; $i++)
                                                    @php($year++)
                                                    <option
                                                        value="{{$year}}">{{$year}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" id="btn-cancel-pengabdian"
                                                class="btn btn-labeled btn-danger right ml-2">
                                            <span class="btn-label"><i class="fas fa-times"></i></span>Batal
                                        </button>
                                        <button type="button" id="btn-save-pengabdian"
                                                class="btn btn-labeled btn-primary right">
                                            <span class="btn-label"><i class="fas fa-save"></i></span>Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <hr/>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table-jurnal-pengabdian">
                            <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Tahun</th>
                                <th class="text-center">Judul Penelitian</th>
                                <th class="text-center">Nama Jurnal/Volume/Nomor</th>
                                <th class="text-center"><i class="fas fa-th"></i></th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Karya Buku dalam 5 Tahun Terakhir</h6>
                    </div>
                    <div class="text-right">
                        <button type="button" id="btn-add-buku" class="btn btn-info rounded-pill"
                                title="Tambah Buku"><i class="fas fa-plus mr-2"></i>Tambah Data
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse_buku">
                        <div class="row">
                            <div class="col-md-5">

                            </div>
                            <div class="col-md-7">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="collapse" id="form_collapse_buku">
                            <form action="#" method="post" id="form-buku">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Judul Buku</label>
                                            <input type="text" class="form-control" name="judul_buku"
                                                   id="judul_buku"
                                                   placeholder="Masukkan Judul Buku">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="form-label">ISBN</label>
                                                <input type="text" class="form-control" name="isbn"
                                                       id="isbn"
                                                       placeholder="ISBN">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Tahun</label>
                                            <select class="form-select select2" name="tahun_terbit"
                                                    id="tahun_buku" data-placeholder="Pilih Tahun">
                                                <option></option>
                                                @php($year = (now()->year - 6))
                                                @for($i = 0; $i < 6; $i++)
                                                    @php($year++)
                                                    <option
                                                        value="{{$year}}">{{$year}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="form-label">Penerbit</label>
                                                <input type="text" class="form-control" name="penerbit"
                                                       id="penerbit" placeholder="Masukkan Nama Penerbit">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" id="btn-cancel-buku"
                                                class="btn btn-labeled btn-danger right ml-2">
                                            <span class="btn-label"><i class="fas fa-times"></i></span>Batal
                                        </button>
                                        <button type="button" id="btn-save-buku"
                                                class="btn btn-labeled btn-primary right">
                                            <span class="btn-label"><i class="fas fa-save"></i></span>Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <hr/>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Judul Buku</th>
                                <th class="text-center">Tahun</th>
                                <th class="text-center">Penerbit</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Pengalaman menduduki Jabtan Struktural</h6>
                    </div>
                    <div class="text-right">
                        <button type="button" id="btn-add-jabatan" class="btn btn-info rounded-pill"
                                title="Tambah Struktural"><i class="fas fa-plus mr-2"></i>Tambah Data
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse_jabatan">
                        <div class="row">
                            <div class="col-md-5">

                            </div>
                            <div class="col-md-7">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="collapse" id="form_collapse_jabatan">
                            <form action="#" method="post" id="form-jabatan">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Periode (ex: 2001 s/d 2006)</label>
                                            <input type="text" class="form-control" name="periode"
                                                   id="periode"
                                                   placeholder="Masukkan Periode Penugasan">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="form-label">Jabatan</label>
                                                <input type="text" class="form-control" name="jabatan"
                                                       id="jabatan"
                                                       placeholder="Masukkan Nama Jabatan">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" id="btn-cancel-jabatan"
                                                class="btn btn-labeled btn-danger right ml-2">
                                            <span class="btn-label"><i class="fas fa-times"></i></span>Batal
                                        </button>
                                        <button type="button" id="btn-save-jabatan"
                                                class="btn btn-labeled btn-primary right">
                                            <span class="btn-label"><i class="fas fa-save"></i></span>Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <hr/>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Periode Penugasan</th>
                                <th class="text-center">Jabatan</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">HAKI dalam 5 Tahun Terakhir</h6>
                    </div>
                    <div class="text-right">
                        <button type="button" id="btn-add-haki" class="btn btn-info rounded-pill"
                                title="Tambah HAKI"><i class="fas fa-plus mr-2"></i>Tambah Data
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse_haki">
                        <div class="row">
                            <div class="col-md-5">

                            </div>
                            <div class="col-md-7">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="collapse" id="form_collapse_haki">
                            <form action="#" method="post" id="form-haki">
                                @csrf
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Tahun</label>
                                            <select class="form-select select2" name="tahun"
                                                    id="tahun_haki" data-placeholder="Pilih Tahun">
                                                <option></option>
                                                @php($year = (now()->year - 6))
                                                @for($i = 0; $i < 6; $i++)
                                                    @php($year++)
                                                    <option
                                                        value="{{$year}}">{{$year}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Judul Karya</label>
                                            <input type="text" class="form-control" name="judul_karya"
                                                   id="judul_karya"
                                                   placeholder="Masukkan Judul Karya">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="form-label">Nomor Sertifikat</label>
                                                <input type="text" class="form-control" name="no_sertifikat"
                                                       id="no_sertifikat"
                                                       placeholder="Masukkan Nomor Sertifikat">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" id="btn-cancel-haki"
                                                class="btn btn-labeled btn-danger right ml-2">
                                            <span class="btn-label"><i class="fas fa-times"></i></span>Batal
                                        </button>
                                        <button type="button" id="btn-save-haki"
                                                class="btn btn-labeled btn-primary right">
                                            <span class="btn-label"><i class="fas fa-save"></i></span>Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <hr/>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Tahun</th>
                                <th class="text-center">Judul</th>
                                <th class="text-center">Nomor Sertifikat</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Prestasi Lainnya (Penghargaan, Narasumber) dalam 5 Tahun
                            Terakhir</h6>
                    </div>
                    <div class="text-right">
                        <button type="button" id="btn-add-prestasi" class="btn btn-info rounded-pill"
                                title="Tambah HAKI"><i class="fas fa-plus mr-2"></i>Tambah Data
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse_prestasi">
                        <div class="row">
                            <div class="col-md-5">

                            </div>
                            <div class="col-md-7">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="collapse" id="form_collapse_prestasi">
                            <form action="#" method="post" id="form-prestasi">
                                @csrf
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Tahun</label>
                                            <select class="form-select select2" name="tahun"
                                                    id="tahun_haki" data-placeholder="Pilih Tahun">
                                                <option></option>
                                                @php($year = (now()->year - 6))
                                                @for($i = 0; $i < 6; $i++)
                                                    @php($year++)
                                                    <option
                                                        value="{{$year}}">{{$year}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Prestasi/Penghargaan/Narasumber</label>
                                            <input type="text" class="form-control" name="nama_prestasi"
                                                   id="nama_prestasi"
                                                   placeholder="Masukkan Prestasi/Penghargaan/Narasumber">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="form-label">Keterangan</label>
                                                <input type="text" class="form-control" name="keterangan"
                                                       id="keterangan"
                                                       placeholder="Masukkan Keterangan">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" id="btn-cancel-prestasi"
                                                class="btn btn-labeled btn-danger right ml-2">
                                            <span class="btn-label"><i class="fas fa-times"></i></span>Batal
                                        </button>
                                        <button type="button" id="btn-save-prestasi"
                                                class="btn btn-labeled btn-primary right">
                                            <span class="btn-label"><i class="fas fa-save"></i></span>Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <hr/>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Prestasi/Penghargaan/Narasumber</th>
                                <th class="text-center">Keterangan</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.id.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/dosen_page/cv/index.js')}}"></script>
@endpush
