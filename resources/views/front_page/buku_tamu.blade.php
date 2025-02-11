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
                <small class="m-0 text-white">WISUDA</small><br/>
                <span>Terima Tamu</span>
            </div>
        </div><!--/.profile element-->
        <div class="sidebar-body">
            <nav class="sidebar-nav">
                <ul class="metismenu">
                    <li class="nav-label">Main Menu</li>
                    <li class="mm-active"><a href="#"><i class="typcn typcn-home mr-2"></i>Buku Tamu</a></li>
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
                        <li class="breadcrumb-item active">Buku Tamu</li>
                    </ol>
                </nav>
                <div class="col-sm-8 header-title p-0">
                    <div class="media">
                        <div class="header-icon text-success mr-3"><i class="typcn typcn-th-large"></i></div>
                        <div class="media-body">
                            <h1 class="font-weight-bold">Buku Tamu</h1>
                            <small>Halaman ini digunakan untuk mengisi daftar hadir Tamu Wisuda</small>
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
                                <button class="btn btn-primary" id="loading" type="button" disabled
                                        style="display: none">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                          aria-hidden="true"></span>
                                    Loading...
                                </button>
                                <form action="{{route('frontpage.wisuda.insert_tamu')}}" method="POST"
                                      enctype="multipart/form-data" id="form-tamu">
                                    @csrf
                                    <div class="form-group">
                                        <div id="reader" width="600px" style="margin-bottom: 10px"></div>
                                        <input type="text" class="form-control" placeholder="Masukkan ID Tamu"
                                               id="barcode" name="barcode" value="">
{{--                                        <label class="font-weight-bold mt-3">Manual Input</label>--}}
{{--                                        <input type="text" class="form-control" placeholder="Masukkan Kode Undangan"--}}
{{--                                               id="barcode_manual" name="manual_barcode" value="">--}}
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-block btn-success">Submit Attendance</button>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Pencarian</label>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control"
                                                           placeholder="Cari Nama Undangan"
                                                           id="cari-data">
                                                </div>
                                                <div class="col-md-4">
                                                    <button class="btn btn-block btn-primary" id="btn-cari-data">
                                                        <i
                                                            class="fas fa-search mr-2"></i>Cari Data
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="font-weight-bold">&nbsp;</label><br/>
                                            <button class="btn btn-danger float-right" style="display: none"><i
                                                    class="fas fa-file-pdf mr-2"></i>Export PDF
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover"
                                                   id="table">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">Nomor</th>
                                                    <th>NIM</th>
                                                    <th>Nama</th>
                                                    <th>Jenis Tamu</th>
                                                    <th>Waktu Hadir</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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
<script src="{{asset('adminpage/assets/plugins/html5-qrcode/html5-qrcode.min.js')}}"></script>

<!-- Third Party Scripts(used by this page)-->
<script src="{{asset('adminpage/assets/plugins/datatables/datatables.min.js')}}"></script>
<script src="{{asset('adminpage/own-js/front_page/buku_tamu.js')}}"></script>
<!--Page Scripts(used by all page)-->
<script>
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script src="{{asset('adminpage/assets/dist/js/sidebar.js')}}"></script>
<script>
    $(document).ready(function () {
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
            columnClass: 'medium',
            content: '{{\Illuminate\Support\Facades\Session::get('failed_message')}}',
            buttons: {
                confirm: {
                    text: 'OK',
                    btnClass: 'btn-green',
                    keys: ['enter']
                }
            }
        });
        @endif

        @if(\Illuminate\Support\Facades\Session::get('success_message'))
        $.alert({
            title: 'Informasi',
            type: 'green',
            columnClass: 'medium',
            content: '{{\Illuminate\Support\Facades\Session::get('success_message')}}',
            buttons: {
                confirm: {
                    text: 'OK',
                    btnClass: 'btn-green',
                    keys: ['enter']
                }
            }
        });
        @endif
        @if(\Illuminate\Support\Facades\Session::get('info_message'))
        $.alert({
            title: 'Informasi',
            type: 'blue',
            columnClass: 'medium',
            content: '{{\Illuminate\Support\Facades\Session::get('info_message')}}'
        });
        @endif

        // HTML5 QR-CODE:
        function onScanSuccess(decodedText, decodedResult) {
            // handle the scanned code as you like, for example:
            $("#barcode").val(decodedText);
            $("#form-tamu").submit();
        }

        function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning.
            // for example:
            // console.warn(`Code scan error = ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            {fps: 10, qrbox: {width: 250, height: 250}},
            /* verbose= */ false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    });
</script>
</body>
</html>
