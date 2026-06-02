jQuery.pengajuan_surat_unit_kerja_create = {
    init: function () {
        this.setEvents();
    },

    setEvents: function () {
        $(".select2").select2();

        $("#form-surat-tanggal").datepicker({
            language: "id",
            format: "dd MM yyyy",
            autoclose: true,
            orientation: "bottom",
        }).datepicker("setDate", moment().format("D/M/YYYY"));

        $(".custom-file-input").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label")
                .addClass("selected")
                .html(fileName || "Pilih file (opsional)");
        });

        function ensureEditor() {
            if (window.CKEDITOR && !CKEDITOR.instances["form-surat-isi_surat"]) {
                CKEDITOR.replace("form-surat-isi_surat", {
                    height: 420,
                    allowedContent: true,
                });
            }
        }

        function setEditorData(value) {
            ensureEditor();
            if (window.CKEDITOR && CKEDITOR.instances && CKEDITOR.instances["form-surat-isi_surat"]) {
                CKEDITOR.instances["form-surat-isi_surat"].setData(value || "");
            } else {
                $("#form-surat-isi_surat").val(value || "");
            }
        }

        ensureEditor();

        var editId = $("#edit-log-surat-id").val();
        if (editId) {
            $.ajax({
                url: "/kary/surat-menyurat/pengajuan-surat/detail",
                method: "POST",
                data: { id_log_surat: editId },
                success: function (res) {
                    if (!res) {
                        return;
                    }

                    $("#form-surat-id_log_surat").val(res.id_log_surat || editId);
                    $("#form-surat-perihal").val(res.perihal || "");
                    $("#form-surat-jenis_surat").val(res.id_jenis_surat || "").trigger("change");

                    var tglRaw = res.tanggal_surat_ || res.tanggal_surat || "";
                    var tgl = moment(tglRaw, ["YYYY-MM-DD", "DD-MM-YYYY", "DD/MM/YYYY", "DD MMMM YYYY"], true);
                    if (!tgl.isValid() && res.tanggal_surat) {
                        tgl = moment(res.tanggal_surat);
                    }
                    if (tgl.isValid()) {
                        $("#form-surat-tanggal").datepicker("setDate", tgl.toDate());
                    }

                    setEditorData(res.isi_surat || "");

                    var catatan = res.catatan_revisi || res.catatan || "";
                    if (catatan) {
                        $("#catatan-revisi-text").text(catatan);
                        $("#catatan-revisi-wrapper").show();
                    }
                },
                error: function () {
                    $.alert({
                        title: "Error",
                        type: "red",
                        content: "Gagal memuat data revisi.",
                    });
                },
            });
        }

        $("#form-surat-btn-save").click(function () {
            var perihal = $("#form-surat-perihal").val();
            var tanggal = $("#form-surat-tanggal").val();
            var jenis = $("#form-surat-jenis_surat").val();
            var isi = $("#form-surat-isi_surat").val();

            if (window.CKEDITOR && CKEDITOR.instances && CKEDITOR.instances["form-surat-isi_surat"]) {
                isi = CKEDITOR.instances["form-surat-isi_surat"].getData();
            }

            if (!perihal || !tanggal || !jenis || !isi) {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Perihal, Tanggal Surat, Jenis Surat, dan Isi Surat sudah terisi!",
                });
                return;
            }

            var tglObj = $("#form-surat-tanggal").datepicker("getDate");
            if (tglObj) {
                $("#form-surat-tanggal").val(moment(tglObj).format("YYYY-MM-DD"));
            }

            if (window.CKEDITOR && CKEDITOR.instances && CKEDITOR.instances["form-surat-isi_surat"]) {
                CKEDITOR.instances["form-surat-isi_surat"].updateElement();
            }

            $("#form-surat-loading").show();
            $("#form-surat-btn-save").prop("disabled", true);
            $("#form-pengajuan-surat-unit-kerja").submit();
        });
    },
};

jQuery(document).ready(function () {
    jQuery.pengajuan_surat_unit_kerja_create.init();
});
