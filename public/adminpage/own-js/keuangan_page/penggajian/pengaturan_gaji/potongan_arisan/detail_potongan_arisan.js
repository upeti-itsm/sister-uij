jQuery.detail_potongan_arisan = {
    data: {
        table: $("#table"),
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
        // Option Data
        $(".select2").select2();

        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/keu/penggajian/pengaturan-gaji/potongan-arisan/detail/json',
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
                        return "<p><b>" + data.nama + "</b><br/>" +
                            "<small>NIK. " + data.nik_mandala + "</small>" +
                            "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                    render: function (data) {
                        return "<p><b>" + data.nama_potongan + "</b><br/>" +
                            "<small>Potongan : " + data.total_potongan_arisan + "</small></p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        return "<button class='btn btn-success btn-edit mr-2' data-id='" + data.id_riwayat_potongan_arisan + "' title='Ubah Nominal Potongan' data-nama='" + data.nama + "' data-nominal='" + data.total_potongan_arisan_ + "'><i class='fas fa-edit'></i></button>" +
                            "<button class='btn btn-danger btn-delete' data-id='" + data.id_riwayat_potongan_arisan + "' title='Hapus Potongan'  data-nama='" + data.nama + "' data-nominal='" + data.total_potongan_arisan + "'><i class='fas fa-trash'></i></button>";
                    }
                },
                {
                    data: 'nama',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                }
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
        $("#table").on('click', 'button.btn-edit', function () {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            var nominal = $(this).data('nominal');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: '<p>' +
                    'Isikan Potongan Terbaru Untuk <b>' + nama + '</b>' +
                    '</p>' +
                    '<div class="form-group">' +
                    '<label>Jumlah Potongan</label>' +
                    '<input type="text" class="form-control potongan" id="changePotongan" onkeyup="keyUpNumber(\'changePotongan\')" placeholder="Jumlah Potongan" value="' + numeral(nominal).format('0,-') + '">' +
                    '</div>',
                buttons: {
                    confirm: {
                        text: 'Simpan',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/keu/penggajian/pengaturan-gaji/potongan-arisan/detail/update',
                                type: 'POST',
                                data: {
                                    id: id,
                                    nominal: numeral(this.$content.find('.potongan').val()).value()
                                },
                                success: function (result) {
                                    if (result.status === 1) {
                                        $.alert({
                                            title: "Informasi",
                                            type: "green",
                                            content: result.keterangan,
                                            backgroundDismissAnimation: 'glow',
                                        });
                                    } else if(result.status === -1) {
                                        $.alert({
                                            title: "Penyalahgunaan Prosedur",
                                            type: "red",
                                            content: result.keterangan,
                                            backgroundDismissAnimation: 'glow',
                                        });
                                    } else {
                                        $.alert({
                                            title: "Peringatan",
                                            type: "red",
                                            content: result.keterangan,
                                            backgroundDismissAnimation: 'glow',
                                        });
                                    }
                                    self.data.table.ajax.reload();
                                }
                            });
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red'
                    }
                },
                backgroundDismissAnimation: 'glow',
                columnClass: 'medium'
            });
        });

        $("#table").on('click', 'button.btn-delete', function () {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            var nominal = $(this).data('nominal');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Apakah anda yakin menghapus potongan ' + nama + ' sebesar ' + nominal + ' ?',
                buttons: {
                    confirm: {
                        text: 'Simpan',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/keu/penggajian/pengaturan-gaji/potongan-arisan/detail/delete',
                                type: 'POST',
                                data: {
                                    id: id,
                                },
                                success: function (result) {
                                    if (result.status === 1) {
                                        $.alert({
                                            title: "Informasi",
                                            type: "green",
                                            content: result.keterangan,
                                            backgroundDismissAnimation: 'glow',
                                        });
                                    } else if(result.status === -1) {
                                        $.alert({
                                            title: "Penyalahgunaan Prosedur",
                                            type: "red",
                                            content: result.keterangan,
                                            backgroundDismissAnimation: 'glow',
                                        });
                                    } else {
                                        $.alert({
                                            title: "Peringatan",
                                            type: "red",
                                            content: result.keterangan,
                                            backgroundDismissAnimation: 'glow',
                                        });
                                    }
                                    self.data.table.ajax.reload();
                                }
                            });
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red'
                    }
                },
                backgroundDismissAnimation: 'glow',
                columnClass: 'medium'
            });
        });
        $("#sub-menu").on('click', 'a', function () {
            location.href = $(this).attr('href');
        });
        $("#btn-filter").click(function () {
            self.data.table.ajax.reload();
        });
        $("#btn-cari-data").click(function () {
            self.data.table.search($("#cari-data").val()).draw();
        });
        $("#cari-data").keyup(function () {
            if (this.value === "") {
                self.data.table.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.table.search(this.value).draw();
            }
        });

        $("#filter_bulan").change(function () {
            self.data.table.ajax.reload();
        });
    },
};

jQuery(document).ready(function () {
    jQuery.detail_potongan_arisan.init();
});
