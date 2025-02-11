jQuery.jadwal_kuliah_mahasiswa = {
    data: {
        hari: [
            'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUM\'AT', 'SABTU', 'MINGGU'
        ],
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
                url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-mahasiswa/json',
                type: 'post',
                data: function (data) {
                    data.tahun_akademik = $("#tahun_akademik").val();
                    data.prodi = $("#prodi").val();
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
                    data: 'nim',
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return data.nama_mata_kuliah + " (" + data.kelas_id + ")";
                    }
                },
                {
                    data: 'jml_sks',
                    searchable: false,
                    sClass: 'text-center',
                    width: "5%",
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                    render: function (data) {
                        return self.data.hari[data.hari] + " (" + data.jam_mulai + " s/d " + data.jam_selesai + ")";
                    }
                },
                {
                    data: 'nama_mata_kuliah',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'nim',
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
                                url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-mahasiswa/json-by-tahun-akademik',
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
        $("#btn-sync-ulang-jadwal-kuliah-by-nim").click(function () {
            $("#modal-sync-jadwal-kuliah-by-nim").modal('show');
        });
        $("#modal-btn-sync-jadwal-kuliah-nim").click(function () {
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
                            $("#modal-sync-jadwal-kuliah-by-nim").modal('hide');
                            self.isSyncCanceled = false;
                            $("#progress-bar-syncron-ulang-jadwal-kuliah").show();
                            $("#log-syncron-ulang-jadwal-kuliah").show();
                            $("#btn-cancel-syncron-ulang-jadwal-kuliah").show();
                            $("#loading-progress-jadwal-kuliah").show();
                            $("#keterangan-progress-jadwal-kuliah").text("Mohon menunggu hingga proses sinkronisasi selesai ...");
                            $.ajax({
                                url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-mahasiswa/json-by-tahun-akademik-nim',
                                method: 'post',
                                data: {
                                    nim: $("#nim_sync-jadwal-kuliah-nim").val(),
                                    tahun_akademik: $("#tahun_akademik_sync-jadwal-kuliah-nim").val()
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
        $("#tahun_akademik, #prodi").on('change', function () {
            self.data.table_jadwal_kuliah.ajax.reload();
        });
    },
    next_data: function (data, index = 0, progres = 0, failed = 0, inserted = 0, updated = 0) {
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-mahasiswa/synchron',
            method: 'post',
            data: {
                'jadwal_kuliah_id': data[index].jadwal_kuliah_id,
                'nim': data[index].NPK,
                'kelas_id': data[index].kelas_id,
                'nama_dosen': data[index].nama_dosen,
                'nama_mata_kuliah': data[index].nama_mata_kuliah,
                'tahun_akademik': data[index].tahun_akademik,
            },
            success: function (result) {
                if (result.status) {
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
            }
        })
    }
    ,
};

jQuery(document).ready(function () {
    jQuery.jadwal_kuliah_mahasiswa.init();
});
