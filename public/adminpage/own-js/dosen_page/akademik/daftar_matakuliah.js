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
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%",
                    render: function (data) {
                        return "<b>" + data.nama_mata_kuliah + " (" + data.kelas_id + ")</b><br/>" +
                            "Kode Prodi : " + data.prodi;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%",
                    render: function (data) {
                        if (data.nik === data.nik_asisten) {
                            return "Dosen 1 : <b>" + data.nama_lengkap + "</b><br/>" +
                                "Dosen 2 : <b>-- Tidak Ada --</b>";
                        } else {
                            return "Dosen 1 : <b>" + data.nama_lengkap + "</b><br/>" +
                                "Dosen 2 : <b>" + data.nama_lengkap_asisten + "</b>";
                        }
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        return "<button title='Lihat Peserta Kuliah' class='btn btn-success-soft btn-sm btn-lihat mr-2' data-id='" + data.jadwal_kuliah_id + "'><i class='fas fa-eye'></i></button>" +
                            "<a href='/dosen/akademik/daftar-matakuliah/export-peserta-pdf/" + data.jadwal_kuliah_id + "' title='Export Peserta Kuliah' class='btn btn-danger-soft btn-sm btn-print mr-2' data-id='" + data.jadwal_kuliah_id + "'><i class='fas fa-file-pdf'></i></a>";
                    }
                },
                {
                    data: 'nama_mata_kuliah',
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
    jQuery.daftar_matakuliah.init();
});
