<?php

use Illuminate\Support\Facades\Route;

Route::get('/temp', [\App\Http\Controllers\tempFeederMigrasiController::class, 'temp']);
Route::get('/get-kebutuhan-khusus', [\App\Http\Controllers\tempFeederMigrasiController::class, 'getKebutuhanKhusus']);
Route::get('/get-jadwal-kuliah/{ta}', [\App\Http\Controllers\tempFeederMigrasiController::class, 'getKelasKuliah']);
Route::get('/get-anggota-am/{id}', [\App\Http\Controllers\tempFeederMigrasiController::class, 'getListAnggotaAM']);
Route::get('/get-mahasiswa/{ta}', [\App\Http\Controllers\tempFeederMigrasiController::class, 'getMahasiswa']);
Route::get('/get-peserta/{id}', [\App\Http\Controllers\tempFeederMigrasiController::class, 'getPesertaKelasKuliah']);
Route::get('/cek-feeder/{id}', [\App\Http\Controllers\tempFeederMigrasiController::class, 'cekKelasFeeder']);
Route::get('/get-list-am/{id}', [\App\Http\Controllers\tempFeederMigrasiController::class, 'getAktifitasMahasiswa']);
Route::post('/cek-feeder/', [\App\Http\Controllers\tempFeederMigrasiController::class, 'cekKelasFeederPost']);
Route::post('/delete-peserta/', [\App\Http\Controllers\tempFeederMigrasiController::class, 'deletePesertaKuliah']);
Route::post('/delete-anggota-am/', [\App\Http\Controllers\tempFeederMigrasiController::class, 'deleteAnggotaAM']);
Route::post('/delete-mahasiswa/', [\App\Http\Controllers\tempFeederMigrasiController::class, 'deleteMahasiswa']);
Route::post('/sync-mahasiswa/', [\App\Http\Controllers\tempFeederMigrasiController::class, 'syncMahasiswa']);
Route::get('/migrasi/get-am', [\App\Http\Controllers\tempFeederMigrasiController::class, 'getBackupAM']);
Route::get('/migrasi/get-am-pembimbing', [\App\Http\Controllers\tempFeederMigrasiController::class, 'getBackupAMPembimbing']);
Route::get('/migrasi/get-am-penguji', [\App\Http\Controllers\tempFeederMigrasiController::class, 'getBackupAMPenguji']);
Route::get('/migrasi/get-am-anggota', [\App\Http\Controllers\tempFeederMigrasiController::class, 'getBackupAMAnggota']);
Route::get('/migrasi/get-kurikulum', [\App\Http\Controllers\tempFeederMigrasiController::class, 'getKurikulum']);
Route::get('/migrasi/get-matkul-kurikulum', [\App\Http\Controllers\tempFeederMigrasiController::class, 'getMatkulKurikulum']);
Route::get('/migrasi/get-peserta-kelas-kuliah-lokal', [\App\Http\Controllers\tempFeederMigrasiController::class, 'getPesertaKelasKuliahLokal']);
Route::get('/migrasi/get-pengampu-kelas-kuliah-lokal', [\App\Http\Controllers\tempFeederMigrasiController::class, 'getPengampuKelasKuliah']);
Route::get('/migrasi/get-mahasiswa-baru', [\App\Http\Controllers\tempFeederMigrasiController::class, 'getMahasiswaBaru']);
Route::get('/migrasi/get-nilai-kelas', [\App\Http\Controllers\tempFeederMigrasiController::class, 'getNilaiKelasKuliah']);
Route::get('/migrasi/get-indeks-prestasi', [\App\Http\Controllers\tempFeederMigrasiController::class, 'getIndeksPrestasi']);
Route::post('/migrasi/insert-am', [\App\Http\Controllers\tempFeederMigrasiController::class, 'insertAktivitasMahasiswa']);
Route::post('/migrasi/update-am', [\App\Http\Controllers\tempFeederMigrasiController::class, 'updateAktivitasMahasiswa']);
Route::post('/migrasi/insert-am-pembimbing', [\App\Http\Controllers\tempFeederMigrasiController::class, 'insertPembimbing']);
Route::post('/migrasi/insert-am-penguji', [\App\Http\Controllers\tempFeederMigrasiController::class, 'insertPenguji']);
Route::post('/migrasi/insert-am-anggota', [\App\Http\Controllers\tempFeederMigrasiController::class, 'insertAnggota']);
Route::post('/migrasi/insert-kurikulum', [\App\Http\Controllers\tempFeederMigrasiController::class, 'insertKurikulum']);
Route::post('/migrasi/insert-matkul-kurikulum', [\App\Http\Controllers\tempFeederMigrasiController::class, 'insertMatkulKurikulum']);
Route::post('/migrasi/insert-peserta-kelas-kuliah', [\App\Http\Controllers\tempFeederMigrasiController::class, 'insertPesertaKelasKuliah']);
Route::post('/migrasi/insert-mahasiswa-baru', [\App\Http\Controllers\tempFeederMigrasiController::class, 'insertMahasiswaBaru']);
Route::post('/migrasi/delete-anggota-am', [\App\Http\Controllers\tempFeederMigrasiController::class, 'deleteAnggotaAM']);
Route::post('/migrasi/insert-pengampu-kelas-kuliah', [\App\Http\Controllers\tempFeederMigrasiController::class, 'insertPengampuKelasKuliah']);
Route::post('/migrasi/insert-nilai-kelas-kuliah', [\App\Http\Controllers\tempFeederMigrasiController::class, 'updateNilaiPerkuliahan']);
Route::post('/migrasi/insert-indeks-prestasi', [\App\Http\Controllers\tempFeederMigrasiController::class, 'updatePerkuliahanMahasiswa']);
/*
 * ------------------------------------------------------------------------
 * FRONT PAGE
 * ------------------------------------------------------------------------
*/
// HOME PAGE
Route::get('/wisuda/buku-tamu-self', [\App\Http\Controllers\FrontPage\HomeController::class, 'buku_tamu_self'])->name('frontpage.wisuda.buku_tamu_self');
Route::get('/wisuda/buku-tamu', [\App\Http\Controllers\FrontPage\HomeController::class, 'buku_tamu'])->name('frontpage.wisuda.buku_tamu');
Route::post('/wisuda/insert-tamu', [\App\Http\Controllers\FrontPage\HomeController::class, 'insert_tamu'])->name('frontpage.wisuda.insert_tamu');
Route::post('/wisuda/insert-tamu-self', [\App\Http\Controllers\FrontPage\HomeController::class, 'insert_tamu_self'])->name('frontpage.wisuda.insert_tamu_self');
Route::post('/wisuda/buku-tamu/json', [\App\Http\Controllers\FrontPage\HomeController::class, 'json_tamu'])->name('frontpage.wisuda.json_tamu');
Route::post('/wisuda/buku-tamu/detail', [\App\Http\Controllers\FrontPage\HomeController::class, 'detail_tamu'])->name('frontpage.wisuda.detail_tamu');
Route::get('/wisuda/barcode-undangan/{offset}/{limit}', [\App\Http\Controllers\AdminAkademikPage\Akademik\Wisuda\PendaftaranWisudaController::class, 'export_barcode'])->name('frontpage.wisuda.export_barcode');
Route::get('/wisuda/barcode-undangan-vvip/{offset}/{limit}', [\App\Http\Controllers\AdminAkademikPage\Akademik\Wisuda\PendaftaranWisudaController::class, 'export_barcode_vvip'])->name('frontpage.wisuda.export_barcode_vvip');
Route::get('/', [\App\Http\Controllers\FrontPage\HomeController::class, 'home'])->name('frontpage.home');
Route::get('/absensi-ngajar', [\App\Http\Controllers\FrontPage\HomeController::class, 'absensi_ngajar'])->name('frontpage.absensi_ngajar');
Route::get('/rekap-ngajar', [\App\Http\Controllers\FrontPage\HomeController::class, 'rekap_ngajar'])->name('frontpage.rekap_ngajar');
Route::get('/json/rekap-ngajar', [\App\Http\Controllers\FrontPage\HomeController::class, 'rekap_ngajar'])->name('frontpage.rekap_ngajar');
Route::post('/store/absensi-ngajar', [\App\Http\Controllers\FrontPage\HomeController::class, 'store_absensi_ngajar'])->name('frontpage.store_absensi_ngajar');
// LOGIN PAGE
Route::get('/sign-in', [\App\Http\Controllers\FrontPage\LoginController::class, 'login'])->middleware('modul:login')->name('login.login');
Route::get('/lupa-password', [\App\Http\Controllers\FrontPage\LoginController::class, 'lupa_password'])->middleware('modul:login')->name('login.lupa_password');
Route::post('/auth', [\App\Http\Controllers\FrontPage\LoginController::class, 'auth'])->name('login.auth');
Route::post('/forget-password', [\App\Http\Controllers\FrontPage\LoginController::class, 'forget_password'])->name('login.forget_password');
Route::post('/sign-out', [\App\Http\Controllers\FrontPage\LoginController::class, 'logout'])->name('login.logout');
Route::get('/change-role/{id}', [\App\Http\Controllers\FrontPage\LoginController::class, 'gantiPeran'])->name('login.ganti_peran');
/*
 * ------------------------------------------------------------------------
 * DASHBOARD PAGE
 * ------------------------------------------------------------------------
*/
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'dashboard'])->name('dashboard.dashboard')->middleware('modul:Dashboard');
/*
 * ------------------------------------------------------------------------
 * ADMIN PAGE
 * ------------------------------------------------------------------------
*/
/* ADMIN AKADEMIK */
/* ------------------------------------------------------------------------ */
/* Akademik */
// Wisuda
// Jadwal Wisuda
Route::get('/adm-akadmik/perkuliahan/jadwal-wisuda', [\App\Http\Controllers\AdminAkademikPage\Akademik\Wisuda\JadwalWisudaController::class, 'index'])->name('jadwal_wisuda.index')->middleware('modul:Pengelolaan Jadwal Wisuda');
Route::post('/adm-akadmik/perkuliahan/jadwal-wisuda/json', [\App\Http\Controllers\AdminAkademikPage\Akademik\Wisuda\JadwalWisudaController::class, 'json_get_daftar_jadwal_wisuda'])->name('jadwal_wisuda.json_get_daftar_jadwal_wisuda')->middleware('modul:Pengelolaan Jadwal Wisuda');
Route::post('/adm-akadmik/perkuliahan/jadwal-wisuda/store', [\App\Http\Controllers\AdminAkademikPage\Akademik\Wisuda\JadwalWisudaController::class, 'store'])->name('jadwal_wisuda.store')->middleware('modul:Pengelolaan Jadwal Wisuda');
Route::post('/adm-akadmik/perkuliahan/jadwal-wisuda/update', [\App\Http\Controllers\AdminAkademikPage\Akademik\Wisuda\JadwalWisudaController::class, 'update'])->name('jadwal_wisuda.update')->middleware('modul:Pengelolaan Jadwal Wisuda');
Route::post('/adm-akadmik/perkuliahan/jadwal-wisuda/delete', [\App\Http\Controllers\AdminAkademikPage\Akademik\Wisuda\JadwalWisudaController::class, 'delete'])->name('jadwal_wisuda.delete')->middleware('modul:Pengelolaan Jadwal Wisuda');
// Pendaftaran Wisuda
Route::get('/adm-akademik/akademik/pendaftaran-wisuda', [\App\Http\Controllers\AdminAkademikPage\Akademik\Wisuda\PendaftaranWisudaController::class, 'index'])->name('admin_akademik.akademik.wisuda.pendaftaran_wisuda.index')->middleware('modul:Validasi Pendaftaran Wisuda');
Route::post('/adm-akademik/akademik/pendaftaran-wisuda/json', [\App\Http\Controllers\AdminAkademikPage\Akademik\Wisuda\PendaftaranWisudaController::class, 'json_pendaftaran_wisuda'])->name('admin_akademik.akademik.wisuda.pendaftaran_wisuda.json_pendaftaran_wisuda')->middleware('modul:Validasi Pendaftaran Wisuda');
Route::post('/adm-akademik/akademik/pendaftaran-wisuda/accept', [\App\Http\Controllers\AdminAkademikPage\Akademik\Wisuda\PendaftaranWisudaController::class, 'accept_pendaftaran'])->name('admin_akademik.akademik.wisuda.pendaftaran_wisuda.accept_pendaftaran')->middleware('modul:Validasi Pendaftaran Wisuda');
Route::post('/adm-akademik/akademik/pendaftaran-wisuda/denied', [\App\Http\Controllers\AdminAkademikPage\Akademik\Wisuda\PendaftaranWisudaController::class, 'denied_pendaftaran'])->name('admin_akademik.akademik.wisuda.pendaftaran_wisuda.denied_pendaftaran')->middleware('modul:Validasi Pendaftaran Wisuda');
Route::get('/adm-akademik/akademik/pendaftaran-wisuda/export/excel/{status_pengajuan}/{kd_prodi}/{kd_konsen}', [\App\Http\Controllers\AdminAkademikPage\Akademik\Wisuda\PendaftaranWisudaController::class, 'export_pendaftar'])->name('admin_akademik.akademik.wisuda.pendaftaran_wisuda.export_pendaftar')->middleware('modul:Validasi Pendaftaran Wisuda');
Route::get('/adm-akademik/akademik/pendaftaran-wisuda/export/pdf/{status_pengajuan}/{kd_prodi}/{kd_konsen}', [\App\Http\Controllers\AdminAkademikPage\Akademik\Wisuda\PendaftaranWisudaController::class, 'export_pdf'])->name('admin_akademik.akademik.wisuda.pendaftaran_wisuda.export_pdf')->middleware('modul:Validasi Pendaftaran Wisuda');
/* Mahasiswa */
// Mahasiswa LP3I
Route::get('/adm-akademik/akademik/mahasiswa/mahasiswa-lpppi', [\App\Http\Controllers\AdminAkademikPage\Akademik\Mahasiswa\LPPPIController::class, 'index'])->name('admin_akademik.akademik.mahasiswa.mahasiswa_lpppi.index')->middleware('modul:Pengelolaan Mahasiswa LP3I Banyuwangi');
Route::post('/adm-akademik/akademik/mahasiswa/mahasiswa-lpppi/json', [\App\Http\Controllers\AdminAkademikPage\Akademik\Mahasiswa\LPPPIController::class, 'json_get_mahasiswa_lpppi'])->name('admin_akademik.akademik.mahasiswa.mahasiswa_lpppi.json_get_mahasiswa_lpppi')->middleware('modul:Pengelolaan Mahasiswa LP3I Banyuwangi');
Route::post('/adm-akademik/akademik/mahasiswa/mahasiswa-lpppi/update-lpppi', [\App\Http\Controllers\AdminAkademikPage\Akademik\Mahasiswa\LPPPIController::class, 'update_lp3i'])->name('admin_akademik.akademik.mahasiswa.mahasiswa_lpppi.update_lp3i')->middleware('modul:Pengelolaan Mahasiswa LP3I Banyuwangi');
// Student Body
Route::get('/adm-akademik/akademik/mahasiswa/student-body', [\App\Http\Controllers\AdminAkademikPage\Akademik\Mahasiswa\StudentBodyCont::class, 'index'])->name('admin_akademik.akademik.mahasiswa.student_body.index')->middleware('modul:Student Body');
Route::post('/adm-akademik/akademik/mahasiswa/student-body/json', [\App\Http\Controllers\AdminAkademikPage\Akademik\Mahasiswa\StudentBodyCont::class, 'json'])->name('admin_akademik.akademik.mahasiswa.student_body.json')->middleware('modul:Student Body');
Route::get('/adm-akademik/akademik/mahasiswa/student-body/pendaftar/{jenis}/{tahun}/{prodi}', [\App\Http\Controllers\AdminAkademikPage\Akademik\Mahasiswa\StudentBodyCont::class, 'pendaftar'])->name('admin_akademik.akademik.mahasiswa.student_body.pendaftar')->middleware('modul:Student Body');
Route::get('/adm-akademik/akademik/mahasiswa/student-body/maba/{jenis}/{tahun}/{prodi}', [\App\Http\Controllers\AdminAkademikPage\Akademik\Mahasiswa\StudentBodyCont::class, 'maba'])->name('admin_akademik.akademik.mahasiswa.student_body.maba')->middleware('modul:Student Body');
Route::get('/adm-akademik/akademik/mahasiswa/student-body/mahasiswa/{jenis}/{tahun}/{prodi}', [\App\Http\Controllers\AdminAkademikPage\Akademik\Mahasiswa\StudentBodyCont::class, 'mahasiswa'])->name('admin_akademik.akademik.mahasiswa.student_body.mahasiswa')->middleware('modul:Student Body');
// Sinkronisasi Mahasiswa dengan Siakad
Route::get('/adm-akademik/akademik/mahasiswa/sinkronisasi-mahasiswa-siakad', [\App\Http\Controllers\AdminAkademikPage\Akademik\Mahasiswa\SinkronisasiMahasiswaSiakadController::class, 'index'])->name('admin_akademik.akademik.mahasiswa.sinkronisasi_mahasiswa_siakad.index')->middleware('modul:Sinkronisasi Mahasiswa dengan Siakad');
Route::post('/adm-akademik/akademik/mahasiswa/sinkronisasi-mahasiswa-siakad/json', [\App\Http\Controllers\AdminAkademikPage\Akademik\Mahasiswa\SinkronisasiMahasiswaSiakadController::class, 'json_get_daftar_mahasiswa'])->name('admin_akademik.akademik.mahasiswa.sinkronisasi_mahasiswa_siakad.json_get_daftar_mahasiswa')->middleware('modul:Sinkronisasi Mahasiswa dengan Siakad');
Route::post('/adm-akademik/akademik/mahasiswa/sinkronisasi-mahasiswa-siakad/json-by-angkatan', [\App\Http\Controllers\AdminAkademikPage\Akademik\Mahasiswa\SinkronisasiMahasiswaSiakadController::class, 'json_get_mahasiswa_by_angkatan'])->name('admin_akademik.akademik.mahasiswa.sinkronisasi_mahasiswa_siakad.json_get_mahasiswa_by_angkatan')->middleware('modul:Sinkronisasi Mahasiswa dengan Siakad');
Route::post('/adm-akademik/akademik/mahasiswa/sinkronisasi-mahasiswa-siakad/json-by-nim', [\App\Http\Controllers\AdminAkademikPage\Akademik\Mahasiswa\SinkronisasiMahasiswaSiakadController::class, 'json_get_mahasiswa_by_nim'])->name('admin_akademik.akademik.mahasiswa.sinkronisasi_mahasiswa_siakad.json_get_mahasiswa_by_nim')->middleware('modul:Sinkronisasi Mahasiswa dengan Siakad');
Route::post('/adm-akademik/akademik/mahasiswa/sinkronisasi-mahasiswa-siakad/synchron', [\App\Http\Controllers\AdminAkademikPage\Akademik\Mahasiswa\SinkronisasiMahasiswaSiakadController::class, 'json_syncron_data'])->name('admin_akademik.akademik.mahasiswa.sinkronisasi_mahasiswa_siakad.json_sync_data')->middleware('modul:Sinkronisasi Mahasiswa dengan Siakad');
/* Perkuliahan */
// Tahun Akademik
Route::get('/adm-akademik/akademik/perkuliahan/sinkronisasi-tahun-akademik-siakad', [\App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah\SemesterController::class, 'index'])->name('admin_akademik.akademik.perkuliahan.sinkronisasi_tahun_akademik_siakad.index')->middleware('modul:Sinkronisasi Tahun Akademik dengan Siakad');
Route::post('/adm-akademik/akademik/perkuliahan/sinkronisasi-tahun-akademik-siakad/json', [\App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah\SemesterController::class, 'json_get_daftar'])->name('admin_akademik.akademik.perkuliahan.sinkronisasi_tahun_akademik_siakad.json_get_daftar')->middleware('modul:Sinkronisasi Tahun Akademik dengan Siakad');
Route::post('/adm-akademik/akademik/perkuliahan/sinkronisasi-tahun-akademik-siakad/json-tahun-akademik-siakad', [\App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah\SemesterController::class, 'json_get_tahun_akademik'])->name('admin_akademik.akademik.perkuliahan.sinkronisasi_tahun_akademik_siakad.json_get_tahun_akademik')->middleware('modul:Sinkronisasi Tahun Akademik dengan Siakad');
Route::post('/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/json-by-tahun-akademik', [\App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah\SemesterController::class, 'json_get_tahun_akademik_by_tahun_akademik'])->name('admin_akademik.akademik.perkuliahan.sinkronisasi_tahun_akademik_siakad.json_get_tahun_akademik_by_tahun_akademik')->middleware('modul:Sinkronisasi Tahun Akademik dengan Siakad');
Route::post('/adm-akademik/akademik/perkuliahan/sinkronisasi-tahun-akademik-siakad/synchron', [\App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah\SemesterController::class, 'json_syncron_data'])->name('admin_akademik.akademik.perkuliahan.sinkronisasi_tahun_akademik_siakad.json_syncron_data')->middleware('modul:Sinkronisasi Tahun Akademik dengan Siakad');
// Jadwal Kuliah
Route::get('/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/{filter?}', [\App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah\JadwalMatakuliahController::class, 'index'])->name('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_siakad.index')->middleware('modul:Sinkronisasi Jadwal Kuliah dengan Siakad');
Route::post('/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/json', [\App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah\JadwalMatakuliahController::class, 'json_get_daftar'])->name('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_siakad.json_get_daftar')->middleware('modul:Sinkronisasi Jadwal Kuliah dengan Siakad');
Route::post('/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/json-by-tahun-akademik', [\App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah\JadwalMatakuliahController::class, 'json_get_jadwal_kuliah_siakad'])->name('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_siakad.json_get_jadwal_kuliah_siakad')->middleware('modul:Sinkronisasi Jadwal Kuliah dengan Siakad');
Route::post('/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/json-by-id', [\App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah\JadwalMatakuliahController::class, 'json_get_jadwal_by_id'])->name('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_siakad.json_get_jadwal_by_id')->middleware('modul:Sinkronisasi Jadwal Kuliah dengan Siakad');
Route::post('/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/synchron', [\App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah\JadwalMatakuliahController::class, 'json_syncron_data'])->name('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_siakad.json_syncron_data')->middleware('modul:Sinkronisasi Jadwal Kuliah dengan Siakad');
Route::get('/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/detail/{id}', [\App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah\JadwalMatakuliahController::class, 'detail'])->name('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_siakad.detail')->middleware('modul:Sinkronisasi Jadwal Kuliah dengan Siakad');
Route::post('/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/set-jadwal', [\App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah\JadwalMatakuliahController::class, 'set_jenis_jadwal'])->name('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_siakad.set_jenis_jadwal')->middleware('modul:Sinkronisasi Jadwal Kuliah dengan Siakad');
// Jadwal Mahasiswa
Route::get('/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-mahasiswa', [\App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah\JadwalMahasiswaController::class, 'index'])->name('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_mahasiswa.index')->middleware('modul:Sinkronisasi Jadwal Mahasiswa dengan Siakad');
Route::post('/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-mahasiswa/json', [\App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah\JadwalMahasiswaController::class, 'json_get_daftar'])->name('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_mahasiswa.json_get_daftar')->middleware('modul:Sinkronisasi Jadwal Mahasiswa dengan Siakad');
Route::post('/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-mahasiswa/json-by-tahun-akademik', [\App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah\JadwalMahasiswaController::class, 'json_get_jadwal_kuliah_by_tahun_akademik'])->name('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_mahasiswa.json_get_jadwal_kuliah_by_tahun_akademik')->middleware('modul:Sinkronisasi Jadwal Mahasiswa dengan Siakad');
Route::post('/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-mahasiswa/json-by-tahun-akademik-nim', [\App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah\JadwalMahasiswaController::class, 'json_get_jadwal_kuliah_by_tahun_akademik_nim'])->name('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_mahasiswa.json_get_jadwal_kuliah_by_tahun_akademik_nim')->middleware('modul:Sinkronisasi Jadwal Mahasiswa dengan Siakad');
Route::post('/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-mahasiswa/synchron', [\App\Http\Controllers\AdminAkademikPage\Akademik\Matakuliah\JadwalMahasiswaController::class, 'json_syncron_data'])->name('admin_akademik.akademik.jadwal_kuliah.sinkronisasi_jadwal_kuliah_mahasiswa.json_syncron_data')->middleware('modul:Sinkronisasi Jadwal Mahasiswa dengan Siakad');

// Konsentrasi Jurusan
Route::get('/adm-akadmik/perkuliahan/konsentrasi-jurusan', [\App\Http\Controllers\AdminAkademikPage\Perkuliahan\KonsentrasiJurusanController::class, 'konsentrasi_jurusan'])->name('konsentrasi_jurusan.konsentrasi_jurusan')->middleware('modul:Konsentrasi Jurusan');
Route::post('/adm-akadmik/perkuliahan/konsentrasi-jurusan/json/get-konsentrasi-jurusan', [\App\Http\Controllers\AdminAkademikPage\Perkuliahan\KonsentrasiJurusanController::class, 'json_get_konsentrasi_jurusan'])->name('konsentrasi_jurusan.json_get_konsentrasi_jurusan')->middleware('modul:Konsentrasi Jurusan');
Route::post('/adm-akadmik/perkuliahan/konsentrasi-jurusan/store-update', [\App\Http\Controllers\AdminAkademikPage\Perkuliahan\KonsentrasiJurusanController::class, 'insup'])->name('konsentrasi_jurusan.insup')->middleware('modul:Konsentrasi Jurusan');
Route::post('/adm-akadmik/perkuliahan/konsentrasi-jurusan/delete', [\App\Http\Controllers\AdminAkademikPage\Perkuliahan\KonsentrasiJurusanController::class, 'delete'])->name('konsentrasi_jurusan.delete')->middleware('modul:Konsentrasi Jurusan');
/* Sinkronisasi Data */
// Program Studi
Route::get('/adm-akadmik/sinkronisasi-data/program-studi', [\App\Http\Controllers\AdminAkademikPage\SinkronisasiData\ProgramStudiController::class, 'program_studi'])->name('sinkronisasi_data.program_studi.program_studi')->middleware('modul:Syncron Program Studi');
Route::post('/adm-akadmik/sinkronisasi-data/program-studi/json/get-program-studi', [\App\Http\Controllers\AdminAkademikPage\SinkronisasiData\ProgramStudiController::class, 'json_get_program_studi'])->name('sinkronisasi_data.program_studi.json_get_program_studi')->middleware('modul:Syncron Program Studi');
Route::post('/adm-akadmik/sinkronisasi-data/program-studi/json/perbandingan-data-feeder', [\App\Http\Controllers\AdminAkademikPage\SinkronisasiData\ProgramStudiController::class, 'json_perbandingan_data_feeder'])->name('sinkronisasi_data.program_studi.json_perbandingan_data_feeder')->middleware('modul:Syncron Program Studi');
Route::get('/adm-akadmik/sinkronisasi-data/program-studi/sync/{id?}', [\App\Http\Controllers\AdminAkademikPage\SinkronisasiData\ProgramStudiController::class, 'sync_data'])->name('sinkronisasi_data.program_studi.sync_data')->middleware('modul:Syncron Program Studi');
// Mahasiswa
Route::get('/adm-akadmik/sinkronisasi-data/mahasiswa', [\App\Http\Controllers\AdminAkademikPage\SinkronisasiData\MahasiswaController::class, 'mahasiswa'])->name('sinkronisasi_data.mahasiswa.mahasiswa')->middleware('modul:Syncron Mahasiswa');
Route::post('/adm-akadmik/sinkronisasi-data/mahasiswa/json/get-mahasiswa', [\App\Http\Controllers\AdminAkademikPage\SinkronisasiData\MahasiswaController::class, 'json_get_mahasiswa'])->name('sinkronisasi_data.mahasiswa.json_get_mahasiswa')->middleware('modul:Syncron Mahasiswa');
Route::post('/adm-akadmik/sinkronisasi-data/mahasiswa/json/syncron', [\App\Http\Controllers\AdminAkademikPage\SinkronisasiData\MahasiswaController::class, 'json_syncron'])->name('sinkronisasi_data.mahasiswa.json_syncron')->middleware('modul:Syncron Mahasiswa');
Route::get('/adm-akadmik/sinkronisasi-data/mahasiswa/json/get-mahasiswa-feeder/{nim?}', [\App\Http\Controllers\AdminAkademikPage\SinkronisasiData\MahasiswaController::class, 'json_get_mahasiswa_feeder'])->name('sinkronisasi_data.mahasiswa.json_get_mahasiswa_feeder')->middleware('modul:Syncron Mahasiswa');
// Dosen
Route::get('/adm-akadmik/sinkronisasi-data/dosen', [\App\Http\Controllers\AdminAkademikPage\SinkronisasiData\DosenController::class, 'dosen'])->name('sinkronisasi_data.dosen.dosen')->middleware('modul:Syncron Dosen');
Route::post('/adm-akadmik/sinkronisasi-data/dosen/json/get-dosen', [\App\Http\Controllers\AdminAkademikPage\SinkronisasiData\DosenController::class, 'json_get_dosen'])->name('sinkronisasi_data.dosen.json_get_dosen')->middleware('modul:Syncron Dosen');
Route::get('/adm-akadmik/sinkronisasi-data/dosen/json/get-dosen-feeder/{nidn?}', [\App\Http\Controllers\AdminAkademikPage\SinkronisasiData\DosenController::class, 'json_get_dosen_feeder'])->name('sinkronisasi_data.dosen.json_get_dosen_feeder')->middleware('modul:Syncron Dosen');
Route::post('/adm-akadmik/sinkronisasi-data/dosen/json/syncron', [\App\Http\Controllers\AdminAkademikPage\SinkronisasiData\DosenController::class, 'json_syncron'])->name('sinkronisasi_data.dosen.json_syncron')->middleware('modul:Syncron Dosen');
/* ------------------------------------------------------------------------ */
/* ADMIN UPT-TI */
/* ------------------------------------------------------------------------ */
/* Akademik */
// Validasi Pengajuan Sertifikat Labkom
Route::get('/adm-uptti/akademik/sertifikat-labkom/daftar-pengajuan', [\App\Http\Controllers\AdminUpttiPage\Akademik\SertifikatLabkomController::class, 'pengajuan_sertifikat'])->name('akademik.sertifikat_labkom.validasi.pengajuan_sertifikat')->middleware('modul:Validasi Pengajuan Sertifikat Laboratorium Komputer');
Route::post('/adm-uptti/akademik/sertifikat-labkom/json/daftar-pengajuan', [\App\Http\Controllers\AdminUpttiPage\Akademik\SertifikatLabkomController::class, 'json_pengajuan_sertifikat'])->name('akademik.sertifikat_labkom.validasi.json_pengajuan_sertifikat')->middleware('modul:Validasi Pengajuan Sertifikat Laboratorium Komputer');
Route::post('/adm-uptti/akademik/sertifikat-labkom/json/tolak-pengajuan', [\App\Http\Controllers\AdminUpttiPage\Akademik\SertifikatLabkomController::class, 'tolak_pengajuan'])->name('akademik.sertifikat_labkom.validasi.tolak_pengajuan')->middleware('modul:Validasi Pengajuan Sertifikat Laboratorium Komputer');
Route::post('/adm-uptti/akademik/sertifikat-labkom/json/terima-pengajuan', [\App\Http\Controllers\AdminUpttiPage\Akademik\SertifikatLabkomController::class, 'accept_pengajuan'])->name('akademik.sertifikat_labkom.validasi.terima_pengajuan')->middleware('modul:Validasi Pengajuan Sertifikat Laboratorium Komputer');
// Validasi Pengelolaan Nomor Sertifikat
Route::get('/adm-uptti/akademik/nomor-sertifikat/daftar-nomor-sertifikat', [\App\Http\Controllers\AdminUpttiPage\Akademik\NomorSertifikatController::class, 'index'])->name('akademik.nomor_sertifikat.index')->middleware('modul:Pengelolaan Nomor Sertifikat');
Route::post('/adm-uptti/akademik/nomor-sertifikat/json/daftar-nomor-sertifikat', [\App\Http\Controllers\AdminUpttiPage\Akademik\NomorSertifikatController::class, 'json_nomor_sertifikat'])->name('akademik.nomor_sertifikat.json_nomor_sertifikat')->middleware('modul:Pengelolaan Nomor Sertifikat');
Route::post('/adm-uptti/akademik/nomor-sertifikat/store', [\App\Http\Controllers\AdminUpttiPage\Akademik\NomorSertifikatController::class, 'store'])->name('akademik.nomor_sertifikat.store')->middleware('modul:Pengelolaan Nomor Sertifikat');
Route::post('/adm-uptti/akademik/nomor-sertifikat/update', [\App\Http\Controllers\AdminUpttiPage\Akademik\NomorSertifikatController::class, 'update'])->name('akademik.nomor_sertifikat.update')->middleware('modul:Pengelolaan Nomor Sertifikat');
/*
 * ------------------------------------------------------------------------
 * MAHASISWA PAGE
 * ------------------------------------------------------------------------
*/
/* Dashboard */
Route::post('/mhs/dashboard/get-index-prestasi', [\App\Http\Controllers\MahasiswaPage\DashboardController::class, 'get_index_prestasi'])->name('mahasiswa.dashboard.get_index_prestasi')->middleware('modul:Dashboard');
Route::get('/mhs/account/sync-profile', [\App\Http\Controllers\FrontPage\LoginController::class, 'sync_password_mahasiswa'])->name('mahasiswa.dashboard.sync_password_mahasiswa')->middleware('modul:Dashboard');

/* Akademik */
// Sertifikat Labkom
Route::get('/mhs/sertifikat-labkom', [\App\Http\Controllers\MahasiswaPage\Akademik\SertifikatLabkomController::class, 'index'])->name('mahasiswa.akademik.sertifikat_labkom.index')->middleware('modul:Pengajuan Sertifikat Laboratorium Komputer');
Route::get('/mhs/sertifikat-labkom/ajukan', [\App\Http\Controllers\MahasiswaPage\Akademik\SertifikatLabkomController::class, 'add_pengajuan'])->name('mahasiswa.akademik.sertifikat_labkom.add_pengajuan')->middleware('modul:Pengajuan Sertifikat Laboratorium Komputer');
Route::get('/mhs/sertifikat-labkom/generate/{id_sertifikat}', [\App\Http\Controllers\MahasiswaPage\Akademik\SertifikatLabkomController::class, 'generate_sertifikat'])->name('mahasiswa.akademik.sertifikat_labkom.generate')->middleware('modul:Pengajuan Sertifikat Laboratorium Komputer');
// for QR
Route::get('/mhs/sertifikat-labkom/download/{id_sertifikat}', [\App\Http\Controllers\MahasiswaPage\Akademik\SertifikatLabkomController::class, 'generate_sertifikat'])->name('mahasiswa.akademik.sertifikat_labkom.download');
// Pengajuan Tugas Akhir
Route::get('/mhs/tugas-akhir', [\App\Http\Controllers\MahasiswaPage\Akademik\PengajuanTugasAkhirController::class, 'index'])->name('mahasiswa.akademik.pengajuan_tugas_akhir.index')->middleware('modul:Pengajuan Tugas Akhir');
// Pendaftaran Wisuda
Route::get('/mhs/pendaftaran-wisuda', [\App\Http\Controllers\MahasiswaPage\Akademik\PendaftaranWisudaController::class, 'index'])->name('mahasiswa.akademik.pendaftaran_wisuda.index')->middleware('modul:Pendaftaran Wisuda');
Route::get('/mhs/kartu-wisuda', [\App\Http\Controllers\MahasiswaPage\Akademik\PendaftaranWisudaController::class, 'getKartu'])->name('mahasiswa.akademik.pendaftaran_wisuda.getKartu')->middleware('modul:Pendaftaran Wisuda');
Route::post('/mhs/pendaftaran-wisuda/daftar', [\App\Http\Controllers\MahasiswaPage\Akademik\PendaftaranWisudaController::class, 'add_pengajuan'])->name('mahasiswa.akademik.pendaftaran_wisuda.add_pengajuan')->middleware('modul:Pendaftaran Wisuda');
// Kuesioner Wisuda
Route::get('/mhs/kuesioner/kepuasan-wisudawan', [\App\Http\Controllers\MahasiswaPage\Akademik\KuesionerKepuasanWisudawanController::class, 'index'])->name('mahasiswa.akademik.kuesioner_kepuasan_wisudawan.index')->middleware('modul:Pengisian Kuesioner Kepuasan Wisudawan');
Route::post('/mhs/kuesioner/kepuasan-wisudawan/add-response', [\App\Http\Controllers\MahasiswaPage\Akademik\KuesionerKepuasanWisudawanController::class, 'insert_response'])->name('mahasiswa.akademik.kuesioner_kepuasan_wisudawan.insert_response')->middleware('modul:Pengisian Kuesioner Kepuasan Wisudawan');
// Kuesioner Kepuasan Mahasiswa
Route::get('/mhs/kuesioner/kepuasan-mahasiswa', [\App\Http\Controllers\MahasiswaPage\Akademik\KuesionerKepuasanMahasiswaThdpKinerjaManajemenController::class, 'index'])->name('mahasiswa.akademik.kuesioner_kepuasan_mahasiswa.index')->middleware('modul:Pengisian Kuesioner Kepuasan Mahasiswa');
Route::post('/mhs/kuesioner/kepuasan-mahasiswa/add-response', [\App\Http\Controllers\MahasiswaPage\Akademik\KuesionerKepuasanMahasiswaThdpKinerjaManajemenController::class, 'insert_response'])->name('mahasiswa.akademik.kuesioner_kepuasan_mahasiswa.insert_response')->middleware('modul:Pengisian Kuesioner Kepuasan Mahasiswa');
// SKPI
Route::get('/mhs/skpi', [\App\Http\Controllers\MahasiswaPage\Akademik\SKPIController::class, 'index'])->name('mahasiswa.akademik.skpi.index')->middleware('modul:Pengisian SPI');
Route::post('/mhs/skpi/json', [\App\Http\Controllers\MahasiswaPage\Akademik\SKPIController::class, 'json_daftar'])->name('mahasiswa.akademik.skpi.json_daftar')->middleware('modul:Pengisian SPI');
Route::post('/mhs/skpi/insup', [\App\Http\Controllers\MahasiswaPage\Akademik\SKPIController::class, 'insup'])->name('mahasiswa.akademik.skpi.insup')->middleware('modul:Pengisian SPI');

// Jadwal Mahasiswa
Route::get('/mhs/akademik/perkuliahan/jadwal-mahasiswa', [\App\Http\Controllers\MahasiswaPage\Akademik\JadwalMahasiswaController::class, 'index'])->name('mahasiswa.akademik.jadwal_kuliah.index')->middleware('modul:Sinkronisasi Jadwal Mahasiswa dengan Siakad');
Route::post('/mhs/akademik/perkuliahan/jadwal-mahasiswa/json', [\App\Http\Controllers\MahasiswaPage\Akademik\JadwalMahasiswaController::class, 'json_get_daftar'])->name('mahasiswa.akademik.jadwal_kuliah.json_get_daftar')->middleware('modul:Sinkronisasi Jadwal Mahasiswa dengan Siakad');
Route::post('/mhs/akademik/perkuliahan/jadwal-mahasiswa/json-by-tahun-akademik', [\App\Http\Controllers\MahasiswaPage\Akademik\JadwalMahasiswaController::class, 'json_get_jadwal_kuliah_by_tahun_akademik'])->name('mahasiswa.akademik.jadwal_kuliah.json_get_jadwal_kuliah_by_tahun_akademik')->middleware('modul:Sinkronisasi Jadwal Mahasiswa dengan Siakad');
Route::post('/mhs/akademik/perkuliahan/jadwal-mahasiswa/synchron', [\App\Http\Controllers\MahasiswaPage\Akademik\JadwalMahasiswaController::class, 'json_syncron_data'])->name('mahasiswa.akademik.jadwal_kuliah.json_syncron_data')->middleware('modul:Sinkronisasi Jadwal Mahasiswa dengan Siakad');

/*
 * ------------------------------------------------------------------------
 * DOSEN PAGE
 * ------------------------------------------------------------------------
*/
/* Akademik */
// Absensi Ngajar Dosen
Route::get('/dosen/akademik/absen-mengajar', [\App\Http\Controllers\DosenPage\Akadmik\AbsensiMengajarController::class, 'absensi_ngajar'])->name('dosen.akademik.absen_mengajar.absensi_ngajar')->middleware('modul:Melihat Rekap Absensi Mengajar');
Route::post('/dosen/akademik/absen-mengajar/store', [\App\Http\Controllers\DosenPage\Akadmik\AbsensiMengajarController::class, 'store_absensi_ngajar'])->name('dosen.akademik.absen_mengajar.store_absensi_ngajar')->middleware('modul:Melihat Rekap Absensi Mengajar');
// Rekapitulasi Absen Mengajar
Route::get('/dosen/akademik/rekapitulasi-absen-mengajar', [\App\Http\Controllers\DosenPage\Akadmik\RekapitulasiAbsenMengajarController::class, 'index'])->name('dosen.akademik.rekapitulasi_absen_mengajar.index')->middleware('modul:Melihat Rekap Absensi Mengajar');
Route::post('/dosen/akademik/rekapitulasi-absen-mengajar/json', [\App\Http\Controllers\DosenPage\Akadmik\RekapitulasiAbsenMengajarController::class, 'json_rekapitulasi_absen_mengajar'])->name('dosen.akademik.rekapitulasi_absen_mengajar.json_rekapitulasi_absen_mengajar')->middleware('modul:Melihat Rekap Absensi Mengajar');
Route::get('/dosen/akademik/rekapitulasi-absen-mengajar/export-pdf/{tgl_awal}/{tgl_akhir}/{search?}', [\App\Http\Controllers\DosenPage\Akadmik\RekapitulasiAbsenMengajarController::class, 'export_pdf'])->name('dosen.akademik.rekapitulasi_absen_mengajar.export_pdf')->middleware('modul:Melihat Rekap Absensi Mengajar');
Route::get('/dosen/akademik/rekapitulasi-absen-mengajar/export-excel/{tgl_awal}/{tgl_akhir}', [\App\Http\Controllers\DosenPage\Akadmik\RekapitulasiAbsenMengajarController::class, 'export_excel'])->name('dosen.akademik.rekapitulasi_absen_mengajar.export_excel')->middleware('modul:Melihat Rekap Absensi Mengajar');
Route::post('/dosen/akademik/rekapitulasi-absen-mengajar/delete', [\App\Http\Controllers\DosenPage\Akadmik\RekapitulasiAbsenMengajarController::class, 'delete_rekap'])->name('dosen.akademik.rekapitulasi_absen_mengajar.delete_rekap')->middleware('modul:Melihat Rekap Absensi Mengajar');
// Daftar Matakuliah
Route::get('/dosen/akademik/daftar-matakuliah', [\App\Http\Controllers\DosenPage\Akadmik\MatakuliahController::class, 'index'])->name('dosen.akademik.daftar_matakuliah.index')->middleware('modul:Dosen - Daftar Matakuliah');
Route::post('/dosen/akademik/daftar-matakuliah/json', [\App\Http\Controllers\DosenPage\Akadmik\MatakuliahController::class, 'json_daftar_matakuliah'])->name('dosen.akademik.daftar_matakuliah.json')->middleware('modul:Dosen - Daftar Matakuliah');
Route::get('/dosen/akademik/daftar-matakuliah/export-pdf/{tahun_akademik}/{search?}', [\App\Http\Controllers\DosenPage\Akadmik\MatakuliahController::class, 'export_pdf'])->name('dosen.akademik.daftar_matakuliah.export_pdf')->middleware('modul:Dosen - Daftar Matakuliah');
Route::get('/dosen/akademik/daftar-matakuliah/export-peserta-pdf/{jadwal_kuliah}', [\App\Http\Controllers\DosenPage\Akadmik\MatakuliahController::class, 'export_peserta_pdf'])->name('dosen.akademik.daftar_matakuliah.export_peserta_pdf')->middleware('modul:Dosen - Daftar Matakuliah');
// Perwalian
Route::get('/dosen/akademik/perwalian/list-mahasiswa', [\App\Http\Controllers\DosenPage\Akadmik\PerwalianController::class, 'daftar_mahasiswa'])->name('dosen.akademik.perwalian.daftar_mahasiswa')->middleware('modul:Perwalian Mahasiswa');
Route::post('/dosen/akademik/perwalian/list-mahasiswa/json', [\App\Http\Controllers\DosenPage\Akadmik\PerwalianController::class, 'json_daftar_mahasiswa'])->name('dosen.akademik.perwalian.json_daftar_mahasiswa')->middleware('modul:Perwalian Mahasiswa');
/* Keuangan */
// Honorarium Mengajar
Route::get('/dosen/keuangan/honorarium/honorarium-mengajar', [\App\Http\Controllers\DosenPage\Keuangan\HonorariumMengajarController::class, 'index'])->name('dosen_page.keuangan.honorarium.honorarium_mengajar.index')->middleware('modul:Melihat Honorarium Mengajar');
Route::post('/dosen/keuangan/honorarium/honorarium-mengajar/json', [\App\Http\Controllers\DosenPage\Keuangan\HonorariumMengajarController::class, 'json'])->name('dosen_page.keuangan.honorarium.honorarium_mengajar.json')->middleware('modul:Melihat Honorarium Mengajar');
Route::get('/dosen/keuangan/honorarium/honorarium-mengajar/detail/{id_honorarium}', [\App\Http\Controllers\DosenPage\Keuangan\HonorariumMengajarController::class, 'detail'])->name('dosen_page.keuangan.honorarium.honorarium_mengajar.detail')->middleware('modul:Melihat Honorarium Mengajar');
Route::get('/dosen/keuangan/honorarium/honorarium-mengajar/slip-gaji/{id_honorarium}', [\App\Http\Controllers\DosenPage\Keuangan\HonorariumMengajarController::class, 'slip_gaji'])->name('dosen_page.keuangan.honorarium.honorarium_mengajar.slip_gaji')->middleware('modul:Melihat Honorarium Mengajar');
Route::post('/dosen/keuangan/honorarium/honorarium-mengajar/ajukan-perbaikan', [\App\Http\Controllers\DosenPage\Keuangan\HonorariumMengajarController::class, 'ajukan_perbaikan'])->name('dosen_page.keuangan.honorarium.honorarium_mengajar.ajukan_perbaikan')->middleware('modul:Melihat Honorarium Mengajar');
// Honorarium Koreksi
Route::get('/dosen/keuangan/honorarium/honorarium-koreksi', [\App\Http\Controllers\DosenPage\Keuangan\HonorariumKoreksiController::class, 'index'])->name('dosen_page.keuangan.honorarium.honorarium_koreksi.index')->middleware('modul:Melihat Honorarium Koreksi');
Route::post('/dosen/keuangan/honorarium/honorarium-koreksi/json', [\App\Http\Controllers\DosenPage\Keuangan\HonorariumKoreksiController::class, 'json'])->name('dosen_page.keuangan.honorarium.honorarium_koreksi.json')->middleware('modul:Melihat Honorarium Koreksi');
Route::get('/dosen/keuangan/honorarium/honorarium-koreksi/detail/{id_honorarium}', [\App\Http\Controllers\DosenPage\Keuangan\HonorariumKoreksiController::class, 'detail'])->name('dosen_page.keuangan.honorarium.honorarium_koreksi.detail')->middleware('modul:Melihat Honorarium Koreksi');
Route::get('/dosen/keuangan/honorarium/honorarium-koreksi/slip-gaji/{id_honorarium}', [\App\Http\Controllers\DosenPage\Keuangan\HonorariumKoreksiController::class, 'slip_gaji'])->name('dosen_page.keuangan.honorarium.honorarium_koreksi.slip_gaji')->middleware('modul:Melihat Honorarium Koreksi');
Route::post('/dosen/keuangan/honorarium/honorarium-koreksi/ajukan-perbaikan', [\App\Http\Controllers\DosenPage\Keuangan\HonorariumKoreksiController::class, 'ajukan_perbaikan'])->name('dosen_page.keuangan.honorarium.honorarium_koreksi.ajukan_perbaikan')->middleware('modul:Melihat Honorarium Koreksi');
// CV
Route::get('/dosen/cv', [\App\Http\Controllers\DosenPage\CV\CurriculumVitaeController::class, 'index'])->name('dosen.cv.curriculum_vitae.index')->middleware('modul:Curriculum Vitae');
Route::post('/dosen/cv/update-pendidikan', [\App\Http\Controllers\DosenPage\CV\CurriculumVitaeController::class, 'update_pendidikan'])->name('dosen.cv.curriculum_vitae.update_pendidikan')->middleware('modul:Curriculum Vitae');
Route::post('/dosen/cv/riwayat-publikasi-jurnal-json', [\App\Http\Controllers\DosenPage\CV\CurriculumVitaeController::class, 'riwayat_publikasi_jurnal'])->name('dosen.cv.curriculum_vitae.riwayat_publikasi_jurnal')->middleware('modul:Curriculum Vitae');
Route::post('/dosen/cv/store-riwayat-publikasi-jurnal', [\App\Http\Controllers\DosenPage\CV\CurriculumVitaeController::class, 'store_publikasi_jurnal'])->name('dosen.cv.curriculum_vitae.store_publikasi_jurnal')->middleware('modul:Curriculum Vitae');

/*
 * ------------------------------------------------------------------------
 * HRD PAGE
 * ------------------------------------------------------------------------
*/
/* Data Kepegawaian */
// Rekap SDM
Route::get('/hrd/data-kepegawaian/rekap-sdm/kecukupan-dosen', [\App\Http\Controllers\HRDPage\RekapSDMCont::class, 'kecukupan_dosen'])->name('hrd_page.rekap_sdm.kecukupan_dosen')->middleware('modul:Rekap SDM');
Route::get('/hrd/data-kepegawaian/rekap-sdm/jabatan-akademik-dosen', [\App\Http\Controllers\HRDPage\RekapSDMCont::class, 'jabatan_akademik_dosen'])->name('hrd_page.rekap_sdm.jabatan_akademik_dosen')->middleware('modul:Rekap SDM');
Route::get('/hrd/data-kepegawaian/rekap-sdm/sertifikasi-dosen', [\App\Http\Controllers\HRDPage\RekapSDMCont::class, 'sertifikasi_dosen'])->name('hrd_page.rekap_sdm.sertifikasi_dosen')->middleware('modul:Rekap SDM');
Route::get('/hrd/data-kepegawaian/rekap-sdm/dosen-tidak-tetap', [\App\Http\Controllers\HRDPage\RekapSDMCont::class, 'dosen_tidak_tetap'])->name('hrd_page.rekap_sdm.dosen_tidak_tetap')->middleware('modul:Rekap SDM');
Route::get('/hrd/data-kepegawaian/rekap-sdm/rasio-dosen', [\App\Http\Controllers\HRDPage\RekapSDMCont::class, 'rasio_dosen'])->name('hrd_page.rekap_sdm.rasio_dosen')->middleware('modul:Rekap SDM');
Route::post('/hrd/data-kepegawaian/rekap-sdm/rasio-dosen-json', [\App\Http\Controllers\HRDPage\RekapSDMCont::class, 'rasio_dosen_json'])->name('hrd_page.rekap_sdm.rasio_dosen_json')->middleware('modul:Rekap SDM');
Route::get('/hrd/data-kepegawaian/rekap-sdm/detail-dosen/{jenis}/{kode1?}/{kode2?}', [\App\Http\Controllers\HRDPage\RekapSDMCont::class, 'detail_dosen'])->name('hrd_page.rekap_sdm.detail_dosen')->middleware('modul:Rekap SDM');
Route::post('/hrd/data-kepegawaian/rekap-sdm/detail-dosen-json/{jenis}', [\App\Http\Controllers\HRDPage\RekapSDMCont::class, 'detail_dosen_json'])->name('hrd_page.rekap_sdm.detail_dosen_json')->middleware('modul:Rekap SDM');
Route::get('/hrd/data-kepegawaian/rekap-sdm/detail-mahasiswa/{prodi}/{angkatan?}', [\App\Http\Controllers\HRDPage\RekapSDMCont::class, 'detail_mahasiswa'])->name('hrd_page.rekap_sdm.detail_mahasiswa')->middleware('modul:Rekap SDM');
Route::post('/hrd/data-kepegawaian/rekap-sdm/detail-mahasiswa-json', [\App\Http\Controllers\HRDPage\RekapSDMCont::class, 'detail_mahasiswa_json'])->name('hrd_page.rekap_sdm.detail_mahasiswa_json')->middleware('modul:Rekap SDM');
// List Data Pegawai
Route::get('/hrd/data-kepegawaian/list-data-pegawai', [\App\Http\Controllers\HRDPage\DataKepegawaian\DataPegawaiController::class, 'index'])->name('hrd.data_kepegawaian.list_data_pegawai.index')->middleware('modul:Pengelolaan Data Kepegawaian');
Route::post('/hrd/data-kepegawaian/list-data-pegawai/json', [\App\Http\Controllers\HRDPage\DataKepegawaian\DataPegawaiController::class, 'json_get_daftar_pegawai'])->name('hrd.data_kepegawaian.list_data_pegawai.json_get_daftar_pegawai')->middleware('modul:Pengelolaan Data Kepegawaian');
Route::get('/hrd/data-kepegawaian/create-data-pegawai/{jenis_karyawan}', [\App\Http\Controllers\HRDPage\DataKepegawaian\DataPegawaiController::class, 'create'])->name('hrd.data_kepegawaian.list_data_pegawai.create')->middleware('modul:Pengelolaan Data Kepegawaian');
Route::post('/hrd/data-kepegawaian/create-data-pegawai/store', [\App\Http\Controllers\HRDPage\DataKepegawaian\DataPegawaiController::class, 'store'])->name('hrd.data_kepegawaian.list_data_pegawai.store')->middleware('modul:Pengelolaan Data Kepegawaian');
Route::post('/hrd/data-kepegawaian/create-data-pegawai/delete', [\App\Http\Controllers\HRDPage\DataKepegawaian\DataPegawaiController::class, 'delete'])->name('hrd.data_kepegawaian.list_data_pegawai.delete')->middleware('modul:Pengelolaan Data Kepegawaian');
Route::get('/hrd/data-kepegawaian/detail-data-pegawai/{id}', [\App\Http\Controllers\HRDPage\DataKepegawaian\DataPegawaiController::class, 'detail'])->name('hrd.data_kepegawaian.list_data_pegawai.detail')->middleware('modul:Pengelolaan Data Kepegawaian');
Route::get('/hrd/data-kepegawaian/edit-data-pegawai/{id}', [\App\Http\Controllers\HRDPage\DataKepegawaian\DataPegawaiController::class, 'edit'])->name('hrd.data_kepegawaian.list_data_pegawai.edit')->middleware('modul:Pengelolaan Data Kepegawaian');
Route::post('/hrd/data-kepegawaian/edit-data-pegawai/update-path-photo', [\App\Http\Controllers\HRDPage\DataKepegawaian\DataPegawaiController::class, 'update_path_photo'])->name('hrd.data_kepegawaian.list_data_pegawai.update_path_photo')->middleware('modul:Pengelolaan Data Kepegawaian');
Route::post('/hrd/data-kepegawaian/edit-data-pegawai/update', [\App\Http\Controllers\HRDPage\DataKepegawaian\DataPegawaiController::class, 'update'])->name('hrd.data_kepegawaian.list_data_pegawai.update')->middleware('modul:Pengelolaan Data Kepegawaian');
// Data Anak
Route::get('/hrd/data-kepegawaian/detail-data-anak/{id}/{is_insert?}', [\App\Http\Controllers\HRDPage\DataKepegawaian\DataAnakKaryawanController::class, 'index'])->name('hrd.data_kepegawaian.data_anak_karyawan.index')->middleware('modul:Pengelolaan Data Kepegawaian');
Route::post('/hrd/data-kepegawaian/detail-data-anak/json', [\App\Http\Controllers\HRDPage\DataKepegawaian\DataAnakKaryawanController::class, 'json_get_daftar_anak_karyawan'])->name('hrd.data_kepegawaian.data_anak_karyawan.json_get_daftar_anak_karyawan')->middleware('modul:Pengelolaan Data Kepegawaian');
Route::post('/hrd/data-kepegawaian/detail-data-anak/insup', [\App\Http\Controllers\HRDPage\DataKepegawaian\DataAnakKaryawanController::class, 'insup'])->name('hrd.data_kepegawaian.data_anak_karyawan.insup')->middleware('modul:Pengelolaan Data Kepegawaian');
Route::get('/hrd/data-kepegawaian/detail/detail-data-anak/{id}', [\App\Http\Controllers\HRDPage\DataKepegawaian\DataAnakKaryawanController::class, 'json_detail_anak'])->name('hrd.data_kepegawaian.data_anak_karyawan.json_detail_anak')->middleware('modul:Pengelolaan Data Kepegawaian');
Route::post('/hrd/data-kepegawaian/detail-data-anak/delete', [\App\Http\Controllers\HRDPage\DataKepegawaian\DataAnakKaryawanController::class, 'delete'])->name('hrd.data_kepegawaian.data_anak_karyawan.delete')->middleware('modul:Pengelolaan Data Kepegawaian');
/* Akademik */
// Rekapitulasi Absen Mengajar
Route::get('/hrd/akademik/rekapitulasi-absen-mengajar', [\App\Http\Controllers\HRDPage\Akadmik\RekapitulasiAbsenMengajarController::class, 'index'])->name('hrd.akademik.rekapitulasi_absen_mengajar.index')->middleware('modul:Pengelolaan Rekap Absensi Mengajar');
Route::post('/hrd/akademik/rekapitulasi-absen-mengajar/json', [\App\Http\Controllers\HRDPage\Akadmik\RekapitulasiAbsenMengajarController::class, 'json_rekapitulasi_absen_mengajar'])->name('hrd.akademik.rekapitulasi_absen_mengajar.json_rekapitulasi_absen_mengajar')->middleware('modul:Pengelolaan Rekap Absensi Mengajar');
Route::get('/hrd/akademik/rekapitulasi-absen-mengajar/export/{tgl_awal}/{tgl_akhir}/{id_personal?}', [\App\Http\Controllers\HRDPage\Akadmik\RekapitulasiAbsenMengajarController::class, 'export'])->name('hrd.akademik.rekapitulasi_absen_mengajar.export_details')->middleware('modul:Pengelolaan Rekap Absensi Mengajar');
Route::get('/hrd/akademik/rekapitulasi-absen-mengajar/detail/{id_personal}', [\App\Http\Controllers\HRDPage\Akadmik\RekapitulasiAbsenMengajarController::class, 'detail'])->name('hrd.akademik.rekapitulasi_absen_mengajar.detail.index')->middleware('modul:Pengelolaan Rekap Absensi Mengajar');
Route::post('/hrd/akademik/rekapitulasi-absen-mengajar/detail/json', [\App\Http\Controllers\HRDPage\Akadmik\RekapitulasiAbsenMengajarController::class, 'json_rekapitulasi_absen_mengajar_detail'])->name('hrd.akademik.rekapitulasi_absen_mengajar.detail.index')->middleware('modul:Pengelolaan Rekap Absensi Mengajar');
Route::get('/hrd/akademik/rekapitulasi-absen-mengajar/detail/export/{tgl_awal}/{tgl_akhir}/{id_personal}', [\App\Http\Controllers\HRDPage\Akadmik\RekapitulasiAbsenMengajarController::class, 'export_details'])->name('hrd.akademik.rekapitulasi_absen_mengajar.detail.index')->middleware('modul:Pengelolaan Rekap Absensi Mengajar');
/* Sarpras */
// Daftar Sarpras
Route::get('/hrd/sarpras', [\App\Http\Controllers\HRDPage\Sarpras\SarprasCont::class, 'index'])->name('hrd.sarpras.index')->middleware('modul:Daftar Sarana Prasarana');
Route::post('/hrd/sarpras-json', [\App\Http\Controllers\HRDPage\Sarpras\SarprasCont::class, 'json'])->name('hrd.sarpras.json')->middleware('modul:Daftar Sarana Prasarana');
/*
 * ------------------------------------------------------------------------
 * SEKRETARIS PAGE
 * ------------------------------------------------------------------------
*/
/* Surat Menyurat */
// Surat Keluar Masuk
Route::get('/sek/surat-menyurat/surat-keluar-masuk/list/{jenis_surat?}', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeluarMasukController::class, 'index'])->name('sekretaris.surat_menyurat.surat_keluar_masuk.index')->middleware('modul:Surat Keluar Masuk');
// Surat Masuk
Route::post('/sek/surat-menyurat/surat-keluar-masuk/json/surat-masuk', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeluarMasukController::class, 'json_get_daftar_surat_masuk'])->name('sekretaris.surat_menyurat.surat_keluar_masuk.json_daftar_surat_masuk')->middleware('modul:Surat Keluar Masuk');
Route::post('/sek/surat-menyurat/surat-keluar-masuk/create/surat-masuk', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeluarMasukController::class, 'insert_surat_masuk'])->name('sekretaris.surat_menyurat.surat_keluar_masuk.insert_surat_masuk')->middleware('modul:Surat Keluar Masuk');
Route::post('/sek/surat-menyurat/surat-keluar-masuk/delete/surat-masuk', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeluarMasukController::class, 'delete_surat_masuk'])->name('sekretaris.surat_menyurat.surat_keluar_masuk.delete_surat_masuk')->middleware('modul:Surat Keluar Masuk');
// Surat Keluar
Route::post('/sek/surat-menyurat/surat-keluar-masuk/json/surat-keluar', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeluarMasukController::class, 'json_get_daftar_surat_keluar'])->name('sekretaris.surat_menyurat.surat_keluar_masuk.json_daftar_surat_keluar')->middleware('modul:Surat Keluar Masuk');
Route::post('/sek/surat-menyurat/surat-keluar-masuk/create/surat-keluar', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeluarMasukController::class, 'insert_surat_keluar'])->name('sekretaris.surat_menyurat.surat_keluar_masuk.insert_surat_keluar')->middleware('modul:Surat Keluar Masuk');
Route::post('/sek/surat-menyurat/surat-keluar-masuk/delete/surat-keluar', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeluarMasukController::class, 'delete_surat_keluar'])->name('sekretaris.surat_menyurat.surat_keluar_masuk.delete_surat_keluar')->middleware('modul:Surat Keluar Masuk');
// Detail Surat Keluar
Route::get('/sek/surat-menyurat/surat-keluar-masuk/detail-surat-keluar/{id_surat}', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeluarMasukController::class, 'detail_partisipan_surat_keluar'])->name('sekretaris.surat_menyurat.surat_keluar_masuk.detail_partisipan_surat_keluar')->middleware('modul:Surat Keluar Masuk');
Route::post('/sek/surat-menyurat/surat-keluar-masuk/json/detail/surat-keluar', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeluarMasukController::class, 'json_get_daftar_partisipan_surat_keluar'])->name('sekretaris.surat_menyurat.surat_keluar_masuk.json_get_daftar_partisipan_surat_keluar')->middleware('modul:Surat Keluar Masuk');
Route::post('/sek/surat-menyurat/surat-keluar-masuk/json/add/surat-keluar', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeluarMasukController::class, 'add_partisipan_surat_keluar'])->name('sekretaris.surat_menyurat.surat_keluar_masuk.add_partisipan_surat_keluar')->middleware('modul:Surat Keluar Masuk');
Route::post('/sek/surat-menyurat/surat-keluar-masuk/json/delete/surat-keluar', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeluarMasukController::class, 'delete_partisipan_surat_keluar'])->name('sekretaris.surat_menyurat.surat_keluar_masuk.delete_partisipan_surat_keluar')->middleware('modul:Surat Keluar Masuk');
// Detail Surat Masuk
Route::get('/sek/surat-menyurat/surat-keluar-masuk/detail-surat-masuk/{id_surat}', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeluarMasukController::class, 'detail_partisipan_surat_masuk'])->name('sekretaris.surat_menyurat.surat_keluar_masuk.detail_partisipan_surat_masuk')->middleware('modul:Surat Keluar Masuk');
Route::post('/sek/surat-menyurat/surat-keluar-masuk/json/detail/surat-masuk', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeluarMasukController::class, 'json_get_daftar_partisipan_surat_masuk'])->name('sekretaris.surat_menyurat.surat_keluar_masuk.json_get_daftar_partisipan_surat_masuk')->middleware('modul:Surat Keluar Masuk');
Route::post('/sek/surat-menyurat/surat-keluar-masuk/json/add/surat-masuk', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeluarMasukController::class, 'add_partisipan_surat_masuk'])->name('sekretaris.surat_menyurat.surat_keluar_masuk.add_partisipan_surat_masuk')->middleware('modul:Surat Keluar Masuk');
Route::post('/sek/surat-menyurat/surat-keluar-masuk/json/delete/surat-masuk', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeluarMasukController::class, 'delete_partisipan_surat_masuk'])->name('sekretaris.surat_menyurat.surat_keluar_masuk.delete_partisipan_surat_masuk')->middleware('modul:Surat Keluar Masuk');
// Surat Keputusan
Route::get('/sek/surat-menyurat/surat-keputusan', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeputusanController::class, 'index'])->name('sekretaris.surat_menyurat.surat_keputusan.index')->middleware('modul:Pengelolaan Surat Keputusan');
Route::post('/sek/surat-menyurat/surat-keputusan/json', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeputusanController::class, 'json_daftar_sk'])->name('sekretaris.surat_menyurat.surat_keputusan.json_daftar_sk')->middleware('modul:Pengelolaan Surat Keputusan');
Route::post('/sek/surat-menyurat/surat-keputusan/create', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeputusanController::class, 'insert_sk'])->name('sekretaris.surat_menyurat.surat_keputusan.insert_sk')->middleware('modul:Pengelolaan Surat Keputusan');
Route::post('/sek/surat-menyurat/surat-keputusan/delete', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeputusanController::class, 'delete_sk'])->name('sekretaris.surat_menyurat.surat_keputusan.delete_sk')->middleware('modul:Pengelolaan Surat Keputusan');
// Detail Surat Keputusan
Route::get('/sek/surat-menyurat/surat-keputusan/detail-surat-keputusan/{id_sk}', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeputusanController::class, 'detail_partisipan_sk'])->name('sekretaris.surat_menyurat.surat_keputusan.detail_partisipan_sk')->middleware('modul:Pengelolaan Surat Keputusan');
Route::post('/sek/surat-menyurat/surat-keputusan/json/detail-partisipan', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeputusanController::class, 'json_get_daftar_partisipan_sk'])->name('sekretaris.surat_menyurat.surat_keputusan.json_get_daftar_partisipan_sk')->middleware('modul:Pengelolaan Surat Keputusan');
Route::post('/sek/surat-menyurat/surat-keputusan/json/add-partisipan', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeputusanController::class, 'add_partisipan_sk'])->name('sekretaris.surat_menyurat.surat_keputusan.add_partisipan_sk')->middleware('modul:Pengelolaan Surat Keputusan');
Route::post('/sek/surat-menyurat/surat-keputusan/json/delete-partisipan', [\App\Http\Controllers\SekretarisPage\SuratMenyurat\SuratKeputusanController::class, 'delete_partisipan_sk'])->name('sekretaris.surat_menyurat.surat_keputusan.delete_partisipan_sk')->middleware('modul:Pengelolaan Surat Keputusan');

/*
 * ------------------------------------------------------------------------
 * KARYAWAN PAGE
 * ------------------------------------------------------------------------
*/
/* Data Kepegawaian */
Route::get('/kary/data-kepegawaian/data-diri', [\App\Http\Controllers\KaryawanPage\DataKepegawaian\DataDiriController::class, 'index'])->name('karyawan.data_kepegawaian.data_diri.index')->middleware('modul:Pengelolaan Data Pribadi Pegawai');
Route::get('/kary/data-kepegawaian/data-diri/ubah', [\App\Http\Controllers\KaryawanPage\DataKepegawaian\DataDiriController::class, 'ubah_data_diri'])->name('karyawan.data_kepegawaian.data_diri.ubah_data_diri')->middleware('modul:Pengelolaan Data Pribadi Pegawai');
Route::post('/kary/data-kepegawaian/data-diri/update-path-photo', [\App\Http\Controllers\KaryawanPage\DataKepegawaian\DataDiriController::class, 'update_path_photo'])->name('karyawan.data_kepegawaian.data_diri.update_path_photo')->middleware('modul:Pengelolaan Data Pribadi Pegawai');
Route::post('/kary/data-kepegawaian/data-diri/update-data-personal', [\App\Http\Controllers\KaryawanPage\DataKepegawaian\DataDiriController::class, 'update_data_personal'])->name('karyawan.data_kepegawaian.data_diri.update_data_personal')->middleware('modul:Pengelolaan Data Pribadi Pegawai');
/* Surat Menyurat */
// Arsip Surat
Route::get('/kary/surat-menyurat/surat', [\App\Http\Controllers\KaryawanPage\SuratMenyurat\SuratController::class, 'index'])->name('karyawan.surat_menyurat.surat.index')->middleware('modul:Melihat Surat');
Route::post('/kary/surat-menyurat/surat/json', [\App\Http\Controllers\KaryawanPage\SuratMenyurat\SuratController::class, 'json_get_daftar_surat'])->name('karyawan.surat_menyurat.surat.json_get_daftar_surat')->middleware('modul:Melihat Surat');
// Arsip SK
Route::get('/kary/surat-menyurat/surat-keputusan', [\App\Http\Controllers\KaryawanPage\SuratMenyurat\SKController::class, 'index'])->name('karyawan.surat_menyurat.surat_keputusan.index')->middleware('modul:Melihat Surat Keputusan (SK)');
Route::post('/kary/surat-menyurat/surat-keputusan/json', [\App\Http\Controllers\KaryawanPage\SuratMenyurat\SKController::class, 'json_get_daftar_sk'])->name('karyawan.surat_menyurat.surat_keputusan.json_get_daftar_sk')->middleware('modul:Melihat Surat Keputusan (SK)');
/* Penggajian */
Route::get('/kary/penggajian/gaji-bulanan', [\App\Http\Controllers\KaryawanPage\Penggajian\GajiBulananController::class, 'index'])->name('karyawan.penggajian.gaji_bulanan.index')->middleware('modul:Melihat Gaji Bulanan');
Route::post('/kary/penggajian/gaji-bulanan/json', [\App\Http\Controllers\KaryawanPage\Penggajian\GajiBulananController::class, 'json_get_rekapitulasi_gaji_bulanan'])->name('karyawan.penggajian.gaji_bulanan.json_get_rekapitulasi_gaji_bulanan')->middleware('modul:Melihat Gaji Bulanan');
Route::get('/kary/penggajian/gaji-bulanan/slip-gaji/{id_rekap}', [\App\Http\Controllers\KaryawanPage\Penggajian\GajiBulananController::class, 'slip_gaji'])->name('karyawan.penggajian.gaji_bulanan.slip_gaji')->middleware('modul:Melihat Gaji Bulanan');
Route::get('/kary/penggajian/gaji-bulanan/detail/{id_rekap}', [\App\Http\Controllers\KaryawanPage\Penggajian\GajiBulananController::class, 'detail'])->name('karyawan.penggajian.gaji_bulanan.detail')->middleware('modul:Melihat Gaji Bulanan');
Route::post('/kary/penggajian/gaji-bulanan/ajukan-perbaikan', [\App\Http\Controllers\KaryawanPage\Penggajian\GajiBulananController::class, 'ajukan_perbaikan'])->name('karyawan.penggajian.gaji_bulanan.ajukan_perbaikan')->middleware('modul:Melihat Gaji Bulanan');

/*
 * ------------------------------------------------------------------------
 * TRACER STUDY
 * ------------------------------------------------------------------------
*/
/*
 * ------------------------------------------------------------------------
 * LPPM PAGE
 * ------------------------------------------------------------------------
*/
Route::get('/lppm/sinta/list-author', [\App\Http\Controllers\LPPM\SintaCont::class, 'list_authors'])->name('lppm.sinta.list_authors')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/list-author-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'list_authors_json'])->name('lppm.sinta.list_authors_json')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/get-author-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'get_author_api'])->name('lppm.sinta.get_author_api')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/store-author-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'sync_authors'])->name('lppm.sinta.sync_authors')->middleware('modul:Melihat Data Sinta');
// Garuda Docs
Route::get('/lppm/sinta/list-garuda-docs/{id_sinta}', [\App\Http\Controllers\LPPM\SintaCont::class, 'list_garuda_docs'])->name('lppm.sinta.list_garuda_docs')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/list-garuda-docs-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'list_garuda_docs_json'])->name('lppm.sinta.list_garuda_docs_json')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/get-garuda-docs-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'get_doc_garuda_api'])->name('lppm.sinta.get_doc_garuda_api')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/store-garuda-docs-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'sync_doc_garuda'])->name('lppm.sinta.sync_doc_garuda')->middleware('modul:Melihat Data Sinta');
// Google Docs
Route::get('/lppm/sinta/list-google-docs/{id_sinta}', [\App\Http\Controllers\LPPM\SintaCont::class, 'list_google_docs'])->name('lppm.sinta.list_google_docs')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/list-google-docs-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'list_google_docs_json'])->name('lppm.sinta.list_google_docs_json')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/get-google-docs-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'get_doc_google_api'])->name('lppm.sinta.get_doc_google_api')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/store-google-docs-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'sync_doc_google'])->name('lppm.sinta.sync_doc_google')->middleware('modul:Melihat Data Sinta');
// Scopus Docs
Route::get('/lppm/sinta/list-scopus-docs/{id_sinta}', [\App\Http\Controllers\LPPM\SintaCont::class, 'list_scopus_docs'])->name('lppm.sinta.list_scopus_docs')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/list-scopus-docs-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'list_scopus_docs_json'])->name('lppm.sinta.list_scopus_docs_json')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/get-scopus-docs-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'get_doc_scopus_api'])->name('lppm.sinta.get_doc_scopus_api')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/store-scopus-docs-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'sync_doc_scopus'])->name('lppm.sinta.sync_doc_scopus')->middleware('modul:Melihat Data Sinta');
// Wos Docs
Route::get('/lppm/sinta/list-wos-docs/{id_sinta}', [\App\Http\Controllers\LPPM\SintaCont::class, 'list_wos_docs'])->name('lppm.sinta.list_wos_docs')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/list-wos-docs-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'list_wos_docs_json'])->name('lppm.sinta.list_wos_docs_json')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/get-wos-docs-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'get_doc_wos_api'])->name('lppm.sinta.get_doc_wos_api')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/store-wos-docs-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'sync_doc_wos'])->name('lppm.sinta.sync_doc_wos')->middleware('modul:Melihat Data Sinta');
// Books
Route::get('/lppm/sinta/list-books/{id_sinta}', [\App\Http\Controllers\LPPM\SintaCont::class, 'list_book'])->name('lppm.sinta.list_book')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/list-books-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'list_book_json'])->name('lppm.sinta.list_book_json')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/get-books-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'get_book_api'])->name('lppm.sinta.get_book_api')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/store-books-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'sync_book'])->name('lppm.sinta.sync_book')->middleware('modul:Melihat Data Sinta');
// IPRs
Route::get('/lppm/sinta/list-iprs/{id_sinta}', [\App\Http\Controllers\LPPM\SintaCont::class, 'list_iprs'])->name('lppm.sinta.list_iprs')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/list-iprs-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'list_iprs_json'])->name('lppm.sinta.list_iprs_json')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/get-iprs-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'get_iprs_api'])->name('lppm.sinta.get_iprs_api')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/store-iprs-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'sync_iprs'])->name('lppm.sinta.sync_iprs')->middleware('modul:Melihat Data Sinta');
// Research
Route::get('/lppm/sinta/list-research/{id_sinta}', [\App\Http\Controllers\LPPM\SintaCont::class, 'list_research'])->name('lppm.sinta.list_research')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/list-research-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'list_research_json'])->name('lppm.sinta.list_research_json')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/get-research-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'get_research_api'])->name('lppm.sinta.get_research_api')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/store-research-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'sync_research'])->name('lppm.sinta.sync_research')->middleware('modul:Melihat Data Sinta');
// Service
Route::get('/lppm/sinta/list-service/{id_sinta}', [\App\Http\Controllers\LPPM\SintaCont::class, 'list_service'])->name('lppm.sinta.list_service')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/list-service-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'list_service_json'])->name('lppm.sinta.list_service_json')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/get-service-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'get_service_api'])->name('lppm.sinta.get_service_api')->middleware('modul:Melihat Data Sinta');
Route::post('/lppm/sinta/store-service-json', [\App\Http\Controllers\LPPM\SintaCont::class, 'sync_service'])->name('lppm.sinta.sync_service')->middleware('modul:Melihat Data Sinta');

/*
 * ------------------------------------------------------------------------
 * BPM PAGE
 * ------------------------------------------------------------------------
*/
/* Kuesioner */
// Kuesioner Kepuasan Wisudawan
Route::get('/bpm/kuesioner/kuesioner-kepuasan-wisudawan', [\App\Http\Controllers\BPMPage\Kuesioner\KuesionerKepuasanWisudawan\HasilKuesionerController::class, 'index'])->name('bpm_page.kuesioner.kuesioner_kepuasan_wisudawan.hasil_kuesioner.index')->middleware('modul:Melihat Hasil Kuesioner Kepuasan Wisudawan');
Route::post('/bpm/kuesioner/kuesioner-kepuasan-wisudawan/json', [\App\Http\Controllers\BPMPage\Kuesioner\KuesionerKepuasanWisudawan\HasilKuesionerController::class, 'get_response_pertanyaan'])->name('bpm_page.kuesioner.kuesioner_kepuasan_wisudawan.hasil_kuesioner.get_response_pertanyaan')->middleware('modul:Melihat Hasil Kuesioner Kepuasan Wisudawan');
Route::get('/bpm/kuesioner/kuesioner-kepuasan-wisudawan/export/{id?}', [\App\Http\Controllers\BPMPage\Kuesioner\KuesionerKepuasanWisudawan\HasilKuesionerController::class, 'export_response'])->name('bpm_page.kuesioner.kuesioner_kepuasan_wisudawan.hasil_kuesioner.export_response')->middleware('modul:Melihat Hasil Kuesioner Kepuasan Wisudawan');
// Kuesioner Kepuasan Mahasiswa
Route::get('/bpm/kuesioner/kuesioner-kepuasan-mahasiswa', [\App\Http\Controllers\BPMPage\Kuesioner\KuesionerKepuasanMahasiswa\HasilKuesionerController::class, 'index'])->name('bpm_page.kuesioner.kuesioner_kepuasan_mahasiswa.hasil_kuesioner.index')->middleware('modul:Melihat Hasil Kuesioner Kepuasan Mahasiswa');
Route::post('/bpm/kuesioner/kuesioner-kepuasan-mahasiswa/json', [\App\Http\Controllers\BPMPage\Kuesioner\KuesionerKepuasanMahasiswa\HasilKuesionerController::class, 'get_response_pertanyaan'])->name('bpm_page.kuesioner.kuesioner_kepuasan_mahasiswa.hasil_kuesioner.get_response_pertanyaan')->middleware('modul:Melihat Hasil Kuesioner Kepuasan Mahasiswa');
Route::get('/bpm/kuesioner/kuesioner-kepuasan-mahasiswa/get-unsur/{id}', [\App\Http\Controllers\BPMPage\Kuesioner\KuesionerKepuasanMahasiswa\HasilKuesionerController::class, 'get_unsur_by_id_jenis'])->name('bpm_page.kuesioner.kuesioner_kepuasan_mahasiswa.hasil_kuesioner.get_unsur_by_id_jenis')->middleware('modul:Melihat Hasil Kuesioner Kepuasan Mahasiswa');
Route::get('/bpm/kuesioner/kuesioner-kepuasan-mahasiswa/export/{id_jenis}/{id_semester?}', [\App\Http\Controllers\BPMPage\Kuesioner\KuesionerKepuasanMahasiswa\HasilKuesionerController::class, 'export_response'])->name('bpm_page.kuesioner.kuesioner_kepuasan_mahasiswa.hasil_kuesioner.export_response')->middleware('modul:Melihat Hasil Kuesioner Kepuasan Mahasiswa');
Route::get('test', function () {
    \Illuminate\Support\Facades\Storage::disk('google')->put('test.txt', 'Hello World');
});
// Profil Mandala
Route::get('/bpm/profil-mandala', [\App\Http\Controllers\BPMPage\ProfilMandala\ProfilMandalaController::class, 'index'])->name('bpm_page.profil_mandala.profil_mandala.index')->middleware('modul:Profil Mandala');
Route::post('/bpm/update-visi', [\App\Http\Controllers\BPMPage\ProfilMandala\ProfilMandalaController::class, 'update_visi'])->name('bpm_page.profil_mandala.update_visis')->middleware('modul:Profil Mandala');
Route::post('/bpm/insup-misi', [\App\Http\Controllers\BPMPage\ProfilMandala\ProfilMandalaController::class, 'insup_misi'])->name('bpm_page.profil_mandala.insup_misis')->middleware('modul:Profil Mandala');
Route::post('/bpm/delete-misi', [\App\Http\Controllers\BPMPage\ProfilMandala\ProfilMandalaController::class, 'delete_misi'])->name('bpm_page.profil_mandala.delete_misis')->middleware('modul:Profil Mandala');
Route::post('/bpm/update-so', [\App\Http\Controllers\BPMPage\ProfilMandala\ProfilMandalaController::class, 'update_struktur'])->name('bpm_page.profil_mandala.update_struktur')->middleware('modul:Profil Mandala');
// Tata Pamong
Route::get('/bpm/tata-pamong', [\App\Http\Controllers\BPMPage\TataPamong\TataPamongController::class, 'index'])->name('bpm_page.tata_pamong.index')->middleware('modul:Tata Pamong');
Route::post('/bpm/insup-tata-pamong', [\App\Http\Controllers\BPMPage\TataPamong\TataPamongController::class, 'insup'])->name('bpm_page.tata_pamong.insup')->middleware('modul:Tata Pamong');
Route::post('/bpm/insup-tata-pamong-json', [\App\Http\Controllers\BPMPage\TataPamong\TataPamongController::class, 'json_get_daftar_tatapamong'])->name('bpm_page.tata_pamong.json_get_daftar_tatapamong')->middleware('modul:Tata Pamong');

/*
 * ------------------------------------------------------------------------
 * KUI & Kerjasama PAGE
 * ------------------------------------------------------------------------
*/
// Kerjasama
Route::get('/kui/kerjasama', [\App\Http\Controllers\KuiKerjasama\KerjasamaController::class, 'index'])->name('kui_kerjasama.kerjasama.index')->middleware('modul:Kerjasama');
Route::post('/kui/insup-kerjasama', [\App\Http\Controllers\KuiKerjasama\KerjasamaController::class, 'insup'])->name('kui_kerjasama.kerjasama.insup')->middleware('modul:Kerjasama');
Route::post('/kui/insup-kerjsama-json', [\App\Http\Controllers\KuiKerjasama\KerjasamaController::class, 'json_get_daftar_kerjasama'])->name('kui_kerjasama.kerjasama.json_get_daftar_kerjasama')->middleware('modul:Kerjasama');
Route::post('/kui/delete-kerjasama', [\App\Http\Controllers\KuiKerjasama\KerjasamaController::class, 'delete'])->name('kui_kerjasama.kerjasama.delete')->middleware('modul:Kerjasama');

/*
 * ------------------------------------------------------------------------
 * REKTOR PAGE
 * ------------------------------------------------------------------------
*/
/* Dashboard */
Route::post('/rek/dashboard/get-daftar-ulang-tahun', [\App\Http\Controllers\RektorPage\DashboardController::class, 'get_daftar_ulang_tahun'])->name('rektor.dashboard.get_daftar_ulang_tahun')->middleware('modul:Dashboard');
Route::get('/rek/dashboard/detail-mahasiswa/{status?}', [\App\Http\Controllers\RektorPage\DashboardController::class, 'detail_mahasiswa'])->name('rektor.dashboard.detail_mahasiswa')->middleware('modul:Dashboard - Detail Mahasiswa');
Route::post('/rek/dashboard/detail-mahasiswa/json', [\App\Http\Controllers\RektorPage\DashboardController::class, 'json_get_daftar_mahasiswa'])->name('rektor.dashboard.json_get_daftar_mahasiswa')->middleware('modul:Dashboard - Detail Mahasiswa');
Route::get('/rek/dashboard/detail-mahasiswa-baru/{filter?}', [\App\Http\Controllers\RektorPage\DashboardController::class, 'detail_maba'])->name('rektor.dashboard.detail_maba')->middleware('modul:Dashboard - Detail Mahasiswa Baru');
Route::post('/rek/dashboard/detail-mahasiswa-baru/json', [\App\Http\Controllers\RektorPage\DashboardController::class, 'json_get_daftar_maba'])->name('rektor.dashboard.json_get_daftar_maba')->middleware('modul:Dashboard - Detail Mahasiswa Baru');
Route::get('/rek/dashboard/detail-karyawan/{filter?}', [\App\Http\Controllers\RektorPage\DashboardController::class, 'detail_karyawan'])->name('rektor.dashboard.detail_karyawan')->middleware('modul:Dashboard - Detail Karyawan');
Route::post('/rek/dashboard/detail-karyawan/json', [\App\Http\Controllers\RektorPage\DashboardController::class, 'json_get_daftar_karyawan'])->name('rektor.dashboard.json_get_daftar_karyawan')->middleware('modul:Dashboard - Detail Karyawan');

/*
 * ------------------------------------------------------------------------
 * SUPER ADMIN PAGE
 * ------------------------------------------------------------------------
*/
/* Konfigurasi Sistem */
// Aplikasi
Route::get('/super-admin/aplikasi/daftar-aplikasi', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\AplikasiController::class, 'index'])->name('aplikasi.index')->middleware('modul:Pengelolaan Aplikasi Mandala');
Route::get('/super-admin/aplikasi/json/daftar-aplikasi', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\AplikasiController::class, 'json_get_daftar_aplikasi'])->name('aplikasi.json_get_daftar_aplikasi')->middleware('modul:Pengelolaan Aplikasi Mandala');
Route::post('/super-admin/aplikasi/store', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\AplikasiController::class, 'store'])->name('aplikasi.store')->middleware('modul:Pengelolaan Aplikasi Mandala');
Route::post('/super-admin/aplikasi/update', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\AplikasiController::class, 'update'])->name('aplikasi.update')->middleware('modul:Pengelolaan Aplikasi Mandala');
Route::post('/super-admin/aplikasi/delete', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\AplikasiController::class, 'delete'])->name('aplikasi.delete')->middleware('modul:Pengelolaan Aplikasi Mandala');
// Modul
Route::get('/super-admin/modul/daftar-modul', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\ModulController::class, 'index'])->name('modul.index')->middleware('modul:Pengelolaan Modul Aplikasi');
Route::post('/super-admin/modul/json/daftar-modul', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\ModulController::class, 'json_get_daftar_modul'])->name('aplikasi.json_get_daftar_modul')->middleware('modul:Pengelolaan Modul Aplikasi');
Route::post('/super-admin/modul/store', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\ModulController::class, 'store'])->name('modul.store')->middleware('modul:Pengelolaan Modul Aplikasi');
Route::post('/super-admin/modul/update', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\ModulController::class, 'update'])->name('modul.update')->middleware('modul:Pengelolaan Modul Aplikasi');
Route::post('/super-admin/modul/delete', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\ModulController::class, 'delete'])->name('modul.delete')->middleware('modul:Pengelolaan Modul Aplikasi');
// Peran
Route::get('/super-admin/peran/daftar-peran', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\PeranController::class, 'index'])->name('peran.index')->middleware('modul:Pengelolaan Peran Aplikasi');
Route::post('/super-admin/peran/json/daftar-peran', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\PeranController::class, 'json_get_daftar_peran'])->name('aplikasi.json_get_daftar_peran')->middleware('modul:Pengelolaan Peran Aplikasi');
Route::post('/super-admin/peran/store', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\PeranController::class, 'store'])->name('peran.store')->middleware('modul:Pengelolaan Peran Aplikasi');
Route::post('/super-admin/peran/update', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\PeranController::class, 'update'])->name('peran.update')->middleware('modul:Pengelolaan Peran Aplikasi');
Route::post('/super-admin/peran/delete', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\PeranController::class, 'delete'])->name('peran.delete')->middleware('modul:Pengelolaan Peran Aplikasi');
// Kewenangan Peran
Route::get('/super-admin/kewenangan-peran/daftar-kewenangan', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\KewenanganPeranController::class, 'index'])->name('kewenangan_peran.index')->middleware('modul:Pengelolaan Kewenangan Peran');
Route::post('/super-admin/kewenangan-peran/daftar-kewenangan/json', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\KewenanganPeranController::class, 'json_get_daftar_kewenangan_peran'])->name('kewenangan_peran.json_get_daftar_kewenangan_peran')->middleware('modul:Pengelolaan Kewenangan Peran');
Route::get('/super-admin/kewenangan-peran/detail/{id}', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\KewenanganPeranController::class, 'detail'])->name('kewenangan_peran.detail')->middleware('modul:Pengelolaan Kewenangan Peran');
Route::post('/super-admin/kewenangan-peran/detail/json', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\KewenanganPeranController::class, 'json_get_daftar_kewenangan_peran_by_peran'])->name('kewenangan_peran.json_get_daftar_kewenangan_peran_by_peran')->middleware('modul:Pengelolaan Kewenangan Peran');
Route::post('/super-admin/kewenangan-peran/store', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\KewenanganPeranController::class, 'store'])->name('kewenangan_peran.store')->middleware('modul:Pengelolaan Kewenangan Peran');
Route::post('/super-admin/kewenangan-peran/delete', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\KewenanganPeranController::class, 'delete'])->name('kewenangan_peran.delete')->middleware('modul:Pengelolaan Kewenangan Peran');
// Peran Pengguna
Route::get('/super-admin/peran-pengguna/daftar-peran', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\PeranPenggunaController::class, 'index'])->name('peran_pengguna.index')->middleware('modul:Pengelolaan Peran Pengguna');
Route::post('/super-admin/peran-pengguna/daftar-peran/json', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\PeranPenggunaController::class, 'json_get_daftar_peran_pengguna'])->name('peran_pengguna.json_get_daftar_peran_pengguna')->middleware('modul:Pengelolaan Peran Pengguna');
Route::get('/super-admin/peran-pengguna/detail/{id}/{id_aplikasi}', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\PeranPenggunaController::class, 'detail'])->name('peran_pengguna.detail')->middleware('modul:Pengelolaan Peran Pengguna');
Route::post('/super-admin/peran-pengguna/detail/json', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\PeranPenggunaController::class, 'json_get_daftar_peran_pengguna_by_personal'])->name('peran_pengguna.json_get_daftar_peran_pengguna_by_personal')->middleware('modul:Pengelolaan Peran Pengguna');
Route::post('/super-admin/peran-pengguna/store', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\PeranPenggunaController::class, 'store'])->name('peran_pengguna.store')->middleware('modul:Pengelolaan Peran Pengguna');
Route::post('/super-admin/peran-pengguna/delete', [\App\Http\Controllers\SuperAdminPage\KonfigurasiSistem\PeranPenggunaController::class, 'delete'])->name('peran_pengguna.delete')->middleware('modul:Pengelolaan Peran Pengguna');

/* SIAKAD */
// Jadwal Dosen
Route::get('/super-admin/siakad/daftar-jadwal-dosen', [\App\Http\Controllers\SuperAdminPage\Siakad\JadwalPerkuliahanController::class, 'vwJadwalDosen'])->name('siakad.jadwal_perkuliahan.vwJadwalDosen')->middleware('modul:Melihat Data Perkuliahan Siakad');
Route::post('/super-admin/siakad/json/daftar-jadwal-dosen', [\App\Http\Controllers\SuperAdminPage\Siakad\JadwalPerkuliahanController::class, 'json_vwJadwalDosen'])->name('siakad.jadwal_perkuliahan.json_vwJadwalDosen')->middleware('modul:Melihat Data Perkuliahan Siakad');
// Jadwal Mahasiswa
Route::get('/super-admin/siakad/daftar-jadwal-mahasiswa', [\App\Http\Controllers\SuperAdminPage\Siakad\JadwalMahasiswaController::class, 'vwJadwalMahasiswa'])->name('siakad.jadwal_mahasiswa.vwJadwalMahasiswa')->middleware('modul:Melihat Data Perkuliahan Mahasiswa Siakad');
Route::post('/super-admin/siakad/json/daftar-jadwal-mahasiswa', [\App\Http\Controllers\SuperAdminPage\Siakad\JadwalMahasiswaController::class, 'json_vwJadwalMahasiswa'])->name('siakad.jadwal_mahasiswa.json_vwJadwalMahasiswa')->middleware('modul:Melihat Data Perkuliahan Mahasiswa Siakad');
// Mahasiswa
Route::get('/super-admin/siakad/daftar-mahasiswa', [\App\Http\Controllers\SuperAdminPage\Siakad\MahasiswaController::class, 'daftar_mahasiswa'])->name('siakad.mahasiswa.daftar_mahasiswa')->middleware('modul:Melihat Data Mahasiswa Siakad');
Route::post('/super-admin/siakad/json/daftar-mahasiswa', [\App\Http\Controllers\SuperAdminPage\Siakad\MahasiswaController::class, 'json_daftar_mahasiswa'])->name('siakad.mahasiswa.json_daftar_mahasiswa')->middleware('modul:Melihat Data Mahasiswa Siakad');
// Dosen
Route::get('/super-admin/siakad/daftar-dosen', [\App\Http\Controllers\SuperAdminPage\Siakad\DosenController::class, 'daftar_dosen'])->name('siakad.dosen.daftar_dosen')->middleware('modul:Melihat Data Dosen/Karyawan Siakad');
Route::post('/super-admin/siakad/json/daftar-dosen', [\App\Http\Controllers\SuperAdminPage\Siakad\DosenController::class, 'json_daftar_dosen'])->name('siakad.dosen.json_daftar_dosen')->middleware('modul:Melihat Data Dosen/Karyawan Siakad');
// Tanggungan Mahasiswa
Route::get('/super-admin/siakad/tanggungan-mahasiswa/daftar-tanggungan', [\App\Http\Controllers\SuperAdminPage\Siakad\TanggunganMahasiswaController::class, 'daftar_tanggungan'])->name('siakad.tanggungan_mahasiswa.daftar_tanggungan')->middleware('modul:Melihat Data Tanggungan Mahasiswa Siakad');
Route::post('/super-admin/siakad/tanggungan-mahasiswa/json/daftar-tanggungan', [\App\Http\Controllers\SuperAdminPage\Siakad\TanggunganMahasiswaController::class, 'json_daftar_tanggungan'])->name('siakad.tanggungan_mahasiswa.json_daftar_tanggungan_mahasiswa')->middleware('modul:Melihat Data Tanggungan Mahasiswa Siakad');
Route::get('/super-admin/siakad/tanggungan-mahasiswa/export/export-tanggungan/{batas}/{reg?}/{regm?}/{trf?}/{trfm?}', [\App\Http\Controllers\SuperAdminPage\Siakad\TanggunganMahasiswaController::class, 'export_tanggungan'])->name('siakad.tanggungan_mahasiswa.export_tanggungan')->middleware('modul:Melihat Data Tanggungan Mahasiswa Siakad');
Route::post('/super-admin/siakad/tanggungan-mahasiswa/json/get-transaksi-mahasiswa', [\App\Http\Controllers\SuperAdminPage\Siakad\TanggunganMahasiswaController::class, 'json_get_transaki_mahasiswa'])->name('siakad.tanggungan_mahasiswa.json_get_transaki_mahasiswa')->middleware('modul:Melihat Data Tanggungan Mahasiswa Siakad');

/* MOODLE */
// Jadwal Siakad
Route::get('/super-admin/moodle/daftar-jadwal-siakad', [\App\Http\Controllers\SuperAdminPage\Moodle\JadwalSiakadController::class, 'index'])->name('moodle.jadwal_siakad.index')->middleware('modul:Moodle Synchronizer - Jadwal Siakad');
Route::post('/super-admin/moodle/json/daftar-jadwal-siakad', [\App\Http\Controllers\SuperAdminPage\Moodle\JadwalSiakadController::class, 'json_get_daftar_jadwal_siakad'])->name('moodle.jadwal_siakad.json_get_daftar_jadwal_siakad')->middleware('modul:Moodle Synchronizer - Jadwal Siakad');
Route::post('/super-admin/moodle/json/json-syncron', [\App\Http\Controllers\SuperAdminPage\Moodle\JadwalSiakadController::class, 'json_syncron'])->name('moodle.jadwal_siakad.json_syncron')->middleware('modul:Moodle Synchronizer - Jadwal Siakad');
Route::post('/super-admin/moodle/json/json-jadwal-siakad', [\App\Http\Controllers\SuperAdminPage\Moodle\JadwalSiakadController::class, 'json_get_jadwal_siakad'])->name('moodle.jadwal_siakad.json_get_jadwal_siakad')->middleware('modul:Moodle Synchronizer - Jadwal Siakad');
Route::post('/super-admin/moodle/json/json-jadwal-siakad-by-id', [\App\Http\Controllers\SuperAdminPage\Moodle\JadwalSiakadController::class, 'json_get_jadwal_siakad_by_id'])->name('moodle.jadwal_siakad.json_get_jadwal_siakad_by_id')->middleware('modul:Moodle Synchronizer - Jadwal Siakad');
Route::post('/super-admin/moodle/json/json-move-to-course', [\App\Http\Controllers\SuperAdminPage\Moodle\JadwalSiakadController::class, 'json_move_to_course'])->name('moodle.jadwal_siakad.json_move_to_course')->middleware('modul:Moodle Synchronizer - Jadwal Siakad');
// Mahasiswa
Route::get('/super-admin/moodle/mahasiswa/daftar-mahasiswa', [\App\Http\Controllers\SuperAdminPage\Moodle\MahasiswaController::class, 'index'])->name('moodle.mahasiswa.index')->middleware('modul:Moodle Synchronizer - Mahasiswa');
Route::post('/super-admin/moodle/mahasiswa/json/daftar-mahasiswa', [\App\Http\Controllers\SuperAdminPage\Moodle\MahasiswaController::class, 'json_get_daftar_mahasiswa'])->name('moodle.mahasiswa.json_get_daftar_mahasiswa')->middleware('modul:Moodle Synchronizer - Mahasiswa');
Route::post('/super-admin/moodle/mahasiswa/json/mahasiswa-aktif', [\App\Http\Controllers\SuperAdminPage\Moodle\MahasiswaController::class, 'json_get_mahasiswa_aktif'])->name('moodle.mahasiswa.json_get_mahasiswa_aktif')->middleware('modul:Moodle Synchronizer - Mahasiswa');
Route::post('/super-admin/moodle/mahasiswa/json/json-syncron', [\App\Http\Controllers\SuperAdminPage\Moodle\MahasiswaController::class, 'json_syncron'])->name('moodle.mahasiswa.json_syncron')->middleware('modul:Moodle Synchronizer - Mahasiswa');
Route::post('/super-admin/moodle/mahasiswa/json/json-mahasiswa-by-npk', [\App\Http\Controllers\SuperAdminPage\Moodle\MahasiswaController::class, 'json_get_mahasiswa_by_npk'])->name('moodle.mahasiswa.json_get_mahasiswa_by_npk')->middleware('modul:Moodle Synchronizer - Mahasiswa');
// Synchron Data Center
Route::post('/super-admin/moodle/mahasiswa/json/daftar-mahasiswa/data-center', [\App\Http\Controllers\SuperAdminPage\Moodle\MahasiswaController::class, 'json_get_daftar_mahasiswa_data_center'])->name('moodle.mahasiswa.json_get_daftar_mahasiswa_data_center')->middleware('modul:Moodle Synchronizer - Mahasiswa');
Route::post('/super-admin/moodle/mahasiswa/json/mahasiswa/data-center', [\App\Http\Controllers\SuperAdminPage\Moodle\MahasiswaController::class, 'json_get_mahasiswa_by_angkatan'])->name('moodle.mahasiswa.json_get_mahasiswa_by_angkatan')->middleware('modul:Moodle Synchronizer - Mahasiswa');
Route::post('/super-admin/moodle/mahasiswa/json/json-syncron/data-center', [\App\Http\Controllers\SuperAdminPage\Moodle\MahasiswaController::class, 'json_syncron_data_center'])->name('moodle.mahasiswa.json_syncron_data_center')->middleware('modul:Moodle Synchronizer - Mahasiswa');
Route::post('/super-admin/moodle/mahasiswa/json/json-mahasiswa-by-npk/data-center', [\App\Http\Controllers\SuperAdminPage\Moodle\MahasiswaController::class, 'json_get_mahasiswa_by_npk_data_center'])->name('moodle.mahasiswa.json_get_mahasiswa_by_npk_data_center')->middleware('modul:Moodle Synchronizer - Mahasiswa');
// Suspended Mahasiswa
Route::get('/super-admin/moodle/mahasiswa/daftar-suspended-mahasiswa', [\App\Http\Controllers\SuperAdminPage\Moodle\MahasiswaController::class, 'suspended'])->name('moodle.mahasiswa.suspended')->middleware('modul:Moodle Synchronizer - Suspended Mahasiswa');
Route::post('/super-admin/moodle/mahasiswa/json/daftar-suspended-mahasiswa', [\App\Http\Controllers\SuperAdminPage\Moodle\MahasiswaController::class, 'json_get_daftar_suspended_mahasiswa'])->name('moodle.mahasiswa.json_get_daftar_suspended_mahasiswa')->middleware('modul:Moodle Synchronizer - Suspended Mahasiswa');
Route::post('/super-admin/moodle/mahasiswa/json/get-tanggungan-mahasiswa', [\App\Http\Controllers\SuperAdminPage\Moodle\MahasiswaController::class, 'json_get_tanggungan_mahasiswa'])->name('moodle.mahasiswa.json_get_tanggungan_mahasiswa')->middleware('modul:Moodle Synchronizer - Suspended Mahasiswa');
Route::post('/super-admin/moodle/mahasiswa/json/get-transaksi-mahasiswa', [\App\Http\Controllers\SuperAdminPage\Moodle\MahasiswaController::class, 'json_get_transaki_mahasiswa'])->name('moodle.mahasiswa.json_get_transaki_mahasiswa')->middleware('modul:Moodle Synchronizer - Suspended Mahasiswa');
Route::post('/super-admin/moodle/mahasiswa/json/get-tanggungan-mahasiswa-siakad', [\App\Http\Controllers\SuperAdminPage\Moodle\MahasiswaController::class, 'json_get_tanggungan_mahasiswa_siakad'])->name('moodle.mahasiswa.json_get_tanggungan_mahasiswa_siakad')->middleware('modul:Moodle Synchronizer - Suspended Mahasiswa');
Route::post('/super-admin/moodle/mahasiswa/json/suspend-mahasiswa-by-npk', [\App\Http\Controllers\SuperAdminPage\Moodle\MahasiswaController::class, 'suspend_mahasiswa_by_npk'])->name('moodle.mahasiswa.suspend_mahasiswa_by_npk')->middleware('modul:Moodle Synchronizer - Suspended Mahasiswa');
Route::post('/super-admin/moodle/mahasiswa/json/un-suspend-mahasiswa-by-npk', [\App\Http\Controllers\SuperAdminPage\Moodle\MahasiswaController::class, 'un_suspend_mahasiswa_by_npk'])->name('moodle.mahasiswa.un_suspend_mahasiswa_by_npk')->middleware('modul:Moodle Synchronizer - Suspended Mahasiswa');

// Dosen
Route::get('/super-admin/moodle/dosen/daftar-dosen', [\App\Http\Controllers\SuperAdminPage\Moodle\DosenController::class, 'index'])->name('moodle.dosen.index')->middleware('modul:Moodle Synchronizer - Dosen');
Route::post('/super-admin/moodle/dosen/json/daftar-dosen', [\App\Http\Controllers\SuperAdminPage\Moodle\DosenController::class, 'json_get_daftar_dosen'])->name('moodle.dosen.json_get_daftar_dosen')->middleware('modul:Moodle Synchronizer - Dosen');
Route::post('/super-admin/moodle/dosen/json/dosen-aktif', [\App\Http\Controllers\SuperAdminPage\Moodle\DosenController::class, 'json_get_dosen_aktif'])->name('moodle.dosen.json_get_dosen_aktif')->middleware('modul:Moodle Synchronizer - Dosen');
Route::post('/super-admin/moodle/dosen/json/json-syncron', [\App\Http\Controllers\SuperAdminPage\Moodle\DosenController::class, 'json_syncron'])->name('moodle.dosen.json_syncron')->middleware('modul:Moodle Synchronizer - Dosen');
Route::post('/super-admin/moodle/dosen/json/json-dosen-by-nik', [\App\Http\Controllers\SuperAdminPage\Moodle\DosenController::class, 'json_get_dosen_by_nik'])->name('moodle.dosen.json_get_dosen_by_nik')->middleware('modul:Moodle Synchronizer - Dosen');
// Course/Enrolment Moodle
Route::get('/super-admin/moodle/course-enrolment/daftar-course', [\App\Http\Controllers\SuperAdminPage\Moodle\EnrolmentController::class, 'daftar_course'])->name('moodle.enrolment.daftar_course')->middleware('modul:Moodle Synchronizer - Enrolment Course');
Route::post('/super-admin/moodle/course-enrolment/json/daftar-course', [\App\Http\Controllers\SuperAdminPage\Moodle\EnrolmentController::class, 'json_get_daftar_course'])->name('moodle.enrolment.json_get_daftar_course')->middleware('modul:Moodle Synchronizer - Enrolment Course');
Route::get('/super-admin/moodle/course-enrolment/detail-course/{shortname}', [\App\Http\Controllers\SuperAdminPage\Moodle\EnrolmentController::class, 'daftar_partisipan'])->name('moodle.enrolment.daftar_partisipan')->middleware('modul:Moodle Synchronizer - Enrolment Course');
Route::post('/super-admin/moodle/course-enrolment/json/detail-enrolment', [\App\Http\Controllers\SuperAdminPage\Moodle\EnrolmentController::class, 'json_get_daftar_enrolment'])->name('moodle.enrolment.json_get_daftar_enrolment')->middleware('modul:Moodle Synchronizer - Enrolment Course');
Route::post('/super-admin/moodle/course-enrolment/json/add-enrolment', [\App\Http\Controllers\SuperAdminPage\Moodle\EnrolmentController::class, 'add_enrolment'])->name('moodle.enrolment.add_enrolment')->middleware('modul:Moodle Synchronizer - Enrolment Course');
Route::post('/super-admin/moodle/course-enrolment/json/delete-enrolment', [\App\Http\Controllers\SuperAdminPage\Moodle\EnrolmentController::class, 'delete_enrolment'])->name('moodle.enrolment.delete_enrolment')->middleware('modul:Moodle Synchronizer - Enrolment Course');
Route::post('/super-admin/moodle/course-enrolment/json/get-mahasiswa-by-angkatan', [\App\Http\Controllers\SuperAdminPage\Moodle\EnrolmentController::class, 'get_mahasiswa_by_angkatan'])->name('moodle.enrolment.get_mahasiswa_by_angkatan')->middleware('modul:Moodle Synchronizer - Enrolment Course');
// Jadwal Mahasiswa
Route::get('/super-admin/moodle/jadwal-mahasiswa/daftar-jadwal-mahasiswa', [\App\Http\Controllers\SuperAdminPage\Moodle\JadwalMahasiswaController::class, 'index'])->name('moodle.jadwal_mahasiswa.index')->middleware('modul:Moodle Synchronizer - Jadwal Mahasiswa');
Route::post('/super-admin/moodle/jadwal-mahasiswa/json/daftar-jadwal-mahasiswa', [\App\Http\Controllers\SuperAdminPage\Moodle\JadwalMahasiswaController::class, 'json_get_daftar_jadwal_mahasiswa'])->name('moodle.jadwal_mahasiswa.json_get_daftar_jadwal_mahasiswa')->middleware('modul:Moodle Synchronizer - Jadwal Mahasiswa');
Route::post('/super-admin/moodle/jadwal-mahasiswa/json/json-syncron', [\App\Http\Controllers\SuperAdminPage\Moodle\JadwalMahasiswaController::class, 'json_syncron'])->name('moodle.jadwal_mahasiswa.json_syncron')->middleware('modul:Moodle Synchronizer - Jadwal Mahasiswa');
Route::post('/super-admin/moodle/jadwal-mahasiswa/json/json-krs-mahasiswa', [\App\Http\Controllers\SuperAdminPage\Moodle\JadwalMahasiswaController::class, 'json_get_krs_mahasiswa'])->name('moodle.jadwal_mahasiswa.json_get_jadwal_mahasiswa')->middleware('modul:Moodle Synchronizer - Jadwal Mahasiswa');
Route::post('/super-admin/moodle/json/jadwal-mahasiswa/json-jadwal-mahasiswa-by-id', [\App\Http\Controllers\SuperAdminPage\Moodle\JadwalMahasiswaController::class, 'json_get_jadwal_siakad_by_id'])->name('moodle.jadwal_mahasiswa.json_get_jadwal_mahasiswa_by_id')->middleware('modul:Moodle Synchronizer - Jadwal Mahasiswa');
Route::post('/super-admin/moodle/jadwal-mahasiswa/json/json-krs-mahasiswa-by-nim', [\App\Http\Controllers\SuperAdminPage\Moodle\JadwalMahasiswaController::class, 'json_get_krs_mahasiswa_by_nim'])->name('moodle.jadwal_mahasiswa.json_get_jadwal_mahasiswa_by_nim')->middleware('modul:Moodle Synchronizer - Jadwal Mahasiswa');
Route::post('/super-admin/moodle/jadwal-mahasiswa/delete-jadwal-mahasiswa', [\App\Http\Controllers\SuperAdminPage\Moodle\JadwalMahasiswaController::class, 'delete_jadwal_mahasiswa'])->name('moodle.jadwal_mahasiswa.delete_jadwal_mahasiswa')->middleware('modul:Moodle Synchronizer - Jadwal Mahasiswa');

/*
 * ------------------------------------------------------------------------
 * KEUANGAN
 * ------------------------------------------------------------------------
*/
/* PENGGAJIAN */
// Gaji Bulanan
Route::get('/keu/penggajian/gaji-bulanan/daftar', [\App\Http\Controllers\KeuanganPage\Penggajian\GajiBulananController::class, 'index'])->name('keuangan.penggajian.gaji_bulanan.index')->middleware('modul:Pengelolaan Gaji Bulanan');
Route::get('/keu/penggajian/gaji-bulanan/detail/{id_rekap}', [\App\Http\Controllers\KeuanganPage\Penggajian\GajiBulananController::class, 'detail'])->name('keuangan.penggajian.gaji_bulanan.detail')->middleware('modul:Pengelolaan Gaji Bulanan');
Route::get('/keu/penggajian/gaji-bulanan/generate-ulang-gaji/{id_karyawan}', [\App\Http\Controllers\KeuanganPage\Penggajian\GajiBulananController::class, 'generate_ulang_gaji'])->name('keuangan.penggajian.gaji_bulanan.generate_ulang_gaji')->middleware('modul:Pengelolaan Gaji Bulanan');
Route::get('/keu/penggajian/gaji-bulanan/export/{periode}/{tahun}', [\App\Http\Controllers\KeuanganPage\Penggajian\GajiBulananController::class, 'export_excel_for_bank'])->name('keuangan.penggajian.gaji_bulanan.export_excel_for_bank')->middleware('modul:Pengelolaan Gaji Bulanan');
Route::get('/keu/penggajian/gaji-bulanan/slip-gaji/{id_rekap}', [\App\Http\Controllers\KeuanganPage\Penggajian\GajiBulananController::class, 'slip_gaji'])->name('keuangan.penggajian.gaji_bulanan.slip_gaji')->middleware('modul:Pengelolaan Gaji Bulanan');
Route::post('/keu/penggajian/gaji-bulanan/json', [\App\Http\Controllers\KeuanganPage\Penggajian\GajiBulananController::class, 'json_get_rekapitulasi_gaji_bulanan'])->name('keuangan.penggajian.gaji_bulanan.json_get_rekapitulasi_gaji_bulanan')->middleware('modul:Pengelolaan Gaji Bulanan');
Route::post('/keu/penggajian/gaji-bulanan/set-rekapitulasi', [\App\Http\Controllers\KeuanganPage\Penggajian\GajiBulananController::class, 'set_rekapitulasi_bulanan'])->name('keuangan.penggajian.gaji_bulanan.set_rekapitulasi_bulanan')->middleware('modul:Pengelolaan Gaji Bulanan');
Route::get('/keu/penggajian/gaji-bulanan/export/for-bank/excel/{periode}/{bulan}', [\App\Http\Controllers\KeuanganPage\Penggajian\GajiBulananController::class, 'export_excel_for_bank'])->name('keuangan.penggajian.gaji_bulanan.export_excel_for_bank')->middleware('modul:Pengelolaan Gaji Bulanan');
Route::get('/keu/penggajian/gaji-bulanan/export/for-rekap/excel/{periode}/{bulan}', [\App\Http\Controllers\KeuanganPage\Penggajian\GajiBulananController::class, 'export_excel_for_rekap'])->name('keuangan.penggajian.gaji_bulanan.export_excel_for_rekap')->middleware('modul:Pengelolaan Gaji Bulanan');
Route::get('/keu/penggajian/gaji-bulanan/export/for-bank/pdf/{periode}/{bulan}', [\App\Http\Controllers\KeuanganPage\Penggajian\GajiBulananController::class, 'export_pdf_for_bank'])->name('keuangan.penggajian.gaji_bulanan.export_pdf_for_bank')->middleware('modul:Pengelolaan Gaji Bulanan');
Route::get('/keu/penggajian/gaji-bulanan/export/for-rekap/pdf/{periode}/{bulan}', [\App\Http\Controllers\KeuanganPage\Penggajian\GajiBulananController::class, 'export_pdf_for_rekap'])->name('keuangan.penggajian.gaji_bulanan.export_pdf_for_rekap')->middleware('modul:Pengelolaan Gaji Bulanan');
// Pengaturan Gaji Individu
Route::get('/keu/penggajian/pengaturan-gaji/daftar-pegawai', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\DaftarPegawaiController::class, 'index'])->name('keuangan.penggajian.pengaturan_gaji.daftar_pegawai.index')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/daftar-pegawai/json', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\DaftarPegawaiController::class, 'json_get_daftar_pegawai'])->name('keuangan.penggajian.pengaturan_gaji.daftar_pegawai.json_get_daftar_pegawai')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/daftar-pegawai/update-asuransi', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\DaftarPegawaiController::class, 'update_asuransi'])->name('keuangan.penggajian.pengaturan_gaji.daftar_pegawai.update_asuransi')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/daftar-pegawai/update-dplk', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\DaftarPegawaiController::class, 'update_dplk'])->name('keuangan.penggajian.pengaturan_gaji.daftar_pegawai.update_dplk')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/daftar-pegawai/update-kinerja', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\DaftarPegawaiController::class, 'update_kinerja'])->name('keuangan.penggajian.pengaturan_gaji.daftar_pegawai.update_kinerja')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/daftar-pegawai/daftar-tunjangan-kinerja', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\DaftarPegawaiController::class, 'get_daftar_tunjangan_kinerja'])->name('keuangan.penggajian.pengaturan_gaji.daftar_pegawai.get_daftar_tunjangan_kinerja')->middleware('modul:Pengaturan Gaji');
// Pengaturan Gaji Umum
Route::get('/keu/penggajian/pengaturan-gaji/gaji-umum', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\GajiUmumController::class, 'index'])->name('keuangan.penggajian.pengaturan_gaji.gaji_umum.index')->middleware('modul:Pengaturan Gaji');
// Potongan Koperasi
Route::get('/keu/penggajian/pengaturan-gaji/potongan-koperasi', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganKoperasiController::class, 'index'])->name('keuangan.penggajian.pengaturan_gaji.potongan_koperasi.index')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-koperasi/json', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganKoperasiController::class, 'json_get_daftar_potongan_koperasi'])->name('keuangan.penggajian.pengaturan_gaji.potongan_koperasi.json_get_daftar_potongan_koperasi')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-koperasi/upload', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganKoperasiController::class, 'upload'])->name('keuangan.penggajian.pengaturan_gaji.potongan_koperasi.upload')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-koperasi/insert', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganKoperasiController::class, 'insert_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_koperasi.insert_potongan')->middleware('modul:Pengaturan Gaji');
Route::get('/keu/penggajian/pengaturan-gaji/potongan-koperasi/detail/{bulan}/{tahun}', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganKoperasiController::class, 'get_detail_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_koperasi.get_detail_potongan')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-koperasi/detail/json', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganKoperasiController::class, 'json_get_detail_potongan_koperasi'])->name('keuangan.penggajian.pengaturan_gaji.potongan_koperasi.json_get_detail_potongan_koperasi')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-koperasi/detail/update', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganKoperasiController::class, 'update_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_koperasi.update_potongan')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-koperasi/detail/delete', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganKoperasiController::class, 'delete_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_koperasi.delete_potongan')->middleware('modul:Pengaturan Gaji');
Route::get('/keu/penggajian/pengaturan-gaji/potongan-koperasi/export/pdf/{bulan}/{tahun}', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganKoperasiController::class, 'export_pdf_detail_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_koperasi.export_pdf_detail_potongan')->middleware('modul:Pengaturan Gaji');
// Potongan BPJS
Route::get('/keu/penggajian/pengaturan-gaji/potongan-bpjs', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganBpjsCont::class, 'index'])->name('keuangan.penggajian.pengaturan_gaji.potongan_bpjs.index')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-bpjs/json', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganBpjsCont::class, 'json_get_daftar_potongan_bpjs'])->name('keuangan.penggajian.pengaturan_gaji.potongan_bpjs.json_get_daftar_potongan_bpjs')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-bpjs/upload', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganBpjsCont::class, 'upload'])->name('keuangan.penggajian.pengaturan_gaji.potongan_bpjs.upload')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-bpjs/insert', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganBpjsCont::class, 'insert_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_bpjs.insert_potongan')->middleware('modul:Pengaturan Gaji');
Route::get('/keu/penggajian/pengaturan-gaji/potongan-bpjs/detail/{bulan}/{tahun}', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganBpjsCont::class, 'get_detail_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_bpjs.get_detail_potongan')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-bpjs/detail/json', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganBpjsCont::class, 'json_get_detail_potongan_bpjs'])->name('keuangan.penggajian.pengaturan_gaji.potongan_bpjs.json_get_detail_potongan_bpjs')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-bpjs/detail/update', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganBpjsCont::class, 'update_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_bpjs.update_potongan')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-bpjs/detail/delete', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganBpjsCont::class, 'delete_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_bpjs.delete_potongan')->middleware('modul:Pengaturan Gaji');
Route::get('/keu/penggajian/pengaturan-gaji/potongan-bpjs/export/pdf/{bulan}/{tahun}', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganBpjsCont::class, 'export_pdf_detail_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_bpjs.export_pdf_detail_potongan')->middleware('modul:Pengaturan Gaji');
// Potongan Arisan
Route::get('/keu/penggajian/pengaturan-gaji/potongan-arisan', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganArisanController::class, 'index'])->name('keuangan.penggajian.pengaturan_gaji.potongan_arisan.index')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-arisan/json', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganArisanController::class, 'json_get_daftar_potongan_arisan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_arisan.json_get_daftar_potongan_arisan')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-arisan/upload', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganArisanController::class, 'upload'])->name('keuangan.penggajian.pengaturan_gaji.potongan_arisan.upload')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-arisan/insert', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganArisanController::class, 'insert_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_arisan.insert_potongan')->middleware('modul:Pengaturan Gaji');
Route::get('/keu/penggajian/pengaturan-gaji/potongan-arisan/detail/{bulan}/{tahun}', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganArisanController::class, 'get_detail_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_arisan.get_detail_potongan')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-arisan/detail/json', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganArisanController::class, 'json_get_detail_potongan_arisan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_arisan.json_get_detail_potongan_arisan')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-arisan/detail/update', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganArisanController::class, 'update_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_arisan.update_potongan')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-arisan/detail/delete', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganArisanController::class, 'delete_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_arisan.delete_potongan')->middleware('modul:Pengaturan Gaji');
Route::get('/keu/penggajian/pengaturan-gaji/potongan-arisan/export/pdf/{bulan}/{tahun}', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganArisanController::class, 'export_pdf_detail_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_arisan.export_pdf_detail_potongan')->middleware('modul:Pengaturan Gaji');
// Potongan Qurban
Route::get('/keu/penggajian/pengaturan-gaji/potongan-qurban', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganQurbanController::class, 'index'])->name('keuangan.penggajian.pengaturan_gaji.potongan_qurban.index')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-qurban/json', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganQurbanController::class, 'json_get_daftar_potongan_qurban'])->name('keuangan.penggajian.pengaturan_gaji.potongan_qurban.json_get_daftar_potongan_qurban')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-qurban/upload', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganQurbanController::class, 'upload'])->name('keuangan.penggajian.pengaturan_gaji.potongan_qurban.upload')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-qurban/insert', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganQurbanController::class, 'insert_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_qurban.insert_potongan')->middleware('modul:Pengaturan Gaji');
Route::get('/keu/penggajian/pengaturan-gaji/potongan-qurban/detail/{bulan}/{tahun}', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganQurbanController::class, 'get_detail_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_qurban.get_detail_potongan')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-qurban/detail/json', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganQurbanController::class, 'json_get_detail_potongan_qurban'])->name('keuangan.penggajian.pengaturan_gaji.potongan_qurban.json_get_detail_potongan_qurban')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-qurban/detail/update', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganQurbanController::class, 'update_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_qurban.update_potongan')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-qurban/detail/delete', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganQurbanController::class, 'delete_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_qurban.delete_potongan')->middleware('modul:Pengaturan Gaji');
// Potongan Lainnya
Route::get('/keu/penggajian/pengaturan-gaji/potongan-lainnya', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganLainnyaController::class, 'index'])->name('keuangan.penggajian.pengaturan_gaji.potongan_lainnya.index')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-lainnya/json', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganLainnyaController::class, 'json_get_daftar_potongan_lainnya'])->name('keuangan.penggajian.pengaturan_gaji.potongan_lainnya.json_get_daftar_potongan_lainnya')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-lainnya/upload', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganLainnyaController::class, 'upload'])->name('keuangan.penggajian.pengaturan_gaji.potongan_lainnya.upload')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-lainnya/insert', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganLainnyaController::class, 'insert_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_lainnya.insert_potongan')->middleware('modul:Pengaturan Gaji');
Route::get('/keu/penggajian/pengaturan-gaji/potongan-lainnya/detail/{bulan}/{tahun}', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganLainnyaController::class, 'get_detail_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_lainnya.get_detail_potongan')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-lainnya/detail/json', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganLainnyaController::class, 'json_get_detail_potongan_lainnya'])->name('keuangan.penggajian.pengaturan_gaji.potongan_lainnya.json_get_detail_potongan_lainnya')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-lainnya/detail/update', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganLainnyaController::class, 'update_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_lainnya.update_potongan')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/potongan-lainnya/detail/delete', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganLainnyaController::class, 'delete_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_lainnya.delete_potongan')->middleware('modul:Pengaturan Gaji');
Route::get('/keu/penggajian/pengaturan-gaji/potongan-lainnya/export/pdf/{bulan}/{tahun}', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PotonganLainnyaController::class, 'export_pdf_detail_potongan'])->name('keuangan.penggajian.pengaturan_gaji.potongan_lainnya.export_pdf_detail_potongan')->middleware('modul:Pengaturan Gaji');
// Pengaturan UMR
Route::get('/keu/penggajian/pengaturan-gaji/pengaturan-umr', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PengaturanUMRController::class, 'index'])->name('keuangan.penggajian.pengaturan_gaji.pengaturan_umr.index')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/pengaturan-umr/json', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PengaturanUMRController::class, 'json_get_daftar'])->name('keuangan.penggajian.pengaturan_gaji.pengaturan_umr.json_get_daftar')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/pengaturan-umr/insert', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PengaturanUMRController::class, 'insert_umr'])->name('keuangan.penggajian.pengaturan_gaji.pengaturan_umr.insert_umr')->middleware('modul:Pengaturan Gaji');
Route::get('/keu/penggajian/pengaturan-gaji/pengaturan-umr/detail/{id_umr}', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PengaturanUMRController::class, 'get_detail_umr'])->name('keuangan.penggajian.pengaturan_gaji.pengaturan_umr.get_detail_umr')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/pengaturan-umr/detail/json', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\PengaturanUMRController::class, 'json_get_detail_umr'])->name('keuangan.penggajian.pengaturan_gaji.pengaturan_umr.json_get_detail_umr')->middleware('modul:Pengaturan Gaji');
// Pengaturan Tunjangan Kinerja
Route::get('/keu/penggajian/pengaturan-gaji/tunjangan-kinerja', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\DaftarTunjanganKinerjaController::class, 'index'])->name('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_kinerja.index')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/tunjangan-kinerja/json', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\DaftarTunjanganKinerjaController::class, 'json_get_daftar'])->name('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_kinerja.json_get_daftar')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/tunjangan-kinerja/insup', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\DaftarTunjanganKinerjaController::class, 'insup_kinerja'])->name('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_kinerja.insup_kinerja')->middleware('modul:Pengaturan Gaji');
// Pengaturan Tunjangan Struktural
Route::get('/keu/penggajian/pengaturan-gaji/tunjangan-struktural', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\DaftarTunjanganStrukturalController::class, 'index'])->name('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_struktural.index')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/tunjangan-struktural/json', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\DaftarTunjanganStrukturalController::class, 'json_get_daftar'])->name('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_struktural.json_get_daftar')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/tunjangan-struktural/insup', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\DaftarTunjanganStrukturalController::class, 'insup_jabatan_struktural'])->name('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_struktural.insup_jabatan_struktural')->middleware('modul:Pengaturan Gaji');
// Pengaturan Tunjangan Fungsional
Route::get('/keu/penggajian/pengaturan-gaji/tunjangan-fungsional', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\DaftarTunjanganFungsionalController::class, 'index'])->name('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_fungsional.index')->middleware('modul:Pengaturan Gaji');
Route::post('/keu/penggajian/pengaturan-gaji/tunjangan-fungsional/json', [\App\Http\Controllers\KeuanganPage\Penggajian\PengaturanGaji\DaftarTunjanganFungsionalController::class, 'json'])->name('keuangan.penggajian.pengaturan_gaji.daftar_tunjangan_fungsional.json')->middleware('modul:Pengaturan Gaji');
// Honorarium Mengajar
Route::get('/keu/penggajian/honorarium/honorarium-mengajar/daftar', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumMengajarController::class, 'index'])->name('keuangan.penggajian.honorarium.honorarium_mengajar.index')->middleware('modul:Pengelolaan Honorarium Mengajar');
Route::get('/keu/penggajian/honorarium/honorarium-mengajar/detail/{id_honorarium}', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumMengajarController::class, 'detail'])->name('keuangan.penggajian.honorarium.honorarium_mengajar.detail')->middleware('modul:Pengelolaan Honorarium Mengajar');
Route::get('/keu/penggajian/honorarium/honorarium-mengajar/slip-gaji/{id_honorarium}', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumMengajarController::class, 'slip_gaji'])->name('keuangan.penggajian.honorarium.honorarium_mengajar.slip_gaji')->middleware('modul:Pengelolaan Honorarium Mengajar');
Route::post('/keu/penggajian/honorarium/honorarium-mengajar/json', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumMengajarController::class, 'json'])->name('keuangan.penggajian.honorarium.honorarium_mengajar.json')->middleware('modul:Pengelolaan Honorarium Mengajar');
Route::post('/keu/penggajian/honorarium/honorarium-mengajar/set-honor', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumMengajarController::class, 'set_honorarium'])->name('keuangan.penggajian.honorarium.honorarium_mengajar.set_honorarium')->middleware('modul:Pengelolaan Honorarium Mengajar');
Route::get('/keu/penggajian/honorarium/honorarium-mengajar/{id_karyawan}', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumMengajarController::class, 'generate_ulang_gaji'])->name('keuangan.penggajian.honorarium.honorarium_mengajar.generate_ulang_gaji')->middleware('modul:Pengelolaan Honorarium Mengajar');
Route::get('/keu/penggajian/honorarium/honorarium-mengajar/export/for-rekap/excel/{periode}/{bulan}', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumMengajarController::class, 'export_excel_for_rekap'])->name('keuangan.penggajian.honorarium.honorarium_mengajar.export_excel_for_rekap')->middleware('modul:Pengelolaan Honorarium Mengajar');
Route::get('/keu/penggajian/honorarium/honorarium-mengajar/export/for-rekap/pdf/{periode}/{bulan}', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumMengajarController::class, 'export_pdf_for_rekap'])->name('keuangan.penggajian.honorarium.honorarium_mengajar.export_pdf_for_rekap')->middleware('modul:Pengelolaan Honorarium Mengajar');
Route::get('/keu/penggajian/honorarium/honorarium-mengajar/export/for-rekap/slip/pdf/{periode}/{bulan}', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumMengajarController::class, 'export_zip_rekap_slip'])->name('keuangan.penggajian.honorarium.honorarium_mengajar.export_slip')->middleware('modul:Pengelolaan Honorarium Mengajar');

// Honorarium Koreksi
Route::get('/keu/penggajian/honorarium/honorarium-koreksi/daftar', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumKoreksiController::class, 'index'])->name('keuangan.penggajian.honorarium.honorarium_koreksi.index')->middleware('modul:Pengelolaan Honorarium Koreksi');
Route::get('/keu/penggajian/honorarium/honorarium-koreksi/detail/{id_honorarium}', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumKoreksiController::class, 'detail'])->name('keuangan.penggajian.honorarium.honorarium_koreksi.detail')->middleware('modul:Pengelolaan Honorarium Koreksi');
Route::get('/keu/penggajian/honorarium/honorarium-koreksi/slip-gaji/{id_honorarium}', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumKoreksiController::class, 'slip_gaji'])->name('keuangan.penggajian.honorarium.honorarium_koreksi.slip_gaji')->middleware('modul:Pengelolaan Honorarium Koreksi');
Route::post('/keu/penggajian/honorarium/honorarium-koreksi/json', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumKoreksiController::class, 'json'])->name('keuangan.penggajian.honorarium.honorarium_koreksi.json')->middleware('modul:Pengelolaan Honorarium Koreksi');
Route::post('/keu/penggajian/honorarium/honorarium-koreksi/set-honor', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumKoreksiController::class, 'set_honorarium'])->name('keuangan.penggajian.honorarium.honorarium_koreksi.set_honorarium')->middleware('modul:Pengelolaan Honorarium Koreksi');
Route::get('/keu/penggajian/honorarium/honorarium-koreksi/{id_karyawan}', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumKoreksiController::class, 'generate_ulang_honor'])->name('keuangan.penggajian.honorarium.honorarium_koreksi.generate_ulang_honor')->middleware('modul:Pengelolaan Honorarium Koreksi');
Route::get('/keu/penggajian/honorarium/honorarium-koreksi/export/for-rekap/excel/{ta}', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumKoreksiController::class, 'export_excel_for_rekap'])->name('keuangan.penggajian.honorarium.honorarium_koreksi.export_excel_for_rekap')->middleware('modul:Pengelolaan Honorarium Koreksi');
Route::get('/keu/penggajian/honorarium/honorarium-koreksi/export/for-rekap/pdf/{ta}', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumKoreksiController::class, 'export_pdf_for_rekap'])->name('keuangan.penggajian.honorarium.honorarium_koreksi.export_pdf_for_rekap')->middleware('modul:Pengelolaan Honorarium Koreksi');
// Honorarium Pengawas
Route::get('/keu/penggajian/honorarium/honorarium-pengawas/daftar', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumPengawasController::class, 'index'])->name('keuangan.penggajian.honorarium.honorarium_pengawas.index')->middleware('modul:Pengelolaan Honorarium Pengawas');
Route::get('/keu/penggajian/honorarium/honorarium-pengawas/detail/{id_honorarium}', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumPengawasController::class, 'detail'])->name('keuangan.penggajian.honorarium.honorarium_pengawas.detail')->middleware('modul:Pengelolaan Honorarium Pengawas');
Route::get('/keu/penggajian/honorarium/honorarium-pengawas/slip-gaji/{id_honorarium}', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumPengawasController::class, 'slip_gaji'])->name('keuangan.penggajian.honorarium.honorarium_pengawas.slip_gaji')->middleware('modul:Pengelolaan Honorarium Pengawas');
Route::post('/keu/penggajian/honorarium/honorarium-pengawas/json', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumPengawasController::class, 'json'])->name('keuangan.penggajian.honorarium.honorarium_pengawas.json')->middleware('modul:Pengelolaan Honorarium Pengawas');
Route::post('/keu/penggajian/honorarium/honorarium-pengawas/set-honor', [\App\Http\Controllers\KeuanganPage\Penggajian\Honorarium\HonorariumPengawasController::class, 'set_honorarium'])->name('keuangan.penggajian.honorarium.honorarium_pengawas.set_honorarium')->middleware('modul:Pengelolaan Honorarium Pengawas');
// SIAKAD
// Mahasiswa
Route::get('/keu/siakad/daftar-mahasiswa', [\App\Http\Controllers\KeuanganPage\Siakad\MahasiswaController::class, 'daftar_mahasiswa'])->name('keu.siakad.mahasiswa.daftar_mahasiswa')->middleware('modul:Keuangan - Melihat Daftar Mahasiswa Siakad');
Route::post('/keu/siakad/json/daftar-mahasiswa', [\App\Http\Controllers\KeuanganPage\Siakad\MahasiswaController::class, 'json_daftar_mahasiswa'])->name('keu.siakad.mahasiswa.json_daftar_mahasiswa')->middleware('modul:Keuangan - Melihat Daftar Mahasiswa Siakad');

// LOGIN PAGE
Route::get('/tes-slip/{id_rekap}', [\App\Http\Controllers\KeuanganPage\Penggajian\GajiBulananController::class, 'slip_gaji']);
Route::get('/tracer-study', [\App\Http\Controllers\TracerStudy\LoginController::class, 'login'])->middleware('modul:login')->name('tracer_study.login.login');
Route::get('/test-telegram', [\App\Http\Controllers\SuperAdminPage\Moodle\MahasiswaController::class, 'testTelegram'])->name('telegram.test');
/*
 * ------------------------------------------------------------------------
 * ADMIN SINTA
 * ------------------------------------------------------------------------
*/
/* AUTHORS */
Route::get('/sinta-authors', [\App\Http\Controllers\AdminSinta\AuthorsCont::class, 'index'])->name('admin_sinta.authors.index');
