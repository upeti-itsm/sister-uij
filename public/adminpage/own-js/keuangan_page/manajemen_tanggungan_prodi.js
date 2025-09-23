jQuery.manajemen_tanggungan_prodi = {
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
                url: '/keu/manajemen-tanggungan-prodi/json',
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
                    sClass: 'text-center align-middle',
                    width: "5%",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left align-middle',
                    width: "15%",
                    render: function (data) {
                        return "<b>" + data.kd_prodi + "</b></br><hr/>" +
                            data.nama_prodi;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left align-middle',
                    width: "20%",
                    render: function (data) {
                        return "<p>" + data.jenis_tagihan + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left align-middle',
                    width: "15%",
                    render: function (data) {
                        return "<b>" + data.jumlah_tagihan_rupiah + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left align-middle',
                    width: "20%",
                    render: function (data) {
                        return "Semester Mulai: <b>" + data.semester_mulai + "</b><hr/></br>Semester Selesai: <b>" + data.semester_selesai + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left align-middle',
                    width: "10%",
                    render: function (data) {
                        return "<b>" + data.tipe_periodisasi + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center align-middle',
                    width: "15%",
                    render: function (data) {
                        return "<button title='Edit Ruangan Perkuliahan' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_tagihan_prodi + "' data-jenis_tagihan='" + data.id_jenis_tagihan + "' data-jumlah_tagihan='" + data.jumlah_tagihan + "' data-kd_prodi='" + data.kd_prodi + "' data-nama_prodi='" + data.nama_prodi + "' data-semester_mulai='" + data.semester_mulai + "'data-semester_selesai='" + data.semester_selesai + "' data-sts_aktif='" + data.sts_aktif + "' data-tipe_periodisasi='" + data.tipe_periodisasi + "'><i class='fas fa-edit'></i></button>" +
                            "<button title='Non-Aktifkan Ruangan Perkuliahan' class='btn btn-sm btn-danger btn-delete' data-id='" + data.id_tagihan_prodi + "' data-jenis_tagihan='" + data.jenis_tagihan + "' data-jumlah_tagihan='" + data.jumlah_tagihan_rupiah + "' ><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_tagihan_prodi + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
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
            $("#id_tagihan_prodi").val("");
        });
        // On Cancel Click
        $("#btn-cancel").click(function () {
            $("#prodi").val("");
            $("#jenis_tagihan").val("");
            $("#jumlah_tagihan").val("");
            $("#tipe_periodisasi").val("");
            $("#semester_mulai").val("");
            $("#semester_selesai").val("");
            $("#status_tanggungan").val("");
            $("#cari-data").val("");
            $("#filter-collapse").collapse("show");
            $("#form-collapse").collapse("hide");
        });
        // On Edit
        $("#table").on('click', 'button.btn-edit', function () {
            $("#jenis_tagihan").val($(this).data("jenis_tagihan")).trigger("change");
            $("#jumlah_tagihan").val($(this).data("jumlah_tagihan"));
            $("#prodi").val($(this).data("kd_prodi")).trigger("change");
            $("#semester_mulai").val($(this).data("semester_mulai"));
            $("#semester_selesai").val($(this).data("semester_selesai"));
            let sts = $(this).data("sts_aktif");
            $("#status_tanggungan").val(sts === true || sts === "true" ? "1" : "0").trigger("change");
            $("#tipe_periodisasi").val($(this).data("tipe_periodisasi")).trigger("change");
            $("#btn-tambah-data").trigger("click");
            $("#id_tagihan_prodi").val($(this).data("id"));
        });

        // On Save Data
        $("#btn-save").click(function () {
            if (!$("#prodi").val() || !$("#jenis_tagihan").val() || !$("#tipe_periodisasi").val() || !$("#semester_mulai").val() || !$("#status_tanggungan").val())
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Program Studi, Jenis Tagihan, Tipe Periodisasi, Semester Mulai dan Status Tanggungan Terisi"
                });
            else {
                var operasi = 'store';
                var id = '00000000-0000-0000-0000-000000000000';
                if ($("#id_tagihan_prodi").val()) {
                    id = $("#id_tagihan_prodi").val();
                    operasi = 'update';
                }
                $.ajax({
                    url: '/keu/manajemen-tanggungan-prodi/' + operasi,
                    method: 'POST',
                    data: {
                        prodi: $("#prodi").val(),
                        jenis_tagihan: $("#jenis_tagihan").val(),
                        jumlah_tagihan: $("#jumlah_tagihan").val(),
                        tipe_periodisasi: $("#tipe_periodisasi").val(),
                        semester_mulai: $("#semester_mulai").val(),
                        semester_selesai: $("#semester_selesai").val() || null,
                        status_tanggungan: $("#status_tanggungan").val(),
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
            var jenis_tagihan = $(this).data("jenis_tagihan");
            var jumlah_tagihan = $(this).data("jumlah_tagihan");
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin ingin menghapus tagihan <b>' + jenis_tagihan + '</b> dengan jumlah <b>' + jumlah_tagihan + '</b> ?',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/keu/manajemen-tanggungan-prodi/delete',
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
    jQuery.manajemen_tanggungan_prodi.init();
});
