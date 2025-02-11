jQuery.create_data_pegawai = {
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
        $("#btn_pilih_foto").click(function (){
            $("#photo_profile").click();
        });
        $("#photo_profile").change(function (){
            self.read_url(this);
        });
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        $("#status_sertifikasi").change(function () {
            if ($(this).val() != "0")
                $("#unggah_sertifikat_area").show()
            else
                $("#unggah_sertifikat_area").hide()
        });

        self.data.tgl_aktif = $("#tanggal_aktif").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment().format('D/M/YYYY')).on('changeDate', function (e) {
            $("#tgl_aktif").val(moment(e.date).format('YYYY-MM-DD'));
        });
        $("#tgl_aktif").val(moment().format('YYYY-MM-DD'));

        self.data.tmt_golongan = $("#tgl_tmt_golongan").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment().format('D/M/YYYY')).on('changeDate', function (e) {
            $("#tmt_golongan").val(moment(e.date).format('YYYY-MM-DD'));
        });
        $("#tmt_golongan").val(moment().format('YYYY-MM-DD'));

        self.data.tmt_jabatan_fungsional = $("#tgl_tmt_jabatan_fungsional").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment().format('D/M/YYYY')).on('changeDate', function (e) {
            $("#tmt_jabatan_fungsional").val(moment(e.date).format('YYYY-MM-DD'));
        });
        $("#tmt_jabatan_fungsional").val(moment().format('YYYY-MM-DD'));

        self.data.tmt_jabatan_struktural = $("#tgl_tmt_jabatan_struktural").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment().format('D/M/YYYY')).on('changeDate', function (e) {
            $("#tmt_jabatan_struktural").val(moment(e.date).format('YYYY-MM-DD'));
        });
        $("#tmt_jabatan_struktural").val(moment().format('YYYY-MM-DD'));

        self.data.tgl_lahir = $("#tanggal_lahir").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment().subtract(17, 'years').format('D/M/YYYY')).on('changeDate', function (e) {
            $("#tgl_lahir").val(moment(e.date).format('YYYY-MM-DD'));
        });
        $("#tgl_lahir").val(moment().subtract(17, 'years').format('YYYY-MM-DD'));

        self.data.tgl_lulus = $("#tanggal_lulus").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment().format('D/M/YYYY')).on('changeDate', function (e) {
            $("#tgl_lulus").val(moment(e.date).format('YYYY-MM-DD'));
        });
        $("#tgl_lulus").val(moment().format('YYYY-MM-DD'));
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
    jQuery.create_data_pegawai.init();
});
