jQuery.peran = {
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
                url: '/super-admin/peran/json/daftar-peran',
                type: 'post',
                data: function (data){
                    data.id = $("#id_aplikasi").val();
                    data.kelompok_peran = $("#kd_kelompok_peran").val();
                }
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
                    width: "35%",
                    render: function (data) {
                        return "<b>" + data.nama_peran + " ( "+data.kelompok_peran+" )</b>";
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
                        return "<button title='Edit Peran' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_peran + "' data-id_aplikasi='" + data.id_aplikasi + "' data-nama_peran='" + data.nama_peran + "' data-keterangan='" + data.keterangan + "' data-kd_kelompok_peran='" + data.kd_kelompok_peran + "'><i class='fas fa-edit'></i></button>" +
                            "<button title='Hapus Peran' class='btn btn-sm btn-danger btn-delete' data-id='" + data.id_peran + "' data-nama_peran='" + data.nama_peran + "' ><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_peran + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
                    }
                },
                {
                    data: 'nama_peran',
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
            $("#id_peran").val("");
            $("#id_aplikasi_add").val("00000000-0000-0000-0000-000000000000").change();
            $("#id_aplikasi_add").attr('disabled', false);
            $("#kd_kelompok_peran_add").val("..").change();
        });
        // On Cancel Click
        $("#btn-cancel").click(function () {
            $("#nama_peran").val("");
            $("#keterangan").val("");
            $("#cari-data").val("");
            $("#filter-collapse").collapse("show");
            $("#form-collapse").collapse("hide");
        });
        // On Edit
        $("#table").on('click', 'button.btn-edit', function () {
            $("#nama_peran").val($(this).data("nama_peran"));
            $("#keterangan").val($(this).data("keterangan"));
            $("#btn-tambah-data").trigger("click");
            $("#id_peran").val($(this).data("id"));
            $("#id_aplikasi_add").val($(this).data("id_aplikasi")).change();
            $("#id_aplikasi_add").attr('disabled', true);
            $("#kd_kelompok_peran_add").val($(this).data("kd_kelompok_peran")).change();
        });

        // On Save Data
        $("#btn-save-peran").click(function () {
            if (!$("#nama_peran").val() || !$("#keterangan").val() || $("#kd_kelompok_peran_add").val() === "..")
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Nama, Aplikasi, Kelompok Peran dan Keterangan Peran Terisi"
                });
            else {
                var operasi = 'store';
                var id = '00000000-0000-0000-0000-000000000000'
                if ($("#id_peran").val()) {
                    id = $("#id_peran").val();
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
                    url: '/super-admin/peran/' + operasi,
                    method: 'POST',
                    data: {
                        id_aplikasi: $("#id_aplikasi_add").val(),
                        kd_kelompok: $("#kd_kelompok_peran_add").val(),
                        nama_peran: $("#nama_peran").val(),
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
            var nama_peran = $(this).data('nama_peran');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin menghapus <b>' + nama_peran + '</b> dari sistem ?<br/><b class="text-danger">Semua Kewenangan Peran yang berhubungan dengan modul ini akan terhapus</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/super-admin/peran/delete',
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
    jQuery.peran.init();
});
