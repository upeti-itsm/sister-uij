jQuery.jenis_pengajaran = {
    data: {
        table: null
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
                url: '/adm-akademik/perkuliahan/jenis-pengajaran/json',
                type: 'post',
                data: function (data) {
                    // Additional data if needed
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
                    width: "70%",
                    render: function (data) {
                        return "<p>" + data.jenis_pengajaran + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "10%",
                    render: function (data) {
                        if (data.sts_aktif === true || data.sts_aktif === 1) {
                            return "<span class='badge badge-success'>Aktif</span>";
                        } else {
                            return "<span class='badge badge-danger'>Non-Aktif</span>";
                        }
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        var editButton = "<button title='Edit Jenis Pengajaran' class='btn btn-sm btn-primary btn-edit mr-2' " +
                            "data-id='" + data.id_jenis_pengajaran + "' " +
                            "data-jenis_pengajaran='" + data.jenis_pengajaran + "'>" +
                            "<i class='fas fa-edit'></i></button>";

                        var deleteButton = "";
                        if (data.sts_aktif === true || data.sts_aktif === 1) {
                            deleteButton = "<button title='Non-Aktifkan Jenis Pengajaran' class='btn btn-sm btn-danger btn-delete' " +
                                "data-id='" + data.id_jenis_pengajaran + "' " +
                                "data-jenis_pengajaran='" + data.jenis_pengajaran + "' " +
                                "data-status='false'>" +
                                "<span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_jenis_pengajaran + "' style='display: none' role='status' aria-hidden='true'></span>" +
                                "<i class='fas fa-trash'></i></button>";
                        } else {
                            deleteButton = "<button title='Aktifkan Jenis Pengajaran' class='btn btn-sm btn-success btn-delete' " +
                                "data-id='" + data.id_jenis_pengajaran + "' " +
                                "data-jenis_pengajaran='" + data.jenis_pengajaran + "' " +
                                "data-status='true'>" +
                                "<span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_jenis_pengajaran + "' style='display: none' role='status' aria-hidden='true'></span>" +
                                "<i class='fas fa-check'></i></button>";
                        }

                        return editButton + deleteButton;
                    }
                },
                {
                    data: 'jenis_pengajaran',
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
                "emptyTable": "Tidak ditemukan data",
                "processing": "Memuat data...",
                "loadingRecords": "Memuat data...",
                "zeroRecords": "Tidak ditemukan data yang sesuai"
            }
        });

        // Filter and Search Events
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

        // Form Events
        $("#btn-tambah-data").click(function () {
            self.resetForm();
            $("#filter-collapse").collapse("hide");
            $("#form-collapse").collapse("show");
        });

        $("#btn-cancel").click(function () {
            self.resetForm();
            $("#filter-collapse").collapse("show");
            $("#form-collapse").collapse("hide");
        });

        // Edit Event
        $("#table").on('click', 'button.btn-edit', function () {
            $("#jenis_pengajaran").val($(this).data("jenis_pengajaran"));
            $("#id_jenis_pengajaran").val($(this).data("id"));
            $("#filter-collapse").collapse("hide");
            $("#form-collapse").collapse("show");
        });

        // Save Event
        $("#btn-save").click(function () {
            if (!$("#jenis_pengajaran").val().trim()) {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Nama Jenis Pengajaran terisi dengan benar"
                });
                return;
            }

            var operasi = 'store';
            var id = $("#id_jenis_pengajaran").val();
            var data = {
                jenis_pengajaran: $("#jenis_pengajaran").val().trim()
            };

            if (id !== "0") {
                operasi = 'update';
                data.id = id;
            }

            $.ajax({
                url: '/adm-akademik/perkuliahan/jenis-pengajaran/' + operasi,
                method: 'POST',
                data: data,
                beforeSend: function () {
                    $("#loading-tambah-data").show();
                    $("#btn-save").prop('disabled', true);
                },
                success: function (response) {
                    if (response.success === true) {
                        $.alert({
                            title: 'Berhasil',
                            type: 'green',
                            content: response.message
                        });
                        $("#btn-cancel").trigger("click");
                    } else {
                        $.alert({
                            title: 'Gagal',
                            type: 'red',
                            content: response.message || 'Terjadi kesalahan saat menyimpan data'
                        });
                    }
                },
                error: function (xhr) {
                    var errorMessage = 'Terjadi kesalahan saat menyimpan data';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        var errorList = [];
                        for (var field in errors) {
                            errorList = errorList.concat(errors[field]);
                        }
                        errorMessage = errorList.join('<br>');
                    }
                    $.alert({
                        title: 'Error',
                        type: 'red',
                        content: errorMessage
                    });
                },
                complete: function () {
                    $("#loading-tambah-data").hide();
                    $("#btn-save").prop('disabled', false);
                    self.data.table.ajax.reload();
                }
            });
        });

        // Delete/Activate Event
        $("#table").on('click', 'button.btn-delete', function () {
            var id = $(this).data("id");
            var jenis_pengajaran = $(this).data('jenis_pengajaran');
            var status = $(this).data('status');
            var action = status === 'true' ? 'Mengaktifkan' : 'Menonaktifkan';
            var keterangan = 'Apakah anda yakin ' + action + ' <b>' + jenis_pengajaran + '</b> dari jenis pengajaran?';

            $.confirm({
                title: 'Konfirmasi!',
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
                                url: '/adm-akademik/perkuliahan/jenis-pengajaran/delete',
                                method: 'POST',
                                data: {
                                    id: id,
                                    status: status
                                },
                                beforeSend: function () {
                                    $("#detail-loading-spin-" + id).show();
                                },
                                success: function (response) {
                                    if (response.success === true) {
                                        $.alert({
                                            title: 'Berhasil',
                                            type: 'green',
                                            content: response.message
                                        });
                                    } else {
                                        $.alert({
                                            title: 'Gagal',
                                            type: 'red',
                                            content: response.message || 'Terjadi kesalahan saat memperbarui status'
                                        });
                                    }
                                },
                                error: function (xhr) {
                                    var errorMessage = 'Terjadi kesalahan saat memperbarui status';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        errorMessage = xhr.responseJSON.message;
                                    }
                                    $.alert({
                                        title: 'Error',
                                        type: 'red',
                                        content: errorMessage
                                    });
                                },
                                complete: function () {
                                    $("#detail-loading-spin-" + id).hide();
                                    self.data.table.ajax.reload();
                                }
                            });
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

    resetForm: function () {
        $("#id_jenis_pengajaran").val("0");
        $("#jenis_pengajaran").val("");
        $("#cari-data").val("");
    }
};

jQuery(document).ready(function () {
    jQuery.jenis_pengajaran.init();
});
