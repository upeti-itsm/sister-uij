jQuery.daftar_umr = {
    data: {
        table: $("#table"),
        number: $(".number"),
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
        // Option Data
        $(".select2").select2();
        $("#btn_add_data_umr").click(function () {
            $("#modal-tambah-data-umr").modal('show');
        });
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/keu/penggajian/pengaturan-gaji/pengaturan-umr/json',
                type: 'post',
                data: function (data) {
                    data.bulan = $("#bulan").val();
                    data.tahun = $("#tahun").val();
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'nomor',
                    searchable: false,
                    sClass: 'text-center',
                    width: "5%"
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return "<p><b>" + data.nominal_upah_minimum_regional_ + "</b>" +
                            "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                    render: function (data) {
                        return "<p><b>" + data.bulan_tahun + "</b><br/>" +
                            "<small>Tanggal Dibuat : " + data.tgl_created_ + "</small></p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        return "<a class='btn btn-primary btn-block' href='/keu/penggajian/pengaturan-gaji/pengaturan-umr/detail/" + data.id_upah_minimum_regional + "'><i class='fas fa-eye mr-2'></i>Detail</a>";
                    }
                },
                {
                    data: 'bulan_tahun',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
            ],
            paging: true,
            processing: true,
            pageLength: 10,
            ordering: false,
            lengthChange: false,
            autoWidth: false,
            sDom: 'ltipr',
            language: {
                "emptyTable": "Tidak ditemukan data"
            }
        });
        $("#sub-menu").on('click', 'a', function () {
            location.href = $(this).attr('href');
        });
        $("#bulan").change(function () {
            self.data.table.ajax.reload();
        });
        $("#tahun").change(function () {
            self.data.table.ajax.reload();
        });
        $("#btn-simpan-data").click(function () {
            if ($("#add_nilai_umr").val()) {
                $.confirm({
                    title: 'Konfirmasi !',
                    type: 'orange',
                    backgroundDismissAnimation: 'glow',
                    content: 'Apakah anda yakin akan menambahkan UMR ? <b style="color: red">UMR Sebelumnya akan non-aktif</b>',
                    buttons: {
                        confirm: {
                            text: 'Yakin',
                            btnClass: 'btn-green',
                            keys: ['enter'],
                            action: function () {
                                $.ajax({
                                    url: '/keu/penggajian/pengaturan-gaji/pengaturan-umr/insert',
                                    type: 'POST',
                                    data: {
                                        nilai_umr: numeral($("#add_nilai_umr").val()).value()
                                    },
                                    success: function (response) {
                                        if (response.status === 1) {
                                            $.alert({
                                                title: 'Informasi',
                                                type: 'green',
                                                content: response.keterangan,
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
                                        $("#modal-tambah-data-umr").modal('hide');
                                        self.data.table.ajax.reload();
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Batal',
                            btnClass: 'btn-red'
                        }
                    }
                });
            } else {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Nilai UMR Diisi",
                    backgroundDismissAnimation: 'glow'
                });
            }
        });
        $("#add_nilai_umr").change(function (){
            if (numeral($(this).val()).value() > 0){
                $("#btn-simpan-data").removeClass('btn-secondary disabled');
                $("#btn-simpan-data").addClass('btn-primary');
            }
        });
    },
};

jQuery(document).ready(function () {
    jQuery.daftar_umr.init();
});
