jQuery.detail_karyawan = {
    data: {
        table_data_center: $("#table-data-center"),
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
        $("#status_kehadiran").change(function () {
            self.data.table_data_center.ajax.reload();
        });
        self.data.table_data_center = $("#table-data-center").DataTable({
            serverSide: true,
            ajax: {
                url: '/rek/dashboard/detail-karyawan/json',
                type: 'post',
                data: function (data) {
                    data.status_kehadiran = $("#status_kehadiran").val();
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
                    width: "30%",
                    render: function (data) {
                        return "<b>" + data.nama + "</b>";
                    }
                },
                {
                    data: 'unit_kerja',
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "25%",
                    render: function (data) {
                        return "<button class='btn btn-primary-soft'>" + data.status_kehadiran + " ( "+data.waktu_absen+" )</button>";
                    }
                },
                {
                    data: 'nama',
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
        $("#btn-filter-data-center").click(function () {
            self.data.table_data_center.ajax.reload();
        });
        $("#btn-cari-data-data-center").click(function () {
            self.data.table_data_center.search($("#cari-data-data-center").val()).draw();
        });
        $("#cari-data-data-center").keyup(function () {
            if (this.value === "") {
                self.data.table_data_center.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.table.search(this.value).draw();
            }
        });
    },
};

jQuery(document).ready(function () {
    jQuery.detail_karyawan.init();
});
