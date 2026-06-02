/**
 * pengelolaan_surat.js
 * Rektor – Pengelolaan Surat Pengajuan
 */
jQuery.pengelolaan_surat_rektor = {
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

        function resolveStatusId(kind) {
            if (kind === "setuju") {
                return $("#status-setuju-id").val() || null;
            }
            if (kind === "revisi") {
                return $("#status-revisi-id").val() || null;
            }
            return null;
        }

        function setDefaultStatusFilter() {
            var id = resolveStatusId(["PIMPINAN"]);
            if (id) {
                $("#filtering-status-surat").val(id).trigger("change");
            }
        }

        function openDetailModal(res) {
            if (!res) {
                return;
            }

            self.data.detail = res;
            $("#detail-nomor-surat").text(res.nomor_surat || "-");
            $("#detail-perihal").text(res.perihal || "-");
            $("#detail-tanggal").text(res.tanggal_surat_ || res.tanggal_surat || "-");
            $("#detail-jenis").text(res.jenis_surat || res.nama_jenis_surat || "-");
            $("#detail-catatan-revisi").text(res.catatan_revisi || res.catatan || "-");
            $("#detail-pengirim-input").val(res.nama_unit_bagian_pengirim || "-");

            var isiSurat = res.isi_surat || "-";
            var statusUpper = (res.status_surat || "").toString().toUpperCase();
            var isLocked = !!res.sudah_disetujui || statusUpper.indexOf("DISETUJUI") >= 0;
            var isRejected = statusUpper.indexOf("DITOLAK") >= 0;
            var isRevisi = statusUpper.indexOf("REVISI") >= 0;

            $("#detail-pimpinan-penerima").val(res.id_personal_pimpinan || "").trigger("change");

            $("#btn-setujui-surat").prop("disabled", isLocked || isRejected || isRevisi);
            $("#btn-revisi-surat").prop("disabled", isLocked || isRejected || isRevisi);

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
        }

        function postSetStatus(statusId, confirmText, catatan) {
            var detail = self.data.detail;
            if (!detail || !detail.id_log_surat) {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Detail surat belum dimuat.",
                });
                return;
            }

            if (!statusId) {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Status tujuan tidak ditemukan. Periksa data status surat.",
                });
                return;
            }

            $.confirm({
                title: "Konfirmasi",
                type: "blue",
                columnClass: "medium",
                content: confirmText,
                buttons: {
                    confirm: {
                        text: "Ya",
                        btnClass: "btn-blue",
                        keys: ["enter"],
                        action: function () {
                            $.ajax({
                                url: "/rek/surat-menyurat/pengelolaan-surat-pengajuan/set-status",
                                method: "POST",
                                data: {
                                    id_log_surat: detail.id_log_surat,
                                    id_status_surat: statusId,
                                    catatan: catatan || null,
                                },
                                success: function (res) {
                                    $.alert({
                                        title: "Informasi",
                                        type: res.status ? "green" : "red",
                                        content: res.keterangan,
                                    });
                                    $("#modal-detail-surat").modal("hide");
                                    if (self.data.table) {
                                        self.data.table.ajax.reload();
                                    }
                                },
                                error: function () {
                                    $.alert({
                                        title: "Error",
                                        type: "red",
                                        content: "Gagal mengubah status surat.",
                                    });
                                },
                            });
                        },
                    },
                    cancel: { text: "Batal", btnClass: "btn-secondary" },
                },
            });
        }

        $(".select2").select2();
        setDefaultStatusFilter();

        self.data.table = $("#table-pengajuan-surat").DataTable({
            serverSide: true,
            ajax: {
                url: "/rek/surat-menyurat/pengelolaan-surat-pengajuan/json",
                type: "POST",
                data: function (d) {
                    d.jenis_surat = $("#filtering-jenis-surat").val();
                    d.status_surat = $("#filtering-status-surat").val();
                    d.unit_kerja = $("#filtering-unit-kerja").val();
                },
            },
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
                        btn += "<button title='Detail Surat' class='btn btn-sm btn-info btn-detail mr-1' data-id='" + data.id_log_surat + "'><i class='fas fa-eye'></i></button>";
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

        $("#cari-data")
            .keyup(function () {
                if (this.value === "") {
                    self.data.table.search("").draw();
                }
            })
            .keypress(function (event) {
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
                url: "/rek/surat-menyurat/pengelolaan-surat-pengajuan/detail",
                method: "POST",
                data: { id_log_surat: id },
                success: function (res) {
                    openDetailModal(res);
                },
                error: function () {
                    $.alert({ title: "Error", type: "red", content: "Gagal mengambil detail." });
                },
            });
        });

        $("#btn-setujui-surat").click(function () {
            var statusId = resolveStatusId("setuju");
            postSetStatus(statusId, "Apakah Anda yakin ingin menyetujui surat ini?");
        });

        $("#btn-revisi-surat").click(function () {
            var statusId = resolveStatusId("revisi");
            if (!statusId) {
                postSetStatus(statusId, "Apakah Anda yakin ingin mengembalikan surat ini untuk revisi?");
                return;
            }

            $("#modal-detail-surat").modal("hide");

            $.confirm({
                title: "Catatan Revisi",
                type: "orange",
                columnClass: "medium",
                content: '' +
                    '<label class="form-label">Catatan Revisi <span class="text-danger">*</span></label>' +
                    '<textarea class="form-control catatan" rows="3" placeholder="Tulis catatan revisi..."></textarea>',
                buttons: {
                    confirm: {
                        text: "Kirim",
                        btnClass: "btn-blue",
                        keys: ["enter"],
                        action: function () {
                            var catatan = this.$content.find(".catatan").val();
                            if (!catatan) {
                                $.alert("Catatan revisi wajib diisi!");
                                return false;
                            }
                            postSetStatus(statusId, "Apakah Anda yakin ingin mengembalikan surat ini untuk revisi?", catatan);
                        },
                    },
                    cancel: {
                        text: "Batal",
                        btnClass: "btn-secondary",
                        action: function () {
                            $("#modal-detail-surat").modal("show");
                        },
                    },
                },
            });
        });
    },
};

jQuery(document).ready(function () {
    jQuery.pengelolaan_surat_rektor.init();
});
