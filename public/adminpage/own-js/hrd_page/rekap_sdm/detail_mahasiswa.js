jQuery.detail_mahasiswa = {
    data: {
        table: $("#table"),
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        // Select2
        $(".select2").select2();
        $("#tahun_akademik").change(function () {
            self.data.table.ajax.reload();
        });
        // Table With DataTable
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/hrd/data-kepegawaian/rekap-sdm/detail-mahasiswa-json',
                type: 'post',
                data: function (data) {
                    data.id_prodi = $("#id_prodi").val();
                    data.angkatan = $("#angkatan").val();
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'nomor',
                    searchable: false,
                    sClass: 'text-center',
                    width: '5%'
                },
                {
                    data: 'nama_mahasiswa',
                    searchable: true,
                    sClass: 'text-left',
                    width: "25%",
                },
                {
                    data: 'nim',
                    searchable: true,
                    sClass: 'text-left',
                    width: "25%"
                },
                {
                    data: 'tahun_angkatan',
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                },
                {
                    data: 'nama_program_studi',
                    searchable: false,
                    sClass: 'text-center',
                    width: "25%",
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
    },
};

jQuery(document).ready(function () {
    jQuery.detail_mahasiswa.init();
});
