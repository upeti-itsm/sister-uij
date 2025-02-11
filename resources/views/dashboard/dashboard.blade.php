@extends('sidebar')
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="typcn typcn-th-large"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Dashboard</h1>
                <small>Selamat Datang {{\Illuminate\Support\Facades\Session::get('user')->nama_lengkap}}</small>
            </div>
        </div>
    </div>
@endsection
@if(\Illuminate\Support\Facades\Session::get('peran')['aktif'] == 39)
    {{-- ADMIN AKADEMIK --}}
@section('head-css')
@endsection
@section('body-content')
    <div class="col-md-12" style="display: none">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Project status</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <a href="#" class="action-item"><i class="ti-reload"></i></a>
                            <div class="dropdown action-item" data-toggle="dropdown">
                                <a href="#" class="action-item"><i class="ti-more-alt"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#" class="dropdown-item">Refresh</a>
                                    <a href="#" class="dropdown-item">Manage Widgets</a>
                                    <a href="#" class="dropdown-item">Settings</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
            </div>
        </div>
    </div>
@endsection
@section('modal')
@endsection
@push('scripts')
@endpush

@elseif(\Illuminate\Support\Facades\Session::get('peran')['aktif'] == 41)
    {{-- MAHASISWA --}}
@section('head-css')
    <style>
        .chartdiv {
            width: 100%;
            height: 300px;
        }
    </style>
@endsection
@section('body-content')
    <div
        @if(sizeof($data["nilai_skripsi"]) > 0 && sizeof($data["jadwal_wisuda"]) > 0) @if($data["nilai_skripsi"][0]->nilai_huruf != "-") class="col-md-6"
        @else class="col-md-12" @endif @else class="col-md-12" @endif>
        <input type="hidden" id="nim" value="{{\Illuminate\Support\Facades\Session::get('user')->nim}}">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Indeks Prestasi</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <button class="btn btn-success-soft" style="display: none" type="button" disabled id="loading">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
                <div id="chartdiv" class="chartdiv"></div>
            </div>
        </div>
    </div>
    @if(sizeof($data["nilai_skripsi"]) > 0 && sizeof($data["jadwal_wisuda"]) > 0)
        @if($data["nilai_skripsi"][0]->nilai_huruf != "-")
            <div class="col-md-6">
                <div class="card-body text-center">
                    <div class="row justify-content-center">
                        <div class="greet-user col-12 col-xl-10">
                            <img src="{{asset('adminpage/assets/dist/img/happiness.svg')}}" alt="..."
                                 class="img-fluid  mb-2">
                            <h2 class="fs-23 font-weight-600 mb-2">
                                Selamat {{ucfirst(\Illuminate\Support\Facades\Session::get('user')->nama_lengkap)}},
                            </h2>
                            <p class="text-muted">
                                Anda bisa mendaftar wisuda {{$data['jadwal_wisuda'][0]->tgl_pelaksanaan_}} dengan nilai
                                skripsi
                                <b>{{$data["nilai_skripsi"][0]->nilai_huruf}}</b>
                                <br/>
                                Peserta Wisuda Saat ini {{$data["jadwal_wisuda"][0]->peserta}}, Sisa Kuota Wisuda <b
                                    class="text-danger">{{($data["jadwal_wisuda"][0]->kuota - $data["jadwal_wisuda"][0]->peserta)}}</b>
                            </p>
                            <a href="{{route('mahasiswa.akademik.pendaftaran_wisuda.index')}}" class="btn btn-success">
                                Daftar Wisuda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endsection
@section('modal')
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/amchart5/index.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/amchart5/xy.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/amchart5/themes/Animated.js')}}"></script>
    <script
        src="{{asset('adminpage/own-js/mahasiswa_page/dashboard.js')}}"></script>
@endpush
@elseif(\Illuminate\Support\Facades\Session::get('peran')['aktif'] == 45)
    {{-- REKTOR ITS --}}
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/fullcalendar/packages/core/main.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/fullcalendar/packages/daygrid/main.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/fullcalendar/packages/timegrid/main.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/fullcalendar/packages/list/main.min.css')}}" rel="stylesheet">
    <style>
        body .fc, .fc-title {
            font-size: .55em;
        }

        body .fc-title {
            font-size: .5em;
        }

        body .fc-event {
            padding: 2px;
            text-align: center;
            cursor: pointer;
        }

        .chartdiv {
            width: 100%;
            height: 300px;
        }
    </style>
@endsection
@section('body-content')
    <div class="col-md-3">
        <div class="d-flex flex-column p-3 mb-3 bg-white shadow-sm rounded">
            <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">Mhs. Aktif
                ({{$data['mahasiswa']->tahun}})
            </div>
            <div class="d-flex justify-content-between">
                <a
                    href="{{route('rektor.dashboard.detail_mahasiswa', ['status' => 1])}}">
                    <div>
                    <span class="badge badge-info fs-24 text-monospace mx-auto"><i
                            class="fas fa-users mr-2"></i>{{number_format($data['mahasiswa']->jml_aktif,0,',','.')}}</span>
                    </div>
                </a>
                <div class="text-success pl-3 pt-1" style="display: none">
                    <span class="text-monospace">{{$data['mahasiswa']->prosentase}}%</span>
                    <div class="small text-muted">dari total mahasiswa belum lulus</div>
                </div>
            </div>
            <div class="d-flex flex-column p-3 mt-3 bg-light shadow-sm rounded">
                <div class="row text-center">
                    <div class="col" title="Mahasiswa Lulus">
                        <a
                            href="{{route('rektor.dashboard.detail_mahasiswa', ['status' => 3])}}">
                            <i class="fas fa-graduation-cap text-success mb-2" style="font-size: 1.5rem"></i>
                            <div
                                class="text-monospace text-secondary">{{number_format($data['mahasiswa']->jml_lulus,0,',','.')}}
                            </div>
                        </a>
                    </div>
                    <div class="col" title="Mahasiswa Cuti">
                        <a
                            href="{{route('rektor.dashboard.detail_mahasiswa', ['status' => 4])}}">
                            <i class="fas fa-user-clock text-warning mb-2" style="font-size: 1.5rem"></i>
                            <div
                                class="text-monospace text-secondary">{{number_format($data['mahasiswa']->jml_cuti,0,',','.')}}
                            </div>
                        </a>
                    </div>
                    <div class="col" title="Mahasiswa Tidak Aktif">
                        <a
                            href="{{route('rektor.dashboard.detail_mahasiswa', ['status' => 2])}}">
                            <i class="fas fa-user-times mb-2 text-danger" style="font-size: 1.5rem"></i>
                            <div
                                class="text-monospace text-secondary">{{number_format($data['mahasiswa']->jml_tidak_aktif,0,',','.')}}
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white mb-0 mt-2 text-center">
                <div class="stats">
                    <i class="typcn typcn-eye text-warning mr-2"></i>
                    <a href="{{route('rektor.dashboard.detail_mahasiswa')}}" class="warning-link">Lihat Detail...</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="d-flex flex-column p-3 mb-3 bg-white shadow-sm rounded">
            <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">Mahasiswa Baru
                ({{$data['pmb']->tahun_seleksi_now}})
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{route('rektor.dashboard.detail_maba', ['filter' => 'x'])}}">
                    <div>
                    <span class="badge badge-success fs-26 text-monospace mx-auto"><i
                            class="fas fa-graduation-cap mr-2"></i>{{number_format($data['pmb']->total_mahasiswa_now,0,',','.')}}</span>
                    </div>
                </a>
                @if($data['pmb']->besar_trend > 0)
                    <div class="text-success pl-2 pt-1">
                        <i class="fas fa fa-long-arrow-alt-up"></i>
                        <span class="text-monospace">{{$data['pmb']->besar_trend}}%</span>
                        <div class="small text-muted">vs {{$data['pmb']->tahun_seleksi_old}}</div>
                    </div>
                @elseif($data['pmb']->besar_trend < 0)
                    <div class="text-danger pl-2 pt-1">
                        <i class="fas fa-long-arrow-alt-down"></i>
                        <span class="text-monospace">{{$data['pmb']->besar_trend}}%</span>
                        <div class="small text-muted">vs {{$data['pmb']->tahun_seleksi_old}}</div>
                    </div>
                @else
                    <div class="text-success pl-2 pt-1">
                        <i class="fas fa-lock"></i>
                        <span class="text-monospace">{{$data['pmb']->besar_trend}}%</span>
                        <div class="small text-muted">vs {{$data['pmb']->tahun_seleksi_old}}</div>
                    </div>
                @endif
            </div>
            <div class="d-flex flex-column p-3 mt-3 bg-light shadow-sm rounded">
                <a href="{{route('rektor.dashboard.detail_maba', ['filter' => '61101'])}}">
                    <div class="row text-center pb-3">
                        <div class="col" title="Mahasiswa Magister Manajemen">
                        <span class="badge badge-info text-white mb-2"
                              style="font-size: .8rem;">Magister Manajemen</span>
                            <div
                                class="text-monospace text-secondary">{{number_format($data['pmb']->n_mhs_s2_manajemen,0,',','.')}}</div>
                        </div>
                    </div>
                </a>
                <div class="row text-center">
                    <div class="col" title="Mahasiswa Prodi Manajemen">
                        <a href="{{route('rektor.dashboard.detail_maba', ['filter' => '61201'])}}">
                            <span class="badge badge-info text-white mb-2" style="font-size: .8rem">MNJ</span>
                            <div
                                class="text-monospace text-secondary">{{number_format($data['pmb']->n_mhs_s1_manajemen,0,',','.')}}</div>
                        </a>
                    </div>
                    <div class="col" title="Mahasiswa Prodi Rekayasa Perangkat Lunak">
                        <a href="{{route('rektor.dashboard.detail_maba', ['filter' => '00000'])}}">
                            <span class="badge badge-info text-white mb-2" style="font-size: .8rem">RPL</span>
                            <div
                                class="text-monospace text-secondary">{{number_format($data['pmb']->n_mhs_s1_rpl,0,',','.')}}</div>
                        </a>
                    </div>
                    <div class="col" title="Mahasiswa Prodi Sistem dan Teknologi Informasi">
                        <a href="{{route('rektor.dashboard.detail_maba', ['filter' => '00001'])}}">
                            <span class="badge badge-info text-white mb-2" style="font-size: .8rem">STI</span>
                            <div
                                class="text-monospace text-secondary">{{number_format($data['pmb']->n_mhs_s1_sti,0,',','.')}}</div>
                        </a>
                    </div>
                </div>
                <div class="row text-center pt-3">
                    <div class="col" title="Mahasiswa Prodi Akuntansi">
                        <a href="{{route('rektor.dashboard.detail_maba', ['filter' => '62201'])}}">
                            <span class="badge badge-info text-white mb-2" style="font-size: .8rem">AK</span>
                            <div
                                class="text-monospace text-secondary">{{number_format($data['pmb']->n_mhs_s1_akuntansi,0,',','.')}}</div>
                        </a>
                    </div>
                    <div class="col" title="Mahasiswa Prodi Ekonomi Pembangunan">
                        <a href="{{route('rektor.dashboard.detail_maba', ['filter' => '60201'])}}">
                            <span class="badge badge-info text-white mb-2" style="font-size: .8rem">EP</span>
                            <div
                                class="text-monospace text-secondary">{{number_format($data['pmb']->n_mhs_s1_ekonomi_pembangunan,0,',','.')}}</div>
                        </a>
                    </div>
                    <div class="col" title="Mahasiswa Prodi D3 Keuangan dan Perbankan">
                        <a href="{{route('rektor.dashboard.detail_maba', ['filter' => '61406'])}}">
                            <span class="badge badge-info text-white mb-2" style="font-size: .8rem">D3</span>
                            <div
                                class="text-monospace text-secondary">{{number_format($data['pmb']->n_mhs_d3_keu,0,',','.')}}</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white mb-0 mt-2 text-center">
                <div class="stats">
                    <i class="typcn typcn-eye text-warning mr-2"></i>
                    <a href="{{route('rektor.dashboard.detail_maba', ['filter' => 'x'])}}" class="warning-link">Lihat
                        Detail...</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="d-flex flex-column p-3 mb-3 bg-white shadow-sm rounded">
            <div class="header-pretitle text-muted fs-11 font-weight-bold text-uppercase mb-2">Absensi
                ({{$data['abs']->tanggal_now}}
                )
            </div>
            <div class="d-flex justify-content-between">
                <div>
                    <span class="badge badge-info fs-26 text-monospace mx-auto"><i
                            class="fas fa-user-check mr-2"></i>{{$data['abs']->n_krywn_hadir_now}}</span>
                </div>
                @if(trim($data['abs']->trend) == "Kenaikan")
                    <div class="text-success pl-3 pt-1">
                        <i class="fas fa fa-long-arrow-alt-up"></i>
                        <span class="text-monospace">{{$data['abs']->kenaikan}}</span>
                        <div class="small text-muted">vs Hari Sebelumnya</div>
                    </div>
                @else
                    <div class="text-danger pl-3 pt-1">
                        <i class="fas fa fa-long-arrow-alt-down"></i>
                        <span class="text-monospace">{{$data['abs']->kenaikan}}</span>
                        <div class="small text-muted">vs Hari Sebelumnya</div>
                    </div>
                @endif
            </div>
            <div class="d-flex flex-column p-3 mt-3 bg-light shadow-sm rounded">
                <div class="row text-center">
                    <div class="col" title="Early (06.00 s/d 06.59)">
                        <a href="{{route('rektor.dashboard.detail_karyawan', ['filter' => '5'])}}">
                            <i class="fas fa-user-plus text-success mb-2" style="font-size: 1.5rem"></i>
                            <div class="text-monospace text-secondary">{{$data['abs']->n_krywn_early}}</div>
                        </a>
                    </div>
                    <div class="col" title="Ontime (07.00 s/d 07.30)">
                        <a href="{{route('rektor.dashboard.detail_karyawan', ['filter' => '1'])}}">
                            <i class="fas fa-user-check text-primary mb-2" style="font-size: 1.5rem"></i>
                            <div class="text-monospace text-secondary">{{$data['abs']->n_krywn_ontime}}</div>
                        </a>
                    </div>
                    <div class="col" title="Late (07.31 s/d 08.00)">
                        <a href="{{route('rektor.dashboard.detail_karyawan', ['filter' => '2'])}}">
                            <i class="fas fa-user-clock mb-2 text-warning" style="font-size: 1.5rem"></i>
                            <div class="text-monospace text-secondary">{{$data['abs']->n_krywn_terlambat}}</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column p-3 mt-3 bg-light shadow-sm rounded">
                <div class="row text-center">
                    <div class="col-3" title="Perjalanan Dinas">
                        <a href="{{route('rektor.dashboard.detail_karyawan', ['filter' => '7'])}}">
                            <i class="fas fa-car text-info mb-2" style="font-size: 1rem"></i>
                            <div class="text-monospace text-secondary">{{$data['abs']->n_krywn_dinas}}</div>
                        </a>
                    </div>
                    <div class="col-3" title="Sakit">
                        <a href="{{route('rektor.dashboard.detail_karyawan', ['filter' => '6'])}}">
                            <i class="fas fa-user-nurse text-info mb-2" style="font-size: 1rem"></i>
                            <div class="text-monospace text-secondary">{{$data['abs']->n_krywn_sakit}}</div>
                        </a>
                    </div>
                    <div class="col-3" title="Ijin">
                        <a href="{{route('rektor.dashboard.detail_karyawan', ['filter' => '4'])}}">
                            <i class="fas fa-user-edit mb-2 text-info" style="font-size: 1rem"></i>
                            <div class="text-monospace text-secondary">{{$data['abs']->n_krywn_izin}}</div>
                        </a>
                    </div>
                    <div class="col-3" title="Alpha">
                        <a href="{{route('rektor.dashboard.detail_karyawan', ['filter' => '0'])}}">
                            <i class="fas fa-user-times mb-2 text-danger" style="font-size: 1rem"></i>
                            <div class="text-monospace text-secondary">{{$data['abs']->n_krywn_alpha}}</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white mb-0 mt-2 text-center">
                <div class="stats">
                    <i class="typcn typcn-eye text-warning mr-2"></i>
                    <a href="{{route('rektor.dashboard.detail_karyawan')}}" class="warning-link">Lihat Detail...</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <input type="hidden" id="tgl" value="{{$data['now']}}">
        <div class="card">
            <div class="card-body">
                <div id='calendar'></div>
            </div>
            <div class="card-footer bg-white mb-0 mt-2 text-left" id="list_ultah" style="font-size: small">
            </div>
        </div>
    </div>
@endsection
@section('modal')
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/fullcalendar/packages/core/main.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/fullcalendar/packages/interaction/main.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/fullcalendar/packages/daygrid/main.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/fullcalendar/packages/timegrid/main.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/fullcalendar/packages/list/main.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/amchart5/index.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/amchart5/percent.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/amchart5/themes/Animated.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/moment/moment.min.js')}}"></script>
    <script
        src="{{asset('adminpage/own-js/rektor_page/dashboard.js')}}"></script>
@endpush
@endif
