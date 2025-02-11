jQuery.surat_keluar_masuk = {
    data: {
        table_surat_keluar: $("#table-surat-keluar"),
        table_surat_masuk: $("#table-surat-masuk"),
        tgl_surat_keluar: $("#form-collapse-keluar-tgl_surat"),
        tgl_surat_masuk: $("#form-collapse-masuk-tgl_surat"),
        tgl_surat_masuk_diterima: $("#form-collapse-masuk-tgl_diterima"),
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        $(".custom-file-input").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        $("#pills-surat-masuk-tab").click(function () {
            $("#pills-surat-masuk").addClass('active show');
            $("#pills-surat-keluar").removeClass('active show');
            self.data.table_surat_masuk.ajax.reload();
        });
        $("#pills-surat-keluar-tab").click(function () {
            $("#pills-surat-keluar").addClass('active show');
            $("#pills-surat-masuk").removeClass('active show');
            self.data.table_surat_keluar.ajax.reload();
        });
        // Option Data
        $(".select2").select2();
        $("#form-collapse-keluar-penerima_surat").select2();
        // Surat Keluar
        self.data.tgl_surat_keluar = $("#form-collapse-keluar-tgl_surat").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment().format('D/M/YYYY'));

        self.data.table_surat_keluar = $("#table-surat-keluar").DataTable({
            serverSide: true,
            ajax: {
                url: '/sek/surat-menyurat/surat-keluar-masuk/json/surat-keluar',
                type: 'post',
                data: function (data) {
                    data.tahun = $("#filtering-tahun-surat-keluar").val();
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
                    width: "40%",
                    render: function (data) {
                        return "<p><b>" + data.nomor_surat_keluar + "</b><br/>" +
                            "<b>Perihal:</b> " + data.perihal_surat_keluar + "<br/>" +
                            "<b>Tanggal:</b> " + data.tanggal_surat_keluar_ + "<br/>" +
                            "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                    render: function (data) {
                        return "<p><b>Penerima: </b>" + data.penerima_surat_keluar + "<br/>" +
                            "<b>Kode: </b>" + data.kode_surat_keluar + "<br/>" +
                            "<b>Tanggal Inventaris: </b>" + data.tgl_created_ + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        if (data.total_personal_partisipant > 0) {
                            return "<a href='/" + data.path_surat_keluar + "' target='_blank' title='Lihat Arsip Surat' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_surat_keluar + "'><i class='fas fa-search'></i></a>" +
                                "<a href='/sek/surat-menyurat/surat-keluar-masuk/detail-surat-keluar/" + data.id_surat_keluar + "' title='Detail Akses Surat' class='btn btn-sm btn-success btn-edit mr-2' data-id='" + data.id_surat_keluar + "'><i class='fas fa-users'></i></a>" +
                                "<button title='Hapus Surat' class='btn btn-sm btn-danger btn-delete' data-id='" + data.id_surat_keluar + "' data-perihal='" + data.perihal_surat_keluar + "' ><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_surat_keluar + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
                        } else {
                            return "<a href='/" + data.path_surat_keluar + "' target='_blank' title='Lihat Arsip Surat' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_surat_keluar + "'><i class='fas fa-search'></i></a>" +
                                "<button title='Hapus Surat' class='btn btn-sm btn-danger btn-delete' data-id='" + data.id_surat_keluar + "' data-perihal='" + data.perihal_surat_keluar + "' ><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_surat_keluar + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
                        }
                    }
                },
                {
                    data: 'nomor_surat_keluar',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'perihal_surat_keluar',
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
        $("#filtering-tahun-surat-keluar").change(function () {
            self.data.table_surat_keluar.ajax.reload();
        });
        $("#btn-cari-data-keluar").click(function () {
            self.data.table_surat_keluar.search($("#cari-data-keluar").val()).draw();
        });
        $("#cari-data-keluar").keyup(function () {
            if (this.value === "") {
                self.data.table_surat_keluar.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.table_surat_keluar.search(this.value).draw();
            }
        });
        // Add
        $("#btn-tambah-surat-keluar").click(function () {
            $("#form-collapse-masuk").collapse("hide");
            $("#table-display").collapse("hide");
            $("#form-collapse-keluar").collapse("show");
            $("#form-collapse-keluar-id_surat").val("");
        });
        // On Cancel Click
        $("#form-collapse-keluar-btn-cancel").click(function () {
            $("#table-display").collapse("show");
            $("#form-collapse-keluar").collapse("hide");
            $("#form-collapse-keluar-id_surat").val("");
        });
        $("#pilihan-akses").change(function () {
            if ($(this).val() === "-2")
                $("#akses-surat-display").show();
            else
                $("#akses-surat-display").hide();
        });
        $("#form-collapse-masuk-penerima_surat").select2();
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
        $("#form-collapse-keluar-btn-save").click(function () {
            if (!$("#form-collapse-keluar-nomor_surat").val() || !$("#form-collapse-keluar-tgl_surat").val() || !$("#form-collapse-keluar-kode").val()
                || !$("#form-collapse-keluar-perihal").val() || !$("#form-collapse-keluar-alamat_tujuan").val() || !$("#suratKeluarFile").val())
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Nomor, Tanggal, Kode, Perihal, Penerima, dan File Arsip sudah terisi !"
                });
            else {
                $("#form-collapse-keluar-tgl_surat").val(moment(self.data.tgl_surat_keluar.datepicker('getDate')).format('YYYY-MM-DD'))
                $("#form-collapse-keluar-form_submit").submit();
            }
        });
        // On Delete
        $("#table-surat-keluar").on('click', 'button.btn-delete', function () {
            var id = $(this).data("id");
            var perihal = $(this).data('perihal');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin menghapus surat dengan perihal <b>' + perihal + '</b> dari sistem ?<br/><b class="text-danger">File Arsip Akan Terhapus dari Sistem</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/sek/surat-menyurat/surat-keluar-masuk/delete/surat-keluar',
                                method: 'POST',
                                data: {
                                    id_surat: id
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
                                    self.data.table_surat_keluar.ajax.reload();
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

        // Surat Masuk
        self.data.tgl_surat_masuk = $("#form-collapse-masuk-tgl_surat").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment().format('D/M/YYYY'));

        self.data.tgl_surat_masuk_diterima = $("#form-collapse-masuk-tgl_diterima").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment().format('D/M/YYYY'));

        // Add
        $("#btn-tambah-surat-masuk").click(function () {
            $("#form-collapse-keluar").collapse("hide");
            $("#table-display").collapse("hide");
            $("#form-collapse-masuk").collapse("show");
            $("#form-collapse-masuk-id_surat").val("");
        });
        // On Cancel Click
        $("#form-collapse-masuk-btn-cancel").click(function () {
            $("#table-display").collapse("show");
            $("#form-collapse-masuk").collapse("hide");
            $("#form-collapse-masuk-id_surat").val("");
        });
        $("#pilihan-akses-surat-masuk").change(function () {
            if ($(this).val() === "-2")
                $("#akses-surat-masuk-display").show();
            else
                $("#akses-surat-masuk-display").hide();
        });

        $("#form-collapse-masuk-btn-save").click(function () {
            if (!$("#form-collapse-masuk-nomor_berkas").val() || !$("#form-collapse-masuk-nomor_surat").val() || !$("#form-collapse-masuk-tgl_surat").val() || !$("#form-collapse-masuk-tgl_diterima").val() || !$("#form-collapse-masuk-kode").val()
                || !$("#form-collapse-masuk-perihal").val() || !$("#form-collapse-masuk-pengirim").val() || !$("#suratMasukFile").val())
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Nomor, Tanggal Surat, Tanggal Diterima, Kode, Perihal, Pengirim, dan File Arsip sudah terisi !"
                });
            else {
                $("#form-collapse-masuk-tgl_surat").val(moment(self.data.tgl_surat_masuk.datepicker('getDate')).format('YYYY-MM-DD'))
                $("#form-collapse-masuk-tgl_diterima").val(moment(self.data.tgl_surat_masuk_diterima.datepicker('getDate')).format('YYYY-MM-DD'))
                $("#form-collapse-masuk-form_submit").submit();
            }
        });

        self.data.table_surat_masuk = $("#table-surat-masuk").DataTable({
            serverSide: true,
            ajax: {
                url: '/sek/surat-menyurat/surat-keluar-masuk/json/surat-masuk',
                type: 'post',
                data: function (data) {
                    data.tahun = $("#filtering-tahun-surat-masuk").val();
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
                    width: "40%",
                    render: function (data) {
                        return "<p><b>" + data.nomor_surat_masuk + "</b><br/>" +
                            "<b>Perihal:</b> " + data.perihal_surat_masuk + "<br/>" +
                            "<b>Tanggal:</b> " + data.tanggal_surat_masuk_ + "<hr/>" +
                            "<b>Tanggal Diterima: </b>" + data.tanggal_surat_masuk_diterima_ + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                    render: function (data) {
                        return "<p><b>Pengirim: </b>" + data.pengirim_surat_masuk + "<br/>" +
                            "<b>Kode: </b>" + data.kode_surat_masuk + "<br/>" +
                            "<b>Tanggal Inventaris: </b>" + data.tgl_created_ + "<hr/>" +
                            "<b>Nomor Berkas: </b>" + data.nomor_berkas + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        if (data.total_personal_partisipant > 0) {
                            return "<a href='/" + data.path_surat_masuk + "' target='_blank' title='Lihat Arsip Surat' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_surat_masuk + "'><i class='fas fa-search'></i></a>" +
                                "<a href='/sek/surat-menyurat/surat-keluar-masuk/detail-surat-masuk/" + data.id_surat_masuk + "' title='Detail Akses Surat' class='btn btn-sm btn-success btn-edit mr-2' data-id='" + data.id_surat_masuk + "'><i class='fas fa-users'></i></a>" +
                                "<button title='Hapus Surat' class='btn btn-sm btn-danger btn-delete' data-id='" + data.id_surat_masuk + "' data-perihal='" + data.perihal_surat_masuk + "' ><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_surat_keluar + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
                        } else {
                            return "<a href='/" + data.path_surat_masuk + "' target='_blank'  title='Lihat Arsip Surat' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_surat_masuk + "'><i class='fas fa-search'></i></a>" +
                                "<button title='Hapus Surat' class='btn btn-sm btn-danger btn-delete' data-id='" + data.id_surat_masuk + "' data-perihal='" + data.perihal_surat_masuk + "' ><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_surat_keluar + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
                        }
                    }
                },
                {
                    data: 'nomor_surat_masuk',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'perihal_surat_masuk',
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
        $("#filtering-tahun-surat-masuk").change(function () {
            self.data.table_surat_masuk.ajax.reload();
        });
        $("#btn-cari-data-masuk").click(function () {
            self.data.table_surat_masuk.search($("#cari-data-masuk").val()).draw();
        });
        $("#cari-data-masuk").keyup(function () {
            if (this.value === "") {
                self.data.table_surat_masuk.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.table_surat_masuk.search(this.value).draw();
            }
        });

        // On Delete
        $("#table-surat-masuk").on('click', 'button.btn-delete', function () {
            var id = $(this).data("id");
            var perihal = $(this).data('perihal');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin menghapus surat dengan perihal <b>' + perihal + '</b> dari sistem ?<br/><b class="text-danger">File Arsip Akan Terhapus dari Sistem</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/sek/surat-menyurat/surat-keluar-masuk/delete/surat-masuk',
                                method: 'POST',
                                data: {
                                    id_surat: id
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
                                    self.data.table_surat_masuk.ajax.reload();
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
        if ($("#jenis_surat").val() === "SK")
            $("#pills-surat-keluar-tab").trigger('click');
        else
            $("#pills-surat-masuk-tab").trigger('click');
    },
};

jQuery(document).ready(function () {
    jQuery.surat_keluar_masuk.init();
});
