@extends('sidebar')
@section('head-css')
    <?PHP

    use Illuminate\Contracts\Session\Session;

    function getUserIP()
    {
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        \Illuminate\Support\Facades\Session::put('ip', $ip);
        return $ip;
    }

    $user_ip = getUserIP();
    ?>
    <link href="{{asset('adminpage/assets/plugins/datatables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <style>
        #reader {
            width: 100%;
            min-height: 300px;
        }

        #result {
            margin-top: 15px;
            font-weight: bold;
            color: green;
        }
    </style>
@endsection
@section('content-header')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item">Perkuliahan</li>
            <li class="breadcrumb-item active">Jadwal Kuliah</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success mr-3"><i class="fas fa-graduation-cap"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">Sinkronisasi Jadwal Kuliah</h1>
                <small>Halaman ini digunakan untuk mengelola sinkronisasi jadwal kuliah antara sipadu dan siakad</small>
            </div>
        </div>
    </div>
@endsection
@section('body-content')
    <input type="hidden" id="hak_akses"
           value="{{\Illuminate\Support\Facades\Session::get('modul')['Sinkronisasi Jadwal Mahasiswa dengan Siakad']}}">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">Daftar Jadwal Kuliah</h6>
                    </div>
                    <div class="text-right">
                        <div class="actions">
                            <button class="btn btn-danger-soft btn-sync-ulang-jadwal-kuliah mr-2"
                                    id="btn-sync-ulang-jadwal-kuliah"
                                    title="Lihat Jadwal Semester Terakhir"><i
                                    class="fas fa-cloud-download-alt"></i> Lihat Jadwal
                                Semester {{$tahun_akademik_aktif->nama_tahun_akademik}}
                            </button>
                            <input type="hidden" id="tahun_akademik_aktif"
                                   value="{{$tahun_akademik_aktif->tahun_akademik}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mt-3" id="progress-bar-syncron-ulang-jadwal-kuliah" style="display: none">
                        <button class="btn btn-primary mr-1 mb-2" type="button" disabled="">
                            <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"
                                  id="loading-progress-jadwal-kuliah"></span>
                            <span id="keterangan-progress-jadwal-kuliah">Mohon menunggu hingga proses sinkronisasi selesai ...</span>
                        </button>
                        <button class="btn btn-danger-soft mr-1 mb-2" id="btn-cancel-syncron-ulang-jadwal-kuliah"><i
                                class="fas fa-window-close mr-2"></i>Batal
                        </button>
                        <div class="progress progress-lg mb-3">
                            <div
                                class="progress-bar progress-bar-violet progress-bar-striped progress-bar-animated"
                                role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                style="width: 0" id="progress-bar-jadwal-kuliah">
                                <span id="progress-text-jadwal-kuliah"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3" id="log-syncron-ulang-jadwal-kuliah" style="display: none">
                        <div class="card bg-light">
                            <div class="card-header bg-info text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fs-17 font-weight-600 mb-0">Log Sinkronisasi</h6>
                                    </div>
                                    <div class="text-right">
                                        <div class="actions">
                                            <button class="btn btn-primary-soft mr-3 text-white"
                                                    id="btn-failed-log-jadwal-kuliah">
                                                Failed : 0
                                            </button>
                                            <button class="btn btn-primary-soft mr-3 text-white"
                                                    id="btn-inserted-log-jadwal-kuliah">
                                                Inserted : 0
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
                                            <input type="text" class="form-control" placeholder="Cari Nama Matakuliah"
                                                   id="cari-data-jadwal-kuliah">
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-block btn-primary" id="btn-cari-data-jadwal-kuliah">
                                                <i
                                                    class="fas fa-search mr-2"></i>Cari Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Tahun Akademik</label>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <select class="select2 form-control" id="tahun_akademik">
                                                @foreach($tahun_akademik AS $item)
                                                    <option
                                                        value="{{$item->tahun_akademik}}">{{$item->tahun_akademik}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-success btn-block mt-4" id="openModalBtn">Presensi Kuliah</button>
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
                                <th>SKS</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Presensi</th>
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
    <div class="modal modal-primary fade" id="modal-scanner" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-600" id="insupLabel">Scan QR Presensi</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="reader" width="600px" style="margin-bottom: 10px"></div>
                    <form action="{{route('mahasiswa.akademik.jadwal_kuliah.set_absensi')}}" method="POST"
                          id="form_absensi">
                        @csrf
                        <input type="hidden" name="id_rekap" id="id_rekap">
{{--                        <input type="hidden" name="ip" id="{{$user_ip}}">--}}
                        <div id="hasil" style="display: none">
                            <div class="form-group">
                                <label>Matakuliah</label>
                                <input type="text" readonly id="nama_matkul" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Perkuliahan</label>
                                <input type="text" readonly id="tgl" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Jam Perkuliahan</label>
                                <input type="text" readonly id="jam" class="form-control">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success btn-block" type="submit">Kirim Presensi</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
    <script
        src="{{asset('adminpage/own-js/mahasiswa_page/akademik/jadwal_mahasiswa.js')}}"></script>
    <script src="{{asset('adminpage/assets/plugins/html5-qrcode/html5-qrcode.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            // HTML5 QR-CODE:
            function onScanSuccess(decodedText, decodedResult) {
                var hasil = decodedText.split(";");
                console.log(hasil);
                // handle the scanned code as you like, for example:
                $("#id_rekap").val(hasil[0]);
                $("#hasil").show()
                $("#nama_matkul").val(hasil[1]);
                $("#tgl").val(hasil[2]);
                $("#jam").val(hasil[3]);
                $("#reader").hide();
                html5QrcodeScanner.clear()
                // $("#modal-scanner").modal('hide');
                // $("#form_absensi").submit();
            }

            function onScanFailure(error) {
                // handle scan failure, usually better to ignore and keep scanning.
                // for example:
                // console.warn(Code scan error = ${error});
            }

            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                {fps: 10, qrbox: {width: 250, height: 250}},
                /* verbose= */ false);
            $('#modal-scanner').on('shown.bs.modal', function () {
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            });

            $('#modal-scanner').on('hidden.bs.modal', function () {
                $("#hasil").hide()
                $("#reader").show();
                html5QrcodeScanner.clear()
            });

            $("#form_absensi").on("submit", function () {
                $.alert({
                    type: 'orange',
                    title: 'Proses Absensi',
                    columnClass: 'xlarge',
                    content: '<div class="d-flex justify-content-center">' +
                        '<button class="btn btn-primary" type="button" disabled>' +
                        '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' +
                        'Proses Absensi Perkuliahan' +
                        '</button>' +
                        '</div>',
                    buttons: {
                        OK: {
                            text: 'Selamat Menunggu',
                            action: function () {
                                return false;
                            }
                        }
                    }
                });
            });
        });
    </script>
@endpush
