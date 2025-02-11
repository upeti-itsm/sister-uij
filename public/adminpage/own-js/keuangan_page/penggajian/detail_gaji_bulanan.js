jQuery.detail_gaji_bulanan = {
    data: {
        number: $(".number"),
        bulan: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
    },
    init: function () {
        var self = this;
        numeral.register('locale', 'id', {
            delimiters: {
                thousands: '.',
                decimal: ','
            },
            abbreviations: {
                thousand: 'k',
                million: 'm',
                billion: 'b',
                trillion: 't'
            },
            ordinal: function (number) {
                return number === 1 ? 'er' : 'ème';
            },
            currency: {
                symbol: 'Rp.'
            }
        });
        numeral.locale('id');
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        self.data.number.keyup(function () {
            var $this = $(this);
            var input = $this.val();
            input = input.replace(/[\D\s\\._\-]+/g, "");
            input = input ? parseInt(input, 10) : 0;
            $this.val(function () {
                return input.toLocaleString("id-ID");
            });
        });
        $("#total_nominal_gaji").text(numeral($("#total_nominal_gaji").text()).format('$ 0,-'));
        $("#gaji_pokok").val(numeral($("#gaji_pokok").val()).format('0,-'));

        $("#nominal_total_tunjangan").text(numeral($("#nominal_total_tunjangan").text()).format('$ 0,-'));
        $("#tunjangan_struktural").val(numeral($("#tunjangan_struktural").val()).format('0,-'));
        $("#tunjangan_jamsos").val(numeral($("#tunjangan_jamsos").val()).format('0,-'));
        $("#tunjangan_fungsional").val(numeral($("#tunjangan_fungsional").val()).format('0,-'));
        $("#tunjangan_transport").val(numeral($("#tunjangan_transport").val()).format('0,-'));
        $("#tunjangan_kinerja").val(numeral($("#tunjangan_kinerja").val()).format('0,-'));
        $("#tunjangan_masa_kerja").val(numeral($("#tunjangan_masa_kerja").val()).format('0,-'));
        $("#nominal_insentif_lembur").val(numeral($("#nominal_insentif_lembur").val()).format('0,-'));
        $("#nominal_tunjangan_keluarga").val(numeral($("#nominal_tunjangan_keluarga").val()).format('0,-'));
        $("#nominal_tunjangan_pendidikan").val(numeral($("#nominal_tunjangan_pendidikan").val()).format('0,-'));
        $("#nominal_tunjangan_beras").val(numeral($("#nominal_tunjangan_beras").val()).format('0,-'));

        $("#nominal_total_potongan").text(numeral($("#nominal_total_potongan").text()).format('$ 0,-'));
        $("#potongan_infaq").val(numeral($("#potongan_infaq").val()).format('0,-'));
        $("#potongan_koperasi").val(numeral($("#potongan_koperasi").val()).format('0,-'));
        $("#potongan_arisan").val(numeral($("#potongan_arisan").val()).format('0,-'));
        $("#potongan_beras").val(numeral($("#potongan_beras").val()).format('0,-'));
        $("#potongan_asuransi").val(numeral($("#potongan_asuransi").val()).format('0,-'));
        $("#potongan_dplk").val(numeral($("#potongan_dplk").val()).format('0,-'));
        $("#potongan_qurban").val(numeral($("#potongan_qurban").val()).format('0,-'));
        $("#potongan_lainnya").val(numeral($("#potongan_lainnya").val()).format('0,-'));
        $("#potongan_paguyuban_cooper").val(numeral($("#potongan_paguyuban_cooper").val()).format('0,-'));
        $('.input-daterange input').each(function() {
            $(this).datepicker('clearDates');
        });
    },
};

jQuery(document).ready(function () {
    jQuery.detail_gaji_bulanan.init();
});
