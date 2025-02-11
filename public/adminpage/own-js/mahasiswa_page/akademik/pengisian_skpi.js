jQuery.pengisian_skpi = {
    data: {
        table: $("#table"),
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/mhs/skpi/json',
                type: 'post'
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'tanggal_upload',
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%"
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return "<a href='/files/dokumen_skpi/"+data.nim+"/"+data.path_dokumen_kartu_prestasi+"' target='_blank'>Kartu Prestasi</a>"
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return "<a href='/files/dokumen_skpi/"+data.nim+"/"+data.path_dokumen_pendukung_skpi+"' target='_blank'>Kartu Prestasi</a>"
                    }
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
    jQuery.pengisian_skpi.init();
});
