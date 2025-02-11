jQuery.sertifikat_labkom = {
    data: {},
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        $("#btn-ajukan").click(function (){
            $("#modal-ajukan-sertifikat").modal('show');
        });
        $("#btn-detail").click(function (){
            $("#modal-ajukan-sertifikat").modal('show');
        });
    },
};

jQuery(document).ready(function () {
    jQuery.sertifikat_labkom.init();
});
