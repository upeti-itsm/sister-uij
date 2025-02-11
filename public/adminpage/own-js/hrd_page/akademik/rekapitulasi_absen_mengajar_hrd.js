jQuery.rekapitulasi_absen_mengajar_hrd = {
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
                url: '/hrd/akademik/rekapitulasi-absen-mengajar/json',
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
                    width: "40%",
                    render: function (data) {
                        return data.nama_dosen;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        return "<span class='badge badge-info-soft p-2'>" + data.jml_reg_p + " </span>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        return "<span class='badge badge-info-soft p-2'>" + data.jml_reg_m + " </span>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "25%",
                    render: function (data) {
                        return "<a href='/hrd/akademik/rekapitulasi-absen-mengajar/detail/" + data.id_personal + "' class='btn btn-sm btn-info mr-2'><i class='fas fa-user-cog mr-2'></i>Detail</a>" +
                            "<button data-id_personal='" + data.id_personal + "' class='btn btn-sm btn-export-detail btn-success'><i class='fas fa-file-excel mr-2'></i>Export</button>";
                    }
                },
                {
                    data: 'nama_dosen',
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
        $("#id_personal").change(function () {
            self.data.table.ajax.reload();
        });
        $("#btn-export-excel").click(function () {
            window.open('/hrd/akademik/rekapitulasi-absen-mengajar/export/' + moment(self.data.tgl_awal.datepicker('getDate')).format('YYYY-MM-DD') + '/' + moment(self.data.tgl_akhir.datepicker('getDate')).format('YYYY-MM-DD'));
        });
        self.data.table.on('click','button.btn-export-detail', function (){
            var id_personal = $(this).data('id_personal');
            window.open('/hrd/akademik/rekapitulasi-absen-mengajar/detail/export/' + moment(self.data.tgl_awal.datepicker('getDate')).format('YYYY-MM-DD') + '/' + moment(self.data.tgl_akhir.datepicker('getDate')).format('YYYY-MM-DD') + '/' + id_personal);
        });
    },
};

jQuery(document).ready(function () {
    jQuery.rekapitulasi_absen_mengajar_hrd.init();
});
