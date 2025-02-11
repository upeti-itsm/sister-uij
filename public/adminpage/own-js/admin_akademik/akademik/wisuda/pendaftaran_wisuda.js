jQuery.pendaftaran_wisuda = {
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
                url: '/adm-akademik/akademik/pendaftaran-wisuda/json',
                type: 'post',
                data: function (d) {
                    d.status_pengajuan = $("#status_pengajuan").val()
                    d.kd_prodi = $("#kd_prodi").val()
                    d.konsentrasi = $("#kd_konsen").val()
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
                    width: "40%",
                    render: function (data) {
                        var kelas = 'Reguler Pagi';
                        if (data.jenis_kelas_siakad === 'TRF')
                            kelas = 'Transfer Pagi';
                        else if (data.jenis_kelas_siakad === 'REGM')
                            kelas = 'Reguler Malam';
                        else if (data.jenis_kelas_siakad === 'TRFM')
                            kelas = 'Transfer Malam';
                        var alasan = "Tgl. Daftar: " + data.tanggal_pendaftaran_wisuda;
                        if (data.id_status_pendaftaran_wisuda === 3)
                            alasan = "Alasan Penolakan: " + data.alasan_penolakan;
                        else if (data.id_status_pendaftaran_wisuda === 2)
                            alasan = "Nomor Pendaftaran: " + data.nomor_pendaftaran_wisuda;
                        return "<b>" + data.nama_mahasiswa + " (" + data.nim + ")</b><br/>" +
                            "Prodi : " + data.nama_prodi + " (" + data.kd_prodi + ")<br/>" +
                            "Kelas : " + kelas + "<hr/>" +
                            "Status Pendaftaran : " + data.status_pendaftaran_wisuda + "<br/>" + alasan + "<hr/>" +
                            "DPU: " + data.dpu + "<br/>" +
                            "DPA: " + data.dpa + "<hr/>" +
                            "Kesan Pesan: <br/>" + data.kesan_pesan;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        return "<img src='https://siakad.itsm.ac.id/_report/photo_m/" + data.nim + ".jpg' style='max-height: 150px' class='img img-fluid'>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        return "<a href='/files/dokumen_pendukung_wisuda/" + data.nim + "/" + data.path_dokumen_pendaftaran_wisuda + "' target='_blank'><i class='fas fa-file mr-2'></i> Lihat Dokumen Pendukung</a>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        var disabled = "disabled";
                        if (data.id_status_pendaftaran_wisuda === 1)
                            disabled = "";
                        return "<button title='Setujui Pengajuan' class='btn btn-block btn-success btn-setujui mr-2' data-ipk='" + data.ipk + "' data-id='" + data.id_pendaftaran_wisuda + "' " + disabled + " data-id_dpu='" + data.id_dpu + "' data-id_dpa='" + data.id_dpa + "'><span class='spinner-border spinner-border-sm mr-2' id='setujui-loading-spin-" + data.id_pendaftaran_wisuda + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-user-check'></i> Setujui</button>" +
                            "<button title='Tolak Pengajuan' class='btn btn-block btn-danger btn-tolak mr-2' data-ipk='" + data.ipk + "' data-id='" + data.id_pendaftaran_wisuda + "' " + disabled + "><span class='spinner-border spinner-border-sm mr-2' id='tolak-loading-spin-" + data.id_pendaftaran_wisuda + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-user-times'></i> Tolak</button>";
                    }
                },
                {
                    data: 'nim',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'nama_mahasiswa',
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
        $("#status_pengajuan, #kd_prodi, #kd_konsen").change(function () {
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
                    '<label>Masukkan Alasan Penolakan <small class="red">* Wajib diisi</small></label>' +
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
                                url: '/adm-akademik/akademik/pendaftaran-wisuda/denied',
                                method: 'POST',
                                data: {
                                    id_pendaftaran: id,
                                    alasan: keterangan
                                },
                                beforeSend: function () {
                                    $("#tolak-loading-spin-" + id).show();
                                },
                                success: function (response) {
                                    if (response.status === 1) {
                                        $.alert({
                                            title: "Informasi",
                                            type: "green",
                                            content: response.keterangan
                                        });
                                    } else {
                                        $.alert({
                                            title: "Peringatan",
                                            type: "red",
                                            content: response.keterangan
                                        });
                                    }
                                },
                                complete: function () {
                                    $("#tolak-loading-spin-" + id).hide();
                                    self.data.table.ajax.reload();
                                },
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
            $("#id_pendaftaran").val(id);
            var ipk = $(this).data("ipk");
            $("#ipk").val(ipk);
            var id_dpu = $(this).data("id_dpu");
            $("#dpu").val(id_dpu).change();
            var id_dpa = $(this).data("id_dpa");
            $("#dpa").val(id_dpa).change();
            $("#modal-persetujuan-pendaftaran").modal('show');
        });

        $("#btn-export-excel").click(function () {
            window.open('/adm-akademik/akademik/pendaftaran-wisuda/export/excel/' + $("#status_pengajuan").val() + '/' + $("#kd_prodi").val() + '/' + $("#kd_konsen").val());
        });

        $("#btn-export-pdf").click(function () {
            window.open('/adm-akademik/akademik/pendaftaran-wisuda/export/pdf/' + $("#status_pengajuan").val() + '/' + $("#kd_prodi").val() + '/' + $("#kd_konsen").val());
        });

        $("#modal-btn-setujui").click(function () {
            $("#form-persetujuan-wisuda").submit();
        });
    },
};

jQuery(document).ready(function () {
    jQuery.pendaftaran_wisuda.init();
});
