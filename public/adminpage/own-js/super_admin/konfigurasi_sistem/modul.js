jQuery.modul = {
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
                url: '/super-admin/modul/json/daftar-modul',
                type: 'post',
                data: function (data){
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
                    width: "35%",
                    render: function (data) {
                        return "<b>" + data.nama_modul + "</b>";
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
                        return "<button title='Edit Modul' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_modul + "' data-id_aplikasi='" + data.id_aplikasi + "' data-nama_modul='" + data.nama_modul + "' data-keterangan='" + data.keterangan + "'><i class='fas fa-edit'></i></button>" +
                            "<button title='Hapus Modul' class='btn btn-sm btn-danger btn-delete' data-id='" + data.id_modul + "' data-nama_modul='" + data.nama_modul + "' ><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_modul + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
                    }
                },
                {
                    data: 'nama_modul',
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
            $("#id_modul").val("");
            $("#id_aplikasi_add").val("00000000-0000-0000-0000-000000000000").change();
            $("#id_aplikasi_add").attr('disabled', false);
        });
        // On Cancel Click
        $("#btn-cancel").click(function () {
            $("#nama_modul").val("");
            $("#keterangan").val("");
            $("#cari-data").val("");
            $("#filter-collapse").collapse("show");
            $("#form-collapse").collapse("hide");
        });
        // On Edit
        $("#table").on('click', 'button.btn-edit', function () {
            $("#nama_modul").val($(this).data("nama_modul"));
            $("#keterangan").val($(this).data("keterangan"));
            $("#btn-tambah-data").trigger("click");
            $("#id_modul").val($(this).data("id"));
            $("#id_aplikasi_add").val($(this).data("id_aplikasi")).change();
            $("#id_aplikasi_add").attr('disabled', true);
        });

        // On Save Data
        $("#btn-save-modul").click(function () {
            if (!$("#nama_modul").val() || !$("#keterangan").val())
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Nama dan Keterangan Modul Terisi"
                });
            else {
                var operasi = 'store';
                var id = '00000000-0000-0000-0000-000000000000'
                if ($("#id_modul").val()) {
                    id = $("#id_modul").val();
                    operasi = 'update'
                } else {
                    if ($("#id_aplikasi_add").val() === '00000000-0000-0000-0000-000000000000'){
                        $.alert({
                            title: "Peringatan",
                            type: "orange",
                            content: "Pastikan Sudah Memilih Aplikasi"
                        });
                        return false;
                    }
                }
                $.ajax({
                    url: '/super-admin/modul/' + operasi,
                    method: 'POST',
                    data: {
                        id_aplikasi: $("#id_aplikasi_add").val(),
                        nama_modul: $("#nama_modul").val(),
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
            var nama_modul = $(this).data('nama_modul');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin menghapus <b>' + nama_modul + '</b> dari sistem ?<br/><b class="text-danger">Semua Kewenangan Peran yang berhubungan dengan modul ini akan terhapus</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/super-admin/modul/delete',
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
    jQuery.modul.init();
});
