jQuery.pengajuan_surat_unit_kerja = {
    data: {
        table: null,
        detail: null,
    },

    init: function () {
        this.setEvents();
    },

    setEvents: function () {
        var self = this;

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/\"/g, "&quot;")
                .replace(/'/g, "&#39;");
        }

        function buildCatatanHtml(raw) {
            if (!raw) {
                return "";
            }

            var text = String(raw);
            var truncated = text;
            var isTruncated = text.length > 120;
            if (isTruncated) {
                truncated = text.slice(0, 117) + "...";
            }

            if (!isTruncated) {
                return "<br/><small class='text-danger'><i class='fas fa-comment-alt mr-1'></i>" + escapeHtml(text) + "</small>";
            }

            return (
                "<br/><small class='text-danger catatan-revisi' data-full='" +
                encodeURIComponent(text) +
                "' data-short='" +
                encodeURIComponent(truncated) +
                "'>" +
                "<i class='fas fa-comment-alt mr-1'></i>" +
                "<span class='catatan-text'>" +
                escapeHtml(truncated) +
                "</span> " +
                "<a href='#' class='catatan-toggle'>Lihat selengkapnya</a>" +
                "</small>"
            );
        }

        $(".select2").select2();

        self.data.table = $("#table-pengajuan-surat").DataTable({
            serverSide: true,
            ajax: {
                url: "/kary/surat-menyurat/pengajuan-surat/json",
                type: "POST",
                data: function (d) {
                    d.akses_all = false;
                    d.jenis_surat = $("#filtering-jenis-surat").val();
                    d.status_surat = $("#filtering-status-surat").val();
                    d.unit_kerja = $("#filtering-unit-kerja").val();
                },
            },
            scrollY: "400px",
            scrollCollapse: true,
            columns: [
                {
                    data: null,
                    searchable: false,
                    className: "text-center",
                    width: "5%",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "nomor_surat",
                    searchable: false,
                    className: "text-left",
                    width: "15%",
                    render: function (data) {
                        return "<b>" + (data || "-") + "</b>";
                    },
                },
                {
                    data: null,
                    searchable: false,
                    className: "text-left",
                    width: "30%",
                    render: function (data) {
                        var catatan = data.catatan_revisi || data.catatan || "";
                        var catatanHtml = buildCatatanHtml(catatan);
                        return "<p><b>" + (data.perihal || "-") + "</b><br/><small class='text-muted'>Jenis: " + (data.jenis_surat || data.nama_jenis_surat || "-") + "</small>" + catatanHtml + "</p>";
                    },
                },
                {
                    data: null,
                    searchable: false,
                    className: "text-left",
                    width: "20%",
                    render: function (data) {
                        return "<p><b>" + (data.nama_unit_bagian_pengirim || "-") + "</b><br/><small class='text-muted'>Tgl: " + (data.tanggal_surat_ || data.tanggal_surat || "-") + "</small></p>";
                    },
                },
                {
                    data: null,
                    searchable: false,
                    className: "text-center",
                    width: "15%",
                    render: function (data) {
                        var status = (data.status_surat || "-").toString();
                        var statusUpper = status.toUpperCase();
                        var badgeClass = "badge-secondary";

                        if (statusUpper.indexOf("DITOLAK") >= 0) {
                            badgeClass = "badge-danger text-white";
                        } else if (statusUpper.indexOf("DISETUJUI") >= 0) {
                            badgeClass = "badge-success text-white";
                        } else if (statusUpper.indexOf("DIAJUKAN") >= 0) {
                            badgeClass = "badge-warning text-white";
                        }

                        return "<span class='badge " + badgeClass + "'>" + status + "</span>";
                    },
                },
                {
                    data: null,
                    searchable: false,
                    className: "text-center",
                    width: "15%",
                    render: function (data) {
                        var btn = "";
                        var isLocked = !!data.id_personal_pimpinan || data.sudah_disetujui;
                        var status = (data.status_surat || "").toString().toUpperCase();
                        var isRevisi = status.indexOf("REVISI") >= 0;

                        btn += "<button title='Detail Surat' class='btn btn-sm btn-info btn-detail mr-1' data-id='" + data.id_log_surat + "'><i class='fas fa-eye'></i></button>";

                        if (isRevisi) {
                            btn += "<a title='Perbaiki' class='btn btn-sm btn-warning text-white mr-1' href='/kary/surat-menyurat/pengajuan-surat/edit/" + data.id_log_surat + "'><i class='fas fa-edit'></i></a>";
                        }

                        if (!isLocked) {
                            btn += "<button title='Hapus' class='btn btn-sm btn-danger btn-delete' data-id='" + data.id_log_surat + "' data-perihal='" + data.perihal + "'><span class='spinner-border spinner-border-sm mr-1' id='loading-spin-" + data.id_log_surat + "' style='display:none' role='status' aria-hidden='true'></span><i class='fas fa-trash' id='delete-icon-" + data.id_log_surat + "'></i></button>";
                        }

                        return btn;
                    },
                },
                { data: "perihal", searchable: true, visible: false },
                { data: "nomor_surat", searchable: true, visible: false },
            ],
            paging: true,
            processing: true,
            pageLength: 10,
            ordering: false,
            lengthChange: false,
            autoWidth: false,
            sDom: "ltipr",
            language: { emptyTable: "Tidak ditemukan data" },
        });

        $("#filtering-jenis-surat, #filtering-status-surat, #filtering-unit-kerja").change(function () {
            self.data.table.ajax.reload();
        });

        $("#btn-cari-data").click(function () {
            self.data.table.search($("#cari-data").val()).draw();
        });

        $("#cari-data").keyup(function () {
            if (this.value === "") {
                self.data.table.search("").draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.table.search(this.value).draw();
            }
        });

        $("#table-pengajuan-surat").on("click", "a.catatan-toggle", function (event) {
            event.preventDefault();
            var $wrap = $(this).closest(".catatan-revisi");
            var isExpanded = $wrap.attr("data-expanded") === "true";
            var fullText = decodeURIComponent($wrap.attr("data-full") || "");
            var shortText = decodeURIComponent($wrap.attr("data-short") || "");
            if (isExpanded) {
                $wrap.find(".catatan-text").text(shortText);
                $(this).text("Lihat selengkapnya");
                $wrap.attr("data-expanded", "false");
            } else {
                $wrap.find(".catatan-text").text(fullText);
                $(this).text("Tutup");
                $wrap.attr("data-expanded", "true");
            }
        });

        $("#table-pengajuan-surat").on("click", "button.btn-detail", function () {
            var id = $(this).data("id");
            $.ajax({
                url: "/kary/surat-menyurat/pengajuan-surat/detail",
                method: "POST",
                data: { id_log_surat: id },
                success: function (res) {
                    if (!res) {
                        return;
                    }

                    self.data.detail = res;
                    $("#detail-nomor-surat").text(res.nomor_surat || "-");
                    $("#detail-perihal").text(res.perihal || "-");
                    $("#detail-tanggal").text(res.tanggal_surat_ || res.tanggal_surat || "-");
                    $("#detail-jenis").text(res.jenis_surat || res.nama_jenis_surat || "-");
                    $("#detail-catatan-revisi").text(res.catatan_revisi || res.catatan || "-");
                    $("#detail-pengirim-input").val(res.nama_unit_pengirim || res.unit_bagian_pengirim || "-");

                    if (res.path_lampiran) {
                        $("#detail-lampiran-link").attr("href", "/storage/" + res.path_lampiran).show();
                        $("#detail-lampiran-empty").hide();
                    } else {
                        $("#detail-lampiran-link").hide();
                        $("#detail-lampiran-empty").show();
                    }

                    var isiSurat = res.isi_surat || "-";

                    $("#modal-detail-surat")
                        .off("shown.bs.modal.ck")
                        .on("shown.bs.modal.ck", function () {
                            if (window.CKEDITOR) {
                                if (!CKEDITOR.instances.editor1) {
                                    CKEDITOR.replace("editor1", {
                                        height: 220,
                                        allowedContent: true,
                                    });
                                    CKEDITOR.instances.editor1.on("instanceReady", function () {
                                        CKEDITOR.instances.editor1.setReadOnly(true);
                                    });
                                }
                                CKEDITOR.instances.editor1.setData(isiSurat);
                                CKEDITOR.instances.editor1.setReadOnly(true);
                            }
                        })
                        .modal("show");
                },
                error: function () {
                    $.alert({ title: "Error", type: "red", content: "Gagal mengambil detail." });
                },
            });
        });

        $("#table-pengajuan-surat").on("click", "button.btn-delete", function () {
            var id = $(this).data("id");
            var perihal = $(this).data("perihal");

            $.confirm({
                title: "Konfirmasi!",
                type: "orange",
                columnClass: "medium",
                content: "Apakah anda yakin menghapus pengajuan surat dengan perihal <b>" + perihal + "</b>?<br/><b class='text-danger'>File lampiran juga akan terhapus dari sistem.</b>",
                buttons: {
                    confirm: {
                        text: "Yakin",
                        btnClass: "btn-green",
                        keys: ["enter"],
                        action: function () {
                            $.ajax({
                                url: "/kary/surat-menyurat/pengajuan-surat/delete",
                                method: "POST",
                                data: { id_log_surat: id },
                                beforeSend: function () {
                                    var $btn = $("button.btn-delete[data-id='" + id + "']");
                                    $btn.prop('disabled', true);
                                    $("#loading-spin-" + id).show();
                                    $("#delete-icon-" + id).hide();
                                },
                                success: function (res) {
                                    $.alert({
                                        title: "Informasi",
                                        type: res.status ? "green" : "red",
                                        content: res.keterangan,
                                    });
                                },
                                error: function () {
                                    $.alert({
                                        title: "Error",
                                        type: "red",
                                        content: "Terjadi kesalahan pada server.",
                                    });
                                },
                                complete: function () {
                                    $("#loading-spin-" + id).hide();
                                    $("#delete-icon-" + id).show();
                                    $("button.btn-delete[data-id='" + id + "']").prop('disabled', false);
                                    self.data.table.ajax.reload();
                                },
                            });
                        },
                    },
                    cancel: { text: "Batal", btnClass: "btn-red" },
                },
            });
        });
    },
};

jQuery(document).ready(function () {
    jQuery.pengajuan_surat_unit_kerja.init();
});
