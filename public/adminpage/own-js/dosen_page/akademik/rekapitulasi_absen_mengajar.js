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
                url: '/dosen/akademik/rekapitulasi-absen-mengajar/json',
                type: 'post',
                data: function (data) {
                    data.tgl_awal = moment(self.data.tgl_awal.datepicker('getDate')).format('YYYY-MM-DD');
                    data.tgl_akhir = moment(self.data.tgl_akhir.datepicker('getDate')).format('YYYY-MM-DD');
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
                    width: "25%",
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
                    width: "20%",
                    render: function (data) {
                        var btn = "<button class='btn btn-primary btn-sm btn-block btn-delete' data-id='" + data.id_rekapitulasi_absensi_mengajar_dosen + "'><span class='spinner-border spinner-border-sm mr-2' id='delete-loading-spin-" + data.id_rekapitulasi_absensi_mengajar_dosen + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash mr-2'></i>Hapus</button>"
                        var html = "<button class='btn btn-success-soft btn-sm btn-block' style='cursor: default'>Tepat Waktu</button>";
                        if (data.status_absen === "Terlambat")
                            html = "<button class='btn btn-danger-soft btn-sm btn-block' style='cursor: default'>Terlambat</button>"
                        return html + btn;
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
        $("#export-to-pdf").click(function () {
            window.open('/dosen/akademik/rekapitulasi-absen-mengajar/export-pdf/' + moment(self.data.tgl_awal.datepicker('getDate')).format('YYYY-MM-DD') + '/' + moment(self.data.tgl_akhir.datepicker('getDate')).format('YYYY-MM-DD') + '/' + $("#cari-data").val());
        });
        $("#export-to-excel").click(function () {
            window.open('/dosen/akademik/rekapitulasi-absen-mengajar/export-excel/' + moment(self.data.tgl_awal.datepicker('getDate')).format('YYYY-MM-DD') + '/' + moment(self.data.tgl_akhir.datepicker('getDate')).format('YYYY-MM-DD'));
        });
        $("#table").on('click', 'button.btn-delete', function () {
            var id = $(this).data('id');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin akan menghapus data ini ? dalam waktu satu minggu hanya diperkenankan menghapus 3 data. <b>Data yang terhapus tidak dapat dikembalikan lagi</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/dosen/akademik/rekapitulasi-absen-mengajar/delete',
                                method: 'POST',
                                data: {
                                    id_rekap: id
                                },
                                beforeSend: function () {
                                    $("#delete-loading-spin-" + id).show();
                                },
                                success: function (response) {
                                    if (response.status === "1") {
                                        $.alert({
                                            title: "Informasi",
                                            type: "green",
                                            content: response.keterangan
                                        });
                                    } else {
                                        $.alert({
                                            title: "Peringatan",
                                            type: "red",
                                            content: response.keterangan
                                        })
                                    }
                                },
                                complete: function (){
                                    $("#delete-loading-spin-" + id).hide();
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
        });
    },
};

jQuery(document).ready(function () {
    jQuery.rekapitulasi_absen_mengajar.init();
});
