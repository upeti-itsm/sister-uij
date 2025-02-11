jQuery.sertifikat_labkom = {
    data: {
        table: $("#table")
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
                url: '/adm-uptti/akademik/sertifikat-labkom/json/daftar-pengajuan',
                type: 'post',
                data: function (d) {
                    d.status_pengajuan = $("#status_pengajuan").val()
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
                    sClass: 'text-left',
                    width: "55%",
                    render: function (data) {
                        var alasan = "Tgl. Pengajuan: " + data.tanggal_pengajuan;
                        if (data.id_status_pengajuan === 3)
                            alasan = "Alasan Penolakan: " + data.alasan_penolakan;
                        else if (data.id_status_pengajuan === 2)
                            alasan = "Nomor Sertifikat: " + data.nomor_sertifikat;
                        return "<b>" + data.nama_pengaju + " (" + data.nim_pengaju + ")</b><br/>" +
                            "Prodi : " + data.nama_prodi + " (" + data.kd_prodi + ")<hr/>" +
                            "Status Pengajuan : " + data.status_pengajuan + "<br/>" + alasan;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        return "<img src='https://siakad.itsm.ac.id/_report/photo_m/" + data.nim_pengaju + ".jpg' style='max-height: 100px' class='img img-fluid'>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "25%",
                    render: function (data) {
                        var disabled = "disabled";
                        if (data.id_status_pengajuan === 1)
                            disabled = "";
                        return "<button title='Setujui Pengajuan' class='btn btn-block btn-success btn-setujui mr-2' data-id='" + data.id_pengajuan_sertifikat + "' " + disabled + " data-nim='" + data.nim_pengaju + "'><span class='spinner-border spinner-border-sm mr-2' id='setujui-loading-spin-" + data.id_pengajuan_sertifikat + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-user-check'></i> Setujui</button>" +
                            "<button title='Tolak Pengajuan' class='btn btn-block btn-danger btn-tolak mr-2' data-id='" + data.id_pengajuan_sertifikat + "' " + disabled + "><span class='spinner-border spinner-border-sm mr-2' id='tolak-loading-spin-" + data.id_pengajuan_sertifikat + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-user-times'></i> Tolak</button>";
                    }
                },
                {
                    data: 'nim_pengaju',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'nama_pengaju',
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
        $("#status_pengajuan").change(function () {
            self.data.table.ajax.reload();
        });

        $("#table").on('click', 'button.btn-tolak', function () {
            var id = $(this).data("id");
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: '' +
                    '<form>' +
                    '<div class="form-group">' +
                    '<label>Masukkan Keterangan Validasi <small class="red">* Wajib diisi</small></label>' +
                    '<textarea placeholder="Keterangan" class="keterangan form-control" style="height: 80px" name="keterangan"></textarea>' +
                    '</div>' +
                    '</form>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            var keterangan = this.$content.find('.keterangan').val();
                            if (!keterangan) {
                                $.alert('Keterangan Wajib Diisi');
                                return false;
                            }
                            $.ajax({
                                url: '/adm-uptti/akademik/sertifikat-labkom/json/tolak-pengajuan',
                                method: 'POST',
                                data: {
                                    id_pengajuan: id,
                                    alasan: keterangan
                                },
                                beforeSend: function () {
                                    $("#tolak-loading-spin-" + id).show();
                                },
                                success: function (response) {
                                    if (response.is_success) {
                                        $.alert({
                                            title: "Informasi",
                                            type: "green",
                                            content: response.result
                                        });
                                    } else {
                                        $.alert({
                                            title: "Peringatan",
                                            type: "red",
                                            content: response.result
                                        });
                                    }
                                },
                                complete: function () {
                                    $("#tolak-loading-spin-" + id).hide();
                                    self.data.table.ajax.reload();
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

        $("#table").on('click', 'button.btn-setujui', function () {
            var id = $(this).data("id");
            var nim = $(this).data("nim");
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin menyetujui Pengajuan ini, Sertifikat akan otomatis di generate berdasarkan NOMOR SERTITIFKAT yang aktif',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/adm-uptti/akademik/sertifikat-labkom/json/terima-pengajuan',
                                method: 'POST',
                                data: {
                                    id_pengajuan: id,
                                    nim: nim
                                },
                                beforeSend: function () {
                                    $("#setujui-loading-spin-" + id).show();
                                },
                                success: function (response) {
                                    if (response.is_success) {
                                        $.alert({
                                            title: "Informasi",
                                            type: "green",
                                            content: response.result
                                        });
                                    } else {
                                        $.alert({
                                            title: "Peringatan",
                                            type: "red",
                                            content: response.result
                                        });
                                    }
                                },
                                complete: function () {
                                    $("#setujui-loading-spin-" + id).hide();
                                    self.data.table.ajax.reload();
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
    jQuery.sertifikat_labkom.init();
});
