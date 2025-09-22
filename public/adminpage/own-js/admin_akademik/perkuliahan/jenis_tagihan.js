jQuery.jenis_tagihan = {
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
                url: '/adm-akademik/perkuliahan/jenis-tagihan/json',
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
                    width: "10%",
                    render: function (data) {
                        return "<p>" + data.kd_tagihan + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                    render: function (data) {
                        return "<p>" + data.jenis_tagihan + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "10%",
                    render: function (data) {
                        return "<p>" + data.tipe_periodisasi + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%",
                    render: function (data) {
                        return "<p>" + data.deskripsi + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        return "<button title='Edit Jenis Tagihan' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_jenis_tagihan + "' data-jenis_tagihan='" + data.jenis_tagihan + "' data-kd_tagihan='" + data.kd_tagihan + "' data-deskripsi='" + data.deskripsi + "' data-tipe_periodisasi='" + data.tipe_periodisasi + "' data-sts_aktif='" + data.sts_aktif + "'><i class='fas fa-edit'></i></button>" +
                            "<button title='Non-Aktifkan Jenis Tagihan' class='btn btn-sm btn-danger btn-delete' data-id='" + data.id_jenis_tagihan + "' data-kd_tagihan='" + data.kd_tagihan + "' data-deskripsi='" + data.deskripsi + "' ><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_jenis_tagihan + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
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
            $("#id_jenis_tagihan").val("");
        });
        // On Cancel Click
        $("#btn-cancel").click(function () {
            $("#jenis_tagihan").val("");
            $("#tipe_periodisasi").val("");
            $("#deskripsi").val("");
            $("#cari-data").val("");
            $("#filter-collapse").collapse("show");
            $("#form-collapse").collapse("hide");
        });
        // On Edit
        $("#table").on('click', 'button.btn-edit', function () {
            $("#jenis_tagihan").val($(this).data("jenis_tagihan"));
            $("#tipe_periodisasi").val($(this).data("tipe_periodisasi"));
            $("#deskripsi").val($(this).data("deskripsi"));
            $("#btn-tambah-data").trigger("click");
            let sts = $(this).data("sts_aktif");
            $("#status_jenis_tagihan").val(sts === true || sts === "true" ? "1" : "0").change();
            $("#id_jenis_tagihan").val($(this).data("id"));
        });

        // On Save Data
        $("#btn-save").click(function () {
            if (!$("#jenis_tagihan").val() || !$("#tipe_periodisasi").val() || !$("#deskripsi").val())
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Jenis Tagihan, Tipe Periodisasi dan Deskripsi Terisi"
                });
            else {
                var operasi = 'store';
                var id = 0;
                if ($("#id_jenis_tagihan").val()) {
                    id = $("#id_jenis_tagihan").val();
                    operasi = 'update';
                }
                $.ajax({
                    url: '/adm-akademik/perkuliahan/jenis-tagihan/' + operasi,
                    method: 'POST',
                    data: {
                        jenis_tagihan: $("#jenis_tagihan").val(),
                        tipe_periodisasi: $("#tipe_periodisasi").val(),
                        deskripsi: $("#deskripsi").val(),
                        status_jenis_tagihan: $("#status_jenis_tagihan").val(),
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
            var kd_tagihan = $(this).data('kd_tagihan');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin ingin menghapus tagihan <b>' + kd_tagihan + '</b> ?',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/adm-akademik/perkuliahan/jenis-tagihan/delete',
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
    jQuery.jenis_tagihan.init();
});
