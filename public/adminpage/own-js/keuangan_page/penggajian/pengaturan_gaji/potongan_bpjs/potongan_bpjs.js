jQuery.potongan_koperasi = {
    data: {
        table: $("#table"),
        bulan: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        isSyncCanceled: false,
        log_table: $("#log-table").DataTable({
            scrollY: '400px',
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
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        // Option Data
        $(".select2").select2();
        $(".custom-file-input").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
        $("#btn_upload_potongan").click(function () {
            $("#modal-upload-potongan-koperasi").modal('show');
        });

        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/keu/penggajian/pengaturan-gaji/potongan-bpjs/json',
                type: 'post',
                data: function (data) {
                    data.bulan = $("#bulan").val();
                    data.tahun = $("#tahun").val();
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
                        return "<p><b>" + data.informasi_potongan_bpjs + "</b>" +
                            "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        return "<p><b>" + data.ket_bulan_tahun + "</b><br/>" +
                            "<small>Tanggal Upload : " + data.tanggal_upload + "</small></p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "30%",
                    render: function (data) {
                        return "<a class='btn btn-primary btn-sm mr-2' title='Detail Peserta' href='/keu/penggajian/pengaturan-gaji/potongan-bpjs/detail/" + data.periode_rekap + "/" + data.tahun + "'><i class='fas fa-eye mr-2'></i>Detail</a>" +
                            "<a target='_blank' class='btn btn-danger btn-sm' title='Export Peserta' href='/keu/penggajian/pengaturan-gaji/potongan-bpjs/export/pdf/" + data.periode_rekap + "/" + data.tahun + "'><i class='fas fa-file-pdf mr-2'></i>Download</a>";
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
        $("#sub-menu").on('click', 'a', function () {
            location.href = $(this).attr('href');
        });
        $("#bulan").change(function () {
            self.data.table.ajax.reload();
        });
        $("#tahun").change(function () {
            self.data.table.ajax.reload();
        });
        $("#btn-submit-file").click(function () {
            if ($("#add-bulan").val() && $("#add-tahun").val() && $("#customFile").val()) {
                $.confirm({
                    title: 'Konfirmasi !',
                    type: 'orange',
                    content: 'Apakah anda yakin potongan dan tunjangan yang anda upload sudah benar ? <b style="color: red">Akan Berpengaruh pada Gaji Pegawai</b>',
                    buttons: {
                        confirm: {
                            text: 'Yakin',
                            btnClass: 'btn-green',
                            keys: ['enter'],
                            action: function () {
                                self.isSyncCanceled = false;
                                $("#modal-upload-potongan-koperasi").modal('hide');
                                $("#progress-bar-syncron-ulang").show();
                                $("#log-syncron-ulang").show();
                                $("#btn-cancel-syncron-ulang").show();
                                $("#loading-progress").show();
                                $("#keterangan-progress").text("Mohon menunggu hingga proses upload selesai ...");
                                $("#row-list-data").hide();
                                var formData = new FormData();
                                formData.append('file', $('#customFile')[0].files[0]);
                                $.ajax({
                                    url: '/keu/penggajian/pengaturan-gaji/potongan-bpjs/upload',
                                    type: 'POST',
                                    data: formData,
                                    processData: false,  // tell jQuery not to process the data
                                    contentType: false,  // tell jQuery not to set contentType
                                    success: function (data) {
                                        self.next_data(data, $("#add-bulan").val(), $("#add-tahun").val());
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
            } else {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Periode, Tahun dan File Potongan sudah terisi !"
                });
            }
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
                content: 'Proses upload akan dihentikan, apakah anda yakin ?<br/><b class="text-danger">Data yang sudah dimasukkan tetap tersimpan</b>',
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
    },
    next_data: function (data, periode, tahun, index = 0, progres = 0, failed = 0, inserted = 0) {
        console.log(data[index][1]);
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/keu/penggajian/pengaturan-gaji/potongan-bpjs/insert',
            method: 'post',
            data: {
                'id_karyawan': data[index][0],
                'tunjangan': data[index][2],
                'potongan_kesehatan': data[index][3],
                'potongan_ketenagakerjaan': data[index][4],
                'periode': periode,
                'tahun': tahun,
            },
            success: function (result) {
                if (result.status === 1) {
                    self.data.log_table.row.add([
                        (index + 1),
                        data[index][1] + " (" + data[index][2] + ")",
                        "<i class='fas fa-check-circle text-success p-1'></i> " + result.keterangan,
                        data[index][1],
                        data[index][2],
                        "inserted"
                    ]).draw();
                    inserted++;
                } else {
                    self.data.log_table.row.add([
                        (index + 1),
                        data[index][1] + " (" + data[index][2] + ")",
                        "<i class='fas fa-times-circle text-danger p-1'></i> " + result.keterangan,
                        data[index][1],
                        data[index][1],
                        "gagal"
                    ]).draw();
                    failed++;
                }
                progres++;
            },
            complete: function () {
                $("#progress-bar").width((progres / n * 100).toFixed(2) + '%');
                $("#progress-text").text((progres / n * 100).toFixed(2) + '% Complete');
                $("#btn-failed-log").text("Failed : " + failed);
                $("#btn-inserted-log").text("Inserted : " + inserted);
                if (index >= (data.length - 1)) {
                    self.data.table.search("").draw();
                    $("#row-list-data").show();
                    $("#log-syncron-ulang").show();
                    $("#progress-bar-syncron-ulang").hide();
                } else {
                    if (!self.isSyncCanceled) {
                        index++;
                        self.next_data(data, periode, tahun, index, progres, failed, inserted)
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
    jQuery.potongan_koperasi.init();
});
