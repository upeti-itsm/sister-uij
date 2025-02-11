jQuery.jadwal_siakad = {
    data: {
        isSyncCanceled: false,
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
        table: $("#table"),
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
                url: '/super-admin/moodle/json/daftar-jadwal-siakad',
                type: 'post',
                data: function (data) {
                    data.tahun_akademik = $("#tahun_akademik").val();
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
                    width: "40%",
                    render: function (data) {
                        return "<b>" + data.fullname + " (" + data.shortname + ")</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                    render: function (data) {
                        var nama_asisten = data.nama_asisten;
                        if (!data.nik_asisten)
                            nama_asisten = "-- Tidak Ada --";
                        return "Dosen Pengampu : <b>" + data.nama_pengajar + "</b><br/>" +
                            "Asisten : <b>" + nama_asisten + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        var icon = "<i class='fas fa-check-square text-success'></i>";
                        var keterangan = "Sudah Pernah Disingkronisasi";
                        if (!data.is_syncronized) {
                            icon = "<i class='fas fa-clock text-danger'></i>";
                            keterangan = "Belum Pernah Disingkronisasi";
                        }
                        return "<button title='" + keterangan + "' class='btn btn-info-soft mr-2'>" + icon + "</button>" +
                            "<button title='Sync With Siakad' class='btn btn-sm btn-danger btn-sync-siakad mr-2' data-id='" + data.jadwal_id + "'><span class='spinner-border spinner-border-sm mr-2' id='sync-siakad-loading-spin-" + data.jadwal_id.replaceAll("@", "_") + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-cloud-download-alt'></i></button>";
                    }
                },
                {
                    data: 'fullname',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'nama_pengajar',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'nama_asisten',
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
            $("#modal-sync-jadwal").modal('show');
        });
        $("#modal-btn-sync").click(function () {
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Apakah anda yakin akan melakukan Sinkronisasi Data ? <b style="color: red">Tidak Akan Berpengaruh Ke Moodle Sebelum Sinkronisasi Course Dijalankan</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $("#modal-sync-jadwal").modal('hide');
                            self.isSyncCanceled = false;
                            $("#progress-bar-syncron-ulang").show();
                            $("#log-syncron-ulang").show();
                            $("#btn-cancel-syncron-ulang").show();
                            $("#loading-progress").show();
                            $("#keterangan-progress").text("Mohon menunggu hingga proses sinkronisasi selesai ...");
                            $("#row-list-data").hide();
                            $.ajax({
                                url: '/super-admin/moodle/json/json-jadwal-siakad',
                                method: 'post',
                                data: {
                                    tahun_akademik: $("#tahun_akademik_sync").val()
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
            var id = $(this).data("id");
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin melakukan sinkronisasi jadwal dengan data siakad ?<br/><b class="text-danger">Semua data akan di update berdasarkan data siakad</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/super-admin/moodle/json/json-jadwal-siakad-by-id',
                                method: 'POST',
                                data: {
                                    id: id
                                },
                                beforeSend: function () {
                                    $("#sync-siakad-loading-spin-" + id.replaceAll("@", "_")).show();
                                },
                                success: function (response) {
                                    if (response.jadwal_kuliah_id) {
                                        $.ajax({
                                            url: '/super-admin/moodle/json/json-syncron',
                                            method: 'post',
                                            data: {
                                                'id_jadwal': response.jadwal_kuliah_id,
                                                'nama_matkul': response.nama_mata_kuliah,
                                                'kelas_id': response.kelas_id,
                                                'prodi': response.prodi,
                                                'tahun_akademik': response.tahun_akademik,
                                                'nik': response.nik,
                                                'nik_asisten': response.nik_asisten,
                                                'nama_pengajar': response.nama_lengkap,
                                                'nama_asisten': response.nama_asisten,
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
                                                $("#sync-siakad-loading-spin-" + id.replaceAll("@", "_")).hide();
                                            }
                                        })
                                    } else {
                                        $.alert({
                                            title: "Peringatan",
                                            type: "red",
                                            content: "Data Jadwal Tidak Ditemukan"
                                        })
                                        $("#sync-siakad-loading-spin-" + id.replaceAll("@", "_")).hide();
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
        $("#btn-move-to-course").click(function () {
            $("#modal-move-to-course").modal('show');
        });
        $("#modal-btn-move-to-course").click(function (){
            $.ajax({
                url: '/super-admin/moodle/json/json-move-to-course',
                method: 'post',
                data: {
                    tahun_akademik: $("#tahun_akademik_move").val()
                },
                beforeSend: function () {
                    $("#move-to-course-loading-spin").show();
                },
                success: function (response) {
                    if (response.is_success) {
                        $.alert({
                            title: "Informasi",
                            type: "green",
                            content: response.result
                        });
                    } else {
                        $.alert({
                            title: "Peringatan",
                            type: "red",
                            content: response.result
                        });
                    }
                },
                complete: function () {
                    $("#move-to-course-loading-spin").hide();
                    $("#modal-move-to-course").modal("hide");
                }
            })
        });
    },
    next_data: function (data, index = 0, progres = 0, failed = 0, inserted = 0, updated = 0) {
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/super-admin/moodle/json/json-syncron',
            method: 'post',
            data: {
                'id_jadwal': data[index].jadwal_kuliah_id,
                'nama_matkul': data[index].nama_mata_kuliah,
                'kelas_id': data[index].kelas_id,
                'prodi': data[index].prodi,
                'tahun_akademik': data[index].tahun_akademik,
                'nik': data[index].nik,
                'nik_asisten': data[index].nik_asisten,
                'nama_pengajar': data[index].nama_lengkap,
                'nama_asisten': data[index].nama_asisten,
            },
            success: function (result) {
                if (result.is_success) {
                    if (result.result.includes("Berhasil Insert Course")) {
                        self.data.log_table.row.add([
                            (index + 1),
                            data[index].nama_mata_kuliah + " (" + data[index].kelas_id + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.result,
                            data[index].nama_lengkap,
                            data[index].nama_asisten,
                            "inserted"
                        ]).draw();
                        inserted++;
                    } else {
                        self.data.log_table.row.add([
                            (index + 1),
                            data[index].nama_mata_kuliah + " (" + data[index].kelas_id + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.result,
                            data[index].nama_lengkap,
                            data[index].nama_asisten,
                            "updated"
                        ]).draw();
                        updated++;
                    }
                } else {
                    self.data.log_table.row.add([
                        (index + 1),
                        data[index].nama_mata_kuliah + " (" + data[index].kelas_id + ")",
                        "<i class='fas fa-times-circle text-danger p-1'></i> " + result.result,
                        data[index].nama_lengkap,
                        data[index].nama_asisten,
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
    jQuery.jadwal_siakad.init();
});
