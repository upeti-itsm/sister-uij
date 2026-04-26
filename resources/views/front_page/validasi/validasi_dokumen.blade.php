<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistem informasi terpadu; Siakad Mandala">
    <meta name="author" content="Bdtask">
    <title>SIPADU | Mandala &mdash; Validasi Dokumen Digital</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('image/logo-mandala.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--Global Styles(used by all pages)-->
    <link href="{{ asset('adminpage/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/metisMenu/metisMenu.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/typicons/src/typicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/themify-icons/themify-icons.min.css') }}" rel="stylesheet">
    <!--Third party Styles(used by this page)-->
    <link href="{{ asset('adminpage/assets/plugins/jquery-confirm/jquery-confirm.min.css') }}" rel="stylesheet">
    <!--Start Your Custom Style Now-->
    <link href="{{ asset('adminpage/assets/dist/css/style.css') }}" rel="stylesheet">

    <style>
        .qr-scanner-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
            border: 1px solid #e0e0e0;
        }

        .verification-steps {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin: 30px 0;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .step-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #28a745;
            transition: all 0.3s ease;
        }

        .step-item:hover {
            background-color: #e9ecef;
            transform: translateX(5px);
        }

        .step-number {
            width: 45px;
            height: 45px;
            background-color: #28a745;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.3rem;
            flex-shrink: 0;
            margin-right: 15px;
        }

        .step-text {
            font-size: 1rem;
            color: #495057;
            font-weight: 500;
            text-align: left;
        }

        .scan-btn {
            background-color: #28a745;
            border: none;
            padding: 12px 35px;
            border-radius: 4px;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .scan-btn:hover {
            background-color: #218838;
            color: white;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
            transform: translateY(-2px);
        }

        .btn-secondary-custom {
            background-color: #6c757d;
            border: none;
            padding: 12px 35px;
            border-radius: 4px;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .btn-secondary-custom:hover {
            background-color: #5a6268;
            color: white;
        }

        #reader {
            border: 2px solid #28a745;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 25px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .scanner-title {
            color: #28a745;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .scanner-subtitle {
            color: #6c757d;
            font-size: 1rem;
            margin-bottom: 25px;
        }

        .instruction-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 20px;
        }

        .loading-scan {
            display: none;
            margin-top: 20px;
        }

        .loading-scan.active {
            display: block;
        }

        .loading-text {
            color: #28a745;
            font-weight: 600;
            margin-top: 15px;
        }

        .spinner-border-custom {
            width: 3rem;
            height: 3rem;
            border-width: 0.3em;
            color: #28a745;
        }

        /* Floating Button Styles - Minimal CSS */
        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
            border: none;
        }

        .floating-btn:hover {
            background-color: #0056b3;
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(0, 123, 255, 0.6);
        }
    </style>
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
                <a href="#" class="logo">
                    <img src="{{ asset('image/logo-2-sipadu-sidebar.png') }}" style="width: 100%; height: 100%"
                        alt="">
                </a>
            </div><!--/.sidebar header-->
            <div class="profile-element d-flex align-items-center flex-shrink-0 bg-dark">
                <div class="avatar online">
                    <img src="{{ asset('adminpage/assets/dist/img/avatar-1.jpg') }}" class="img-fluid rounded-circle"
                        alt="" onerror="this.src='{{ asset('adminpage/assets/dist/img/avatar-1.jpg') }}'">
                </div>
                <div class="profile-text">
                    <small class="m-0 text-white">
                        Welcome to SIPADU
                    </small>
                    <br />
                    <span>
                        Validasi Dokumen Digital
                    </span>
                </div>
            </div><!--/.profile element-->
            <div class="sidebar-body">
                <nav class="sidebar-nav">
                    <ul class="metismenu">
                        <li class="nav-label">Menu Utama</li>
                        <li class="mm-active">
                            <a href="#">
                                <i class="typcn typcn-home mr-2"></i>Validasi Dokumen Digital
                            </a>
                        </li>
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
                    <img src="{{ asset('image/mdl.png') }}" alt="" class="img-fluid" style="max-height: 40px">
                    <!--/.  sidebar toggle icon-->
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
                    <div class="col-sm-8 header-title p-0">
                        <div class="media">
                            <div class="header-icon text-success mr-3"><i class="typcn typcn-th-large"></i></div>
                            <div class="media-body">
                                <h1 class="font-weight-bold">Validasi Dokumen Digital</h1>
                                <small>
                                    Selamat datang di halaman validasi dokumen digital SIPADU. Anda dapat
                                    memverifikasi keaslian dokumen digital yang diterbitkan oleh institusi
                                    kami.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/.Content Header (Page header)-->
                <div class="body-content">
                    <!-- QR Scanner Section -->
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <div class="qr-scanner-section">
                                <div class="text-center">
                                    <div class="instruction-icon">
                                        <i class="fas fa-qrcode"></i>
                                    </div>
                                    <h3 class="scanner-title">Scan QR Code Dokumen</h3>
                                    <p class="scanner-subtitle">
                                        Ikuti langkah-langkah di bawah ini untuk memvalidasi dokumen Anda
                                    </p>
                                </div>

                                <!-- Verification Steps (Vertical) -->
                                <div class="verification-steps">
                                    <div class="step-item">
                                        <div class="step-number">1</div>
                                        <div class="step-text">Klik tombol "Mulai Scan" di bawah</div>
                                    </div>
                                    <div class="step-item">
                                        <div class="step-number">2</div>
                                        <div class="step-text">Izinkan akses kamera pada browser Anda</div>
                                    </div>
                                    <div class="step-item">
                                        <div class="step-number">3</div>
                                        <div class="step-text">Arahkan kamera ke QR Code pada dokumen</div>
                                    </div>
                                    <div class="step-item">
                                        <div class="step-number">4</div>
                                        <div class="step-text">Tunggu proses scanning selesai</div>
                                    </div>
                                </div>

                                <!-- Scan Buttons -->
                                <div class="text-center mt-4">
                                    <button class="btn scan-btn" id="startScanBtn">
                                        <i class="fas fa-camera mr-2"></i>
                                        Mulai Scan QR Code
                                    </button>
                                    <button class="btn btn-secondary-custom ml-2" id="stopScanBtn"
                                        style="display: none;">
                                        <i class="fas fa-stop mr-2"></i>
                                        Berhenti
                                    </button>
                                </div>

                                <!-- QR Reader -->
                                <div id="reader" style="display: none;"></div>

                                <!-- Loading Indicator -->
                                <div class="loading-scan text-center" id="loadingScan">
                                    <div class="spinner-border spinner-border-custom" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="loading-text">
                                        <i class="fas fa-search mr-2"></i>
                                        Memproses data validasi...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Section -->
                    <div class="row mt-4">
                        <div class="col-lg-8 offset-lg-2">
                            <div class="alert alert-info">
                                <h5 class="alert-heading">
                                    <i class="fas fa-info-circle mr-2"></i>Informasi Penting
                                </h5>
                                <hr>
                                <ul class="mb-0">
                                    <li>Pastikan QR Code pada dokumen terlihat jelas dan tidak buram</li>
                                    <li>Gunakan pencahayaan yang cukup saat melakukan scan</li>
                                    <li>Browser akan meminta izin akses kamera, klik "Izinkan" atau "Allow"</li>
                                    <li>Setelah berhasil scan, Anda akan diarahkan ke halaman hasil validasi</li>
                                    <li><strong>Alternatif:</strong> Jika tidak bisa scan, klik tombol biru di pojok
                                        kanan bawah untuk input kode dokumen manual</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div><!--/.body content-->
            </div><!--/.main content-->
            <footer class="footer-content">
                <div class="footer-text d-flex align-items-center justify-content-between">
                    <div class="copy">
                        &copy; {{ date('Y') }} UPETI Mandala. All Rights Reserved.
                    </div>
                </div>
            </footer><!--/.footer content-->
            <div class="overlay"></div>
        </div><!--/.wrapper-->
    </div>

    <!-- Floating Button for Manual Input -->
    <button class="floating-btn" id="floatingBtn" data-toggle="modal" data-target="#inputKodeModal"
        title="Input Kode Dokumen">
        <i class="fas fa-keyboard"></i>
    </button>

    <!-- Modal Input Kode Dokumen -->
    <div class="modal fade" id="inputKodeModal" tabindex="-1" role="dialog" aria-labelledby="inputKodeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="inputKodeModalLabel">
                        <i class="fas fa-keyboard mr-2"></i>Input Kode Dokumen
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formInputKode" onsubmit="return false;">
                        <div class="form-group">
                            <label for="kodeDokumen" class="font-weight-bold">
                                <i class="fas fa-file-alt mr-2 text-primary"></i>Kode Dokumen
                            </label>
                            <input type="text" class="form-control form-control-lg" id="kodeDokumen"
                                placeholder="Masukkan kode dokumen (contoh: DOC-2025-001)" autocomplete="off"
                                required>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Masukkan kode dokumen yang tertera pada dokumen Anda
                            </small>
                        </div>
                        <div class="alert alert-warning mb-0">
                            <small>
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Perhatian:</strong> Pastikan kode yang dimasukkan sesuai dengan kode yang
                                tertera pada dokumen digital Anda.
                            </small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="button" class="btn btn-primary" id="btnValidasiKode">
                        <i class="fas fa-check-circle mr-2"></i>Validasi Dokumen
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!--Global script(used by all pages)-->
    <script src="{{ asset('adminpage/assets/plugins/jQuery/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/dist/js/popper.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/metisMenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/html5-qrcode/html5-qrcode.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/dist/js/sidebar.js') }}"></script>

    <script>
        (function() {
            // Helper: show alert using jquery-confirm if available, else fallback to alert()
            function showAlert(title, content, type = 'blue') {
                if ($.confirm) {
                    $.confirm({
                        title: title,
                        content: content,
                        type: type,
                        buttons: {
                            ok: {
                                text: 'OK',
                                btnClass: 'btn-primary'
                            }
                        }
                    });
                } else {
                    alert((title ? (title + '\n\n') : '') + content);
                }
            }

            // Validate and trigger check via AJAX. On success redirect to hasil page (by token).
            async function validateTokenAndRedirect(token) {
                if (!token || token.trim() === '') {
                    showAlert('Validasi', 'Silakan masukkan kode dokumen terlebih dahulu.', 'orange');
                    return;
                }

                // show loading state on button
                const $btn = $('#btnValidasiKode');
                const originalHtml = $btn.html();
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Memeriksa...');

                try {
                    const res = await $.ajax({
                        url: '/validasi/dokumen-digital/check',
                        method: 'POST',
                        data: {
                            token: token
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                    });

                    // If API returns status = 1 => success; redirect to hasil page
                    if (res && (res.status === 1 || res.status === '1')) {
                        // close modal first
                        $('#inputKodeModal').modal('hide');

                        // redirect to hasil page (route: /validasi/dokumen-digital/{token})
                        // Encode token to be safe in URL
                        const encoded = encodeURIComponent(token);
                        window.location.href = '/validasi/dokumen-digital/' + encoded;
                    } else {
                        // show message from API (keterangan) or a general message
                        const msg = res && (res.keterangan || res.message) ? (res.keterangan || res.message) :
                            'Token tidak valid atau tidak ditemukan.';
                        showAlert('Hasil Validasi', msg, 'red');
                    }
                } catch (err) {
                    console.error('AJAX error', err);
                    let msg = 'Terjadi kesalahan saat menghubungi server.';
                    if (err && err.responseJSON && err.responseJSON.keterangan) {
                        msg = err.responseJSON.keterangan;
                    } else if (err && err.statusText) {
                        msg = err.statusText;
                    }
                    showAlert('Kesalahan', msg, 'red');
                } finally {
                    $btn.prop('disabled', false).html(originalHtml);
                }
            }

            // bind click event
            $(document).on('click', '#btnValidasiKode', function(e) {
                const token = $('#kodeDokumen').val();
                validateTokenAndRedirect(token);
            });

            // allow Enter key inside input to trigger the same action
            $(document).on('keypress', '#kodeDokumen', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#btnValidasiKode').trigger('click');
                }
            });

            // Quick debug: show console message to confirm JS loaded
            console.log('Validasi dokumen JS loaded');

            // NOTE:
            // - The scanning flow (html5-qrcode) is not implemented here. If you want, I can add a small init
            //   so the "Mulai Scan QR Code" button opens the camera and reads token values from QR.
            // - Make sure route GET /validasi/dokumen-digital/{token} calls controller method validasi_hasil($token)
            //   and that the controller returns the hasil view (the view will call POST /check to fetch details).

            let html5QrCode;
let isScanning = false;

$(document).on('click', '#startScanBtn', function () {
    if (isScanning) return;

    $('#reader').show();
    $('#startScanBtn').hide();
    $('#stopScanBtn').show();

    html5QrCode = new Html5Qrcode("reader");

    const config = {
        fps: 10,
        qrbox: { width: 250, height: 250 }
    };

    html5QrCode.start(
        { facingMode: "environment" }, // kamera belakang
        config,
        (decodedText, decodedResult) => {
            // QR berhasil dibaca
            console.log("QR Code detected:", decodedText);

            // Stop scanner
            stopScanner();

            // Tampilkan loading
            $('#loadingScan').addClass('active');

            // Kirim ke fungsi validasi
            validateTokenAndRedirect(decodedText);
        },
        (errorMessage) => {
            // error scan (biasanya diabaikan)
            // console.log(errorMessage);
        }
    ).then(() => {
        isScanning = true;
    }).catch(err => {
        console.error("Gagal membuka kamera", err);
        showAlert('Error', 'Tidak dapat mengakses kamera.', 'red');
    });
});

// tombol stop
$(document).on('click', '#stopScanBtn', function () {
    stopScanner();
});

function stopScanner() {
    if (html5QrCode && isScanning) {
        html5QrCode.stop().then(() => {
            html5QrCode.clear();
            isScanning = false;

            $('#reader').hide();
            $('#startScanBtn').show();
            $('#stopScanBtn').hide();
            $('#loadingScan').removeClass('active');
        }).catch(err => {
            console.error("Gagal stop scanner", err);
        });
    }
}
        })();
    </script>

</body>

</html>
