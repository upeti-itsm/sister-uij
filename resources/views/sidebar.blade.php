<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistem informasi terpadu; Siakad Mandala">
    <meta name="author" content="Bdtask">
    <title>SISTER | UIJ</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('image/logo-uij.png')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--Global Styles(used by all pages)-->
    <link href="{{asset('adminpage/assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/metisMenu/metisMenu.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/fontawesome/css/all.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/typicons/src/typicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/themify-icons/themify-icons.min.css')}}" rel="stylesheet">
    <link href="{{asset('adminpage/assets/plugins/jquery-confirm/jquery-confirm.min.css')}}" rel="stylesheet">
    <!--Third party Styles(used by this page)-->
    @yield('head-css')
    <!--Start Your Custom Style Now-->
    <link href="{{asset('adminpage/assets/dist/css/style.css')}}" rel="stylesheet">
</head>
<body class="fixed">
@php($modul = \Illuminate\Support\Facades\Session::get('modul'))
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
                    src="{{asset('image/Logos.png')}}"
                    alt=""></a>
        </div><!--/.sidebar header-->
        <div class="profile-element d-flex align-items-center flex-shrink-0 bg-dark">
            <div class="avatar online">
                @if(isset(\Illuminate\Support\Facades\Session::get('user')->id_mhs))
                    <img
                        src="http://siakad.stie-mandala.ac.id/_report/photo_m/{{\Illuminate\Support\Facades\Session::get('user')->nim}}.jpg"
                        class="img-fluid rounded-circle"
                        alt="" onerror="this.src='{{asset('adminpage/assets/dist/img/avatar-1.jpg')}}'">
                @else
                    <img
                        src="/files/profil_karyawan/{{\Illuminate\Support\Facades\Session::get('user')->id_personal}}/{{\Illuminate\Support\Facades\Session::get('karyawan')->path_photo}}"
                        class="img-fluid rounded-circle"
                        alt="" onerror="this.src='{{asset('adminpage/assets/dist/img/avatar-1.jpg')}}'">
                @endif
            </div>
            <div class="profile-text">
                <small class="m-0 text-white">{{\Illuminate\Support\Facades\Session::get('user')->nama_lengkap}}</small><br/>
                <span>{{\Illuminate\Support\Facades\Session::get('peran')['aktif_']}}</span>
            </div>
        </div><!--/.profile element-->
        <div class="sidebar-body">
            <nav class="sidebar-nav">
                <ul class="metismenu">
                    <li class="nav-label">Main Menu</li>
                    @if(array_key_exists('Dashboard', $modul))
                        <li @if($menu == "Dashboard") class="mm-active" @endif><a
                                href="{{route('dashboard.dashboard')}}"><i
                                    class="typcn typcn-th-large mr-2"></i>Dashboard</a></li>
                    @endif
                    @if(array_key_exists('Profil Mandala', $modul))
                        <li @if($menu == "Profil Mandala") class="mm-active" @endif><a
                                href="{{route('bpm_page.profil_mandala.profil_mandala.index')}}"><i
                                    class="typcn typcn-home mr-2"></i>Profil Mandala</a></li>
                    @endif
                    @if(array_key_exists("Tata Pamong", $modul))
                        <li @if($menu == "Tata Pamong") class="mm-active" @endif>
                            <a class="has-arrow material-ripple" href="#">
                                <i class="typcn typcn-th-list mr-2"></i>
                                Dokumen SPMI
                            </a>
                            <ul class="nav-second-level">
                                @if(array_key_exists("Tata Pamong", $modul))
                                    <li @if($menu == "Tata Pamong") class="mm-active" @endif>
                                        <a href="{{route('bpm_page.tata_pamong.index')}}">Dokumen Tata Pamong</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(array_key_exists("Melihat Data Sinta", $modul))
                        <li @if($menu == "Melihat Data Sinta") class="mm-active" @endif>
                            <a class="has-arrow material-ripple" href="#">
                                <i class="typcn typcn-th-list mr-2"></i>
                                Sinta
                            </a>
                            <ul class="nav-second-level">
                                @if(array_key_exists("Melihat Data Sinta", $modul))
                                    <li @if($menu == "Melihat Data Sinta") class="mm-active" @endif>
                                        <a href="{{route('lppm.sinta.list_authors')}}">Authors</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(array_key_exists("Pengelolaan Data Pribadi Pegawai", $modul) || array_key_exists('Pengelolaan Data Kepegawaian', $modul) || array_key_exists('Curriculum Vitae', $modul) || array_key_exists('Rekap SDM', $modul))
                        <li class="nav-label">Kepegawaian</li>
                        @if(array_key_exists("Pengelolaan Data Pribadi Pegawai", $modul) || array_key_exists("Pengelolaan Data Kepegawaian", $modul) || array_key_exists("Curriculum Vitae", $modul))
                            <li @if($menu == "Melihat Data Diri" || $menu == "Mengubah Data Diri" || $menu == "List Data Pegawai - HRD" || $menu == "Detail Data Pegawai - HRD" || $menu == "Edit Data Pegawai - HRD" || $menu == "Create Data Pegawai - HRD" || $menu == "Curriculum Vitae") class="mm-active" @endif>
                                <a class="has-arrow material-ripple" href="#">
                                    <i class="typcn typcn-th-list mr-2"></i>
                                    Data Kepegawaian
                                </a>
                                <ul class="nav-second-level">
                                    @if(array_key_exists("Pengelolaan Data Pribadi Pegawai", $modul))
                                        <li @if($menu == "Melihat Data Diri" || $menu == "Mengubah Data Diri") class="mm-active" @endif>
                                            <a href="{{route('karyawan.data_kepegawaian.data_diri.index')}}">Data
                                                Diri</a>
                                        </li>
                                    @endif
                                    @if(array_key_exists("Pengelolaan Data Kepegawaian", $modul))
                                        <li @if($menu == "List Data Pegawai - HRD" || $menu == "Detail Data Pegawai - HRD" || $menu == "Edit Data Pegawai - HRD" || $menu == "Create Data Pegawai - HRD") class="mm-active" @endif>
                                            <a href="{{route('hrd.data_kepegawaian.list_data_pegawai.index')}}">Data
                                                Pegawai</a>
                                        </li>
                                    @endif
                                    @if(array_key_exists("Curriculum Vitae", $modul))
                                        <li @if($menu == "Curriculum Vitae") class="mm-active" @endif>
                                            <a href="{{route('dosen.cv.curriculum_vitae.index')}}">Curriculum Vitae</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        @if(array_key_exists("Rekap SDM", $modul))
                            <li @if(substr($menu, 0, 9) == "Rekap SDM") class="mm-active" @endif>
                                <a class="has-arrow material-ripple" href="#">
                                    <i class="typcn typcn-th-list mr-2"></i>
                                    Rekapitulasi SDM
                                </a>
                                <ul class="nav-second-level">
                                    @if(array_key_exists("Rekap SDM", $modul))
                                        <li @if($menu == "Rekap SDM - Kecukupan Dosen") class="mm-active" @endif>
                                            <a href="{{route('hrd_page.rekap_sdm.kecukupan_dosen')}}">Kecukupan
                                                Dosen</a>
                                        </li>
                                        <li @if($menu == "Rekap SDM - Jabatan Akademik") class="mm-active" @endif>
                                            <a href="{{route('hrd_page.rekap_sdm.jabatan_akademik_dosen')}}">Jabatan
                                                Akademik</a>
                                        </li>
                                        <li @if($menu == "Rekap SDM - Sertifikasi Dosen") class="mm-active" @endif>
                                            <a href="{{route('hrd_page.rekap_sdm.sertifikasi_dosen')}}">Sertifikasi
                                                Dosen</a>
                                        </li>
                                        <li @if($menu == "Rekap SDM - Dosen Tidak Tetap") class="mm-active" @endif>
                                            <a href="{{route('hrd_page.rekap_sdm.dosen_tidak_tetap')}}">Dosen Tidak
                                                Tetap</a>
                                        </li>
                                        <li @if($menu == "Rekap SDM - Rasio Dosen") class="mm-active" @endif>
                                            <a href="{{route('hrd_page.rekap_sdm.rasio_dosen')}}">Rasio Dosen</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    @endif
                    @if(array_key_exists("Kerjasama", $modul))
                        <li @if($menu == "Daftar Kerjasama") class="mm-active" @endif>
                            <a class="has-arrow material-ripple" href="#">
                                <i class="typcn typcn-th-list mr-2"></i>
                                KUI & Kerjasama
                            </a>
                            <ul class="nav-second-level">
                                @if(array_key_exists("Kerjasama", $modul))
                                    <li @if($menu == "Daftar Kerjasama") class="mm-active" @endif>
                                        <a href="{{route('kui_kerjasama.kerjasama.index')}}">Kerjasama</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(array_key_exists("Melihat Hasil Kuesioner Kepuasan Wisudawan", $modul) || array_key_exists("Melihat Hasil Kuesioner Kepuasan Mahasiswa", $modul))
                        <li class="nav-label">Kuesioner</li>
                        <li @if($menu == "Melihat Hasil Kuesioner Kepuasan Wisudawan") class="mm-active" @endif>
                            <a class="has-arrow material-ripple" href="#">
                                <i class="typcn typcn-th-list mr-2"></i>
                                Kepuasan Wisudawan
                            </a>
                            <ul class="nav-second-level">
                                @if(array_key_exists("Melihat Hasil Kuesioner Kepuasan Wisudawan", $modul))
                                    <li @if($menu == "Melihat Hasil Kuesioner Kepuasan Wisudawan") class="mm-active" @endif>
                                        <a href="{{route('bpm_page.kuesioner.kuesioner_kepuasan_wisudawan.hasil_kuesioner.index')}}">Hasil
                                            Kuesioner</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        <li @if($menu == "Melihat Hasil Kuesioner Kepuasan Mahasiswa") class="mm-active" @endif>
                            <a class="has-arrow material-ripple" href="#">
                                <i class="typcn typcn-th-list mr-2"></i>
                                Kepuasan Mahasiswa
                            </a>
                            <ul class="nav-second-level">
                                @if(array_key_exists("Melihat Hasil Kuesioner Kepuasan Mahasiswa", $modul))
                                    <li @if($menu == "Melihat Hasil Kuesioner Kepuasan Mahasiswa") class="mm-active" @endif>
                                        <a href="{{route('bpm_page.kuesioner.kuesioner_kepuasan_mahasiswa.hasil_kuesioner.index')}}">Hasil
                                            Kuesioner</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(array_key_exists('Surat Keluar Masuk', $modul) || array_key_exists('Pengelolaan Surat Keputusan', $modul))
                        <li @if($menu == "Surat Keluar Masuk" || $menu == "Pengelolaan Surat Keputusan") class="mm-active" @endif>
                            <a class="has-arrow material-ripple" href="#">
                                <i class="typcn typcn-th-list mr-2"></i>
                                Surat Menyurat
                            </a>
                            <ul class="nav-second-level">
                                @if(array_key_exists("Surat Keluar Masuk", $modul))
                                    <li @if($menu == "Surat Keluar Masuk") class="mm-active" @endif>
                                        <a href="{{route('sekretaris.surat_menyurat.surat_keluar_masuk.index')}}">Surat
                                            Keluar Masuk</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Pengelolaan Surat Keputusan", $modul))
                                    <li @if($menu == "Pengelolaan Surat Keputusan") class="mm-active" @endif>
                                        <a href="{{route('sekretaris.surat_menyurat.surat_keputusan.index')}}">Surat
                                            Keputusan (SK)</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(array_key_exists('Melihat Surat', $modul) || array_key_exists('Melihat Surat Keputusan (SK)', $modul))
                        <li @if($menu == "Melihat Surat" || $menu == "Melihat Surat Keputusan (SK)") class="mm-active" @endif>
                            <a class="has-arrow material-ripple" href="#">
                                <i class="typcn typcn-th-list mr-2"></i>
                                Surat Menyurat
                            </a>
                            <ul class="nav-second-level">
                                @if(array_key_exists("Melihat Surat", $modul))
                                    <li @if($menu == "Melihat Surat") class="mm-active" @endif>
                                        <a href="{{route('karyawan.surat_menyurat.surat.index')}}">Arsip Surat</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Melihat Surat Keputusan (SK)", $modul))
                                    <li @if($menu == "Melihat Surat Keputusan (SK)") class="mm-active" @endif>
                                        <a href="{{route('karyawan.surat_menyurat.surat_keputusan.index')}}">Surat
                                            Keputusan (SK)</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(array_key_exists('Pengelolaan Gaji Bulanan', $modul) || array_key_exists('Pengaturan Gaji', $modul) || array_key_exists('Melihat Gaji Bulanan', $modul) || array_key_exists('Moodle Synchronizer - Suspended Mahasiswa', $modul) || array_key_exists("Melihat Data Tanggungan Mahasiswa Siakad", $modul) || array_key_exists('Keuangan - Melihat Daftar Mahasiswa Siakad', $modul))
                        <li class="nav-label">Keuangan</li>
                    @endif
                    @if(array_key_exists('Pengelolaan Gaji Bulanan', $modul) || array_key_exists('Pengaturan Gaji', $modul) || array_key_exists('Melihat Gaji Bulanan', $modul) || array_key_exists('Melihat Honorarium Mengajar', $modul) || array_key_exists('Melihat Honorarium Koreksi', $modul))
                        <li @if($menu == "Pengelolaan Gaji Bulanan" || $menu == "Pengaturan Gaji - Daftar Pegawai" || $menu == "Pengaturan Gaji - Gaji Umum" || $menu == "Pengaturan Gaji - Potongan BPJS" || $menu == "Pengaturan Gaji - Potongan Koperasi" || $menu == "Pengaturan Gaji - Potongan Arisan" || $menu == "Pengaturan Gaji - Potongan Qurban" || $menu == "Pengaturan Gaji - Potongan Lainnya" || $menu == "Melihat Gaji Bulanan" || $menu == "Pengelolaan Honorarium Mengajar" || $menu == "Pengelolaan Honorarium Koreksi" || $menu == "Pengelolaan Honorarium Pengawas" || $menu == "Melihat Honorarium Mengajar" || $menu == "Melihat Honorarium Koreksi") class="mm-active" @endif>
                            <a class="has-arrow material-ripple" href="#">
                                <i class="typcn typcn-th-list mr-2"></i>
                                Penggajian
                            </a>
                            <ul class="nav-second-level">
                                @if(array_key_exists('Melihat Honorarium Mengajar', $modul) || array_key_exists('Melihat Honorarium Koreksi', $modul))
                                    <li @if($menu == "Melihat Honorarium Mengajar" || $menu == "Melihat Honorarium Koreksi") class="mm-active" @endif>
                                        <a class="has-arrow" href="#" aria-expanded="true">Honorarium</a>
                                        <ul class="nav-third-level mm-collapse" style="">
                                            @if(array_key_exists("Melihat Honorarium Mengajar", $modul))
                                                <li @if($menu == "Melihat Honorarium Mengajar") class="mm-active" @endif>
                                                    <a href="{{route('dosen_page.keuangan.honorarium.honorarium_mengajar.index')}}">Mengajar</a>
                                                </li>
                                            @endif
                                            @if(array_key_exists("Melihat Honorarium Koreksi", $modul))
                                                <li @if($menu == "Melihat Honorarium Koreksi") class="mm-active" @endif>
                                                    <a href="{{route('dosen_page.keuangan.honorarium.honorarium_koreksi.index')}}">Koreksi</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif
                                @if(array_key_exists("Melihat Gaji Bulanan", $modul))
                                    <li @if($menu == "Melihat Gaji Bulanan") class="mm-active" @endif>
                                        <a href="{{route('karyawan.penggajian.gaji_bulanan.index')}}">Gaji
                                            Bulanan</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Pengelolaan Gaji Bulanan", $modul))
                                    <li @if($menu == "Pengelolaan Gaji Bulanan") class="mm-active" @endif>
                                        <a href="{{route('keuangan.penggajian.gaji_bulanan.index')}}">Gaji Bulanan</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Pengelolaan Honorarium Mengajar", $modul) || array_key_exists("Pengelolaan Honorarium Koreksi", $modul) || array_key_exists("Pengelolaan Honorarium Pengawas", $modul))
                                    <li @if($menu == "Pengelolaan Honorarium Mengajar" || $menu == "Pengelolaan Honorarium Koreksi" || $menu == "Pengelolaan Honorarium Pengawas") class="mm-active" @endif>
                                        <a @if(array_key_exists("Pengelolaan Honorarium Mengajar", $modul)) href="{{route('keuangan.penggajian.honorarium.honorarium_mengajar.index')}}"
                                           @elseif(array_key_exists("Pengelolaan Honorarium Koreksi", $modul)) href="{{route('keuangan.penggajian.honorarium.honorarium_koreksi.index')}}"
                                           @else href="{{route('keuangan.penggajian.honorarium.honorarium_pengawas.index')}}" @endif>Honorarium</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Pengaturan Gaji", $modul))
                                    <li @if($menu == "Pengaturan Gaji - Daftar Pegawai" || $menu == "Pengaturan Gaji - Gaji Umum" || $menu == "Pengaturan Gaji - Potongan Koperasi" || $menu == "Pengaturan Gaji - Potongan BPJS") class="mm-active" @endif>
                                        <a href="{{route('keuangan.penggajian.pengaturan_gaji.daftar_pegawai.index')}}">Pengaturan
                                            Gaji</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(array_key_exists('Moodle Synchronizer - Suspended Mahasiswa', $modul) || array_key_exists("Melihat Data Tanggungan Mahasiswa Siakad", $modul) || array_key_exists("Keuangan - Melihat Daftar Mahasiswa Siakad", $modul))
                        <li @if($menu == "Suspended Mahasiswa | Moodle" || $menu == "Tanggungan Mahasiswa | Siakad" || $menu == "Keuangan - Melihat Daftar Mahasiswa Siakad") class="mm-active" @endif>
                            <a class="has-arrow material-ripple" href="#">
                                <i class="typcn typcn-th-list mr-2"></i>
                                Mahasiswa
                            </a>
                            <ul class="nav-second-level">
                                @if(array_key_exists("Moodle Synchronizer - Suspended Mahasiswa", $modul))
                                    <li @if($menu == "Suspended Mahasiswa | Moodle") class="mm-active" @endif>
                                        <a href="{{route('moodle.mahasiswa.suspended')}}"
                                           aria-expanded="false">Suspended</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Melihat Data Tanggungan Mahasiswa Siakad", $modul))
                                    <li @if($menu == "Tanggungan Mahasiswa | Siakad") class="mm-active" @endif>
                                        <a href="{{route('siakad.tanggungan_mahasiswa.daftar_tanggungan')}}">Tanggungan</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Keuangan - Melihat Daftar Mahasiswa Siakad", $modul))
                                    <li @if($menu == "Keuangan - Melihat Daftar Mahasiswa Siakad") class="mm-active" @endif>
                                        <a href="{{route('keu.siakad.mahasiswa.daftar_mahasiswa')}}">Daftar
                                            Mahasiswa</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(array_key_exists('Daftar Sarana Prasarana', $modul))
                        <li @if($menu == "Daftar Sarana Prasarana") class="mm-active" @endif><a
                                href="{{route('hrd.sarpras.index')}}"><i
                                    class="typcn typcn-info mr-2"></i>Sarpras</a></li>
                    @endif
                    @if(array_key_exists('Pengisian SPI', $modul) || array_key_exists('Validasi Pengajuan Sertifikat Laboratorium Komputer', $modul) || array_key_exists('Pengelolaan Nomor Sertifikat', $modul) || array_key_exists('Pengajuan Sertifikat Laboratorium Komputer', $modul) || array_key_exists('Melihat Rekap Absensi Mengajar', $modul) || array_key_exists('Pengelolaan Rekap Absensi Mengajar', $modul) || array_key_exists("Pengelolaan Jadwal Wisuda", $modul) || array_key_exists("Validasi Pendaftaran Wisuda", $modul) || array_key_exists("Pengelolaan Mahasiswa LP3I Banyuwangi", $modul) || array_key_exists("Pengajuan Tugas Akhir", $modul) || array_key_exists('Sinkronisasi Tahun Akademik dengan Siakad', $modul) || array_key_exists("Perwalian Mahasiswa", $modul) || array_key_exists('Sinkronisasi Jadwal Mahasiswa dengan Siakad', $modul) || array_key_exists('Student Body', $modul))
                        <li class="nav-label">Akademik</li>
                        <li @if($menu == "Validasi Pengajuan Sertifikat Laboratorium Komputer" || $menu == "Pengelolaan Nomor Sertifikat" || $menu == "Pengajuan Sertifikat Laboratorium Komputer" || $menu == "Melihat Rekap Absensi Mengajar" || $menu == "Pengelolaan Rekap Absensi Mengajar" || $menu == "Pengelolaan Jadwal Wisuda" || $menu == "Validasi Pendaftaran Wisuda" || $menu == "Absensi Mengajar" || $menu == "Pengelolaan Mahasiswa LP3I Banyuwangi" || $menu == "Pengajuan Tugas Akhir" || $menu == "Sinkronisasi Tahun Akademik dengan Siakad" || $menu == "Sinkronisasi Jadwal Mahasiswa dengan Siakad" || $menu == "Sinkronisasi Jadwal Mahasiswa dengan Siakad" || $menu == "Student Body") class="mm-active" @endif>
                            <a class="has-arrow material-ripple" href="#">
                                <i class="fas fa-graduation-cap mr-2"></i>
                                @if(\Illuminate\Support\Facades\Session::get('peran')['aktif_'] === 'Dosen')
                                    Pengajaran
                                @else
                                    Akademik
                                @endif
                            </a>
                            <ul class="nav-second-level">
                                @if(array_key_exists('Validasi Pengajuan Sertifikat Laboratorium Komputer', $modul))
                                    <li @if($menu == "Validasi Pengajuan Sertifikat Laboratorium Komputer") class="mm-active" @endif>
                                        <a href="{{route('akademik.sertifikat_labkom.validasi.pengajuan_sertifikat')}}">Sertifikat
                                            Labkom</a>
                                    </li>
                                @endif
                                @if(array_key_exists('Student Body', $modul))
                                    <li @if($menu == "Student Body") class="mm-active" @endif>
                                        <a href="{{route('admin_akademik.akademik.mahasiswa.student_body.index')}}">Student
                                            Body</a>
                                    </li>
                                @endif
                                @if(array_key_exists('Pengelolaan Nomor Sertifikat', $modul))
                                    <li @if($menu == "Pengelolaan Nomor Sertifikat") class="mm-active" @endif>
                                        <a href="{{route('akademik.nomor_sertifikat.index')}}">Nomor Sertifikat</a>
                                    </li>
                                @endif
                                @if(array_key_exists('Pengisian SPI', $modul))
                                    <li @if($menu == "Pengisian SPI") class="mm-active" @endif>
                                        <a href="{{route('mahasiswa.akademik.skpi.index')}}">SKPI</a>
                                    </li>
                                @endif
                                @if(session()->has('nilai_labkom'))
                                    @if(array_key_exists('Pengajuan Sertifikat Laboratorium Komputer', $modul))
                                        <li @if($menu == "Pengajuan Sertifikat Laboratorium Komputer") class="mm-active" @endif>
                                            <a href="{{route('mahasiswa.akademik.sertifikat_labkom.index')}}">Sertifikat
                                                Labkom</a>
                                        </li>
                                    @endif
                                @endif
                                @if(array_key_exists('Melihat Rekap Absensi Mengajar', $modul))
                                    <li @if($menu == "Absensi Mengajar") class="mm-active" @endif>
                                        <a href="{{route('dosen.akademik.absen_mengajar.absensi_ngajar')}}">Absensi
                                            Mengajar</a>
                                    </li>
                                    <li @if($menu == "Melihat Rekap Absensi Mengajar") class="mm-active" @endif>
                                        <a href="{{route('dosen.akademik.rekapitulasi_absen_mengajar.index')}}">Rekap
                                            Absensi Mengajar</a>
                                    </li>
                                @endif
                                @if(array_key_exists('Pengelolaan Rekap Absensi Mengajar', $modul))
                                    <li @if($menu == "Pengelolaan Rekap Absensi Mengajar") class="mm-active" @endif>
                                        <a href="{{route('hrd.akademik.rekapitulasi_absen_mengajar.index')}}">Rekap
                                            Absensi Mengajar</a>
                                    </li>
                                @endif
                                @if(array_key_exists('Pengelolaan Mahasiswa LP3I Banyuwangi', $modul) || array_key_exists('Sinkronisasi Mahasiswa dengan Siakad', $modul))
                                    <li @if($menu == "Pengelolaan Mahasiswa LP3I Banyuwangi" || $menu == "Sinkronisasi Mahasiswa dengan Siakad") class="mm-active" @endif>
                                        <a class="has-arrow" href="#" aria-expanded="true">Mahasiswa</a>
                                        <ul class="nav-third-level mm-collapse" style="">
                                            @if(array_key_exists("Pengelolaan Mahasiswa LP3I Banyuwangi", $modul))
                                                <li @if($menu == "Pengelolaan Mahasiswa LP3I Banyuwangi") class="mm-active" @endif>
                                                    <a href="{{route('admin_akademik.akademik.mahasiswa.mahasiswa_lpppi.index')}}">Mahasiswa
                                                        LP3I</a>
                                                </li>
                                            @endif
                                            @if(array_key_exists("Sinkronisasi Mahasiswa dengan Siakad", $modul))
                                                <li @if($menu == "Sinkronisasi Mahasiswa dengan Siakad") class="mm-active" @endif>
                                                    <a href="{{route('admin_akademik.akademik.mahasiswa.sinkronisasi_mahasiswa_siakad.index')}}">Sinkronisasi
                                                        dengan Siakad</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif
                                @if(array_key_exists('Sinkronisasi Jadwal Kuliah dengan Siakad', $modul) || array_key_exists('Sinkronisasi Tahun Akademik dengan Siakad', $modul) || array_key_exists('Sinkronisasi Jadwal Mahasiswa dengan Siakad', $modul))
                                    <li @if($menu == "Sinkronisasi Jadwal Kuliah dengan Siakad" || $menu == "Sinkronisasi Tahun Akademik dengan Siakad" || $menu == 'Sinkronisasi Jadwal Mahasiswa dengan Siakad') class="mm-active" @endif>
                                        <a class="has-arrow" href="#" aria-expanded="true">Perkuliahan</a>
                                        <ul class="nav-third-level mm-collapse" style="">
                                            @if(array_key_exists("Sinkronisasi Tahun Akademik dengan Siakad", $modul))
                                                <li @if($menu == "Sinkronisasi Tahun Akademik dengan Siakad") class="mm-active" @endif>
                                                    <a href="{{route('admin_akademik.akademik.perkuliahan.sinkronisasi_tahun_akademik_siakad.index')}}">Tahun
                                                        Akademik</a>
                                                </li>
                                            @endif
                                            @if(array_key_exists("Sinkronisasi Jadwal Kuliah dengan Siakad", $modul))
                                                <li @if($menu == "Sinkronisasi Jadwal Kuliah dengan Siakad") class="mm-active" @endif>
                                                    <a href="{{route('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_siakad.index')}}">Jadwal
                                                        Kuliah</a>
                                                </li>
                                            @endif
                                            @if(array_key_exists("Sinkronisasi Jadwal Mahasiswa dengan Siakad", $modul))
                                                <li @if($menu == "Sinkronisasi Jadwal Mahasiswa dengan Siakad") class="mm-active" @endif>
                                                    @if($modul['Sinkronisasi Jadwal Mahasiswa dengan Siakad'] == 2)
                                                        <a href="{{route('mahasiswa.akademik.jadwal_kuliah.index')}}">Jadwal
                                                            Kuliah</a>
                                                    @else
                                                        <a href="{{route('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_mahasiswa.index')}}">Jadwal
                                                            Kuliah Mahasiswa</a>
                                                    @endif
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif
                                @if(array_key_exists("Pengelolaan Jadwal Wisuda", $modul) || array_key_exists("Validasi Pendaftaran Wisuda", $modul))
                                    <li @if($menu == "Pengelolaan Jadwal Wisuda" || $menu == "Validasi Pendaftaran Wisuda") class="mm-active" @endif>
                                        <a class="has-arrow" href="#" aria-expanded="true">Wisuda</a>
                                        <ul class="nav-third-level mm-collapse" style="">
                                            @if(array_key_exists("Pengelolaan Jadwal Wisuda", $modul))
                                                <li @if($menu == "Pengelolaan Jadwal Wisuda") class="mm-active" @endif>
                                                    <a href="{{route('jadwal_wisuda.index')}}">Jadwal Wisuda</a>
                                                </li>
                                            @endif
                                            @if(array_key_exists("Validasi Pendaftaran Wisuda", $modul))
                                                <li @if($menu == "Validasi Pendaftaran Wisuda") class="mm-active" @endif>
                                                    <a href="{{route('admin_akademik.akademik.wisuda.pendaftaran_wisuda.index')}}">Pendaftaran
                                                        Wisuda</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif
                                @if(session()->has('nilai_ta') || session()->has('krs_ta'))
                                    @if(array_key_exists("Pengajuan Tugas Akhir", $modul))
                                        <li @if($menu == "Pengajuan Tugas Akhir") class="mm-active" @endif>
                                            <a href="{{route('mahasiswa.akademik.pengajuan_tugas_akhir.index')}}">Tugas
                                                Akhir</a>
                                        </li>
                                    @endif
                                @endif
                                @if(session()->has('nilai_ta'))
                                    @if(array_key_exists("Pendaftaran Wisuda", $modul))
                                        <li @if($menu == "Pendaftaran Wisuda") class="mm-active" @endif>
                                            <a href="{{route('mahasiswa.akademik.pendaftaran_wisuda.index')}}">Wisuda</a>
                                        </li>
                                    @endif
                                @endif
                                @if(array_key_exists("Dosen - Daftar Matakuliah", $modul))
                                    <li @if($menu == "Dosen - Akademik - Melihat Daftar Matakuliah") class="mm-active" @endif>
                                        <a href="{{route('dosen.akademik.daftar_matakuliah.index')}}">Daftar
                                            Matakuliah</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Perwalian Mahasiswa", $modul))
                                    <li @if($menu == "Perwalian - Daftar Mahasiswa") class="mm-active" @endif>
                                        <a href="{{route('dosen.akademik.perwalian.daftar_mahasiswa')}}">Perwalian</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(array_key_exists('Jenis Pelaksanaan Mata Kuliah', $modul) || array_key_exists('Jenis Mata Kuliah', $modul) || array_key_exists('Kurikulum', $modul) || array_key_exists('Mata Kuliah', $modul) || array_key_exists('Konsentrasi Jurusan', $modul))
                        <li @if($menu == "Mata Kuliah" || $menu == "Kurikulum" || $menu == "Jenis Mata Kuliah" || $menu == "Jenis Pelaksanaan Mata Kuliah" || $menu == "Konsentrasi Jurusan") class="mm-active" @endif>
                            <a class="has-arrow material-ripple" href="#">
                                <i class="typcn typcn-edit mr-2"></i>
                                Perkuliahan
                            </a>
                            <ul class="nav-second-level">
                                @if(array_key_exists("Mata Kuliah", $modul))
                                    <li @if($menu == "Mata Kuliah") class="mm-active" @endif>
                                        <a href="#">Mata Kuliah</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Kurikulum", $modul))
                                    <li @if($menu == "Kurikulum") class="mm-active" @endif>
                                        <a href="#">Kurikulum</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Jenis Mata Kuliah", $modul))
                                    <li @if($menu == "Jenis Mata Kuliah") class="mm-active" @endif>
                                        <a href="#">Jenis Mata Kuliah</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Jenis Pelaksanaan Mata Kuliah", $modul))
                                    <li @if($menu == "Jenis Pelaksanaan Mata Kuliah") class="mm-active" @endif>
                                        <a href="#">Jenis Pelaksanaan Mata Kuliah</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Konsentrasi Jurusan", $modul))
                                    <li @if($menu == "Konsentrasi Jurusan") class="mm-active" @endif>
                                        <a href="{{route('konsentrasi_jurusan.konsentrasi_jurusan')}}">Konsentrasi
                                            Jurusan</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(array_key_exists('Syncron Program Studi', $modul) || array_key_exists('Syncron Mahasiswa', $modul) || array_key_exists('Syncron Dosen', $modul))
                        <li class="nav-label">Data Master</li>
                    @endif
                    @if(array_key_exists('Syncron Program Studi', $modul) || array_key_exists('Syncron Mahasiswa', $modul) || array_key_exists('Syncron Dosen', $modul))
                        <li @if($menu == "Syncron Program Studi" || $menu == "Syncron Mahasiswa" || $menu == "Syncron Dosen") class="mm-active" @endif>
                            <a class="has-arrow material-ripple" href="#">
                                <i class="typcn typcn-th-list mr-2"></i>
                                Sinkronisasi Data
                            </a>
                            <ul class="nav-second-level">
                                @if(array_key_exists("Syncron Program Studi", $modul))
                                    <li @if($menu == "Syncron Program Studi") class="mm-active" @endif>
                                        <a href="{{route('sinkronisasi_data.program_studi.program_studi')}}">Program
                                            Studi</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Syncron Mahasiswa", $modul))
                                    <li @if($menu == "Syncron Mahasiswa") class="mm-active" @endif>
                                        <a href="{{route('sinkronisasi_data.mahasiswa.mahasiswa')}}">Mahasiswa</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Syncron Dosen", $modul))
                                    <li @if($menu == "Syncron Dosen") class="mm-active" @endif>
                                        <a href="{{route('sinkronisasi_data.dosen.dosen')}}">Dosen</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(array_key_exists('Pengelolaan Aplikasi Mandala', $modul) || array_key_exists('Pengelolaan Modul Aplikasi', $modul) || array_key_exists('Pengelolaan Peran Aplikasi', $modul) || array_key_exists('Pengelolaan Kewenangan Peran', $modul))
                        <li class="nav-label">SISTEM</li>
                    @endif
                    @if(array_key_exists('Pengelolaan Aplikasi Mandala', $modul) || array_key_exists('Pengelolaan Modul Aplikasi', $modul) || array_key_exists('Pengelolaan Peran Aplikasi', $modul) || array_key_exists('Pengelolaan Kewenangan Peran', $modul) || array_key_exists('Pengelolaan Peran Pengguna', $modul))
                        <li @if($menu == "Pengelolaan Aplikasi Mandala" || $menu == "Pengelolaan Modul Aplikasi" || $menu == "Pengelolaan Peran Aplikasi" || $menu == "Pengelolaan Kewenangan Peran" || $menu == "Pengelolaan Peran Pengguna")  class="mm-active" @endif>
                            <a class="has-arrow material-ripple" href="#">
                                <i class="typcn typcn-th-list mr-2"></i>
                                Konfigurasi Sistem
                            </a>
                            <ul class="nav-second-level">
                                @if(array_key_exists("Pengelolaan Aplikasi Mandala", $modul))
                                    <li @if($menu == "Pengelolaan Aplikasi Mandala") class="mm-active" @endif>
                                        <a href="{{route('aplikasi.index')}}">Aplikasi</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Pengelolaan Modul Aplikasi", $modul))
                                    <li @if($menu == "Pengelolaan Modul Aplikasi") class="mm-active" @endif>
                                        <a href="{{route('modul.index')}}">Modul</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Pengelolaan Peran Aplikasi", $modul))
                                    <li @if($menu == "Pengelolaan Peran Aplikasi") class="mm-active" @endif>
                                        <a href="{{route('peran.index')}}">Peran</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Pengelolaan Kewenangan Peran", $modul))
                                    <li @if($menu == "Pengelolaan Kewenangan Peran") class="mm-active" @endif>
                                        <a href="{{route('kewenangan_peran.index')}}">Kewenangan Peran</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Pengelolaan Peran Pengguna", $modul))
                                    <li @if($menu == "Pengelolaan Peran Pengguna") class="mm-active" @endif>
                                        <a href="{{route('peran_pengguna.index')}}">Peran Pengguna</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(array_key_exists('Melihat Data Perkuliahan Siakad', $modul) || array_key_exists('Melihat Data Mahasiswa Siakad', $modul) || array_key_exists('Melihat Data Dosen/Karyawan Siakad', $modul) || array_key_exists('Moodle Synchronizer - Jadwal Siakad', $modul) || array_key_exists("Melihat Data Perkuliahan Mahasiswa Siakad", $modul) || array_key_exists('Moodle Synchronizer - Mahasiswa', $modul) || array_key_exists("Moodle Synchronizer - Dosen", $modul) || array_key_exists("Moodle Synchronizer - Jadwal Mahasiswa", $modul))
                        <li class="nav-label">EXTERNAL DB</li>
                    @endif
                    @if(array_key_exists('Melihat Data Perkuliahan Siakad', $modul) || array_key_exists('Melihat Data Mahasiswa Siakad', $modul) || array_key_exists('Melihat Data Dosen/Karyawan Siakad', $modul) || array_key_exists("Melihat Data Perkuliahan Mahasiswa Siakad", $modul))
                        <li @if($menu == "Jadwal Dosen | Siakad" || $menu == "Mahasiswa | Siakad" || $menu == "Dosen | Siakad" || $menu == "Jadwal Mahasiswa | Siakad") class="mm-active" @endif>
                            <a class="has-arrow material-ripple" href="#">
                                <i class="typcn typcn-th-list mr-2"></i>
                                Siakad
                            </a>
                            <ul class="nav-second-level">
                                @if(array_key_exists("Melihat Data Dosen/Karyawan Siakad", $modul))
                                    <li @if($menu == "Dosen | Siakad") class="mm-active" @endif>
                                        <a href="{{route('siakad.dosen.daftar_dosen')}}">Dosen</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Melihat Data Perkuliahan Siakad", $modul))
                                    <li @if($menu == "Jadwal Dosen | Siakad") class="mm-active" @endif>
                                        <a href="{{route('siakad.jadwal_perkuliahan.vwJadwalDosen')}}">Jadwal Dosen</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Melihat Data Mahasiswa Siakad", $modul))
                                    <li @if($menu == "Mahasiswa | Siakad") class="mm-active" @endif>
                                        <a href="{{route('siakad.mahasiswa.daftar_mahasiswa')}}">Mahasiswa</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Melihat Data Perkuliahan Mahasiswa Siakad", $modul))
                                    <li @if($menu == "Jadwal Mahasiswa | Siakad") class="mm-active" @endif>
                                        <a href="{{route('siakad.jadwal_mahasiswa.vwJadwalMahasiswa')}}">Jadwal
                                            Mahasiswa</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(array_key_exists("Moodle Synchronizer - Jadwal Siakad", $modul) || array_key_exists('Moodle Synchronizer - Mahasiswa', $modul) || array_key_exists("Moodle Synchronizer - Dosen", $modul) || array_key_exists("Moodle Synchronizer - Jadwal Mahasiswa", $modul))
                        <li @if($menu == "Jadwal Dosen | Moodle" || $menu == "Mahasiswa | Moodle" || $menu == "Dosen | Moodle" || $menu == "Jadwal Mahasiswa | Moodle") class="mm-active" @endif>
                            <a class="has-arrow material-ripple" href="#">
                                <i class="typcn typcn-th-list mr-2"></i>
                                Moodle
                            </a>
                            <ul class="nav-second-level">
                                @if(array_key_exists("Moodle Synchronizer - Dosen", $modul))
                                    <li @if($menu == "Dosen | Moodle") class="mm-active" @endif>
                                        <a href="{{route('moodle.dosen.index')}}">Dosen</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Moodle Synchronizer - Jadwal Siakad", $modul))
                                    <li @if($menu == "Jadwal Dosen | Moodle") class="mm-active" @endif>
                                        <a href="{{route('moodle.jadwal_siakad.index')}}">Jadwal Dosen</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Moodle Synchronizer - Enrolment Course", $modul))
                                    <li @if($menu == "Daftar Course | Moodle") class="mm-active" @endif>
                                        <a href="{{route('moodle.enrolment.daftar_course')}}">Course</a>
                                    </li>
                                @endif
                                @if(array_key_exists("Moodle Synchronizer - Mahasiswa", $modul))
                                    <li @if($menu == "Mahasiswa | Moodle") class="mm-active" @endif>
                                        <a class="has-arrow" href="#" aria-expanded="true">Mahasiswa</a>
                                        <ul class="nav-third-level mm-collapse" style="">
                                            @if(array_key_exists("Moodle Synchronizer - Mahasiswa", $modul))
                                                <li @if($menu == "Mahasiswa | Moodle") class="mm-active" @endif><a
                                                        href="{{route('moodle.mahasiswa.index')}}"
                                                        aria-expanded="false">List</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif
                                @if(array_key_exists("Moodle Synchronizer - Jadwal Mahasiswa", $modul))
                                    <li @if($menu == "Jadwal Mahasiswa | Moodle") class="mm-active" @endif>
                                        <a href="{{route('moodle.jadwal_mahasiswa.index')}}">Jadwal Mahasiswa</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    <li class="nav-label">Bantuan</li>
                    <li style="display: none"><a target="_blank"
                           href="{{asset('files/panduan/'.\Illuminate\Support\Facades\Session::get('peran')['aktif']).'.pdf'}}"
                           class="btn btn-warning-soft"><i class="typcn typcn-download mr-2"></i>Panduan</a>
                    </li>
                    @if(!isset(\Illuminate\Support\Facades\Session::get('user')->id_mhs))
                        <li style="display: none"><a target="_blank"
                               href="{{asset('files/panduan/peraturan_disiplin_2022.pdf')}}"
                               class="btn btn-warning-soft mt-2"><i class="typcn typcn-download mr-2"></i>Peraturan
                                Disiplin</a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div><!-- sidebar-body -->
    </nav>
    <!-- Page Content  -->
    <div class="content-wrapper">
        <div class="main-content">
            <nav class="navbar-custom-menu navbar navbar-expand-lg m-0">
                <div class="sidebar-toggle-icon" id="sidebarCollapse">
                    sidebar toggle<span></span>
                </div><!--/.sidebar toggle icon-->
                <div class="d-flex flex-grow-1">
                    <div class="nav-clock ml-auto">
                        <div class="time">
                            <span class="time-hours"></span>
                            <span class="time-min"></span>
                            <span class="time-sec"></span>
                        </div>
                    </div><!-- nav-clock -->
                    <ul class="navbar-nav flex-row align-items-center m-0">
                        <li class="nav-item dropdown notification">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                <i class="typcn typcn-group"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <h6 class="notification-title">Ganti Peran</h6>
                                <p class="notification-text">Berikut List Peran Anda</p>
                                <div class="notification-list"
                                     style="max-height: 300px; overflow-y: auto; overflow-x: hidden">
                                    @foreach(\Illuminate\Support\Facades\Session::get('peran')['all'] as $item)
                                        <div class="media new peran" data-id_peran="{{$item->id_peran}}">
                                            <div class="img-user">
                                                <img src="{{asset('adminpage/assets/dist/img/avatar.png')}}" alt="">
                                            </div>
                                            <div class="media-body" style="max-height: 200px; overflow-x: auto">
                                                <a href="{{route('login.ganti_peran', ['id' => $item->id_peran])}}"
                                                   class="text-dark">{{$item->nama_peran}}</a>
                                                @if($item->id_peran == \Illuminate\Support\Facades\Session::get('peran')['aktif'])
                                                    <span class="text-success">Aktif</span>
                                                @endif
                                            </div>
                                        </div><!--/.media -->
                                    @endforeach
                                </div>
                            </div><!--/.dropdown-menu -->
                        </li><!--/.dropdown-->
                        <li class="nav-item dropdown user-menu">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                <i class="typcn typcn-user"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div class="dropdown-header d-sm-none">
                                    <a href="" class="header-arrow"><i class="icon ion-md-arrow-back"></i></a>
                                </div>
                                <div class="user-header">
                                    <div class="img-user">
                                        @if(isset(\Illuminate\Support\Facades\Session::get('user')->id_mhs))
                                            <img
                                                src="http://siakad.stie-mandala.ac.id/_report/photo_m/{{\Illuminate\Support\Facades\Session::get('user')->nim}}.jpg"
                                                alt=""
                                                onerror="this.src='{{asset('adminpage/assets/dist/img/avatar-1.jpg')}}'">
                                        @else
                                            <img
                                                src="/files/profil_karyawan/{{\Illuminate\Support\Facades\Session::get('user')->id_personal}}/{{\Illuminate\Support\Facades\Session::get('karyawan')->path_photo}}"
                                                alt=""
                                                onerror="this.src='{{asset('adminpage/assets/dist/img/avatar-1.jpg')}}'">
                                        @endif
                                    </div><!-- img-user -->
                                    <p class="text-center">{{\Illuminate\Support\Facades\Session::get('user')->nama_lengkap}}</p>
                                </div><!-- user-header -->
                                @if(isset(\Illuminate\Support\Facades\Session::get('user')->id_mhs))
                                    <a href="{{isset(\Illuminate\Support\Facades\Session::get('user')->id_mhs) ? route('mahasiswa.dashboard.sync_password_mahasiswa') : ''}}"
                                       class="dropdown-item"><i class="typcn typcn-arrow-sync mr-2"></i>Sync Profil</a>
                                @else
                                    <a href="" class="dropdown-item"><i class="typcn typcn-user mr-2"></i>Profil</a>
                                @endif
                                <a href="{{isset(\Illuminate\Support\Facades\Session::get('user')->id_mhs) ? route('mahasiswa.dashboard.sync_password_mahasiswa') : ''}}"
                                   class="dropdown-item"><i class="typcn typcn-cog mr-2"></i>Ganti Password</a>
                                <a href="{{route('login.logout')}}"
                                   class="dropdown-item" id="btn-logout"
                                   onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                                        class="typcn typcn-key mr-2"></i>Keluar</a>
                                <form id="logout-form" action="{{ route('login.logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div><!--/.dropdown-menu -->
                        </li>
                    </ul><!--/.navbar nav-->
                </div>
            </nav><!--/.navbar-->
            <!--Content Header (Page header)-->
            <div class="content-header row align-items-center m-0">
                @yield('content-header')
            </div>
            <!--/.Content Header (Page header)-->
            <div class="body-content">
                <div class="row">
                    @yield('body-content')
                </div>
                @yield('modal')
                <div class="modal modal-primary fade" id="modal-ubah-password" tabindex="-1" role="dialog"
                     aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title font-weight-600" id="insupLabel"></h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="#" method="POST" id="ubah-password-form">
                                    @csrf
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                <button type="button" class="btn btn-success" id="btn-ubah-password">Ubah Password
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--/.body content-->
        </div><!--/.main content-->
        <footer class="footer-content">
            <div class="footer-text d-flex align-items-center justify-content-between">
                <div class="copy">© 2024 UPT-TI</div>
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

<!-- Third Party Scripts(used by this page)-->

<!--Page Active Scripts(used by this page)-->
@stack('scripts')
<!--Page Scripts(used by all page)-->
<script>
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#btn-logout").click(function () {
        $("#form.logout").submit();
    });
</script>
<script src="{{asset('adminpage/assets/dist/js/sidebar.js')}}"></script>
<script>
    $(document).ready(function () {
        $(".peran").click(function () {
            location.href = '/change-role/' + $(this).data('id_peran');
        })
        @if(\Illuminate\Support\Facades\Session::get('failed_message'))
        $.alert({
            title: 'Informasi',
            type: 'red',
            content: '{!! \Illuminate\Support\Facades\Session::get('failed_message') !!}',
            backgroundDismissAnimation: 'glow'
        });
        @endif

        @if(\Illuminate\Support\Facades\Session::get('success_message'))
        $.alert({
            title: 'Informasi',
            type: 'green',
            content: '{!! \Illuminate\Support\Facades\Session::get('success_message') !!}',
            backgroundDismissAnimation: 'glow'
        });
        @endif
        @if(\Illuminate\Support\Facades\Session::get('info_message'))
        $.alert({
            title: 'Informasi',
            type: 'blue',
            content: '{!! \Illuminate\Support\Facades\Session::get('info_message') !!}',
            backgroundDismissAnimation: 'glow'
        });
        @endif
        @if($errors->any())
        var html = "<ul>";
        @foreach ($errors->all() as $error)
            html = html + "<li>{!! $error !!}</li>";
        @endforeach
            html = html + "</ul>";
        $.alert({
            title: 'Informasi',
            type: 'blue',
            content: html,
            backgroundDismissAnimation: 'glow'
        });
        @endif
    });
</script>
</body>
</html>
