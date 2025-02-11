jQuery.index = {
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
                url: '/hrd/sarpras-json',
                type: 'post',
                data: function (data) {
                    data.jenis_sarpras = $("#jenis_prasarana").val();
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%",
                    render: function (data) {
                        return "<b>" + data.nama_sarana_prasarana + "</b><br/>" +
                            "<small>Jenis : " + data.jenis_prasarana + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "15%",
                    render: function (data) {
                        return "Jumlah : <b>" + data.jumlah_sarana_prasarana + "</b><br/>" +
                            "Luas : <b>" + data.luas_sarana_prasarana + " m<sup>2</sup></b>";
                    }
                },
                {
                    data: 'keterangan',
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%",
                },
                {
                    data: 'nama_sarana_prasarana',
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
        $("#btn-export-pdf").click(function () {
            window.open('/dosen/akademik/daftar-matakuliah/export-pdf/' + $("#tahun_akademik").val() + '/' + $("#cari-data").val());
        });
    },
};

jQuery(document).ready(function () {
    jQuery.index.init();
});
