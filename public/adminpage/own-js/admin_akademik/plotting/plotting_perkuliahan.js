jQuery.plotting_perkuliahan = {
    data: {
        table: null,
        isPopulatingData: false,  // Flag untuk mencegah cascade refresh
        editData: null            // Menyimpan data untuk edit
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
                url: '/adm-akademik/plotting/plotting-kelas/json',
                type: 'post',
                data: function (data) {
                    data.kd_prodi = $("#filter_kd_prodi").val();
                    data.tahun_akademik = $("#filter_tahun_akademik").val();
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
                    width: "15%",
                    render: function (data) {
                        return "<span class='badge badge-info'>" + data.kd_matakuliah + "</span>" +
                            "<span class='badge badge-success'>" + data.nama_prodi + "</span>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                    render: function (data) {
                        return "<strong>" + data.nama_matakuliah + "</strong><br><small class='text-muted'>" + data.sks + " SKS</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                    render: function (data) {
                        return data.nama_dosen || data.nama_karyawan || '-';
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "12%",
                    render: function (data) {
                        return "<span class='badge badge-primary'>" + data.nama_jenis_pengajaran + "</span>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "10%",
                    render: function (data) {
                        return "<span class='badge badge-secondary'>" + data.kd_kelas + " - "+ data.nama_kelas + "</span>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "8%",
                    render: function (data) {
                        if (data.sts_aktif === true || data.sts_aktif === 1) {
                            return "<span class='badge badge-success'>Aktif</span>";
                        } else {
                            return "<span class='badge badge-danger'>Non-Aktif</span>";
                        }
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "10%",
                    render: function (data) {
                        var editButton = "<button title='Edit Plotting' class='btn btn-sm btn-primary btn-edit mr-1' " +
                            "data-id='" + data.id_ploting_matakuliah + "' " +
                            "data-id_matakuliah='" + data.id_matakuliah + "' " +
                            "data-id_karyawan='" + data.id_karyawan + "' " +
                            "data-tahun_akademik='" + data.tahun_akademik + "' " +
                            "data-jenis_pengajaran='" + data.jenis_pengajaran + "' " +
                            "data-id_kelas='" + data.id_kelas + "' " +
                            "data-kd_prodi='" + data.kd_prodi + "' " +
                            "data-id_kurikulum='" + data.id_kurikulum + "'>" +
                            "<i class='fas fa-edit'></i></button>";

                        var deleteButton = "";
                        if (data.sts_aktif === true || data.sts_aktif === 1) {
                            deleteButton = "<button title='Non-Aktifkan Plotting' class='btn btn-sm btn-danger btn-delete' " +
                                "data-id='" + data.id_ploting_matakuliah + "' " +
                                "data-nama_matakuliah='" + data.nama_matakuliah + "' " +
                                "data-status='false'>" +
                                "<span class='spinner-border spinner-border-sm mr-1' id='detail-loading-spin-" + data.id_plotting + "' style='display: none' role='status' aria-hidden='true'></span>" +
                                "<i class='fas fa-trash'></i></button>";
                        } else {
                            deleteButton = "<button title='Aktifkan Plotting' class='btn btn-sm btn-success btn-delete' " +
                                "data-id='" + data.id_ploting_matakuliah + "' " +
                                "data-nama_matakuliah='" + data.nama_matakuliah + "' " +
                                "data-status='true'>" +
                                "<span class='spinner-border spinner-border-sm mr-1' id='detail-loading-spin-" + data.id_plotting + "' style='display: none' role='status' aria-hidden='true'></span>" +
                                "<i class='fas fa-check'></i></button>";
                        }

                        return editButton + deleteButton;
                    }
                },
                {
                    data: 'nama_matakuliah',
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
                "emptyTable": "Tidak ditemukan data plotting",
                "processing": "Memuat data...",
                "loadingRecords": "Memuat data...",
                "zeroRecords": "Tidak ditemukan data yang sesuai"
            }
        });

        // Cascade Events - Simplified karena edit menggunakan populateEditForm
        $("#kd_prodi").change(function () {
            var kd_prodi = $(this).val();
            if (kd_prodi && !self.data.isPopulatingData) {
                self.loadKurikulum(kd_prodi);
            } else if (!kd_prodi) {
                $("#id_kurikulum").empty().append('<option value="">Pilih Kurikulum</option>').trigger('change');
            }
        });

        $("#id_kurikulum").change(function () {
            var id_kurikulum = $(this).val();
            if (id_kurikulum && !self.data.isPopulatingData) {
                self.loadMatakuliah(id_kurikulum);
            } else if (!id_kurikulum) {
                $("#id_matakuliah").empty().append('<option value="">Pilih Mata Kuliah</option>');
            }
        });

        $("#tahun_akademik").change(function () {
            var tahun_akademik = $(this).val();
            var kd_prodi = $("#kd_prodi").val();
            if (tahun_akademik && kd_prodi && !self.data.isPopulatingData) {
                self.loadKelas(kd_prodi, tahun_akademik);
            } else if (!tahun_akademik || !kd_prodi) {
                $("#id_kelas").empty().append('<option value="">Pilih Kelas</option>');
            }
        });

        // Filter Events
        $("#filter_kd_prodi").change(function () {
            var filterReady = $(this).val() && $("#filter_tahun_akademik").val();
            if (filterReady) {
                self.data.table.ajax.reload();
            } else {
                self.data.table.clear().draw();
            }
        });

        $("#filter_tahun_akademik").change(function () {
            var filterReady = $(this).val() && $("#filter_kd_prodi").val();
            if (filterReady) {
                self.data.table.ajax.reload();
            } else {
                self.data.table.clear().draw();
            }
        });

        $("#btn-cari-data").click(function () {
            if ($("#filter_kd_prodi").val() && $("#filter_tahun_akademik").val()) {
                self.data.table.search($("#cari-data").val()).draw();
            } else {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pilih Program Studi dan Tahun Akademik terlebih dahulu"
                });
            }
        });

        $("#cari-data").keyup(function () {
            if (this.value === "" && $("#filter_kd_prodi").val() && $("#filter_tahun_akademik").val()) {
                self.data.table.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13 && $("#filter_kd_prodi").val() && $("#filter_tahun_akademik").val()) {
                self.data.table.search(this.value).draw();
            }
        });

        // Form Events
        $("#btn-tambah-data").click(function () {
            if (!$("#filter_kd_prodi").val() || !$("#filter_tahun_akademik").val()) {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pilih Program Studi dan Tahun Akademik terlebih dahulu"
                });
                return;
            }
            self.resetForm();
            $("#kd_prodi").val($("#filter_kd_prodi").val()).trigger('change');
            $("#tahun_akademik").val($("#filter_tahun_akademik").val()).trigger('change');
            $("#filter-collapse").collapse("hide");
            $("#form-collapse").collapse("show");
        });

        $("#btn-cancel").click(function () {
            self.resetForm();
            $("#filter-collapse").collapse("show");
            $("#form-collapse").collapse("hide");
        });

        // Import Events
        $("#btn-import-data").click(function () {
            $("#filter-collapse").collapse("hide");
            $("#form-collapse").collapse("hide");
            $("#import-collapse").collapse("show");
        });

        $("#btn-cancel-import").click(function () {
            self.resetImportForm();
            $("#filter-collapse").collapse("show");
            $("#import-collapse").collapse("hide");
        });

        // Custom file input label update
        $('#file_import').on('change', function () {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Pilih file...');
        });

        // Import form submit dengan progress
        $("#form-import").submit(function (e) {
            e.preventDefault();

            var fileInput = $("#file_import")[0];
            if (!fileInput.files || fileInput.files.length === 0) {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pilih file Excel yang akan diimport"
                });
                return;
            }

            var file = fileInput.files[0];
            var allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];

            if (!allowedTypes.includes(file.type)) {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Format file tidak didukung. Gunakan file .xlsx atau .xls"
                });
                return;
            }

            if (file.size > 2 * 1024 * 1024) { // 2MB
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Ukuran file terlalu besar. Maksimal 2MB"
                });
                return;
            }

            // Start import process
            self.processExcelImport(file);
        });

        // Edit Event - Menggunakan populateEditForm
        $("#table").on('click', 'button.btn-edit', function () {
            // Set flag bahwa sedang populate data
            self.data.isPopulatingData = true;

            // Simpan data edit untuk digunakan di sequential loading
            self.data.editData = {
                id: $(this).data("id"),
                kd_prodi: $(this).data("kd_prodi"),
                id_kurikulum: $(this).data("id_kurikulum"),
                id_matakuliah: $(this).data("id_matakuliah"),
                id_karyawan: $(this).data("id_karyawan"),
                tahun_akademik: $(this).data("tahun_akademik"),
                jenis_pengajaran: $(this).data("jenis_pengajaran"),
                id_kelas: $(this).data("id_kelas")
            };

            // Set ID plotting
            $("#id_plotting").val(self.data.editData.id);

            // Mulai sequential loading
            self.populateEditForm();

            $("#filter-collapse").collapse("hide");
            $("#form-collapse").collapse("show");
        });

        // Save Event
        $("#btn-save").click(function () {
            if (!self.validateForm()) {
                return;
            }

            var id = $("#id_plotting").val();
            var data = {
                id_matakuliah: $("#id_matakuliah").val(),
                id_karyawan: $("#id_karyawan").val(),
                tahun_akademik: $("#tahun_akademik").val(),
                jenis_pengajaran: $("#jenis_pengajaran").val(),
                id_kelas: $("#id_kelas").val()
            };

            if (id && id !== "") {
                data.id = id;

                $.ajax({
                    url: '/adm-akademik/plotting/plotting-kelas/update',
                    method: 'POST',
                    data: data,
                    beforeSend: function () {
                        $("#loading-tambah-data").show();
                        $("#btn-save").prop('disabled', true);
                    },
                    success: function (response) {
                        if (response.success === true) {
                            $.alert({
                                title: 'Berhasil',
                                type: 'green',
                                content: response.message
                            });
                            $("#btn-cancel").trigger("click");
                        } else {
                            $.alert({
                                title: 'Gagal',
                                type: 'red',
                                content: response.message || 'Terjadi kesalahan saat menyimpan data'
                            });
                        }
                    },
                    error: function (xhr) {
                        var errorMessage = 'Terjadi kesalahan saat menyimpan data';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            var errorList = [];
                            for (var field in errors) {
                                errorList = errorList.concat(errors[field]);
                            }
                            errorMessage = errorList.join('<br>');
                        }
                        $.alert({
                            title: 'Error',
                            type: 'red',
                            content: errorMessage
                        });
                    },
                    complete: function () {
                        $("#loading-tambah-data").hide();
                        $("#btn-save").prop('disabled', false);
                        self.data.table.ajax.reload();
                    }
                });
            } else {
                $.ajax({
                    url: '/adm-akademik/plotting/plotting-kelas/store',
                    method: 'POST',
                    data: data,
                    beforeSend: function () {
                        $("#loading-tambah-data").show();
                        $("#btn-save").prop('disabled', true);
                    },
                    success: function (response) {
                        if (response.success === true) {
                            $.alert({
                                title: 'Berhasil',
                                type: 'green',
                                content: response.message
                            });
                            $("#btn-cancel").trigger("click");
                        } else {
                            $.alert({
                                title: 'Gagal',
                                type: 'red',
                                content: response.message || 'Terjadi kesalahan saat menyimpan data'
                            });
                        }
                    },
                    error: function (xhr) {
                        var errorMessage = 'Terjadi kesalahan saat menyimpan data';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            var errorList = [];
                            for (var field in errors) {
                                errorList = errorList.concat(errors[field]);
                            }
                            errorMessage = errorList.join('<br>');
                        }
                        $.alert({
                            title: 'Error',
                            type: 'red',
                            content: errorMessage
                        });
                    },
                    complete: function () {
                        $("#loading-tambah-data").hide();
                        $("#btn-save").prop('disabled', false);
                        self.data.table.ajax.reload();
                    }
                });
            }
        });

        // Delete/Activate Event
        $("#table").on('click', 'button.btn-delete', function () {
            var id = $(this).data("id");
            var nama_matakuliah = $(this).data('nama_matakuliah');
            var status = $(this).data('status');
            var action = status === true ? 'Mengaktifkan' : 'Menonaktifkan';
            var keterangan = 'Apakah anda yakin ' + action + ' plotting mata kuliah <b>' + nama_matakuliah + '</b>?';

            $.confirm({
                title: 'Konfirmasi!',
                type: 'orange',
                columnClass: 'medium',
                content: keterangan,
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/adm-akademik/plotting/plotting-kelas/delete',
                                method: 'POST',
                                data: {
                                    id: id,
                                    status: status === true
                                },
                                beforeSend: function () {
                                    $("#detail-loading-spin-" + id).show();
                                },
                                success: function (response) {
                                    if (response.success === true) {
                                        $.alert({
                                            title: 'Berhasil',
                                            type: 'green',
                                            content: response.message
                                        });
                                    } else {
                                        $.alert({
                                            title: 'Gagal',
                                            type: 'red',
                                            content: response.message || 'Terjadi kesalahan saat memperbarui status'
                                        });
                                    }
                                },
                                error: function (xhr) {
                                    var errorMessage = 'Terjadi kesalahan saat memperbarui status';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        errorMessage = xhr.responseJSON.message;
                                    }
                                    $.alert({
                                        title: 'Error',
                                        type: 'red',
                                        content: errorMessage
                                    });
                                },
                                complete: function () {
                                    $("#detail-loading-spin-" + id).hide();
                                    self.data.table.ajax.reload();
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

    // Method untuk populate form edit secara sequential
    populateEditForm: function () {
        var self = this;
        var editData = self.data.editData;

        console.log('Starting populateEditForm with data:', editData);

        // Step 1: Set Program Studi dan load kurikulum
        $("#kd_prodi").val(editData.kd_prodi).trigger('change');
        console.log('Step 1: Set prodi to', editData.kd_prodi);

        self.loadKurikulum(editData.kd_prodi, function () {
            console.log('Step 2: Kurikulum loaded, setting to', editData.id_kurikulum);
            // Step 2: Set kurikulum dan load mata kuliah
            $("#id_kurikulum").val(editData.id_kurikulum).trigger('change');

            self.loadMatakuliah(editData.id_kurikulum, function () {
                console.log('Step 3: Matakuliah loaded, setting to', editData.id_matakuliah);
                // Step 3: Set mata kuliah
                $("#id_matakuliah").val(editData.id_matakuliah).trigger('change');

                // Step 4: Set tahun akademik dan load kelas
                $("#tahun_akademik").val(editData.tahun_akademik).trigger('change');
                console.log('Step 4: Set tahun akademik to', editData.tahun_akademik);

                self.loadKelas(editData.kd_prodi, editData.tahun_akademik, function () {
                    console.log('Step 5: Kelas loaded, setting remaining fields');
                    // Step 5: Set semua field sisanya
                    $("#id_kelas").val(editData.id_kelas).trigger('change');
                    $("#id_karyawan").val(editData.id_karyawan).trigger('change');
                    $("#jenis_pengajaran").val(editData.jenis_pengajaran).trigger('change');

                    // Step 6: Reset flag setelah semua selesai
                    setTimeout(function () {
                        self.data.isPopulatingData = false;
                        self.data.editData = null;
                        console.log('Step 6: Populate completed, flags reset');
                    }, 100);
                });
            });
        });
    },

    // Method untuk process Excel import dengan progress
    processExcelImport: function (file) {
        var self = this;

        // Show progress section
        $("#import-progress").show();
        $("#loading-import").show();
        $("#btn-submit-import").prop('disabled', true);
        $("#btn-cancel-import").prop('disabled', true);

        // Reset progress
        self.resetProgress();

        var reader = new FileReader();
        reader.onload = function (e) {
            try {
                // Parse Excel file menggunakan SheetJS
                if (typeof XLSX === 'undefined') {
                    $.alert({
                        title: "Error",
                        type: "red",
                        content: "Library XLSX tidak tersedia. Pastikan SheetJS sudah di-load."
                    });
                    self.resetImportForm();
                    return;
                }

                var data = new Uint8Array(e.target.result);
                var workbook = XLSX.read(data, {type: 'array'});
                var firstSheetName = workbook.SheetNames[0];
                var worksheet = workbook.Sheets[firstSheetName];

                // Convert to JSON
                var jsonData = XLSX.utils.sheet_to_json(worksheet, {header: 1});

                // Remove header row first
                var allRows = jsonData.slice(1);

                // Filter out completely empty rows AND rows with incomplete data
                var validDataRows = [];
                var skippedRows = 0;

                for (var i = 0; i < allRows.length; i++) {
                    var row = allRows[i];
                    var rowNumber = i + 2; // +2 karena header dan 0-based index

                    // Check if row is completely empty
                    var isEmpty = true;
                    for (var j = 0; j < 5; j++) {
                        if (row[j] && String(row[j]).trim() !== '') {
                            isEmpty = false;
                            break;
                        }
                    }

                    if (isEmpty) {
                        // Skip completely empty rows without logging
                        skippedRows++;
                        continue;
                    }

                    // Check if row has all required data (5 columns)
                    var hasAllData = true;
                    for (var k = 0; k < 5; k++) {
                        if (!row[k] || String(row[k]).trim() === '') {
                            hasAllData = false;
                            break;
                        }
                    }

                    if (hasAllData) {
                        // Add row with its original row number for tracking
                        validDataRows.push({
                            data: row,
                            originalRowNumber: rowNumber
                        });
                    }
                }

                if (validDataRows.length === 0) {
                    $.alert({
                        title: "Peringatan",
                        type: "orange",
                        content: "File Excel tidak berisi data yang valid atau format tidak sesuai"
                    });
                    self.resetImportForm();
                    return;
                }

                // Set total rows (hanya yang valid)
                $("#total-rows").text(validDataRows.length);

                var statusMessage = "Memulai import " + validDataRows.length + " baris data valid...";
                if (skippedRows > 0) {
                    statusMessage += " (" + skippedRows + " baris kosong diabaikan)";
                }
                self.addStatus(statusMessage, "info");

                // Process only valid rows sequentially
                self.processValidRowsSequentially(validDataRows, 0);

            } catch (error) {
                $.alert({
                    title: "Error",
                    type: "red",
                    content: "Gagal membaca file Excel: " + error.message
                });
                self.resetImportForm();
            }
        };

        reader.readAsArrayBuffer(file);
    },

    // Method baru untuk process rows yang sudah valid
    processValidRowsSequentially: function(validDataRows, currentIndex) {
        var self = this;

        if (currentIndex >= validDataRows.length) {
            // Selesai import
            self.finishImport();
            return;
        }

        var rowData = validDataRows[currentIndex];
        var row = rowData.data;
        var originalRowNumber = rowData.originalRowNumber;

        // Update current row
        $("#current-row").text(originalRowNumber);

        // Prepare data (semua row di sini sudah dipastikan valid)
        var importData = {
            kd_matakuliah: String(row[0]).trim(),
            nidn: String(row[1]).trim(),
            tahun_akademik: String(row[2]).trim(),
            jenis_pengajaran: String(row[3]).trim(),
            kd_kelas: String(row[4]).trim()
        };

        self.addStatus("Baris " + originalRowNumber + ": Memproses " + importData.kd_matakuliah + "...", "info");

        // Insert via AJAX
        $.ajax({
            url: '/adm-akademik/plotting/plotting-kelas/import',
            method: 'POST',
            data: importData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success === true) {
                    self.addStatus("Baris " + originalRowNumber + ": " + importData.kd_matakuliah + " - BERHASIL", "success");
                    self.incrementSuccess();
                } else {
                    self.addStatus("Baris " + originalRowNumber + ": " + (response.keterangan || response.message || "Import gagal"), "danger");
                    self.incrementError();
                }
            },
            error: function(xhr) {
                var errorMessage = "Error tidak diketahui";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    var errorList = [];
                    for (var field in errors) {
                        errorList = errorList.concat(errors[field]);
                    }
                    errorMessage = errorList.join(', ');
                }

                self.addStatus("Baris " + originalRowNumber + ": " + errorMessage, "danger");
                self.incrementError();
            },
            complete: function() {
                // Update progress berdasarkan valid rows, bukan total rows
                self.updateProgress(currentIndex + 1, validDataRows.length);

                // Process next row
                setTimeout(function() {
                    self.processValidRowsSequentially(validDataRows, currentIndex + 1);
                }, 200);
            }
        });
    },

    // Helper methods untuk progress tracking
    resetProgress: function () {
        $("#progress-bar").css('width', '0%').text('0%');
        $("#total-rows").text('0');
        $("#success-count").text('0');
        $("#error-count").text('0');
        $("#current-row").text('-');
        $("#import-status").html('');
    },

    updateProgress: function (current, total) {
        var percentage = Math.round((current / total) * 100);
        $("#progress-bar").css('width', percentage + '%').text(percentage + '%');

        if (percentage === 100) {
            $("#progress-bar").removeClass('progress-bar-animated');
        }
    },

    incrementSuccess: function () {
        var current = parseInt($("#success-count").text()) + 1;
        $("#success-count").text(current);
    },

    incrementError: function () {
        var current = parseInt($("#error-count").text()) + 1;
        $("#error-count").text(current);
    },

    addStatus: function (message, type) {
        var badgeClass = 'badge-secondary';
        switch (type) {
            case 'success':
                badgeClass = 'badge-success';
                break;
            case 'danger':
                badgeClass = 'badge-danger';
                break;
            case 'warning':
                badgeClass = 'badge-warning';
                break;
            case 'info':
                badgeClass = 'badge-info';
                break;
        }

        var statusHtml = '<div class="mb-1"><span class="badge ' + badgeClass + '">' +
            new Date().toLocaleTimeString() + '</span> ' + message + '</div>';

        $("#import-status").prepend(statusHtml);

        // Auto scroll to top
        $("#import-status").scrollTop(0);

        // Limit status entries (keep only last 50)
        var statusEntries = $("#import-status").children();
        if (statusEntries.length > 50) {
            statusEntries.slice(50).remove();
        }
    },

    finishImport: function () {
        var self = this;
        var successCount = parseInt($("#success-count").text());
        var errorCount = parseInt($("#error-count").text());

        $("#loading-import").hide();
        $("#btn-submit-import").prop('disabled', false);
        $("#btn-cancel-import").prop('disabled', false);

        self.addStatus("Import selesai! Berhasil: " + successCount + ", Gagal: " + errorCount, "info");

        $.alert({
            title: 'Import Selesai',
            type: errorCount > 0 ? 'orange' : 'green',
            content: "Import data selesai.<br>Berhasil: " + successCount + " data<br>Gagal: " + errorCount + " data"
        });

        // Reload table jika ada filter
        if ($("#filter_kd_prodi").val() && $("#filter_tahun_akademik").val()) {
            self.data.table.ajax.reload();
        }
    },

    resetImportForm: function () {
        $("#file_import").val('');
        $(".custom-file-label").text('Pilih file...');
        $("#import-progress").hide();
        $("#loading-import").hide();
        $("#btn-submit-import").prop('disabled', false);
        $("#btn-cancel-import").prop('disabled', false);
        this.resetProgress();
    },

    // Cascade Methods - Modified untuk mendukung callback
    loadKurikulum: function (kd_prodi, callback) {
        var self = this;
        $.ajax({
            url: '/adm-akademik/plotting/plotting-kelas/json-kurikulum-by-prodi',
            method: 'POST',
            data: {kd_prodi: kd_prodi},
            success: function (response) {
                if (response.success && response.data) {
                    var options = '<option value="">Pilih Kurikulum</option>';
                    $.each(response.data, function (index, item) {
                        options += '<option value="' + item.id_kurikulum + '">' + item.nama_kurikulum + '</option>';
                    });
                    $("#id_kurikulum").html(options);
                    console.log('loadKurikulum success, options loaded');

                    // Panggil callback jika ada
                    if (typeof callback === 'function') {
                        callback();
                    }
                }
            },
            error: function () {
                $("#id_kurikulum").html('<option value="">Pilih Kurikulum</option>');
                console.log('loadKurikulum error');
                if (typeof callback === 'function') {
                    callback();
                }
            }
        });
    },

    loadMatakuliah: function (id_kurikulum, callback) {
        var self = this;
        $.ajax({
            url: '/adm-akademik/plotting/plotting-kelas/json-matakuliah-by-kurikulum',
            method: 'POST',
            data: {id_kurikulum: id_kurikulum},
            success: function (response) {
                if (response.success && response.data) {
                    var options = '<option value="">Pilih Mata Kuliah</option>';
                    $.each(response.data, function (index, item) {
                        options += '<option value="' + item.id_matakuliah + '">' + item.kd_matakuliah + ' - ' + item.matakuliah + ' (' + item.sks + ' SKS)</option>';
                    });
                    $("#id_matakuliah").html(options);
                    console.log('loadMatakuliah success, options loaded');

                    // Panggil callback jika ada
                    if (typeof callback === 'function') {
                        callback();
                    }
                }
            },
            error: function () {
                $("#id_matakuliah").html('<option value="">Pilih Mata Kuliah</option>');
                console.log('loadMatakuliah error');
                if (typeof callback === 'function') {
                    callback();
                }
            }
        });
    },

    loadKelas: function (kd_prodi, tahun_akademik, callback) {
        var self = this;
        $.ajax({
            url: '/adm-akademik/plotting/plotting-kelas/json-kelas-by-pprodi-tahun',
            method: 'POST',
            data: {
                kd_prodi: kd_prodi,
                tahun_akademik: tahun_akademik
            },
            success: function (response) {
                if (response.success && response.data) {
                    var options = '<option value="">Pilih Kelas</option>';
                    $.each(response.data, function (index, item) {
                        options += '<option value="' + item.id_kelas + '">' + item.kd_kelas + ' - ' + item.nama_kelas + '</option>';
                    });
                    $("#id_kelas").html(options);
                    console.log('loadKelas success, options loaded');

                    // Panggil callback jika ada
                    if (typeof callback === 'function') {
                        callback();
                    }
                }
            },
            error: function () {
                $("#id_kelas").html('<option value="">Pilih Kelas</option>');
                console.log('loadKelas error');
                if (typeof callback === 'function') {
                    callback();
                }
            }
        });
    },

    validateForm: function () {
        var isValid = true;
        var errorMessages = [];

        // Validate required fields
        if (!$("#id_matakuliah").val()) {
            errorMessages.push("Mata Kuliah harus dipilih");
            isValid = false;
        }

        if (!$("#id_karyawan").val()) {
            errorMessages.push("Dosen Pengampu harus dipilih");
            isValid = false;
        }

        if (!$("#tahun_akademik").val()) {
            errorMessages.push("Tahun Akademik harus dipilih");
            isValid = false;
        }

        if (!$("#jenis_pengajaran").val()) {
            errorMessages.push("Jenis Pengajaran harus dipilih");
            isValid = false;
        }

        if (!$("#id_kelas").val()) {
            errorMessages.push("Kelas harus dipilih");
            isValid = false;
        }

        if (!isValid) {
            $.alert({
                title: "Validasi Gagal",
                type: "orange",
                content: errorMessages.join('<br>')
            });
        }

        return isValid;
    },

    resetForm: function () {
        var self = this;
        // Reset flag saat reset form
        self.data.isPopulatingData = false;
        self.data.editData = null;

        $("#id_plotting").val("");
        $("#kd_prodi").val("all").trigger('change');
        $("#tahun_akademik").val("all").trigger('change');
        $("#id_kurikulum").html('<option value="">Pilih Kurikulum</option>');
        $("#id_matakuliah").html('<option value="">Pilih Mata Kuliah</option>');
        $("#id_karyawan").val("").trigger('change');
        $("#jenis_pengajaran").val("").trigger('change');
        $("#id_kelas").html('<option value="">Pilih Kelas</option>');
        $("#cari-data").val("");
    }
};

jQuery(document).ready(function () {
    jQuery.plotting_perkuliahan.init();
});
