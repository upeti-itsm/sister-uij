jQuery.buku_tamu = {
    data: {
        table: $("#table"),
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        $("#barcode").focus();
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/wisuda/buku-tamu/json',
                type: 'post',
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
                    data: 'nim',
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%"
                },
                {
                    data: 'nama',
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%",
                },
                {
                    data: 'jenis_tamu',
                    searchable: false,
                    sClass: 'text-left',
                    width: "15%"
                },
                {
                    data: 'waktu_hadir',
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%"
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
        $("#barcode_manual").keypress(function (event){
            if (event.keyCode === 13) {
                $("#form-tamu").submit();
            }
        });
        $("#barcode").keypress(function (event) {
            if (event.keyCode === 13) {
                $("#form-tamu").submit();
            }
        });
    },
};

jQuery(document).ready(function () {
    jQuery.buku_tamu.init();
});
