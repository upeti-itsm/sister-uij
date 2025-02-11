@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Data Kepegawaian</li>
            <li class="breadcrumb-item">Data Pegawai</li>
            <li class="breadcrumb-item active">Data Anak</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-users"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Data Anak</h1>
                <small>Halaman ini digunakan untuk pengelolaan data anak pegawai</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0 text-white">Daftar Anak</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">Pencarian</label>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control"
                                                   placeholder="Cari Nama"
                                                   id="cari-data">
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-block btn-primary" id="btn-cari-data"><i
                                                    class="fas fa-search mr-2"></i>Cari Data
                                            </button>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{route('hrd.data_kepegawaian.list_data_pegawai.index')}}"
                                               class="btn btn-block btn-success"><i class="fas fa-users mr-2"></i>Daftar
                                                Karyawan</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Nomor</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Usia</th>
                                    <th class="text-center"><i class="fas fa-th"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0 text-white">Tambah Data Anak</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{route('hrd.data_kepegawaian.data_anak_karyawan.insup')}}" method="POST" id="form_insup">
                    @csrf
                    <input type="hidden" id="id_personal" name="id_personal" value="{{$karyawan->id_personal}}">
                    <input type="hidden" id="id_anak" name="id_anak" value="0">
                    <div class="form-group">
                        <label class="text-muted">NIK <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nik"
                               placeholder="NIK Sesuai KK" id="nik" maxlength="16" minlength="16">
                    </div>
                    <div class="form-group">
                        <label class="text-muted">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama"
                               placeholder="Nama Lengkap Sesuai KK" id="nama">
                    </div>
                    <div class="form-group">
                        <label class="text-muted">Jenis Kelamin <span
                                class="text-danger">*</span></label>
                        <select class="form-control select2" name="jenis_kelamin" id="jenis_kelamin" required>
                            <option>-- Pilih Jenis Kelamin --</option>
                            <option value="lk">Laki-Laki</option>
                            <option value="pr">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="text-muted">Tempat Lahir <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Tempat Lahir"
                               name="tempat_lahir" id="tempat_lahir" required>
                    </div>
                    <div class="form-group">
                        <label class="text-muted">Tanggal Lahir <span
                                class="text-danger">*</span></label>
                        <input type="text" readonly class="form-control" id="tanggal_lahir"
                               placeholder="Tanggal Lahir">
                        <input type="hidden" id="tgl_lahir" required name="tgl_lahir">
                    </div>
                    <div class="form-group">
                        <div class="float-right">
                            <button class="btn btn-outline-danger" type="reset">Reset</button>
                            <button class="btn btn-success"><i class="fas fa-save mr-2"></i>Simpan
                            </button>
                        </div>
                    </div>
                </form>
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
    <script src="{{asset('adminpage/own-js/hrd_page/data_kepegawaian/data_anak.js')}}"></script>
    @if($is_insert)
        <script>
            $(document).ready(function () {
                var pesan = '{{\Illuminate\Support\Facades\Session::get('success_message_on_data_anak')}}';
                $.confirm({
                    title: 'Konfirmasi !',
                    type: 'orange',
                    columnClass: 'medium',
                    content: pesan + "<hr/>Apakah pegawai ini mempunyai anak ?",
                    buttons: {
                        confirm: {
                            text: 'Ya',
                            btnClass: 'btn-green',
                            keys: ['enter'],
                        },
                        cancel: {
                            text: 'Tidak',
                            btnClass: 'btn-red',
                            action: function () {
                                location.href = '/hrd/data-kepegawaian/list-data-pegawai';
                            }
                        }
                    }
                });
            });
        </script>
    @endif
@endpush
