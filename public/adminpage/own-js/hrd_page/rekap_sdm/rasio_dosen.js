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
                url: '/hrd/data-kepegawaian/rekap-sdm/rasio-dosen-json',
                type: 'post',
                data: function (data) {
                    data.tahun_akademik = $("#tahun_akademik").val();
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            createdRow: function (row, data, dataIndex) {
                // If name is "Ashton Cox"
                if (data['nama_program_studi'].toLowerCase() === 'jumlah') {
                    // Add COLSPAN attribute
                    $('td:eq(0)', row).attr('colspan', 2).css('font-weight', 'bold');

                    // Hide required number of columns
                    // next to the cell with COLSPAN attribute
                    $('td:eq(1)', row).css('display', 'none');

                    // Update cell data
                    this.api().cell($('td:eq(0)', row)).data(data['nama_program_studi']);
                }
            },
            columns: [
                {
                    data: 'nomor',
                    searchable: false,
                    sClass: 'text-center',
                    width: '5%'
                },
                {
                    data: 'nama_program_studi',
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        if (data.nama_program_studi.toLowerCase() !== 'jumlah')
                            return "<a href='/hrd/data-kepegawaian/rekap-sdm/detail-dosen/rasio/" + data.id_program_studi + "/0'>" + data.jml_dosen + "</a>";
                        else
                            return "<a style='font-weight: bold' href='/hrd/data-kepegawaian/rekap-sdm/detail-dosen/rasio/00000000-0000-0000-0000-000000000000/0'>" + data.jml_dosen + "</a>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        if (data.nama_program_studi.toLowerCase() !== 'jumlah')
                            return "<a href='/hrd/data-kepegawaian/rekap-sdm/detail-mahasiswa/" + data.id_program_studi + "'>" + data.jml_mahasiswa + "</a>";
                        else
                            return "<a style='font-weight: bold' href='/hrd/data-kepegawaian/rekap-sdm/detail-mahasiswa/00000000-0000-0000-0000-000000000000'>" + data.jml_mahasiswa + "</a>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        if (data.nama_program_studi.toLowerCase() !== 'jumlah')
                            return "<a style='color: #37a000; cursor: pointer' class='mahasiswa_ta' data-id='" + data.id_program_studi + "'>" + data.jml_mahasiswa_ta + "</a>";
                        else
                            return "<a style='color: #37a000; cursor: pointer; font-weight: bold' class='mahasiswa_ta' data-id='00000000-0000-0000-0000-000000000000'>" + data.jml_mahasiswa_ta + "</a>";
                    }
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

        $("#table").on('click', 'a.mahasiswa_ta', function () {
            var id = $(this).data('id');
            location.href = '/hrd/data-kepegawaian/rekap-sdm/detail-mahasiswa/' + id + '/' + $("#tahun_akademik").val();
        })
    },
};

jQuery(document).ready(function () {
    jQuery.rasio_dosen.init();
});
