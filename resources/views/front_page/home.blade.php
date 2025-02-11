<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>SISTER | HOME</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{asset('image/logo-uij.png')}}" rel="icon">
    <link href="{{asset('image/logo-uij.png')}}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i"
        rel="stylesheet">
    <!-- Vendor CSS Files -->
    <link href="{{asset('frontpage/assets/vendor/aos/aos.css')}}" rel="stylesheet">
    <link href="{{asset('frontpage/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('frontpage/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('frontpage/assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('frontpage/assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
    <link href="{{asset('frontpage/assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{asset('frontpage/assets/css/style.css')}}" rel="stylesheet">

    <!-- =======================================================
    * Template Name: Ninestars - v4.1.0
    * Template URL: https://bootstrapmade.com/ninestars-free-bootstrap-3-theme-for-creative/
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
</head>

<body>
<!-- ======= Header ======= -->
<header id="header" class="fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">
        <div class="logo">
            <!-- <h1 class="text-light"><a href="index.html"><span>Ninestars</span></a></h1> -->
            <!-- Uncomment below if you prefer to use an image logo -->
            <a href="{{route('frontpage.home')}}"><img src="{{asset('image/Logos.png')}}" alt="" class="img-fluid"></a>
        </div>

        <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto active" href="{{route('frontpage.home')}}">Home</a></li>
                <li><a class="nav-link scrollto" href="#about">Pengumuman</a></li>
                <li><a class="nav-link scrollto" href="#services">Panduan</a></li>
                <li><a class="nav-link scrollto" href="#portfolio">Aduan</a></li>
                <li><a class="nav-link scrollto" href="#contact">Kontak</a></li>
                <li><a class="getstarted scrollto" href="{{route('login.login')}}">Masuk</a></li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav><!-- .navbar -->

    </div>
</header><!-- End Header -->

<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
                <h1>Sistem Informasi Terpadu (SISTER) Universitas Islam Jember</h1>
                <h2>Sistem informasi ini digunakan untuk mendukung beberapa kegiatan di Kampus <br>Universitas Islam Jember</h2>
                <div>
                    <a href="{{route('login.login')}}" class="btn-get-started scrollto">Get Started</a>
                </div>
            </div>
            <div class="col-lg-6 order-1 order-lg-2 hero-img">
                <img src="{{asset('frontpage/assets/img/uij.svg')}}" class="img-fluid animated" alt="">
            </div>
        </div>
    </div>
</section><!-- End Hero -->

<main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5 d-flex align-items-center justify-content-center about-img">
                    <img src="{{asset('frontpage/assets/img/uij2.svg')}}" class="img-fluid" alt="" data-aos="zoom-in">
                </div>
                <div class="col-lg-6 pt-5 pt-lg-0">
                    <h3 data-aos="fade-up">Keunggulan Layanan SISTER</h3>
                    <p data-aos="fade-up" data-aos-delay="100">
                        Media ini dikembangkan oleh UPTTI Universitas Islam Jember untuk
                        mendukung
                        beberapa serangkaian proses yang ada di Universitas Islam Jember.
                        Layanan ini
                        akan diharapkan akan menjadi media utama (Data Center) UIJ untuk jangka kedepan.

                    </p>
                    <div class="row">
                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                            <i class="bx bx-receipt"></i>
                            <h4>Tangible</h4>
                            <p>Beberapa proses yang ada akan dikemas menjadi satu media untuk mempermudah proses
                                mengakses informasi melalui satu lama website
                            </p>
                        </div>
                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                            <i class="bx bx-cube-alt"></i>
                            <h4>Intagible</h4>
                            <p>kedepan akan membantu proses pengambilan keputusan dikampus Universitas Islam Jember untuk menuju kampus berbasis teknologi informasi</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section><!-- End About Section -->

    <!-- ======= Services Section ======= -->
    <section id="services" class="services section-bg">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2>Services</h2>
                <p>Layanan yang tersedia di SISTER</p>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-3 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
                    <div class="icon-box">
                        <div class="icon"><i class="bx bxl-dribbble"></i></div>
                        <h4 class="title"><a href="">E-Sertifikat LABKOM</a></h4>
                        <p class="description">Pengajuan dan verifikasi usulan sertfikat LABKOM</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="200">
                    <div class="icon-box">
                        <div class="icon"><i class="bx bx-file"></i></div>
                        <h4 class="title"><a href="">Penerimaan Mahasiswa Baru (PMB)</a></h4>
                        <p class="description">Proses monitoring penerimaan mahasiswa Universitas Islam Jember</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="300">
                    <div class="icon-box">
                        <div class="icon"><i class="bx bx-tachometer"></i></div>
                        <h4 class="title"><a href="">Absensi Karyawan/Dosen</a></h4>
                        <p class="description">Proses monitoring Absensi Karyawan/Dosen mahasiswa Universitas Islam Jember</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="400">
                    <div class="icon-box">
                        <div class="icon"><i class="bx bx-world"></i></div>
                        <h4 class="title"><a href="">Keuangan Universitas Islam Jember</a></h4>
                        <p class="description">Pengelolaan Arus KAS Keuangan Universitas Islam Jember</p>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- End Services Section -->

    <!-- ======= Portfolio Section ======= -->
{{--    <section id="portfolio" class="portfolio">--}}
{{--        <div class="container" data-aos="fade-up">--}}
{{--            <div class="section-title">--}}
{{--                <h2>Portfolio</h2>--}}
{{--                <p>Check out our beautifull portfolio</p>--}}
{{--            </div>--}}
{{--            <div class="row" data-aos="fade-up" data-aos-delay="100">--}}
{{--                <div class="col-lg-12">--}}
{{--                    <ul id="portfolio-flters">--}}
{{--                        <li data-filter="*" class="filter-active">All</li>--}}
{{--                        <li data-filter=".filter-app">App</li>--}}
{{--                        <li data-filter=".filter-card">Card</li>--}}
{{--                        <li data-filter=".filter-web">Web</li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">--}}
{{--                <div class="col-lg-4 col-md-6 portfolio-item filter-app">--}}
{{--                    <div class="portfolio-wrap">--}}
{{--                        <img src="{{asset('frontpage/assets/img/portfolio/portfolio-1.jpg')}}" class="img-fluid" alt="">--}}
{{--                        <div class="portfolio-links">--}}
{{--                            <a href="{{asset('frontpage/assets/img/portfolio/portfolio-1.jpg')}}" data-galleryery="portfolioGallery"--}}
{{--                               class="portfolio-lightbox" title="App 1"><i class="bi bi-plus"></i></a>--}}
{{--                            <a href="#" title="More Details"><i class="bi bi-link"></i></a>--}}
{{--                        </div>--}}
{{--                        <div class="portfolio-info">--}}
{{--                            <h4>App 1</h4>--}}
{{--                            <p>App</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-lg-4 col-md-6 portfolio-item filter-web">--}}
{{--                    <div class="portfolio-wrap">--}}
{{--                        <img src="{{asset('frontpage/assets/img/portfolio/portfolio-2.jpg')}}" class="img-fluid" alt="">--}}
{{--                        <div class="portfolio-links">--}}
{{--                            <a href="{{asset('frontpage/assets/img/portfolio/portfolio-2.jpg')}}" data-galleryery="portfolioGallery"--}}
{{--                               class="portfolio-lightbox" title="Web 3"><i class="bi bi-plus"></i></a>--}}
{{--                            <a href="#" title="More Details"><i class="bi bi-link"></i></a>--}}
{{--                        </div>--}}
{{--                        <div class="portfolio-info">--}}
{{--                            <h4>Web 3</h4>--}}
{{--                            <p>Web</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-lg-4 col-md-6 portfolio-item filter-app">--}}
{{--                    <div class="portfolio-wrap">--}}
{{--                        <img src="{{asset('frontpage/assets/img/portfolio/portfolio-3.jpg')}}" class="img-fluid" alt="">--}}
{{--                        <div class="portfolio-links">--}}
{{--                            <a href="{{asset('frontpage/assets/img/portfolio/portfolio-3.jpg')}}" data-galleryery="portfolioGallery"--}}
{{--                               class="portfolio-lightbox" title="App 2"><i class="bi bi-plus"></i></a>--}}
{{--                            <a href="#" title="More Details"><i class="bi bi-link"></i></a>--}}
{{--                        </div>--}}
{{--                        <div class="portfolio-info">--}}
{{--                            <h4>App 2</h4>--}}
{{--                            <p>App</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-lg-4 col-md-6 portfolio-item filter-card">--}}
{{--                    <div class="portfolio-wrap">--}}
{{--                        <img src="{{asset('frontpage/assets/img/portfolio/portfolio-4.jpg')}}" class="img-fluid" alt="">--}}
{{--                        <div class="portfolio-links">--}}
{{--                            <a href="{{asset('frontpage/assets/img/portfolio/portfolio-4.jpg')}}" data-galleryery="portfolioGallery"--}}
{{--                               class="portfolio-lightbox" title="Card 2"><i class="bi bi-plus"></i></a>--}}
{{--                            <a href="#" title="More Details"><i class="bi bi-link"></i></a>--}}
{{--                        </div>--}}
{{--                        <div class="portfolio-info">--}}
{{--                            <h4>Card 2</h4>--}}
{{--                            <p>Card</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-lg-4 col-md-6 portfolio-item filter-web">--}}
{{--                    <div class="portfolio-wrap">--}}
{{--                        <img src="{{asset('frontpage/assets/img/portfolio/portfolio-5.jpg')}}" class="img-fluid" alt="">--}}
{{--                        <div class="portfolio-links">--}}
{{--                            <a href="{{asset('frontpage/assets/img/portfolio/portfolio-5.jpg')}}" data-galleryery="portfolioGallery"--}}
{{--                               class="portfolio-lightbox" title="Web 2"><i class="bi bi-plus"></i></a>--}}
{{--                            <a href="#" title="More Details"><i class="bi bi-link"></i></a>--}}
{{--                        </div>--}}
{{--                        <div class="portfolio-info">--}}
{{--                            <h4>Web 2</h4>--}}
{{--                            <p>Web</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-lg-4 col-md-6 portfolio-item filter-app">--}}
{{--                    <div class="portfolio-wrap">--}}
{{--                        <img src="{{asset('frontpage/assets/img/portfolio/portfolio-6.jpg')}}" class="img-fluid" alt="">--}}
{{--                        <div class="portfolio-links">--}}
{{--                            <a href="{{asset('frontpage/assets/img/portfolio/portfolio-6.jpg')}}" data-galleryery="portfolioGallery"--}}
{{--                               class="portfolio-lightbox" title="App 3"><i class="bi bi-plus"></i></a>--}}
{{--                            <a href="#" title="More Details"><i class="bi bi-link"></i></a>--}}
{{--                        </div>--}}
{{--                        <div class="portfolio-info">--}}
{{--                            <h4>App 3</h4>--}}
{{--                            <p>App</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-lg-4 col-md-6 portfolio-item filter-card">--}}
{{--                    <div class="portfolio-wrap">--}}
{{--                        <img src="{{asset('frontpage/assets/img/portfolio/portfolio-7.jpg')}}" class="img-fluid" alt="">--}}
{{--                        <div class="portfolio-links">--}}
{{--                            <a href="{{asset('frontpage/assets/img/portfolio/portfolio-7.jpg')}}" data-galleryery="portfolioGallery"--}}
{{--                               class="portfolio-lightbox" title="Card 1"><i class="bi bi-plus"></i></a>--}}
{{--                            <a href="#" title="More Details"><i class="bi bi-link"></i></a>--}}
{{--                        </div>--}}
{{--                        <div class="portfolio-info">--}}
{{--                            <h4>Card 1</h4>--}}
{{--                            <p>Card</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-lg-4 col-md-6 portfolio-item filter-card">--}}
{{--                    <div class="portfolio-wrap">--}}
{{--                        <img src="{{asset('frontpage/assets/img/portfolio/portfolio-8.jpg')}}" class="img-fluid" alt="">--}}
{{--                        <div class="portfolio-links">--}}
{{--                            <a href="{{asset('frontpage/assets/img/portfolio/portfolio-8.jpg')}}" data-galleryery="portfolioGallery"--}}
{{--                               class="portfolio-lightbox" title="Card 3"><i class="bi bi-plus"></i></a>--}}
{{--                            <a href="#" title="More Details"><i class="bi bi-link"></i></a>--}}
{{--                        </div>--}}
{{--                        <div class="portfolio-info">--}}
{{--                            <h4>Card 3</h4>--}}
{{--                            <p>Card</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-lg-4 col-md-6 portfolio-item filter-web">--}}
{{--                    <div class="portfolio-wrap">--}}
{{--                        <img src="{{asset('frontpage/assets/img/portfolio/portfolio-9.jpg')}}" class="img-fluid" alt="">--}}
{{--                        <div class="portfolio-links">--}}
{{--                            <a href="{{asset('frontpage/assets/img/portfolio/portfolio-9.jpg')}}" data-galleryery="portfolioGallery"--}}
{{--                               class="portfolio-lightbox" title="Web 3"><i class="bi bi-plus"></i></a>--}}
{{--                            <a href="#" title="More Details"><i class="bi bi-link"></i></a>--}}
{{--                        </div>--}}
{{--                        <div class="portfolio-info">--}}
{{--                            <h4>Web 3</h4>--}}
{{--                            <p>Web</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--            </div>--}}

{{--        </div>--}}
{{--    </section><!-- End Portfolio Section -->--}}

<!-- ======= F.A.Q Section ======= -->
    <section id="faq" class="faq section-bg">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2>F.A.Q</h2>
                <p>Frequently Asked Questions</p>
            </div>
            <ul class="faq-list" data-aos="fade-up" data-aos-delay="100">
                <li>
                    <div data-bs-toggle="collapse" class="collapsed question" href="#faq1">Jika Matkul Tidak Sama antara Siakad dan Elearning, apa yang harus saya lakukan ? <i class="bi bi-chevron-down icon-show"></i><i
                            class="bi bi-chevron-up icon-close"></i></div>
                    <div id="faq1" class="collapse" data-bs-parent=".faq-list">
                        <p>
                            1. Pastikan Jadwal Di Siakad Sudah Muncul<br/>
                            2. Jika jadwal di siakad sudah muncul, masuk ke akun <a href="http://sister.uij.ac.id">SISTER</a> menggunakan nim dan password yg sama dg siakad.<br/>
                            3. Pilih menu Akademik -> Perkuliahan -> Jadwal Kuliah<br/>
                            4. Pastikan Jadwal Yang tampil sesuai dg jadwal di siakad.<br/>
                            5. Jika jadwal yg tampil berbeda, Klik Tombol "Lihat Jadwal" berwarna merah di pojok kanan atas tabel.<br/>
                            6. Tunggu hingga proses sinkronisasi selesai.<br/>
                            7. Logout dan Login lagi dari elearning<br/>
                        </p>
                    </div>
                </li>

                <li>
                    <div data-bs-toggle="collapse" href="#faq2" class="collapsed question">Saya sudah ganti password di SIAKAD, tapi saya tidak bisa masuk di elearning dan sister menggunakan password tersebut <i class="bi bi-chevron-down icon-show"></i><i
                            class="bi bi-chevron-up icon-close"></i></div>
                    <div id="faq2" class="collapse" data-bs-parent=".faq-list">
                        <p>
                            Elearning dan SISTER tidak bisa secara otomatis auto update dengan data siakad, jadi yang harus dilakukan oleh mahasiswa jika sudah melakukan perubahan data (identitas pribadi/password) adalah menghubungi bagian Akademik/IT untuk melakukan Sinkronisasi ulang data
                        </p>
                    </div>
                </li>
            </ul>

        </div>
    </section><!-- End F.A.Q Section -->

    <!-- ======= Team Section ======= -->
    <section id="team" class="team">
        <div class="container">

            <div class="section-title" data-aos="fade-up">
                <h2>Jajaran Pimpinan</h2>
                <p>Pimpinan Universitas Islam Jember</p>
            </div>
            <div class="row">
                <div class="col" data-aos="zoom-in" data-aos-delay="100">
                    <div class="member">
                        <img src="{{asset('image/pimpinan/fix/2.png')}}" class="img-fluid" alt="">
                        <div class="member-info">
                            <div class="member-info-content">
                                <h4>Dr. Ahmad Halid, S.Pd.I., M.Pd.I</h4>
                                <span>Rektor UIJ</span>
                            </div>
                            <div class="social">
                                <a href=""><i class="bi bi-twitter"></i></a>
                                <a href=""><i class="bi bi-facebook"></i></a>
                                <a href=""><i class="bi bi-instagram"></i></a>
                                <a href=""><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-aos="zoom-in" data-aos-delay="200">
                    <div class="member">
                        <img src="{{asset('image/pimpinan/fix/12.png')}}" class="img-fluid" alt="">
                        <div class="member-info">
                            <div class="member-info-content">
                                <h4>Arifin Nur Budiono, S.Pd, M.Si</h4>
                                <span>Wakil Rektor 1 UIJ</span>
                            </div>
                            <div class="social">
                                <a href=""><i class="bi bi-twitter"></i></a>
                                <a href=""><i class="bi bi-facebook"></i></a>
                                <a href=""><i class="bi bi-instagram"></i></a>
                                <a href=""><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-aos="zoom-in" data-aos-delay="300">
                    <div class="member">
                        <img src="{{asset('image/pimpinan/fix/3.png')}}" class="img-fluid" alt="">
                        <div class="member-info">
                            <div class="member-info-content">
                                <h4>Nanang Tribudiman, S.H., M.Hum</h4>
                                <span>Wakil Rektor 2 UIJ</span>
                            </div>
                            <div class="social">
                                <a href=""><i class="bi bi-twitter"></i></a>
                                <a href=""><i class="bi bi-facebook"></i></a>
                                <a href=""><i class="bi bi-instagram"></i></a>
                                <a href=""><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-aos="zoom-in" data-aos-delay="400">
                    <div class="member">
                        <img src="{{asset('image/pimpinan/fix/4.png')}}" class="img-fluid" alt="">
                        <div class="member-info">
                            <div class="member-info-content">
                                <h4>Dr. Nuzzulul Ulum, M.Pd.I</h4>
                                <span>Wakil Rektor 3 UIJ</span>
                            </div>
                            <div class="social">
                                <a href=""><i class="bi bi-twitter"></i></a>
                                <a href=""><i class="bi bi-facebook"></i></a>
                                <a href=""><i class="bi bi-instagram"></i></a>
                                <a href=""><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-aos="zoom-in" data-aos-delay="400">
                    <div class="member">
                        <img src="{{asset('image/pimpinan/fix/1.png')}}" class="img-fluid" alt="">
                        <div class="member-info">
                            <div class="member-info-content">
                                <h4>Achmad Ilyasi, S.Pd., M.Ap</h4>
                                <span>Ka. Biro UIJ</span>
                            </div>
                            <div class="social">
                                <a href=""><i class="bi bi-twitter"></i></a>
                                <a href=""><i class="bi bi-facebook"></i></a>
                                <a href=""><i class="bi bi-instagram"></i></a>
                                <a href=""><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col" data-aos="zoom-in" data-aos-delay="400">
                    <div class="member">
                        <img src="{{asset('image/pimpinan/fix/5.png')}}" class="img-fluid" alt="">
                        <div class="member-info">
                            <div class="member-info-content">
                                <h4>Endang Sri Wahyuni, M.P.</h4>
                                <span>Dekan Faperta</span>
                            </div>
                            <div class="social">
                                <a href=""><i class="bi bi-twitter"></i></a>
                                <a href=""><i class="bi bi-facebook"></i></a>
                                <a href=""><i class="bi bi-instagram"></i></a>
                                <a href=""><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-aos="zoom-in" data-aos-delay="400">
                    <div class="member">
                        <img src="{{asset('image/pimpinan/fix/6.png')}}" class="img-fluid" alt="">
                        <div class="member-info">
                            <div class="member-info-content">
                                <h4>Izzul Ashlah, S.E., M.Akun.</h4>
                                <span>Dekan FEBI</span>
                            </div>
                            <div class="social">
                                <a href=""><i class="bi bi-twitter"></i></a>
                                <a href=""><i class="bi bi-facebook"></i></a>
                                <a href=""><i class="bi bi-instagram"></i></a>
                                <a href=""><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-aos="zoom-in" data-aos-delay="400">
                    <div class="member">
                        <img src="{{asset('image/pimpinan/fix/7.png')}}" class="img-fluid" alt="">
                        <div class="member-info">
                            <div class="member-info-content">
                                <h4>Dewi Rakhmawati S.ST., M.Kes</h4>
                                <span>Dekan FIKES</span>
                            </div>
                            <div class="social">
                                <a href=""><i class="bi bi-twitter"></i></a>
                                <a href=""><i class="bi bi-facebook"></i></a>
                                <a href=""><i class="bi bi-instagram"></i></a>
                                <a href=""><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-aos="zoom-in" data-aos-delay="400">
                    <div class="member">
                        <img src="{{asset('image/pimpinan/fix/8.png')}}" class="img-fluid" alt="">
                        <div class="member-info">
                            <div class="member-info-content">
                                <h4>Nur Wahdatul Chilmy, S.Sos., M.Si.</h4>
                                <span>Dekan FISIP</span>
                            </div>
                            <div class="social">
                                <a href=""><i class="bi bi-twitter"></i></a>
                                <a href=""><i class="bi bi-facebook"></i></a>
                                <a href=""><i class="bi bi-instagram"></i></a>
                                <a href=""><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-aos="zoom-in" data-aos-delay="400">
                    <div class="member">
                        <img src="{{asset('image/pimpinan/fix/9.png')}}" class="img-fluid" alt="">
                        <div class="member-info">
                            <div class="member-info-content">
                                <h4>H. Sholahuddin Al Ayyubi, S.Pd., M.Pd.</h4>
                                <span>Dekan FKIP</span>
                            </div>
                            <div class="social">
                                <a href=""><i class="bi bi-twitter"></i></a>
                                <a href=""><i class="bi bi-facebook"></i></a>
                                <a href=""><i class="bi bi-instagram"></i></a>
                                <a href=""><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-aos="zoom-in" data-aos-delay="400">
                    <div class="member">
                        <img src="{{asset('image/pimpinan/fix/10.png')}}" class="img-fluid" alt="">
                        <div class="member-info">
                            <div class="member-info-content">
                                <h4>Supianto, S.H., M.H</h4>
                                <span>Dekan FH</span>
                            </div>
                            <div class="social">
                                <a href=""><i class="bi bi-twitter"></i></a>
                                <a href=""><i class="bi bi-facebook"></i></a>
                                <a href=""><i class="bi bi-instagram"></i></a>
                                <a href=""><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-aos="zoom-in" data-aos-delay="400">
                    <div class="member">
                        <img src="{{asset('image/pimpinan/fix/11.png')}}" class="img-fluid" alt="">
                        <div class="member-info">
                            <div class="member-info-content">
                                <h4>Saman Hudi, S.Ag., M.Si</h4>
                                <span>Dekan Tarbiyah</span>
                            </div>
                            <div class="social">
                                <a href=""><i class="bi bi-twitter"></i></a>
                                <a href=""><i class="bi bi-facebook"></i></a>
                                <a href=""><i class="bi bi-instagram"></i></a>
                                <a href=""><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- End Team Section -->

    <!-- ======= Clients Section ======= -->
    <section id="clients" class="clients section-bg">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2>Clients</h2>
                <p>They trusted us</p>
            </div>
            <div class="clients-slider swiper-container" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper-wrapper align-items-center">
                    <div class="swiper-slide"><img src="{{asset('frontpage/assets/img/clients/client-1.png')}}"
                                                   class="img-fluid" alt=""></div>
                    <div class="swiper-slide"><img src="{{asset('frontpage/assets/img/clients/client-2.png')}}"
                                                   class="img-fluid" alt=""></div>
                    <div class="swiper-slide"><img src="{{asset('frontpage/assets/img/clients/client-3.png')}}"
                                                   class="img-fluid" alt=""></div>
                    <div class="swiper-slide"><img src="{{asset('frontpage/assets/img/clients/client-4.png')}}"
                                                   class="img-fluid" alt=""></div>
                    <div class="swiper-slide"><img src="{{asset('frontpage/assets/img/clients/client-5.png')}}"
                                                   class="img-fluid" alt=""></div>
                    <div class="swiper-slide"><img src="{{asset('frontpage/assets/img/clients/client-6.png')}}"
                                                   class="img-fluid" alt=""></div>
                    <div class="swiper-slide"><img src="{{asset('frontpage/assets/img/clients/client-7.png')}}"
                                                   class="img-fluid" alt=""></div>
                    <div class="swiper-slide"><img src="{{asset('frontpage/assets/img/clients/client-8.png')}}"
                                                   class="img-fluid" alt=""></div>
                </div>
                <div class="swiper-pagination"></div>
            </div>

        </div>
    </section><!-- End Clients Section -->

    <!-- ======= Contact Us Section ======= -->
    <section id="contact" class="contact">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2>Contact Us</h2>
                <p>Contact us the get started</p>
            </div>
            <div class="row">
                <div class="col-lg-5 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
                    <div class="info">
                        <div class="address">
                            <i class="bi bi-geo-alt"></i>
                            <h4>Location:</h4>
                            <p>Kampus 1 : Jl. Kyai Mojo No.101, Kaliwates Kidul, Kaliwates, Kec. Kaliwates, Kabupaten Jember, Jawa Timur 68133</p>
				<p>Kampus 2 : Jl. Tidar No.19, Kloncing, Karangrejo, Kec. Sumbersari, Kabupaten Jember, Jawa Timur 68124</p>
                        </div>

                        <div class="email">
                            <i class="bi bi-envelope"></i>
                            <h4>Email:</h4>
                            <p>humasuij@uij.ac.id</p>
                        </div>

                        <div class="phone">
                            <i class="bi bi-phone"></i>
                            <h4>Call:</h4>
                            <p>0331-321304</p>
                        </div>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6641.701540650351!2d113.67798956397013!3d-8.18467926861894!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd696a78da640f9%3A0xaf0b273047eddaf9!2sUniversitas%20Islam%20Jember!5e0!3m2!1sid!2sid!4v1723695318260!5m2!1sid!2sid"
                                style="border:0;  width: 100%; height: 290px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>

                </div>

                <div class="col-lg-7 mt-5 mt-lg-0 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="200">
                    <form action="#" method="post" role="form" class="php-email-form">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name">Your Name</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Your Name"
                                       required>
                            </div>
                            <div class="form-group col-md-6 mt-3 mt-md-0">
                                <label for="name">Your Email</label>
                                <input type="email" class="form-control" name="email" id="email"
                                       placeholder="Your Email" required>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="name">Subject</label>
                            <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject"
                                   required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="name">Message</label>
                            <textarea class="form-control" name="message" rows="10" required></textarea>
                        </div>
                        <div class="my-3">
                            <div class="loading">Loading</div>
                            <div class="error-message"></div>
                            <div class="sent-message">Your message has been sent. Thank you!</div>
                        </div>
                        <div class="text-center">
                            <button type="submit">Send Message</button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </section><!-- End Contact Us Section -->

</main><!-- End #main -->

<!-- ======= Footer ======= -->
<footer id="footer">

    <div class="footer-top">
        <div class="container">
            <div class="row">

                <div class="col-lg-3 col-md-6 footer-contact">
                    <h3>Ninestars</h3>
                    <p>
                        A108 Adam Street <br>
                        New York, NY 535022<br>
                        United States <br><br>
                        <strong>Phone:</strong> +1 5589 55488 55<br>
                        <strong>Email:</strong> info@example.com<br>
                    </p>
                </div>

                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Home</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">About us</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Services</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Terms of service</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Privacy policy</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Our Services</h4>
                    <ul>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Web Design</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Web Development</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Product Management</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Marketing</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="#">Graphic Design</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Our Social Networks</h4>
                    <p>Cras fermentum odio eu feugiat lide par naso tierra videa magna derita valies</p>
                    <div class="social-links mt-3">
                        <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
                        <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                        <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                        <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
                        <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="container py-4">
        <div class="copyright">
            &copy; Copyright <strong><span>Ninestars</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/ninestars-free-bootstrap-3-theme-for-creative/ -->
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
    </div>
</footer><!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="{{asset('frontpage/assets/vendor/aos/aos.js')}}"></script>
<script src="{{asset('frontpage/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('frontpage/assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
<script src="{{asset('frontpage/assets/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
<script src="{{asset('frontpage/assets/vendor/php-email-form/validate.js')}}"></script>
<script src="{{asset('frontpage/assets/vendor/swiper/swiper-bundle.min.js')}}"></script>

<!-- Template Main JS File -->
<script src="{{asset('frontpage/assets/js/main.js')}}"></script>

</body>

</html>
