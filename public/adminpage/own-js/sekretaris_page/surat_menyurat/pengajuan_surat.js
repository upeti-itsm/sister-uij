/**
 * pengajuan_surat.js
 * Sekretaris – Surat Menyurat – Pengajuan Surat
 * Mengikuti pola surat_keputusan.js
 */
jQuery.pengajuan_surat = {
    data: {
        table: null,
        datepicker: null,
        detail: null,
    },

    init: function () {
        this.setEvents();
    },

    // ─── Reset form ke kondisi kosong ────────────────────────────────────────
    resetForm: function () {
        $("#form-surat-id_log_surat").val("");
        $("#form-surat-perihal").val("");
        if (window.CKEDITOR && CKEDITOR.instances && CKEDITOR.instances["form-surat-isi_surat"]) {
            CKEDITOR.instances["form-surat-isi_surat"].setData("");
        } else {
            $("#form-surat-isi_surat").val("");
        }
        $("#form-surat-jenis_surat").val("").trigger("change");
        $("#form-surat-unit_pengirim").val("").trigger("change");
        $("#form-surat-unit_penerima").val([]).trigger("change");
        $("#form-surat-personal_penerima").val([]).trigger("change");
        $("#form-surat-pimpinan_penerima").val("").trigger("change");
        $("#surat-lampiran-file").val("");
        $(".custom-file-label").html("Pilih file (opsional)");
        $("#info-lampiran-lama").hide();
        $("#form-collapse-surat-title").text("Tambah Pengajuan Surat Baru");

        // Reset datepicker ke hari ini
        jQuery.pengajuan_surat.data.datepicker
            .datepicker("setDate", moment().format("D/M/YYYY"));
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

        function initFormEditor() {
            if (window.CKEDITOR && !CKEDITOR.instances["form-surat-isi_surat"]) {
                CKEDITOR.replace("form-surat-isi_surat", {
                    height: 260,
                    allowedContent: true,
                });
            }
        }

        // ── Select2 ─────────────────────────────────────────────────────────
        $(".select2").not("#detail-pimpinan-penerima").select2();

        // ── Datepicker ───────────────────────────────────────────────────────
        self.data.datepicker = $("#form-surat-tanggal").datepicker({
            language: "id",
            format: "dd MM yyyy",
            autoclose: true,
            orientation: "bottom",
        }).datepicker("setDate", moment().format("D/M/YYYY"));

        // ── Label file input ─────────────────────────────────────────────────
        $(".custom-file-input").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label")
                .addClass("selected")
                .html(fileName || "Pilih file (opsional)");
        });

        // ── DataTable ────────────────────────────────────────────────────────
        self.data.table = $("#table-pengajuan-surat").DataTable({
            serverSide: true,
            ajax: {
                url: "/sek/surat-menyurat/pengajuan-surat/json",
                type: "POST",
                data: function (d) {
                    d.jenis_surat = $("#filtering-jenis-surat").val();
                    d.status_surat = $("#filtering-status-surat").val();
                    d.unit_kerja = $("#filtering-unit-kerja").val();
                },
            },
            scrollY: "300px",
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
                    render: function (data, type, row) {
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
                        return (
                            "<p><b>" + data.perihal + "</b><br/>" +
                            "<small class='text-muted'>Jenis: " + (data.jenis_surat || data.nama_jenis_surat || "-") + "</small>" +
                            catatanHtml +
                            "</p>"
                        );
                    },
                },
                {
                    data: null,
                    searchable: false,
                    className: "text-left",
                    width: "20%",
                    render: function (data) {
                        return (
                            "<p><b>" + (data.nama_unit_bagian_pengirim || "-") + "</b><br/>" +
                            "<small class='text-muted'>Tgl: " + (data.tanggal_surat_ || data.tanggal_surat || "-") + "</small></p>"
                        );
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

                        // Tombol detail isi surat
                        btn +=
                            "<button title='Detail Surat' class='btn btn-sm btn-info btn-detail mr-1' " +
                            "data-id='" + data.id_log_surat + "'>" +
                            "<i class='fas fa-eye'></i></button>";

                        if (!isLocked) {
                            // Tombol hapus
                            btn +=
                                "<button title='Hapus' class='btn btn-sm btn-danger btn-delete' " +
                                "data-id='" + data.id_log_surat + "' data-perihal='" + data.perihal + "'>" +
                                "<span class='spinner-border spinner-border-sm mr-1' " +
                                "id='loading-spin-" + data.id_log_surat + "' style='display:none' " +
                                "role='status' aria-hidden='true'></span>" +
                                "<i class='fas fa-trash'></i></button>";
                        }

                        return btn;
                    },
                },
                // Kolom tersembunyi untuk pencarian
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

        // ── Filter tahun ─────────────────────────────────────────────────────
        $("#filtering-jenis-surat, #filtering-status-surat, #filtering-unit-kerja").change(function () {
            self.data.table.ajax.reload();
        });

        // ── Cari data ────────────────────────────────────────────────────────
        $("#btn-cari-data").click(function () {
            self.data.table.search($("#cari-data").val()).draw();
        });
        $("#cari-data")
            .keyup(function () {
                if (this.value === "") self.data.table.search("").draw();
            })
            .keypress(function (e) {
                if (e.keyCode === 13) self.data.table.search(this.value).draw();
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

        // ── Tombol Tambah ────────────────────────────────────────────────────
        $("#btn-tambah-surat").click(function () {
            self.resetForm();
            $("#table-display").collapse("hide");
            $("#form-collapse-surat").collapse("show");
            initFormEditor();
        });

        // ── Tombol Batal ─────────────────────────────────────────────────────
        $("#form-surat-btn-cancel").click(function () {
            self.resetForm();
            $("#form-collapse-surat").collapse("hide");
            $("#table-display").collapse("show");
        });

        // ── Tombol Simpan ────────────────────────────────────────────────────
        $("#form-surat-btn-save").click(function () {
            var perihal = $("#form-surat-perihal").val();
            var tanggal = $("#form-surat-tanggal").val();
            var isi = $("#form-surat-isi_surat").val();
            if (window.CKEDITOR && CKEDITOR.instances && CKEDITOR.instances["form-surat-isi_surat"]) {
                isi = CKEDITOR.instances["form-surat-isi_surat"].getData();
            }
            var jenis = $("#form-surat-jenis_surat").val();
            var pengirim = $("#form-surat-unit_pengirim").val();
            var pimpinan = $("#form-surat-pimpinan_penerima").val();

            if (!perihal || !tanggal || !isi || !jenis || !pengirim || !pimpinan) {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content:
                        "Pastikan Perihal, Tanggal, Jenis Surat, Unit Pengirim, Pimpinan Rektorat, dan Isi Surat sudah terisi!",
                });
                return;
            }

            var tglObj = self.data.datepicker.datepicker("getDate");
            $("#form-surat-tanggal").val(moment(tglObj).format("YYYY-MM-DD"));

            if (window.CKEDITOR && CKEDITOR.instances && CKEDITOR.instances["form-surat-isi_surat"]) {
                CKEDITOR.instances["form-surat-isi_surat"].updateElement();
            }

            $("#form-surat-loading").show();
            $("#form-surat-btn-save").prop("disabled", true);
            $("#form-collapse-surat-form_submit").submit();
        });

        // ── Tombol Detail ─────────────────────────────────────────────────────
        $("#table-pengajuan-surat").on("click", "button.btn-detail", function () {
            var id = $(this).data("id");
            $.ajax({
                url: "/sek/surat-menyurat/pengajuan-surat/detail",
                method: "POST",
                data: { id_log_surat: id },
                success: function (res) {
                    if (!res) return;
                    self.data.detail = res;
                    $("#detail-nomor-surat").text(res.nomor_surat || "-");
                    $("#detail-perihal").text(res.perihal || "-");
                    $("#detail-tanggal").text(res.tanggal_surat_ || res.tanggal_surat || "-");
                    $("#detail-jenis").text(res.jenis_surat || res.nama_jenis_surat || "-");
                    $("#detail-catatan-revisi").text(res.catatan_revisi || res.catatan || "-");
                    $("#detail-pengirim-input").val(res.nama_unit_bagian_pengirim || res.unit_bagian_pengirim || "-");
                    var isiSurat = res.isi_surat || "-";

                    if (res.id_personal_pimpinan) {
                        $("#detail-pimpinan-penerima").val(res.id_personal_pimpinan).trigger("change");
                    } else {
                        $("#detail-pimpinan-penerima").val("").trigger("change");
                    }

                    var isLocked = !!res.id_personal_pimpinan || res.sudah_disetujui;
                    $("#detail-pimpinan-penerima").prop("disabled", isLocked);
                    $("#btn-teruskan-pimpinan").prop("disabled", isLocked);

                    $("#modal-detail-surat")
                        .off("shown.bs.modal.ck")
                        .on("shown.bs.modal.ck", function () {
                            var $detailPimpinan = $("#detail-pimpinan-penerima");
                            if ($detailPimpinan.data("select2")) {
                                $detailPimpinan.select2("destroy");
                            }
                            $detailPimpinan.select2({
                                dropdownParent: $("#modal-detail-surat"),
                                width: "100%",
                                dropdownAutoWidth: false,
                            });
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

        // ── Tombol Teruskan ke Pimpinan ────────────────────────────────────
        $("#btn-teruskan-pimpinan").click(function () {
            var detail = self.data.detail;
            var pimpinan = $("#detail-pimpinan-penerima").val();

            if (!detail || !detail.id_log_surat) {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Detail surat belum dimuat.",
                });
                return;
            }

            if (!pimpinan) {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Silakan pilih pimpinan rektorat.",
                });
                return;
            }

            $.confirm({
                title: "Konfirmasi",
                type: "blue",
                columnClass: "medium",
                content: "Apakah Anda yakin ingin meneruskan surat ini ke pimpinan rektorat?",
                buttons: {
                    confirm: {
                        text: "Ya",
                        btnClass: "btn-blue",
                        keys: ["enter"],
                        action: function () {
                            $.ajax({
                                url: "/sek/surat-menyurat/pengajuan-surat/teruskan-pimpinan",
                                method: "POST",
                                data: {
                                    id_log_surat: detail.id_log_surat,
                                    id_personal_pimpinan: pimpinan,
                                },
                                beforeSend: function () {
                                    $("#btn-teruskan-pimpinan").prop("disabled", true);
                                },
                                success: function (res) {
                                    $.alert({
                                        title: res.status ? "Berhasil" : "Gagal",
                                        type: res.status ? "green" : "red",
                                        content: res.keterangan || "Proses selesai.",
                                    });

                                    if (res.status === true) {
                                        $("#detail-pimpinan-penerima").prop("disabled", true);
                                        $("#modal-detail-surat").modal("hide");
                                    }

                                    self.data.table.ajax.reload();
                                },
                                error: function () {
                                    $.alert({
                                        title: "Error",
                                        type: "red",
                                        content: "Terjadi kesalahan pada server.",
                                    });
                                },
                                complete: function () {
                                    $("#btn-teruskan-pimpinan").prop("disabled", false);
                                },
                            });
                        },
                    },
                    cancel: { text: "Batal", btnClass: "btn-secondary" },
                },
            });
        });

        // ── Tombol Hapus ─────────────────────────────────────────────────────
        $("#table-pengajuan-surat").on("click", "button.btn-delete", function () {
            var id = $(this).data("id");
            var perihal = $(this).data("perihal");

            $.confirm({
                title: "Konfirmasi!",
                type: "orange",
                columnClass: "medium",
                content:
                    "Apakah anda yakin menghapus pengajuan surat dengan perihal <b>" +
                    perihal + "</b>?<br/>" +
                    "<b class='text-danger'>File lampiran juga akan terhapus dari sistem.</b>",
                buttons: {
                    confirm: {
                        text: "Yakin",
                        btnClass: "btn-green",
                        keys: ["enter"],
                        action: function () {
                            $.ajax({
                                url: "/sek/surat-menyurat/pengajuan-surat/delete",
                                method: "POST",
                                data: { id_log_surat: id },
                                beforeSend: function () {
                                    $("#loading-spin-" + id).show();
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
                                    self.data.table.ajax.reload();
                                },
                            });
                        },
                    },
                    cancel: { text: "Batal", btnClass: "btn-red" },
                },
            });
        });
    }, // setEvents
}; // jQuery.pengajuan_surat

jQuery(document).ready(function () {
    jQuery.pengajuan_surat.init();
});
