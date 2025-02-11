jQuery.detail_maba = {
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
        $("#prodi").change(function () {
            self.data.table_data_center.ajax.reload();
        });
        self.data.table_data_center = $("#table-data-center").DataTable({
            serverSide: true,
            ajax: {
                url: '/rek/dashboard/detail-mahasiswa-baru/json',
                type: 'post',
                data: function (data) {
                    data.prodi = $("#prodi").val();
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
                        return "<b>" + data.nama_lengkap + " (" + data.nim + ")</b><br/>" +
                            "<small>Tempat Lahir : " + data.tempat_lahir + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "45%",
                    render: function (data) {
                        return data.nama_seleksi + "<br/>" +
                            "<small>Tahun Seleksi : " + data.tahun_seleksi + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        return "<button class='btn btn-primary-soft'>" + data.jenjang_didik + " " + data.nama_prodi + "</button>";
                    }
                },
                {
                    data: 'nama_lengkap',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'nomor_pendaftaran',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'nisn',
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
    jQuery.detail_maba.init();
});
