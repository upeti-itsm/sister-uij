<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Dokumen Digital</title>
    <link rel="shortcut icon" href="{{ asset('image/logo-uij.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-image: url('{{ asset('images/bg_login.webp') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
        }

        /* Overlay hijau transparan di atas background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(17, 153, 142, 0.7) 0%, rgba(56, 239, 125, 0.7) 100%);
            z-index: 0;
        }

        .container {
            padding: 50px 15px;
            position: relative;
            z-index: 1;
        }

        .document-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            max-width: 600px;
            margin: 0 auto;
        }

        .card-header-custom {
            padding: 30px;
            text-align: center;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            position: relative;
        }

        .logo-container {
            margin-bottom: 20px;
        }

        .logo-container img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            background: white;
            padding: 10px;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .card-header-custom i {
            font-size: 60px;
            margin-bottom: 15px;
        }

        .card-body-custom {
            padding: 40px;
        }

        .status-badge {
            display: inline-block;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 20px;
            animation: fadeInScale 0.5s ease-in-out;
        }

        @keyframes fadeInScale {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .status-error {
            background-color: #fee;
            color: #c33;
            border: 2px solid #fcc;
        }

        .status-success {
            background-color: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }

        .info-row {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.3s ease;
        }

        .info-row:hover {
            background-color: #f8f9fa;
            padding-left: 10px;
            padding-right: 10px;
            border-radius: 8px;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #11998e;
            min-width: 180px;
            display: flex;
            align-items: center;
        }

        .info-label i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            color: #38ef7d;
        }

        .info-value {
            color: #555;
            flex: 1;
        }

        .empty-value {
            color: #999;
            font-style: italic;
        }

        .alert-custom {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0d7a6f 0%, #2dd165 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(17, 153, 142, 0.3);
        }

        .btn-outline-secondary {
            border-color: #11998e;
            color: #11998e;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background: #11998e;
            border-color: #11998e;
            color: white;
            transform: translateY(-2px);
        }

        .document-card {
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .info-row {
                flex-direction: column;
            }

            .info-label {
                min-width: 100%;
                margin-bottom: 5px;
            }

            .card-body-custom {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="document-card">
        <!-- Header -->
        <div class="card-header-custom">
            <!-- Logo -->
            <div class="logo-container">
                <img src="{{ asset('image/logo-uij.png') }}" alt="Logo UIJ">
            </div>
            <h2 class="mb-0">Verifikasi Dokumen Digital</h2>
            <p class="mb-0 mt-2">Berdasarkan Data Dokumen Digital Milik Uinversitas Islam Jember</p>
        </div>

        <!-- Body -->
        <div class="card-body-custom">
            <!-- Status Badge -->
            <div class="text-center">
                @if($data->status == 0)
                    <span class="status-badge status-error">
                        <i class="fas fa-times-circle"></i> Dokumen Tidak Ditemukan
                    </span>
                @else
                    <span class="status-badge status-success">
                        <i class="fas fa-check-circle"></i> Dokumen Valid
                    </span>
                @endif
            </div>

            <!-- Info Details -->
            <div class="mt-4">
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-info-circle"></i> Status Kode
                    </div>
                    <div class="info-value">
                        <strong>{{ $data->status }}</strong>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-clipboard-list"></i> Keterangan
                    </div>
                    <div class="info-value">
                        {{ $data->keterangan }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-file-alt"></i> Nama Dokumen
                    </div>
                    <div class="info-value {{ $data->nama_dokumen == '-' ? 'empty-value' : '' }}">
                        {{ $data->nama_dokumen == '-' ? 'Tidak Tersedia' : $data->nama_dokumen }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-user-edit"></i> Penandatangan
                    </div>
                    <div class="info-value {{ $data->nama_penandatangan == '-' ? 'empty-value' :  '' }}">
                        {{ $data->nama_penandatangan == '-' ? 'Tidak Tersedia' : $data->nama_penandatangan }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-calendar-check"></i> Tanggal Persetujuan
                    </div>
                    <div class="info-value {{ empty($data->tgl_persetujuan) ? 'empty-value' : '' }}">
                        {{ empty($data->tgl_persetujuan) ? 'Tidak Tersedia' :  $data->tgl_persetujuan }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-user-tag"></i> Peran
                    </div>
                    <div class="info-value {{ empty($data->nama_peran) ? 'empty-value' : '' }}">
                        {{ empty($data->nama_peran) ? 'Tidak Tersedia' : $data->nama_peran }}
                    </div>
                </div>
            </div>

            <!-- Alert Message -->
            @if($data->status == 0)
                <div class="alert-custom">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Perhatian: </strong> Dokumen tidak ditemukan atau tidak valid di database UIJ.
                    Silakan periksa kembali nomor dokumen atau hubungi administrator.
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <button class="btn btn-primary" onclick="window.location.reload()">
                    <i class="fas fa-redo"></i> Cek Ulang
                </button>
                <a href="{{route('frontpage.home')}}" class="btn btn-outline-secondary">
                    <i class="fas fa-home"></i> Beranda
                </a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
