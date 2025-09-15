jQuery.manajemen_kelas = {
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
                url: '/adm-akademik/plotting/mnj-kelas/json',
                type: 'post',
                data: function (data) {
                    data.kd_prodi = $("#filter_kd_prodi").val();
                    data.tahun_akademik = $("#filter_tahun_akademik").val();
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
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        return "<span class='badge badge-info'>" + data.kd_kelas + "</span>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        return "<strong>" + data.nama_kelas + "</strong>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                    render: function (data) {
                        return data.nm_prodi || data.nama_prodi || '-';
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        return "<span class='badge badge-secondary'>" + data.tahun_akademik + "</span>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "8%",
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
                    width: "12%",
                    render: function (data) {
                        var editButton = "<button title='Edit Kelas' class='btn btn-sm btn-primary btn-edit mr-1' " +
                            "data-id='" + data.id_kelas + "' " +
                            "data-nama_kelas='" + data.nama_kelas + "' " +
                            "data-kode_kelas='" + data.kd_kelas + "' " +
                            "data-kd_prodi='" + data.kd_prodi + "' " +
                            "data-tahun_akademik='" + data.tahun_akademik + "' " +
                            "data-keterangan='" + (data.keterangan || '') + "'>" +
                            "<i class='fas fa-edit'></i></button>";

                        var deleteButton = "";
                        if (data.sts_aktif === true || data.sts_aktif === 1) {
                            deleteButton = "<button title='Non-Aktifkan Kelas' class='btn btn-sm btn-danger btn-delete' " +
                                "data-id='" + data.id_kelas + "' " +
                                "data-nama_kelas='" + data.nama_kelas + "' " +
                                "data-status='false'>" +
                                "<span class='spinner-border spinner-border-sm mr-1' id='detail-loading-spin-" + data.id_kelas + "' style='display: none' role='status' aria-hidden='true'></span>" +
                                "<i class='fas fa-trash'></i></button>";
                        } else {
                            deleteButton = "<button title='Aktifkan Kelas' class='btn btn-sm btn-success btn-delete' " +
                                "data-id='" + data.id_kelas + "' " +
                                "data-nama_kelas='" + data.nama_kelas + "' " +
                                "data-status='true'>" +
                                "<span class='spinner-border spinner-border-sm mr-1' id='detail-loading-spin-" + data.id_kelas + "' style='display: none' role='status' aria-hidden='true'></span>" +
                                "<i class='fas fa-check'></i></button>";
                        }

                        return editButton + deleteButton;
                    }
                },
                {
                    data: 'nama_kelas',
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
                "emptyTable": "Tidak ditemukan data kelas",
                "processing": "Memuat data...",
                "loadingRecords": "Memuat data...",
                "zeroRecords": "Tidak ditemukan data yang sesuai"
            }
        });

        // Filter Events
        $("#filter_kd_prodi").change(function () {
            if ($(this).val()) {
                self.data.table.ajax.reload();
            } else {
                // Clear table if no program studi selected
                self.data.table.clear().draw();
            }
        });

        $("#btn-cari-data").click(function () {
            if ($("#filter_kd_prodi").val()) {
                self.data.table.search($("#cari-data").val()).draw();
            } else {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pilih Program Studi terlebih dahulu"
                });
            }
        });

        $("#cari-data").keyup(function () {
            if (this.value === "" && $("#filter_kd_prodi").val()) {
                self.data.table.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13 && $("#filter_kd_prodi").val()) {
                self.data.table.search(this.value).draw();
            }
        });

        // Form Events
        $("#btn-tambah-data").click(function () {
            if (!$("#filter_kd_prodi").val()) {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pilih Program Studi terlebih dahulu"
                });
                return;
            }
            self.resetForm();
            $("#kd_prodi").val($("#filter_kd_prodi").val()).trigger('change');
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
            $("#id_kelas").val($(this).data("id"));
            $("#nama_kelas").val($(this).data("nama_kelas"));
            $("#kode_kelas").val($(this).data("kode_kelas"));
            $("#kd_prodi").val($(this).data("kd_prodi")).trigger('change');
            $("#tahun_akademik").val($(this).data("tahun_akademik")).trigger('change');
            $("#keterangan").val($(this).data("keterangan"));

            $("#filter-collapse").collapse("hide");
            $("#form-collapse").collapse("show");
        });

        // Save Event
        $("#btn-save").click(function () {
            if (!self.validateForm()) {
                return;
            }

            var operasi = 'store';
            var id = $("#id_kelas").val();
            var data = {
                nama_kelas: $("#nama_kelas").val().trim(),
                kode_kelas: $("#kode_kelas").val().trim(),
                kd_prodi: $("#kd_prodi").val(),
                tahun_akademik: $("#tahun_akademik").val().trim(),
                keterangan: $("#keterangan").val().trim()
            };

            if (id !== "00000000-0000-0000-0000-000000000000") {
                operasi = 'update';
                data.id = id;
            }

            $.ajax({
                url: '/adm-akademik/plotting/mnj-kelas/' + operasi,
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
            var nama_kelas = $(this).data('nama_kelas');
            var status = $(this).data('status');
            var action = status === 'true' ? 'Mengaktifkan' : 'Menonaktifkan';
            var keterangan = 'Apakah anda yakin ' + action + ' kelas <b>' + nama_kelas + '</b>?';

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
                                url: '/adm-akademik/plotting/mnj-kelas/delete',
                                method: 'POST',
                                data: {
                                    id: id,
                                    status: status === 'true'
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

    validateForm: function () {
        var isValid = true;
        var errorMessages = [];

        // Validate required fields
        if (!$("#nama_kelas").val().trim()) {
            errorMessages.push("Nama Kelas harus diisi");
            isValid = false;
        }

        if (!$("#kode_kelas").val().trim()) {
            errorMessages.push("Kode Kelas harus diisi");
            isValid = false;
        }

        if (!$("#kd_prodi").val()) {
            errorMessages.push("Program Studi harus dipilih");
            isValid = false;
        }

        if (!$("#tahun_akademik").val().trim()) {
            errorMessages.push("Tahun Akademik harus diisi");
            isValid = false;
        }

        if (!isValid) {
            $.alert({
                title: "Validasi Gagal",
                type: "orange",
                content: errorMessages.join('<br>')
            });
        }

        return isValid;
    },

    resetForm: function () {
        $("#id_kelas").val("00000000-0000-0000-0000-000000000000");
        $("#nama_kelas").val("");
        $("#kode_kelas").val("");
        $("#kd_prodi").val("").trigger('change');
        $("#tahun_akademik").val("");
        $("#keterangan").val("");
        $("#cari-data").val("");
    }
};

jQuery(document).ready(function () {
    jQuery.manajemen_kelas.init();
});
