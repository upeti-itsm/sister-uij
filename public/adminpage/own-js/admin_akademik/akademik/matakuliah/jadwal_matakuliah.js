jQuery.jadwal_matakuliah = {
    data: {
        isSyncCanceled: false,
        isGenerateCanceled: false,
        log_table_jadwal_kuliah: $("#log-table-jadwal-kuliah").DataTable({
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
                        return "<b>" + data.nama_mata_kuliah + " (" + data.nama_kelas + ")</b><br/>" +
                            "TA : " + data.tahun_akademik + " | SKS : " + data.jml_sks;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
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
                    width: "10%",
                    render: function (data) {
                        var namaHari = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                        var hari = namaHari[data.hari] || '-';
                        var jamMulai = data.jam_mulai ? data.jam_mulai.substring(0, 5) : '-';
                        var jamSelesai = data.jam_selesai ? data.jam_selesai.substring(0, 5) : '-';
                        return "<div style='text-align: center;'><b>" + hari + "</b><br/>" +
                            "<div style='display: inline-flex; align-items: center; gap: 5px;'>" +
                            "<span class='badge badge-success'>" + jamMulai + "</span>" +
                            "<span>-</span>" +
                            "<span class='badge badge-danger'>" + jamSelesai + "</span>" +
                            "</div></div>";
                    }
                },
                {
                    data: 'ruang_id',
                    searchable: false,
                    sClass: 'text-center',
                    width: "7%",
                    render: function (data) {
                        return data ? "<code>" + data + "</code>" : "-";
                    }
                },
                {
                    data: 'kapasitas',
                    searchable: false,
                    sClass: 'text-center',
                    width: "7%",
                    render: function (data) {
                        return "<span class='badge badge-primary'>" + (data || 0) + " Mhs</span>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "8%",
                    render: function (data) {
                        // Cek field 'status' dari database
                        var isActive = false;

                        if (typeof data.status !== 'undefined' && data.status !== null) {
                            // Boolean: true/false
                            if (data.status === true) {
                                isActive = true;
                            } else if (data.status === false) {
                                isActive = false;
                            }
                            // String: 't'/'f', 'true'/'false', '1'/'0'
                            else if (typeof data.status === 'string') {
                                isActive = data.status === 't' || data.status === 'true' || data.status === '1';
                            }
                            // Number: 1/0
                            else if (typeof data.status === 'number') {
                                isActive = data.status === 1;
                            }
                        }

                        if (isActive) {
                            return "<span class='badge badge-success p-2'><i class='fas fa-check-circle mr-1'></i>Aktif</span>";
                        } else {
                            return "<span class='badge badge-danger p-2'><i class='fas fa-times-circle mr-1'></i>Nonaktif</span>";
                        }
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "10%",
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
                    width: "10%",
                    render: function (data) {
                        var html = "<div class='d-flex justify-content-center'><button class='btn btn-danger-soft btn-sm'>Tidak Memiliki Akses</button></div>";
                        if ($("#hak_akses").val() === "1") {
                            // Tombol Toggle Status - gunakan field 'status'
                            var isActive = false;

                            if (typeof data.status !== 'undefined' && data.status !== null) {
                                // Boolean: true/false
                                if (data.status === true) {
                                    isActive = true;
                                } else if (data.status === false) {
                                    isActive = false;
                                }
                                // String: 't'/'f', 'true'/'false', '1'/'0'
                                else if (typeof data.status === 'string') {
                                    isActive = data.status === 't' || data.status === 'true' || data.status === '1';
                                }
                                // Number: 1/0
                                else if (typeof data.status === 'number') {
                                    isActive = data.status === 1;
                                }
                            }

                            // Wrap dalam div dengan display flex untuk horizontal alignment
                            html = "<div class='d-flex justify-content-center align-items-center'>";
                            html += "<button class='btn btn-primary btn-sm btn-edit-jadwal mr-1' title='Edit Jadwal' data-id='" + data.id_jadwal + "'><i class='fas fa-edit'></i></button>";

                            if (isActive) {
                                html += "<button class='btn btn-success btn-sm btn-toggle-status' title='Nonaktifkan Jadwal' data-id='" + data.id_jadwal + "' data-status='0' data-nama_mata_kuliah='" + data.nama_mata_kuliah + "'><i class='fas fa-toggle-on'></i></button>";
                            } else {
                                html += "<button class='btn btn-danger btn-sm btn-toggle-status' title='Aktifkan Jadwal' data-id='" + data.id_jadwal + "' data-status='1' data-nama_mata_kuliah='" + data.nama_mata_kuliah + "'><i class='fas fa-toggle-off'></i></button>";
                            }
                            html += "</div>";
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

        // Event handler untuk Generate Jadwal
        $("#btn-generate-jadwal").click(function () {
            $("#modal-generate-jadwal").modal('show');
        });

        $("#modal-btn-generate-jadwal").click(function () {
            var tahunAkademik = $("#tahun_akademik_generate").val();

            if (!tahunAkademik) {
                $.alert({
                    title: "Peringatan",
                    type: "red",
                    content: "Silahkan pilih tahun akademik terlebih dahulu!"
                });
                return;
            }

            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Apakah anda yakin akan melakukan Generate Jadwal Kuliah ?<br/><b class="text-warning">Proses ini akan memakan waktu beberapa menit</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $("#modal-generate-jadwal").modal('hide');
                            self.startGenerateJadwal(tahunAkademik);
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red'
                    }
                }
            });
        });

        $("#btn-cancel-generate-jadwal").click(function () {
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Proses generate jadwal akan dihentikan, apakah anda yakin ?',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            self.isGenerateCanceled = true;
                            $("#progress-bar-generate-jadwal").hide();
                            $("#btn-generate-jadwal").prop('disabled', false).html('<i class="fas fa-calendar-plus"></i> Generate Jadwal');
                            $.alert({
                                title: "Informasi",
                                type: "blue",
                                content: "Proses generate jadwal dibatalkan"
                            });
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red'
                    }
                }
            });
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

        // Event handler untuk Edit Jadwal
        $("#table-jadwal-kuliah").on('click', 'button.btn-edit-jadwal', function () {
            var id = $(this).data("id");

            // Ambil data langsung dari row DataTable
            var table = self.data.table_jadwal_kuliah;
            var row = $(this).closest('tr');
            var data = table.row(row).data();

            console.log('Row data:', data);

            if (!data) {
                $.alert({
                    title: 'Error!',
                    type: 'red',
                    content: 'Data jadwal tidak ditemukan'
                });
                return;
            }

            // Simpan semua data untuk update nanti
            $("#edit_id").val(data.id_jadwal);
            $("#edit_id").data('full-data', data); // Simpan full data untuk update

            // Populate form
            $("#edit_nama_mata_kuliah").val(data.nama_mata_kuliah);
            $("#edit_nama_kelas").val(data.nama_kelas);
            $("#edit_hari").val(data.hari);
            $("#edit_jam_mulai").val(data.jam_mulai);
            $("#edit_jam_selesai").val(data.jam_selesai);
            $("#edit_ruang_id").val(data.ruang_id);

            // Set status aktif (handle different data types)
            var statusAktif = '0';
            if (data.status === true || data.status === 't' || data.status === '1' || data.status === 1) {
                statusAktif = '1';
            }
            $("#edit_status_aktif").val(statusAktif);

            $("#modal-edit-jadwal-kuliah").modal('show');
        });

        // Event handler untuk Update Jadwal
        $("#modal-btn-update-jadwal").click(function () {
            // Ambil full data yang disimpan sebelumnya
            var fullData = $("#edit_id").data('full-data');

            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Apakah anda yakin akan mengupdate jadwal kuliah ini ?',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            // Siapkan data untuk dikirim sesuai parameter function baru
                            var updateData = {
                                id: $("#edit_id").val(),
                                ruang_id: $("#edit_ruang_id").val() || null,
                                hari: $("#edit_hari").val() || null,
                                jam_mulai: $("#edit_jam_mulai").val() || null,
                                jam_selesai: $("#edit_jam_selesai").val() || null,
                                sts_aktif: $("#edit_status_aktif").val() === '1' ? true : false
                            };

                            $.ajax({
                                url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/update',
                                method: 'post',
                                data: updateData,
                                success: function (result) {
                                    if (result.status === 'success') {
                                        $.alert({
                                            title: 'Berhasil!',
                                            type: 'green',
                                            content: 'Jadwal berhasil diupdate'
                                        });
                                        $("#modal-edit-jadwal-kuliah").modal('hide');
                                        self.data.table_jadwal_kuliah.ajax.reload();
                                    } else {
                                        $.alert({
                                            title: 'Gagal!',
                                            type: 'red',
                                            content: result.message
                                        });
                                    }
                                },
                                error: function (xhr) {
                                    var message = 'Terjadi kesalahan saat update jadwal';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        message = xhr.responseJSON.message;
                                    }
                                    $.alert({
                                        title: 'Error!',
                                        type: 'red',
                                        content: message
                                    });
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
        });

        // Event handler untuk Delete Jadwal
        $("#table-jadwal-kuliah").on('click', 'button.btn-delete-jadwal', function () {
            var id = $(this).data("id");
            var nama_mata_kuliah = $(this).data('nama_mata_kuliah');

            $.confirm({
                title: 'Konfirmasi !',
                type: 'red',
                content: 'Apakah anda yakin akan menghapus jadwal <b>' + nama_mata_kuliah + '</b> ?',
                buttons: {
                    confirm: {
                        text: 'Hapus',
                        btnClass: 'btn-red',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/delete',
                                method: 'post',
                                data: { id: id },
                                success: function (result) {
                                    if (result.status === 'success') {
                                        $.alert({
                                            title: 'Berhasil!',
                                            type: 'green',
                                            content: result.message
                                        });
                                        self.data.table_jadwal_kuliah.ajax.reload();
                                    } else {
                                        $.alert({
                                            title: 'Gagal!',
                                            type: 'red',
                                            content: result.message
                                        });
                                    }
                                },
                                error: function (xhr) {
                                    var message = 'Terjadi kesalahan saat menghapus jadwal';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        message = xhr.responseJSON.message;
                                    }
                                    $.alert({
                                        title: 'Error!',
                                        type: 'red',
                                        content: message
                                    });
                                }
                            });
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-secondary'
                    }
                }
            });
        });

        // Event handler untuk Toggle Status Jadwal
        $("#table-jadwal-kuliah").on('click', 'button.btn-toggle-status', function () {
            var id = $(this).data("id");
            var nama_mata_kuliah = $(this).data('nama_mata_kuliah');
            var status = $(this).data('status'); // 1 untuk aktifkan, 0 untuk nonaktifkan

            var stsAktif = parseInt(status);
            var statusText = stsAktif === 1 ? 'mengaktifkan' : 'menonaktifkan';
            var statusTextTitle = stsAktif === 1 ? 'Aktifkan' : 'Nonaktifkan';

            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Apakah anda yakin akan ' + statusText + ' jadwal <b>' + nama_mata_kuliah + '</b> ?',
                buttons: {
                    confirm: {
                        text: statusTextTitle,
                        btnClass: stsAktif === 1 ? 'btn-green' : 'btn-orange',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/set-status-aktif',
                                method: 'post',
                                data: {
                                    id: id,
                                    sts_aktif: stsAktif
                                },
                                success: function (result) {
                                    if (result.status === 'success') {
                                        $.alert({
                                            title: 'Berhasil!',
                                            type: 'green',
                                            content: result.message
                                        });
                                        self.data.table_jadwal_kuliah.ajax.reload();
                                    } else {
                                        $.alert({
                                            title: 'Gagal!',
                                            type: 'red',
                                            content: result.message
                                        });
                                    }
                                },
                                error: function (xhr) {
                                    var message = 'Terjadi kesalahan saat mengubah status jadwal';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        message = xhr.responseJSON.message;
                                    }
                                    $.alert({
                                        title: 'Error!',
                                        type: 'red',
                                        content: message
                                    });
                                }
                            });
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-secondary'
                    }
                }
            });
        });

        $("#tahun_akademik, #status_pengajar, #prodi").on('change', function () {
            self.data.table_jadwal_kuliah.ajax.reload();
        });
    },

    // Fungsi untuk memulai generate jadwal
    startGenerateJadwal: function (tahunAkademik) {
        var self = this;

        // Reset flag cancel
        self.isGenerateCanceled = false;

        // Disable tombol generate
        $("#btn-generate-jadwal").prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

        // Tampilkan progress bar
        $("#progress-bar-generate-jadwal").show();

        // Update keterangan
        $("#keterangan-progress-generate-jadwal").text('Memulai proses generate jadwal...');

        // AJAX Request
        $.ajax({
            url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/generate-jadwal',
            type: 'POST',
            data: {
                tahun_akademik: tahunAkademik,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            timeout: 300000, // 5 menit timeout
            beforeSend: function () {
                $("#keterangan-progress-generate-jadwal").text('Mengirim request ke server...');
            },
            success: function (response) {
                if (!self.isGenerateCanceled) {
                    if (response.status === 'success') {
                        $("#keterangan-progress-generate-jadwal").html('<i class="fas fa-check-circle text-success"></i> ' + response.message);

                        $.alert({
                            title: "Berhasil",
                            type: "green",
                            content: response.message
                        });

                        // Reload table
                        self.data.table_jadwal_kuliah.ajax.reload();

                        // Hide progress after 3 seconds
                        setTimeout(function () {
                            $("#progress-bar-generate-jadwal").hide();
                        }, 3000);

                    } else {
                        $("#keterangan-progress-generate-jadwal").html('<i class="fas fa-exclamation-triangle text-warning"></i> ' + response.message);

                        $.alert({
                            title: "Gagal",
                            type: "red",
                            content: response.message
                        });
                    }
                }
            },
            error: function (xhr, status, error) {
                if (!self.isGenerateCanceled) {
                    var errorMessage = 'Terjadi kesalahan saat generate jadwal';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (status === 'timeout') {
                        errorMessage = 'Request timeout. Proses mungkin masih berjalan di background.';
                    }

                    $("#keterangan-progress-generate-jadwal").html('<i class="fas fa-times-circle text-danger"></i> ' + errorMessage);

                    $.alert({
                        title: "Error",
                        type: "red",
                        content: errorMessage
                    });
                }
            },
            complete: function () {
                if (!self.isGenerateCanceled) {
                    // Enable tombol kembali
                    $("#btn-generate-jadwal").prop('disabled', false).html('<i class="fas fa-calendar-plus"></i> Generate Jadwal');
                }
            }
        });
    },

    next_data: function (data, index = 0, progres = 0, failed = 0, inserted = 0, updated = 0) {
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/adm-akademik/akademik/perkuliahan/sinkronisasi-jadwal-kuliah-siakad/synchron',
            method: 'post',
            data: {
                'jadwal_kuliah_id': data[index].p_jadwal_kuliah_id,
                'tahun_akademik': data[index].p_tahun_akademik,
                'kelas_id': data[index].p_kelas_id,
                'nama_kelas': data[index].p_nama_kelas,
                'ruang_id': data[index].p_ruang_id,
                'hari': data[index].p_hari,
                'jam_mulai': data[index].p_jam_mulai,
                'jam_selesai': data[index].p_jam_selesai,
                'matakuliah_id': data[index].p_matakuliah_id,
                'nama_mata_kuliah': data[index].p_nama_mata_kuliah,
                'kapasitas': data[index].p_kapasitas,
                'dosen_id': data[index].p_dosen_id,
                'asisten_id': data[index].p_asisten_id,
                'kd_prodi': data[index].p_kd_prodi,
                'jumlah_sks': data[index].p_sks,
                'is_lab': data[index].p_is_lab,
                'jenis_kelas': data[index].p_id_jenis_kelas_matakuliah,
                'nama_dosen': data[index].p_nama_dosen,
                'nama_asisten': data[index].p_nama_asisten,
                'nik_pengampu': data[index].p_nik_pengampu,
                'nik_asisten': data[index].p_nik_asisten,
                'kd_matkul': data[index].p_kd_mata_kuliah,
            },
            success: function (result) {
                if (result.status) {
                    if (result.jenis_aksi === 1) {
                        self.data.log_table_jadwal_kuliah.row.add([
                            (index + 1),
                            data[index].p_nama_mata_kuliah + " (" + data[index].p_nama_kelas + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.keterangan,
                            data[index].p_nama_mata_kuliah,
                            "inserted"
                        ]).draw();
                        inserted++;
                    } else {
                        self.data.log_table_jadwal_kuliah.row.add([
                            (index + 1),
                            data[index].p_nama_mata_kuliah + " (" + data[index].p_nama_kelas + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.keterangan,
                            data[index].p_nama_mata_kuliah,
                            "updated"
                        ]).draw();
                        updated++;
                    }
                } else {
                    self.data.log_table_jadwal_kuliah.row.add([
                        (index + 1),
                        data[index].p_nama_mata_kuliah + " (" + data[index].p_nama_kelas + ")",
                        "<i class='fas fa-check-circle text-danger p-1'></i> " + result.keterangan,
                        data[index].p_nama_mata_kuliah,
                        "gagal"
                    ]).draw();
                    failed++;
                }

            },
            complete: function () {
                progres++;
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
            error: function () {
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
};

jQuery(document).ready(function () {
    jQuery.jadwal_matakuliah.init();
});
