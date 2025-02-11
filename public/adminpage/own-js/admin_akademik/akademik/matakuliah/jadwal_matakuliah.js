jQuery.jadwal_matakuliah = {
    data: {
        isSyncCanceled: false,
        log_table_jadwal_kuliah: $("#log-table-jadwal-kuliah").DataTable({
            scrollY: '300px',
            columns: [
                {width: "5%", sClass: 'text-center', searchable: false},
                {width: "50%", searchable: false},
                {width: "45%", sClass: 'text-center', searchable: false},
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
        table_jadwal_kuliah: $("#table-jadwal-kuliah"),
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
        $("#prodi").change(function () {
            self.data.table_jadwal_kuliah.ajax.reload();
        });
        $("#angkatan").change(function () {
            self.data.table_jadwal_kuliah.ajax.reload();
        });
        self.data.table_jadwal_kuliah = $("#table-jadwal-kuliah").DataTable({
            serverSide: true,
            ajax: {
                url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/json',
                type: 'post',
                data: function (data) {
                    data.tahun_akademik = $("#tahun_akademik").val();
                    data.prodi = $("#prodi").val();
                    data.status = $("#status_pengajar").val();
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
                    width: "25%",
                    render: function (data) {
                        return "<b>" + data.nama_mata_kuliah + " (" + data.kelas_id + " - " + data.jenis_kelas + ")</b><br/>" +
                            "TA : " + data.tahun_akademik + " | SKS : " + data.jml_sks;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                    render: function (data) {
                        if (data.asisten_id) {
                            if (data.id_jenis_jadwal === 2 || data.id_jenis_jadwal === 3) {
                                if (data.dosen_id === data.koordinator_id)
                                    return "<b>1. " + data.nama_dosen + "</b> <span class='badge badge-info'>Co</span><br/>" +
                                        "<b>2. " + data.nama_asisten + "</b>";
                                else if (data.asisten_id === data.koordinator_id)
                                    return "<b>1. " + data.nama_dosen + "</b><br/>" +
                                        "<b>2. " + data.nama_asisten + "</b> <span class='badge badge-info'>Co</span>";
                                else
                                    return "<b>1. " + data.nama_dosen + "</b><br/>" +
                                        "<b>2. " + data.nama_asisten + "</b>";
                            } else
                                return "<b>1. " + data.nama_dosen + "</b><br/>" +
                                    "<b>2. " + data.nama_asisten + "</b>";
                        } else
                            return "<b>" + data.nama_dosen + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        var jenis = "Belum Ditentukan";
                        var kelas = "btn-danger-soft";
                        if (data.id_jenis_jadwal === 1) {
                            jenis = data.jenis_jadwal;
                            kelas = "btn-success-soft";
                        } else if (data.id_jenis_jadwal === 2 || data.id_jenis_jadwal === 3) {
                            jenis = data.jenis_jadwal;
                            kelas = "btn-info-soft";
                        }
                        return "<span class='btn btn-block " + kelas + " p-2'>" + jenis + "</span>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        var html = "<button class='btn btn-danger-soft btn-block mb-2'>Tidak Memiliki Akses</button>";
                        if ($("#hak_akses").val() === "1") {
                            if (data.asisten_id)
                                html = "<button class='btn btn-success btn-sync-siakad mr-2' title='Sinkron data dengan siakad' data-id='" + data.id_jadwal + "' data-nama_mata_kuliah='" + data.nama_mata_kuliah + "'><span class='spinner-border spinner-border-sm mr-2' id='sync-siakad-loading-spin-" + data.id_jadwal + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-sync'></i></button>" +
                                    "<a href='/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/detail/" + data.id_jadwal + "' class='btn btn-info' title='Edit Status Pengajar' data-dosen_id='" + data.dosen_id + "' data-dosen='" + data.nama_dosen + "' data-asisten_id='" + data.asisten_id + "' data-asisten='" + data.nama_asisten + "' data-id='" + data.id_jadwal + "' data-nama_mata_kuliah='" + data.nama_mata_kuliah + "'><span class='spinner-border spinner-border-sm mr-2' id='sync-siakad-loading-spin-" + data.id_jadwal + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-users'></i></a>";
                            else
                                html = "<button class='btn btn-success btn-block btn-sync-siakad mr-2' title='Sinkron data dengan siakad' data-id='" + data.id_jadwal + "' data-nama_mata_kuliah='" + data.nama_mata_kuliah + "'><span class='spinner-border spinner-border-sm mr-2' id='sync-siakad-loading-spin-" + data.id_jadwal + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-sync'></i></button>";
                        }
                        return html;
                    }
                },
                {
                    data: 'nama_mata_kuliah',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
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
        $("#btn-cari-data-jadwal-kuliah").click(function () {
            self.data.table_jadwal_kuliah.search($("#cari-data-jadwal-kuliah").val()).draw();
        });
        $("#cari-data-jadwal-kuliah").keyup(function () {
            if (this.value === "") {
                self.data.table_jadwal_kuliah.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.table.search(this.value).draw();
            }
        });
        $("#btn-sync-ulang-jadwal-kuliah").click(function () {
            $("#modal-sync-jadwal-kuliah").modal('show');
        });
        $("#modal-btn-sync-jadwal-kuliah").click(function () {
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Apakah anda yakin akan melakukan Sinkronisasi Data ?',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $("#modal-sync-jadwal-kuliah").modal('hide');
                            self.isSyncCanceled = false;
                            $("#progress-bar-syncron-ulang-jadwal-kuliah").show();
                            $("#log-syncron-ulang-jadwal-kuliah").show();
                            $("#btn-cancel-syncron-ulang-jadwal-kuliah").show();
                            $("#loading-progress-jadwal-kuliah").show();
                            $("#keterangan-progress-jadwal-kuliah").text("Mohon menunggu hingga proses sinkronisasi selesai ...");
                            $.ajax({
                                url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/json-by-tahun-akademik',
                                method: 'post',
                                data: {
                                    tahun_akademik: $("#tahun_akademik_sync-jadwal-kuliah").val()
                                },
                                success: function (result) {
                                    self.next_data(result);
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
        $("#btn-cari-log-jadwal-kuliah").click(function () {
            self.data.log_table_jadwal_kuliah.search($("#cari-log-data-center").val()).draw();
        });
        $("#cari-log-jadwal-kuliah").keyup(function () {
            if (this.value === "") {
                self.data.log_table_jadwal_kuliah.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.log_table_jadwal_kuliah.search(this.value).draw();
            }
        });
        $("#status-log-jadwal-kuliah").on('change', function () {
            self.data.log_table_jadwal_kuliah.search(this.value).draw();
        });
        $("#btn-failed-log-jadwal-kuliah").click(function () {
            $("#status-log-jadwal-kuliah").val('failed').trigger('change');
        });
        $("#btn-inserted-log-jadwal-kuliah").click(function () {
            $("#status-log-jadwal-kuliah").val('inserted').trigger('change');
        });
        $("#btn-updated-log-jadwal-kuliah").click(function () {
            $("#status-log-jadwal-kuliah").val('updated').trigger('change');
        });
        $("#btn-tutup-log-jadwal-kuliah").click(function () {
            $("#log-syncron-ulang-jadwal-kuliah").hide();
        });
        $("#btn-cancel-syncron-ulang-jadwal-kuliah").click(function () {
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
        $("#table-jadwal-kuliah").on('click', 'button.btn-sync-siakad', function () {
            var id = $(this).data("id");
            var nama_mata_kuliah = $(this).data('nama_mata_kuliah');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin melakukan sinkronisasi data jadwal <b>' + nama_mata_kuliah + '</b> dengan data siakad ?<br/><b class="text-danger">Semua data akan di update berdasarkan data siakad</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/json-by-id',
                                method: 'POST',
                                data: {
                                    id: id
                                },
                                beforeSend: function () {
                                    $("#sync-siakad-loading-spin-" + id).show();
                                },
                                success: function (response) {
                                    if (response.jadwal_kuliah_id) {
                                        $.ajax({
                                            url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/synchron',
                                            method: 'post',
                                            data: {
                                                'jadwal_kuliah_id': response.jadwal_kuliah_id,
                                                'tahun_akademik': response.tahun_akademik,
                                                'kelas_id': response.kelas_id,
                                                'ruang_id': response.ruang_id,
                                                'hari': response.hari,
                                                'jam_mulai': response.jam_mulai,
                                                'jam_selesai': response.jam_selesai,
                                                'matakuliah_id': response.matakuliah_id,
                                                'nama_mata_kuliah': response.nama_mata_kuliah,
                                                'kapasitas': response.kapasitas,
                                                'dosen_id': response.dosen_id,
                                                'asisten_id': response.asisten_id,
                                                'kd_prodi': response.kd_prodi,
                                                'jumlah_sks': response.jumlah_sks,
                                                'is_lab': response.is_lab,
                                                'jenis_kelas': response.jenis_kelas,
                                                'nama_dosen': response.nama_dosen,
                                                'nama_asisten': response.nama_asisten,
                                                'nik_pengampu': response.nik_pengampu,
                                                'nik_asisten': response.nik_asisten,
                                            },
                                            success: function (result) {
                                                if (result.status) {
                                                    $.alert({
                                                        title: "Informasi",
                                                        type: "green",
                                                        content: result.keterangan
                                                    });
                                                } else {
                                                    $.alert({
                                                        title: "Peringatan",
                                                        type: "red",
                                                        content: result.keterangan
                                                    });
                                                }
                                            },
                                            complete: function () {
                                                self.data.table_jadwal_kuliah.ajax.reload();
                                                $("#sync-siakad-loading-spin-" + id).hide();
                                            }
                                        })
                                    } else {
                                        $.alert({
                                            title: "Peringatan",
                                            type: "red",
                                            content: "Data Jadwal Tidak Ditemukan"
                                        })
                                    }
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
        $("#tahun_akademik, #status_pengajar, #prodi").on('change', function () {
            self.data.table_jadwal_kuliah.ajax.reload();
        });
    },
    next_data: function (data, index = 0, progres = 0, failed = 0, inserted = 0, updated = 0) {
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/synchron',
            method: 'post',
            data: {
                'jadwal_kuliah_id': data[index].jadwal_kuliah_id,
                'tahun_akademik': data[index].tahun_akademik,
                'kelas_id': data[index].kelas_id,
                'ruang_id': data[index].ruang_id,
                'hari': data[index].hari,
                'jam_mulai': data[index].jam_mulai,
                'jam_selesai': data[index].jam_selesai,
                'matakuliah_id': data[index].matakuliah_id,
                'nama_mata_kuliah': data[index].nama_mata_kuliah,
                'kapasitas': data[index].kapasitas,
                'dosen_id': data[index].dosen_id,
                'asisten_id': data[index].asisten_id,
                'kd_prodi': data[index].kd_prodi,
                'jumlah_sks': data[index].jumlah_sks,
                'is_lab': data[index].is_lab,
                'jenis_kelas': data[index].jenis_kelas,
                'nama_dosen': data[index].nama_dosen,
                'nama_asisten': data[index].nama_asisten,
                'nik_pengampu': data[index].nik_pengampu,
                'nik_asisten': data[index].nik_asisten,
            },
            success: function (result) {
                if (result.status) {
                    if (result.jenis_aksi === 1) {
                        self.data.log_table_jadwal_kuliah.row.add([
                            (index + 1),
                            data[index].nama_mata_kuliah + " (" + data[index].kelas_id + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.keterangan,
                            data[index].nama_mata_kuliah,
                            "inserted"
                        ]).draw();
                        inserted++;
                    } else {
                        self.data.log_table_jadwal_kuliah.row.add([
                            (index + 1),
                            data[index].nama_mata_kuliah + " (" + data[index].kelas_id + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.keterangan,
                            data[index].nama_mata_kuliah,
                            "updated"
                        ]).draw();
                        updated++;
                    }
                } else {
                    self.data.log_table_jadwal_kuliah.row.add([
                        (index + 1),
                        data[index].nama_mata_kuliah + " (" + data[index].kelas_id + ")",
                        "<i class='fas fa-check-circle text-danger p-1'></i> " + result.keterangan,
                        data[index].nama_mata_kuliah,
                        "gagal"
                    ]).draw();
                    failed++;
                }
                progres++;
            },
            complete: function () {
                $("#progress-bar-jadwal-kuliah").width((progres / n * 100).toFixed(2) + '%');
                $("#progress-text-jadwal-kuliah").text((progres / n * 100).toFixed(2) + '% Complete');
                $("#btn-failed-log-jadwal-kuliah").text("Failed : " + failed);
                $("#btn-inserted-log-jadwal-kuliah").text("Inserted : " + inserted);
                $("#btn-updated-log-jadwal-kuliah").text("Updated : " + updated);
                if (index >= (data.length - 1)) {
                    self.data.table_jadwal_kuliah.search("").draw();
                    $("#log-syncron-ulang-jadwal-kuliah").show();
                    $("#progress-bar-syncron-ulang-jadwal-kuliah").hide();
                } else {
                    if (!self.isSyncCanceled) {
                        index++;
                        self.next_data(data, index, progres, failed, inserted, updated)
                    } else {
                        $("#loading-progress-jadwal-kuliah").hide();
                        $("#keterangan-progress-jadwal-kuliah").text("Dibatalkan oleh pengguna");
                        $("#log-syncron-ulang-jadwal-kuliah").show();
                        $("#btn-cancel-syncron-ulang-jadwal-kuliah").hide();
                    }
                }
            },
            error: function (){
                self.data.log_table_jadwal_kuliah.row.add([
                    (index + 1),
                    data[index].nama_mata_kuliah + " (" + data[index].kelas_id + ")",
                    "<i class='fas fa-check-circle text-danger p-1'></i> 500 Internal Server",
                    data[index].nama_mata_kuliah,
                    "gagal"
                ]).draw();
                failed++;
                progres++;
            }
        })
    }
    ,
};

jQuery(document).ready(function () {
    jQuery.jadwal_matakuliah.init();
});
