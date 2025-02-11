jQuery.surat_keputusan = {
    data: {
        table_sk: $("#table-sk"),
        tgl_sk: $("#form-collapse-tgl_sk"),
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

        // Option Data
        $(".select2").select2();
        $("#form-collapse-sk-penerima_sk").select2();
        // Surat Keluar
        self.data.tgl_sk = $("#form-collapse-sk-tgl_sk").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment().format('D/M/YYYY'));

        self.data.table_sk = $("#table-sk").DataTable({
            serverSide: true,
            ajax: {
                url: '/sek/surat-menyurat/surat-keputusan/json',
                type: 'post',
                data: function (data) {
                    data.tahun = $("#filtering-tahun-sk").val();
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
                        return "<p><b>" + data.nomor_sk + "</b><br/>" +
                            "<small>Tanggal SK: " + data.tanggal_sk_ + "</small>" +
                            "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                    render: function (data) {
                        return "<p><b>" + data.nama_sk + "</b><br/>" +
                            "<small>Tanggal Inventaris: " + data.tgl_created_ + "</small></p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        if (data.total_personal_partisipant > 0) {
                            return "<a href='/" + data.path_sk + "' target='_blank' title='Lihat Arsip Surat' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_sk + "'><i class='fas fa-search'></i></a>" +
                                "<a href='/sek/surat-menyurat/surat-keputusan/detail-surat-keputusan/" + data.id_sk + "' title='Detail Akses Surat' class='btn btn-sm btn-success btn-edit mr-2' data-id='" + data.id_sk + "'><i class='fas fa-users'></i></a>" +
                                "<button title='Hapus Surat' class='btn btn-sm btn-danger btn-delete' data-id='" + data.id_sk + "' data-nama='" + data.nama_sk + "' ><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_sk + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
                        } else {
                            return "<a href='/" + data.path_sk + "' target='_blank' title='Lihat Arsip Surat' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_sk + "'><i class='fas fa-search'></i></a>" +
                                "<button title='Hapus Surat' class='btn btn-sm btn-danger btn-delete' data-id='" + data.id_sk + "' data-perihal='" + data.nama_sk + "' ><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_sk + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
                        }
                    }
                },
                {
                    data: 'nomor_sk',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'nama_sk',
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
        $("#filtering-tahun-sk").change(function () {
            self.data.table_sk.ajax.reload();
        });
        $("#btn-cari-data").click(function () {
            self.data.table_sk.search($("#cari-data").val()).draw();
        });
        $("#cari-data").keyup(function () {
            if (this.value === "") {
                self.data.table_sk.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.table_sk.search(this.value).draw();
            }
        });
        $("#pilihan-akses-surat-keputusan").change(function () {
            if ($(this).val() === "-2")
                $("#akses-display").show();
            else
                $("#akses-display").hide();
        });
        // Add
        $("#btn-tambah-sk").click(function () {
            $("#table-display").collapse("hide");
            $("#form-collapse-sk").collapse("show");
            $("#form-collapse-sk-id_sk").val("");
        });
        // On Cancel Click
        $("#form-collapse-sk-btn-cancel").click(function () {
            $("#table-display").collapse("show");
            $("#form-collapse-sk").collapse("hide");
            $("#form-collapse-sk-id_sk").val("");
        });

        // On Save Data
        $("#form-collapse-sk-btn-save").click(function () {
            if (!$("#form-collapse-sk-nomor_sk").val() || !$("#form-collapse-sk-tgl_sk").val() || !$("#form-collapse-sk-nama_sk").val() || !$("#SK_File").val())
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Nomor, Tanggal, Nama SK, dan File Arsip sudah terisi !"
                });
            else {
                $("#form-collapse-sk-tgl_sk").val(moment(self.data.tgl_sk.datepicker('getDate')).format('YYYY-MM-DD'))
                $("#form-collapse-sk-form_submit").submit();
            }
        });
        // On Delete
        $("#table-sk").on('click', 'button.btn-delete', function () {
            var id = $(this).data("id");
            var nama_sk = $(this).data('nama');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin menghapus SK dengan nama <b>' + nama_sk + '</b> dari sistem ?<br/><b class="text-danger">File Arsip Akan Terhapus dari Sistem</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/sek/surat-menyurat/surat-keputusan/delete',
                                method: 'POST',
                                data: {
                                    id_sk: id
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
                                    self.data.table_sk.ajax.reload();
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
    jQuery.surat_keputusan.init();
});
