jQuery.detail_gaji_bulanan = {
    data: {
        number: $(".number"),
        bulan: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        $("#btn-ajukan-perbaikan").click(function () {
            var id = $(this).data('id_rekap');
            $.confirm({
                type: 'purple',
                title: '',
                columnClass: 'medium',
                content: '' +
                    '<div class="form-group">' +
                    '<label>Masukkan Alasan Perbaikan</label>' +
                    '<textarea class="form-control keterangan" placeholder="Masukkan detail alasan perbaikan">' +
                    '</textarea>' +
                    '</div>',
                buttons: {
                    export: {
                        text: 'Submit Ajuan',
                        btnClass: 'btn-blue',
                        action: function () {
                            var keterangan = this.$content.find('.keterangan').val();
                            if (!keterangan) {
                                $.alert('Pastikan alasan perbaikan terisi');
                                return false;
                            }
                            $.ajax({
                                url: '/kary/penggajian/gaji-bulanan/ajukan-perbaikan',
                                method: 'post',
                                data: {
                                    'id': id,
                                    'keterangan': keterangan
                                },
                                beforeSend: function () {
                                    $("#loading-spin-ajukan-perbaikan").show();
                                },
                                success: function (response) {
                                    if (response.status === 1) {
                                        $.alert({
                                            title: 'Informasi',
                                            type: 'green',
                                            content: response.keterangan,
                                            buttons: {
                                                ok: {
                                                    text: 'OK',
                                                    btnClass: 'btn-blue',
                                                    action: function () {
                                                        location.reload();
                                                    }
                                                }
                                            },
                                            backgroundDismissAnimation: 'glow'
                                        });
                                    } else {
                                        $.alert({
                                            title: 'Informasi',
                                            type: 'red',
                                            content: response.keterangan,
                                            backgroundDismissAnimation: 'glow'
                                        });
                                    }
                                },
                                complete: function () {
                                    $("#loading-spin-ajukan-perbaikan").hide();
                                }
                            });
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red',
                    },
                },
                backgroundDismissAnimation: 'glow'
            });
        });
    },
};

jQuery(document).ready(function () {
    jQuery.detail_gaji_bulanan.init();
});
