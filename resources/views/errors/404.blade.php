<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="Sistem informasi terpadu; Siakad Mandala - Halaman tidak ditemukan">
    <meta name="author" content="Bdtask | UIJ">
    <title>404 | SISTER - UIJ</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('image/logo-uij.png') }}">
    <!-- Bootstrap & Font Awesome (CDN fallback cepat) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(145deg, #f8faff 0%, #f0f3fd 100%);
            font-family: 'Segoe UI', 'Poppins', system-ui, -apple-system, 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }

        /* wadah utama dengan sentuhan modern */
        .error-container {
            text-align: center;
            padding: 2rem 1.5rem;
            max-width: 750px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(1px);
            border-radius: 60px 60px 30px 30px;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.1);
        }

        /* SVG wrapper - animasi subtle */
        .svg-illustration {
            margin-bottom: 1.5rem;
        }

        /* teks modern */
        .error-code {
            font-weight: 800;
            font-size: 5rem;
            background: linear-gradient(135deg, #1e2b6e, #2a3f8f);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -1px;
            margin-top: 0;
            margin-bottom: 0.5rem;
        }

        .error-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #1f2a4e;
            margin-bottom: 0.75rem;
        }

        .error-message {
            color: #5a6b8c;
            font-size: 1rem;
            max-width: 450px;
            margin: 0 auto 1.8rem auto;
            line-height: 1.5;
        }

        .btn-home {
            background: #2c3e91;
            border: none;
            padding: 12px 28px;
            font-weight: 500;
            border-radius: 40px;
            color: white;
            transition: all 0.25s ease;
            box-shadow: 0 8px 18px rgba(44, 62, 145, 0.2);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            font-size: 1rem;
        }

        .btn-home:hover {
            background: #1e2b6e;
            transform: translateY(-2px);
            box-shadow: 0 12px 22px rgba(0, 0, 0, 0.12);
            color: white;
        }

        /* style untuk menyesuaikan SVG internal */
        .st0 {
            fill: #EFF3FE;
        }

        .st1 {
            fill: #FFFFFF;
        }

        .st2 {
            fill: #2A3F8F;
        }

        .st3 {
            fill: #445DA1;
        }

        .st4 {
            fill: #FB9C58;
        }

        .st5 {
            fill: #FFD166;
        }

        .st6 {
            fill: #FF7A45;
        }

        .st7 {
            fill: #F0F4FF;
        }

        .st8 {
            fill: #C0D4FF;
        }

        .st9 {
            fill: #233674;
        }

        /* animasi floating untuk roket/awan */
        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-8px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .float-item {
            animation: float 4s ease-in-out infinite;
        }

        .float-delayed {
            animation: float 5s ease-in-out infinite 0.8s;
        }

        /* responsif */
        @media (max-width: 576px) {
            .error-code {
                font-size: 3.5rem;
            }

            .error-title {
                font-size: 1.5rem;
            }

            .btn-home {
                padding: 10px 22px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="error-container">
            <div class="svg-illustration">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 380" width="100%" height="auto"
                    style="max-width: 550px; margin:0 auto;">
                    <defs>
                        <linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#EFF3FE" />
                            <stop offset="100%" stop-color="#E4EBFB" />
                        </linearGradient>
                        <linearGradient id="blueGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#2A3F8F" />
                            <stop offset="100%" stop-color="#445DA1" />
                        </linearGradient>
                        <filter id="shadow" x="-5%" y="-5%" width="115%" height="115%">
                            <feDropShadow dx="0" dy="5" stdDeviation="8" flood-color="#a0b4e0"
                                flood-opacity="0.3" />
                        </filter>
                    </defs>

                    <!-- Background lembut -->
                    <rect width="600" height="380" fill="url(#bgGrad)" rx="32" ry="32" />

                    <!-- Awan / elemen latar -->
                    <path class="float-item"
                        d="M80,280 Q95,262 117,268 Q133,260 152,266 Q168,258 180,268 Q192,272 186,285 Q178,298 160,295 L100,296 Q82,295 78,285 Q72,274 80,280Z"
                        fill="#FFFFFF" opacity="0.7" />
                    <path class="float-delayed"
                        d="M480,110 Q492,96 510,101 Q524,94 538,102 Q552,108 548,119 Q540,128 524,126 L490,127 Q476,124 474,116 Q470,107 480,110Z"
                        fill="#FFFFFF" opacity="0.6" />

                    <!-- Layer papan / layar besar (seperti dashboard akademik) -->
                    <rect x="125" y="90" width="350" height="210" rx="28" fill="white"
                        filter="url(#shadow)" />
                    <rect x="145" y="112" width="310" height="165" rx="14" fill="#F8FAFF" stroke="#E2E9FC"
                        stroke-width="1.5" />

                    <!-- bar judul layar -->
                    <circle cx="160" cy="120" r="5" fill="#FF5F5E" />
                    <circle cx="180" cy="120" r="5" fill="#FFBD40" />
                    <circle cx="200" cy="120" r="5" fill="#2DCF6E" />
                    <!-- garis header -->
                    <line x1="240" y1="120" x2="425" y2="120" stroke="#D0DDF5" stroke-width="4"
                        stroke-linecap="round" />

                    <!-- isi layar: angka 404 dengan desain modern custom -->
                    <g transform="translate(45, 45)">
                        <!-- digit '4' pertama -->
                        <path d="M80,85 L80,28 L48,85 L80,85" fill="none" stroke="#2A3F8F" stroke-width="12"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="80" y1="28" x2="80" y2="110" stroke="#2A3F8F"
                            stroke-width="12" stroke-linecap="round" />
                        <line x1="48" y1="85" x2="102" y2="85" stroke="#2A3F8F"
                            stroke-width="10" stroke-linecap="round" />

                        <!-- digit '0' (mata ikon ramah) -->
                        <circle cx="152" cy="73" r="42" fill="none" stroke="#FB9C58"
                            stroke-width="12" />
                        <!-- pupil lucu di angka 0 -->
                        <circle cx="152" cy="73" r="9" fill="#2A3F8F" />
                        <circle cx="148" cy="69" r="3" fill="white" />

                        <!-- digit '4' kedua -->
                        <path d="M240,85 L240,28 L208,85 L240,85" fill="none" stroke="#2A3F8F" stroke-width="12"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="240" y1="28" x2="240" y2="110" stroke="#2A3F8F"
                            stroke-width="12" stroke-linecap="round" />
                        <line x1="208" y1="85" x2="262" y2="85" stroke="#2A3F8F"
                            stroke-width="10" stroke-linecap="round" />
                    </g>

                    <!-- Elemen "roket" atau pesawat kertas -> melambangkan eksplorasi halaman -->
                    <g class="float-item" transform="translate(420, 60)">
                        <path d="M0,25 L20,35 L10,40 L0,25Z" fill="#FF7A45" />
                        <path d="M0,25 L-12,18 L-5,30 L0,25Z" fill="#FFB485" />
                        <rect x="-5" y="38" width="10" height="8" fill="#FFD166" rx="2" />
                        <path d="M-2,48 L2,48 L3,58 L-3,58Z" fill="#FF7A45" />
                        <!-- api -->
                        <path d="M-1,58 Q-5,68 0,74 Q5,68 1,58Z" fill="#FFD166" opacity="0.9" />
                    </g>

                    <!-- Buku kecil terbang (simbol akademik) -->
                    <g transform="translate(95, 260) rotate(-10)">
                        <rect x="0" y="0" width="42" height="30" rx="4" fill="#FFFFFF"
                            stroke="#A0B6E0" stroke-width="2" />
                        <line x1="21" y1="0" x2="21" y2="30" stroke="#A0B6E0"
                            stroke-width="2" />
                        <line x1="8" y1="10" x2="16" y2="10" stroke="#C0D4FF"
                            stroke-width="2" stroke-linecap="round" />
                        <line x1="8" y1="18" x2="16" y2="18" stroke="#C0D4FF"
                            stroke-width="2" stroke-linecap="round" />
                        <line x1="28" y1="10" x2="36" y2="10" stroke="#C0D4FF"
                            stroke-width="2" stroke-linecap="round" />
                        <line x1="28" y1="18" x2="36" y2="18" stroke="#C0D4FF"
                            stroke-width="2" stroke-linecap="round" />
                    </g>

                    <!-- lampu sorot (highlight modern) -->
                    <path d="M350,310 L410,310 L390,350 L320,350 Z" fill="#FFD166" opacity="0.25" />
                    <circle cx="370" cy="315" r="6" fill="#FFD166" />

                    <!-- garis orbit abstrak + titik data -->
                    <path d="M50,315 Q150,295 260,310 Q380,325 480,295" fill="none" stroke="#B8CCF0"
                        stroke-width="3" stroke-dasharray="6 6" />
                    <circle cx="120" cy="308" r="4" fill="#2A3F8F" opacity="0.6" />
                    <circle cx="200" cy="304" r="5" fill="#FB9C58" opacity="0.7" />
                    <circle cx="340" cy="318" r="4.5" fill="#445DA1" opacity="0.6" />

                    <!-- kutipan kecil pesan hilang -->
                    <text x="300" y="355" font-family="'Segoe UI', system-ui" font-size="12" fill="#7B8FB0"
                        text-anchor="middle" font-style="italic">halaman tidak ditemukan ~ menjelajah lebih
                        jauh</text>
                </svg>
            </div>

            <!-- pesan error -->
            <div class="error-code">404</div>
            <div class="error-title">Halaman Tidak Ditemukan</div>
            <div class="error-message">
                Maaf, halaman yang Anda tuju sedang dalam perbaikan, telah dipindahkan, atau tidak tersedia.
                Kembali ke beranda untuk melanjutkan aktivitas akademik.
            </div>
            <a href="{{ route('frontpage.home') }}" class="btn-home">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

    <!-- Script minimal untuk efek smooth -->
    <script>
        // Tidak ada interaksi berat, namun menambahkan sedikit konsistensi jika diperlukan
        document.querySelectorAll('.btn-home').forEach(btn => {
            btn.addEventListener('mouseenter', (e) => {
                e.currentTarget.style.transform = 'translateY(-2px)';
            });
            btn.addEventListener('mouseleave', (e) => {
                e.currentTarget.style.transform = 'translateY(0px)';
            });
        });
    </script>
    <!-- Optional: tambahkan Bootstrap JS untuk kompatibilitas interaksi (tidak wajib untuk tampilan) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
