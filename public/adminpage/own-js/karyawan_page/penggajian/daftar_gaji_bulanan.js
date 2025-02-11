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

        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/kary/penggajian/gaji-bulanan/json',
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
                        return "<p>" + data.periode_pembayaran + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                    render: function (data) {
                        return "<p>" + data.nominal_gaji + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        if (data.available_repair)
                            return "<a href='/kary/penggajian/gaji-bulanan/detail/" + data.id_rekapitulasi_gaji_bulanan_pegawai + "' title='Lihat Detail Gaji' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_rekapitulasi_gaji_bulanan_pegawai + "'><i class='fas fa-search'></i></a>";
                        else
                            return "<a href='/kary/penggajian/gaji-bulanan/slip-gaji/" + data.id_rekapitulasi_gaji_bulanan_pegawai + "' target='_blank' title='Print Slip Gaji' class='btn btn-sm btn-danger btn-print mr-2' data-id='" + data.id_rekapitulasi_gaji_bulanan_pegawai + "'><i class='fas fa-print'></i></a>";
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
    jQuery.daftar_gaji_bulanan.init();
});
