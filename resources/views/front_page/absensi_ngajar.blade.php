<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistem informasi terpadu; Siakad Mandala">
    <meta name="author" content="Bdtask">
    <title>SIPADU | Mandala</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('image/logo-mandala.png')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--Global Styles(used by all pages)-->
    <link href="{{asset('adminpage/assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/metisMenu/metisMenu.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/fontawesome/css/all.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/typicons/src/typicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/themify-icons/themify-icons.min.css')}}" rel="stylesheet">
    <!--Third party Styles(used by this page)-->
    <link href="{{asset('adminpage/assets/plugins/jquery-confirm/jquery-confirm.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/select2/css/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <!--Start Your Custom Style Now-->
    <link href="{{asset('adminpage/assets/dist/css/style.css')}}" rel="stylesheet">
</head>
<body class="fixed">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="preloader">
            <div class="spinner-layer pl-green">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <p>Please wait...</p>
    </div>
</div>
<!-- #END# Page Loader -->
<div class="wrapper">
    <!-- Sidebar  -->
    <nav class="sidebar sidebar-bunker">
        <div class="sidebar-header">
            <!--<a href="index.html" class="logo"><span>bd</span>task</a>-->
            <a href="{{route('dashboard.dashboard')}}" class="logo"><img
                    src="{{asset('image/logo-2-sipadu-sidebar.png')}}"
                    alt=""></a>
        </div><!--/.sidebar header-->
        <div class="profile-element d-flex align-items-center flex-shrink-0 bg-dark">
            <div class="avatar online">
                <img src="{{asset('adminpage/assets/dist/img/avatar-1.jpg')}}"
                     class="img-fluid rounded-circle"
                     alt="" onerror="this.src='{{asset('adminpage/assets/dist/img/avatar-1.jpg')}}'">
            </div>
            <div class="profile-text">
                <small class="m-0 text-white">{{$dosen->nama_lengkap}}</small><br/>
                <span>{{$dosen->status_dosen}}</span>
            </div>
        </div><!--/.profile element-->
        <div class="sidebar-body">
            <nav class="sidebar-nav">
                <ul class="metismenu">
                    <li class="nav-label">Main Menu</li>
                    <li class="mm-active"><a href="#"><i class="typcn typcn-home mr-2"></i>Absensi Ajar Dosen</a></li>
                </ul>
            </nav>
        </div><!-- sidebar-body -->
    </nav>
    <!-- Page Content  -->
    <div class="content-wrapper">
        <div class="main-content">
            <nav class="navbar-custom-menu navbar navbar-expand-lg m-0">
                <div class="sidebar-toggle-icon mr-3" id="sidebarCollapse">
                    sidebar toggle<span></span>
                </div>
                <img src="{{asset('image/mdl.png')}}" alt="" class="img-fluid" style="max-height: 40px">
                <!--/.sidebar toggle icon-->
                <div class="d-flex flex-grow-1">
                    <div class="nav-clock ml-auto">
                        <div class="time">
                            <span class="time-hours"></span>
                            <span class="time-min"></span>
                            <span class="time-sec"></span>
                        </div>
                    </div>
                </div>
            </nav><!--/.navbar-->
            <!--Content Header (Page header)-->
            <div class="content-header row align-items-center m-0">
                <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
                    <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
                        <li class="breadcrumb-item active">Absensi Ajar Dosen</li>
                    </ol>
                </nav>
                <div class="col-sm-8 header-title p-0">
                    <div class="media">
                        <div class="header-icon text-success mr-3"><i class="typcn typcn-th-large"></i></div>
                        <div class="media-body">
                            <h1 class="font-weight-bold">Absensi Ajar Dosen</h1>
                            <small>Selamat Datang {{$dosen->nama_lengkap}}</small>
                        </div>
                    </div>
                </div>
            </div>
            <!--/.Content Header (Page header)-->
            <div class="body-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <form action="{{route('frontpage.store_absensi_ngajar')}}" method="POST"
                                      enctype="multipart/form-data" id="form-absensi">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label>Nama Dosen</label><br/>
                                                <strong>{{$dosen->nama_lengkap}}</strong>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Status Kepegawaian</label><br/>
                                                <strong>{{$dosen->status_dosen}}</strong>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Email</label><br/>
                                                <strong>{{$dosen->email}}</strong>
                                            </div>
                                            <div class="col-md-12">
                                                <hr/>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="exampleInputEmail1"
                                                       class="font-weight-600">Matakuliah <span
                                                        class="text-danger">*</span></label>
                                                <select id="id_matakuliah" class="form-control select2"
                                                        name="id_matakuliah" required>
                                                    @foreach($course AS $item)
                                                        <option
                                                            value="{{$item->jadwal_id}}"
                                                            @if(old('id_matakuliah') == $item->jadwal_id) selected @endif>{{$item->fullname}}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="nama_mata_kuliah"
                                                       value="{{old('nama_mata_kuliah') ? old('nama_mata_kuliah') : $course[0]->fullname}}"
                                                       id="nama_mata_kuliah">
                                                <input type="hidden" name="username"
                                                       value="{{ old('username') ? old('username') : $dosen->username}}"
                                                       id="nama_mata_kuliah">
                                                <small id="" class="form-text text-muted">Silahkan pilih mata
                                                    kuliah
                                                    dan kelas sesuai dengan elearning</small>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label>Jenis Kelas <span class="text-danger">*</span></label><br/>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="customRadio1" name="jenis_kelas"
                                                           class="custom-control-input" value="reg_p"
                                                           @if(old('jenis_kelas') != 'reg_m') checked @endif>
                                                    <label class="custom-control-label" for="customRadio1">Reguler
                                                        Pagi</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="customRadio2"
                                                           @if(old('jenis_kelas') == 'reg_m') checked
                                                           @endif name="jenis_kelas"
                                                           class="custom-control-input" value="reg_m">
                                                    <label class="custom-control-label" for="customRadio2">Reguler
                                                        Malam</label>
                                                </div>
                                            </div>
                                            <div class="col-md-5 mb-3">
                                                <label>Pertemuan Ke <span class="text-danger">*</span></label>
                                                <select class="form-control select2" id="pertemuan_ke"
                                                        name="pertemuan_ke" required>
                                                    @for($i = 1; $i <= 15; $i++)
                                                        <option value="{{$i}}"
                                                                @if(old('pertemuan_ke') == strval($i)) selected @endif>
                                                            Pertemuan
                                                            Ke-{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="exampleInputEmail1" class="font-weight-600">Tanggal
                                                    Pelaksanaan <span class="text-danger">*</span></label>
                                                <input type="text" readonly id="tgl_pelaksanaan" class="form-control"
                                                       placeholder="Pilih Tanggal Pelaksanaan">
                                                <input type="hidden" id="tgl_pelaksanaan_" required class="form-control"
                                                       placeholder="Pilih Tanggal Pelaksanaan"
                                                       value="{{old('tgl_pelaksanaan') ? old('tgl_pelaksanaan') : \Illuminate\Support\Carbon::now('Asia/Jakarta')->format('Y-m-d')}}"
                                                       name="tgl_pelaksanaan">
                                                <small id="emailHelp" class="form-text text-muted">Masukkan tanggal
                                                    pelaksanaan kuliah</small>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Jam Ke <span class="text-danger">*</span></label>
                                                <select class="form-control select2" id="jam_ke" required name="jam_ke">
                                                    @if(old('jenis_kelas') == 'reg_m')
                                                        <option value="4" @if(old('jam_ke') == "4") selected @endif>Jam
                                                            Ke-1 (18.00 WIB - 20.00 WIB)
                                                        </option>
                                                        <option value="5" @if(old('jam_ke') == "5") selected @endif>Jam
                                                            Ke-2 (20.00 WIB - 22.00 WIB)
                                                        </option>
                                                    @else
                                                        <option value="1" @if(old('jam_ke') == "1") selected @endif>Jam
                                                            Ke-1 (07.00 WIB - 09.30 WIB)
                                                        </option>
                                                        <option value="2" @if(old('jam_ke') == "2") selected @endif>Jam
                                                            Ke-2 (09.30 WIB - 12.00 WIB)
                                                        </option>
                                                        <option value="3" @if(old('jam_ke') == "3") selected @endif>Jam
                                                            Ke-3 (13.00 WIB - 15.30 WIB)
                                                        </option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label>Materi Pembelajaran <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="materi_pembelajaran"
                                                       id="materi_pembelajaran" required
                                                       placeholder="Masukkan Judul Materi Pembelajaran"
                                                       value="{{old('materi_pembelajaran')}}">
                                            </div>
{{--                                            <div class="col-md-6 mb-3">--}}
{{--                                                <label>Materi/Bukti Ajar <span class="text-danger">*</span></label>--}}
{{--                                                <div class="custom-file">--}}
{{--                                                    <input type="file" class="custom-file-input" required--}}
{{--                                                           id="bukti_ajar" name="bukti_ajar">--}}
{{--                                                    <label class="custom-file-label" for="customFile">Pilih--}}
{{--                                                        Berkas</label>--}}
{{--                                                </div>--}}
{{--                                                <small id="" class="form-text text-muted">Upload Materi/Bukti Ajar lain--}}
{{--                                                    yang digunakan</small>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-3 mb-3">--}}
{{--                                                <label for="exampleInputEmail1" class="font-weight-600">Mahasiswa--}}
{{--                                                    Hadir <span class="text-danger">*</span></label>--}}
{{--                                                <input type="number" required id="jml_mahasiswa_hadir"--}}
{{--                                                       name="jml_mahasiswa_hadir"--}}
{{--                                                       class="form-control"--}}
{{--                                                       placeholder="Jumlah Mahasiswa"--}}
{{--                                                       value="{{old('jml_mahasiswa_hadir')}}">--}}
{{--                                                <small id="" class="form-text text-muted">Masukkan jumlah Mahasiswa yang--}}
{{--                                                    hadir</small>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-3 mb-3">--}}
{{--                                                <label for="exampleInputEmail1" class="font-weight-600">Mahasiswa--}}
{{--                                                    Tidak Hadir <span class="text-danger">*</span></label>--}}
{{--                                                <input type="number" id="jml_mahasiswa_alpha" name="jml_mahasiswa_alpha"--}}
{{--                                                       class="form-control"--}}
{{--                                                       placeholder="Jumlah Mahasiswa" required--}}
{{--                                                       value="{{old('jml_mahasiswa_alpha')}}">--}}
{{--                                                <small id="" class="form-text text-muted">Masukkan jumlah Mahasiswa yang--}}
{{--                                                    tidak hadir</small>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-6 mb-3">--}}
{{--                                                <label>Bukti Absensi Mahasiswa <span--}}
{{--                                                        class="text-danger">*</span></label>--}}
{{--                                                <div class="custom-file">--}}
{{--                                                    <input type="file" required class="custom-file-input"--}}
{{--                                                           id="bukti_absensi" name="bukti_absensi">--}}
{{--                                                    <label class="custom-file-label" for="customFile">Pilih--}}
{{--                                                        Berkas</label>--}}
{{--                                                </div>--}}
{{--                                                <small id="" class="form-text text-muted">Upload Tangkapan Layar/Hasil--}}
{{--                                                    export Attendance di elearning</small>--}}
{{--                                            </div>--}}
                                            <div class="col-md-12">
                                                <label>Alasan Terlambar Absen</label>
                                                <textarea class="form-control" rows="4" name="keterangan"
                                                          placeholder="Masukkan keterangan jika terlambat">{{old('keterangan') ? old('keterangan') : ""}}</textarea>
                                                <small class="form-text text-muted">Alasan jika mengisi tidak pada
                                                    jadwal mengajar <b class="text-danger">(Kosongkan jika anda
                                                        mengisinya sesuai dengan jadwal mengajar anda)</b></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix">
                                        <button type="submit" class="btn btn-success float-right" id="btnSubmit">Kirim
                                            Absensi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--/.body content-->
        </div><!--/.main content-->
        <footer class="footer-content">
            <div class="footer-text d-flex align-items-center justify-content-between">
                <div class="copy">© 2021 UPETI Mandala</div>
            </div>
        </footer><!--/.footer content-->
        <div class="overlay"></div>
    </div><!--/.wrapper-->
</div>
<!--Global script(used by all pages)-->
<script src="{{asset('adminpage/assets/plugins/jQuery/jquery-3.4.1.min.js')}}"></script>
<script src="{{asset('adminpage/assets/dist/js/popper.min.js')}}"></script>
<script src="{{asset('adminpage/assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('adminpage/assets/plugins/metisMenu/metisMenu.min.js')}}"></script>
<script src="{{asset('adminpage/assets/plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('adminpage/assets/plugins/jquery-confirm/jquery-confirm.min.js')}}"></script>

<!-- Third Party Scripts(used by this page)-->
<script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
<script src="{{asset('adminpage/assets/plugins/select2/js/select2.min.js')}}"></script>
<script src="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('adminpage/assets/plugins/datepicker/bootstrap-datepicker.id.min.js')}}"></script>
<script src="{{asset('adminpage/assets/plugins/moment/moment.min.js')}}"></script>
<!--Page Scripts(used by all page)-->
<script>
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#btn-logout").click(function () {
        $("#form.logout").submit();
    });
</script>
<script src="{{asset('adminpage/assets/dist/js/sidebar.js')}}"></script>
<script>
    $(document).ready(function () {
        var tanggal_pelaksanaan = $("#tgl_pelaksanaan").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            startDate: moment('01/07/2021', 'DD/MM/YYYY').format('D/M/YYYY'),
            endDate: moment('30/06/2022', 'DD/MM/YYYY').format('D/M/YYYY'),
            orientation: 'bottom'
        }).datepicker('setDate', moment($("#tgl_pelaksanaan_").val(), 'YYYY-MM-DD').format('D/M/YYYY')).on('changeDate', function (e) {
            $("#tgl_pelaksanaan_").val(moment(e.date).format('YYYY-MM-DD'));
        });

        $(".select2").select2();
        $(".custom-file-input").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        $("#form-absensi").on("submit", function () {
            $.alert({
                type: 'orange',
                title: 'Proses Absensi',
                columnClass: 'xlarge',
                content: '<div class="d-flex justify-content-center">' +
                    '<button class="btn btn-primary" type="button" disabled>' +
                    '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' +
                    'Proses Absensi Mengajar' +
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

        $("#id_matakuliah").change(function () {
            $("#nama_mata_kuliah").val(this.options[this.selectedIndex].text);
        });

        $('input[type=radio][name=jenis_kelas]').change(function () {
            $("#jam_ke").empty();
            if (this.value === 'reg_p') {
                // option pertemuan
                $("#pertemuan_ke").append($("<option/>", {"value": "14", text: "Pertemuan Ke-14"}));
                $("#pertemuan_ke").append($("<option/>", {"value": "15", text: "Pertemuan Ke-15"}));
                // option jam
                $("#jam_ke").append($("<option/>", {"value": "1", text: "Jam Ke-1 (07.00 WIB - 09.30 WIB)"}));
                $("#jam_ke").append($("<option/>", {"value": "2", text: "Jam Ke-2 (09.30 WIB - 12.00 WIB)"}));
                $("#jam_ke").append($("<option/>", {"value": "3", text: "Jam Ke-3 (13.00 WIB - 15.30 WIB)"}));
            } else if (this.value === 'reg_m') {
                // option pertemuan
                $("#pertemuan_ke").find('option[value="14"]').remove();
                $("#pertemuan_ke").find('option[value="15"]').remove();
                // option jam
                $("#jam_ke").append($("<option/>", {"value": "4", text: "Jam Ke-1 (18.00 WIB - 20.00 WIB)"}));
                $("#jam_ke").append($("<option/>", {"value": "5", text: "Jam Ke-2 (20.00 WIB - 22.00 WIB)"}));
            }
        });
        @if ($errors->any())
            var html = "<ul>";
            @foreach($errors->all() as $error)
                html = html + "<li>{{$error}}</li>";
            @endforeach
        $.alert({
            title: 'Informasi',
            type: 'red',
            columnClass: 'xlarge',
            content: html,
        });
        @endif
        @if(\Illuminate\Support\Facades\Session::get('failed_message'))
        $.alert({
            title: 'Informasi',
            type: 'red',
            columnClass: 'xlarge',
            content: '{{\Illuminate\Support\Facades\Session::get('failed_message')}}'
        });
        @endif

        @if(\Illuminate\Support\Facades\Session::get('success_message'))
        $.alert({
            title: 'Informasi',
            type: 'green',
            columnClass: 'xlarge',
            content: '{{\Illuminate\Support\Facades\Session::get('success_message')}}'
        });
        @endif
        @if(\Illuminate\Support\Facades\Session::get('info_message'))
        $.alert({
            title: 'Informasi',
            type: 'blue',
            columnClass: 'xlarge',
            content: '{{\Illuminate\Support\Facades\Session::get('info_message')}}'
        });
        @endif
    });
</script>
</body>
</html>
