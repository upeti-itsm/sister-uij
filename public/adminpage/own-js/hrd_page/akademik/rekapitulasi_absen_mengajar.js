jQuery.rekapitulasi_absen_mengajar = {
    data: {
        table: $("#table"),
        tgl_awal: $("#tgl_awal"),
        tgl_akhir: $("#tgl_akhir")
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        // Option Data
        $(".select2").select2();
        // Tanggal
        self.data.tgl_awal = $("#tgl_awal").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment().subtract(1, 'months').format('D/M/YYYY')).on('changeDate', function (e) {
            self.data.tgl_akhir.datepicker('setStartDate', moment(e.date).format('D/M/YYYY'));
            self.data.tgl_akhir.datepicker('setDate', moment(e.date).add(1, 'months').format('D/M/YYYY'));
            self.data.table.ajax.reload();
        });

        self.data.tgl_akhir = $("#tgl_akhir").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            startDate: moment().format('D/M/YYYY'),
            orientation: 'bottom'
        }).datepicker('setDate', moment().format('D/M/YYYY')).on('changeDate', function (e) {
            self.data.table.ajax.reload();
        });
        // Table With DataTable
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/hrd/akademik/rekapitulasi-absen-mengajar/detail/json',
                type: 'post',
                data: function (data) {
                    data.tgl_awal = moment(self.data.tgl_awal.datepicker('getDate')).format('YYYY-MM-DD');
                    data.tgl_akhir = moment(self.data.tgl_akhir.datepicker('getDate')).format('YYYY-MM-DD');
                    data.id_personal = $("#id_personal").val();
                }
            },
            fnDrawCallback: function () {
                var rows = this.fnGetData();
                if (rows.length === 0) {
                    $("#tot_reg_p").text("0");
                    $("#tot_reg_m").text("0");
                    $("#tot_tepat").text("0");
                    $("#tot_terlambat").text("0");
                } else {
                    $("#tot_reg_p").text(rows[0].jml_reg_p);
                    $("#tot_reg_m").text(rows[0].jml_reg_m);
                    $("#tot_tepat").text(rows[0].jml_tepat_waktu);
                    $("#tot_terlambat").text(rows[0].jml_terlambat);
                }
            },
            scrollY: '500px',
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
                    width: "35%",
                    render: function (data) {
                        var keterangan = "";
                        if (data.keterangan)
                            keterangan = "<br/><small>Keterangan : " + data.keterangan + "</small>";
                        return "<b>" + data.fullname + " </b><br/>" +
                            "<small>" + data.tanggal_absen + "</small>" + keterangan;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        return "<small>Tanggal : " + data.tgl_pelaksanaan_ + "</small><br/>" +
                            "<small>" + data.waktu_mengajar + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        return "<small>Pertemuan Ke-" + data.pertemuan_ke + "</small><br/>" +
                            "<small>Materi : <br/>" + data.materi + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "10%",
                    render: function (data) {
                        var html = "<span class='badge badge-success m-2'>Tepat Waktu</span>";
                        if (data.status_absen === "Terlambat")
                            html = "<span class='badge badge-danger m-2'>Terlambat</span>"
                        return html;
                    }
                },
                {
                    data: 'fullname',
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
    },
};

jQuery(document).ready(function () {
    jQuery.rekapitulasi_absen_mengajar.init();
});
