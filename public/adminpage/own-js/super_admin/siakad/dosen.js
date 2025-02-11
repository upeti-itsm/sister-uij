jQuery.dosen = {
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
                url: '/super-admin/siakad/json/daftar-dosen',
                type: 'post'
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
                        return "<b>" + data.gelardepan + " " + data.namanongelar + data.gelarbelakang + " ( " + data.nidn + " )</b>";
                    }
                },
                {
                    data: 'nidn',
                    searchable: false,
                    sClass: 'text-left',
                    width: "15%",
                },
                {
                    data: 'namaprogramstudi',
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                },
                {
                    data: 'namanongelar',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'nidn',
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
    jQuery.dosen.init();
});
