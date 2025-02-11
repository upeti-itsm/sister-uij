jQuery.list_doc_author = {
    data: {
        isSyncCanceled: false,
        log_table: $("#log-table").DataTable({
            scrollY: '300px',
            columns: [
                {width: "5%", sClass: 'text-center', searchable: false},
                {width: "50%", searchable: false},
                {width: "35%", searchable: false},
                {width: "10%", sClass: 'text-center', searchable: false},
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
        $(".select2").select2();
        // Table With DataTable
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/lppm/sinta/list-garuda-docs-json',
                type: 'post',
                data: function (data) {
                    data.id_sinta = $("#id_sinta").val();
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'nomor',
                    searchable: false,
                    sClass: 'text-center',
                    width: '5%'
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                    render: function (data) {
                        return "<b>" + data.nama_lengkap + "</b><hr/>" +
                            "<span class='badge badge-danger-soft mr-2'>" + data.nama_program_studi + "<br/><small>Program Studi</small></span>" +
                            "<span class='badge badge-success-soft mr-2'>" + data.publish_year + "<br/><small>Publish Year</small></span>" +
                            "<span class='badge badge-info-soft mr-2'>" + data.accreditation + "<br/><small>Accreditation</small></span>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "50%",
                    render: function (data) {
                        return "<p style='margin-bottom: 0'>" + data.title + "</p>" +
                            "<a href='" + data.url + "' target='_blank'>" + data.url + "</a>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "10%",
                    render: function (data) {
                        return "<button class='btn btn-sm btn-success-soft'>" + data.citation + "</button>";
                    }
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

        $("#kd_prodi").change(function () {
            self.data.table.ajax.reload();
        });

        $("#btn-sync").click(function () {
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Apakah anda yakin akan melakukan sinkronisasi data',
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
                                url: '/lppm/sinta/get-garuda-docs-json',
                                method: 'post',
                                data: {
                                    id_sinta: $("#id_sinta").val()
                                },
                                success: function (result) {
                                    self.next_data(result, $("#id_sinta").val());
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
    },
    next_data: function (data, id_sinta, index = 0, progres = 0, failed = 0, inserted = 0) {
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/lppm/sinta/store-garuda-docs-json',
            method: 'post',
            data: {
                'id_sinta': id_sinta,
                'doc_id_sinta': data[index].id,
                'accreditation': data[index].accreditation,
                'title': data[index].title,
                'abstract': data[index].abstract,
                'publisher_name': data[index].publisher_name,
                'publish_date': data[index].publish_date,
                'publish_year': data[index].publish_year,
                'doi': data[index].doi,
                'citation': data[index].citation,
                'source': data[index].source,
                'source_issue': data[index].source_issue,
                'source_page': data[index].source_page,
                'url': data[index].url,
            },
            success: function (result) {
                if (result.status == 1) {
                    self.data.log_table.row.add([
                        (index + 1),
                        data[index].title,
                        data[index].source,
                        "<i class='fas fa-check-square text-success p-1'></i> " + result.keterangan,
                        data[index].title,
                        data[index].title,
                        "success"
                    ]).draw();
                    inserted++;
                } else {
                    self.data.log_table.row.add([
                        (index + 1),
                        data[index].title,
                        data[index].source,
                        "<i class='fas fa-times-circle text-danger p-1'></i> " + result.keterangan,
                        data[index].title,
                        data[index].title,
                        "failed"
                    ]).draw();
                    failed++;
                }
                progres++;
            },
            error: function () {
                self.data.log_table.row.add([
                    (index + 1),
                    data[index].title,
                    data[index].source,
                    "<i class='fas fa-times-circle text-danger p-1'></i> ",
                    data[index].title,
                    data[index].title,
                    "failed"
                ]).draw();
                failed++;
                progres++;
            },
            complete: function () {
                $("#progress-bar").width((progres / n * 100).toFixed(2) + '%');
                $("#progress-text").text((progres / n * 100).toFixed(2) + '% Complete');
                $("#btn-failed-log").text("Failed : " + failed);
                $("#btn-inserted-log").text("Success : " + inserted);
                if (index >= (data.length - 1)) {
                    self.data.table.search("").draw();
                    $("#row-list-data").show();
                    $("#log-syncron-ulang").show();
                    $("#progress-bar-syncron-ulang").hide();
                } else {
                    if (!self.isSyncCanceled) {
                        index++;
                        self.next_data(data, id_sinta, index, progres, failed, inserted)
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
    jQuery.list_doc_author.init();
});
