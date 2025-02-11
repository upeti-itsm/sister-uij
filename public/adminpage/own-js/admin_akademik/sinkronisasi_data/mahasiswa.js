jQuery.mahasiswa = {
    data: {
        isSyncCanceled: false
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
        log_table = $("#log-table").DataTable({
            scrollY: '300px',
            columns: [
                {width: "5%", sClass: 'text-center', searchable: false},
                {width: "80%", searchable: false},
                {width: "15%", sClass: 'text-center', searchable: false},
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
        });
        table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/adm-akadmik/sinkronisasi-data/mahasiswa/json/get-mahasiswa',
                type: 'post',
                data: function (d) {
                    d.prodi = $("#prodi").val();
                    d.semester = $("#semester").val();
                }
            },
            scrollY: '300px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'nomor',
                    searchable: false,
                    sClass: 'text-center'
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    render: function (data) {
                        return "<b>" + data.nama_mahasiswa + " (" + data.nim + ")</b><br/>" +
                            "<small>Prodi : " + data.nama_program_studi + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    render: function (data) {
                        return "<small>Jenjang Didik: " + data.jenjang_didik + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    render: function (data) {
                        return "<button title='Cek Data Feeder' class='btn btn-sm btn-info btn-check-feeder mr-2' data-id='" + data.id_mahasiswa + "'><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_mahasiswa + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-cogs'></i></button>";
                    }
                },
                {
                    data: 'nama_mahasiswa',
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
            table.ajax.reload();
        });
        $("#btn-cari-data").click(function () {
            table.search($("#cari-data").val()).draw();
        });
        $("#cari-data").keyup(function () {
            if (this.value === "") {
                table.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                table.search(this.value).draw();
            }
        });

        $("#table").on('click', 'button.btn-check-feeder', function () {
            var id = $(this).data('id');
            var kd = $(this).data('kd_prodi');
            $.ajax({
                url: "/adm-akadmik/sinkronisasi-data/program-studi/json/perbandingan-data-feeder",
                method: 'POST',
                data: {
                    id_program_studi: id,
                    kd_prodi: kd
                },
                beforeSend: function () {
                    $("#detail-loading-spin-" + id).show();
                },
                success: function (result) {
                    $("#modal-check-id_program_studi").val(result.sipadu[0].id_program_studi)
                    self.set_data_sipadu(result.sipadu);
                    self.set_data_feeder(result.feeder);
                    self.set_perbandingan(result);
                },
                complete: function () {
                    $("#modal-check-feeder").modal("show");
                    $("#detail-loading-spin-" + id).hide();
                }
            })
        });

        $('#modal-check-feeder').on('hidden.bs.modal', function () {
            $("#modal-check-id_program_studi").val();
        });

        $("#modal-check-btn-sync").click(function () {
            $("#progress-bar-syncron-ulang").show();
            $("#row-list-mahasiswa").hide();
        });
        $("#btn-sync-ulang").click(function () {
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Apakah anda yakin akan melakukan Sinkronisasi Data ? <b style="color: red">Semua data Prodi akan diseuaikan dengan Feeder</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            self.isSyncCanceled = false;
                            $.ajax({
                                url: '/adm-akadmik/sinkronisasi-data/mahasiswa/json/get-mahasiswa-feeder/',
                                method: 'get',
                                success: function (result) {
                                    log_table.clear().draw();
                                    self.next_data(result);
                                },
                                beforeSend: function () {
                                    $("#progress-bar-syncron-ulang").show();
                                    $("#loading-progress").show();
                                    $("#keterangan-progress").text("Inisialisasi Proses ...");
                                    $("#row-list-data").hide();
                                },
                                complete: function () {
                                    $("#log-syncron-ulang").show();
                                    $("#btn-cancel-syncron-ulang").show();
                                    $("#keterangan-progress").text("Mohon menunggu hingga proses sinkronisasi selesai ...");
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
            log_table.search($("#cari-log").val()).draw();
        });
        $("#cari-log").keyup(function () {
            if (this.value === "") {
                log_table.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                log_table.search(this.value).draw();
            }
        });
        $("#status-log").on('change', function () {
            log_table.search(this.value).draw();
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
        $("#btn-cancel-syncron-ulang").click(function (){
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
    },
    next_data: function (data, index = 0, progres = 0, gagal = 0, sukses = 0) {
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/adm-akadmik/sinkronisasi-data/mahasiswa/json/syncron',
            method: 'post',
            data: {
                'nim': data[index].nim,
                'id_perguruan_tinggi': data[index].id_perguruan_tinggi,
                'id_mahasiswa': data[index].id_mahasiswa,
                'id_prodi': data[index].id_prodi,
                'id_periode': data[index].id_periode,
                'id_registrasi_mahasiswa': data[index].id_registrasi_mahasiswa,
                'nama_status_mahasiswa': data[index].nama_status_mahasiswa
            },
            success: function (result) {
                if (result.status) {
                    log_table.row.add([
                        (index + 1),
                        data[index].nama_mahasiswa + " (" + data[index].nim + ")",
                        "<i class='fas fa-user-check text-success p-1'></i>",
                        data[index].nama_mahasiswa,
                        data[index].nim,
                        "sukses"
                    ]).draw();
                    sukses++;
                } else {
                    log_table.row.add([
                        (index + 1),
                        data[index].nama_mahasiswa + " (" + data[index].nim + ")",
                        "<i class='fas fa-user-times text-danger p-1'></i>",
                        data[index].nama_mahasiswa,
                        data[index].nim,
                        "gagal"
                    ]).draw();
                    gagal++;
                }
                progres++;
            },
            complete: function () {
                $("#progress-bar").width((progres / n * 100).toFixed(2) + '%');
                $("#progress-text").text((progres / n * 100).toFixed(2) + '% Complete');
                $("#btn-gagal-log").text("Gagal : " + gagal);
                $("#btn-sukses-log").text("Sukses : " + sukses);
                if (index === (data.length - 1)) {
                    table.search("").draw();
                    $("#row-list-data").show();
                    $("#log-syncron-ulang").show();
                    $("#progress-bar-syncron-ulang").hide();
                } else {
                    if (!self.isSyncCanceled) {
                        index++;
                        self.next_data(data, index, progres, gagal, sukses)
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
    set_data_sipadu: function (data) {
        data = data[0];
        var self = this;
    },
    set_data_feeder: function (data) {
        data = data[0];
        var self = this;
    },
    set_perbandingan: function (data) {
        var self = this;
    }

};

jQuery(document).ready(function () {
    jQuery.mahasiswa.init();
});
