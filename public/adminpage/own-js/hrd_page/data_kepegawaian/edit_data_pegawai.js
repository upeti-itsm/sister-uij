jQuery.edit_data_pegawai = {
    data: {
        tgl_aktif: $("#tanggal_aktif"),
        tgl_lahir: $("#tanggal_lahir"),
        tgl_lulus: $("#tanggal_lulus"),
        tmt_golongan: $("#tgl_tmt_golongan"),
        tmt_jabatan_fungsional: $("#tgl_tmt_jabatan_fungsional"),
        tmt_jabatan_struktural: $("#tgl_tmt_jabatan_struktural"),
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        // Option Data
        $(".select2").select2();
        $("#btn_pilih_foto").click(function () {
            $("#photo_profile").click();
        });
        $("#status_sertifikasi").change(function () {
            if ($(this).val() != "0")
                $("#unggah_sertifikat_area").show()
            else
                $("#unggah_sertifikat_area").hide()
        });
        $(".custom-file-input").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        $("#photo_profile").change(function () {
            var data = new FormData();
            data.append('id', $("#id_personal").val());
            $.each($("#photo_profile")[0].files, function (i, file) {
                data.append('file', file);
            });
            $.ajax({
                url: '/hrd/data-kepegawaian/edit-data-pegawai/update-path-photo',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                beforeSend: function () {
                    $("#photo_profile_loading").show();
                },
                success: function (response) {
                    if (response.status === 1) {
                        $.confirm({
                            title: "Informasi",
                            type: "green",
                            content: response.keterangan,
                            buttons: {
                                confirm: {
                                    text: 'OK',
                                    btnClass: 'btn-green',
                                    keys: ['enter'],
                                    action: function () {
                                        location.reload();
                                    }
                                }
                            }
                        });
                    } else {
                        $.alert({
                            title: "Informasi",
                            type: "red",
                            content: response.keterangan
                        });
                    }
                },
                complete: function () {
                    $("#photo_profile_loading").hide();
                }
            });
        });

        var tgl_aktif_sebelumnya = moment().format('YYYY-MM-DD');
        if ($("#tgl_aktif").val())
            tgl_aktif_sebelumnya = $("#tgl_aktif").val();

        self.data.tgl_aktif = $("#tanggal_aktif").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment(tgl_aktif_sebelumnya).format('D/M/YYYY')).on('changeDate', function (e) {
            $("#tgl_aktif").val(moment(e.date).format('YYYY-MM-DD'));
        });
        $("#tgl_aktif").val(tgl_aktif_sebelumnya);

        var tgl_lahir_sebelumnya = moment().format('YYYY-MM-DD');
        if ($("#tgl_lahir").val())
            tgl_lahir_sebelumnya = $("#tgl_lahir").val();
        self.data.tgl_lahir = $("#tanggal_lahir").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment(tgl_lahir_sebelumnya).format('D/M/YYYY')).on('changeDate', function (e) {
            $("#tgl_lahir").val(moment(e.date).format('YYYY-MM-DD'));
        });
        $("#tgl_lahir").val(tgl_lahir_sebelumnya);

        var tgl_lulus_sebelumnya = moment().format('YYYY-MM-DD');
        if ($("#tgl_lulus").val())
            tgl_lulus_sebelumnya = $("#tgl_lulus").val();
        self.data.tgl_lulus = $("#tanggal_lulus").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment(tgl_lulus_sebelumnya).format('D/M/YYYY')).on('changeDate', function (e) {
            $("#tgl_lulus").val(moment(e.date).format('YYYY-MM-DD'));
        });
        $("#tgl_lulus").val(tgl_lulus_sebelumnya);

        var tmt_golongan_sebelumnya = moment().format('YYYY-MM-DD');
        if ($("#tmt_golongan").val())
            tmt_golongan_sebelumnya = $("#tmt_golongan").val();
        self.data.tmt_golongan = $("#tgl_tmt_golongan").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment(tmt_golongan_sebelumnya).format('D/M/YYYY')).on('changeDate', function (e) {
            $("#tmt_golongan").val(moment(e.date).format('YYYY-MM-DD'));
        });
        $("#tmt_golongan").val(tmt_golongan_sebelumnya);

        var tmt_fungsional_sebelumnya = moment().format('YYYY-MM-DD');
        if ($("#tmt_jabatan_fungsional").val())
            tmt_fungsional_sebelumnya = $("#tmt_jabatan_fungsional").val();
        self.data.tmt_jabatan_fungsional = $("#tgl_tmt_jabatan_fungsional").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment(tmt_fungsional_sebelumnya).format('D/M/YYYY')).on('changeDate', function (e) {
            $("#tmt_jabatan_fungsional").val(moment(e.date).format('YYYY-MM-DD'));
        });
        $("#tmt_jabatan_fungsional").val(tmt_fungsional_sebelumnya);

        var tmt_struktural_sebelumnya = moment().format('YYYY-MM-DD');
        if ($("#tmt_jabatan_struktural").val())
            tmt_struktural_sebelumnya = $("#tmt_jabatan_struktural").val();
        self.data.tmt_jabatan_fungsional = $("#tgl_tmt_jabatan_struktural").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment(tmt_struktural_sebelumnya).format('D/M/YYYY')).on('changeDate', function (e) {
            $("#tmt_jabatan_struktural").val(moment(e.date).format('YYYY-MM-DD'));
        });
        $("#tmt_jabatan_struktural").val(tmt_struktural_sebelumnya);

        $("#status_menikah").change(function () {
            if ($(this).val() === "1" || !$(this).val())
                $("#btn-tambah-anak").hide();
            else
                $("#btn-tambah-anak").show();
        });

    },
    read_url: function (input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                //alert(e.target.result);
                $('#user_profile').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
};

jQuery(document).ready(function () {
    jQuery.edit_data_pegawai.init();
});
