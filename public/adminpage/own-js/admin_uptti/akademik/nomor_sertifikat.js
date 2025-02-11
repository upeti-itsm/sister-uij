jQuery.nomor_sertifikat = {
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
                url: '/adm-uptti/akademik/nomor-sertifikat/json/daftar-nomor-sertifikat',
                type: 'POST'
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
                    width: "55%",
                    render: function (data) {
                        return "<b>" + data.nomor_sertifikat + "</b><br/>" +
                            "<small>Tanggal Pembuatan : " + data.tgl_created + "</small>";
                    }
                }, {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        if (data.is_data_aktif === false)
                            return "Tidak Digunakan";
                        else
                            return "<b>Digunakan Saat Ini</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        var disabled = 'disabled';
                        if (data.is_data_aktif === true)
                            disabled = '';
                        return "<button title='Edit Nomor Sertifikat' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id + "' data-nomor_sertifikat='" + data.nomor_sertifikat + "' " + disabled + "><i class='fas fa-edit'></i></button>";
                    }
                },
                {
                    data: 'nomor_sertifikat',
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
        // Add or Update Data
        // Add
        $("#btn-tambah-data").click(function () {
            $("#filter-collapse").collapse("hide");
            $("#form-collapse").collapse("show");
            $("#id").val("");
        });
        // On Cancel Click
        $("#btn-cancel").click(function () {
            $("#nomor_sertifikat").val("");
            $("#cari-data").val("");
            $("#filter-collapse").collapse("show");
            $("#form-collapse").collapse("hide");
        });
        // On Edit
        $("#table").on('click', 'button.btn-edit', function () {
            $("#nomor_sertifikat").val($(this).data("nomor_sertifikat"));
            $("#btn-tambah-data").trigger("click");
            $("#id").val($(this).data("id"));
        });

        // On Save Data
        $("#btn-save-nomor").click(function () {
            if (!$("#nomor_sertifikat").val())
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Nomor Sertifikat Terisi"
                });
            else {
                var operasi = 'store';
                var id = 0
                if ($("#id").val()) {
                    id = $("#id").val();
                    operasi = 'update'
                }
                $.ajax({
                    url: '/adm-uptti/akademik/nomor-sertifikat/' + operasi,
                    method: 'POST',
                    data: {
                        nomor_sertifikat: $("#nomor_sertifikat").val(),
                        id: id
                    },
                    beforeSend: function () {
                        $("#loading-tambah-data").show();
                    },
                    success: function (response) {
                        if (response.status === 1) {
                            $.alert({
                                title: 'Informasi',
                                type: 'green',
                                content: response.keterangan
                            });
                            $("#btn-cancel").trigger("click");
                        } else {
                            $.alert({
                                title: 'Informasi',
                                type: 'red',
                                content: response.keterangan
                            });
                        }
                    },
                    complete: function () {
                        $("#loading-tambah-data").hide();
                        self.data.table.ajax.reload();
                    }
                });
            }
        });
    },
};

jQuery(document).ready(function () {
    jQuery.nomor_sertifikat.init();
});
