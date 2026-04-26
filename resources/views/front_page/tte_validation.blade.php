<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTER | Dokumen Digital</title>
    <link rel="shortcut icon" href="{{ asset('image/logo-uij.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg: #f4f6f8;
            --card: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --line: #e5e7eb;

            --primary: #0f5c5f;
            --primary-dark: #0b494b;

            --success-bg: #eaf7f0;
            --success-text: #166534;
            --success-line: #bbdfc9;

            --danger-bg: #fef2f2;
            --danger-text: #b91c1c;
            --danger-line: #fecaca;

            --warning-bg: #fff7ed;
            --warning-text: #9a3412;
            --warning-line: #fed7aa;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: "Segoe UI", Inter, -apple-system, Roboto, sans-serif;
            min-height: 100vh;
            padding: 16px;

            display: flex;
            align-items: center;
            /* vertical center */
            justify-content: center;
            /* horizontal center */
        }

        .page-wrap {
            max-width: 840px;
            margin: 0 auto;
        }

        .verify-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .verify-head {
            background: #ffffff;
            border-bottom: 1px solid var(--line);
            padding: 26px 24px 20px;
        }

        .head-top {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 8px;
        }

        .logo-box {
            width: 52px;
            height: 52px;
            border: 1px solid var(--line);
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: #fff;
            flex-shrink: 0;
        }

        .logo-box img {
            width: 36px;
            height: 36px;
            object-fit: contain;
        }

        .head-title {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .head-sub {
            margin: 0;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .verify-body {
            padding: 22px 24px 26px;
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            padding: 8px 14px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1px solid transparent;
        }

        .status-chip.success {
            background: var(--success-bg);
            color: var(--success-text);
            border-color: var(--success-line);
        }

        .status-chip.error {
            background: var(--danger-bg);
            color: var(--danger-text);
            border-color: var(--danger-line);
        }

        .detail-box {
            margin-top: 18px;
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 12px;
            padding: 14px 16px;
            border-bottom: 1px solid var(--line);
            align-items: start;
            background: #fff;
        }

        .detail-row:last-child {
            border-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: #334155;
            font-size: 0.92rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail-label i {
            width: 18px;
            text-align: center;
            color: var(--primary);
        }

        .detail-value {
            font-size: 0.95rem;
            color: #111827;
            word-break: break-word;
            line-height: 1.5;
            font-weight: 500;
        }

        .detail-value.empty {
            color: #94a3b8;
            font-weight: 400;
        }

        .notice {
            margin-top: 16px;
            border: 1px solid var(--warning-line);
            background: var(--warning-bg);
            color: var(--warning-text);
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 0.88rem;
            line-height: 1.5;
        }

        .notice i {
            margin-right: 6px;
        }

        .actions {
            margin-top: 22px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn-uij-primary {
            background: var(--primary);
            color: #fff;
            border: 1px solid var(--primary);
            border-radius: 10px;
            padding: 9px 16px;
            font-size: 0.86rem;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .btn-uij-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-uij-outline {
            background: #fff;
            color: #334155;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 9px 16px;
            font-size: 0.86rem;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .btn-uij-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-1px);
        }

        .credits {
            text-align: center;
            margin-top: 14px;
            color: #64748b;
            font-size: 0.75rem;
        }

        @media (max-width: 768px) {

            .verify-head,
            .verify-body {
                padding-left: 16px;
                padding-right: 16px;
            }

            .detail-row {
                grid-template-columns: 1fr;
                gap: 6px;
                padding: 12px 14px;
            }

            .head-title {
                font-size: 1.05rem;
            }
        }
    </style>
</head>

<body>
    <div class="page-wrap">
        <div class="verify-card">
            <div class="verify-head">
                <div class="head-top">
                    <div class="logo-box">
                        <img src="{{ asset('image/logo-uij.png') }}" alt="Logo UIJ">
                    </div>
                    <div>
                        <h1 class="head-title">Verifikasi Dokumen Digital</h1>
                        <p class="head-sub">Arsip digital terintegrasi Universitas Islam Jember</p>
                    </div>
                </div>
            </div>

            <div class="verify-body">
                @if ($data->status == 0)
                    <span class="status-chip error">
                        <i class="fas fa-circle-xmark"></i> Dokumen Tidak Ditemukan
                    </span>
                @else
                    <span class="status-chip success">
                        <i class="fas fa-circle-check"></i> Dokumen Tervalidasi
                    </span>
                @endif

                <div class="detail-box">
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-file-lines"></i> Nama Dokumen</div>
                        <div
                            class="detail-value {{ empty($data->nama_dokumen) || $data->nama_dokumen == '-' ? 'empty' : '' }}">
                            {{ !empty($data->nama_dokumen) && $data->nama_dokumen != '-' ? $data->nama_dokumen : 'Tidak Tersedia' }}
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-user-pen"></i> Penandatangan</div>
                        <div
                            class="detail-value {{ empty($data->nama_penandatangan) || $data->nama_penandatangan == '-' ? 'empty' : '' }}">
                            {{ !empty($data->nama_penandatangan) && $data->nama_penandatangan != '-' ? $data->nama_penandatangan : 'Tidak Tersedia' }}
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-calendar-check"></i> Tanggal Persetujuan</div>
                        <div class="detail-value {{ empty($data->tgl_persetujuan) ? 'empty' : '' }}">
                            {{ !empty($data->tgl_persetujuan) ? $data->tgl_persetujuan : 'Tidak Tersedia' }}
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-user-tag"></i> Peran / Jabatan</div>
                        <div class="detail-value {{ empty($data->nama_peran) ? 'empty' : '' }}">
                            {{ !empty($data->nama_peran) ? $data->nama_peran : 'Tidak Tersedia' }}
                        </div>
                    </div>
                </div>

                @if ($data->status == 0)
                    <div class="notice">
                        <i class="fas fa-triangle-exclamation"></i>
                        Dokumen tidak ditemukan di database UIJ. Pastikan kode verifikasi benar, atau hubungi bagian
                        akademik.
                    </div>
                @endif

                <div class="actions">
                    <button class="btn btn-uij-primary" onclick="window.location.reload()">
                        <i class="fas fa-rotate-right me-1"></i> Cek Ulang
                    </button>
                    <a href="{{ route('frontpage.home') }}" class="btn btn-uij-outline">
                        <i class="fas fa-house me-1"></i> Halaman Utama
                    </a>
                </div>
            </div>
        </div>

        <div class="credits">
            <i class="fas fa-shield-halved me-1"></i> Data bersumber dari sistem informasi terpadu SISTER UIJ
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-uij-primary').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (this.getAttribute('onclick') === "window.location.reload()") {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memuat...';
                    setTimeout(() => window.location.reload(), 140);
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>
