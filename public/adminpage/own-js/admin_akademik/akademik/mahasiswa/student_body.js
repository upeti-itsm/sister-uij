jQuery.student_body = {
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
                url: '/adm-akademik/akademik/mahasiswa/student-body/json',
                type: 'post',
                data: function (data) {
                    data.angkatan = $("#angkatan").val();
                    data.prodi = $("#prodi").val();
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'tahun',
                    searchable: false,
                    sClass: 'text-center',
                    width: "11%"
                },
                {
                    data: 'daya_tampung',
                    searchable: false,
                    sClass: 'text-center',
                    width: "11%"
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "13%",
                    render: function (data) {
                        return "<a href='#' data-tahun='" + data.tahun + "' class='camaba'>" + data.calon_mhs + "</a>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "13%",
                    render: function (data) {
                        return "<a href='#' data-tahun='" + data.tahun + "' class='camaba_lulus'>" + data.lulus_calon_mhs + "</a>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "13%",
                    render: function (data) {
                        return "<a href='#' data-tahun='" + data.tahun + "' class='maba'>" + data.maba_reguler + "</a>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "13%",
                    render: function (data) {
                        return "<a href='#' data-tahun='" + data.tahun + "' class='maba_transfer'>" + data.maba_transfer + "</a>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "13%",
                    render: function (data) {
                        return "<a href='#' data-tahun='" + data.tahun + "' class='mahasiswa'>" + data.mhs_reguler + "</a>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "13%",
                    render: function (data) {
                        return "<a href='#' data-tahun='" + data.tahun + "' class='mahasiswa_transfer'>" + data.mhs_transfer + "</a>";
                    }
                }
            ],
            paging: true,
            processing: true,
            pageLength: 10,
            ordering: false,
            lengthChange: false,
            autoWidth: false,
            sDom: 'ltr',
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
        $("#prodi").change(function () {
            self.data.table.ajax.reload();
        });
        $("#angkatan").change(function () {
            self.data.table.ajax.reload();
        });
        $("#table").on('click', 'a.camaba', function () {
            var tahun = $(this).data('tahun');
            window.open('/adm-akademik/akademik/mahasiswa/student-body/pendaftar/1/' + tahun + '/' + $("#prodi").val())
        });
        $("#table").on('click', 'a.camaba_lulus', function () {
            var tahun = $(this).data('tahun');
            window.open('/adm-akademik/akademik/mahasiswa/student-body/pendaftar/2/' + tahun + '/' + $("#prodi").val())
        });
        $("#table").on('click', 'a.maba', function () {
            var tahun = $(this).data('tahun');
            window.open('/adm-akademik/akademik/mahasiswa/student-body/maba/1/' + tahun + '/' + $("#prodi").val())
        });
        $("#table").on('click', 'a.maba_transfer', function () {
            var tahun = $(this).data('tahun');
            window.open('/adm-akademik/akademik/mahasiswa/student-body/maba/2/' + tahun + '/' + $("#prodi").val())
        });
        $("#table").on('click', 'a.mahasiswa', function () {
            var tahun = $(this).data('tahun');
            window.open('/adm-akademik/akademik/mahasiswa/student-body/mahasiswa/1/' + tahun + '/' + $("#prodi").val())
        });
        $("#table").on('click', 'a.mahasiswa_transfer', function () {
            var tahun = $(this).data('tahun');
            window.open('/adm-akademik/akademik/mahasiswa/student-body/mahasiswa/2/' + tahun + '/' + $("#prodi").val())
        });
    },
};

jQuery(document).ready(function () {
    jQuery.student_body.init();
});
