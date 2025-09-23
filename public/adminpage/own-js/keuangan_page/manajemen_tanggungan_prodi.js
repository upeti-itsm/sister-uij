jQuery.manajemen_ruangan = {
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
                url: '/adm-akademik/manajemen-ruangan/json',
                type: 'post',
                data: function (data) {
                    // data.id = $("#kd_prodi").val();
                }
            },
            scrollY: '300px',
            scrollCollapse: true,
            columns: [
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "5%",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "15%",
                    render: function (data) {
                        return "<p>" + data.kd_ruang_perkuliahan + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                    render: function (data) {
                        return "<p>" + data.ruang_perkuliahan + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "10%",
                    render: function (data) {
                        return "<b>" + data.kapasitas + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                    render: function (data) {
                        return "<b>" + data.informasi_kelas + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        return "<button title='Edit Ruangan Perkuliahan' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_ruang_perkuliahan + "' data-ruang_perkuliahan='" + data.ruang_perkuliahan + "' data-kapasitas='" + data.kapasitas + "' data-informasi_ruangan='" + data.informasi_kelas + "' data-sts_aktif='" + data.sts_aktif + "'><i class='fas fa-edit'></i></button>" +
                            "<button title='Non-Aktifkan Ruangan Perkuliahan' class='btn btn-sm btn-danger btn-delete' data-id='" + data.id_ruang_perkuliahan + "' data-ruang_perkuliahan='" + data.ruang_perkuliahan + "' data-kapasitas='" + data.kapasitas + "' ><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_ruang_perkuliahan + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
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
            $("#id_ruang_perkuliahan").val("");
        });
        // On Cancel Click
        $("#btn-cancel").click(function () {
            $("#ruang_perkuliahan").val("");
            $("#kapasitas").val("");
            $("#informasi_kelas").val("");
            $("#cari-data").val("");
            $("#filter-collapse").collapse("show");
            $("#form-collapse").collapse("hide");
        });
        // On Edit
        $("#table").on('click', 'button.btn-edit', function () {
            $("#ruang_perkuliahan").val($(this).data("ruang_perkuliahan"));
            $("#kapasitas").val($(this).data("kapasitas"));
            $("#informasi_kelas").val($(this).data("informasi_ruangan"));
            $("#btn-tambah-data").trigger("click");
            let sts = $(this).data("sts_aktif");
            $("#status_ruangan").val(sts === true || sts === "true" ? "1" : "0").change();
            $("#id_ruang_perkuliahan").val($(this).data("id"));
        });

        // On Save Data
        $("#btn-save").click(function () {
            if (!$("#ruang_perkuliahan").val() || !$("#kapasitas").val() || !$("#informasi_kelas").val())
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Ruang Perkuliahan, Kapasitas dan Informasi Kelas Terisi"
                });
            else {
                var operasi = 'store';
                var id = 0;
                if ($("#id_ruang_perkuliahan").val()) {
                    id = $("#id_ruang_perkuliahan").val();
                    operasi = 'update';
                }
                $.ajax({
                    url: '/adm-akademik/manajemen-ruangan/' + operasi,
                    method: 'POST',
                    data: {
                        ruang_perkuliahan: $("#ruang_perkuliahan").val(),
                        kapasitas: $("#kapasitas").val(),
                        informasi_kelas: $("#informasi_kelas").val(),
                        status_ruangan: $("#status_ruangan").val(),
                        id: id
                    },
                    beforeSend: function () {
                        $("#loading-tambah-data").show();
                    },
                    success: function (response) {
                        if (response.status === true) {
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
            var ruang_perkuliahan = $(this).data('ruang_perkuliahan');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin ingin menghapus ruangan <b>' + ruang_perkuliahan + '</b> ?',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/adm-akademik/manajemen-ruangan/delete',
                                method: 'POST',
                                data: {
                                    id: id,
                                    status: false,
                                },
                                beforeSend: function () {
                                    $("#detail-loading-spin-" + id).show();
                                },
                                success: function (response) {
                                    if (response.status === true) {
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
    jQuery.manajemen_ruangan.init();
});
