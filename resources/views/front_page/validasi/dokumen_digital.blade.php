<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistem informasi terpadu; Siakad Mandala">
    <meta name="author" content="Bdtask">
    <title>SIPADU | Mandala &mdash; Hasil Validasi Dokumen</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('image/logo-mandala.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--Global Styles(used by all pages)-->
    <link href="{{ asset('adminpage/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/metisMenu/metisMenu.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/fontawesome/css/all.min. css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/typicons/src/typicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminpage/assets/plugins/themify-icons/themify-icons.min.css') }}" rel="stylesheet">
    <!--Start Your Custom Style Now-->
    <link href="{{ asset('adminpage/assets/dist/css/style.css') }}" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .validation-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .validation-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .validation-header {
            padding: 50px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .validation-header. valid {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .validation-header. invalid {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        }

        .validation-icon {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            animation: scaleIn 0.5s ease;
        }

        . validation-icon i {
            font-size: 60px;
            color: white;
        }

        @keyframes scaleIn {
            0% {
                transform: scale(0);
            }

            50% {
                transform: scale(1. 1);
            }

            100% {
                transform: scale(1);
            }
        }

        .validation-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .validation-message {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.1rem;
            margin: 0;
        }

        .validation-body {
            padding: 40px;
        }

        . section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #667eea;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 12px;
            color: #667eea;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .info-label {
            font-size: 0.85rem;
            color: #7f8c8d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .info-label i {
            margin-right: 8px;
            font-size: 1rem;
        }

        .info-value {
            font-size: 1.1rem;
            color: #2c3e50;
            font-weight: 600;
        }

        .signature-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 35px;
            border-radius: 15px;
            text-align: center;
            margin-top: 30px;
            position: relative;
            overflow: hidden;
        }

        .signature-box::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
        }

        .signature-box . signature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .signature-role {
            font-size: 0.95rem;
            opacity: 0.9;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .signature-name {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .creator-info {
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            padding-top: 20px;
            margin-top: 20px;
        }

        .creator-label {
            font-size: 0.85rem;
            opacity: 0.8;
            margin-bottom: 5px;
        }

        . creator-name {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .metadata-section {
            background: #ecf0f1;
            padding: 25px;
            border-radius: 12px;
            margin-top: 30px;
        }

        .metadata-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #bdc3c7;
        }

        .metadata-row:last-child {
            border-bottom: none;
        }

        .metadata-label {
            font-size: 0.9rem;
            color: #7f8c8d;
            font-weight: 600;
        }

        .metadata-value {
            font-size: 0.95rem;
            color: #2c3e50;
            font-weight: 600;
        }

        . uuid-box {
            background: #34495e;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: center;
            word-break: break-all;
            font-family: 'Courier New', monospace;
        }

        .uuid-label {
            font-size: 0.8rem;
            opacity: 0.7;
            margin-bottom: 8px;
        }

        .uuid-value {
            font-size: 0.95rem;
            letter-spacing: 1px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .status-badge.final {
            background: #27ae60;
            color: white;
        }

        . document-code-badge {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 12px 25px;
            border-radius: 50px;
            font-size: 1. 2rem;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(245, 87, 108, 0.3);
        }

        .back-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
            margin-top: 20px;
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        .print-button {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
            padding: 12px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
            margin-top: 20px;
            margin-left: 10px;
        }

        . print-button:hover {
            background: #667eea;
            color: white;
            text-decoration: none;
        }

        @media print {
            body {
                background: white;
            }

            . back-button,
            .print-button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="validation-container">
        <div class="validation-card">
            <!-- Header Section - Dynamic based on validation status -->
            <div class="validation-header {{ $isValid ? 'valid' : 'invalid' }}">
                <div class="validation-icon">
                    <i class="fas {{ $isValid ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                </div>
                <h1 class="validation-title">
                    {{ $isValid ? 'Dokumen Valid' : 'Dokumen Tidak Valid' }}
                </h1>
                <p class="validation-message">{{ $validationMessage }}</p>
            </div>

            <!-- Body Section -->
            <div class="validation-body">
                @if ($isValid)
                    <!-- Document Code Badge -->
                    <div class="text-center">
                        <div class="document-code-badge">
                            <i class="fas fa-barcode mr-2"></i>
                            {{ $documentData['document_code'] }}
                        </div>
                    </div>

                    <!-- Informasi Mata Kuliah -->
                    <div class="section-title">
                        <i class="fas fa-book-open"></i>
                        Informasi Mata Kuliah
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-book"></i>
                                Nama Mata Kuliah
                            </div>
                            <div class="info-value">{{ $documentData['course_name'] }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-hashtag"></i>
                                Kode Mata Kuliah
                            </div>
                            <div class="info-value">{{ $documentData['course_code'] }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calculator"></i>
                                SKS
                            </div>
                            <div class="info-value">{{ $documentData['credits'] }} SKS</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-layer-group"></i>
                                Semester
                            </div>
                            <div class="info-value">Semester {{ $documentData['semester'] }}</div>
                        </div>
                    </div>

                    <!-- Informasi Program Studi -->
                    <div class="section-title">
                        <i class="fas fa-graduation-cap"></i>
                        Informasi Program Studi
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-university"></i>
                                Program Studi
                            </div>
                            <div class="info-value">{{ $documentData['study_program_name'] }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-id-card"></i>
                                Kode Program Studi
                            </div>
                            <div class="info-value">{{ $documentData['study_program_code'] }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-award"></i>
                                Jenjang
                            </div>
                            <div class="info-value">{{ $documentData['education_level'] }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calendar"></i>
                                Periode
                            </div>
                            <div class="info-value">{{ $documentData['period'] }}</div>
                        </div>
                    </div>

                    <!-- Status Dokumen -->
                    <div class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Status Dokumen
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-flag-checkered"></i>
                            Status
                        </div>
                        <div class="info-value">
                            <span class="status-badge final">{{ $documentData['status'] }}</span>
                        </div>
                    </div>

                    <!-- Signature Section -->
                    <div class="signature-box">
                        <div class="signature-icon">
                            <i class="fas fa-pen-fancy"></i>
                        </div>
                        <div class="signature-role">{{ $documentData['signer_role'] }}</div>
                        <div class="signature-name">{{ $documentData['signer_name'] }}</div>

                        <div class="creator-info">
                            <div class="creator-label">Dibuat Oleh:</div>
                            <div class="creator-name">{{ $documentData['creator_name'] }}</div>
                        </div>
                    </div>

                    <!-- Metadata Section -->
                    <div class="metadata-section">
                        <div class="metadata-row">
                            <span class="metadata-label">
                                <i class="fas fa-clock mr-2"></i>Tanggal Dibuat
                            </span>
                            <span class="metadata-value">{{ $documentData['created_at'] }}</span>
                        </div>
                        <div class="metadata-row">
                            <span class="metadata-label">
                                <i class="fas fa-check-double mr-2"></i>Tanggal Diverifikasi
                            </span>
                            <span class="metadata-value">{{ $documentData['verified_at'] }}</span>
                        </div>
                    </div>

                    <!-- UUID -->
                    <div class="uuid-box">
                        <div class="uuid-label">DOCUMENT UUID</div>
                        <div class="uuid-value">{{ $documentData['uuid'] }}</div>
                    </div>
                @else
                    <!-- Invalid Document Message -->
                    <div class="alert alert-danger text-center" style="font-size: 1.1rem;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ $validationMessage }}
                    </div>

                    <div class="text-center mt-4">
                        <p style="font-size: 1rem; color: #7f8c8d;">
                            Dokumen yang Anda coba validasi tidak ditemukan dalam sistem kami atau telah
                            kedaluwarsa.
                            Silakan hubungi pihak yang mengeluarkan dokumen untuk informasi lebih lanjut.
                        </p>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="text-center">
                    <a href="#" class="back-button">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Halaman Utama
                    </a>
                    @if ($isValid)
                        <button onclick="window.print()" class="print-button">
                            <i class="fas fa-print mr-2"></i>
                            Cetak Hasil Validasi
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!--Global script(used by all pages)-->
    <script src="{{ asset('adminpage/assets/plugins/jQuery/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/dist/js/popper.min.js') }}"></script>
    <script src="{{ asset('adminpage/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
</body>

</html>
