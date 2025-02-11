jQuery.jadwal_mahasiswa = {
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
                url: '/super-admin/moodle/jadwal-mahasiswa/json/daftar-jadwal-mahasiswa',
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
                    width: "50%",
                    render: function (data) {
                        return "<b>" + data.nama_lengkap + "</b><br/>" +
                            "<small>NIM : " + data.npk + " | Angkatan : " + data.angkatan + "<br/>" +
                            "Jadwal Tahun Akademik : " + data.tahun_akademik + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                    render: function (data) {
                        var nama_asisten = "-- Tidak Ada --";
                        if (data.nama_asisten)
                            nama_asisten = data.nama_asisten;
                        return "<b>" + data.nama_mata_kuliah + " " + data.kelas_id + "</b><br/>" +
                            "<small>Dosen Pengampu : " + data.nama_dosen + "<br/>" +
                            "Asisten : " + nama_asisten + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "10%",
                    render: function (data) {
                        return "<button title='Delete' class='btn btn-sm btn-danger btn-delete mr-2' data-id='" + data.jadwal_kuliah_id + "' data-nim='" + data.npk + "'><span class='spinner-border spinner-border-sm mr-2' id='delete-loading-spin-" + data.jadwal_kuliah_id.replaceAll("@", "_") + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash-alt'></i></button>";
                    }
                },
                {
                    data: 'nama_mata_kuliah',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'nama_dosen',
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
            $("#modal-sync-jadwal").modal('show');
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
                            $("#modal-sync-jadwal").modal('hide');
                            self.isSyncCanceled = false;
                            $("#progress-bar-syncron-ulang").show();

                            $.ajax({
                                url: '/super-admin/moodle/jadwal-mahasiswa/json/json-krs-mahasiswa',
                                method: 'post',
                                data: {
                                    tahun_akademik: $("#tahun_akademik_sync").val()
                                },
                                success: function (result) {
                                    if (result.length > 0) {
                                        $("#log-syncron-ulang").show();
                                        $("#btn-cancel-syncron-ulang").show();
                                        $("#loading-progress").show();
                                        $("#keterangan-progress").text("Mohon menunggu hingga proses sinkronisasi selesai ...");
                                        $("#row-list-data").hide();
                                        self.next_data(result);
                                    } else {
                                        $.alert({
                                            title: "Peringatan",
                                            type: "red",
                                            content: "Tidak Ditemukan Jadwal Mahasiswa"
                                        })
                                        $("#row-list-data").hide();
                                        $("#log-syncron-ulang").hide();
                                        $("#progress-bar-syncron-ulang").hide();
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
        $("#btn-sync-ulang-by-nim").click(function () {
            $("#modal-sync-jadwal-by-nim").modal('show');
        });
        $("#modal-btn-sync-by-nim").click(function () {
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
                            $("#modal-sync-jadwal-by-nim").modal('hide');
                            self.isSyncCanceled = false;
                            $("#progress-bar-syncron-ulang").show();
                            $.ajax({
                                url: '/super-admin/moodle/jadwal-mahasiswa/json/json-krs-mahasiswa-by-nim',
                                method: 'post',
                                data: {
                                    nim: $("#nim_sync_by_nim").val(),
                                    tahun_akademik: $("#tahun_akademik_sync_by_nim").val()
                                },
                                success: function (result) {
                                    if (result.length > 0) {
                                        $("#log-syncron-ulang").show();
                                        $("#btn-cancel-syncron-ulang").show();
                                        $("#loading-progress").show();
                                        $("#keterangan-progress").text("Mohon menunggu hingga proses sinkronisasi selesai ...");
                                        $("#row-list-data").hide();
                                        self.next_data(result);
                                    } else {
                                        $.alert({
                                            title: "Peringatan",
                                            type: "red",
                                            content: "Tidak Ditemukan Jadwal Mahasiswa"
                                        })
                                        $("#row-list-data").hide();
                                        $("#log-syncron-ulang").hide();
                                        $("#progress-bar-syncron-ulang").hide();
                                    }
                                },
                                error: function (response) {
                                    $.alert({
                                        title: "Peringatan",
                                        type: "red",
                                        content: response.result
                                    });
                                    $("#row-list-data").hide();
                                    $("#log-syncron-ulang").hide();
                                    $("#progress-bar-syncron-ulang").hide();
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

        $("#table").on('click', 'button.btn-delete', function () {
            var id = $(this).data("id");
            var nim = $(this).data("nim");
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah Anda Yakin ?',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/super-admin/moodle/jadwal-mahasiswa/delete-jadwal-mahasiswa',
                                method: 'POST',
                                data: {
                                    id: id,
                                    nim: nim
                                },
                                beforeSend: function () {
                                    $("#delete-loading-spin-" + id.replaceAll("@", "_")).show();
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
                                    $("#delete-loading-spin-" + id.replaceAll("@", "_")).hide();
                                    self.data.table.ajax.reload();
                                },
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
            url: '/super-admin/moodle/jadwal-mahasiswa/json/json-syncron',
            method: 'post',
            data: {
                'id_jadwal': data[index].jadwal_kuliah_id,
                'nim': data[index].NPK,
                'nama_mata_kuliah': data[index].nama_mata_kuliah,
                'kelas_id': data[index].kelas_id,
                'nama_dosen': data[index].nama_dosen,
                'tahun_akademik': data[index].tahun_akademik
            },
            success: function (result) {
                if (result.is_success) {
                    if (result.result.includes("Berhasil Insert Jadwal Mahasiswa")) {
                        self.data.log_table.row.add([
                            (index + 1),
                            data[index].nama_mata_kuliah + " (" + data[index].kelas_id + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.result,
                            data[index].nama_mata_kuliah,
                            data[index].nama_dosen,
                            "inserted"
                        ]).draw();
                        inserted++;
                    } else {
                        self.data.log_table.row.add([
                            (index + 1),
                            data[index].nama_mata_kuliah + " (" + data[index].kelas_id + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.result,
                            data[index].nama_mata_kuliah,
                            data[index].nama_dosen,
                            "updated"
                        ]).draw();
                        updated++;
                    }
                } else {
                    self.data.log_table.row.add([
                        (index + 1),
                        data[index].nama_mata_kuliah + " (" + data[index].kelas_id + ")",
                        "<i class='fas fa-times-circle text-danger p-1'></i> " + result.result,
                        data[index].nama_mata_kuliah,
                        data[index].nama_dosen,
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
    jQuery.jadwal_mahasiswa.init();
});
