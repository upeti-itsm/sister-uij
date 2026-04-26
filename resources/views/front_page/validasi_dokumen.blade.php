<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Bdtask">
    <title>SISTER | Validasi Dokumen Digital</title>
    <link rel="shortcut icon" href="{{ asset('image/logo-uij.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('adminpage/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/metisMenu/metisMenu.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/typicons/src/typicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/themify-icons/themify-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/jquery-confirm/jquery-confirm.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/dist/css/style.css') }}" rel="stylesheet">

    <style>
        .qr-validate-wrap {
            width: 100%;
            max-width: 100%;
            margin: 0;
        }

        .qr-card {
            width: 100%;
            background: #fff;
            border: 1px solid #e7edf3;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .06);
            overflow: hidden;
        }

        .qr-card-head {
            padding: 16px 18px;
            border-bottom: 1px solid #eef2f7;
            background: #ffffff;
        }

        .qr-card-head h5 {
            margin: 0;
            font-weight: 700;
            color: #1f2937;
            font-size: 1rem;
        }

        .qr-card-head p {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: .84rem;
        }

        .qr-card-body {
            padding: 18px;
        }

        .qr-guide {
            border: 1px solid #bfdbfe;
            background: #eff6ff;
            color: #1e40af;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: .84rem;
            margin-bottom: 12px;
        }

        #qr-reader {
            width: 100%;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
            min-height: 320px;
        }

        .qr-status {
            margin-top: 12px;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #334155;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: .84rem;
        }

        .qr-status.error {
            border-color: #fecaca;
            background: #fef2f2;
            color: #b91c1c;
        }

        .qr-status.success {
            border-color: #bbdfc9;
            background: #eaf7f0;
            color: #166534;
        }

        .qr-actions {
            margin-top: 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .btn-q-primary {
            background: #37a000;
            border: 1px solid #37a000;
            color: #fff;
            border-radius: 10px;
            padding: 8px 14px;
            font-weight: 600;
            font-size: .84rem;
        }

        .btn-q-primary:hover {
            color: #fff;
            background: #2e8500;
            border-color: #2e8500;
        }

        .btn-q-outline {
            background: #fff;
            border: 1px solid #cbd5e1;
            color: #334155;
            border-radius: 10px;
            padding: 8px 14px;
            font-weight: 600;
            font-size: .84rem;
        }

        .btn-q-outline:hover {
            border-color: #0f5c5f;
            color: #0f5c5f;
        }

        .qr-manual {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px dashed #d1d5db;
        }

        .qr-note {
            margin-top: 10px;
            font-size: .76rem;
            color: #64748b;
        }

        @media (max-width: 576px) {

            .qr-card-head,
            .qr-card-body {
                padding: 14px;
            }

            .qr-card-head h5 {
                font-size: .95rem;
            }

            .qr-card-head p {
                font-size: .8rem;
            }

            #qr-reader {
                min-height: 260px;
            }
        }
    </style>
</head>

<body class="fixed">
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

    <div class="wrapper">
        <nav class="sidebar sidebar-bunker">
            <div class="sidebar-header">
                <a href="#" class="logo">
                    <img src="{{ asset('image/Logos.png') }}" style="width: 100%; height: 100%" alt="">
                </a>
            </div>

            <div class="profile-element d-flex align-items-center flex-shrink-0 bg-dark">
                <div class="avatar online">
                    <img src="{{ asset('adminpage/assets/dist/img/avatar-1.jpg') }}" class="img-fluid rounded-circle"
                        alt="" onerror="this.src='{{ asset('adminpage/assets/dist/img/avatar-1.jpg') }}'">
                </div>
                <div class="profile-text">
                    <small class="m-0 text-white">GUEST</small><br />
                    <span>Validasi Dokumen Digital</span>
                </div>
            </div>

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
            </div>
        </nav>

        <div class="content-wrapper">
            <div class="main-content">
                <nav class="navbar-custom-menu navbar navbar-expand-lg m-0">
                    <div class="sidebar-toggle-icon mr-3" id="sidebarCollapse">
                        sidebar toggle<span></span>
                    </div>
                    <div class="d-flex flex-grow-1">
                        <div class="nav-clock ml-auto">
                            <div class="time">
                                <span class="time-hours"></span>
                                <span class="time-min"></span>
                                <span class="time-sec"></span>
                            </div>
                        </div>
                    </div>
                </nav>

                <div class="content-header row align-items-center m-0">
                    <div class="col-12 header-title p-0">
                        <div class="media">
                            <div class="header-icon text-success mr-3"><i class="typcn typcn-th-large"></i></div>
                            <div class="media-body">
                                <h1 class="font-weight-bold">Validasi Dokumen</h1>
                                <small>
                                    Halaman ini digunakan untuk memvalidasi dokumen digital yang telah ditandatangani
                                    secara elektronik (TTE). Cukup dengan memindai QR code pada dokumen, Anda dapat
                                    langsung mengetahui keaslian dan status validasi dokumen tersebut.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="body-content">
                    <div class="container-fluid px-0">
                        <div class="qr-validate-wrap">
                            <div class="qr-card">
                                <div class="qr-card-head">
                                    <h5><i class="fas fa-qrcode mr-1"></i> Scan QR Validasi Dokumen</h5>
                                    <p>Arahkan kamera ke QR code pada dokumen, lalu sistem akan mengalihkan ke halaman
                                        detail validasi.</p>
                                </div>

                                <div class="qr-card-body">
                                    <div class="qr-guide">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Pastikan browser sudah diberi izin kamera.
                                    </div>

                                    <div id="qr-reader"></div>

                                    <div id="scanStatus" class="qr-status">
                                        <i class="fas fa-camera mr-1"></i> Menunggu scanner dijalankan...
                                    </div>

                                    <div class="qr-actions">
                                        <button id="startScanBtn" class="btn btn-q-primary">
                                            <i class="fas fa-play mr-1"></i> Mulai Scan
                                        </button>
                                        <button id="stopScanBtn" class="btn btn-q-outline">
                                            <i class="fas fa-stop mr-1"></i> Hentikan
                                        </button>
                                        <a href="{{ route('frontpage.home') }}" class="btn btn-q-outline">
                                            <i class="fas fa-home mr-1"></i> Halaman Utama
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--/.main content-->

            <footer class="footer-content">
                <div class="footer-text d-flex align-items-center justify-content-between">
                    <div class="copy">&copy; {{ date('Y') }} UPT-TI. All Rights Reserved.</div>
                </div>
            </footer>
            <div class="overlay"></div>
        </div>
    </div>

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
            const scanStatus = document.getElementById('scanStatus');
            const startScanBtn = document.getElementById('startScanBtn');
            const stopScanBtn = document.getElementById('stopScanBtn');

            if (typeof Html5Qrcode === 'undefined') {
                scanStatus.className = 'qr-status error';
                scanStatus.innerHTML = '<i class="fas fa-times-circle mr-1"></i> Library scanner tidak ditemukan.';
                return;
            }

            const html5QrCode = new Html5Qrcode("qr-reader");
            let isScannerRunning = false;
            let isRedirecting = false;
            const detailRouteBase = @json(url('/tte-validation'));

            function setStatus(message, type = '') {
                scanStatus.className = 'qr-status' + (type ? ' ' + type : '');
                scanStatus.innerHTML = message;
            }

            function toDetailUrl(scannedText) {
                const text = (scannedText || '').trim();
                if (!text) return null;

                const fullMatch = text.match(/\/tte-validation\/([^\/\?\#]+)/i);
                if (fullMatch && fullMatch[1]) {
                    return `${detailRouteBase}/${encodeURIComponent(fullMatch[1])}`;
                }
                return `${detailRouteBase}/${encodeURIComponent(text)}`;
            }

            async function onScanSuccess(decodedText) {
                if (isRedirecting) return;
                isRedirecting = true;

                const targetUrl = toDetailUrl(decodedText);
                if (!targetUrl) {
                    isRedirecting = false;
                    setStatus('<i class="fas fa-exclamation-triangle mr-1"></i> QR tidak valid.', 'error');
                    return;
                }

                setStatus('<i class="fas fa-check-circle mr-1"></i> QR terbaca, mengalihkan ke detail dokumen...',
                    'success');

                try {
                    if (isScannerRunning) {
                        await html5QrCode.stop();
                        isScannerRunning = false;
                    }
                } catch (e) {}

                window.location.href = targetUrl;
            }

            function onScanFailure() {}

            async function startScanner() {
                if (isScannerRunning) return;
                try {
                    setStatus('<i class="fas fa-spinner fa-spin mr-1"></i> Mengaktifkan kamera...');
                    await html5QrCode.start({
                            facingMode: "environment"
                        }, {
                            fps: 10,
                            qrbox: {
                                width: 280,
                                height: 280
                            }
                        },
                        onScanSuccess,
                        onScanFailure
                    );
                    isScannerRunning = true;
                    setStatus('<i class="fas fa-camera mr-1"></i> Kamera aktif. Silakan scan QR dokumen.');
                } catch (err) {
                    setStatus(
                        '<i class="fas fa-times-circle mr-1"></i> Kamera gagal diakses. Izinkan akses kamera lalu coba lagi.',
                        'error');
                }
            }

            async function stopScanner() {
                if (!isScannerRunning) return;
                try {
                    await html5QrCode.stop();
                    isScannerRunning = false;
                    setStatus('<i class="fas fa-stop-circle mr-1"></i> Scanner dihentikan.');
                } catch (err) {
                    setStatus('<i class="fas fa-exclamation-triangle mr-1"></i> Gagal menghentikan scanner.',
                        'error');
                }
            }

            startScanBtn.addEventListener('click', startScanner);
            stopScanBtn.addEventListener('click', stopScanner);

            window.addEventListener('load', startScanner);

            document.addEventListener('visibilitychange', async function() {
                if (document.hidden) {
                    await stopScanner();
                }
            });
        })();
    </script>
</body>

</html>
