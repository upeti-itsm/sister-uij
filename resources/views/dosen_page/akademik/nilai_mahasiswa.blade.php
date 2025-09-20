@extends('sidebar')
@section('head-css')
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/jquery-confirm/jquery-confirm.min.css')}}" rel="stylesheet">
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Akademik</li>
            <li class="breadcrumb-item active">Nilai Mahasiswa</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Nilai Mahasiswa</h1>
                <small>Halaman ini digunakan mengelola Nilai Mahasiswa</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <div class="col-md-12">
        {{-- Ringkasan Bobot Kriteria --}}
        @if(isset($mahasiswa[0]->bobot_per_kriteria))
            @php
                $daftar_kriteria = explode('#', $mahasiswa[0]->daftar_kriteria_text);
                $daftar_bobot = explode('#', $mahasiswa[0]->bobot_per_kriteria);
                $total_bobot = array_sum($daftar_bobot);
            @endphp

            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-info text-white py-2">
                    <h6 class="mb-0 font-weight-bold">
                        <i class="fas fa-balance-scale mr-2"></i>Bobot Kriteria Penilaian
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-2">
                            <thead class="thead-light">
                            <tr class="text-center">
                                @foreach($daftar_kriteria as $kriteria)
                                    <th class="align-middle">{{ trim($kriteria) }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="text-center">
                                @foreach($daftar_bobot as $bobot)
                                    <td>
                                        <span class="badge badge-pill badge-info px-3 py-1">{{ $bobot }}%</span>
                                    </td>
                                @endforeach
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-right font-weight-bold mt-2">
                        Total:
                        @if($total_bobot === 100)
                            <span class="text-success">{{ $total_bobot }}%</span>
                        @else
                            <span class="text-danger">{{ $total_bobot }}%</span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        {{-- Tabel Nilai Mahasiswa --}}
        <div class="card">
            <div class="card-header bg-primary text-white py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fs-17 font-weight-600 mb-0">Daftar Mahasiswa</h6>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover align-middle" id="table">
                        <thead class="thead-light sticky-top">
                        <tr class="text-center">
                            <th style="min-width:60px">No</th>
                            <th style="min-width:80px">NIM</th>
                            <th style="min-width:150px" class="text-left">Nama</th>

                            {{-- Kolom kriteria dinamis --}}
                            @foreach($daftar_kriteria as $kriteria)
                                <th class="text-center text-truncate" style="min-width:90px"
                                    title="{{ trim($kriteria) }}">
                                    {{ trim($kriteria) }}
                                </th>
                            @endforeach

                            <th class="text-center bg-light" style="min-width:90px">Akhir</th>
                            <th class="text-center bg-light" style="min-width:70px">Mutu</th>
                            <th class="text-center bg-light" style="min-width:70px">Huruf</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($mahasiswa as $item)
                            @php
                                $kriteria_ids = explode('#', $item->daftar_kriteria_id);
                                $nilai_per_kriteria = isset($item->nilai_per_kriteria) ? explode('#', $item->nilai_per_kriteria) : [];
                            @endphp
                            <tr>
                                <td class="text-center">{{ $item->nomor }}</td>
                                <td class="text-center">{{ $item->nim }}</td>
                                <td>{{ $item->nama_mahasiswa }}</td>

                                {{-- Input nilai per kriteria --}}
                                @foreach($kriteria_ids as $index => $kid)
                                    <td class="text-center">
                                        <input type="number"
                                               class="form-control form-control-sm text-center"
                                               name="nilai[{{ $item->nim }}][{{ $kid }}]"
                                               min="0" max="100"
                                               value="{{ $nilai_per_kriteria[$index] ?? '' }}"
                                               placeholder="0"
                                               style="max-width:60px; padding:2px 4px; font-size:12px;">
                                    </td>
                                @endforeach

                                {{-- Nilai Akhir --}}
                                <td class="text-center font-weight-bold bg-light">
                                    @if(isset($item->nilai_mutu) && $item->nilai_mutu < 2.0)
                                        <span class="text-danger">{{ $item->nilai_akhir ?? '-' }}</span>
                                    @else
                                        <span class="text-info">{{ $item->nilai_akhir ?? '-' }}</span>
                                    @endif
                                </td>

                                {{-- Nilai Mutu --}}
                                <td class="text-center bg-light">
                                    @if(isset($item->nilai_mutu) && $item->nilai_mutu < 2.0)
                                        <span class="badge badge-danger">{{ $item->nilai_mutu }}</span>
                                    @else
                                        <span
                                            class="badge badge-success">{{ $item->nilai_mutu ?? '-' }}</span>
                                    @endif
                                </td>

                                {{-- Nilai Huruf --}}
                                <td class="text-center bg-light">
                                    @if(isset($item->nilai_mutu) && $item->nilai_mutu < 2.0)
                                        <span
                                            class="badge badge-danger">{{ $item->nilai_huruf ?? '-' }}</span>
                                    @else
                                        <span
                                            class="badge badge-success">{{ $item->nilai_huruf ?? '-' }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
    <script src="{{asset('adminpage/assets/plugins/jquery-confirm/jquery-confirm.min.js')}}"></script>
    <script src="{{asset('adminpage/own-js/dosen_page/akademik/nilai_mahasiswa.js')}}"></script>
@endpush
