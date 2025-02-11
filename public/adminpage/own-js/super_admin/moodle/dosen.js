jQuery.dosen = {
    data: {
        isSyncCanceled: false,
        log_table: $("#log-table").DataTable({
            scrollY: '400px',
            columns: [
                {width: "5%", sClass: 'text-center', searchable: false},
                {width: "50%", searchable: false},
                {width: "45%", sClass: 'text-center', searchable: false},
                {searchable: true, visible: false},
                {searchable: true, visible: false},
                {searchable: true, visible: false},
            ],
            scrollCollapse: true,
            paging: true,
            processing: true,
            pageLength: 10,
            ordering: false,
            lengthChange: false,
            autoWidth: false,
            sDom: 'ltipr',
            language: {
                "emptyTable": "Tidak ditemukan data"
            },
        }),
        tabel: $("#table"),
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        // Option Data
        $(".select2").select2();
        // Table With DataTable
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/super-admin/moodle/dosen/json/daftar-dosen',
                type: 'post'
            },
            scrollY: '500px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'nomor',
                    searchable: false,
                    sClass: 'text-center',
                    width: "5%"
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                    render: function (data) {
                        return "<b>" + data.nama_lengkap + " (" + data.nidn + ")</b><br/>" +
                            "<small>Username : " + data.username + " | Password : " + data.password + "</small><br/>" +
                            "<small>Email : " + data.email + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return "<b>" + data.nama_prodi + "</b><br/>" +
                            "<small>Nomor HP : " + data.nomor_hp + " | Kota Rumah : " + data.kota_asal + "</small><br/>" +
                            "<small>Alamat : " + data.alamat_rumah + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        var icon = "<i class='fas fa-check-square text-success'></i>";
                        var keterangan = "Sudah Pernah Disinkronisasi";
                        if (!data.status_akun) {
                            icon = "<i class='fas fa-clock text-danger'></i>";
                            keterangan = "Belum Pernah Disinkronisasi";
                        }
                        return "<button title='" + keterangan + "' class='btn btn-info-soft mr-2' disabled>" + icon + "</button>" +
                            "<button title='Sync With Siakad' class='btn btn-sm btn-danger btn-sync-siakad mr-2' data-nik='" + data.nik_siakad + "' data-nama='" + data.nama_lengkap + "'><span class='spinner-border spinner-border-sm mr-2' id='sync-siakad-loading-spin-" + data.nik_siakad + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-cloud-download-alt'></i></button>";
                    }
                },
                {
                    data: 'nama_lengkap',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'nidn',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                }
            ],
            paging: true,
            processing: true,
            pageLength: 10,
            ordering: false,
            lengthChange: false,
            autoWidth: false,
            sDom: 'ltipr',
            language: {
                "emptyTable": "Tidak ditemukan data"
            }
        });
        $("#btn-filter").click(function () {
            self.data.table.ajax.reload();
        });
        $("#btn-cari-data").click(function () {
            self.data.table.search($("#cari-data").val()).draw();
        });
        $("#cari-data").keyup(function () {
            if (this.value === "") {
                self.data.table.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.table.search(this.value).draw();
            }
        });
        $("#btn-sync-ulang").click(function () {
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Apakah anda yakin akan melakukan Sinkronisasi Data ? <b style="color: red">Akan Berpengaruh Ke Moodle</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            self.isSyncCanceled = false;
                            $("#progress-bar-syncron-ulang").show();
                            $("#log-syncron-ulang").show();
                            $("#btn-cancel-syncron-ulang").show();
                            $("#loading-progress").show();
                            $("#keterangan-progress").text("Mohon menunggu hingga proses sinkronisasi selesai ...");
                            $("#row-list-data").hide();
                            $.ajax({
                                url: '/super-admin/moodle/dosen/json/dosen-aktif',
                                method: 'post',
                                success: function (result) {
                                    self.next_data(result);
                                }
                            })
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red'
                    }
                }
            });
        });
        $("#btn-cari-log").click(function () {
            self.data.log_table.search($("#cari-log").val()).draw();
        });
        $("#cari-log").keyup(function () {
            if (this.value === "") {
                self.data.log_table.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.log_table.search(this.value).draw();
            }
        });
        $("#status-log").on('change', function () {
            self.data.log_table.search(this.value).draw();
        });
        $("#btn-gagal-log").click(function () {
            $("#status-log").val('gagal').trigger('change');
        });
        $("#btn-sukses-log").click(function () {
            $("#status-log").val('sukses').trigger('change');
        });
        $("#btn-tutup-log").click(function () {
            $("#log-syncron-ulang").hide();
        });
        $("#btn-cancel-syncron-ulang").click(function () {
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Proses sinkronisasi akan dihentikan, apakah anda yakin ?',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            self.isSyncCanceled = true;
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red'
                    }
                }
            })
        });
        $("#table").on('click', 'button.btn-sync-siakad', function () {
            var nik = $(this).data("nik");
            var nama = $(this).data('nama');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin melakukan sinkronisasi data atas nama <b>' + nama + '</b> dengan data siakad ?<br/><b class="text-danger">Semua data akan di update berdasarkan data siakad</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/super-admin/moodle/dosen/json/json-dosen-by-nik',
                                method: 'POST',
                                data: {
                                    nik: nik
                                },
                                beforeSend: function () {
                                    $("#sync-siakad-loading-spin-" + nik).show();
                                },
                                success: function (response) {
                                    if (response.username) {
                                        var gelar_awal = '';
                                        if (response.gelar_awal !== '')
                                            gelar_awal = response.gelar_awal.replaceAll('-', '') + ' ';
                                        var gelar_akhir = '';
                                        if (response.gelar_akhir !== '')
                                            gelar_akhir = response.gelar_akhir.replaceAll('-', '');
                                        var nama_lengkap = gelar_awal + response.nama_lengkap + gelar_akhir;
                                        $.ajax({
                                            url: '/super-admin/moodle/dosen/json/json-syncron',
                                            method: 'post',
                                            data: {
                                                'username': response.username,
                                                'password': response.password,
                                                'nidn': response.nidn,
                                                'nama_lengkap': nama_lengkap,
                                                'email': response.email,
                                                'kota_asal': response.tempat_lahir,
                                                'kd_prodi': response.kd_prodi,
                                                'nama_prodi': response.nama_prodi,
                                                'nomor_hp': response.hp_no,
                                                'alamat_rumah': response.alamat_rumah,
                                                'status_dosen': response.status_karyawan,
                                                'karyawan_id': response.karyawan_id,
                                                'nip': response.nip,
                                                'npwp': response.npwp,
                                                'bank_account': response.bank_account,
                                                'masa_pensiun': response.masa_pensiun,
                                                'status_aktif': response.status_aktif,
                                                'tgl_masuk': response.tgl_masuk,
                                                'asal_sekolah': response.asal_sekolah,
                                                'nama': response.nama_lengkap,
                                                'gelar_awal': response.gelar_awal,
                                                'gelar_akhir': response.gelar_akhir,
                                                'no_ktp': response.no_ktp,
                                                'tempat_lahir': response.tempat_lahir,
                                                'tgl_lahir': response.tgl_lahir,
                                                'kode_pos_rumah': response.kode_pos_rumah,
                                                'telepon_rumah': response.telepon_rumah,
                                                'agama': response.agama,
                                                'jenis_kelamin': response.jenis_kelamin,
                                                'status_menikah': response.status_menikah,
                                                'jenjang_pendidikan_akhir': response.jenjang_pendidikan_akhir,
                                                'nama_ibu': response.nama_ibu,
                                            },
                                            success: function (result) {
                                                if (result.is_success) {
                                                    $.alert({
                                                        title: "Informasi",
                                                        type: "green",
                                                        content: result.result
                                                    });
                                                } else {
                                                    $.alert({
                                                        title: "Peringatan",
                                                        type: "red",
                                                        content: result.result
                                                    });
                                                }
                                            },
                                            complete: function (){
                                                self.data.table.ajax.reload();
                                                $("#sync-siakad-loading-spin-" + nik).hide();
                                            }
                                        })
                                    } else {
                                        $.alert({
                                            title: "Peringatan",
                                            type: "red",
                                            content: "Data Mahasiswa Tidak Ditemukan"
                                        })
                                    }
                                }
                            })
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red'
                    }
                }
            });
        });
    },
    next_data: function (data, index = 0, progres = 0, failed = 0, inserted = 0, updated = 0) {
        var self = this;
        var n = data.length;
        var gelar_awal = '';
        if (data[index].gelar_awal !== '')
            gelar_awal = data[index].gelar_awal.replaceAll('-', '') + ' ';
        var gelar_akhir = '';
        if (data[index].gelar_akhir !== '')
            gelar_akhir = data[index].gelar_akhir.replaceAll('-', '');
        var nama_lengkap = gelar_awal + data[index].nama_lengkap + gelar_akhir;
        $.ajax({
            url: '/super-admin/moodle/dosen/json/json-syncron',
            method: 'post',
            data: {
                'username': data[index].username,
                'password': data[index].password,
                'nidn': data[index].nidn,
                'nama_lengkap': nama_lengkap,
                'email': data[index].email,
                'kota_asal': data[index].tempat_lahir,
                'kd_prodi': data[index].kd_prodi,
                'nama_prodi': data[index].nama_prodi,
                'nomor_hp': data[index].hp_no,
                'alamat_rumah': data[index].alamat_rumah,
                'status_dosen': data[index].status_karyawan,
                'karyawan_id': data[index].karyawan_id,
                'nip': data[index].nip,
                'npwp': data[index].npwp,
                'bank_account': data[index].bank_account,
                'masa_pensiun': data[index].masa_pensiun,
                'status_aktif': data[index].status_aktif,
                'tgl_masuk': data[index].tgl_masuk,
                'asal_sekolah': data[index].asal_sekolah,
                'nama': data[index].nama_lengkap,
                'gelar_awal': data[index].gelar_awal,
                'gelar_akhir': data[index].gelar_akhir,
                'no_ktp': data[index].no_ktp,
                'tempat_lahir': data[index].tempat_lahir,
                'tgl_lahir': data[index].tgl_lahir,
                'kode_pos_rumah': data[index].kode_pos_rumah,
                'telepon_rumah': data[index].telepon_rumah,
                'agama': data[index].agama,
                'jenis_kelamin': data[index].jenis_kelamin,
                'status_menikah': data[index].status_menikah,
                'jenjang_pendidikan_akhir': data[index].jenjang_pendidikan_akhir,
                'nama_ibu': data[index].nama_ibu,
            },
            success: function (result) {
                if (result.is_success) {
                    if (result.result.includes("Berhasil Insert Dosen")) {
                        self.data.log_table.row.add([
                            (index + 1),
                            data[index].nama_lengkap + " (" + data[index].nidn + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.result,
                            data[index].nama_lengkap,
                            data[index].nidn,
                            "inserted"
                        ]).draw();
                        inserted++;
                    } else {
                        self.data.log_table.row.add([
                            (index + 1),
                            data[index].nama_lengkap + " (" + data[index].nidn + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.result,
                            data[index].nama_lengkap,
                            data[index].nidn,
                            "updated"
                        ]).draw();
                        updated++;
                    }
                } else {
                    self.data.log_table.row.add([
                        (index + 1),
                        data[index].nama_lengkap + " (" + data[index].nidn + ")",
                        "<i class='fas fa-times-circle text-danger p-1'></i> " + result.result,
                        data[index].nama_lengkap,
                        data[index].nidn,
                        "gagal"
                    ]).draw();
                    failed++;
                }
                progres++;
            },
            complete: function () {
                $("#progress-bar").width((progres / n * 100).toFixed(2) + '%');
                $("#progress-text").text((progres / n * 100).toFixed(2) + '% Complete');
                $("#btn-failed-log").text("Failed : " + failed);
                $("#btn-inserted-log").text("Inserted : " + inserted);
                $("#btn-updated-log").text("Updated : " + updated);
                if (index >= (data.length - 1)) {
                    self.data.table.search("").draw();
                    $("#row-list-data").show();
                    $("#log-syncron-ulang").show();
                    $("#progress-bar-syncron-ulang").hide();
                } else {
                    if (!self.isSyncCanceled) {
                        index++;
                        self.next_data(data, index, progres, failed, inserted, updated)
                    } else {
                        $("#loading-progress").hide();
                        $("#keterangan-progress").text("Dibatalkan oleh pengguna");
                        $("#row-list-data").show();
                        $("#log-syncron-ulang").show();
                        $("#btn-cancel-syncron-ulang").hide();
                    }
                }
            }
        })
    },
};

jQuery(document).ready(function () {
    jQuery.dosen.init();
});
