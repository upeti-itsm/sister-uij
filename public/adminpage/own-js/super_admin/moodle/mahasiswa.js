jQuery.mahasiswa = {
    data: {
        isSyncCanceled: false,
        isSyncDataCenterCanceled: false,
        log_table: $("#log-table").DataTable({
            scrollY: '300px',
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
        log_table_data_center: $("#log-table-data-center").DataTable({
            scrollY: '300px',
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
        table: $("#table"),
        table_data_center: $("#table-data-center"),
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
        // Elearning
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/super-admin/moodle/mahasiswa/json/daftar-mahasiswa',
                type: 'post',
                data: function (data) {
                    data.angkatan = $("#angkatan").val();
                }
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
                        return "<b>" + data.nama_lengkap + " (" + data.npk + ")</b><br/>" +
                            "<small>Email : " + data.email + " | Password : " + data.password + "</small><br/>" +
                            "<small>Jenis Pendanaan : " + data.jenis_pendanaan + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return "<b>" + data.nama_prodi + " (" + data.angkatan + ")</b><br/>" +
                            "<small>Nomor HP : " + data.no_hp + " | Kota Rumah : " + data.kota + "</small><br/>" +
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
                            "<button title='Sync With Siakad' class='btn btn-sm btn-danger btn-sync-siakad mr-2' data-npk='" + data.npk + "' data-nama='" + data.nama_lengkap + "'><span class='spinner-border spinner-border-sm mr-2' id='sync-siakad-loading-spin-" + data.npk + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-cloud-download-alt'></i></button>";
                    }
                },
                {
                    data: 'nama_lengkap',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'npk',
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
            $("#modal-sync-mahasiswa").modal('show');
        });
        $("#modal-btn-sync").click(function () {
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
                            $("#modal-sync-mahasiswa").modal('hide');
                            self.isSyncCanceled = false;
                            $("#progress-bar-syncron-ulang").show();
                            $("#log-syncron-ulang").show();
                            $("#btn-cancel-syncron-ulang").show();
                            $("#loading-progress").show();
                            $("#keterangan-progress").text("Mohon menunggu hingga proses sinkronisasi selesai ...");
                            $("#row-list-data").hide();
                            $.ajax({
                                url: '/super-admin/moodle/mahasiswa/json/mahasiswa-aktif',
                                method: 'post',
                                data: {
                                    angkatan: $("#angkatan_sync").val()
                                },
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
        $("#btn-failed-log").click(function () {
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
            var npk = $(this).data("npk");
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
                                url: '/super-admin/moodle/mahasiswa/json/json-mahasiswa-by-npk',
                                method: 'POST',
                                data: {
                                    npk: npk
                                },
                                beforeSend: function () {
                                    $("#sync-siakad-loading-spin-" + npk).show();
                                },
                                success: function (response) {
                                    if (response.NPK) {
                                        $.ajax({
                                            url: '/super-admin/moodle/mahasiswa/json/json-syncron',
                                            method: 'post',
                                            data: {
                                                'npk': response.NPK,
                                                'nama': response.nama_lengkap,
                                                'tahun_akademik': response.angkatan,
                                                'password': response.password,
                                                'kd_prodi': response.program_id,
                                                'alamat_rumah': response.alamat_rumah,
                                                'hp': response.hp,
                                                'kota': response.kota_rumah,
                                                'email': response.email,
                                                'nama_prodi': response.nama_program,
                                                'jenis_pendanaan': response.jenis_pendanaan,
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
                                            complete: function () {
                                                self.data.table.ajax.reload();
                                                $("#sync-siakad-loading-spin-" + npk).hide();
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

        // Data Center
        self.data.table_data_center = $("#table-data-center").DataTable({
            serverSide: true,
            ajax: {
                url: '/super-admin/moodle/mahasiswa/json/daftar-mahasiswa/data-center',
                type: 'post',
                data: function (data) {
                    data.angkatan = $("#angkatan-data-center").val();
                }
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
                        return "<b>" + data.nama_lengkap + " (" + data.npk + ")</b><br/>" +
                            "<small>Email : " + data.email + " | Password : " + data.password + "</small><br/>" +
                            "<small>Jenis Pendanaan : " + data.jenis_pendanaan + "</small><br/>" +
                            "<small>Status Siakad : " + data.status_siakad + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return "<b>" + data.nama_prodi + " (" + data.angkatan + ")</b><br/>" +
                            "<small>Nomor HP : " + data.no_hp + " | Kota Rumah : " + data.kota + "</small><br/>" +
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
                            "<button title='Sync With Siakad' class='btn btn-sm btn-danger btn-sync-siakad mr-2' data-npk='" + data.npk + "' data-nama='" + data.nama_lengkap + "'><span class='spinner-border spinner-border-sm mr-2' id='sync-siakad-loading-spin-" + data.npk + "-data-center' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-cloud-download-alt'></i></button>";
                    }
                },
                {
                    data: 'nama_lengkap',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'npk',
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
        $("#btn-filter-data-center").click(function () {
            self.data.table_data_center.ajax.reload();
        });
        $("#btn-cari-data-data-center").click(function () {
            self.data.table_data_center.search($("#cari-data-data-center").val()).draw();
        });
        $("#cari-data-data-center").keyup(function () {
            if (this.value === "") {
                self.data.table_data_center.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.table.search(this.value).draw();
            }
        });
        $("#btn-sync-ulang-data-center").click(function () {
            $("#modal-sync-mahasiswa-data-center").modal('show');
        });
        $("#modal-btn-sync-data-center").click(function () {
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
                            $("#modal-sync-mahasiswa-data-center").modal('hide');
                            self.isSyncDataCenterCanceled = false;
                            $("#progress-bar-syncron-ulang-data-center").show();
                            $("#log-syncron-ulang-data-center").show();
                            $("#btn-cancel-syncron-ulang-data-center").show();
                            $("#loading-progress-data-center").show();
                            $("#keterangan-progress-data-center").text("Mohon menunggu hingga proses sinkronisasi selesai ...");
                            $.ajax({
                                url: '/super-admin/moodle/mahasiswa/json/mahasiswa/data-center',
                                method: 'post',
                                data: {
                                    angkatan: $("#angkatan_sync-data-center").val()
                                },
                                success: function (result) {
                                    self.next_data_center(result);
                                    // console.log(result)
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
        $("#btn-cari-log-data-center").click(function () {
            self.data.log_table_data_center.search($("#cari-log-data-center").val()).draw();
        });
        $("#cari-log-data-center").keyup(function () {
            if (this.value === "") {
                self.data.log_table_data_center.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.log_table_data_center.search(this.value).draw();
            }
        });
        $("#status-log-data-center").on('change', function () {
            self.data.log_table_data_center.search(this.value).draw();
        });
        $("#btn-failed-log-data-center").click(function () {
            $("#status-log-data-center").val('failed').trigger('change');
        });
        $("#btn-inserted-log-data-center").click(function () {
            $("#status-log-data-center").val('inserted').trigger('change');
        });
        $("#btn-updated-log-data-center").click(function () {
            $("#status-log-data-center").val('updated').trigger('change');
        });
        $("#btn-tutup-log-data-center").click(function () {
            $("#log-syncron-ulang-data-center").hide();
        });
        $("#btn-cancel-syncron-ulang-data-center").click(function () {
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
                            self.isSyncDataCenterCanceled = true;
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red'
                    }
                }
            })
        });
        $("#table-data-center").on('click', 'button.btn-sync-siakad', function () {
            var npk = $(this).data("npk");
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
                                url: '/super-admin/moodle/mahasiswa/json/json-mahasiswa-by-npk/data-center',
                                method: 'POST',
                                data: {
                                    npk: npk
                                },
                                beforeSend: function () {
                                    $("#sync-siakad-loading-spin-" + npk + "-data-center").show();
                                },
                                success: function (response) {
                                    if (response.NPK) {
                                        $.ajax({
                                            url: '/super-admin/moodle/mahasiswa/json/json-syncron/data-center',
                                            method: 'post',
                                            data: {
                                                'npk': response.NPK,
                                                'inf_nisn': response.nisn,
                                                'dosen_wali': response.dosen_wali,
                                                'tgl_lulus_sma': response.tgl_lulus_sma,
                                                'inf_jurusan_sma': response.inf_jurusan_sma,
                                                'sekolah_asal': response.sekolah_asal,
                                                'inf_tgl_lulus': response.inf_tgl_lulus,
                                                'inf_nomor_ijazah': response.inf_nomor_ijazah,
                                                'inf_nomor_transkrip': response.inf_nomor_transkrip,
                                                'status_aktif': response.status_aktif,
                                                'program_id': response.program_id,
                                                'konsentrasi_id': response.konsentrasi_id,
                                                'nama_wali': response.nama_wali,
                                                'pekerjaan_wali': response.pekerjaan_wali,
                                                'jenis_mahasiswa': response.jenis_mahasiswa,
                                                'jenis_pendanaan': response.jenis_pendanaan,
                                                'nomor_seri_ijazah': response.no_seri_ijazah,
                                                'nama_lengkap': response.nama_lengkap,
                                                'tempat_lahir': response.tempat_lahir,
                                                'tanggal_lahir': response.tgl_lahir,
                                                'jenis_kelamin': response.jenis_kelamin,
                                                'agama_id': response.agama_id,
                                                'status_menikah': response.status_menikah,
                                                'hp': response.hp,
                                                'telepon_rumah': response.telepon_rumah,
                                                'alamat_rumah': response.alamat_rumah,
                                                'kode_pos_rumah': response.kode_pos_rumah,
                                                'inf_warga_negara': response.kewarganegaraan,
                                                'email': response.email,
                                                'nik': response.nik,
                                                'rt': response.rt,
                                                'rw': response.rw,
                                                'ds_kel': response.ds_kel,
                                                'nama_ibu': response.nama_ibu,
                                                'password': response.password,
                                                'angkatan': response.angkatan,
                                                'jenis_kelas': response.jenis_kelas,
                                                'judul_skripsi': response.judul_skripsi,
                                                'ipk': response.ipk,
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
                                            complete: function () {
                                                self.data.table_data_center.ajax.reload();
                                                $("#sync-siakad-loading-spin-" + npk + "-data-center").hide();
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
        $.ajax({
            url: '/super-admin/moodle/mahasiswa/json/json-syncron',
            method: 'post',
            data: {
                'npk': data[index].NPK,
                'nama': data[index].nama_lengkap,
                'tahun_akademik': data[index].angkatan,
                'password': data[index].password,
                'kd_prodi': data[index].program_id,
                'alamat_rumah': data[index].alamat_rumah,
                'hp': data[index].hp,
                'kota': data[index].kota_rumah,
                'email': data[index].email,
                'nama_prodi': data[index].nama_program,
                'jenis_pendanaan': data[index].jenis_pendanaan,
            },
            success: function (result) {
                if (result.is_success) {
                    if (result.result.includes("Berhasil Insert Mahasiswa")) {
                        self.data.log_table.row.add([
                            (index + 1),
                            data[index].nama_lengkap + " (" + data[index].NPK + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.result,
                            data[index].nama_lengkap,
                            data[index].NPK,
                            "inserted"
                        ]).draw();
                        inserted++;
                    } else {
                        self.data.log_table.row.add([
                            (index + 1),
                            data[index].nama_lengkap + " (" + data[index].NPK + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.result,
                            data[index].nama_lengkap,
                            data[index].NPK,
                            "updated"
                        ]).draw();
                        updated++;
                    }
                } else {
                    self.data.log_table.row.add([
                        (index + 1),
                        data[index].nama_lengkap + " (" + data[index].NPK + ")",
                        "<i class='fas fa-times-circle text-danger p-1'></i> " + result.result,
                        data[index].nama_lengkap,
                        data[index].NPK,
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
                    $("#log-syncron-ulang").show();
                    $("#progress-bar-syncron-ulang").hide();
                } else {
                    if (!self.isSyncCanceled) {
                        index++;
                        self.next_data(data, index, progres, failed, inserted, updated)
                    } else {
                        $("#loading-progress").hide();
                        $("#keterangan-progress").text("Dibatalkan oleh pengguna");
                        $("#log-syncron-ulang").show();
                        $("#btn-cancel-syncron-ulang").hide();
                    }
                }
            }
        })
    },
    next_data_center: function (data, index = 0, progres = 0, failed = 0, inserted = 0, updated = 0) {
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/super-admin/moodle/mahasiswa/json/json-syncron/data-center',
            method: 'post',
            data: {
                'npk': data[index].NPK,
                'inf_nisn': data[index].nisn,
                'dosen_wali': data[index].dosen_wali,
                'tgl_lulus_sma': data[index].tgl_lulus_sma,
                'inf_jurusan_sma': data[index].inf_jurusan_sma,
                'sekolah_asal': data[index].sekolah_asal,
                'inf_tgl_lulus': data[index].inf_tgl_lulus,
                'inf_nomor_ijazah': data[index].inf_nomor_ijazah,
                'inf_nomor_transkrip': data[index].inf_nomor_transkrip,
                'status_aktif': data[index].status_aktif,
                'program_id': data[index].program_id,
                'konsentrasi_id': data[index].konsentrasi_id,
                'nama_wali': data[index].nama_wali,
                'pekerjaan_wali': data[index].pekerjaan_wali,
                'jenis_mahasiswa': data[index].jenis_mahasiswa,
                'jenis_pendanaan': data[index].jenis_pendanaan,
                'nomor_seri_ijazah': data[index].no_seri_ijazah,
                'nama_lengkap': data[index].nama_lengkap,
                'tempat_lahir': data[index].tempat_lahir,
                'tanggal_lahir': data[index].tgl_lahir,
                'jenis_kelamin': data[index].jenis_kelamin,
                'agama_id': data[index].agama_id,
                'status_menikah': data[index].status_menikah,
                'hp': data[index].hp,
                'telepon_rumah': data[index].telepon_rumah,
                'alamat_rumah': data[index].alamat_rumah,
                'kode_pos_rumah': data[index].kode_pos_rumah,
                'inf_warga_negara': data[index].kewarganegaraan,
                'email': data[index].email,
                'nik': data[index].nik,
                'rt': data[index].rt,
                'rw': data[index].rw,
                'ds_kel': data[index].ds_kel,
                'nama_ibu': data[index].nama_ibu,
                'password': data[index].password,
                'angkatan': data[index].angkatan,
                'jenis_kelas': data[index].jenis_kelas,
                'judul_skripsi': data[index].judul_skripsi,
                'ipk': data[index].ipk,
            },
            success: function (result) {
                if (result.is_success) {
                    if (result.result.includes("Berhasil Insert Mahasiswa")) {
                        self.data.log_table_data_center.row.add([
                            (index + 1),
                            data[index].nama_lengkap + " (" + data[index].NPK + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.result,
                            data[index].nama_lengkap,
                            data[index].NPK,
                            "inserted"
                        ]).draw();
                        inserted++;
                    } else {
                        self.data.log_table_data_center.row.add([
                            (index + 1),
                            data[index].nama_lengkap + " (" + data[index].NPK + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.result,
                            data[index].nama_lengkap,
                            data[index].NPK,
                            "updated"
                        ]).draw();
                        updated++;
                    }
                } else {
                    self.data.log_table_data_center.row.add([
                        (index + 1),
                        data[index].nama_lengkap + " (" + data[index].NPK + ")",
                        "<i class='fas fa-times-circle text-danger p-1'></i> " + result.result,
                        data[index].nama_lengkap,
                        data[index].NPK,
                        "gagal"
                    ]).draw();
                    failed++;
                }
                progres++;
            },
            complete: function () {
                $("#progress-bar-data-center").width((progres / n * 100).toFixed(2) + '%');
                $("#progress-text-data-center").text((progres / n * 100).toFixed(2) + '% Complete');
                $("#btn-failed-log-data-center").text("Failed : " + failed);
                $("#btn-inserted-log-data-center").text("Inserted : " + inserted);
                $("#btn-updated-log-data-center").text("Updated : " + updated);
                if (index >= (data.length - 1)) {
                    self.data.table_data_center.search("").draw();
                    $("#log-syncron-ulang-data-center").show();
                    $("#progress-bar-syncron-ulang-data-center").hide();
                } else {
                    if (!self.isSyncDataCenterCanceled) {
                        index++;
                        self.next_data_center(data, index, progres, failed, inserted, updated)
                    } else {
                        $("#loading-progress-data-center").hide();
                        $("#keterangan-progress-data-center").text("Dibatalkan oleh pengguna");
                        $("#log-syncron-ulang-data-center").show();
                        $("#btn-cancel-syncron-ulang-data-center").hide();
                    }
                }
            }
        })
    },
};

jQuery(document).ready(function () {
    jQuery.mahasiswa.init();
});
