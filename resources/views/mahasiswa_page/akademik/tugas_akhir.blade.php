@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item active">Tugas Akhir</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Tugas Akhir</h1>
                <small>Halaman ini digunakan untuk mengajukan/melihat proses pengajuan tugas akhir</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body">
                <table style="font-weight: bold">
                    <tbody>
                    <tr>
                        <td style="width: 30%">Nama</td>
                        <td style="width: 70%">: {{\Illuminate\Support\Facades\Session::get('user')->nama_lengkap}}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%">NIM</td>
                        <td style="width: 70%">: {{\Illuminate\Support\Facades\Session::get('user')->nim}}</td>
                    </tr>
                    </tbody>
                </table>
                <hr/>
                <div class="form-group">
                    <label class="text-secondary">Judul Skripsi (Maksimal 200 karakter)</label>
                    <textarea class="form-control" maxlength="5" id="judul_ta"
                              placeholder="Masukkan Judul Skripsi"></textarea>
                </div>
                <div class="form-group">
                    <label class="text-secondary">Matakuliah Dasar</label>
                    <select class="form-control select2" id="matkul_dasar">
                        <option>Ekonomi Mikro</option>
                        <option>Ekonomi Makro</option>
                        <option>Pemasaran</option>
                        <option>Manajemen Informatika</option>
                    </select>
                </div>
                <div class="form-group text-right">
                    <button class="btn btn-info-soft"><i class="fas fa-save mr-2"></i>Simpan</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Seminar Proposal</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="activity-list list-unstyled">
                    <li class="activity-primary">
                        <span class="text-success text-sm"><i class="fas fa-user mr-2"></i>MAHASISWA</span>
                        <h5><a href="#" class="d-block fs-15 font-weight-600 text-sm mb-0">Upload File Draft</a></h5>
                        <div class="form-group" style="text-align: left">
                            <div class="custom-file">
                                <input type="file" accept=".jpg,.jpeg,.png,.pdf"
                                       class="custom-file-input"
                                       required id="file_draft">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <small class="text-muted"><i class="far fa-clock mr-1"></i>05 Oktober 2021</small>
                    </li>
                    <li class="activity-primary">
                        <span class="text-success text-sm"><i class="fas fa-user mr-2"></i>KAPRODI</span>
                        <h5><a href="#" class="d-block fs-15 font-weight-600 text-sm mb-0">Penetapan Judul dan Dosen
                                Pembimbing</a></h5>
                        <ul class="mb-2" style="font-weight: bold">
                            <li>DPU: Dr. MUHAMMAD FIRDAUS, S.P., M.M., M.P, CiQAR</li>
                            <li>DPA: Drs. BAGUS QOMARUZZAMAN RATU E,M.P</li>
                        </ul>
                        <small class="text-muted"><i class="far fa-clock mr-1"></i>15 Oktober 2021</small>
                    </li>
                    <li class="activity-primary">
                        <span class="text-success text-sm"><i class="fas fa-user mr-2"></i>BAGIAN AKADEMIK</span>
                        <h5><a href="#" class="d-block fs-15 font-weight-600 text-sm mb-0">Cetak Surat Tugas
                                Pembimbing</a></h5>
                        <small class="text-muted"><i class="far fa-clock mr-1"></i>15 Oktober 2021</small>
                    </li>
                    <li class="activity-primary">
                        <span class="text-success text-sm"><i class="fas fa-user mr-2"></i>MAHASISWA</span>
                        <h5><a href="#" class="d-block fs-15 font-weight-600 text-sm mb-0">Upload Proposal Final</a>
                        </h5>
                        <div class="form-group" style="text-align: left">
                            <div class="custom-file">
                                <input type="file" accept=".jpg,.jpeg,.png,.pdf"
                                       class="custom-file-input"
                                       required id="file_draft">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <small class="text-muted"><i class="far fa-clock mr-1"></i>05 Oktober 2021</small>
                    </li>
                    <li class="activity-primary">
                        <span class="text-success text-sm"><i class="fas fa-user mr-2"></i>DOSEN PEMBIMBING</span>
                        <h5><a href="#" class="d-block fs-15 font-weight-600 text-sm mb-0">Persetujuan Proposal
                                Final</a></h5>
                        <small class="text-muted"><i class="far fa-clock mr-1"></i>15 Oktober 2021</small>
                    </li>
                    <li class="activity-primary">
                        <span class="text-success text-sm"><i class="fas fa-user mr-2"></i>MAHASISWA</span>
                        <h5><a href="#" class="d-block fs-15 font-weight-600 text-sm mb-0">Cetak Berita Acara Seminar
                                Proposal</a></h5>
                        <button class="btn btn-info-soft mb-2"><i class="fas fa-print mr-2"></i>Download Berita Acara
                        </button>
                        <br/>
                        <small class="text-muted"><i class="far fa-clock mr-1"></i>15 Oktober 2021</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Sidang Tugas Akhir</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="activity-list list-unstyled">
                    <li class="activity-primary">
                        <span class="text-success text-sm"><i class="fas fa-user mr-2"></i>MAHASISWA</span>
                        <h5><a href="#" class="d-block fs-15 font-weight-600 text-sm mb-0">Upload File Tugas Akhir Final</a></h5>
                        <div class="form-group" style="text-align: left">
                            <div class="custom-file">
                                <input type="file" accept=".jpg,.jpeg,.png,.pdf"
                                       class="custom-file-input"
                                       required id="file_draft">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <small class="text-muted"><i class="far fa-clock mr-1"></i>05 Oktober 2021</small>
                    </li>
                    <li class="activity-primary">
                        <span class="text-success text-sm"><i class="fas fa-user mr-2"></i>DOSEN PEMBIMBING</span>
                        <h5><a href="#" class="d-block fs-15 font-weight-600 text-sm mb-0">Persetujuan File
                                Tugas Akhir Final</a></h5>
                        <small class="text-muted"><i class="far fa-clock mr-1"></i>15 Oktober 2021</small>
                    </li>
                    <li class="activity-primary">
                        <span class="text-success text-sm"><i class="fas fa-user mr-2"></i>KAPRODI</span>
                        <h5><a href="#" class="d-block fs-15 font-weight-600 text-sm mb-0">Penetapan Penguji Utama</a></h5>
                        <ul class="mb-2" style="font-weight: bold">
                            <li>Dr. MUHAMMAD FIRDAUS, S.P., M.M., M.P, CiQAR</li>
                        </ul>
                        <small class="text-muted"><i class="far fa-clock mr-1"></i>15 Oktober 2021</small>
                    </li>
                    <li class="activity-primary">
                        <span class="text-success text-sm"><i class="fas fa-user mr-2"></i>BAGIAN AKADEMIK</span>
                        <h5><a href="#" class="d-block fs-15 font-weight-600 text-sm mb-0">Cetak Surat Tugas
                                Penguji</a></h5>
                        <small class="text-muted"><i class="far fa-clock mr-1"></i>15 Oktober 2021</small>
                    </li>
                    <li class="activity-primary">
                        <span class="text-success text-sm"><i class="fas fa-user mr-2"></i>BAGIAN AKADEMIK</span>
                        <h5><a href="#" class="d-block fs-15 font-weight-600 text-sm mb-0">Penentuan Jadwal Sidang Tugas Akhir</a></h5>
                        <table class="mb-2 ml-5" style="font-weight: bold">
                            <tbody>
                            <tr>
                                <td style="width: 35%">Hari, Tanggal</td>
                                <td style="width: 65%">: Sabtu, 28 Januari 2022</td>
                            </tr>
                            <tr>
                                <td style="width: 35%">Jam</td>
                                <td style="width: 65%">: 08:00 WIB s/d 10:00 WIB</td>
                            </tr>
                            <tr>
                                <td style="width: 35%">Ruangan</td>
                                <td style="width: 65%">: A.24</td>
                            </tr>
                            </tbody>
                        </table>
                        <small class="text-muted"><i class="far fa-clock mr-1"></i>15 Oktober 2021</small>
                    </li>
                    <li class="activity-primary">
                        <span class="text-success text-sm"><i class="fas fa-user mr-2"></i>MAHASISWA</span>
                        <h5><a href="#" class="d-block fs-15 font-weight-600 text-sm mb-0">Cetak Berita Acara Sidang Tugas Akhir</a></h5>
                        <button class="btn btn-info-soft mb-2"><i class="fas fa-print mr-2"></i>Download Berita Acara
                        </button>
                        <br/>
                        <small class="text-muted"><i class="far fa-clock mr-1"></i>15 Oktober 2021</small>
                    </li>
                    <li class="activity-primary">
                        <span class="text-success text-sm"><i class="fas fa-user mr-2"></i>MAHASISWA</span>
                        <h5><a href="#" class="d-block fs-15 font-weight-600 text-sm mb-0">Upload File Revisi Tugas Akhir</a></h5>
                        <div class="form-group" style="text-align: left">
                            <div class="custom-file">
                                <input type="file" accept=".jpg,.jpeg,.png,.pdf"
                                       class="custom-file-input"
                                       required id="file_draft">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <small class="text-muted"><i class="far fa-clock mr-1"></i>05 Oktober 2021</small>
                    </li>
                    <li class="activity-primary">
                        <span class="text-success text-sm"><i class="fas fa-user mr-2"></i>DOSEN PEMBIMBING</span>
                        <h5><a href="#" class="d-block fs-15 font-weight-600 text-sm mb-0">Persetujuan File
                                Tugas Akhir Final</a></h5>
                        <small class="text-muted"><i class="far fa-clock mr-1"></i>15 Oktober 2021</small>
                    </li>
                    <li class="activity-primary">
                        <span class="text-success text-sm"><i class="fas fa-user mr-2"></i>BAGIAN AKADEMIK</span>
                        <h5><a href="#" class="d-block fs-15 font-weight-600 text-sm mb-0">Input Nilai Tugas Akhir</a></h5>
                        <button class="btn btn-info-soft mb-2"><i class="fas fa-refresh mr-2"></i>Cek Nilai</button>
                        <br/>
                        <small class="text-muted"><i class="far fa-clock mr-1"></i>15 Oktober 2021</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('modal')

@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/mahasiswa_page/akademik/tugas_akhir.js')}}"></script>
@endpush
