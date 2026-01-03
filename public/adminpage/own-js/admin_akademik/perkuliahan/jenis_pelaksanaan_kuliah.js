jQuery.jenis_pelaksanaan_kuliah = {
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
                url: '/adm-akademik/perkuliahan/jenis-pelaksanaan-kuliah/json',
                type: 'post',
                data: function (data) {

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
                    width: "20%",
                    render: function (data) {
                        return "<p>" + data.kd_jenis_pelaksanaan_matakuliah + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        return "<p>" + data.jenis_pelaksanaan_matakuliah + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        return "<p>" + data.keterangan + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "10%",
                    render: function (data) {
                        const status = data.sts_aktif_data ? 'Aktif' : 'Non-Aktif';
                        return "<p>" + status + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        var button = "<button title='Non-Aktifkan Jenis Pelaksanaan Kuliah' class='btn btn-sm btn-danger btn-delete' data-id='" + data.id_jenis_pelaksanaan_matakuliah + "' data-nama_jenis='" + data.jenis_pelaksanaan_matakuliah + "' data-kd_jenis='" + data.kd_jenis_pelaksanaan_matakuliah + "' data-status='false' ><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_jenis_pelaksanaan_matakuliah + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
                        if (data.sts_aktif_data === false) {
                            button = "<button title='Aktifkan Jenis Pelaksanaan Kuliah' class='btn btn-sm btn-danger btn-delete' data-id='" + data.id_jenis_pelaksanaan_matakuliah + "' data-nama_jenis='" + data.jenis_pelaksanaan_matakuliah + "' data-kd_jenis='" + data.kd_jenis_pelaksanaan_matakuliah + "' data-status='true'><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_jenis_pelaksanaan_matakuliah + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
                        }
                        return "<button title='Edit Jenis Pelaksanaan Kuliah' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_jenis_pelaksanaan_matakuliah + "' data-nama_jenis='" + data.jenis_pelaksanaan_matakuliah + "' data-keterangan='" + data.keterangan + "' data-kd_jenis='" + data.kd_jenis_pelaksanaan_matakuliah + "'><i class='fas fa-edit'></i></button>" +
                            button;
                    }
                },
                {
                    data: 'jenis_pelaksanaan_matakuliah',
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
        });
        // On Cancel Click
        $("#btn-cancel").click(function () {
            $("#cari-data").val("");
            $("#filter-collapse").collapse("show");
            $("#form-collapse").collapse("hide");
        });
        // On Edit
        $("#table").on('click', 'button.btn-edit', function () {
            $("#kd_jenis_pelaksanaan_kuliah").val($(this).data("kd_jenis"));
            $("#jenis_pelaksanaan_kuliah").val($(this).data("nama_jenis"));
            $("#keterangan").val($(this).data("keterangan"));
            $("#btn-tambah-data").trigger("click");
            $("#id_jenis_pelaksanaan_kuliah").val($(this).data("id"));
        });

        // On Save Data
        $("#btn-save").click(function () {
            if (!$("#kd_jenis_pelaksanaan_kuliah").val() || !$("#jenis_pelaksanaan_kuliah").val() || !$("#keterangan").val())
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Kode, Jenis dan Keterangan jenis pelaksanaan Terisi"
                });
            else {
                var operasi = 'store';
                var id = 0;

                if ($("#id_jenis_pelaksanaan_kuliah").val() !== "0") {
                    id = $("#id_jenis_pelaksanaan_kuliah").val();
                    operasi = 'update';
                }
                $.ajax({
                    url: '/adm-akademik/perkuliahan/jenis-pelaksanaan-kuliah/' + operasi,
                    method: 'POST',
                    data: {
                        kd_jenis_pelaksanaan: $("#kd_jenis_pelaksanaan_kuliah").val(),
                        jenis_pelaksanaan: $("#jenis_pelaksanaan_kuliah").val(),
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
            var nama_jenis = $(this).data('nama_jenis');
            var status = $(this).data('status');
            var keterangan = 'Apakah anda yakin Mengaktifkan <b>' + nama_jenis + '</b> dari jenis pelaksanaan kuliah ?';
            if (status === false) {
                keterangan = 'Apakah anda yakin Menon-Aktifkan <b>' + nama_jenis + '</b> dari jenis pelaksanaan kuliah?';
            }
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: keterangan,
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/adm-akademik/perkuliahan/jenis-pelaksanaan-kuliah/delete',
                                method: 'POST',
                                data: {
                                    id: id,
                                    status: status,
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
    jQuery.jenis_pelaksanaan_kuliah.init();
});
