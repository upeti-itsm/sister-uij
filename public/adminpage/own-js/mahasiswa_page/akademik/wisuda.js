jQuery.wisuda = {
    data: {},
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        $("#btn-ajukan").click(function (){
            $("#modal-ajukan-pendaftaran").modal('show');
        });
        $("#btn-detail").click(function (){
            $("#modal-ajukan-pendaftaran").modal('show');
        });
        $("#modal-btn-ajukan").click(function (){
            $("#form-pendaftaran-wisuda").submit();
        });
    },
};

jQuery(document).ready(function () {
    jQuery.wisuda.init();
});
