jQuery.tata_pamong = {
    data: {
        table: $("#table"),
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        // Option Data
        $(".select2").select2();
        // Table With DataTable
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/bpm/insup-tata-pamong-json',
                type: 'post',
                data: function (data) {
                    data.tahun_terbit = $("#tahun_terbit").val();
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'nomor',
                    searchable: false,
                    sClass: 'text-center',
                    width: "5%",
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                    render: function (data) {
                        return "<b>" + data.nama_dokumen + "</b><br/>" +
                            "<small>Nomor Dokumen: " + data.no_dokumen + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                    render: function (data) {
                        return "<b>" + data.tahun_terbit + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%",
                    render: function (data) {
                        return "<p>" + data.deskripsi + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        return "<a href='" + data.link_dokumen + "' target='_blank'>Bukti</a><br/>" +
                            "<p>" + data.level_institusi + "</p>";
                    }
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

        $("#btn-add-document").click(function () {
            $("#form_collapse").collapse('show');
            $("#filter-collapse").collapse('hide');
        });

        $("#btn-cancel").click(function () {
            $("#form_collapse").collapse('hide');
            $("#filter-collapse").collapse('show');
        });
        $("#btn-save").click(function () {
            if ($("#nomor_dokumen").val() && $("#nama_dokumen").val() && $("#tahun_terbit").val() && $("#deskripsi").val() && $("#link_dokumen").val()) {
                $.confirm({
                    type: 'purple',
                    title: '',
                    columnClass: 'large',
                    content: 'Apakah anda yakin akan menambahkan data dokumen tata pamong ?',
                    buttons: {
                        export: {
                            text: 'Yakin',
                            btnClass: 'btn-blue',
                            action: function () {
                                $("#form-tata-pamong").submit();
                            }
                        },
                        cancel: {
                            text: 'Batal',
                            btnClass: 'btn-red',
                        },
                    },
                    backgroundDismissAnimation: 'glow'
                });
            } else {
                $.alert({
                    title: 'Peringatan',
                    type: 'red',
                    content: 'Pastikan Semua Form Terisi',
                    backgroundDismissAnimation: 'glow'
                });
            }
        });
    },
};

jQuery(document).ready(function () {
    jQuery.tata_pamong.init();
});
