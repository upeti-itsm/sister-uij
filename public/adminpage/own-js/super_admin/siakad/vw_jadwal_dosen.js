jQuery.vw_jadwal_dosen = {
    data: {},
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        // Option Data
        $(".select2").select2();
        // Table With DataTable
        var table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/super-admin/siakad/json/daftar-jadwal-dosen',
                type: 'post',
                data: function (data) {
                    data.tahun = $("#tahun_akademik").val();
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'dosen_pengampuh',
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%"
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%",
                    render: function (data) {
                        return "<b>" + data.namamatakuliah + " (" + data.namakelas + ")</b><br/>" +
                            "Kode Matkul : " + data.kdmatakuliah;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return "<b>" + data.hari + ", (" + data.jamperkuliahan + ")</b><br/>" +
                            "SKS : " + data.sks;
                    }
                },
                {
                    data: 'namamatakuliah',
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
        $("#btn-filter").click(function () {
            table.ajax.reload();
        });
        $("#btn-cari-data").click(function () {
            table.search($("#cari-data").val()).draw();
        });
        $("#cari-data").keyup(function () {
            if (this.value === "") {
                table.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                table.search(this.value).draw();
            }
        });
    },
};

jQuery(document).ready(function () {
    jQuery.vw_jadwal_dosen.init();
});
