jQuery.sinkronisasi_mahasiswa_siakad = {
    data: {
        isSyncDataCenterCanceled: false,
        log_table_data_center: $("#log-table-data-center").DataTable({
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
        table_data_center: $("#table-data-center"),
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
        $("#prodi, #angkatan, #status").change(function () {
            self.data.table_data_center.ajax.reload();
        });
        self.data.table_data_center = $("#table-data-center").DataTable({
            serverSide: true,
            ajax: {
                url: '/adm-akademik/akademik/mahasiswa/sinkronisasi-mahasiswa-siakad/json',
                type: 'post',
                data: function (data) {
                    data.angkatan = $("#angkatan").val();
                    data.prodi = $("#prodi").val();
                    data.status = $("#status").val();
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
                    width: "55%",
                    render: function (data) {
                        return "<b>" + data.nama_mahasiswa + " (" + data.nim + ")</b><br/>" +
                            "Alamat : <br/>" + data.alamat;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%",
                    render: function (data) {
                        return "<b>" + data.jenjang_didik + " " + data.nama_program_studi + " (" + data.kd_program_studi + ") </b><br/>" +
                            "Fakultas : <b>" + data.nama_fakultas + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "10%",
                    render: function (data) {
                        if ($("#hak_akses").val() === "1") {
                            return "<button class='btn btn-success btn-sync-siakad' title='Sinkron data dengan siakad' data-nim='" + data.nim + "' data-nama='" + data.nama_mahasiswa + "'><span class='spinner-border spinner-border-sm mr-2' id='sync-siakad-loading-spin-" + data.nim + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-sync'></i></button>";
                        } else {
                            return "<button class='btn btn-secondary'><i class='fas fa-sync'></i></button>";
                        }
                    }
                },
                {
                    data: 'nama_mahasiswa',
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
        $("#btn-filter-data-center").click(function () {
            self.data.table_data_center.ajax.reload();
        });
        $("#btn-cari-data-data-center").click(function () {
            self.data.table_data_center.search($("#cari-data-data-center").val()).draw();
        });
        $("#cari-data-data-center").keyup(function () {
            if (this.value === "") {
                self.data.table_data_center.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.table.search(this.value).draw();
            }
        });
        $("#btn-sync-ulang-data-center").click(function () {
            $("#modal-sync-mahasiswa-data-center").modal('show');
        });
        $("#modal-btn-sync-data-center").click(function () {
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
                            $("#modal-sync-mahasiswa-data-center").modal('hide');
                            self.isSyncDataCenterCanceled = false;
                            $("#progress-bar-syncron-ulang-data-center").show();
                            $("#log-syncron-ulang-data-center").show();
                            $("#btn-cancel-syncron-ulang-data-center").show();
                            $("#loading-progress-data-center").show();
                            $("#keterangan-progress-data-center").text("Mohon menunggu hingga proses sinkronisasi selesai ...");
                            $.ajax({
                                url: '/adm-akademik/akademik/mahasiswa/sinkronisasi-mahasiswa-siakad/json-by-angkatan',
                                method: 'post',
                                data: {
                                    angkatan: $("#angkatan_sync-data-center").val()
                                },
                                success: function (result) {
                                    self.next_data_center(result);
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
        $("#btn-cari-log-data-center").click(function () {
            self.data.log_table_data_center.search($("#cari-log-data-center").val()).draw();
        });
        $("#cari-log-data-center").keyup(function () {
            if (this.value === "") {
                self.data.log_table_data_center.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.log_table_data_center.search(this.value).draw();
            }
        });
        $("#status-log-data-center").on('change', function () {
            self.data.log_table_data_center.search(this.value).draw();
        });
        $("#btn-failed-log-data-center").click(function () {
            $("#status-log-data-center").val('failed').trigger('change');
        });
        $("#btn-inserted-log-data-center").click(function () {
            $("#status-log-data-center").val('inserted').trigger('change');
        });
        $("#btn-updated-log-data-center").click(function () {
            $("#status-log-data-center").val('updated').trigger('change');
        });
        $("#btn-tutup-log-data-center").click(function () {
            $("#log-syncron-ulang-data-center").hide();
        });
        $("#btn-cancel-syncron-ulang-data-center").click(function () {
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
                            self.isSyncDataCenterCanceled = true;
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red'
                    }
                }
            })
        });
        $("#table-data-center").on('click', 'button.btn-sync-siakad', function () {
            var nim = $(this).data("nim");
            var nama = $(this).data('nama');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin melakukan sinkronisasi data atas nama <b>' + nama + '</b> dengan data siakad ?<br/><b class="text-danger">Semua data akan di update berdasarkan data siakad</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/adm-akademik/akademik/mahasiswa/sinkronisasi-mahasiswa-siakad/json-by-nim',
                                method: 'POST',
                                data: {
                                    nim: nim
                                },
                                beforeSend: function () {
                                    $("#sync-siakad-loading-spin-" + nim + "-data-center").show();
                                },
                                success: function (response) {
                                    if (response.NPK) {
                                        $.ajax({
                                            url: '/adm-akademik/akademik/mahasiswa/sinkronisasi-mahasiswa-siakad/synchron',
                                            method: 'post',
                                            data: {
                                                'npk': response.NPK,
                                                'inf_nisn': response.nisn,
                                                'dosen_wali': response.dosen_wali,
                                                'tgl_lulus_sma': response.tgl_lulus_sma,
                                                'inf_jurusan_sma': response.inf_jurusan_sma,
                                                'sekolah_asal': response.sekolah_asal,
                                                'inf_tgl_lulus': response.inf_tgl_lulus,
                                                'inf_nomor_ijazah': response.inf_nomor_ijazah,
                                                'inf_nomor_transkrip': response.inf_nomor_transkrip,
                                                'status_aktif': response.status_aktif,
                                                'program_id': response.program_id,
                                                'konsentrasi_id': response.konsentrasi_id,
                                                'nama_wali': response.nama_wali,
                                                'pekerjaan_wali': response.pekerjaan_wali,
                                                'jenis_mahasiswa': response.jenis_mahasiswa,
                                                'jenis_pendanaan': response.jenis_pendanaan,
                                                'nomor_seri_ijazah': response.no_seri_ijazah,
                                                'nama_lengkap': response.nama_lengkap,
                                                'tempat_lahir': response.tempat_lahir,
                                                'tanggal_lahir': response.tgl_lahir,
                                                'jenis_kelamin': response.jenis_kelamin,
                                                'agama_id': response.agama_id,
                                                'status_menikah': response.status_menikah,
                                                'hp': response.hp,
                                                'telepon_rumah': response.telepon_rumah,
                                                'alamat_rumah': response.alamat_rumah,
                                                'kode_pos_rumah': response.kode_pos_rumah,
                                                'inf_warga_negara': response.kewarganegaraan,
                                                'email': response.email,
                                                'nik': response.nik,
                                                'rt': response.rt,
                                                'rw': response.rw,
                                                'ds_kel': response.ds_kel,
                                                'nama_ibu': response.nama_ibu,
                                                'password': response.password,
                                                'angkatan': response.angkatan,
                                                'jenis_kelas': response.jenis_kelas,
                                                'judul_skripsi': response.judul_skripsi,
                                                'ipk': response.ipk,
                                                'nama_program': response.nama_program,
                                                'kota_rumah': response.kota_rumah,
                                            },
                                            success: function (result) {
                                                if (result.is_success) {
                                                    $.alert({
                                                        title: "Informasi",
                                                        type: "green",
                                                        content: result.result
                                                    });
                                                } else {
                                                    $.alert({
                                                        title: "Peringatan",
                                                        type: "red",
                                                        content: result.result
                                                    });
                                                }
                                            },
                                            complete: function () {
                                                self.data.table_data_center.ajax.reload();
                                                $("#sync-siakad-loading-spin-" + nim + "-data-center").hide();
                                            }
                                        })
                                    } else {
                                        $.alert({
                                            title: "Peringatan",
                                            type: "red",
                                            content: "Data Mahasiswa Tidak Ditemukan"
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
    },
    next_data_center: function (data, index = 0, progres = 0, failed = 0, inserted = 0, updated = 0) {
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/adm-akademik/akademik/mahasiswa/sinkronisasi-mahasiswa-siakad/synchron',
            method: 'post',
            data: {
                'npk': data[index].NPK,
                'inf_nisn': data[index].nisn,
                'dosen_wali': data[index].dosen_wali,
                'tgl_lulus_sma': data[index].tgl_lulus_sma,
                'inf_jurusan_sma': data[index].inf_jurusan_sma,
                'sekolah_asal': data[index].sekolah_asal,
                'inf_tgl_lulus': data[index].inf_tgl_lulus,
                'inf_nomor_ijazah': data[index].inf_nomor_ijazah,
                'inf_nomor_transkrip': data[index].inf_nomor_transkrip,
                'status_aktif': data[index].status_aktif,
                'program_id': data[index].program_id,
                'konsentrasi_id': data[index].konsentrasi_id,
                'nama_wali': data[index].nama_wali,
                'pekerjaan_wali': data[index].pekerjaan_wali,
                'jenis_mahasiswa': data[index].jenis_mahasiswa,
                'jenis_pendanaan': data[index].jenis_pendanaan,
                'nomor_seri_ijazah': data[index].no_seri_ijazah,
                'nama_lengkap': data[index].nama_lengkap,
                'tempat_lahir': data[index].tempat_lahir,
                'tanggal_lahir': data[index].tgl_lahir,
                'jenis_kelamin': data[index].jenis_kelamin,
                'agama_id': data[index].agama_id,
                'status_menikah': data[index].status_menikah,
                'hp': data[index].hp,
                'telepon_rumah': data[index].telepon_rumah,
                'alamat_rumah': data[index].alamat_rumah,
                'kode_pos_rumah': data[index].kode_pos_rumah,
                'inf_warga_negara': data[index].kewarganegaraan,
                'email': data[index].email,
                'nik': data[index].nik,
                'rt': data[index].rt,
                'rw': data[index].rw,
                'ds_kel': data[index].ds_kel,
                'nama_ibu': data[index].nama_ibu,
                'password': data[index].password,
                'angkatan': data[index].angkatan,
                'jenis_kelas': data[index].jenis_kelas,
                'judul_skripsi': data[index].judul_skripsi,
                'ipk': data[index].ipk,
                'nama_program': data[index].nama_program,
                'kota_rumah': data[index].kota_rumah,
            },
            success: function (result) {
                if (result.is_success) {
                    if (result.result.includes("Berhasil Insert Mahasiswa")) {
                        self.data.log_table_data_center.row.add([
                            (index + 1),
                            data[index].nama_lengkap + " (" + data[index].NPK + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.result,
                            data[index].nama_lengkap,
                            data[index].NPK,
                            "inserted"
                        ]).draw();
                        inserted++;
                    } else {
                        self.data.log_table_data_center.row.add([
                            (index + 1),
                            data[index].nama_lengkap + " (" + data[index].NPK + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.result,
                            data[index].nama_lengkap,
                            data[index].NPK,
                            "updated"
                        ]).draw();
                        updated++;
                    }
                } else {
                    self.data.log_table_data_center.row.add([
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
                self.data.log_table_data_center.row.add([
                    (index + 1),
                    data[index].nama_lengkap + " (" + data[index].NPK + ")",
                    "<i class='fas fa-times-circle text-danger p-1'></i> " + result.result,
                    data[index].nama_lengkap,
                    data[index].NPK,
                    "gagal"
                ]).draw();
                failed++;
                progres++;
            },
            complete: function () {
                $("#progress-bar-data-center").width((progres / n * 100).toFixed(2) + '%');
                $("#progress-text-data-center").text((progres / n * 100).toFixed(2) + '% Complete');
                $("#btn-failed-log-data-center").text("Failed : " + failed);
                $("#btn-inserted-log-data-center").text("Inserted : " + inserted);
                $("#btn-updated-log-data-center").text("Updated : " + updated);
                if (index >= (data.length - 1)) {
                    self.data.table_data_center.search("").draw();
                    $("#log-syncron-ulang-data-center").show();
                    $("#progress-bar-syncron-ulang-data-center").hide();
                } else {
                    if (!self.isSyncDataCenterCanceled) {
                        index++;
                        self.next_data_center(data, index, progres, failed, inserted, updated)
                    } else {
                        $("#loading-progress-data-center").hide();
                        $("#keterangan-progress-data-center").text("Dibatalkan oleh pengguna");
                        $("#log-syncron-ulang-data-center").show();
                        $("#btn-cancel-syncron-ulang-data-center").hide();
                    }
                }
            }
        })
    },
};

jQuery(document).ready(function () {
    jQuery.sinkronisasi_mahasiswa_siakad.init();
});
