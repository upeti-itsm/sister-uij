jQuery.tugas_akhir = {
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
        // Option Data
        $(".select2").select2();
    },
};

jQuery(document).ready(function () {
    jQuery.tugas_akhir.init();
});
