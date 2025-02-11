jQuery.detail_jadwal_kuliah = {
    data: {},
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        $(".select2").select2();
        $("#asisten").change(function () {
            $("#koordinator").val(($(this).val()));
            $("#dosen").prop("checked", !$(this).prop('checked'));
        });
        $("#dosen").change(function () {
            $("#koordinator").val(($(this).val()));
            $("#asisten").prop("checked", !$(this).prop('checked'));
        });
        $("#status_pengajar").change(function () {
            $("#jenis_jadwal").val($(this).val());
            if ($(this).val() === "3") {
                $("#koordinator").val("");
                $("#dosen").prop("checked", false);
                $("#dosen").prop("disabled", true);
                $("#asisten").prop("checked", false);
                $("#asisten").prop("disabled", true);
            } else if ($(this).val() === "2") {
                $("#dosen").prop("checked", false);
                $("#dosen").prop("disabled", false);
                $("#asisten").prop("checked", true).trigger('change');
                $("#asisten").prop("disabled", false);
            }
        });
        $("#btn-simpan").click(function (){
            $("#submit-form").submit();
        });
    },
};

jQuery(document).ready(function () {
    jQuery.detail_jadwal_kuliah.init();
});
