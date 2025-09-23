jQuery.kurikulum = {
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
                url: '/adm-akademik/akademik/kurikulum/json',
                type: 'post',
                data: function (data) {
                    data.id = $("#kd_prodi").val();
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
                    width: "25%",
                    render: function (data) {
                        return "<p>" + data.nama_kurikulum + " (" + data.tahun_kurikulum + ")</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                    render: function (data) {
                        return "<p>" + data.nama_program_studi + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                    render: function (data) {
                        return "<b>" + data.sks_lulus + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "10%",
                    render: function (data) {
                        const status = data.sts_aktif_data ? 'Aktif' : 'Non-Aktif';
                        return "<b>" + status + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        return "<button title='Edit Kurikulum' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_kurikulum + "' data-id_prodi='" + data.kd_program_studi + "' data-nama_kurikulum='" + data.nama_kurikulum + "' data-sks_lulus='" + data.sks_lulus + "' data-tahun_kurikulum='" + data.tahun_kurikulum + "'><i class='fas fa-edit'></i></button>" +
                            "<button title='Non-Aktifkan Kurikulum' class='btn btn-sm btn-danger btn-delete' data-id='" + data.id_kurikulum + "' data-nama_kurikulum='" + data.nama_kurikulum + "' data-nama_prodi='" + data.nama_program_studi + "' ><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_kurikulum + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
                    }
                },
                {
                    data: 'nama_kurikulum',
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
            $("#id_kurikulum").val("");
            $("#id_prodi_add").val("").change();
            $("#id_prodi_add").attr('disabled', false);
        });
        // On Cancel Click
        $("#btn-cancel").click(function () {
            $("#nama_kurikulum").val("");
            $("#tahun_kurikulum").val("");
            $("#sks_lulus").val("");
            $("#cari-data").val("");
            $("#filter-collapse").collapse("show");
            $("#form-collapse").collapse("hide");
        });
        // On Edit
        $("#table").on('click', 'button.btn-edit', function () {
            $("#nama_kurikulum").val($(this).data("nama_kurikulum"));
            $("#tahun_kurikulum").val($(this).data("tahun_kurikulum"));
            $("#sks_lulus").val($(this).data("sks_lulus"));
            $("#btn-tambah-data").trigger("click");
            $("#id_kurikulum").val($(this).data("id"));
            $("#id_prodi_add").val($(this).data("id_prodi")).change();
            $("#id_prodi_add").attr('disabled', true);
        });

        // On Save Data
        $("#btn-save").click(function () {
            if (!$("#nama_kurikulum").val() || !$("#tahun_kurikulum").val() || !$("#sks_lulus").val())
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Nama, Tahun dan SKS Lulus Kurikulum Terisi"
                });
            else {
                var operasi = 'store';
                var id = '00000000-0000-0000-0000-000000000000'
                if ($("#id_kurikulum").val()) {
                    id = $("#id_kurikulum").val();
                    operasi = 'update';
                }
                $.ajax({
                    url: '/adm-akademik/akademik/kurikulum/' + operasi,
                    method: 'POST',
                    data: {
                        kd_prodi: $("#id_prodi_add").val(),
                        nama_kurikulum: $("#nama_kurikulum").val(),
                        tahun_kurikulum: $("#tahun_kurikulum").val(),
                        sks_lulus: $("#sks_lulus").val(),
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
            var nama_kurikulum = $(this).data('nama_kurikulum');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin Menon-Aktifkan <b>' + nama_kurikulum + '</b> dari Kurikulum ?',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/adm-akademik/akademik/kurikulum/delete',
                                method: 'POST',
                                data: {
                                    id: id,
                                    status: false,
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
    jQuery.kurikulum.init();
});
