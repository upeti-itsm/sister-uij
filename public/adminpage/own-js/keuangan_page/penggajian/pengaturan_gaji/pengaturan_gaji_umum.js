jQuery.pengaturan_gaji_umum = {
    data: {
        table: $("#table"),
        bulan: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        // Option Data
        $(".select2").select2();
        $("#sub-menu").on('click', 'a', function (){
            location.href = $(this).attr('href');
        });
    },
};

jQuery(document).ready(function () {
    jQuery.pengaturan_gaji_umum.init();
});
