jQuery.suspended_mahasiswa = {
    data: {
        isSyncCanceled: false,
        log_table: $("#log-table").DataTable({
            scrollY: '300px',
            columns: [
                {width: "5%", sClass: 'text-center', searchable: false},
                {width: "50%", searchable: false},
                {width: "45%", sClass: 'text-center', searchable: false},
                {searchable: true, visible: false},
                {searchable: true, visible: false},
                {searchable: true, visible: false},
            ],
            scrollCollapse: true,
            paging: true,
            processing: true,
            pageLength: 10,
            ordering: false,
            lengthChange: false,
            autoWidth: false,
            sDom: 'ltipr',
            language: {
                "emptyTable": "Tidak ditemukan data"
            },
        }),
        table: $("#table"),
    },
    init: function () {
        var self = this;
        numeral.register('locale', 'id', {
            delimiters: {
                thousands: '.',
                decimal: ','
            },
            abbreviations: {
                thousand: 'k',
                million: 'm',
                billion: 'b',
                trillion: 't'
            },
            ordinal: function (number) {
                return number === 1 ? 'er' : 'ème';
            },
            currency: {
                symbol: 'Rp.'
            }
        });
        numeral.locale('id')
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
                url: '/super-admin/moodle/mahasiswa/json/daftar-suspended-mahasiswa',
                type: 'post',
                data: function (data) {
                    data.angkatan = $("#angkatan").val();
                }
            },
            scrollY: '500px',
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
                    width: "35%",
                    render: function (data) {
                        return "<b>" + data.nama_lengkap + " (" + data.npk + ")</b><br/>" +
                            "<small>Email : " + data.email + " | Password : " + data.password + "</small><br/>" +
                            "<small>Jenis Pendanaan : " + data.jenis_pendanaan + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return "<b>" + data.nama_prodi + " (" + data.angkatan + ")</b><br/>" +
                            "<small>Nomor HP : " + data.no_hp + " | Kota Rumah : " + data.kota + "</small><br/>" +
                            "<small>Alamat : " + data.alamat_rumah + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        return "<button title='Lihat Tanggungan' class='btn btn-sm btn-info-soft btn-lihat-tanggungan mr-2' data-npk='" + data.npk + "'><span class='spinner-border spinner-border-sm mr-2' id='lihat-tanggungan-loading-spin-" + data.npk + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-eye'></i></button>" +
                            "<button title='Lihat Transaksi' class='btn btn-sm btn-success-soft btn-lihat-transaksi mr-2' data-npk='" + data.npk + "'><span class='spinner-border spinner-border-sm mr-2' id='lihat-transaksi-loading-spin-" + data.npk + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-edit'></i></button>" +
                            "<button title='Un-Suspend Mahasiswa' class='btn btn-sm btn-danger btn-un-suspend mr-2' data-npk='" + data.npk + "' data-nama='" + data.nama_lengkap + "'><span class='spinner-border spinner-border-sm mr-2' id='unsuspend-loading-spin-" + data.npk + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-unlock'></i></button>";
                    }
                },
                {
                    data: 'nama_lengkap',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'npk',
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
        $("#angkatan").change(function () {
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
        $("#btn-suspend").click(function () {
            $("#modal-suspend-mahasiswa").modal('show');
        });
        $("#modal-btn-suspend").click(function () {
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Apakah anda yakin akan melakukan Suspend Data ? <b style="color: red">Akun yang memiliki tanggungan lebih dari batas minimal akan tersuspend</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $("#modal-suspend-mahasiswa").modal('hide');
                            self.isSyncCanceled = false;
                            $("#progress-bar-syncron-ulang").show();
                            $("#log-syncron-ulang").show();
                            $("#btn-cancel-syncron-ulang").show();
                            $("#loading-progress").show();
                            $("#keterangan-progress").text("Mohon menunggu hingga proses sinkronisasi selesai ...");
                            $("#row-list-data").hide();
                            $.ajax({
                                url: '/super-admin/moodle/mahasiswa/json/get-tanggungan-mahasiswa-siakad',
                                method: 'post',
                                data: {
                                    batas: $("#batas_tanggungan").val(),
                                    angkatan: $("#angkatan_sync").val(),
                                    jenis_kelas: $("#jenis_kelas").val()
                                },
                                success: function (result) {
                                    self.next_data(result, "Memiliki Tanggungan di atas Rp. " + $("#batas_tanggungan").val());
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
        $("#btn-cari-log").click(function () {
            self.data.log_table.search($("#cari-log").val()).draw();
        });
        $("#cari-log").keyup(function () {
            if (this.value === "") {
                self.data.log_table.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.log_table.search(this.value).draw();
            }
        });
        $("#status-log").on('change', function () {
            self.data.log_table.search(this.value).draw();
        });
        $("#btn-gagal-log").click(function () {
            $("#status-log").val('gagal').trigger('change');
        });
        $("#btn-sukses-log").click(function () {
            $("#status-log").val('sukses').trigger('change');
        });
        $("#btn-tutup-log").click(function () {
            $("#log-syncron-ulang").hide();
        });
        $("#btn-cancel-syncron-ulang").click(function () {
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Proses sinkronisasi akan dihentikan, apakah anda yakin ?',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            self.isSyncCanceled = true;
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red'
                    }
                }
            })
        });
        $("#table").on('click', 'button.btn-lihat-tanggungan', function () {
            var npk = $(this).data("npk");
            $.ajax({
                url: '/super-admin/moodle/mahasiswa/json/get-tanggungan-mahasiswa',
                method: 'POST',
                data: {
                    npk: npk
                },
                beforeSend: function () {
                    $("#lihat-tanggungan-loading-spin-" + npk).show();
                },
                success: function (response) {
                    $.alert({
                        title: "Informasi",
                        type: "blue",
                        content: "Jumlah Tanggungan Mahasiswa Sebesar Rp. " + numeral(response[0].sisa_tanggungan).format('0,0')
                    });
                },
                complete: function () {
                    $("#lihat-tanggungan-loading-spin-" + npk).hide();
                }
            })
        });
        $("#table").on('click', 'button.btn-lihat-transaksi', function () {
            var npk = $(this).data("npk");
            $.ajax({
                url: '/super-admin/moodle/mahasiswa/json/get-transaksi-mahasiswa',
                method: 'POST',
                data: {
                    npk: npk
                },
                beforeSend: function () {
                    $("#lihat-transaksi-loading-spin-" + npk).show();
                },
                success: function (response) {
                    var row = "<tr>" +
                        "<td colspan='9' style='text-align: center'>Tidak Ada Data</td>" +
                        "</tr>";
                    if (response.length > 0) {
                        row = "";
                        $.each(response, function (key, value) {
                            row = row + "<tr>" +
                                "<td>" + value.kode_biaya + "</td>" +
                                "<td>" + value.nama_biaya + "</td>" +
                                "<td>" + value.jumlah_bayar + "</td>" +
                                "<td>" + value.semester + "</td>" +
                                "<td>" + value.tgl_bayar + "</td>" +
                                "<td>" + value.id_kwitansi + "</td>" +
                                "<td>" + value.jenis_bayar + "</td>" +
                                "<td>" + value.keterangan + "</td>" +
                                "<td>" + value.denda + "</td>" +
                                "</tr>";
                        });
                    }
                    $.alert({
                        title: "Informasi",
                        type: "blue",
                        columnClass: "xlarge",
                        content: "<div class='table-responsive'>" +
                            "<table class='table table-striped'>" +
                            "<thead class='bg-info text-white'>" +
                            "<td>KODE</td>" +
                            "<td>NAMA BIAYA</td>" +
                            "<td>JUMLAH</td>" +
                            "<td>SEMESTER</td>" +
                            "<td>TGL BAYAR</td>" +
                            "<td>NO. KWIT.</td>" +
                            "<td>J. BYR</td>" +
                            "<td>KET</td>" +
                            "<td>DENDA</td>" +
                            "</thead>" +
                            "<tbody>" + row +
                            "</tbody>" +
                            "</table>" +
                            "</div>"
                    });
                },
                complete: function () {
                    $("#lihat-transaksi-loading-spin-" + npk).hide();
                }
            })
        });
        $("#table").on('click', 'button.btn-un-suspend', function () {
            var npk = $(this).data("npk");
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin mencabut suspend mahasiswa dengan NIM: ' + npk + ' ?<br/><b class="text-danger">akun akan kembali aktif di elearning</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/super-admin/moodle/mahasiswa/json/un-suspend-mahasiswa-by-npk',
                                method: 'POST',
                                data: {
                                    username: npk,
                                    user_type: 'ST'
                                },
                                beforeSend: function () {
                                    $("#unsuspend-loading-spin-" + npk).show();
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
                                    self.data.table.ajax.reload();
                                    $("#unsuspend-loading-spin-" + npk).hide();
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
    next_data: function (data, alasan, index = 0, progres = 0, failed = 0, inserted = 0) {
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/super-admin/moodle/mahasiswa/json/suspend-mahasiswa-by-npk',
            method: 'post',
            data: {
                'npk': data[index].NPK,
                'alasan': alasan,
            },
            success: function (result) {
                if (result.is_success) {
                    self.data.log_table.row.add([
                        (index + 1),
                        data[index].nama_lengkap + " (" + data[index].NPK + ")",
                        "<i class='fas fa-check-square text-success p-1'></i> " + result.result,
                        data[index].nama_lengkap,
                        data[index].NPK,
                        "sukses"
                    ]).draw();
                    inserted++;
                } else {
                    self.data.log_table.row.add([
                        (index + 1),
                        data[index].nama_lengkap + " (" + data[index].NPK + ")",
                        "<i class='fas fa-times-circle text-danger p-1'></i> " + result.result,
                        data[index].nama_lengkap,
                        data[index].NPK,
                        "gagal"
                    ]).draw();
                    failed++;
                }
                progres++;
            },
            error: function () {
                self.data.log_table.row.add([
                    (index + 1),
                    data[index].nama_mata_kuliah + " (" + data[index].kelas_id + ")",
                    "<i class='fas fa-times-circle text-danger p-1'></i> ",
                    data[index].nama_lengkap,
                    data[index].nama_asisten,
                    "gagal"
                ]).draw();
                failed++;
                progres++;
            },
            complete: function () {
                $("#progress-bar").width((progres / n * 100).toFixed(2) + '%');
                $("#progress-text").text((progres / n * 100).toFixed(2) + '% Complete');
                $("#btn-failed-log").text("Failed : " + failed);
                $("#btn-inserted-log").text("Suspended : " + inserted);
                if (index >= (data.length - 1)) {
                    self.data.table.search("").draw();
                    $("#row-list-data").show();
                    $("#log-syncron-ulang").show();
                    $("#progress-bar-syncron-ulang").hide();
                } else {
                    if (!self.isSyncCanceled) {
                        index++;
                        self.next_data(data, alasan, index, progres, failed, inserted)
                    } else {
                        $("#loading-progress").hide();
                        $("#keterangan-progress").text("Dibatalkan oleh pengguna");
                        $("#row-list-data").show();
                        $("#log-syncron-ulang").show();
                        $("#btn-cancel-syncron-ulang").hide();
                    }
                }
            }
        })
    },
};

jQuery(document).ready(function () {
    jQuery.suspended_mahasiswa.init();
});
