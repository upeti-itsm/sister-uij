<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistem informasi terpadu; Siakad Mandala">
    <meta name="author" content="Bdtask">
    <title>SIPADU | Mandala &mdash; Hasil Validasi Dokumen</title>
    <link rel="shortcut icon" href="{{ asset('image/logo-mandala.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('adminpage/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/metisMenu/metisMenu.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/typicons/src/typicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/themify-icons/themify-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/dist/css/style.css') }}" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .hasil-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            margin-bottom: 30px;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hasil-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 50px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hasil-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .hasil-header. invalid {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }

        .hasil-header i {
            font-size: 5rem;
            margin-bottom: 20px;
            animation: checkScale 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            position: relative;
            z-index: 1;
            text-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        @keyframes checkScale {
            0% {
                transform: scale(0) rotate(-180deg);
                opacity: 0;
            }
            50% {
                transform: scale(1.2) rotate(10deg);
            }
            100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }

        .hasil-header h2 {
            font-weight: 800;
            margin-bottom: 15px;
            font-size: 2.5rem;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
            letter-spacing: 1px;
        }

        .hasil-header p {
            font-size: 1.1rem;
            opacity: 0.95;
            position: relative;
            z-index: 1;
            font-weight: 500;
        }

        .hasil-body {
            padding: 40px;
        }

        .info-section {
            background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 25px;
            border-left: 5px solid #28a745;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .info-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .info-section h5 {
            color: #28a745;
            font-weight: 700;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            font-size: 1.4rem;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
        }

        .info-section h5 i {
            margin-right: 12px;
            font-size: 1.5rem;
            background: linear-gradient(135deg, #28a745, #20c997);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .info-row {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
            transition: background 0.2s ease;
        }

        .info-row:hover {
            background: rgba(40, 167, 69, 0.05);
            padding-left: 10px;
            border-radius: 6px;
        }

        . info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            min-width: 220px;
            display: flex;
            align-items: flex-start;
            font-size: 0.95rem;
        }

        . info-label i {
            margin-right: 10px;
            color: #28a745;
            width: 22px;
            margin-top: 2px;
        }

        .info-value {
            color: #212529;
            flex: 1;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .badge-custom {
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .badge-success-custom {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .badge-danger-custom {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }

        .badge-info-custom {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
        }

        .verifikator-list {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-top: 15px;
        }

        .verifikator-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: linear-gradient(145deg, #f8f9fa, #ffffff);
            border-radius: 8px;
            margin-bottom: 12px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .verifikator-item:hover {
            transform: translateX(10px);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.15);
            border-color: #28a745;
        }

        .verifikator-item:last-child {
            margin-bottom: 0;
        }

        .verifikator-item i {
            font-size: 2rem;
            color: #28a745;
            margin-right: 20px;
            background: rgba(40, 167, 69, 0.1);
            padding: 15px;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .verifikator-role {
            font-weight: 700;
            color: #495057;
            margin-bottom: 5px;
            font-size: 1rem;
        }

        .verifikator-name {
            color: #6c757d;
            font-size: 0.95rem;
        }

        . timeline {
            position: relative;
            padding-left: 40px;
            margin-top: 25px;
        }

        . timeline::before {
            content: '';
            position: absolute;
            left: 12px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, #28a745, #20c997);
            border-radius: 10px;
        }

        . timeline-item {
            position: relative;
            padding-bottom: 30px;
            animation: slideInLeft 0.6s ease-out backwards;
        }

        .timeline-item:nth-child(1) { animation-delay: 0.1s; }
        .timeline-item:nth-child(2) { animation-delay: 0.2s; }
        .timeline-item:nth-child(3) { animation-delay: 0.3s; }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -33px;
            top: 5px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: white;
            border: 4px solid #28a745;
            box-shadow: 0 0 0 4px rgba(40, 167, 69, 0.2);
            z-index: 1;
        }

        .timeline-time {
            font-size: 0.9rem;
            color: #6c757d;
            font-style: italic;
            margin-top: 5px;
        }

        .timeline-title {
            font-weight: 700;
            color: #495057;
            margin-bottom: 5px;
            font-size: 1.05rem;
        }

        . action-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .btn-custom {
            padding: 15px 40px;
            font-weight: 700;
            border-radius: 50px;
            transition: all 0.3s ease;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0. 2);
        }

        . btn-custom i {
            margin-right: 10px;
        }

        .btn-print {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border: none;
        }

        .btn-print:hover {
            background: linear-gradient(135deg, #0056b3, #003d82);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
        }

        .btn-back {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
            border: none;
        }

        .btn-back:hover {
            background: linear-gradient(135deg, #5a6268, #495057);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
        }

        .btn-scan-again {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
        }

        .btn-scan-again:hover {
            background: linear-gradient(135deg, #20c997, #17a589);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .hasil-card {
                box-shadow: none;
                border: 1px solid #dee2e6;
            }

            .info-section {
                page-break-inside: avoid;
            }
        }

        . loading-container {
            text-align: center;
            padding: 150px 20px;
        }

        . spinner-border-custom {
            width: 5rem;
            height: 5rem;
            border-width: 0.5em;
            color: #28a745;
        }

        .loading-container p {
            font-size: 1.2rem;
            color: #6c757d;
            font-weight: 600;
            margin-top: 20px;
        }

        . error-container {
            text-align: center;
            padding: 100px 20px;
        }

        .error-container i {
            font-size: 6rem;
            color: #dc3545;
            margin-bottom: 30px;
            animation: shake 1s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .error-container h3 {
            font-weight: 700;
            color: #495057;
            margin-bottom: 15px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hasil-header h2 {
                font-size: 1.8rem;
            }

            .hasil-header i {
                font-size: 3. 5rem;
            }

            .hasil-body {
                padding: 25px;
            }

            . info-section {
                padding: 20px;
            }

            .info-row {
                flex-direction: column;
            }

            .info-label {
                min-width: 100%;
                margin-bottom: 8px;
            }

            . action-buttons {
                flex-direction: column;
            }

            .btn-custom {
                width: 100%;
            }
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
            <p>Please wait... </p>
        </div>
    </div>

    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar sidebar-bunker">
            <div class="sidebar-header">
                <a href="#" class="logo">
                    <img src="{{ asset('image/logo-2-sipadu-sidebar.png') }}" style="width: 100%; height: 100%"
                        alt="">
                </a>
            </div>
            <div class="profile-element d-flex align-items-center flex-shrink-0 bg-dark">
                <div class="avatar online">
                    <img src="{{ asset('adminpage/assets/dist/img/avatar-1.jpg') }}" class="img-fluid rounded-circle"
                        alt="">
                </div>
                <div class="profile-text">
                    <small class="m-0 text-white">Welcome to SIPADU</small>
                    <br />
                    <span>Hasil Validasi Dokumen</span>
                </div>
            </div>
            <div class="sidebar-body">
                <nav class="sidebar-nav">
                    <ul class="metismenu">
                        <li class="nav-label">Menu Utama</li>
                        <li>
                            <a href="{{ route('validasi.show') }}">
                                <i class="typcn typcn-arrow-back mr-2"></i>Kembali ke Validasi
                            </a>
                        </li>
                        <li class="mm-active">
                            <a href="#">
                                <i class="typcn typcn-document mr-2"></i>Hasil Validasi
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="content-wrapper">
            <div class="main-content">
                <nav class="navbar-custom-menu navbar navbar-expand-lg m-0">
                    <div class="sidebar-toggle-icon mr-3" id="sidebarCollapse">
                        sidebar toggle<span></span>
                    </div>
                    <img src="{{ asset('image/mdl. png') }}" alt="" class="img-fluid" style="max-height: 40px">
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
                    <div class="col-sm-12 header-title p-0">
                        <div class="media">
                            <div class="header-icon text-success mr-3"><i class="typcn typcn-document-text"></i></div>
                            <div class="media-body">
                                <h1 class="font-weight-bold">Hasil Validasi Dokumen Digital</h1>
                                <small>Detail informasi validasi dokumen digital SIPADU dari QR Code</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="body-content">
                    <div class="row">
                        <div class="col-lg-11 offset-lg-0">
                            <div class="hasil-card" id="printArea">
                                <!-- Header Status -->
                                <div class="hasil-header {{ isset($error) && $error ? 'invalid' : '' }}">
                                    <i class="fas {{ isset($result['token_valid']) && $result['token_valid'] ?  'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    <h2>{{ isset($result['token_valid']) && $result['token_valid'] ?  'Dokumen Valid ✓' : 'Dokumen Tidak Valid ✗' }}</h2>
                                    <p class="mb-0">{{ $result['keterangan'] ??  'Tidak ada informasi' }}</p>
                                </div>

                                @if(isset($result['token_valid']) && $result['token_valid'])
                                <!-- Body Content - Valid Document -->
                                <div class="hasil-body">
                                    <!-- Informasi Dokumen RPS -->
                                    <div class="info-section">
                                        <h5>
                                            <i class="fas fa-file-alt"></i>
                                            Informasi Dokumen RPS
                                        </h5>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-barcode"></i>
                                                Kode RPS
                                            </div>
                                            <div class="info-value">{{ $result['kode_rps'] ?? '-' }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-book"></i>
                                                Mata Kuliah
                                            </div>
                                            <div class="info-value">{{ $result['nama_mk'] ?? '-' }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-code"></i>
                                                Kode MK
                                            </div>
                                            <div class="info-value">{{ $result['kode_mk'] ?? '-' }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-calculator"></i>
                                                SKS
                                            </div>
                                            <div class="info-value">
                                                <strong>Teori:</strong> {{ $result['sks_teori'] ?? 0 }} |
                                                <strong>Praktikum:</strong> {{ $result['sks_praktikum'] ??  0 }} |
                                                <strong>Total:</strong> {{ $result['sks_total'] ?? 0 }}
                                            </div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-layer-group"></i>
                                                Semester
                                            </div>
                                            <div class="info-value">Semester {{ $result['semester'] ?? '-' }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-graduation-cap"></i>
                                                Program Studi
                                            </div>
                                            <div class="info-value">
                                                {{ $result['nama_program_studi'] ?? '-' }}
                                                <span class="badge badge-info-custom ml-2">{{ $result['jenjang_didik'] ?? '' }}</span>
                                            </div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-calendar-alt"></i>
                                                Tahun Akademik
                                            </div>
                                            <div class="info-value">
                                                @php
                                                    $ta = $result['tahun_akademik'] ?? null;
                                                    if ($ta) {
                                                        $tahun = substr($ta, 0, 4);
                                                        $semester = substr($ta, 4);
                                                        $tahunBerikutnya = intval($tahun) + 1;
                                                        $semesterText = $semester === '1' ? 'Ganjil' : 'Genap';
                                                        echo "{$tahun}/{$tahunBerikutnya} {$semesterText}";
                                                    } else {
                                                        echo '-';
                                                    }
                                                @endphp
                                            </div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-info-circle"></i>
                                                Status Penugasan
                                            </div>
                                            <div class="info-value">
                                                <span class="badge badge-success-custom">{{ $result['status_penugasan_rps'] ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Informasi Dosen Pengembang -->
                                    <div class="info-section">
                                        <h5>
                                            <i class="fas fa-user-tie"></i>
                                            Dosen Pengembang
                                        </h5>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-user"></i>
                                                Nama Dosen
                                            </div>
                                            <div class="info-value">{{ $result['nama_dosen_pengembang'] ?? '-' }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-calendar-check"></i>
                                                Tanggal Penugasan
                                            </div>
                                            <div class="info-value">
                                                @php
                                                    $tglPenugasan = $result['tgl_penugasan'] ?? null;
                                                    if ($tglPenugasan) {
                                                        try {
                                                            $date = new DateTime($tglPenugasan);
                                                            echo $date->format('d F Y, H:i:s') . ' WIB';
                                                        } catch (Exception $e) {
                                                            echo $tglPenugasan;
                                                        }
                                                    } else {
                                                        echo '-';
                                                    }
                                                @endphp
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Informasi Verifikasi -->
                                    <div class="info-section">
                                        <h5>
                                            <i class="fas fa-shield-alt"></i>
                                            Informasi Verifikasi
                                        </h5>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-check-double"></i>
                                                Status Token
                                            </div>
                                            <div class="info-value">
                                                @if(isset($result['token_valid']) && $result['token_valid'] && isset($result['token_sudah_digunakan']) && $result['token_sudah_digunakan'])
                                                    <span class="badge badge-success-custom">Valid & Terverifikasi</span>
                                                @elseif(isset($result['token_valid']) && $result['token_valid'])
                                                    <span class="badge badge-info-custom">Valid</span>
                                                @else
                                                    <span class="badge badge-danger-custom">Tidak Valid</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-user-check"></i>
                                                Jenis Verifikator
                                            </div>
                                            <div class="info-value">{{ $result['jenis_verifikator'] ?? '-' }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">
                                                <i class="fas fa-clock"></i>
                                                Waktu Verifikasi
                                            </div>
                                            <div class="info-value">
                                                @php
                                                    $waktuVerif = $result['waktu_verifikasi'] ?? null;
                                                    if ($waktuVerif) {
                                                        try {
                                                            $date = new DateTime($waktuVerif);
                                                            echo $date->format('d F Y, H:i:s') .  ' WIB';
                                                        } catch (Exception $e) {
                                                            echo $waktuVerif;
                                                        }
                                                    } else {
                                                        echo '-';
                                                    }
                                                @endphp
                                            </div>
                                        </div>

                                        <!-- Daftar Verifikator -->
                                        <div class="mt-4">
                                            <strong class="d-block mb-3" style="font-size: 1.1rem; color: #495057;">
                                                <i class="fas fa-users mr-2 text-success"></i>Daftar Verifikator:
                                            </strong>
                                            <div class="verifikator-list">
                                                @php
                                                    $daftarVerif = $result['daftar_verifikator'] ?? null;
                                                    if ($daftarVerif) {
                                                        try {
                                                            $verifikator = json_decode($daftarVerif, true);
                                                        } catch (Exception $e) {
                                                            $verifikator = null;
                                                        }
                                                    } else {
                                                        $verifikator = null;
                                                    }
                                                @endphp

                                                @if($verifikator)
                                                    @if(isset($verifikator['dosen_pengembang']))
                                                        <div class="verifikator-item">
                                                            <i class="fas fa-user-graduate"></i>
                                                            <div>
                                                                <div class="verifikator-role">Dosen Pengembang</div>
                                                                <div class="verifikator-name">{{ $verifikator['dosen_pengembang'] }}</div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if(isset($verifikator['ko_rmk']) && is_array($verifikator['ko_rmk']))
                                                        @foreach($verifikator['ko_rmk'] as $ko)
                                                            <div class="verifikator-item">
                                                                <i class="fas fa-user-check"></i>
                                                                <div>
                                                                    <div class="verifikator-role">Koordinator RMK</div>
                                                                    <div class="verifikator-name">{{ $ko['nama'] ??  '-' }}</div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif

                                                    @if(isset($verifikator['kaprodi']) && is_array($verifikator['kaprodi']))
                                                        @foreach($verifikator['kaprodi'] as $ka)
                                                            <div class="verifikator-item">
                                                                <i class="fas fa-user-tie"></i>
                                                                <div>
                                                                    <div class="verifikator-role">Kepala Program Studi</div>
                                                                    <div class="verifikator-name">{{ $ka['nama'] ?? '-' }}</div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @else
                                                    <p class="text-muted mb-0 text-center py-3">
                                                        <i class="fas fa-info-circle mr-2"></i>Tidak ada data verifikator
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Timeline Verifikasi -->
                                        <div class="mt-5">
                                            <strong class="d-block mb-3" style="font-size: 1.1rem; color: #495057;">
                                                <i class="fas fa-history mr-2 text-success"></i>Timeline Verifikasi:
                                            </strong>
                                            <div class="timeline">
                                                @php
                                                    $hasTimeline = false;
                                                @endphp

                                                @if(isset($result['verf_action_time_tim_dosen']))
                                                    @php $hasTimeline = true; @endphp
                                                    <div class="timeline-item">
                                                        <div class="timeline-title">Verifikasi Tim Dosen Pengembang</div>
                                                        <div class="timeline-time">
                                                            <i class="far fa-clock mr-1"></i>
                                                            @php
                                                                try {
                                                                    $date = new DateTime($result['verf_action_time_tim_dosen']);
                                                                    echo $date->format('d F Y, H:i:s') . ' WIB';
                                                                } catch (Exception $e) {
                                                                    echo $result['verf_action_time_tim_dosen'];
                                                                }
                                                            @endphp
                                                        </div>
                                                    </div>
                                                @endif

                                                @if(isset($result['verf_action_time_ko_rmk']))
                                                    @php $hasTimeline = true; @endphp
                                                    <div class="timeline-item">
                                                        <div class="timeline-title">Verifikasi Koordinator RMK</div>
                                                        <div class="timeline-time">
                                                            <i class="far fa-clock mr-1"></i>
                                                            @php
                                                                try {
                                                                    $date = new DateTime($result['verf_action_time_ko_rmk']);
                                                                    echo $date->format('d F Y, H:i:s') . ' WIB';
                                                                } catch (Exception $e) {
                                                                    echo $result['verf_action_time_ko_rmk'];
                                                                }
                                                            @endphp
                                                        </div>
                                                    </div>
                                                @endif

                                                @if(isset($result['verf_action_time_kaprodi']))
                                                    @php $hasTimeline = true; @endphp
                                                    <div class="timeline-item">
                                                        <div class="timeline-title">Verifikasi Kepala Program Studi</div>
                                                        <div class="timeline-time">
                                                            <i class="far fa-clock mr-1"></i>
                                                            @php
                                                                try {
                                                                    $date = new DateTime($result['verf_action_time_kaprodi']);
                                                                    echo $date->format('d F Y, H:i:s') . ' WIB';
                                                                } catch (Exception $e) {
                                                                    echo $result['verf_action_time_kaprodi'];
                                                                }
                                                            @endphp
                                                        </div>
                                                    </div>
                                                @endif

                                                @if(! $hasTimeline)
                                                    <p class="text-muted mb-0">
                                                        <i class="fas fa-info-circle mr-2"></i>Belum ada riwayat verifikasi
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="action-buttons no-print">
                                        <a href="{{ route('validasi.show') }}" class="btn btn-custom btn-scan-again">
                                            <i class="fas fa-qrcode"></i>
                                            Scan QR Lain
                                        </a>
                                        <a href="{{ route('validasi.show') }}" class="btn btn-custom btn-back">
                                            <i class="fas fa-arrow-left"></i>
                                            Kembali
                                        </a>
                                    </div>
                                </div>
                                @else
                                <!-- Error Content -->
                                <div class="hasil-body">
                                    <div class="error-container">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <h3>Dokumen Tidak Dapat Diverifikasi</h3>
                                        <p class="text-muted">{{ $result['keterangan'] ?? 'Token/Kode dokumen tidak valid atau tidak ditemukan' }}</p>

                                        <div class="action-buttons mt-4">
                                            <a href="{{ route('validasi.show') }}" class="btn btn-custom btn-scan-again">
                                                <i class="fas fa-qrcode"></i>
                                                Scan QR Lain
                                            </a>
                                            <a href="{{ route('validasi.show') }}" class="btn btn-custom btn-back">
                                                <i class="fas fa-arrow-left"></i>
                                                Kembali
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="footer-content no-print">
                <div class="footer-text d-flex align-items-center justify-content-between">
                    <div class="copy">
                        &copy; {{ date('Y') }} UPETI Mandala. All Rights Reserved.
                    </div>
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
    <script src="{{ asset('adminpage/assets/dist/js/sidebar.js') }}"></script>
</body>

</html>
