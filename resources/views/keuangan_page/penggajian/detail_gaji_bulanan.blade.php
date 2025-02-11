@extends('sidebar')
@section('head-css')
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Penggajian</li>
            <li class="breadcrumb-item active">Detail Gaji Bulanan</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-user"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Detail Gaji Bulanan</h1>
                <small>Halaman ini digunakan untuk melihat detail gaji bulanan masing-masing pegawai</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        @if($rekap->is_repair)
            <div class="alert alert-danger" role="alert">
                Alasan Perbaikan :
                <hr/>
                {{$rekap->keterangan_repair}}
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Identitas Karyawan</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
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
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted">Nama Lengkap</label>
                                    <input type="text" required name="nama_lengkap"
                                           value="{{$karyawan->nama_lengkap}}" readonly
                                           placeholder="Nama Lengkap" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted">Nomor Rekening</label>
                                    <input type="text" required name="nomor_rekening"
                                           value="{{$karyawan->nomor_rekening}}" readonly
                                           placeholder="Nomor Rekening" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Periode Gaji</label>
                                    <input type="text" required name="nama_lengkap"
                                           value="{{$rekap->periode_pembayaran}}" readonly
                                           placeholder="Nama Lengkap" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Status Karyawan</label>
                                    <input type="text" required name="nomor_rekening"
                                           value="{{$karyawan->jenis_karyawan}}" readonly
                                           placeholder="Nomor Rekening" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-muted">Nomor HP</label>
                                    <input type="text" required name="nomor_hp"
                                           value="{{$karyawan->no_hp}}" readonly
                                           placeholder="Nomor Rekening" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-header bg-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-white">
                        <h6 class="fs-17 font-weight-600 mb-0">Detail Gaji</h6>
                        <small id="total_nominal_gaji">{{$rekap->total_nominal_gaji}}</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Gaji Pokok</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Rp.</div>
                                </div>
                                <input readonly type="text" id="gaji_pokok"
                                       class="form-control number"
                                       value="{{$rekap->gaji_pokok}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-white">
                                        <h6 class="fs-17 font-weight-600 mb-0">Tunjangan</h6>
                                        <small id="nominal_total_tunjangan">{{$rekap->nominal_total_tunjangan}}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tunjangan Jabatan</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number" id="tunjangan_struktural"
                                                       value="{{$rekap->tunjangan_struktural}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tunjangan Jamsos</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number" id="tunjangan_jamsos"
                                                       value="{{$rekap->tunjangan_struktural}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6"  style="display: none">
                                        <div class="form-group">
                                            <label>Tunjangan Fungsional</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number" id="tunjangan_fungsional"
                                                       value="{{$rekap->tunjangan_fungsional}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tunjangan Kinerja</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number" id="tunjangan_transport"
                                                       value="{{$rekap->tunjangan_transport}}">
                                            </div>
                                            <small
                                                class="text-danger">{{$rekap->transport_harian.' x '.$rekap->total_kehadiran.' hari' }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display: none">
                                        <div class="form-group">
                                            <label>Tunjangan Kinerja</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number" id="tunjangan_kinerja"
                                                       value="{{$rekap->nominal_tunjangan_kinerja}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Insentif Masa Kerja</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number" id="tunjangan_masa_kerja"
                                                       value="{{$rekap->nominal_tunjangan_kinerja}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6"  style="display: none">
                                        <div class="form-group">
                                            <label>Tunjangan Keluarga</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number"
                                                       id="nominal_tunjangan_keluarga"
                                                       value="{{$rekap->nominal_tunjangan_keluarga}}">
                                            </div>
                                            <small class="text-danger">{{$rekap->keterangan_tunjangan_keluarga}}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Insentif Lembur</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number"
                                                       id="nominal_insentif_lembur"
                                                       value="{{$rekap->nominal_tunjangan_lembur}}">
                                            </div>
{{--                                            <small class="text-danger">{{$rekap->keterangan_tunjangan_keluarga}}</small>--}}
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display: none">
                                        <div class="form-group">
                                            <label>Tunjangan Pendidikan</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number"
                                                       id="nominal_tunjangan_pendidikan"
                                                       value="{{$rekap->nominal_tunjangan_pendidikan}}">
                                            </div>
                                            <small class="text-danger">{{$rekap->pendidikan_terakhir}}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Insentif Kelebihan Mengajar S1/D3</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number"
                                                       id="insentif_kelebihan_mengajar"
                                                       value="{{$rekap->nominal_tunjangan_pendidikan}}">
                                            </div>
                                            <small class="text-danger">{{$rekap->pendidikan_terakhir}}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Insentif Kelebihan Mengajar S2</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number"
                                                       id="insentif_kelebihan_mengajar_2"
                                                       value="{{$rekap->nominal_tunjangan_pendidikan}}">
                                            </div>
                                            <small class="text-danger">{{$rekap->pendidikan_terakhir}}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Insentif Lainnya</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number"
                                                       id="insentif_lainnya"
                                                       value="{{$rekap->nominal_tunjangan_pendidikan}}">
                                            </div>
                                            <small class="text-danger">{{$rekap->pendidikan_terakhir}}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="display: none">
                                        <div class="form-group">
                                            <label>Tunjangan Beras</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number"
                                                       id="nominal_tunjangan_beras"
                                                       value="{{$rekap->total_harga_beras}}">
                                            </div>
                                            <small
                                                class="text-danger">{{'Diberikan dalam bentuk '.$rekap->total_beras.' '.$rekap->nama_beras}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-white">
                                        <h6 class="fs-17 font-weight-600 mb-0">Potongan</h6>
                                        <small id="nominal_total_potongan">{{$rekap->nominal_total_potongan}}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6" style="display: none">
                                        <div class="form-group">
                                            <label>Potongan Infaq</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number" id="potongan_infaq"
                                                       value="{{$rekap->nominal_infaq}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display: none">
                                        <div class="form-group">
                                            <label>Potongan Beras</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number" id="potongan_beras"
                                                       value="{{$rekap->total_harga_beras}}">
                                            </div>
                                            <small
                                                class="text-danger">{{$rekap->harga_perkilo.' x '.$rekap->total_beras.' - '.$rekap->nama_beras}}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Potongan Pinjaman</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number" id="potongan_koperasi"
                                                       value="{{$rekap->nominal_koperasi}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display: none">
                                        <div class="form-group">
                                            <label>Potongan Arisan</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number" id="potongan_arisan"
                                                       value="{{$rekap->nominal_arisan}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Potongan Paguyuban dan Cooper</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number"
                                                       value="{{$rekap->nominal_dplk}}" id="potongan_paguyuban_cooper">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Potongan BPJS Kesehatan</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number" id="potongan_bpjs"
                                                       value="{{$rekap->nominal_asuransi}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Potongan BPJS Ketengakerjaan</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number" id="potongan_bpjs"
                                                       value="{{$rekap->nominal_asuransi}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display: none">
                                        <div class="form-group">
                                            <label>Potongan Qurban</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number" id="potongan_qurban"
                                                       value="{{$rekap->nominal_qurban}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Potongan Lainnya</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input readonly type="text"
                                                       class="form-control number"
                                                       value="{{$rekap->nominal_lainnya}}" id="potongan_lainnya">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="float-right">
                    <a href="{{route('keuangan.penggajian.gaji_bulanan.index')}}"
                       class="btn btn-outline-danger">Kembali</a>
                    @if($rekap->is_repair)
                        <a href="{{route('keuangan.penggajian.gaji_bulanan.generate_ulang_gaji', ['id_karyawan' => $karyawan->id_karyawan])}}" class="btn btn-success">Generate Ulang Gaji</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/numeral/numeral.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/keuangan_page/penggajian/detail_gaji_bulanan.js')}}"></script>
@endpush
