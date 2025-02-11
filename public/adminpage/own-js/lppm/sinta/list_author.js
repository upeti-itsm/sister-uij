jQuery.list_author = {
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
                url: '/lppm/sinta/list-author-json',
                type: 'post',
                data: function (data) {
                    data.kd_prodi = $("#kd_prodi").val();
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
                        return "<b>" + data.nama_lengkap + "</b><br/>" +
                            "<small>Prodi. " + data.nama_program_studi + "</small><br/>" +
                            "<a href='/lppm/sinta/list-iprs/" + data.id_sinta + "' class='badge badge-danger-soft mr-2'><i class='fas fa-stamp mr-2'></i>IPRs</a>" +
                            "<a href='/lppm/sinta/list-books/" + data.id_sinta + "' class='badge badge-danger-soft mr-2'><i class='fas fa-book mr-2'></i>Books</a>" +
                            "<a href='/lppm/sinta/list-research/" + data.id_sinta + "' class='badge badge-danger-soft mr-2'><i class='fas fa-search-location mr-2'></i>Research</a>" +
                            "<a href='/lppm/sinta/list-service/" + data.id_sinta + "' class='badge badge-danger-soft'><i class='fas fa-users-cog mr-2'></i>Service</a>" +
                            "<hr/>" +
                            "<span class='badge badge-info-soft mr-2'>" + data.id_sinta + "<br/><small>ID Sinta</small></span>" +
                            "<span class='badge badge-info-soft mr-2'>" + data.sinta_score_v3_overall + "<br/><small>SINTA Score Overall</small></span>" +
                            "<span class='badge badge-success-soft'>" + data.sinta_score_v3_3year + "<br/><small>SINTA Score 3Yr</small></span><br/>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%",
                    render: function (data) {
                        return "<a href='/lppm/sinta/list-scopus-docs/" + data.id_sinta + "' style='font-weight: normal'>Jml. Doc Scopus : " + data.total_document_scopus + "</a><br/>" +
                            "<span class='badge badge-info-soft mr-2'>" + data.total_citation_scopus + "<br/><small>Total Citation</small></span>" +
                            "<span class='badge badge-success-soft'>" + data.h_index_scopus + "<br/><small>H-Index Scopus</small></span><hr/>" +
                            "<a href='/lppm/sinta/list-wos-docs/" + data.id_sinta + "' style='font-weight: normal'>Jml. Doc WoS : " + data.total_document_wos + "</a><br/>" +
                            "<span class='badge badge-info-soft mr-2'>" + data.total_citation_wos + "<br/><small>Total Citation</small></span>" +
                            "<span class='badge badge-success-soft'>" + data.h_index_wos + "<br/><small>H-Index WoS</small></span>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%",
                    render: function (data) {
                        return "<a href='/lppm/sinta/list-garuda-docs/" + data.id_sinta + "' style='font-weight: normal'>Jml. Doc Garuda : " + data.total_document_garuda + "</a><br/>" +
                            "<span class='badge badge-info-soft mr-2'>" + data.total_citation_garuda + "<br/><small>Total Citation</small></span>" +
                            "<span class='badge badge-success-soft'>" + data.total_cited_doc_garuda + "<br/><small>Total Cited Documents</small></span><hr/>" +
                            "<a href='/lppm/sinta/list-google-docs/" + data.id_sinta + "' style='font-weight: normal'>Jml. Doc Scholar : " + data.total_document_google + "</a><br/>" +
                            "<span class='badge badge-info-soft mr-2'>" + data.total_citation_google + "<br/><small>Total Citation</small></span>" +
                            "<span class='badge badge-success-soft'>" + data.h_index_google + "<br/><small>H-Index Scholar</small></span>";
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
                                url: '/lppm/sinta/get-author-json',
                                method: 'post',
                                data: {},
                                success: function (result) {
                                    if (result.length > 0)
                                        self.next_data(result);
                                    else {
                                        self.data.table.search("").draw();
                                        $("#row-list-data").show();
                                        $("#log-syncron-ulang").show();
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
    next_data: function (data, index = 0, progres = 0, failed = 0, inserted = 0) {
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/lppm/sinta/store-author-json',
            method: 'post',
            data: {
                'id_sinta': data[index].id,
                'sinta_score_v2_overall': data[index].sinta_score_v2_overall,
                'sinta_score_v2_3year': data[index].sinta_score_v2_3year,
                'sinta_score_v3_overall': data[index].sinta_score_v3_overall,
                'sinta_score_v3_3year': data[index].sinta_score_v3_3year,
                'affiliation_score_v3_overall': data[index].affiliation_score_v3_overall,
                'affiliation_score_v3_3year': data[index].affiliation_score_v3_3year,
                'total_document_scopus': data[index].scopus.total_document,
                'total_citation_scopus': data[index].scopus.total_citation,
                'total_cited_doc_scopus': data[index].scopus.total_cited_doc,
                'h_index_scopus': data[index].scopus.h_index,
                'i10_index_scopus': data[index].scopus.i10_index,
                'g_index_scopus': data[index].scopus.g_index,
                'g_index_3year_scopus': data[index].scopus.g_index_3year,
                'total_document_wos': data[index].wos.total_document,
                'total_citation_wos': data[index].wos.total_citation,
                'total_cited_doc_wos': data[index].wos.total_cited_doc,
                'h_index_wos': data[index].wos.h_index,
                'total_document_garuda': data[index].garuda.total_document,
                'total_citation_garuda': data[index].garuda.total_citation,
                'total_cited_doc_garuda': data[index].garuda.total_cited_doc,
                'total_document_google': data[index].google.total_document,
                'total_citation_google': data[index].google.total_citation,
                'total_cited_doc_google': data[index].google.total_cited_doc,
                'h_index_google': data[index].google.h_index,
                'i10_index_google': data[index].google.i10_index,
                'g_index_google': data[index].google.g_index,
            },
            success: function (result) {
                if (result.status == 1) {
                    self.data.log_table.row.add([
                        (index + 1),
                        data[index].fullname,
                        data[index].NIDN,
                        "<i class='fas fa-check-square text-success p-1'></i> " + result.keterangan,
                        data[index].fullname,
                        data[index].NIDN,
                        "success"
                    ]).draw();
                    inserted++;
                } else {
                    self.data.log_table.row.add([
                        (index + 1),
                        data[index].fullname,
                        data[index].NIDN,
                        "<i class='fas fa-times-circle text-danger p-1'></i> " + result.keterangan,
                        data[index].fullname,
                        data[index].NIDN,
                        "failed"
                    ]).draw();
                    failed++;
                }
                progres++;
            },
            error: function () {
                self.data.log_table.row.add([
                    (index + 1),
                    data[index].fullname,
                    data[index].NIDN,
                    "<i class='fas fa-times-circle text-danger p-1'></i> ",
                    data[index].fullname,
                    data[index].NIDN,
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
                        self.next_data(data, index, progres, failed, inserted)
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
    jQuery.list_author.init();
});
