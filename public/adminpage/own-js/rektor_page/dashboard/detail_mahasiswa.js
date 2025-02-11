jQuery.detail_mahasiswa = {
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
        $("#prodi, #angkatan, #status_mahasiswa").change(function () {
            self.data.table_data_center.ajax.reload();
        });
        self.data.table_data_center = $("#table-data-center").DataTable({
            serverSide: true,
            ajax: {
                url: '/rek/dashboard/detail-mahasiswa/json',
                type: 'post',
                data: function (data) {
                    data.angkatan = $("#angkatan").val();
                    data.prodi = $("#prodi").val();
                    data.status = $("#status_mahasiswa").val();
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
                    width: "45%",
                    render: function (data) {
                        return "<b>" + data.nama_mahasiswa + " (" + data.nim + ")</b><br/>" +
                            "Alamat : <br/>" + data.alamat;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%",
                    render: function (data) {
                        return "<b>" + data.jenjang_didik + " " + data.nama_program_studi + " (" + data.kd_program_studi + ") </b><br/>" +
                            "Fakultas : <b>" + data.nama_fakultas + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        return "<button class='btn btn-primary-soft'>" + data.status_mahasiswa + "</button>";
                    }
                },
                {
                    data: 'nama_mahasiswa',
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
    jQuery.detail_mahasiswa.init();
});
