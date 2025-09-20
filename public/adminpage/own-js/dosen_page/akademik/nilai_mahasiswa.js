jQuery.daftar_matakuliah = {
    data: {
        table: $("#table"),
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
    },
};

// Initialize when document ready
jQuery(document).ready(function () {
    jQuery.daftar_matakuliah.init();
});
