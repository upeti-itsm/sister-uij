jQuery.peran_pengguna = {
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
                url: '/super-admin/peran-pengguna/daftar-peran/json',
                type: 'post',
                data: function (data) {
                    data.id = $("#id_aplikasi").val();
                }
            },
            scrollY: '300px',
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
                    width: "25%",
                    render: function (data) {
                        return "<b>" + data.nama + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "50%",
                    render: function (data) {
                        var nama_peran = data.nama_peran.split(",");
                        var html = "<ol>";
                        $.each(nama_peran, function (index, item) {
                            html = html + "<li>" + item + "</li>"
                        });
                        html = html + "</ol>"
                        return html;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        return "<button title='Edit Peran' class='btn btn-block btn-info btn-edit' data-id='"+data.id_personal+"'><i class='fas fa-edit mr-2'></i>Edit</button>";
                    }
                },
                {
                    data: 'nama',
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
        $("#id_aplikasi").change(function () {
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

        $("#table").on('click', 'button.btn-edit', function (){
            location.href = '/super-admin/peran-pengguna/detail/' + $(this).data('id') + '/' + $("#id_aplikasi").val()
        })
    },
};

jQuery(document).ready(function () {
    jQuery.peran_pengguna.init();
});
