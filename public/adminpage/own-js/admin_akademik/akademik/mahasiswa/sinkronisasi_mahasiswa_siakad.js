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
                    width: "3%",
                    render: function (data, type, row) {
                        return '<small>' + data + '</small>';
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        var gender_icon = data.jenis_kelamin === 'Laki-laki' || data.jenis_kelamin === 'L' ?
                            '<i class="fas fa-mars text-primary"></i>' : '<i class="fas fa-venus text-danger"></i>';

                        var status_badge = '';
                        if (data.status_mahasiswa === 'AKTIF') {
                            status_badge = '<span class="badge badge-success badge-sm">Aktif</span>';
                        } else if (data.status_mahasiswa === 'LULUS') {
                            status_badge = '<span class="badge badge-info badge-sm">Lulus</span>';
                        } else if (data.status_mahasiswa === 'CUTI') {
                            status_badge = '<span class="badge badge-warning badge-sm">Cuti</span>';
                        } else {
                            status_badge = '<span class="badge badge-secondary badge-sm">Tidak Aktif</span>';
                        }

                        return "<div class='mb-1'>" +
                            "<b>" + data.nama_mahasiswa + "</b> " + gender_icon + " " + status_badge +
                            "</div>" +
                            "<small class='text-muted'><i class='fas fa-id-card mr-1'></i>NIM: <b>" + data.nim + "</b></small><br/>" +
                            "<small class='text-muted'><i class='fas fa-fingerprint mr-1'></i>NIK: " + (data.nik || '-') + " | NISN: " + (data.nisn || '-') + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                    render: function (data) {
                        return "<small>" +
                            "<div class='mb-1'><b>" + data.jenjang_didik + " " + data.nama_program_studi + "</b></div>" +
                            "<div class='text-muted'><i class='fas fa-building mr-1'></i>" + data.nama_fakultas + "</div>" +
                            "<div class='text-muted'><i class='fas fa-code mr-1'></i>Kode:  " + data.kd_program_studi + "</div>" +
                            "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "22%",
                    render: function (data) {
                        var alamat_display = data.alamat ?
                            (data.alamat.length > 80 ? data.alamat.substring(0, 80) + '...' : data.alamat) : '-';

                        return "<small>" +
                            "<div class='mb-1'><i class='fas fa-birthday-cake mr-1'></i>" +
                            (data.tempat_lahir || '-') + ", " + (data.tanggal_lahir || '-') + "</div>" +
                            "<div class='mb-1'><i class='fas fa-pray mr-1'></i>" + (data.agama || '-') + "</div>" +
                            "<div class='text-muted'><i class='fas fa-map-marker-alt mr-1'></i>" + alamat_display + "</div>" +
                            "<div class='mt-1'>" +
                            "<i class='fas fa-phone mr-1'></i>" + (data.handphone || data.telepon || '-') + " | " +
                            "<i class='fas fa-envelope mr-1'></i>" + (data.email || '-') +
                            "</div>" +
                            "</small>";
                    }
                },
                {
                    data:  null,
                    searchable: false,
                    sClass: 'text-left',
                    width:  "15%",
                    render:  function (data) {
                        var funding_badge = '';
                        if (data.jenis_pendanaan) {
                            if (data.jenis_pendanaan.toLowerCase().includes('beasiswa')) {
                                funding_badge = '<span class="badge badge-warning badge-sm">' + data.jenis_pendanaan + '</span>';
                            } else {
                                funding_badge = '<span class="badge badge-secondary badge-sm">' + data.jenis_pendanaan + '</span>';
                            }
                        }

                        var lp3i_badge = data.is_lp3i ?
                            '<span class="badge badge-primary badge-sm">LP3I</span>' : '';

                        return "<small>" +
                            "<div class='mb-1'><i class='fas fa-user-friends mr-1'></i><b>Wali: </b> " + (data.nama_wali || '-') + "</div>" +
                            "<div class='mb-1'><i class='fas fa-female mr-1'></i><b>Ibu:</b> " + (data.nama_ibu || '-') + "</div>" +
                            "<div class='mt-2'>" + funding_badge + " " + lp3i_badge + "</div>" +
                            "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        var buttons = '';

                        if ($("#hak_akses").val() === "1") {
                            // Tombol Sync SIAKAD
                            buttons += "<button class='btn btn-success btn-sm mb-1 btn-sync-siakad' " +
                                "title='Sinkron data dengan siakad' data-nim='" + data.nim + "' " +
                                "data-nama='" + data.nama_mahasiswa + "'>" +
                                "<span class='spinner-border spinner-border-sm mr-1' id='sync-siakad-loading-spin-" + data.nim + "' " +
                                "style='display: none' role='status' aria-hidden='true'></span>" +
                                "<i class='fas fa-sync'></i> Sync" +
                                "</button><br/>";

                            // Tombol Edit
                            buttons += "<button class='btn btn-primary btn-sm mb-1 btn-edit-mahasiswa' " +
                                "title='Edit data mahasiswa' data-id='" + data.id_mahasiswa + "' " +
                                "data-nim='" + data.nim + "' data-nama='" + data.nama_mahasiswa + "'>" +
                                "<i class='fas fa-edit'></i> Edit" +
                                "</button><br/>";

                            // Tombol Ubah Status
                            var statusClass = 'btn-warning';
                            if (data.status_mahasiswa === 'AKTIF') {
                                statusClass = 'btn-warning';
                            } else if (data.status_mahasiswa === 'LULUS') {
                                statusClass = 'btn-info';
                            } else if (data.status_mahasiswa === 'CUTI') {
                                statusClass = 'btn-secondary';
                            } else {
                                statusClass = 'btn-dark';
                            }

                            buttons += "<button class='btn " + statusClass + " btn-sm btn-change-status' " +
                                "title='Ubah status mahasiswa' data-nim='" + data.nim + "' " +
                                "data-nama='" + data.nama_mahasiswa + "' data-status='" + data.status_mahasiswa + "'>" +
                                "<i class='fas fa-exchange-alt'></i> Status" +
                                "</button>";
                        } else {
                            buttons = "<button class='btn btn-secondary btn-sm' disabled><i class='fas fa-lock'></i> Locked</button>";
                        }

                        return buttons;
                    }
                },
                {
                    data: 'nama_mahasiswa',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'nim',
                    searchable: true,
                    visible: false
                },
                {
                    data: 'nik',
                    searchable: true,
                    visible: false
                },
                {
                    data: 'email',
                    searchable:  true,
                    visible: false
                },
            ],
            paging: true,
            processing: true,
            pageLength:  10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
            ordering: false,
            lengthChange: true,
            autoWidth: false,
            sDom: 'ltipr',
            language:  {
                "emptyTable":  "Tidak ditemukan data",
                "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>',
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "zeroRecords": "Tidak ditemukan data yang sesuai",
                "paginate": {
                    "first":  "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
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

        // SIAKAD Sync Button
        $("#btn-sync-ulang-data-center").click(function () {
            $("#modal-sync-mahasiswa-data-center").modal('show');
        });

        $("#modal-btn-sync-data-center").click(function () {
            $.confirm({
                title: 'Konfirmasi ! ',
                type: 'orange',
                content: 'Apakah anda yakin akan melakukan Sinkronisasi Data dengan SIAKAD? ',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass:  'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $("#modal-sync-mahasiswa-data-center").modal('hide');
                            self.isSyncDataCenterCanceled = false;
                            $("#progress-bar-syncron-ulang-data-center").show();
                            $("#log-syncron-ulang-data-center").show();
                            $("#btn-cancel-syncron-ulang-data-center").show();
                            $("#loading-progress-data-center").show();
                            $("#keterangan-progress-data-center").text("Mohon menunggu hingga proses sinkronisasi selesai ...");

                            // Clear log
                            self.data.log_table_data_center.clear().draw();

                            $.ajax({
                                url: '/adm-akademik/akademik/mahasiswa/sinkronisasi-mahasiswa-siakad/json-by-angkatan',
                                method: 'post',
                                data: {
                                    angkatan: $("#angkatan_sync-data-center").val()
                                },
                                success: function (result) {
                                    self.next_data_center(result);
                                }
                            });
                        }
                    },
                    cancel: {
                        text:  'Batal',
                        btnClass: 'btn-red'
                    }
                }
            });
        });

        // TAMBAHAN: PMB Sync Button (SIMPLIFIED)
        $("#btn-sync-pmb").click(function () {
            $("#modal-sync-mahasiswa-pmb").modal('show');
        });

        $("#modal-btn-sync-pmb").click(function () {
            $.confirm({
                title: 'Konfirmasi ! ',
                type: 'blue',
                content: 'Apakah anda yakin akan melakukan Sinkronisasi Data dengan PMB?  <br/><small class="text-muted">Proses ini akan berjalan di database server</small>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $("#modal-sync-mahasiswa-pmb").modal('hide');

                            // Show loading animation
                            $("#progress-bar-syncron-pmb").show();
                            $("#loading-progress-pmb").show();
                            $("#keterangan-progress-pmb").html(
                                '<i class="fas fa-database mr-2"></i>Proses sinkronisasi sedang berjalan di database server...<br/>' +
                                '<small class="text-white">Mohon tunggu, jangan tutup halaman ini</small>'
                            );
                            $("#btn-cancel-syncron-pmb").hide();

                            // Set indeterminate progress bar
                            $("#progress-bar-pmb").width('100%');
                            $("#progress-text-pmb").text('Processing...');

                            $.ajax({
                                url: '/adm-akademik/akademik/mahasiswa/sinkronisasi-mahasiswa-pmb/synchron',
                                method: 'post',
                                data: {
                                    tahun: $("#tahun_sync-pmb").val()
                                },
                                timeout: 300000, // 5 minutes timeout
                                success: function (result) {
                                    $("#loading-progress-pmb").hide();
                                    if (result.is_success) {
                                        $("#keterangan-progress-pmb").html(
                                            '<i class="fas fa-check-circle text-success mr-2"></i>Proses sinkronisasi selesai! <br/>' +
                                            '<small>Total diproses: <b>' + (result.total_processed || 0) + '</b> | ' +
                                            'Inserted: <b class="text-success">' + (result.total_inserted || 0) + '</b> | ' +
                                            'Updated: <b class="text-info">' + (result.total_updated || 0) + '</b> | ' +
                                            'Failed: <b class="text-danger">' + (result.total_failed || 0) + '</b></small>'
                                        );

                                        $.alert({
                                            title: "Sukses",
                                            type: "green",
                                            content: result.result || "Sinkronisasi berhasil dilakukan",
                                            columnClass: 'large'
                                        });

                                        // Reload table
                                        self.data.table_data_center.ajax.reload();
                                    } else {
                                        $("#keterangan-progress-pmb").html(
                                            '<i class="fas fa-exclamation-circle text-danger mr-2"></i>Proses gagal!'
                                        );

                                        $.alert({
                                            title: "Error",
                                            type: "red",
                                            content: result.result || "Terjadi kesalahan saat sinkronisasi",
                                            columnClass: 'large'
                                        });
                                    }
                                },
                                error:  function(xhr, status) {
                                    $("#loading-progress-pmb").hide();
                                    $("#keterangan-progress-pmb").html(
                                        '<i class="fas fa-times-circle text-danger mr-2"></i>Koneksi error!'
                                    );

                                    var errorMsg = "Gagal menghubungi server";
                                    if (status === 'timeout') {
                                        errorMsg = "Request timeout - Proses memakan waktu terlalu lama";
                                    } else if (xhr.responseJSON && xhr.responseJSON.result) {
                                        errorMsg = xhr.responseJSON.result;
                                    }

                                    $.alert({
                                        title: "Error",
                                        type: "red",
                                        content:  errorMsg,
                                        columnClass: 'large'
                                    });
                                },
                                complete: function() {
                                    // Hide progress after 3 seconds if success
                                    setTimeout(function() {
                                        if (!$("#loading-progress-pmb").is(':visible')) {
                                            $("#progress-bar-syncron-pmb").fadeOut();
                                        }
                                    }, 1000);
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

        // Close PMB Progress Bar
        $("#btn-tutup-progress-pmb").click(function() {
            $("#progress-bar-syncron-pmb").hide();
        });

        // TAMBAHAN:  Event Handler untuk Tombol Edit
        $("#table-data-center").on('click', 'button.btn-edit-mahasiswa', function () {
            var id = $(this).data("id");
            var nim = $(this).data("nim");
            var nama = $(this).data('nama');

            // Redirect ke halaman edit
            window.location.href = '/adm-akademik/akademik/mahasiswa/edit/' + nim;
        });

// TAMBAHAN: Event Handler untuk Tombol Ubah Status
        $("#table-data-center").on('click', 'button.btn-change-status', function () {
            var nim = $(this).data("nim");
            var nama = $(this).data('nama');
            var currentStatus = $(this).data('status');

            // Set current status di modal
            $("#nim-change-status").val(nim);
            $("#nama-change-status").text(nama);
            $("#current-status-display").text(currentStatus);
            $("#new-status-mahasiswa").val('').trigger('change');

            // Show modal
            $("#modal-change-status-mahasiswa").modal('show');
        });

// TAMBAHAN: Submit Change Status
        $("#btn-submit-change-status").click(function() {
            var nim = $("#nim-change-status").val();
            var newStatus = $("#new-status-mahasiswa").val();
            var alasan = $("#alasan-change-status").val();

            if (! newStatus) {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Silahkan pilih status baru terlebih dahulu"
                });
                return;
            }

            $.confirm({
                title: 'Konfirmasi ! ',
                type: 'orange',
                content: 'Apakah anda yakin akan mengubah status mahasiswa? ',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action:  function () {
                            $.ajax({
                                url: '/adm-akademik/akademik/mahasiswa/sinkronisasi-mahasiswa-siakad/change-status',
                                method: 'POST',
                                data: {
                                    nim:  nim,
                                    status: newStatus,
                                    alasan: alasan
                                },
                                beforeSend: function() {
                                    $("#btn-submit-change-status").prop('disabled', true).html(
                                        '<span class="spinner-border spinner-border-sm mr-2"></span>Processing...'
                                    );
                                },
                                success: function (result) {
                                    if (result.is_success) {
                                        $.alert({
                                            title: "Sukses",
                                            type: "green",
                                            content: result.result
                                        });
                                        $("#modal-change-status-mahasiswa").modal('hide');
                                        self.data.table_data_center.ajax.reload();
                                    } else {
                                        $.alert({
                                            title: "Error",
                                            type: "red",
                                            content:  result.result
                                        });
                                    }
                                },
                                error: function() {
                                    $.alert({
                                        title: "Error",
                                        type: "red",
                                        content: "Terjadi kesalahan saat mengubah status"
                                    });
                                },
                                complete: function() {
                                    $("#btn-submit-change-status").prop('disabled', false).html(
                                        '<i class="fas fa-save mr-2"></i>Simpan Perubahan'
                                    );
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

        // Log SIAKAD Events
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
                content: 'Proses sinkronisasi akan dihentikan, apakah anda yakin ? ',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys:  ['enter'],
                        action:  function () {
                            self.isSyncDataCenterCanceled = true;
                        }
                    },
                    cancel: {
                        text:  'Batal',
                        btnClass: 'btn-red'
                    }
                }
            });
        });

        $("#table-data-center").on('click', 'button.btn-sync-siakad', function () {
            var nim = $(this).data("nim");
            var nama = $(this).data('nama');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin melakukan sinkronisasi data atas nama <b>' + nama + '</b> dengan data siakad ? <br/><b class="text-danger">Semua data akan di update berdasarkan data siakad</b>',
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
                                                'npk': response.nim,
                                                'inf_nisn': response.nisn,
                                                'dosen_wali': response.p_dosen_wali,
                                                'tgl_lulus_sma': response.p_tgl_lulus_slta,
                                                'inf_jurusan_sma': response.p_inf_jurusan_sma,
                                                'sekolah_asal': response.p_sekolah_asal,
                                                'inf_tgl_lulus':  response.p_inf_tgl_lulus,
                                                'inf_nomor_ijazah': response.p_nomor_ijazah,
                                                'inf_nomor_transkrip': response.p_inf_nomor_transkrip,
                                                'status_aktif': response.p_sts_aktif,
                                                'program_id': response.p_program_id,
                                                'konsentrasi_id': response.p_konsentrasi_id,
                                                'nama_wali': response.p_nama_wali,
                                                'pekerjaan_wali': response.p_pekerjaan_wali,
                                                'jenis_mahasiswa': response.p_jenis_mahasiswa,
                                                'jenis_pendanaan': response.p_jenis_pendanaan,
                                                'nomor_seri_ijazah': response.p_nomor_seri_ijazah,
                                                'nama_lengkap': response.p_nama_lengkap,
                                                'tempat_lahir': response.p_tempat_lahir,
                                                'tanggal_lahir': response.p_tanggal_lahir,
                                                'jenis_kelamin': response.p_jenis_kelamin,
                                                'agama_id': response.p_agama,
                                                'status_menikah': response.p_status_menikah,
                                                'hp': response.p_no_hp,
                                                'telepon_rumah': response.p_telepon_rumah,
                                                'alamat_rumah': response.p_alamat_rumah,
                                                'kode_pos_rumah': response.p_kd_pos,
                                                'inf_warga_negara': response.p_inf_warga_negara,
                                                'email': response.p_email,
                                                'nik': response.p_nik,
                                                'rt': response.p_rt,
                                                'rw': response.p_rw,
                                                'ds_kel': response.p_kelurahan,
                                                'nama_ibu': response.p_nama_ibu,
                                                'password': response.p_password,
                                                'angkatan': response.p_tahun_angkatan,
                                                'jenis_kelas': response.p_jenis_kelas,
                                                'judul_skripsi': response.p_judul_skripsi,
                                                'ipk': response.p_ipk,
                                                'nama_program': response.p_nama_program,
                                                'kota_rumah': response.p_kota_rumah,
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
                                        });
                                    } else {
                                        $.alert({
                                            title: "Peringatan",
                                            type: "red",
                                            content: "Data Mahasiswa Tidak Ditemukan"
                                        });
                                    }
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
    },

    // Fungsi untuk SIAKAD (tetap sama)
    next_data_center: function (data, index = 0, progres = 0, failed = 0, inserted = 0, updated = 0) {
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/adm-akademik/akademik/mahasiswa/sinkronisasi-mahasiswa-siakad/synchron',
            method: 'post',
            data: {
                'npk': data[index].nim,
                'inf_nisn': data[index].nisn,
                'dosen_wali': data[index].p_dosen_wali,
                'tgl_lulus_sma': data[index].p_tgl_lulus_slta,
                'inf_jurusan_sma': data[index].p_inf_jurusan_sma,
                'sekolah_asal': data[index].p_sekolah_asal,
                'inf_tgl_lulus': data[index].p_inf_tgl_lulus,
                'inf_nomor_ijazah': data[index].p_nomor_ijazah,
                'inf_nomor_transkrip':  data[index].p_inf_nomor_transkrip,
                'status_aktif': data[index].p_sts_aktif,
                'program_id': data[index].p_program_id,
                'konsentrasi_id': data[index].p_konsentrasi_id,
                'nama_wali': data[index].p_nama_wali,
                'pekerjaan_wali': data[index].p_pekerjaan_wali,
                'jenis_mahasiswa':  data[index].p_jenis_mahasiswa,
                'jenis_pendanaan': data[index].p_jenis_pendanaan,
                'nomor_seri_ijazah':  data[index].p_nomor_seri_ijazah,
                'nama_lengkap': data[index].p_nama_lengkap,
                'tempat_lahir': data[index].p_tempat_lahir,
                'tanggal_lahir': data[index].p_tanggal_lahir,
                'jenis_kelamin': data[index].p_jenis_kelamin,
                'agama_id': data[index].p_agama,
                'status_menikah':  data[index].p_status_menikah,
                'hp': data[index].p_no_hp,
                'telepon_rumah': data[index].p_telepon_rumah,
                'alamat_rumah': data[index].p_alamat_rumah,
                'kode_pos_rumah':  data[index].p_kd_pos,
                'inf_warga_negara': data[index].p_inf_warga_negara,
                'email': data[index].p_email,
                'nik': data[index].p_nik,
                'rt': data[index].p_rt,
                'rw': data[index].p_rw,
                'ds_kel': data[index].p_kelurahan,
                'nama_ibu': data[index].p_nama_ibu,
                'password': data[index].p_password,
                'angkatan': data[index].p_tahun_angkatan,
                'jenis_kelas': data[index].p_jenis_kelas,
                'judul_skripsi': data[index].p_judul_skripsi,
                'ipk': data[index].p_ipk,
                'nama_program': data[index].p_nama_program,
                'kota_rumah': data[index].p_kota_rumah,
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
                            data[index].p_nama_lengkap + " (" + data[index].nim + ")",
                            "<i class='fas fa-check-circle text-success p-1'></i> " + result.result,
                            data[index].p_nama_lengkap,
                            data[index].nim,
                            "updated"
                        ]).draw();
                        updated++;
                    }
                } else {
                    self.data.log_table_data_center.row.add([
                        (index + 1),
                        data[index].p_nama_lengkap + " (" + data[index].nim + ")",
                        "<i class='fas fa-times-circle text-danger p-1'></i> " + result.result,
                        data[index].p_nama_lengkap,
                        data[index].nim,
                        "gagal"
                    ]).draw();
                    failed++;
                }
            },
            error: function () {
                self.data.log_table_data_center.row.add([
                    (index + 1),
                    data[index].p_nama_lengkap + " (" + data[index].nim + ")",
                    "<i class='fas fa-times-circle text-danger p-1'></i> Error",
                    data[index].p_nama_lengkap,
                    data[index].nim,
                    "gagal"
                ]).draw();
                failed++;
            },
            complete: function () {
                progres++;
                $("#progress-bar-data-center").width((progres / n * 100).toFixed(2) + '%');
                $("#progress-text-data-center").text((progres / n * 100).toFixed(2) + '% Complete');
                $("#btn-failed-log-data-center").text("Failed :  " + failed);
                $("#btn-inserted-log-data-center").text("Inserted : " + inserted);
                $("#btn-updated-log-data-center").text("Updated : " + updated);
                if (index >= (data.length - 1)) {
                    self.data.table_data_center.search("").draw();
                    $("#log-syncron-ulang-data-center").show();
                    $("#progress-bar-syncron-ulang-data-center").hide();
                } else {
                    if (! self.isSyncDataCenterCanceled) {
                        index++;
                        self.next_data_center(data, index, progres, failed, inserted, updated);
                    } else {
                        $("#loading-progress-data-center").hide();
                        $("#keterangan-progress-data-center").text("Dibatalkan oleh pengguna");
                        $("#log-syncron-ulang-data-center").show();
                        $("#btn-cancel-syncron-ulang-data-center").hide();
                    }
                }
            }
        });
    },
};

jQuery(document).ready(function () {
    jQuery.sinkronisasi_mahasiswa_siakad.init();
});
