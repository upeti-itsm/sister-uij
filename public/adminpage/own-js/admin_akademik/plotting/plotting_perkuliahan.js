jQuery.plotting_perkuliahan = {
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
                url: '/adm-akademik/plotting/plotting-kelas/json',
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
                        return "<span class='badge badge-info'>" + data.kd_matakuliah + "</span>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                    render: function (data) {
                        return "<strong>" + data.nama_matakuliah + "</strong><br><small class='text-muted'>" + data.sks + " SKS</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                    render: function (data) {
                        return data.nama_dosen || data.nama_karyawan || '-';
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "12%",
                    render: function (data) {
                        return "<span class='badge badge-primary'>" + data.jenis_pengajaran + "</span>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "10%",
                    render: function (data) {
                        return "<span class='badge badge-secondary'>" + data.kelas + "</span>";
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
                    width: "10%",
                    render: function (data) {
                        var editButton = "<button title='Edit Plotting' class='btn btn-sm btn-primary btn-edit mr-1' " +
                            "data-id='" + data.id_plotting + "' " +
                            "data-id_matakuliah='" + data.id_matakuliah + "' " +
                            "data-id_karyawan='" + data.id_karyawan + "' " +
                            "data-tahun_akademik='" + data.tahun_akademik + "' " +
                            "data-jenis_pengajaran='" + data.id_jenis_pengajaran + "' " +
                            "data-id_kelas='" + data.id_kelas + "'>" +
                            "<i class='fas fa-edit'></i></button>";

                        var deleteButton = "";
                        if (data.sts_aktif === true || data.sts_aktif === 1) {
                            deleteButton = "<button title='Non-Aktifkan Plotting' class='btn btn-sm btn-danger btn-delete' " +
                                "data-id='" + data.id_plotting + "' " +
                                "data-nama_matakuliah='" + data.nama_matakuliah + "' " +
                                "data-status='false'>" +
                                "<span class='spinner-border spinner-border-sm mr-1' id='detail-loading-spin-" + data.id_plotting + "' style='display: none' role='status' aria-hidden='true'></span>" +
                                "<i class='fas fa-trash'></i></button>";
                        } else {
                            deleteButton = "<button title='Aktifkan Plotting' class='btn btn-sm btn-success btn-delete' " +
                                "data-id='" + data.id_plotting + "' " +
                                "data-nama_matakuliah='" + data.nama_matakuliah + "' " +
                                "data-status='true'>" +
                                "<span class='spinner-border spinner-border-sm mr-1' id='detail-loading-spin-" + data.id_plotting + "' style='display: none' role='status' aria-hidden='true'></span>" +
                                "<i class='fas fa-check'></i></button>";
                        }

                        return editButton + deleteButton;
                    }
                },
                {
                    data: 'nama_matakuliah',
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
                "emptyTable": "Tidak ditemukan data plotting",
                "processing": "Memuat data...",
                "loadingRecords": "Memuat data...",
                "zeroRecords": "Tidak ditemukan data yang sesuai"
            }
        });

        // Cascade Events
        $("#kd_prodi").change(function () {
            var kd_prodi = $(this).val();
            if (kd_prodi) {
                self.loadKurikulum(kd_prodi);
            } else {
                $("#id_kurikulum").empty().append('<option value="">Pilih Kurikulum</option>').trigger('change');
            }
        });

        $("#id_kurikulum").change(function () {
            var id_kurikulum = $(this).val();
            if (id_kurikulum) {
                self.loadMatakuliah(id_kurikulum);
            } else {
                $("#id_matakuliah").empty().append('<option value="">Pilih Mata Kuliah</option>');
            }
        });

        $("#tahun_akademik").change(function () {
            var tahun_akademik = $(this).val();
            var kd_prodi = $("#kd_prodi").val();
            if (tahun_akademik && kd_prodi) {
                self.loadKelas(kd_prodi, tahun_akademik);
            } else {
                $("#id_kelas").empty().append('<option value="">Pilih Kelas</option>');
            }
        });

        // Filter Events
        $("#filter_kd_prodi").change(function () {
            var filterReady = $(this).val() && $("#filter_tahun_akademik").val();
            if (filterReady) {
                self.data.table.ajax.reload();
            } else {
                self.data.table.clear().draw();
            }
        });

        $("#filter_tahun_akademik").change(function () {
            var filterReady = $(this).val() && $("#filter_kd_prodi").val();
            if (filterReady) {
                self.data.table.ajax.reload();
            } else {
                self.data.table.clear().draw();
            }
        });

        $("#btn-cari-data").click(function () {
            if ($("#filter_kd_prodi").val() && $("#filter_tahun_akademik").val()) {
                self.data.table.search($("#cari-data").val()).draw();
            } else {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pilih Program Studi dan Tahun Akademik terlebih dahulu"
                });
            }
        });

        $("#cari-data").keyup(function () {
            if (this.value === "" && $("#filter_kd_prodi").val() && $("#filter_tahun_akademik").val()) {
                self.data.table.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13 && $("#filter_kd_prodi").val() && $("#filter_tahun_akademik").val()) {
                self.data.table.search(this.value).draw();
            }
        });

        // Form Events
        $("#btn-tambah-data").click(function () {
            if (!$("#filter_kd_prodi").val() || !$("#filter_tahun_akademik").val()) {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pilih Program Studi dan Tahun Akademik terlebih dahulu"
                });
                return;
            }
            self.resetForm();
            $("#kd_prodi").val($("#filter_kd_prodi").val()).trigger('change');
            $("#tahun_akademik").val($("#filter_tahun_akademik").val()).trigger('change');
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
            $("#id_plotting").val($(this).data("id"));
            $("#id_matakuliah").val($(this).data("id_matakuliah")).trigger('change');
            $("#id_karyawan").val($(this).data("id_karyawan")).trigger('change');
            $("#tahun_akademik").val($(this).data("tahun_akademik")).trigger('change');
            $("#jenis_pengajaran").val($(this).data("jenis_pengajaran")).trigger('change');
            $("#id_kelas").val($(this).data("id_kelas")).trigger('change');

            $("#filter-collapse").collapse("hide");
            $("#form-collapse").collapse("show");
        });

        // Save Event
        $("#btn-save").click(function () {
            if (!self.validateForm()) {
                return;
            }

            var id = $("#id_plotting").val();
            var data = {
                id_matakuliah: $("#id_matakuliah").val(),
                id_karyawan: $("#id_karyawan").val(),
                tahun_akademik: $("#tahun_akademik").val(),
                jenis_pengajaran: $("#jenis_pengajaran").val(),
                id_kelas: $("#id_kelas").val()
            };

            if (id && id !== "") {
                data.id = id;

                $.ajax({
                    url: '/adm-akademik/plotting/plotting-kelas/update',
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
            } else {
                $.ajax({
                    url: '/adm-akademik/plotting/plotting-kelas/store',
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
            }
        });

        // Delete/Activate Event
        $("#table").on('click', 'button.btn-delete', function () {
            var id = $(this).data("id");
            var nama_matakuliah = $(this).data('nama_matakuliah');
            var status = $(this).data('status');
            var action = status === 'true' ? 'Mengaktifkan' : 'Menonaktifkan';
            var keterangan = 'Apakah anda yakin ' + action + ' plotting mata kuliah <b>' + nama_matakuliah + '</b>?';

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
                                url: '/adm-akademik/plotting/plotting-kelas/delete',
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

    // Cascade Methods
    loadKurikulum: function (kd_prodi) {
        var self = this;
        $.ajax({
            url: '/adm-akademik/plotting/plotting-kelas/json-kurikulum-by-prodi',
            method: 'POST',
            data: { kd_prodi: kd_prodi },
            success: function (response) {
                if (response.success && response.data) {
                    var options = '<option value="">Pilih Kurikulum</option>';
                    $.each(response.data, function (index, item) {
                        options += '<option value="' + item.id_kurikulum + '">' + item.nama_kurikulum + '</option>';
                    });
                    $("#id_kurikulum").html(options);
                }
            },
            error: function () {
                $("#id_kurikulum").html('<option value="">Pilih Kurikulum</option>');
            }
        });
    },

    loadMatakuliah: function (id_kurikulum) {
        var self = this;
        $.ajax({
            url: '/adm-akademik/plotting/plotting-kelas/json-matakuliah-by-kurikulum',
            method: 'POST',
            data: { id_kurikulum: id_kurikulum },
            success: function (response) {
                if (response.success && response.data) {
                    var options = '<option value="">Pilih Mata Kuliah</option>';
                    $.each(response.data, function (index, item) {
                        options += '<option value="' + item.id_matakuliah + '">' + item.kd_matakuliah + ' - ' + item.nama_matakuliah + ' (' + item.sks + ' SKS)</option>';
                    });
                    $("#id_matakuliah").html(options);
                }
            },
            error: function () {
                $("#id_matakuliah").html('<option value="">Pilih Mata Kuliah</option>');
            }
        });
    },

    loadKelas: function (kd_prodi, tahun_akademik) {
        var self = this;
        $.ajax({
            url: '/adm-akademik/plotting/plotting-kelas/json-kelas-by-pprodi-tahun',
            method: 'POST',
            data: {
                kd_prodi: kd_prodi,
                tahun_akademik: tahun_akademik
            },
            success: function (response) {
                if (response.success && response.data) {
                    var options = '<option value="">Pilih Kelas</option>';
                    $.each(response.data, function (index, item) {
                        options += '<option value="' + item.id_kelas + '">' + item.kode_kelas + ' - ' + item.nama_kelas + '</option>';
                    });
                    $("#id_kelas").html(options);
                }
            },
            error: function () {
                $("#id_kelas").html('<option value="">Pilih Kelas</option>');
            }
        });
    },

    validateForm: function () {
        var isValid = true;
        var errorMessages = [];

        // Validate required fields
        if (!$("#id_matakuliah").val()) {
            errorMessages.push("Mata Kuliah harus dipilih");
            isValid = false;
        }

        if (!$("#id_karyawan").val()) {
            errorMessages.push("Dosen Pengampu harus dipilih");
            isValid = false;
        }

        if (!$("#tahun_akademik").val()) {
            errorMessages.push("Tahun Akademik harus dipilih");
            isValid = false;
        }

        if (!$("#jenis_pengajaran").val()) {
            errorMessages.push("Jenis Pengajaran harus dipilih");
            isValid = false;
        }

        if (!$("#id_kelas").val()) {
            errorMessages.push("Kelas harus dipilih");
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
        $("#id_plotting").val("");
        $("#kd_prodi").val("").trigger('change');
        $("#tahun_akademik").val("").trigger('change');
        $("#id_kurikulum").html('<option value="">Pilih Kurikulum</option>');
        $("#id_matakuliah").html('<option value="">Pilih Mata Kuliah</option>');
        $("#id_karyawan").val("").trigger('change');
        $("#jenis_pengajaran").val("").trigger('change');
        $("#id_kelas").html('<option value="">Pilih Kelas</option>');
        $("#cari-data").val("");
    }
};

jQuery(document).ready(function () {
    jQuery.plotting_perkuliahan.init();
});
