jQuery.rasio_dosen = {
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
                url: '/hrd/data-kepegawaian/rekap-sdm/detail-dosen-json/' + $("#back").val(),
                type: 'post',
                data: function (data) {
                    data.id_prodi = $("#id_prodi").val();
                    data.jenis = $("#jenis").val();
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
                    data: 'nama_dosen',
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                    render: function (data) {
                        var html = "";
                        if ($("#back").val() == 'kecukupan')
                            html = data.pendidikan_terakhir;
                        else if ($("#back").val() == 'jafa' || $("#back").val() == 'serdos' || $("#back").val() == 'lb')
                            html = data.jabatan_fungsional + "<br/>" +
                                "<small>Jenis Sertifikasi: " + data.sertifikasi + "</small>";
                        return html;
                    }
                },
                {
                    data: 'nidn',
                    searchable: false,
                    sClass: 'text-center',
                    width: "25%",
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
    },
};

jQuery(document).ready(function () {
    jQuery.rasio_dosen.init();
});
