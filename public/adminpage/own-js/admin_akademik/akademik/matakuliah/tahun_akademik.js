jQuery.tahun_akademik = {
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
                url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-tahun-akademik-siakad/json',
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
                        return "<b>" + data.nama_tahun_akademik + " (" + data.tahun_akademik + ")</b><br/>" +
                            "KRS: <br/><small>" + data.tgl_mulai_krs + ' s/d ' + data.tgl_selesai_krs +"</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "50%",
                    render: function (data) {
                        return "" +
                        "Perkuliahan: <br/><small>" + data.tgl_awal_perkuliahan + ' s/d ' + data.tgl_akhir_perkuliahan +"</small><br/>" +
                        "Input Nilai: <br/><small>" + data.tgl_mulai_input_nilai + ' s/d ' + data.tgl_selesai_input_nilai +"</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        var html = "<button class='btn btn-danger-soft btn-block mb-2'>Tidak Memiliki Akses</button>";
                        if ($("#hak_akses").val() === "1") {
                            html = "<button class='btn btn-success btn-block btn-sync-siakad mr-2' title='Sinkron data dengan siakad' data-tahun_akademik='" + data.tahun_akademik + "' data-nama_tahun_akademik='" + data.nama_tahun_akademik + "'><span class='spinner-border spinner-border-sm mr-2' id='sync-siakad-loading-spin-" + data.tahun_akademik + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-sync'></i></button>";
                        }
                        return html;
                    }
                },
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
        $("#btn-sync-ulang").click(function () {
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Apakah anda yakin akan melakukan Sinkronisasi Data ?',
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
                            $.ajax({
                                url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-tahun-akademik-siakad/json-tahun-akademik-siakad',
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
        $("#btn-failed-log").click(function () {
            $("#status-log").val('failed').trigger('change');
        });
        $("#btn-inserted-log").click(function () {
            $("#status-log").val('inserted').trigger('change');
        });
        $("#btn-updated-log").click(function () {
            $("#status-log").val('updated').trigger('change');
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
            var tahun_akademik = $(this).data("tahun_akademik");
            var nama_tahun_akademik = $(this).data("nama_tahun_akademik");
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin melakukan sinkronisasi data TA <b>' + nama_tahun_akademik + '</b> dengan data siakad ?<br/><b class="text-danger">Semua data akan di update berdasarkan data siakad</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/json-by-tahun-akademik',
                                method: 'POST',
                                data: {
                                    tahun_akademik: tahun_akademik
                                },
                                beforeSend: function () {
                                    $("#sync-siakad-loading-spin-" + tahun_akademik).show();
                                },
                                success: function (response) {
                                    if (response.tahun_akademik) {
                                        $.ajax({
                                            url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-tahun-akademik-siakad/synchron',
                                            method: 'post',
                                            data: {
                                                'fd_id_smt': response.fd_id_smt,
                                                'nama_tahun_akademik': response.nama_tahun_akademik,
                                                'status_aktif': response.status_aktif,
                                                'tanggal_awal_perkuliahan': response.tanggal_awal_perkuliahan,
                                                'tanggal_akhir_perkuliahan': response.tanggal_akhir_perkuliahan,
                                                'tahun_akademik': response.tahun_akademik,
                                                'tgl_mulai_krs': response.tgl_mulai_krs,
                                                'tgl_selesai_krs': response.tgl_selesai_krs,
                                                'tgl_mulai_input_nilai': response.tgl_mulai_input_nilai,
                                                'tgl_selesai_input_nilai': response.tgl_selesai_input_nilai
                                            },
                                            success: function (result) {
                                                if (result.status) {
                                                    $.alert({
                                                        title: "Informasi",
                                                        type: "green",
                                                        content: result.keterangan
                                                    });
                                                } else {
                                                    $.alert({
                                                        title: "Peringatan",
                                                        type: "red",
                                                        content: result.keterangan
                                                    });
                                                }
                                            },
                                            complete: function () {
                                                self.data.table.ajax.reload();
                                                $("#sync-siakad-loading-spin-" + tahun_akademik).hide();
                                            }
                                        })
                                    } else {
                                        $.alert({
                                            title: "Peringatan",
                                            type: "red",
                                            content: "Data Jadwal Tidak Ditemukan"
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
            url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-tahun-akademik-siakad/synchron',
            method: 'post',
            data: {
                'fd_id_smt': data[index].fd_id_smt,
                'nama_tahun_akademik': data[index].nama_tahun_akademik,
                'status_aktif': data[index].status_aktif,
                'tanggal_awal_perkuliahan': data[index].tanggal_awal_perkuliahan,
                'tanggal_akhir_perkuliahan': data[index].tanggal_akhir_perkuliahan,
                'tahun_akademik': data[index].tahun_akademik,
                'tgl_mulai_krs': data[index].tgl_mulai_krs,
                'tgl_selesai_krs': data[index].tgl_selesai_krs,
                'tgl_mulai_input_nilai': data[index].tgl_mulai_input_nilai,
                'tgl_selesai_input_nilai': data[index].tgl_selesai_input_nilai
            },
            success: function (result) {
                if (result.status) {
                    if (result.jenis_aksi === 1) {
                        self.data.log_table.row.add([
                            (index + 1),
                            data[index].nama_tahun_akademik + " (" + data[index].tahun_akademik + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.keterangan,
                            data[index].nama_tahun_akademik,
                            "inserted"
                        ]).draw();
                        inserted++;
                    } else {
                        self.data.log_table.row.add([
                            (index + 1),
                            data[index].nama_tahun_akademik + " (" + data[index].tahun_akademik + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.keterangan,
                            data[index].nama_tahun_akademik,
                            "updated"
                        ]).draw();
                        updated++;
                    }
                } else {
                    self.data.log_table.row.add([
                        (index + 1),
                        data[index].nama_tahun_akademik + " (" + data[index].tahun_akademik + ")",
                        "<i class='fas fa-check-circle text-danger p-1'></i> " + result.keterangan,
                        data[index].nama_tahun_akademik,
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
    }
    ,
};

jQuery(document).ready(function () {
    jQuery.tahun_akademik.init();
});
