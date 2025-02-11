jQuery.daftar_gaji_bulanan = {
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
        $("#btn_export_excel").click(function () {
            $.confirm({
                type: 'purple',
                title: '',
                columnClass: 'medium',
                content: '' +
                    '<div class="form-group">' +
                    '<label>Pilih Periode</label>' +
                    '<div class="row" style="width: 100%; padding: 0;margin: 0">' +
                    '<div class="col-md-6" style="padding: 0px 5px 0px 0px;margin: 0">' +
                    '<select class="form-control bulan">' +
                    '<option value="-1">--Semua Bulan--</option>' +
                    '<option value="1">Januari</option>' +
                    '<option value="2">Februari</option>' +
                    '<option value="3">Maret</option>' +
                    '<option value="4">April</option>' +
                    '<option value="5">Mei</option>' +
                    '<option value="6">Juni</option>' +
                    '<option value="7">Juli</option>' +
                    '<option value="8">Agustus</option>' +
                    '<option value="9">September</option>' +
                    '<option value="10">Oktober</option>' +
                    '<option value="11">November</option>' +
                    '<option value="12">Desember</option>' +
                    '</select>' +
                    '</div>' +
                    '<div class="col-md-6" style="padding: 0px 0px 0px 5px; margin: 0">' +
                    '<select class="form-control tahun">' +
                    '<option value="2024">2024</option>' +
                    '<option value="2023">2023</option>' +
                    '<option value="2022">2022</option>' +
                    '</select>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>Pilih Format</label>' +
                    '<select class="form-control format">' +
                    '<option value="data_bank">Data Bank</option>' +
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
                                return false;
                            }
                            if (format === "data_bank")
                                location.href = '/keu/penggajian/gaji-bulanan/export/for-bank/excel/' + this.$content.find('.bulan').val() + '/' + this.$content.find('.tahun').val();
                            else if (format === "rekap_internal")
                                location.href = '/keu/penggajian/gaji-bulanan/export/for-rekap/excel/' + this.$content.find('.bulan').val() + '/' + this.$content.find('.tahun').val();
                            else {
                                $.alert('Invalid Format');
                                return false;
                            }
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
                    '<label>Pilih Periode</label>' +
                    '<div class="row" style="width: 100%; padding: 0;margin: 0">' +
                    '<div class="col-md-6" style="padding: 0px 5px 0px 0px;margin: 0">' +
                    '<select class="form-control bulan">' +
                    '<option value="-1">--Semua Bulan--</option>' +
                    '<option value="1">Januari</option>' +
                    '<option value="2">Februari</option>' +
                    '<option value="3">Maret</option>' +
                    '<option value="4">April</option>' +
                    '<option value="5">Mei</option>' +
                    '<option value="6">Juni</option>' +
                    '<option value="7">Juli</option>' +
                    '<option value="8">Agustus</option>' +
                    '<option value="9">September</option>' +
                    '<option value="10">Oktober</option>' +
                    '<option value="11">November</option>' +
                    '<option value="12">Desember</option>' +
                    '</select>' +
                    '</div>' +
                    '<div class="col-md-6" style="padding: 0px 0px 0px 5px;margin: 0">' +
                    '<select class="form-control tahun">' +
                    '<option value="2024">2024</option>' +
                    '<option value="2023">2023</option>' +
                    '</select>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>Pilih Format</label>' +
                    '<select class="form-control format">' +
                    '<option value="data_bank">Data Bank</option>' +
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
                            if (format === "data_bank")
                                location.href = '/keu/penggajian/gaji-bulanan/export/for-bank/pdf/' + this.$content.find('.bulan').val() + '/' + this.$content.find('.tahun').val();
                            else if (format === "rekap_internal")
                                location.href = '/keu/penggajian/gaji-bulanan/export/for-rekap/pdf/' + this.$content.find('.bulan').val() + '/' + this.$content.find('.tahun').val();
                            else {
                                $.alert('Invalid Format');
                                return false;
                            }
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
        $("#btn_permanen").click(function () {
            var dateObj = new Date();
            $.confirm({
                type: 'purple',
                title: '',
                columnClass: 'medium',
                content: '' +
                    'Apakah anda yakin tidak ada perubahan data pada gaji periode pembayaran <b>' + self.data.bulan[dateObj.getMonth()] + '</b> tahun <b>' + dateObj.getFullYear() + '</b> ?<hr/>' +
                    '<span class="text-danger">Setelah permanen, anda tidak dapat merubah data gaji</span>',
                buttons: {
                    yakin: {
                        text: 'Yakin',
                        btnClass: 'btn-blue',
                        action: function () {

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
        $("#btn_buat_gaji").click(function () {
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
                columnClass: 'large',
                content: '<h3>Apakah anda yakin akan membuat Gaji Bulanan periode <b>' + self.data.bulan[dateObj.getMonth()] + '</b> tahun <b>' + dateObj.getFullYear() + '</b> ? <h1>PASTIKAN SEMUA POTONGAN SUDAH TERISI</h1><hr/>' +
                    '<span class="text-danger">Perhitungan Gaji akan didasarkan pada data kinerja bulan <b>' + self.data.bulan[bulan_sebelumnya] + '</b> tahun <b>' + tahun_sebelumnya + '</b></span></h3>',
                buttons: {
                    export: {
                        text: 'Yakin',
                        btnClass: 'btn-blue',
                        action: function () {
                            $.ajax({
                                url: '/keu/penggajian/gaji-bulanan/set-rekapitulasi',
                                method: 'post',
                                data: {},
                                beforeSend: function () {
                                    $("#loading-spin-buat-gaji").show();
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
                                    $("#loading-spin-buat-gaji").hide();
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
                url: '/keu/penggajian/gaji-bulanan/json',
                type: 'post',
                data: function (data) {
                    data.bulan = $("#filter_bulan").val();
                    data.tahun = $("#filter_tahun").val();
                    data.is_repair = $("#filter_repair").val();
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
                        return "<p><b>" + data.periode_pembayaran + "</b><br/>" +
                            "<small>Gaji Bersih : " + data.nominal_gaji + "<br/>" +
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
                            return "<a href='/keu/penggajian/gaji-bulanan/detail/" + data.id_rekapitulasi_gaji_bulanan_pegawai + "' title='Lihat Detail Gaji' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_rekapitulasi_gaji_bulanan_pegawai + "'><i class='fas fa-search'></i></a>" +
                                "<a href='/keu/penggajian/gaji-bulanan/slip-gaji/" + data.id_rekapitulasi_gaji_bulanan_pegawai + "' title='Print Slip Gaji' class='btn btn-sm btn-info btn-print mr-2' data-id='" + data.id_rekapitulasi_gaji_bulanan_pegawai + "'><i class='fas fa-print'></i></a>";
                        else
                            return "<a href='/keu/penggajian/gaji-bulanan/detail/" + data.id_rekapitulasi_gaji_bulanan_pegawai + "' title='Lihat Detail Gaji' class='btn btn-sm btn-danger btn-edit mr-2' data-id='" + data.id_rekapitulasi_gaji_bulanan_pegawai + "'><i class='fas fa-exclamation-triangle mr-2'></i>Butuh Perbaikan</a>"
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

        $("#filter_repair").change(function () {
            self.data.table.ajax.reload();
        });
    },
};

jQuery(document).ready(function () {
    jQuery.daftar_gaji_bulanan.init();
});
