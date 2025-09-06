jQuery.daftar_matakuliah = {
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
                url: '/dosen/akademik/daftar-matakuliah/json',
                type: 'post',
                data: function (data) {
                    data.tahun = $("#tahun_akademik").val();
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
                    width: "30%",
                    render: function (data) {
                        return "<p>" + data.nama_matakuliah + " (" + data.nama_kelas + ")</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        return "<p>" + data.nama_prodi + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        return "<a href='/dosen/akademik/daftar-matakuliah/export-presensi/" + data.id_jadwal + "' title='Export Presensi Mahasiswa' class='btn btn-danger-soft btn-sm btn-print mr-2' data-id='" + data.id_jadwal + "'><i class='fas fa-file-pdf'></i></a>";
                    }
                },
                {
                    data: 'nama_matakuliah',
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
        $("#tahun_akademik").change(function () {
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
    jQuery.daftar_matakuliah.init();
});
