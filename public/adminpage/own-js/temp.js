jQuery.temp = {
    data: {},
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        $("#btn-insert-kurikulum").click(function () {
            $.ajax({
                url: '/migrasi/get-kurikulum',
                method: 'get',
                success: function (result) {
                    self.next_kurikulum(result)
                }
            })
        });
        $("#btn-insert-peserta-kelas-kuliah").click(function () {
            $.ajax({
                url: '/migrasi/get-peserta-kelas-kuliah-lokal',
                method: 'get',
                success: function (result) {
                    self.next_peserta_kelas_kuliah(result)
                }
            })
        });
        $("#btn-insert-matkul-kurikulum").click(function () {
            $.ajax({
                url: '/migrasi/get-matkul-kurikulum',
                method: 'get',
                success: function (result) {
                    self.next_matkul_kurikulum(result)
                }
            })
        });
        $("#btn-insert").click(function () {
            $.ajax({
                url: '/migrasi/get-am',
                method: 'get',
                success: function (result) {
                    self.next_aktivitas_mahasiswa(result)
                }
            })
        });
        $("#btn-update").click(function () {
            $.ajax({
                url: '/migrasi/get-am',
                method: 'get',
                success: function (result) {
                    self.next_update_aktivitas_mahasiswa(result)
                }
            })
        });
        $("#btn-insert-peserta").click(function () {
            $.ajax({
                url: '/migrasi/get-am-anggota',
                method: 'get',
                success: function (result) {
                    self.next_aktivitas_mahasiswa_anggota(result)
                }
            })
        });

        $("#btn-insert-pengampu-kelas-kuliah").click(function () {
            $.ajax({
                url: '/migrasi/get-pengampu-kelas-kuliah-lokal',
                method: 'get',
                success: function (result) {
                    self.next_pengampu_kelas_kuliah(result)
                }
            })
        });

        $("#btn-insert-nilai-kelas-kuliah").click(function () {
            $.ajax({
                url: '/migrasi/get-nilai-kelas',
                method: 'get',
                success: function (result) {
                    self.next_nilai_kelas_kuliah(result)
                }
            })
        });

        $("#btn-insert-indeks-prestasi").click(function () {
            $.ajax({
                url: '/migrasi/get-indeks-prestasi',
                method: 'get',
                success: function (result) {
                    self.next_indeks_prestasi(result)
                }
            })
        });

        $("#btn-delete-peserta").click(function () {
            $.ajax({
                url: '/migrasi/get-am-anggota',
                method: 'get',
                success: function (result) {
                    self.next_delete_anggota_am(result)
                }
            })
        });

        $("#btn-insert-penguji").click(function () {
            $.ajax({
                url: '/migrasi/get-am-penguji',
                method: 'get',
                success: function (result) {
                    self.next_aktivitas_mahasiswa_penguji(result)
                }
            })
        });

        $("#btn-insert-biodata-mahasiswa").click(function () {
            $.ajax({
                url: '/migrasi/get-mahasiswa-baru',
                method: 'get',
                success: function (result) {
                    self.next_mahasiswa_baru(result)
                }
            })
        });

        $("#btn-insert-pembimbing").click(function () {
            $.ajax({
                url: '/migrasi/get-am-pembimbing',
                method: 'get',
                success: function (result) {
                    self.next_aktivitas_mahasiswa_pembimbing(result)
                }
            })
        });
        $("#btn-cek").click(function () {
            $.ajax({
                url: '/get-jadwal-kuliah/' + $("#tahun_akademik").val(),
                method: 'get',
                success: function (result) {
                    self.next_cek(result);
                }
            })
        });
        $("#btn-delete").click(function () {
            $.ajax({
                url: '/delete-peserta',
                method: 'post',
                data: {
                    'id_mahasiswa': $("#id_mahasiswa").val(),
                    'id_kelas': $("#id_kelas").val(),
                },
                success: function (result) {
                    console.log(result);
                }
            })
        });
        $("#btn-delete-peserta").click(function () {
            $.ajax({
                url: '/get-peserta/' + $("#id_kelas_delete").val(),
                method: 'get',
                success: function (result) {
                    self.next_peserta(result);
                }
            })
        });
    },
    next_cek: function (data, index = 0, progres = 0, failed = 0, inserted = 0, updated = 0) {
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/cek-feeder',
            method: 'post',
            data: {
                'id': data[index].id_kelas_kuliah
            },
            success: function (result) {
                if (result)
                    inserted++
                else
                    failed++;
            },
            complete: function () {
                if (index >= (data.length - 1)) {
                    console.log('Ada: ' + inserted + '; Tidak Ada: ' + failed)
                } else {
                    index++;
                    self.next_cek(data, index, progres, failed, inserted, updated)
                }
            }
        })
    },
    next_peserta: function (data, index = 0, inserted = 0, failed = 0){
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/delete-peserta',
            method: 'post',
            data: {
                'id_mahasiswa': data[index].id_registrasi_mahasiswa,
                'id_kelas': data[index].id_kelas_kuliah
            },
            success: function (result) {
                if (result)
                    inserted++
                else
                    failed++;
            },
            complete: function () {
                if (index >= (data.length - 1)) {
                    console.log('Ada: ' + inserted + '; Tidak Ada: ' + failed)
                } else {
                    index++;
                    self.next_peserta(data, index,inserted, failed)
                }
            }
        })
    },
    next_delete_anggota_am: function (data, index = 0, inserted = 0, failed = 0){
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/migrasi/delete-anggota-am',
            method: 'post',
            data: {
                'id_anggota': data[index].id_anggota_feeder,
            },
            success: function (result) {
                if (result)
                    inserted++
                else
                    failed++;
            },
            complete: function () {
                if (index >= (data.length - 1)) {
                    console.log('Ada: ' + inserted + '; Tidak Ada: ' + failed)
                } else {
                    index++;
                    self.next_delete_anggota_am(data, index,inserted, failed)
                }
            }
        })
    },
    next_kurikulum: function (data, index = 0, inserted = 0, failed = 0){
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/migrasi/insert-kurikulum',
            method: 'post',
            data: {
                'id': data[index].id,
                'nama_kurikulum': data[index].nama_kurikulum,
                'id_prodi': data[index].id_prodi,
                'id_semester': data[index].id_semester,
                'jumlah_sks_lulus': data[index].jumlah_sks_lulus,
                'jumlah_sks_wajib': data[index].jumlah_sks_wajib,
                'jumlah_sks_pilihan': data[index].jumlah_sks_pilihan,
            },
            success: function (result) {
                if (result)
                    inserted++
                else
                    failed++;
            },
            complete: function () {
                if (index >= (data.length - 1)) {
                    console.log('Ada: ' + inserted + '; Tidak Ada: ' + failed)
                } else {
                    index++;
                    self.next_kurikulum(data, index,inserted, failed)
                }
            }
        })
    },
    next_peserta_kelas_kuliah: function (data, index = 0, inserted = 0, failed = 0){
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/migrasi/insert-peserta-kelas-kuliah',
            method: 'post',
            data: {
                'id_kelas_kuliah': data[index].id_kelas_kuliah,
                'id_registrasi_mahasiswa': data[index].id_registrasi_mahasiswa,
            },
            success: function (result) {
                if (result.error_code == 106){
                    alert('Web Service Admin');
                }
                if (result)
                    inserted++
                else
                    failed++;
            },
            complete: function () {
                if (index >= (data.length - 1)) {
                    console.log('Ada: ' + inserted + '; Tidak Ada: ' + failed)
                } else {
                    index++;
                    self.next_peserta_kelas_kuliah(data, index,inserted, failed)
                }
            },
        })
    },
    next_pengampu_kelas_kuliah: function (data, index = 0, inserted = 0, failed = 0){
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/migrasi/insert-pengampu-kelas-kuliah',
            method: 'post',
            data: {
                'id': data[index].id,
                'id_registrasi_dosen': data[index].id_registrasi_dosen,
                'id_kelas_kuliah': data[index].id_kelas_kuliah,
                'id_substansi': data[index].id_substansi,
                'sks_substansi_total': data[index].sks_substansi_total,
                'rencana_minggu_pertemuan': data[index].rencana_minggu_pertemuan,
                'realisasi_minggu_pertemuan': data[index].realisasi_minggu_pertemuan,
                'id_jenis_evaluasi': data[index].id_jenis_evaluasi,
            },
            success: function (result) {
                if (result)
                    inserted++
                else
                    failed++;
            },
            complete: function () {
                if (index >= (data.length - 1)) {
                    console.log('Ada: ' + inserted + '; Tidak Ada: ' + failed)
                } else {
                    index++;
                    self.next_pengampu_kelas_kuliah(data, index,inserted, failed)
                }
            }
        })
    },
    next_nilai_kelas_kuliah: function (data, index = 0, inserted = 0, failed = 0){
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/migrasi/insert-nilai-kelas-kuliah',
            method: 'post',
            data: {
                'id': data[index].id,
                'id_registrasi_mahasiswa': data[index].id_registrasi_mahasiswa,
                'id_kelas_kuliah': data[index].id_kelas_kuliah,
                'nilai_angka': data[index].nilai_angka,
                'nilai_huruf': data[index].nilai_huruf,
                'nilai_indeks': data[index].nilai_indeks,
            },
            success: function (result) {
                if (result.error_code == 106){
                    alert('Web Service Admin');
                }
                if (result)
                    inserted++
                else
                    failed++;
            },
            complete: function () {
                if (index >= (data.length - 1)) {
                    console.log('Ada: ' + inserted + '; Tidak Ada: ' + failed)
                } else {
                    index++;
                    self.next_nilai_kelas_kuliah(data, index,inserted, failed)
                }
            }
        })
    },
    next_indeks_prestasi: function (data, index = 0, inserted = 0, failed = 0){
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/migrasi/insert-indeks-prestasi',
            method: 'post',
            data: {
                'id': data[index].id,
                'id_registrasi_mahasiswa': data[index].id_registrasi_mahasiswa,
                'id_semester': data[index].id_semester,
                'id_status_mahasiswa': data[index].id_status_mahasiswa,
                'ips': data[index].ips,
                'ipk': data[index].ipk,
                'sks_semester': data[index].sks_semester,
                'total_sks': data[index].sks_total,
                'biaya_kuliah_smt': data[index].biaya,
                'id_pembiayaan': data[index].id_biaya,
            },
            success: function (result) {
                if (result.error_code == 106){
                    alert('Web Service Admin');
                }
                if (result)
                    inserted++
                else
                    failed++;
            },
            complete: function () {
                if (index >= (data.length - 1)) {
                    console.log('Ada: ' + inserted + '; Tidak Ada: ' + failed)
                } else {
                    index++;
                    self.next_indeks_prestasi(data, index,inserted, failed)
                }
            }
        })
    },
    next_matkul_kurikulum: function (data, index = 0, inserted = 0, failed = 0){
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/migrasi/insert-matkul-kurikulum',
            method: 'post',
            data: {
                'id_kurikulum': data[index].id_kurikulum,
                'id_matkul': data[index].id_matkul,
                'semester': data[index].semester,
                'sks_mata_kuliah': data[index].sks_mata_kuliah,
                'sks_tatap_muka': data[index].sks_tatap_muka,
                'sks_praktek': data[index].sks_praktek,
                'sks_praktek_lapangan': data[index].sks_praktek_lapangan,
                'sks_simulasi': data[index].sks_simulasi,
                'apakah_wajib': data[index].apakah_wajib,
            },
            success: function (result) {
                if (result)
                    inserted++
                else
                    failed++;
            },
            complete: function () {
                if (index >= (data.length - 1)) {
                    console.log('Ada: ' + inserted + '; Tidak Ada: ' + failed)
                } else {
                    index++;
                    self.next_matkul_kurikulum(data, index,inserted, failed)
                }
            }
        })
    },
    next_aktivitas_mahasiswa: function (data, index = 0, inserted = 0, failed = 0){
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/migrasi/insert-am',
            method: 'post',
            data: {
                'id': data[index].id,
                'jenis_anggota': data[index].jenis_anggota,
                'id_jenis_aktivitas': data[index].id_jenis_aktivitas,
                'id_prodi': data[index].id_prodi_baru,
                'id_semester': data[index].id_semester,
                'judul': data[index].judul,
                'keterangan': data[index].keterangan,
                'lokasi': data[index].lokasi,
                'sk_tugas': data[index].sk_tugas,
                'tanggal_sk_tugas': data[index].tanggal_sk_tugas,
                'tanggal_mulai': data[index].tanggal_mulai,
                'tanggal_selesai': data[index].tanggal_selesai,
            },
            success: function (result) {
                if (result)
                    inserted++
                else
                    failed++;
            },
            complete: function () {
                if (index >= (data.length - 1)) {
                    console.log('Ada: ' + inserted + '; Tidak Ada: ' + failed)
                } else {
                    index++;
                    self.next_aktivitas_mahasiswa(data, index,inserted, failed)
                }
            }
        })
    },
    next_update_aktivitas_mahasiswa: function (data, index = 0, inserted = 0, failed = 0){
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/migrasi/update-am',
            method: 'post',
            data: {
                'id_aktivitas': data[index].id_aktivitas_feeder,
                'jenis_anggota': data[index].jenis_anggota,
                'id_jenis_aktivitas': data[index].id_jenis_aktivitas,
                'id_prodi': data[index].id_prodi_baru,
                'id_semester': data[index].id_semester,
                'judul': data[index].judul,
                'keterangan': data[index].keterangan,
                'lokasi': data[index].lokasi,
                'sk_tugas': data[index].sk_tugas,
                'tanggal_sk_tugas': data[index].tanggal_sk_tugas,
                'tanggal_mulai': data[index].tanggal_mulai,
                'tanggal_selesai': data[index].tanggal_selesai,
            },
            success: function (result) {
                if (result)
                    inserted++
                else
                    failed++;
            },
            complete: function () {
                if (index >= (data.length - 1)) {
                    console.log('Ada: ' + inserted + '; Tidak Ada: ' + failed)
                } else {
                    index++;
                    self.next_update_aktivitas_mahasiswa(data, index,inserted, failed)
                }
            }
        })
    },
    next_aktivitas_mahasiswa_pembimbing: function (data, index = 0, inserted = 0, failed = 0){
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/migrasi/insert-am-pembimbing',
            method: 'post',
            data: {
                'id': data[index].id,
                'id_aktivitas': data[index].id_aktivitas,
                'id_kategori_kegiatan': data[index].id_kategori_kegiatan,
                'id_dosen': data[index].id_dosen,
                'pembimbing_ke': data[index].pembimbing_ke,
            },
            success: function (result) {
                if (result)
                    inserted++
                else
                    failed++;
            },
            complete: function () {
                if (index >= (data.length - 1)) {
                    console.log('Ada: ' + inserted + '; Tidak Ada: ' + failed)
                } else {
                    index++;
                    self.next_aktivitas_mahasiswa_pembimbing(data, index,inserted, failed)
                }
            }
        })
    },
    next_aktivitas_mahasiswa_penguji: function (data, index = 0, inserted = 0, failed = 0){
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/migrasi/insert-am-penguji',
            method: 'post',
            data: {
                'id': data[index].id,
                'id_aktivitas': data[index].id_aktivitas,
                'id_kategori_kegiatan': data[index].id_kategori_kegiatan,
                'id_dosen': data[index].id_dosen,
                'penguji_ke': data[index].penguji_ke,
            },
            success: function (result) {
                if (result)
                    inserted++
                else
                    failed++;
            },
            complete: function () {
                if (index >= (data.length - 1)) {
                    console.log('Ada: ' + inserted + '; Tidak Ada: ' + failed)
                } else {
                    index++;
                    self.next_aktivitas_mahasiswa_penguji(data, index,inserted, failed)
                }
            }
        })
    },
    next_mahasiswa_baru: function (data, index = 0, inserted = 0, failed = 0){
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/migrasi/insert-mahasiswa-baru',
            method: 'post',
            data: {
                'id': data[index].id,
                'nama_mahasiswa': data[index].nama_mahasiswa,
                'jenis_kelamin': data[index].jenis_kelamin,
                'jalan': data[index].jalan,
                'rt': data[index].rt,
                'rw': data[index].rw,
                'dusun': data[index].dusun,
                'kelurahan': data[index].kelurahan,
                'kode_pos': data[index].kode_pos,
                'nisn': data[index].nisn,
                'nik': data[index].nik,
                'tempat_lahir': data[index].tempat_lahir,
                'tanggal_lahir': data[index].tgl_lahir,
                'nama_ayah': data[index].nama_ayah,
                'tanggal_lahir_ayah': data[index].tgl_lahir_ayah,
                'nik_ayah': data[index].nik_ayah,
                'id_jenjang_pendidikan_ayah': data[index].id_jenjang_pendidikan_ayah,
                'id_pekerjaan_ayah': data[index].id_pekerjaan_ayah,
                'id_penghasilan_ayah': data[index].id_penghasilan_ayah,
                'id_kebutuhan_khusus_ayah': data[index].id_kebutuhan_khusus_ayah,
                'nama_ibu_kandung': data[index].nama_ibu_kandung,
                'tanggal_lahir_ibu': data[index].tgl_lahir_ibu,
                'nik_ibu': data[index].nik_ibu,
                'id_jenjang_pendidikan_ibu': data[index].id_jenjang_pendidikan_ibu,
                'id_pekerjaan_ibu': data[index].id_pekerjaan_ibu,
                'id_penghasilan_ibu': data[index].id_penghasilan_ibu,
                'id_kebutuhan_khusus_ibu': data[index].id_kebutuhan_khusus_ibu,
                'nama_wali': data[index].nama_wali,
                'tanggal_lahir_wali': data[index].tgl_lahir_wali,
                'id_jenjang_pendidikan_wali': data[index].id_jenjang_pendidikan_wali,
                'id_pekerjaan_wali': data[index].id_pekerjaan_wali,
                'id_penghasilan_wali': data[index].id_penghasilan_wali,
                'id_kebutuhan_khusus_mahasiswa': data[index].kebutuhan_khusus_mahasiswa,
                'telepon': data[index].telepon,
                'handphone': data[index].handphone,
                'email': data[index].email,
                'penerima_kps': data[index].penerima_kps,
                'no_kps': data[index].no_kps,
                'npwp': data[index].npwp,
                'id_wilayah': data[index].id_wilayah,
                'id_jenis_tinggal': data[index].id_jenis_tinggal,
                'id_agama': data[index].id_agama,
                'id_alat_transportasi': data[index].id_alat_transportasi,
                'kewarganegaraan': data[index].kewarganegaraan,
            },
            success: function (result) {
                if (result)
                    inserted++
                else
                    failed++;
            },
            complete: function () {
                if (index >= (data.length - 1)) {
                    console.log('Ada: ' + inserted + '; Tidak Ada: ' + failed)
                } else {
                    index++;
                    self.next_mahasiswa_baru(data, index,inserted, failed)
                }
            }
        })
    },
    next_aktivitas_mahasiswa_anggota: function (data, index = 0, inserted = 0, failed = 0){
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/migrasi/insert-am-anggota',
            method: 'post',
            data: {
                'id': data[index].id,
                'id_aktivitas': data[index].id_aktivitas_feeder,
                'id_registrasi_mahasiswa': data[index].id_registrasi_mahasiswa,
                'jenis_peran': data[index].jenis_peran
            },
            success: function (result) {
                if (result)
                    inserted++
                else
                    failed++;
            },
            complete: function () {
                if (index >= (data.length - 1)) {
                    console.log('Ada: ' + inserted + '; Tidak Ada: ' + failed)
                } else {
                    index++;
                    self.next_aktivitas_mahasiswa_anggota(data, index,inserted, failed)
                }
            }
        })
    }
};

jQuery(document).ready(function () {
    jQuery.temp.init();
});
