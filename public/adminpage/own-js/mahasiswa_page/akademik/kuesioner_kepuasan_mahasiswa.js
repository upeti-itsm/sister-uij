jQuery.kuesioner_kepuasan_mahasiswa = {
    data: {},
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        $('.f1 fieldset:first').fadeIn('slow');
        // next step
        $('.f1 .btn-next').on('click', function () {
            var parent_fieldset = $(this).parents('fieldset');
            var pernyataan = [];
            var jawaban = [];
            var next_step = true;
            parent_fieldset.find('input').each(function () {
                if (!pernyataan.includes($(this)[0].name))
                    pernyataan.push($(this)[0].name);
            });
            $.each(pernyataan, function (index, value){
                parent_fieldset.find('input[name="' + value + '"]').each(function (){
                    if ($(this).prop('checked'))
                        jawaban.push($(this).val());
                })
            });
            if (pernyataan.length !== jawaban.length)
                next_step = false;
            if (next_step) {
                parent_fieldset.fadeOut(400, function () {
                    // show next step
                    $(this).next().fadeIn();
                    // scroll window to beginning of the form
                    self.scroll_to_class($('.f1'), 20);
                });
            } else {
                $.alert({
                    title: 'Informasi',
                    type: 'red',
                    content: "Pastikan Semua Pertanyaan Terisi",
                    backgroundDismissAnimation: 'glow'
                });
            }
        });

        // previous step
        $('.f1 .btn-previous').on('click', function () {
            $(this).parents('fieldset').fadeOut(400, function () {
                $(this).prev().fadeIn();
                // scroll window to beginning of the form
                self.scroll_to_class($('.f1'), 20);
            });
        });

        // submit
        $(document).on('click', 'form button[type=submit]', function (e) {
            var isValid = $(e.target).parents('form').isValid();
            if (!isValid) {
                e.preventDefault(); //prevent the default action
                $.alert({
                    title: 'Informasi',
                    type: 'red',
                    content: "Pastikan Semua Pertanyaan Sudah Dijawab",
                    backgroundDismissAnimation: 'glow'
                });
            }
        });
    },
    scroll_to_class: function (element_class, removed_height) {
        var scroll_to = $(element_class).offset().top - removed_height;
        if ($(window).scrollTop() !== scroll_to) {
            $('html, body').stop().animate({scrollTop: scroll_to}, 0);
        }
    },
};

jQuery(document).ready(function () {
    jQuery.kuesioner_kepuasan_mahasiswa.init();
});
