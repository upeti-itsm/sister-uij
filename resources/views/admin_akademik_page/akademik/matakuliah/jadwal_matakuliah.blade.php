@extends('sidebar')
@section('head-css')
    <link href="{{ asset('adminpage/assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Perkuliahan</li>
            <li class="breadcrumb-item active">Sinkronisasi Jadwal Kuliah</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Sinkronisasi Jadwal Kuliah</h1>
                <small>Halaman ini digunakan untuk mengelola sinkronisasi jadwal kuliah antara sister dan siakad</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <input type="hidden" id="hak_akses"
        value="{{ \Illuminate\Support\Facades\Session::get('modul')['Sinkronisasi Jadwal Kuliah dengan Siakad'] }}">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Jadwal Kuliah</h6>
                    </div>
                    <div class="text-sm-right mt-3 mt-sm-0">
                        <div class="actions d-flex flex-column flex-sm-row">
                            <button type="button" class="btn btn-primary mb-2 mb-sm-0 mr-sm-2" id="btn-tambah-jadwal"><i
                                    class="fas fa-plus"></i> Tambah Jadwal</button>
                            <button class="btn btn-success btn-generate-jadwal mb-2 mb-sm-0 mr-sm-2" id="btn-generate-jadwal"
                                title="Generate Jadwal Kuliah">
                                <i class="fas fa-calendar-plus"></i> Generate Jadwal
                            </button>
                            <button class="btn btn-danger-soft btn-sync-ulang-jadwal-kuliah mb-2 mb-sm-0"
                                id="btn-sync-ulang-jadwal-kuliah" title="Syncron Dengan Siakad"><i
                                    class="fas fa-cloud-download-alt"></i> Synchron Dengan Siakad
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mt-3" id="progress-bar-generate-jadwal" style="display: none">
                        <div class="alert alert-info">
                            <button class="btn btn-primary mr-1 mb-2" type="button" disabled="">
                                <span class="spinner-border spinner-border-sm mr-2" role="status"
                                    aria-hidden="true"></span>
                                <span id="keterangan-progress-generate-jadwal">Sedang generate jadwal kuliah...</span>
                            </button>
                            <button class="btn btn-danger-soft mr-1 mb-2" id="btn-cancel-generate-jadwal">
                                <i class="fas fa-window-close mr-2"></i>Batal
                            </button>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3" id="progress-bar-syncron-ulang-jadwal-kuliah" style="display: none">
                        <button class="btn btn-primary mr-1 mb-2" type="button" disabled="">
                            <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"
                                id="loading-progress-jadwal-kuliah"></span>
                            <span id="keterangan-progress-jadwal-kuliah">Mohon menunggu hingga proses sinkronisasi selesai
                                ...</span>
                        </button>
                        <button class="btn btn-danger-soft mr-1 mb-2" id="btn-cancel-syncron-ulang-jadwal-kuliah"><i
                                class="fas fa-window-close mr-2"></i>Batal
                        </button>
                        <div class="progress progress-lg mb-3">
                            <div class="progress-bar progress-bar-violet progress-bar-striped progress-bar-animated"
                                role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0"
                                id="progress-bar-jadwal-kuliah">
                                <span id="progress-text-jadwal-kuliah"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3" id="log-syncron-ulang-jadwal-kuliah" style="display: none">
                        <div class="card bg-light">
                            <div class="card-header bg-info text-white">
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center">
                                    <div>
                                        <h6 class="fs-17 font-weight-600 mb-0">Log Sinkronisasi</h6>
                                    </div>
                                    <div class="text-sm-right mt-2 mt-sm-0">
                                        <div class="actions d-flex flex-wrap">
                                            <button class="btn btn-primary-soft mr-3 mb-2 mb-sm-0 text-white"
                                                id="btn-failed-log-jadwal-kuliah">
                                                Failed : 0
                                            </button>
                                            <button class="btn btn-primary-soft mr-3 mb-2 mb-sm-0 text-white"
                                                id="btn-inserted-log-jadwal-kuliah">
                                                Inserted : 0
                                            </button>
                                            <button class="btn btn-primary-soft mr-3 mb-2 mb-sm-0 text-white"
                                                id="btn-updated-log-jadwal-kuliah">
                                                Updated : 0
                                            </button>
                                            <button class="action-item text-white" title="Tutup Log"
                                                id="btn-tutup-log-jadwal-kuliah">
                                                <i class="fas fa-times-circle"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control"
                                                placeholder="Masukkan Nama Matakuliah" id="cari-log-jadwal-kuliah">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" id="btn-cari-log-jadwal-kuliah"><i
                                                        class="fas fa-search mr-2"></i>Cari
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select class="form-control select2" id="status-log-jadwal-kuliah">
                                                <option value="">-- All Of Them --</option>
                                                <option value="inserted">-- Inserted --</option>
                                                <option value="updated">-- Updated --</option>
                                                <option value="failed">-- Failed --</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover"
                                        id="log-table-jadwal-kuliah">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Nomor</th>
                                                <th style="width: 80%">Nama Matakuliah - (Kelas)</th>
                                                <th style="width: 15%">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="log-table-tbody-jadwal-kuliah">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 collapse show" id="filter-collapse-jadwal-kuliah">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control mb-2" placeholder="Cari Nama Matakuliah"
                                                id="cari-data-jadwal-kuliah">
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-block btn-primary" id="btn-cari-data-jadwal-kuliah"><i
                                                    class="fas fa-search mr-2"></i>Cari Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="font-weight-bold">Filtering</label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <select class="select2 form-control" id="tahun_akademik">
                                                <option value="all">-- Semua TA --</option>
                                                @foreach ($tahun_akademik as $item)
                                                    <option value="{{ $item->tahun_akademik }}">{{ $item->tahun_akademik }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <select class="select2 form-control" id="prodi">
                                                <option value="all">-- Semua Prodi --</option>
                                                @foreach ($program_studi as $item)
                                                    <option value="{{ $item->kd_program_studi }}">
                                                        {{ $item->nama_program_studi . ' (' . $item->jenjang_didik . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="select2 form-control" id="status_pengajar">
                                                <option value="-1" @if ($filter == -1) selected @endif>--
                                                    Semua Status --</option>
                                                <option value="0" @if ($filter == 0) selected @endif>
                                                    Belum Ditentukan</option>
                                                <option value="1" @if ($filter == 1) selected @endif>
                                                    Individu</option>
                                                <option value="2" @if ($filter == 2) selected @endif>
                                                    TIM</option>
                                                <option value="3" @if ($filter == 3) selected @endif>
                                                    Koordinator</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table-jadwal-kuliah">
                            <thead>
                                <tr>
                                    <th class="text-center">Nomor</th>
                                    <th>Mata Kuliah</th>
                                    <th>Dosen Pengampu</th>
                                    <th class="text-center">Hari & Waktu</th>
                                    <th class="text-center">Ruangan</th>
                                    <th class="text-center">Kapasitas</th>
                                    <th class="text-center">Status Aktif</th>
                                    <th>Status Pengajar</th>
                                    <th class="text-center"><i class="fas fa-th"></i></th>
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
    <div class="modal modal-primary fade" id="modal-tambah-jadwal-kuliah" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600">Tambah Jadwal Kuliah</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mata Kuliah</label>
                                <select class="form-control select2" id="tambah_id_ploting_matakuliah">
                                    <option value="">-- Pilih Mata Kuliah --</option>
                                    @foreach ($list_ploting_matakuliah as $ploting)
                                        <option value="{{ $ploting->id_ploting_matakuliah }}">
                                            {{ $ploting->nama_matakuliah }} ({{ $ploting->nama_kelas }}) -
                                            {{ $ploting->nama_dosen }} - {{ $ploting->tahun_akademik }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ruangan</label>
                                <select class="form-control" id="tambah_ruang_id">
                                    <option value="">-- Pilih Ruangan --</option>
                                    @foreach ($list_ruangan as $ruang)
                                        <option value="{{ $ruang->id_ruang_perkuliahan_ }}">
                                            {{ $ruang->ruang_perkuliahan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Hari</label>
                                <select class="form-control" id="tambah_hari">
                                    <option value="1">Senin</option>
                                    <option value="2">Selasa</option>
                                    <option value="3">Rabu</option>
                                    <option value="4">Kamis</option>
                                    <option value="5">Jumat</option>
                                    <option value="6">Sabtu</option>
                                    <option value="7">Minggu</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Jam Mulai</label>
                                <input type="time" class="form-control" id="tambah_jam_mulai">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Jam Selesai</label>
                                <input type="time" class="form-control" id="tambah_jam_selesai">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kapasitas</label>
                                <input type="number" class="form-control" id="tambah_kapasitas">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status Aktif</label>
                                <select class="form-control" id="tambah_sts_aktif">
                                    <option value="true">Aktif</option>
                                    <option value="false">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="modal-btn-tambah-jadwal"><i
                            class="fas fa-save mr-2"></i>Simpan Jadwal</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Generate Jadwal --}}
    <div class="modal modal-primary fade" id="modal-generate-jadwal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600">Konfirmasi Generate Jadwal Kuliah</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Proses generate jadwal akan membuat jadwal kuliah baru berdasarkan data yang tersedia. Silahkan pilih
                        tahun akademik untuk generate jadwal:</p>
                    <div class="form-group">
                        <label for="tahun_akademik_generate">Tahun Akademik</label>
                        <select class="select2 form-control" id="tahun_akademik_generate">
                            @foreach ($tahun_akademik_siakad as $item)
                                <option value="{{ $item->tahun_akademik }}{{ $item->id_semester_uij }}">
                                    {{ $item->tahun_akademik }} ({{ $item->nama_tahun_akademik }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Peringatan:</strong> Proses ini akan memakan waktu beberapa menit. Pastikan tidak menutup
                        halaman selama proses berlangsung.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="modal-btn-generate-jadwal">
                        <i class="fas fa-cog mr-2"></i>Generate Jadwal
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Sync Jadwal --}}
    <div class="modal modal-primary fade" id="modal-sync-jadwal-kuliah" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600">Konfirmasi Sinkronisasi Jadwal Kuliah</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Jika dilakukan proses sinkronisasi, jadwal kuliah yang ada di siakad akan di import ke
                        sister.uij.ac.id, silahkan pilih tahun akademik untuk proses sinkronisasi
                        jadwal kuliah</p>
                    <div class="form-group">
                        <select class="select2 form-control" id="tahun_akademik_sync-jadwal-kuliah">
                            @foreach ($tahun_akademik_siakad as $item)
                                <option value="{{ $item->tahun_akademik }}{{ $item->id_semester_uij }}">
                                    {{ $item->tahun_akademik }} ({{ $item->nama_tahun_akademik }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="modal-btn-sync-jadwal-kuliah"><i
                            class="fas fa-sync mr-2"></i>Sinkronisasi Jadwal Kuliah
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit Jadwal --}}
    <div class="modal modal-primary fade" id="modal-edit-jadwal-kuliah" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600">Edit Jadwal Kuliah</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mata Kuliah</label>
                                <input type="text" class="form-control" id="edit_nama_mata_kuliah" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kelas</label>
                                <input type="text" class="form-control" id="edit_nama_kelas" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Hari</label>
                                <select class="form-control" id="edit_hari">
                                    <option value="1">Senin</option>
                                    <option value="2">Selasa</option>
                                    <option value="3">Rabu</option>
                                    <option value="4">Kamis</option>
                                    <option value="5">Jumat</option>
                                    <option value="6">Sabtu</option>
                                    <option value="7">Minggu</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Jam Mulai</label>
                                <input type="time" class="form-control" id="edit_jam_mulai">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Jam Selesai</label>
                                <input type="time" class="form-control" id="edit_jam_selesai">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ruangan</label>
                                <select class="form-control" id="edit_ruang_id">
                                    <option value="">-- Pilih Ruangan --</option>
                                    @foreach ($list_ruangan as $ruang)
                                        <option value="{{ $ruang->id_ruang_perkuliahan_ }}">
                                            {{ $ruang->ruang_perkuliahan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status Aktif</label>
                                <select class="form-control" id="edit_status_aktif">
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="modal-btn-update-jadwal"><i
                            class="fas fa-save mr-2"></i>Update Jadwal
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('adminpage/assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('adminpage/own-js/admin_akademik/akademik/matakuliah/jadwal_matakuliah.js') }}"></script>
@endpush
