jQuery.tahun_akademik = {
    data: {
        isSyncCanceled: false,
        log_table: $("#log-table").DataTable({
            scrollY: '300px',
            columns: [
                { width: "5%", sClass: 'text-center', searchable: false },
                { width: "50%", searchable: false },
                { width: "45%", sClass: 'text-center', searchable: false },
                { searchable: true, visible: false },
                { searchable: true, visible: false },
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
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        // Option Data
        $(".select2").select2();
        // Table With DataTable
        self.data.table = $("#table").DataTable({
            createdRow: function (row, data) {
                if (data.sts_aktif == 0) {
                    $(row).addClass('row-disabled');
                }
            },
            serverSide: true,
            ajax: {
                url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-tahun-akademik-siakad/json',
                type: 'post'
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
                        return "<b>" + data.nama_tahun_akademik + " (" + data.tahun_akademik + ")</b><br/>" +
                            "KRS: <br/><small>" + data.tgl_mulai_krs + ' s/d ' + data.tgl_selesai_krs + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "50%",
                    render: function (data) {
                        return "" +
                            "Perkuliahan: <br/><small>" + data.tgl_awal_perkuliahan + ' s/d ' + data.tgl_akhir_perkuliahan + "</small><br/>" +
                            "Input Nilai: <br/><small>" + data.tgl_mulai_input_nilai + ' s/d ' + data.tgl_selesai_input_nilai + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {

                        let html = "";

                        if (data.sts_aktif == 1) {
                            html += `
                                <button class="btn btn-success btn-sm btn-block btn-toggle-status mb-2"
                                    title="Nonaktifkan Tahun Akademik"
                                    data-id="${data.id_semester}"
                                    data-status="0">
                                    <i class="fas fa-toggle-on"></i> Aktif
                                </button>
                            `;
                        } else {
                            html += `
                                <button class="btn btn-danger btn-sm btn-block btn-toggle-status mb-2"
                                    title="Aktifkan Tahun Akademik"
                                    data-id="${data.id_semester}"
                                    data-status="1">
                                    <i class="fas fa-toggle-off"></i> Non Aktif
                                </button>
                            `;
                        }

                        // ===== TOMBOL EDIT =====
                        html += `
                            <button class="btn btn-warning btn-sm btn-block btn-edit-tahun mb-2"
                                title="Edit Tahun Akademik"
                                data-id="${data.id_semester}"
                                data-nama_semester="${data.nama_tahun_akademik}"
                                data-tahun_akademik="${data.tahun_akademik}"
                                data-tgl_awal_perkuliahan="${data.tgl_awal_perkuliahan_raw || data.tgl_awal_perkuliahan}"
                                data-tgl_akhir_perkuliahan="${data.tgl_akhir_perkuliahan_raw || data.tgl_akhir_perkuliahan}"
                                data-tgl_mulai_krs="${data.tgl_mulai_krs_raw || data.tgl_mulai_krs}"
                                data-tgl_selesai_krs="${data.tgl_selesai_krs_raw || data.tgl_selesai_krs}"
                                data-tgl_mulai_input_nilai="${data.tgl_mulai_input_nilai_raw || data.tgl_mulai_input_nilai}"
                                data-tgl_selesai_input_nilai="${data.tgl_selesai_input_nilai_raw || data.tgl_selesai_input_nilai}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        `;

                        // ===== TOMBOL SYNC =====
                        // if ($("#hak_akses").val() === "1" && data.sts_aktif == 1) {
                        //     html += `
                        //         <button class="btn btn-success btn-block btn-sync-siakad"
                        //             title="Sinkron data dengan siakad"
                        //             data-p_tahun_akademik="${data.tahun_akademik}"
                        //             data-p_nama_semester="${data.nama_tahun_akademik}"
                        //             data-p_id_semester="${data.id_semester_uij}">
                        //             <span class="spinner-border spinner-border-sm mr-2"
                        //                 id="sync-siakad-loading-spin-${data.tahun_akademik}"
                        //                 style="display:none"></span>
                        //             <i class="fas fa-sync"></i> Sync
                        //         </button>
                        //     `;
                        // } else if ($("#hak_akses").val() !== "1") {
                        //     html += `
                        //         <button class="btn btn-secondary btn-block" disabled>
                        //             Tidak Memiliki Akses
                        //         </button>
                        //     `;
                        // }

                        return html;
                    }
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
        $("#btn-sync-ulang").click(function () {
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
                            self.isSyncCanceled = false;
                            $("#progress-bar-syncron-ulang").show();
                            $("#log-syncron-ulang").show();
                            $("#btn-cancel-syncron-ulang").show();
                            $("#loading-progress").show();
                            $("#keterangan-progress").text("Mohon menunggu hingga proses sinkronisasi selesai ...");
                            $.ajax({
                                url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-tahun-akademik-siakad/json-tahun-akademik-siakad',
                                method: 'post',
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
        $("#btn-failed-log").click(function () {
            $("#status-log").val('failed').trigger('change');
        });
        $("#btn-inserted-log").click(function () {
            $("#status-log").val('inserted').trigger('change');
        });
        $("#btn-updated-log").click(function () {
            $("#status-log").val('updated').trigger('change');
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
        $("#table").on('click', 'button.btn-sync-siakad', function () {
            var tahun_akademik = $(this).data("p_tahun_akademik");
            var nama_tahun_akademik = $(this).data("p_nama_semester");
            var id_semester = $(this).data("p_id_semester");
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin melakukan sinkronisasi data TA <b>' + nama_tahun_akademik + '</b> dengan data siakad ?<br/><b class="text-danger">Semua data akan di update berdasarkan data siakad</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-tahun-akademik-siakad/json-by-tahun-akademik',
                                method: 'POST',
                                data: {
                                    tahun_akademik: tahun_akademik,
                                    id_semester: id_semester
                                },
                                beforeSend: function () {
                                    $("#sync-siakad-loading-spin-" + tahun_akademik).show();
                                },
                                success: function (response) {
                                    if (response[0].p_tahun_akademik) {
                                        $.ajax({
                                            url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-tahun-akademik-siakad/synchron',
                                            method: 'post',
                                            data: {
                                                'p_id_semester': response[0].p_id_semester,
                                                'p_nama_semester': response[0].p_nama_semester_teks,
                                                'p_is_periode_aktif': response[0].p_is_periode_aktif,
                                                'p_tgl_awal_perkuliahan': response[0].p_tgl_awal_perkuliahan,
                                                'p_tgl_akhir_perkuliahan': response[0].p_tgl_akhir_perkuliahan,
                                                'p_tahun_akademik': response[0].p_tahun_akademik,
                                                'p_tgl_mulai_krs': response[0].p_tgl_mulai_krs,
                                                'p_tgl_akhir_krs': response[0].p_tgl_akhir_krs,
                                                'p_tgl_mulai_input_nilai': response[0].p_tgl_mulai_input_nilai,
                                                'p_tgl_akhir_input_nilai': response[0].p_tgl_akhir_input_nilai
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
                                                self.data.table.ajax.reload();
                                                $("#sync-siakad-loading-spin-" + tahun_akademik).hide();
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
        $("#table").on("click", ".btn-toggle-status", function () {

            const id = $(this).data("id");
            const status = $(this).data("status");

            const text = status == 1 ? "mengaktifkan" : "menonaktifkan";

            $.confirm({
                title: "Konfirmasi!",
                type: "orange",
                content: `Apakah anda yakin ingin ${text} Tahun Akademik ini?`,
                buttons: {
                    confirm: {
                        text: "Yakin",
                        btnClass: "btn-green",
                        action: function () {
                            $.ajax({
                                url: "/adm-akademik/akademik/perkuliahan/tahun-akademik/toggle-status",
                                method: "POST",
                                data: {
                                    id_semester: id,
                                    sts_aktif: status
                                },
                                success: function (res) {
                                    console.log(res);
                                    if (res.status) {
                                        $.alert({
                                            title: "Berhasil",
                                            type: "green",
                                            content: res.keterangan
                                        });
                                        jQuery.tahun_akademik.data.table.ajax.reload(null, false);
                                    } else {
                                        $.alert({
                                            title: "Gagal",
                                            type: "red",
                                            content: res.keterangan
                                        });
                                    }
                                }
                            });
                        }
                    },
                    cancel: {
                        text: "Batal",
                        btnClass: "btn-red"
                    }
                }
            });
        });

        $("#btn-tambah-tahun").click(function () {
            $("#form-tahun-akademik")[0].reset();
            $("input[name='p_id_semester_akademik']").val(0);
            $("#modal-tahun-akademik .modal-title").html('<i class="fas fa-calendar-plus"></i> Tambah Tahun Akademik');
            $("select[name='p_nama_semester']").val("").trigger('change');
            $("#modal-tahun-akademik").modal("show");
        });

        $("#btn-simpan-tahun").click(function () {

            $.ajax({
                url: "/adm-akademik/akademik/perkuliahan/tahun-akademik/insup-tahun-akademik",
                method: "POST",
                data: $("#form-tahun-akademik").serialize(),
                success: function (res) {
                    if (res.status) {
                        $.alert({
                            title: "Berhasil",
                            type: "green",
                            content: res.keterangan
                        });
                        $("#modal-tahun-akademik").modal("hide");
                        jQuery.tahun_akademik.data.table.ajax.reload(null, false);
                    } else {
                        $.alert({
                            title: "Gagal",
                            type: "red",
                            content: res.keterangan
                        });
                    }
                }
            });

        });

        $("#table").on("click", ".btn-edit-tahun", function () {
            const id = $(this).data("id");
            const nama_semester = $(this).data("nama_semester");
            const tahun_akademik = $(this).data("tahun_akademik");

            function parseIndonesianDate(dateStr) {
                const months = {
                    'Januari': '01', 'Februari': '02', 'Maret': '03', 'April': '04',
                    'Mei': '05', 'Juni': '06', 'Juli': '07', 'Agustus': '08',
                    'September': '09', 'Oktober': '10', 'November': '11', 'Desember': '12'
                };

                const parts = dateStr.split(' ');
                const day = parts[0].padStart(2, '0');
                const month = months[parts[1]];
                const year = parts[2];

                return `${year}-${month}-${day}`;
            }

            const tgl_awal_perkuliahan = parseIndonesianDate($(this).data("tgl_awal_perkuliahan"));
            const tgl_akhir_perkuliahan = parseIndonesianDate($(this).data("tgl_akhir_perkuliahan"));
            const tgl_mulai_krs = parseIndonesianDate($(this).data("tgl_mulai_krs"));
            const tgl_selesai_krs = parseIndonesianDate($(this).data("tgl_selesai_krs"));
            const tgl_mulai_input_nilai = parseIndonesianDate($(this).data("tgl_mulai_input_nilai"));
            const tgl_selesai_input_nilai = parseIndonesianDate($(this).data("tgl_selesai_input_nilai"));

            $("#modal-tahun-akademik .modal-title").html('<i class="fas fa-edit"></i> Edit Tahun Akademik');

            $("input[name='p_id_semester_akademik']").val(id);
            $("select[name='p_nama_semester']").val(nama_semester).trigger('change');
            $("input[name='p_tahun_akademik']").val(tahun_akademik);
            $("input[name='p_tgl_awal_perkuliahan']").val(tgl_awal_perkuliahan);
            $("input[name='p_tgl_akhir_perkuliahan']").val(tgl_akhir_perkuliahan);
            $("input[name='p_tgl_mulai_krs']").val(tgl_mulai_krs);
            $("input[name='p_tgl_akhir_krs']").val(tgl_selesai_krs);
            $("input[name='p_tgl_mulai_input_nilai']").val(tgl_mulai_input_nilai);
            $("input[name='p_tgl_akhir_input_nilai']").val(tgl_selesai_input_nilai);

            $("#modal-tahun-akademik").modal("show");
        });

    },
    next_data: function (data, index = 0, progres = 0, failed = 0, inserted = 0, updated = 0) {
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-tahun-akademik-siakad/synchron',
            method: 'post',
            data: {
                'p_id_semester': data[index].p_id_semester,
                'p_nama_semester': data[index].p_nama_semester_teks,
                'p_is_periode_aktif': data[index].p_is_periode_aktif,
                'p_tgl_awal_perkuliahan': data[index].p_tgl_awal_perkuliahan,
                'p_tgl_akhir_perkuliahan': data[index].p_tgl_akhir_perkuliahan,
                'p_tahun_akademik': data[index].p_tahun_akademik,
                'p_tgl_mulai_krs': data[index].p_tgl_mulai_krs,
                'p_tgl_akhir_krs': data[index].p_tgl_akhir_krs,
                'p_tgl_mulai_input_nilai': data[index].p_tgl_mulai_input_nilai,
                'p_tgl_akhir_input_nilai': data[index].p_tgl_akhir_input_nilai
            },
            success: function (result) {
                if (result.status) {
                    if (result.jenis_aksi === 1) {
                        self.data.log_table.row.add([
                            (index + 1),
                            data[index].p_nama_semester + " (" + data[index].p_tahun_akademik + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.keterangan,
                            data[index].p_nama_semester,
                            "inserted"
                        ]).draw();
                        inserted++;
                    } else {
                        self.data.log_table.row.add([
                            (index + 1),
                            data[index].p_nama_semester + " (" + data[index].p_tahun_akademik + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.keterangan,
                            data[index].p_nama_semester,
                            "updated"
                        ]).draw();
                        updated++;
                    }
                } else {
                    self.data.log_table.row.add([
                        (index + 1),
                        data[index].p_nama_semester + " (" + data[index].p_tahun_akademik + ")",
                        "<i class='fas fa-check-circle text-danger p-1'></i> " + result.keterangan,
                        data[index].p_nama_semester,
                        "gagal"
                    ]).draw();
                    failed++;
                }
            },
            complete: function () {
                progres++;
                $("#progress-bar").width((progres / n * 100).toFixed(2) + '%');
                $("#progress-text").text((progres / n * 100).toFixed(2) + '% Complete');
                $("#btn-failed-log").text("Failed : " + failed);
                $("#btn-inserted-log").text("Inserted : " + inserted);
                $("#btn-updated-log").text("Updated : " + updated);
                if (index >= (data.length - 1)) {
                    self.data.table.search("").draw();
                    $("#log-syncron-ulang").show();
                    $("#progress-bar-syncron-ulang").hide();
                } else {
                    if (!self.isSyncCanceled) {
                        index++;
                        self.next_data(data, index, progres, failed, inserted, updated)
                    } else {
                        $("#loading-progress").hide();
                        $("#keterangan-progress").text("Dibatalkan oleh pengguna");
                        $("#log-syncron-ulang").show();
                        $("#btn-cancel-syncron-ulang").hide();
                    }
                }
            }
        })
    }
    ,
};

jQuery(document).ready(function () {
    jQuery.tahun_akademik.init();
});
