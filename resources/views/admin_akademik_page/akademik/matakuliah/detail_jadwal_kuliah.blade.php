@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item">Jadwal Kuliah</li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-users"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Detail Jadwal Kuliah</h1>
                <small>Halaman ini digunakan untuk pengelolaan status pengajar pada jadwal kuliah</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0 text-white">{{strtoupper($jadwal->nama_mata_kuliah.' ('.$jadwal->kelas_id.')')}}</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 collapse show" id="filter-collapse">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Status Pengajar</label>
                                    <select class="form-control select2" id="status_pengajar">
                                        <option value="3" @if($jadwal->id_jenis_jadwal == 3) selected @endif>TIM
                                        </option>
                                        <option value="2" @if($jadwal->id_jenis_jadwal == 2) selected @endif>Koordinator
                                            (Co)
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="table">
                                        <tbody>
                                        <tr>
                                            <td>{{$jadwal->nama_dosen}}</td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input @if($jadwal->id_jenis_jadwal == 3 || is_null($jadwal->id_jenis_jadwal)) disabled
                                                           @elseif($jadwal->id_jenis_jadwal == 2) @if($jadwal->dosen_id == $jadwal->koordinator_id) checked
                                                           @endif @endif type="checkbox" class="custom-control-input"
                                                           id="dosen"
                                                           style="cursor: pointer" value="{{$jadwal->dosen_id}}">
                                                    <label class="custom-control-label" for="dosen"
                                                           style="cursor: pointer">Co</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{$jadwal->nama_asisten}}</td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input @if($jadwal->id_jenis_jadwal == 3 || is_null($jadwal->id_jenis_jadwal)) disabled
                                                           @elseif($jadwal->id_jenis_jadwal == 2) @if($jadwal->asisten_id == $jadwal->koordinator_id) checked
                                                           @endif @endif type="checkbox" class="custom-control-input"
                                                           id="asisten"
                                                           style="cursor: pointer" value="{{$jadwal->asisten_id}}">
                                                    <label class="custom-control-label" for="asisten"
                                                           style="cursor: pointer">Co</label>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="float-right">
                    <a href="{{route('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_siakad.index')}}"
                       class="btn btn-danger">Kembali</a>
                    <button class="btn btn-success" id="btn-simpan"><i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
            <form id="submit-form" style="display: none"
                  action="{{route('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_siakad.set_jenis_jadwal')}}"
                  method="post">
                @csrf
                <input type="hidden" name="id" id="id_jadwal" value="{{$jadwal->id_jadwal}}">
                <input type="hidden" name="jenis_jadwal" id="jenis_jadwal"
                       value="{{is_null($jadwal->id_jenis_jadwal) ? "3" : $jadwal->id_jenis_jadwal}}">
                <input type="hidden" name="koordinator" id="koordinator" value="{{$jadwal->koordinator_id}}">
            </form>
        </div>
    </div>
@endsection
@section('modal')
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/admin_akademik/akademik/matakuliah/detail_jadwal_kuliah.js')}}"></script>
@endpush
