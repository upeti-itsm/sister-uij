jQuery.mahasiswa = {
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
                url: '/super-admin/siakad/json/daftar-mahasiswa',
                type: 'post',
                data: function (data) {
                    data.angkatan = $("#angkatan").val();
                    data.status = $("#status").val();
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "60%",
                    render: function (data) {
                        return "<p><b>" + data.nama + " ( " + data.nim + " )</b><br/>" +
                            "<small>Fak. : " + data.namafakultas + "</small><br/>" +
                            "<small>Seleksi: "+data.seleksi+"</small></p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return "<b>" + data.namaprogramstudi + " (" + data.thnakademikmasuk + ")</b><br/>" +
                            "<small>Nomor HP : " + data.nohp + "</small><br/>" +
                            "<small>Alamat : " + data.alamattinggal + "</small>";
                    }
                },
                {
                    data: 'nama',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'nim',
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
    jQuery.mahasiswa.init();
});
