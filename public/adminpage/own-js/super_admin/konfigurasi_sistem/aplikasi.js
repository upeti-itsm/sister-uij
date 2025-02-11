jQuery.aplikasi = {
    data: {},
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        // Option Data
        $(".select2").select2();
        // Table With DataTable
        var table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/super-admin/aplikasi/json/daftar-aplikasi',
                type: 'get',
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
                    width: "35%",
                    render: function (data) {
                        return "<b>" + data.nama_aplikasi + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return "<small>" + data.keterangan + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        return "<button title='Edit Aplikasi' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_aplikasi + "' data-nama_aplikasi='" + data.nama_aplikasi + "' data-keterangan='" + data.keterangan + "'><i class='fas fa-edit'></i></button>" +
                            "<button title='Hapus Aplikasi' class='btn btn-sm btn-danger btn-delete' data-id='" + data.id_aplikasi + "' data-nama_aplikasi='" + data.nama_aplikasi + "' ><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_aplikasi + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
                    }
                },
                {
                    data: 'nama_aplikasi',
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
            table.ajax.reload();
        });
        $("#btn-cari-data").click(function () {
            table.search($("#cari-data").val()).draw();
        });
        $("#cari-data").keyup(function () {
            if (this.value === "") {
                table.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                table.search(this.value).draw();
            }
        });
        // Add or Update Data
        // Add
        $("#btn-tambah-data").click(function () {
            $("#filter-collapse").collapse("hide");
            $("#form-collapse").collapse("show");
            $("#id_aplikasi").val("");
        });
        // On Cancel Click
        $("#btn-cancel").click(function () {
            $("#nama_aplikasi").val("");
            $("#keterangan").val("");
            $("#cari-data").val("");
            $("#filter-collapse").collapse("show");
            $("#form-collapse").collapse("hide");
        });
        // On Edit
        $("#table").on('click', 'button.btn-edit', function () {
            $("#nama_aplikasi").val($(this).data("nama_aplikasi"));
            $("#keterangan").val($(this).data("keterangan"));
            $("#btn-tambah-data").trigger("click");
            $("#id_aplikasi").val($(this).data("id"));
        });

        // On Save Data
        $("#btn-save-aplikasi").click(function () {
            if (!$("#nama_aplikasi").val() || !$("#keterangan").val())
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Nama dan Keterangan Aplikasi Terisi"
                });
            else {
                var operasi = 'store';
                var id = '00000000-0000-0000-0000-000000000000'
                if ($("#id_aplikasi").val()) {
                    id = $("#id_aplikasi").val();
                    operasi = 'update'
                }
                $.ajax({
                    url: '/super-admin/aplikasi/' + operasi,
                    method: 'POST',
                    data: {
                        nama_aplikasi: $("#nama_aplikasi").val(),
                        keterangan: $("#keterangan").val(),
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
                        table.ajax.reload();
                    }
                });
            }
        });
        // On Delete
        $("#table").on('click', 'button.btn-delete', function () {
            var id = $(this).data("id");
            var nama_aplikasi = $(this).data('nama_aplikasi');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin menghapus <b>' + nama_aplikasi + '</b> dari sistem ?<br/><b class="text-danger">Semua Peran dan Modul yang berhubungan dengan Aplikasi ini akan terhapus</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',

                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/super-admin/aplikasi/delete',
                                method: 'POST',
                                data: {
                                    id: id
                                },
                                beforeSend: function () {
                                    $("#detail-loading-spin-" + id).show();
                                },
                                success: function (response) {
                                    if (response.status === 1) {
                                        $.alert({
                                            title: 'Informasi',
                                            type: 'green',
                                            content: response.keterangan
                                        });
                                    } else {
                                        $.alert({
                                            title: 'Informasi',
                                            type: 'red',
                                            content: response.keterangan
                                        });
                                    }
                                },
                                complete: function () {
                                    $("#detail-loading-spin-" + id).hide();
                                    table.ajax.reload();
                                }
                            })
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red'
                    }
                }
            });
        });
    },
};

jQuery(document).ready(function () {
    jQuery.aplikasi.init();
});
