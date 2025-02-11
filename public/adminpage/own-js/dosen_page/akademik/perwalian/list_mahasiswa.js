jQuery.list_mahasiswa = {
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
                url: '/dosen/akademik/perwalian/list-mahasiswa/json',
                type: 'post',
                data: function (data) {
                    data.semester = $("#semester").val();
                }
            },
            scrollY: '400px',
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
                        return data.nama_mahasiswa + " (" + data.nim + ")";
                    }
                },
                {
                    data: 'nama_prodi',
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%"
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                    render: function (data) {
                        if (data.sts_mengisi_kuesioner)
                            return "<span class='badge badge-success'>Sudah Mengisi</span>";
                        else
                            return "<span class='badge badge-danger'>Belum Mengisi</span>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        if (data.sts_dokumen_skpi === "-") {
                            return "<span class='badge badge-danger'>Belum Mengisi</span>";
                        } else {
                            var path = data.sts_dokumen_skpi.split(";");
                            return "<ol>" +
                                "<li><a target='_blank' href='/files/dokumen_skpi/" + data.nim + "/" + path[1] + "'>Kartu Prestasi</a></li>" +
                                "<li><a target='_blank' href='/files/dokumen_skpi/" + data.nim + "/" + path[0] + "'>Dok. Pendukung</a></li>" +
                                "</ol>";
                        }
                    }
                },
                {
                    data: 'nama_mahasiswa',
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
        $("#semester").change(function () {
            self.data.table.ajax.reload();
        })
    },
};

jQuery(document).ready(function () {
    jQuery.list_mahasiswa.init();
});
