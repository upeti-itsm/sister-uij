<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPADU | Hasil Validasi Dokumen Digital</title>
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
        /* Modern Minimal Design */
        .result-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }

        /* Header Card */
        .validation-header {
            background: #ffffff;
            border-radius: 12px;
            padding: 32px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #28a745;
        }

        .validation-status {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .status-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            flex-shrink: 0;
        }

        .status-icon.valid {
            background: #ecfdf5;
            color: #059669;
        }

        .status-icon.invalid {
            background: #fef2f2;
            color: #dc2626;
        }

        .status-content h2 {
            margin: 0 0 4px 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
        }

        .status-content p {
            margin: 0;
            color: #6b7280;
            font-size: 0.95rem;
        }

        .token-display {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            font-family: 'Courier New', monospace;
            font-size: 1rem;
            color: #374151;
            letter-spacing: 1px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            margin-top: 16px;
        }

        .btn-custom {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary-custom {
            background: #28a745;
            color: white;
        }

        .btn-primary-custom:hover {
            background: #1d4ed8;
        }

        .btn-secondary-custom {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-secondary-custom:hover {
            background: #e5e7eb;
        }

        /* Main Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .info-card h3 {
            margin: 0 0 20px 0;
            font-size: 1.1rem;
            font-weight: 700;
            color: #111827;
            padding-bottom: 12px;
            border-bottom: 2px solid #f3f4f6;
        }

        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f9fafb;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            flex: 0 0 140px;
            font-weight: 600;
            color: #6b7280;
            font-size: 0.9rem;
        }

        .info-value {
            flex: 1;
            color: #111827;
            font-size: 0.95rem;
        }

        /* Timeline */
        .timeline-card {
            grid-column: 1 / -1;
        }

        .timeline {
            position: relative;
            padding-left: 32px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 24px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -26px;
            top: 6px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #28a745;
            border: 3px solid #dbeafe;
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            left: -21px;
            top: 18px;
            width: 2px;
            height: calc(100% - 6px);
            background: #e5e7eb;
        }

        .timeline-item:last-child::after {
            display: none;
        }

        .timeline-title {
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
            text-decoration: none !important;
        }

        .timeline-time {
            color: #6b7280;
            font-size: 0.88rem;
            text-decoration: none !important;
        }

        .timeline-item * {
            text-decoration: none !important;
        }

        /* Verifikator List */
        .verifikator-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #f3f4f6;
        }

        .verifikator-section h4 {
            font-size: 0.95rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 12px;
        }

        .verifikator-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .verifikator-list li {
            padding: 8px 0 8px 24px;
            position: relative;
            color: #6b7280;
            font-size: 0.9rem;
        }

        .verifikator-list li::before {
            content: '•';
            position: absolute;
            left: 8px;
            color: #28a745;
            font-weight: bold;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        /* Error State */
        .error-card {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 32px;
            text-align: center;
        }

        .error-card i {
            font-size: 3rem;
            color: #dc2626;
            margin-bottom: 16px;
        }

        .error-card h3 {
            color: #991b1b;
            margin-bottom: 8px;
        }

        .error-card p {
            color: #dc2626;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }

            .validation-header {
                padding: 20px;
            }

            .validation-status {
                flex-direction: column;
                align-items: flex-start;
            }

            .info-row {
                flex-direction: column;
                gap: 4px;
            }

            .info-label {
                flex: none;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-custom {
                width: 100%;
            }

            .result-container {
                padding: 12px;
            }

        }

        /* Print Styles */
        @media print {

            .action-buttons,
            .sidebar,
            .navbar-custom-menu {
                display: none !important;
            }

            .validation-header {
                box-shadow: none;
                border: 1px solid #e5e7eb;
            }

            .info-card {
                box-shadow: none;
                border: 1px solid #e5e7eb;
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body class="fixed">
    <!-- page loader -->
    <div class="page-loader-wrapper" style="display:none;">
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
        <!-- sidebar (unchanged) -->
        <nav class="sidebar sidebar-bunker">
            <div class="sidebar-header">
                <a href="#" class="logo"><img src="{{ asset('image/logo-2-sipadu-sidebar.png') }}"
                        style="width:100%;height:100%" alt=""></a>
            </div>
            <div class="profile-element d-flex align-items-center flex-shrink-0 bg-dark">
                <div class="avatar online">
                    <img src="{{ asset('adminpage/assets/dist/img/avatar-1.jpg') }}" class="img-fluid rounded-circle"
                        alt="" onerror="this.src='{{ asset('adminpage/assets/dist/img/avatar-1.jpg') }}'">
                </div>
                <div class="profile-text">
                    <small class="m-0 text-white">Welcome to SIPADU</small><br>
                    <span>Validasi Dokumen Digital</span>
                </div>
            </div>
            <div class="sidebar-body">
                <nav class="sidebar-nav">
                    <ul class="metismenu">
                        <li class="nav-label">Menu Utama</li>
                        <li class="mm-active"><a href="#"><i class="typcn typcn-home mr-2"></i>Validasi Dokumen
                                Digital</a></li>
                    </ul>
                </nav>
            </div>
        </nav>

        <!-- content wrapper -->
        <div class="content-wrapper">
            <div class="main-content">
                <!-- topbar (unchanged) -->
                <nav class="navbar-custom-menu navbar navbar-expand-lg m-0">
                    <div class="sidebar-toggle-icon mr-3" id="sidebarCollapse">sidebar toggle<span></span></div>
                    <img src="{{ asset('image/mdl.png') }}" alt="" class="img-fluid" style="max-height:40px">
                    <div class="d-flex flex-grow-1">
                        <div class="nav-clock ml-auto">
                            <div class="time"><span class="time-hours"></span><span class="time-min"></span><span
                                    class="time-sec"></span></div>
                        </div>
                    </div>
                </nav>

                <!-- content header -->
                <div class="content-header row align-items-center m-0">
                    <div class="col-sm-8 header-title p-0">
                        <div class="media">
                            <div class="header-icon text-success mr-3"><i class="typcn typcn-th-large"></i></div>
                            <div class="media-body">
                                <h1 class="font-weight-bold">Hasil Validasi Dokumen</h1>
                                <small>Verifikasi dan detail informasi dokumen digital</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- body content -->
                <div class="body-content">
                    <div class="result-container" id="mainContent">

                        <!-- Validation Header -->
                        <div class="validation-header">
                            <div class="validation-status">
                                <div class="status-icon valid" id="statusIcon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="status-content">
                                    <h2 id="keterangan">Memuat data validasi...</h2>
                                    <p id="jenisVerifikator">Sistem Validasi Dokumen Digital</p>
                                </div>
                            </div>

                            <div>
                                <label
                                    style="font-size: 0.85rem; color: #6b7280; margin-bottom: 6px; display: block;">Kode
                                    Token Dokumen</label>
                                <div class="token-display" id="kodeToken">-</div>
                            </div>

                            <div class="action-buttons">
                                <a href="{{ url('/validasi/dokumen-digital') }}" class="btn-custom btn-primary-custom">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </a>
                            </div>
                        </div>

                        <!-- Content Grid -->
                        <div class="content-grid">

                            <!-- Document Info -->
                            <div class="info-card">
                                <h3><i class="fas fa-file-alt mr-2" style="color: #28a745;"></i>Informasi Dokumen</h3>
                                <div class="info-row">
                                    <div class="info-label">Kode RPS</div>
                                    <div class="info-value" id="kodeRPS">-</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Kode MK</div>
                                    <div class="info-value" id="kodeMK">-</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Nama MK</div>
                                    <div class="info-value" id="namaMK">-</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">SKS</div>
                                    <div class="info-value" id="sks">-</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Semester</div>
                                    <div class="info-value" id="semester">-</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Program Studi</div>
                                    <div class="info-value" id="programStudi">-</div>
                                </div>
                            </div>

                            <!-- Validation Info -->
                            <div class="info-card">
                                <h3><i class="fas fa-shield-alt mr-2" style="color: #28a745;"></i>Status Validasi</h3>
                                <div class="info-row">
                                    <div class="info-label">Waktu Verifikasi</div>
                                    <div class="info-value" id="waktuVerifikasi">-</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Token Valid</div>
                                    <div class="info-value" id="tokenValid">-</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Token Digunakan</div>
                                    <div class="info-value" id="tokenUsed">-</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Terakhir Update</div>
                                    <div class="info-value" id="tglUpdated">-</div>
                                </div>

                                <div class="verifikator-section">
                                    <h4>Verifikator Utama</h4>
                                    <div id="namaVerifikator" style="color: #374151; font-weight: 500;">-</div>
                                </div>
                            </div>

                            <!-- Timeline -->
                            <div class="info-card timeline-card">
                                <h3><i class="fas fa-history mr-2" style="color: #28a745;"></i>Riwayat Verifikasi</h3>
                                <div class="timeline" id="verifTimeline">
                                    <div class="empty-state">
                                        <i class="fas fa-clock"></i>
                                        <p>Belum ada riwayat verifikasi</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Verifikator Details -->
                            <div class="info-card timeline-card" id="verifikatorCard" style="display: none;">
                                <h3><i class="fas fa-users mr-2" style="color: #28a745;"></i>Daftar Verifikator</h3>
                                <div id="daftarVerifikatorContainer"></div>
                            </div>

                        </div>

                    </div>

                    <!-- Error State -->
                    <div id="errorContent" style="display:none; max-width: 600px; margin: 60px auto;">
                        <div class="error-card">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h3>Gagal Memuat Data</h3>
                            <p id="errorMessage">Terjadi kesalahan saat memuat hasil validasi</p>
                            <a href="{{ url('/validasi/dokumen-digital') }}" class="btn-custom btn-primary-custom"
                                style="margin-top: 20px; display: inline-block;">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Halaman Utama
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <footer class="footer-content">
                <div class="footer-text d-flex align-items-center justify-content-between">
                    <div class="copy">&copy; {{ date('Y') }} UPETI Mandala. All Rights Reserved.</div>
                </div>
            </footer>
        </div>
    </div>

    <!-- scripts -->
    <script src="{{ asset('adminpage/assets/plugins/jQuery/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/dist/js/popper.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/metisMenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/dist/js/sidebar.js') }}"></script>

    <script>
        (function() {
            const serverTokenEl = document.getElementById('serverToken');
            const token = (serverTokenEl && serverTokenEl.dataset && serverTokenEl.dataset.token) ?
                serverTokenEl.dataset.token :
                (function() {
                    const parts = window.location.pathname.split('/').filter(Boolean);
                    return parts[parts.length - 1] || '';
                })();

            function fmtDate(dt) {
                if (!dt) return '-';
                try {
                    const d = new Date(dt);
                    if (isNaN(d)) return dt;
                    return d.toLocaleString('id-ID', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                } catch (e) {
                    return dt;
                }
            }

            function showError(msg) {
                $('#mainContent').hide();
                $('#errorMessage').text(msg || 'Terjadi kesalahan.');
                $('#errorContent').show();
            }

            function render(data, token) {
                const isValid = data.status && (data.status === 1 || data.status === '1');

                $('#keterangan').text(data.keterangan || 'Hasil Validasi Dokumen');
                $('#jenisVerifikator').text(data.jenis_verifikator || 'Sistem Validasi Dokumen Digital');
                $('#kodeToken').text(data.kode_rps || '-');

                // Update status icon
                const $icon = $('#statusIcon');
                if (isValid) {
                    $icon.removeClass('invalid').addClass('valid');
                    $icon.html('<i class="fas fa-check-circle"></i>');
                } else {
                    $icon.removeClass('valid').addClass('invalid');
                    $icon.html('<i class="fas fa-times-circle"></i>');
                }

                // Document info
                $('#kodeRPS').text(data.kode_rps || '-');
                $('#kodeMK').text(data.kode_mk || '-');
                $('#namaMK').text(data.nama_mk || '-');
                $('#sks').text((data.sks_teori || 0) + ' / ' + (data.sks_praktikum || 0) + ' (Total: ' + (data
                    .sks_total || '-') + ')');
                $('#semester').text(data.semester || '-');
                $('#programStudi').text(data.nama_program_studi ? data.nama_program_studi + ' (' + (data
                    .kd_program_studi || '') + ')' : '-');

                // Validation info
                $('#waktuVerifikasi').text(fmtDate(data.waktu_verifikasi) || '-');
                $('#tokenValid').html(data.token_valid ?
                    '<span style="color: #059669; font-weight: 600;"><i class="fas fa-check-circle mr-1"></i>Ya</span>' :
                    '<span style="color: #dc2626; font-weight: 600;"><i class="fas fa-times-circle mr-1"></i>Tidak</span>'
                );
                $('#tokenUsed').html(data.token_sudah_digunakan ?
                    '<span style="color: #059669; font-weight: 600;"><i class="fas fa-check-circle mr-1"></i>Ya</span>' :
                    '<span style="color: #dc2626; font-weight: 600;"><i class="fas fa-times-circle mr-1"></i>Tidak</span>'
                );
                $('#tglUpdated').text(fmtDate(data.tgl_updated) || '-');
                $('#namaVerifikator').text(data.nama_verifikator || 'Belum ada verifikator');

                // Timeline
                const events = [];
                if (data.verf_action_time_tim_dosen) events.push({
                    label: 'Tim Dosen - Penugasan',
                    time: data.verf_action_time_tim_dosen
                });
                if (data.verf_action_time_ko_rmk) events.push({
                    label: 'Ko. RMK - Verifikasi',
                    time: data.verf_action_time_ko_rmk
                });
                if (data.verf_action_time_kaprodi) events.push({
                    label: 'Kaprodi - Validasi Final',
                    time: data.verf_action_time_kaprodi
                });

                $('#verifTimeline').empty();
                if (events.length) {
                    events.forEach(it => {
                        $('#verifTimeline').append(
                            '<div class="timeline-item">' +
                            '<div class="timeline-title">' + it.label + '</div>' +
                            '<div class="timeline-time">' + fmtDate(it.time) + '</div>' +
                            '</div>'
                        );
                    });
                } else {
                    $('#verifTimeline').html(
                        '<div class="empty-state">' +
                        '<i class="fas fa-clock"></i>' +
                        '<p>Belum ada riwayat verifikasi</p>' +
                        '</div>'
                    );
                }

                // Daftar verifikator
                let daftar = null;
                try {
                    if (data.daftar_verifikator) {
                        daftar = typeof data.daftar_verifikator === 'string' ?
                            JSON.parse(data.daftar_verifikator) : data.daftar_verifikator;
                    }
                } catch (e) {
                    daftar = null;
                }

                if (daftar && (daftar.dosen_pengembang || daftar.ko_rmk || daftar.kaprodi)) {
                    let html = '';

                    if (daftar.dosen_pengembang) {
                        html += '<div class="verifikator-section">' +
                            '<h4>Dosen Pengembang</h4>' +
                            '<p style="color: #374151; margin: 0;">' + daftar.dosen_pengembang + '</p>' +
                            '</div>';
                    }

                    if (daftar.ko_rmk && Array.isArray(daftar.ko_rmk) && daftar.ko_rmk.length) {
                        html += '<div class="verifikator-section">' +
                            '<h4>Ko. RMK</h4>' +
                            '<ul class="verifikator-list">';
                        daftar.ko_rmk.forEach(v => html += '<li>' + (v.nama || '-') + '</li>');
                        html += '</ul></div>';
                    }

                    if (daftar.kaprodi && Array.isArray(daftar.kaprodi) && daftar.kaprodi.length) {
                        html += '<div class="verifikator-section">' +
                            '<h4>Kaprodi</h4>' +
                            '<ul class="verifikator-list">';
                        daftar.kaprodi.forEach(v => html += '<li>' + (v.nama || '-') + '</li>');
                        html += '</ul></div>';
                    }

                    $('#daftarVerifikatorContainer').html(html);
                    $('#verifikatorCard').show();
                } else {
                    $('#verifikatorCard').hide();
                }

                $('#mainContent').show();
                $('#errorContent').hide();
            }

            function fetch(token) {
                if (!token) {
                    showError('Token/Kode dokumen tidak ditemukan pada URL.');
                    return;
                }

                $.ajax({
                    url: '/validasi/dokumen-digital/check',
                    method: 'POST',
                    data: {
                        token: token
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if (res && (res.status === 1 || res.status === '1')) {
                            render(res, token);
                        } else {
                            const msg = res && (res.keterangan || res.message) ?
                                (res.keterangan || res.message) :
                                'Token tidak valid atau tidak ditemukan.';
                            showError(msg);
                        }
                    },
                    error: function(xhr) {
                        let msg = 'Terjadi kesalahan saat mengambil data.';
                        try {
                            const r = xhr.responseJSON;
                            if (r && r.keterangan) msg = r.keterangan;
                            else if (xhr.statusText) msg = xhr.statusText;
                        } catch (e) {}
                        showError(msg);
                    }
                });
            }

            $(document).on('click', '#btnPrint', function() {
                window.print();
            });

            $(function() {
                fetch(token);
            });
        })();
    </script>
</body>

</html>
