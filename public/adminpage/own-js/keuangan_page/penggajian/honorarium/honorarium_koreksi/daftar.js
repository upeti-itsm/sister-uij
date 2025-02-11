jQuery.daftar = {
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
        $("#sub-menu").on('click', 'a', function () {
            location.href = $(this).attr('href');
        });
        $("#btn_export_excel").click(function () {
            $.confirm({
                type: 'purple',
                title: '',
                columnClass: 'medium',
                content: '' +
                    '<div class="form-group">' +
                    '<label>Pilih Tahun Akademik</label>' +
                    '<select class="form-control ta">' +
                    '<option value="334">2023/2024 Ganjil</option>' +
                    '<option value="333">2022/2023 Genap</option>' +
                    '<option value="281">2022/2023 Ganjil</option>' +
                    '<option value="283">2021/2022 Genap</option>' +
                    '</select>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>Pilih Format</label>' +
                    '<select class="form-control format">' +
                    '<option value="rekap_internal">Rekap Internal</option>' +
                    '</select>' +
                    '</div>',
                buttons: {
                    export: {
                        text: 'Export',
                        btnClass: 'btn-blue',
                        action: function () {
                            var format = this.$content.find('.format').val();
                            if (!format) {
                                $.alert('Pilih Format Terlebih Dahulu');
                                return false;
                            }
                            location.href = '/keu/penggajian/honorarium/honorarium-koreksi/export/for-rekap/excel/' + this.$content.find('.ta').val();
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
        $("#btn_export_pdf").click(function () {
            $.confirm({
                type: 'purple',
                title: '',
                columnClass: 'medium',
                content: '' +
                    '<div class="form-group">' +
                    '<label>Pilih Tahun Akademik</label>' +
                    '<select class="form-control ta">' +
                    '<option value="334">2023/2024 Ganjil</option>' +
                    '<option value="333">2022/2023 Genap</option>' +
                    '<option value="281">2022/2023 Ganjil</option>' +
                    '<option value="283">2021/2022 Genap</option>' +
                    '</select>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>Pilih Format</label>' +
                    '<select class="form-control format">' +
                    '<option value="rekap_internal">Rekap Internal</option>' +
                    '</select>' +
                    '</div>',
                buttons: {
                    export: {
                        text: 'Export',
                        btnClass: 'btn-blue',
                        action: function () {
                            var format = this.$content.find('.format').val();
                            if (!format) {
                                $.alert('Pilih Format Terlebih Dahulu');
                                return false;
                            }
                            location.href = '/keu/penggajian/honorarium/honorarium-koreksi/export/for-rekap/pdf/' + this.$content.find('.ta').val();
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
        $("#btn_buat_honor").click(function () {
            var dateObj = new Date();
            var bulan_sebelumnya = dateObj.getMonth() - 1;
            var tahun_sebelumnya = dateObj.getFullYear();
            if (bulan_sebelumnya < 0) {
                bulan_sebelumnya = 11
                tahun_sebelumnya = tahun_sebelumnya - 1;
            }
            $.confirm({
                type: 'purple',
                title: '',
                columnClass: 'medium',
                content: 'Apakah anda yakin akan membuat Honorarium Koreksi periode <b>' + self.data.bulan[dateObj.getMonth()] + '</b> tahun <b>' + dateObj.getFullYear() + '</b> ?',
                buttons: {
                    export: {
                        text: 'Yakin',
                        btnClass: 'btn-blue',
                        action: function () {
                            $.ajax({
                                url: '/keu/penggajian/honorarium/honorarium-koreksi/set-honor',
                                method: 'post',
                                data: {},
                                beforeSend: function () {
                                    $("#loading-spin-buat-honor").show();
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
                                },
                                complete: function () {
                                    $("#loading-spin-buat-honor").hide();
                                    self.data.table.ajax.reload();
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

        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/keu/penggajian/honorarium/honorarium-koreksi/json',
                type: 'post',
                data: function (data) {
                    data.bulan = $("#filter_bulan").val();
                    data.tahun = $("#filter_tahun").val();
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
                            "<small>Unit Kerja : " + data.unit_kerja + "<br/>" +
                            "Status : " + data.jenis_karyawan + "</small>" +
                            "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                    render: function (data) {
                        return "<p><b>" + data.keterangan + "</b><br/>" +
                            "<small>Honorarium : " + data.nominal_honor_koreksi + "<br/>" +
                            "Tanggal Dibuat : " + data.tanggal_pembuatan + "</small></p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        if (data.is_repair === false)
                            return "<a href='/keu/penggajian/honorarium/honorarium-koreksi/detail/" + data.id_rekapitulasi_honorarium_koreksi + "' title='Lihat Detail Gaji' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_rekapitulasi_honorarium_koreksi + "'><i class='fas fa-search'></i></a>" +
                                "<a href='/keu/penggajian/honorarium/honorarium-koreksi/slip-gaji/" + data.id_rekapitulasi_honorarium_koreksi + "' title='Print Slip Gaji' class='btn btn-sm btn-info btn-print mr-2' data-id='" + data.id_rekapitulasi_honorarium_koreksi + "'><i class='fas fa-print'></i></a>";
                        else
                            return "<a href='/keu/penggajian/honorarium/honorarium-koreksi/detail/" + data.id_rekapitulasi_honorarium_koreksi + "' title='Lihat Detail Gaji' class='btn btn-sm btn-danger btn-edit mr-2' data-id='" + data.id_rekapitulasi_honorarium_koreksi + "'><i class='fas fa-exclamation-triangle mr-2'></i>Butuh Perbaikan</a>"
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
    jQuery.daftar.init();
});
