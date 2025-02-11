jQuery.form_ubah_data_diri = {
    data: {
        tgl_lahir: $("#tanggal_lahir"),
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        // Option Data
        $(".select2").select2();
        $("#btn_pilih_foto").click(function (){
            $("#photo_profile").click();
        });
        $("#photo_profile").change(function (){
            var data = new FormData();
            data.append('id', $("#id_personal").val());
            $.each($("#photo_profile")[0].files, function (i, file) {
                data.append('file', file);
            });
            $.ajax({
                url: '/kary/data-kepegawaian/data-diri/update-path-photo',
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
        var tanggal_lahir = moment().format('YYYY-MM-DD');
        if ($("#tgl_lahir").val())
            tanggal_lahir = $("#tgl_lahir").val();
        self.data.tgl_lahir = $("#tanggal_lahir").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment(tanggal_lahir).format('D/M/YYYY')).on('changeDate', function (e) {
            $("#tgl_lahir").val(moment(e.date).format('YYYY-MM-DD'));
        });
    },
    read_url: function (input){
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
    jQuery.form_ubah_data_diri.init();
});
