jQuery.nilai_mahasiswa = {
    data: {
        table: $("#table"),
        isSaving: false,
        unsavedChanges: new Set(),
        originalValues: new Map()
    },
    init: function () {
        var self = this;
        self.setEvents();
        self.initDataTable();
        self.addSaveButton();
        self.initOriginalValues();
        self.initAutoSaveIndicator();
        self.updateInputPlaceholders();
    },
    setEvents: function () {
        var self = this;

        // Event untuk input nilai dengan dukungan desimal
        $(document).on('input', 'input[name^="nilai["]', function () {
            var $input = $(this);
            var nilai = $input.val();
            var inputName = $input.attr('name');
            var originalValue = self.data.originalValues.get(inputName);

            // Allow decimal input dengan titik sebagai separator
            self.handleDecimalInput($input);

            // Track perubahan
            if (nilai !== originalValue) {
                self.trackUnsavedChange(inputName);
            } else {
                self.markAsSaved(inputName);
            }

            // Get criteria name for validation
            var kriteriaName = self.getKriteriaName($input);

            // Validasi real-time tanpa auto-save
            var validation = self.validateNilai(nilai, kriteriaName);
            if (!validation.valid && nilai !== '') {
                $input.addClass('border-danger').removeClass('border-success border-warning');
                self.showInputError($input, validation.message);
                return;
            } else {
                $input.removeClass('border-danger border-warning border-success');
                self.hideInputError($input);
            }
        });

        // Event untuk mencegah input karakter yang tidak valid
        $(document).on('keydown', 'input[name^="nilai["]', function (e) {
            self.handleKeyInput(e, $(this));
        });

        // KETIKA TOMBOL PUBLISH DIKLIK
        $(document).on('click', '#btn-publish-all', function () {
            var id_jadwal = $("#id_jadwal").val();

            // Konfirmasi sebelum publish
            $.confirm({
                title: '<i class="fa fa-question-circle text-blue"></i> Konfirmasi Publish',
                content: 'Apakah Anda yakin ingin mempublikasikan semua nilai mahasiswa? Pastikan semua nilai sudah benar sebelum dipublikasikan.',
                type: 'blue',
                theme: 'modern',
                draggable: false,
                backgroundDismiss: false,
                buttons: {
                    ya: {
                        text: '<i class="fa fa-check"></i> Ya, Publish',
                        btnClass: 'btn-primary',
                        action: function () {
                            // Langsung ke route publish tanpa perlu konfirmasi tambahan
                            $.ajax({
                                url: '/dosen/akademik/nilai-matakuliah/set-status/' + id_jadwal,
                                type: 'GET',
                                data: {
                                    status: true
                                },
                                success: function (response) {
                                    if (response.success) {
                                        self.showMessage('success', response.message || 'Semua nilai berhasil dipublikasikan', true);
                                        // Update status di UI
                                        setTimeout(function () {
                                            location.reload();
                                        }, 1500);
                                    } else {
                                        self.showMessage('error', response.message || 'Gagal mempublikasikan nilai');
                                    }
                                },
                                error: function (xhr, status, error) {
                                    var message = 'Terjadi kesalahan saat mempublikasikan nilai';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        message = xhr.responseJSON.message;
                                    }
                                    $.alert({
                                        title: '<i class="fa fa-times-circle text-red"></i> Gagal Publish',
                                        content: message,
                                        type: 'red',
                                        theme: 'modern',
                                        buttons: {
                                            ok: {
                                                text: 'OK',
                                                btnClass: 'btn-red'
                                            },
                                            refresh: {
                                                text: '<i class="fa fa-refresh"></i> Refresh',
                                                btnClass: 'btn-light',
                                                action: function () {
                                                    location.reload();
                                                }
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    },
                    batal: {
                        text: '<i class="fa fa-times"></i> Batal',
                        btnClass: 'btn-light',
                        action: function () {
                            // Tidak melakukan apa-apa, hanya menutup dialog
                        }
                    }
                }
            });
        });

        $(document).on('click', '#btn-unpublish-all', function () {
            var id_jadwal = $("#id_jadwal").val();

            // Konfirmasi sebelum publish
            $.confirm({
                title: '<i class="fa fa-question-circle text-blue"></i> Konfirmasi Unpublish',
                content: 'Apakah Anda yakin ingin membatalkan publikasi semua nilai mahasiswa?',
                type: 'blue',
                theme: 'modern',
                draggable: false,
                backgroundDismiss: false,
                buttons: {
                    ya: {
                        text: '<i class="fa fa-check"></i> Ya, Unpublish',
                        btnClass: 'btn-primary',
                        action: function () {
                            // Langsung ke route publish tanpa perlu konfirmasi tambahan
                            $.ajax({
                                url: '/dosen/akademik/nilai-matakuliah/set-status/' + id_jadwal,
                                type: 'GET',
                                data: {
                                    status: false
                                },
                                success: function (response) {
                                    if (response.success) {
                                        self.showMessage('success', response.message || 'Semua nilai berhasil di-unpublish', true);
                                        // Update status di UI
                                        setTimeout(function () {
                                            location.reload();
                                        }, 1500);
                                    } else {
                                        self.showMessage('error', response.message || 'Gagal meng-unpublish nilai');
                                    }
                                },
                                error: function (xhr, status, error) {
                                    var message = 'Terjadi kesalahan saat meng-unpublish nilai';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        message = xhr.responseJSON.message;
                                    }
                                    $.alert({
                                        title: '<i class="fa fa-times-circle text-red"></i> Gagal Unpublish',
                                        content: message,
                                        type: 'red',
                                        theme: 'modern',
                                        buttons: {
                                            ok: {
                                                text: 'OK',
                                                btnClass: 'btn-red'
                                            },
                                            refresh: {
                                                text: '<i class="fa fa-refresh"></i> Refresh',
                                                btnClass: 'btn-light',
                                                action: function () {
                                                    location.reload();
                                                }
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    },
                    batal: {
                        text: '<i class="fa fa-times"></i> Batal',
                        btnClass: 'btn-light',
                        action: function () {
                            // Tidak melakukan apa-apa, hanya menutup dialog
                        }
                    }
                }
            });
        });

        // Event untuk paste - validasi content yang di-paste
        $(document).on('paste', 'input[name^="nilai["]', function (e) {
            var $input = $(this);
            setTimeout(function () {
                self.handleDecimalInput($input);
                var kriteriaName = self.getKriteriaName($input);
                var validation = self.validateNilai($input.val(), kriteriaName);
                if (!validation.valid && $input.val() !== '') {
                    $input.addClass('border-danger');
                    self.showInputError($input, validation.message);
                } else {
                    $input.removeClass('border-danger border-warning border-success');
                    self.hideInputError($input);
                }
            }, 10);
        });

        // Event untuk simpan semua
        $(document).on('click', '#btn-save-all', function () {
            self.saveAllNilai();
        });

        // Event untuk reset semua nilai
        $(document).on('click', '#btn-reset-all', function () {
            self.resetAllNilai();
        });

        // Event untuk export nilai
        $(document).on('click', '#btn-export-nilai', function () {
            self.exportNilai();
        });

        // Event untuk refresh
        $(document).on('click', '#btn-refresh', function () {
            self.refreshPage();
        });

        // Keyboard shortcuts
        $(document).on('keydown', function (e) {
            // Ctrl+S untuk simpan semua
            if (e.ctrlKey && e.which === 83) {
                e.preventDefault();
                if (!self.data.isSaving) {
                    self.saveAllNilai();
                }
            }

            // Ctrl+R untuk reset (dengan konfirmasi)
            // if (e.ctrlKey && e.which === 82) {
            //     e.preventDefault();
            //     self.resetAllNilai();
            // }
        });

        // Warn user about unsaved changes before leaving page
        $(window).on('beforeunload', function (e) {
            if (self.data.unsavedChanges.size > 0) {
                var message = 'Anda memiliki ' + self.data.unsavedChanges.size + ' perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?';
                e.returnValue = message;
                return message;
            }
        });
    },

    // Handle decimal input validation and formatting
    handleDecimalInput: function ($input) {
        var value = $input.val();

        // Remove any characters that aren't numbers or decimal point
        var cleanValue = value.replace(/[^0-9.]/g, '');

        // Ensure only one decimal point
        var parts = cleanValue.split('.');
        if (parts.length > 2) {
            cleanValue = parts[0] + '.' + parts.slice(1).join('');
        }

        // Update input value if it was cleaned
        if (value !== cleanValue) {
            $input.val(cleanValue);
        }
    },

    // Handle keyboard input for decimal support
    handleKeyInput: function (e, $input) {
        var key = e.which || e.keyCode;
        var value = $input.val();

        // Allow: backspace, delete, tab, escape, enter
        if ($.inArray(key, [46, 8, 9, 27, 13]) !== -1 ||
            // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X, Ctrl+Z
            (key === 65 && e.ctrlKey === true) ||
            (key === 67 && e.ctrlKey === true) ||
            (key === 86 && e.ctrlKey === true) ||
            (key === 88 && e.ctrlKey === true) ||
            (key === 90 && e.ctrlKey === true) ||
            // Allow: home, end, left, right, down, up
            (key >= 35 && key <= 40)) {
            return;
        }

        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (key < 48 || key > 57)) && (key < 96 || key > 105)) {
            // Allow decimal point (period key 190, numpad period 110)
            if (key === 190 || key === 110) {
                // Only allow one decimal point
                if (value.indexOf('.') !== -1) {
                    e.preventDefault();
                }
                return;
            }
            e.preventDefault();
        }
    },

    // Show input error tooltip
    showInputError: function ($input, message) {
        // Remove existing error tooltip
        this.hideInputError($input);

        // Add error tooltip
        $input.attr('data-error', message)
            .attr('title', message)
            .tooltip('dispose')
            .tooltip({
                title: message,
                placement: 'top',
                trigger: 'manual'
            })
            .tooltip('show');
    },

    // Hide input error tooltip
    hideInputError: function ($input) {
        $input.removeAttr('data-error')
            .removeAttr('title')
            .tooltip('dispose');
    },

    initDataTable: function () {
        // Initialize DataTable jika diperlukan
        if (this.data.table.length > 0) {
            this.data.table.DataTable({
                "paging": false,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "dom": 'rt', // Hide default search box and other elements
                "language": {
                    "search": "Cari:",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "emptyTable": "Tidak ada data mahasiswa"
                }
            });
        }
    },

    addSaveButton: function () {
        const container = $('#action-buttons-container');

        if (container.length && $('#btn-save-all').length === 0) {

            let mainButtonsHTML = '';

            if (window.nilaiConfig) {

                // MASIH ADA NILAI KOSONG → hanya draft
                if (window.nilaiConfig.hasEmptyNilai) {

                    mainButtonsHTML = `
                    <button type="button" id="btn-save-all" class="btn btn-secondary btn-sm"
                        title="Simpan semua nilai (Ctrl+S)">
                        <i class="fas fa-file-alt mr-1"></i>Simpan Draft
                    </button>
                `;

                } else {

                    // SEMUA SUDAH ADA NILAI
                    if (window.nilaiConfig.allPublished) {

                        // SUDAH PUBLISH → hanya unpublish
                        mainButtonsHTML = `
                        <button type="button" id="btn-unpublish-all" class="btn btn-danger btn-sm"
                            title="Batalkan publikasi semua nilai">
                            <i class="fas fa-times mr-1"></i>Unpublish
                        </button>
                    `;

                    } else {

                        // BELUM PUBLISH → 2 tombol
                        mainButtonsHTML = `
                        <button type="button" id="btn-save-all" class="btn btn-success btn-sm"
                            title="Simpan sebagai draft">
                            <i class="fas fa-save mr-1"></i>Simpan
                        </button>

                        <button type="button" id="btn-publish-all" class="btn btn-primary btn-sm"
                            title="Publikasikan semua nilai">
                            <i class="fas fa-check mr-1"></i>Publish
                        </button>
                    `;
                    }
                }
            }

            var actionButtons = `
            <div class="d-flex justify-content-between align-items-center">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-right-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                    </div>
                    <input type="text" id="custom-search" class="form-control border-left-0"
                           placeholder="Cari mahasiswa..."
                           style="font-size: 13px;">
                </div>

                <div class="btn-group" role="group">
                    ${mainButtonsHTML}

                    <button type="button" id="btn-reset-all" class="btn btn-warning btn-sm">
                        <i class="fas fa-eraser mr-1"></i>Reset
                    </button>

                    <button type="button" id="btn-export-nilai" class="btn btn-info btn-sm">
                        <i class="fas fa-download mr-1"></i>Export
                    </button>

                    <button type="button" id="btn-refresh" class="btn btn-secondary btn-sm">
                        <i class="fas fa-sync-alt mr-1"></i>Refresh
                    </button>
                </div>
            </div>
        `;

            container.html(actionButtons);
            this.initCustomSearch();
        }
    },

    // Initialize custom search functionality
    initCustomSearch: function () {
        var self = this;

        // Connect custom search input to DataTable
        $('#custom-search').on('keyup', function () {
            if (self.data.table.length > 0) {
                var table = self.data.table.DataTable();
                table.search(this.value).draw();
            }
        });

        // Clear search on Escape key
        $('#custom-search').on('keydown', function (e) {
            if (e.which === 27) { // Escape key
                $(this).val('');
                if (self.data.table.length > 0) {
                    var table = self.data.table.DataTable();
                    table.search('').draw();
                }
            }
        });

        // Add search counter
        if (self.data.table.length > 0) {
            var table = self.data.table.DataTable();

            table.on('draw', function () {
                var info = table.page.info();
                var searchValue = $('#custom-search').val();

                if (searchValue && searchValue.length > 0) {
                    var searchInfo = info.recordsDisplay + ' dari ' + info.recordsTotal + ' mahasiswa';
                    $('#custom-search').attr('title', 'Menampilkan ' + searchInfo);
                } else {
                    $('#custom-search').attr('title', 'Cari berdasarkan NIM atau nama mahasiswa');
                }
            });
        }
    },

    // Simpan semua nilai sekaligus
    saveAllNilai: function () {
        var self = this;

        if (self.data.isSaving) return;

        var allData = {};
        var hasData = false;
        var invalidInputs = [];

        // DEBUG: Cek semua input
        console.log('=== DEBUG SEMUA INPUT ===');
        $('input[name^="nilai["]').each(function (index) {
            var $input = $(this);
            var name = $input.attr('name');
            var nilai = $input.val();
            var nim = $input.data('nim');
            var kriteriaId = $input.data('kriteria');

            console.log(`Input ${index}:`, {
                name: name,
                nilai: nilai,
                'data-nim': nim,
                'data-kriteria': kriteriaId,
                'attr nim': $input.attr('data-nim'),
                'attr kriteria': $input.attr('data-kriteria')
            });
        });
        console.log('=== END DEBUG ===');

        // Kumpulkan semua nilai dan validasi
        $('input[name^="nilai["]').each(function () {
            var $input = $(this);
            var name = $input.attr('name');
            var nilai = $input.val();

            if (nilai !== '') {
                // Get criteria name for validation
                var kriteriaName = self.getKriteriaNameFromInput($input);

                // Validasi nilai
                var validation = self.validateNilai(nilai, kriteriaName);
                if (!validation.valid) {
                    invalidInputs.push({
                        input: $input,
                        message: validation.message
                    });
                    return;
                }

                // PERBAIKAN: Gunakan data attribute (lebih reliable)
                var nim = $input.data('nim');
                var kriteriaId = $input.data('kriteria');

                console.log('Setelah validasi - NIM:', nim, 'KriteriaId:', kriteriaId, 'Nilai:', validation.value);

                // Fallback ke regex jika data attribute tidak ada
                if (!nim || !kriteriaId) {
                    var matches = name.match(/nilai\[([^\]]+)\]\[([^\]]+)\]/);
                    console.log('Regex match:', matches);
                    if (matches) {
                        nim = matches[1];
                        kriteriaId = matches[2];
                        console.log('Dari regex - NIM:', nim, 'KriteriaId:', kriteriaId);
                    }
                }

                console.log('Final - NIM:', nim, 'KriteriaId:', kriteriaId, 'HasData akan true?:', (nim && kriteriaId));

                if (nim && kriteriaId) {
                    if (!allData[nim]) {
                        allData[nim] = {};
                    }
                    allData[nim][kriteriaId] = validation.value;
                    hasData = true;
                }
            }
        });

        console.log('hasData:', hasData);
        console.log('allData:', allData);

        // Jika ada input yang tidak valid
        if (invalidInputs.length > 0) {
            var errorMessage = 'Terdapat ' + invalidInputs.length + ' nilai yang tidak valid:<br>';
            invalidInputs.slice(0, 5).forEach(function (item) {
                item.input.addClass('border-danger');
                errorMessage += '• ' + item.message + '<br>';
            });

            if (invalidInputs.length > 5) {
                errorMessage += '• ... dan ' + (invalidInputs.length - 5) + ' lainnya';
            }

            self.showMessage('error', errorMessage);

            // Fokus ke input pertama yang error
            invalidInputs[0].input.focus();

            // Hapus border error setelah 5 detik
            setTimeout(function () {
                invalidInputs.forEach(function (item) {
                    item.input.removeClass('border-danger');
                });
            }, 5000);

            return;
        }

        if (!hasData) {
            self.showMessage('warning', 'Tidak ada nilai yang akan disimpan');
            return;
        }


        // Konfirmasi
        $.confirm({
            title: '<i class="fa fa-question-circle text-blue"></i> Konfirmasi Simpan',
            content: 'Apakah Anda yakin ingin menyimpan semua nilai mahasiswa?<br><small class="text-muted">Total nilai yang akan disimpan: <strong>' + Object.keys(allData).length + ' mahasiswa</strong></small>',
            type: 'blue',
            theme: 'bootstrap',
            closeIcon: true,
            draggable: false,
            backgroundDismiss: false,
            buttons: {
                ya: {
                    text: '<i class="fa fa-save"></i> Ya, Simpan',
                    btnClass: 'btn-blue',
                    action: function () {
                        self.processSaveAll(allData);
                    }
                },
                batal: {
                    text: '<i class="fa fa-times"></i> Batal',
                    btnClass: 'btn-light',
                    action: function () {
                        // Tidak melakukan apa-apa, hanya menutup dialog
                    }
                }
            }
        });
    },

    processSaveAll: function (allData) {
        var self = this;
        self.data.isSaving = true;

        var data = {
            nilai_data: allData,
            save_all: true,
            _token: $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()
        };

        // DEBUG: Log data yang akan dikirim
        console.log('Data yang dikirim ke server:', JSON.stringify(data, null, 2));
        console.log('allData structure:', allData);

        // Loading dialog
        var loadingDialog = $.dialog({
            title: '<i class="fa fa-spinner fa-spin text-blue"></i> Menyimpan Data',
            content: 'Sedang menyimpan semua nilai mahasiswa, mohon tunggu...',
            type: 'blue',
            theme: 'bootstrap',
            closeIcon: false,
            backgroundDismiss: false,
            escapeKey: false,
            buttons: false
        });

        // Visual feedback pada tombol
        $('#btn-save-all').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...');

        $.ajax({
            url: '/dosen/akademik/nilai-matakuliah/store',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (response) {
                loadingDialog.close();

                if (response.success) {
                    // Clear all unsaved changes
                    self.data.unsavedChanges.clear();
                    self.updateUnsavedCounter();
                    self.initOriginalValues();

                    // Update tampilan jika ada data response
                    if (response.data && response.data.updated_students) {
                        response.data.updated_students.forEach(function (student) {
                            var $row = $('input[name="nilai[' + student.nim + '][' + Object.keys(allData[student.nim])[0] + ']"]').closest('tr');

                            if (student.nilai_akhir !== undefined) {
                                var colorClass = student.nilai_mutu < 2.0 ? 'text-danger' : 'text-success';
                                var icon = student.nilai_mutu < 2.0 ? 'fa-arrow-down' : 'fa-arrow-up';
                                $row.find('.nilai-akhir').html(
                                    '<span class="' + colorClass + ' font-weight-bold">' +
                                    '<i class="fas ' + icon + ' mr-1"></i>' + student.nilai_akhir + '</span>'
                                );
                            }

                            if (student.nilai_mutu !== undefined) {
                                var badgeClass = student.nilai_mutu < 2.0 ? 'badge-danger' : 'badge-success';
                                var icon = student.nilai_mutu < 2.0 ? 'fa-times' : 'fa-check';
                                $row.find('.nilai-mutu').html(
                                    '<span class="badge ' + badgeClass + ' badge-lg">' +
                                    '<i class="fas ' + icon + ' mr-1"></i>' + student.nilai_mutu + '</span>'
                                );
                            }

                            if (student.nilai_huruf !== undefined) {
                                var badgeClass = student.nilai_mutu < 2.0 ? 'badge-danger' : 'badge-success';
                                $row.find('.nilai-huruf').html(
                                    '<span class="badge ' + badgeClass + ' badge-lg">' + student.nilai_huruf + '</span>'
                                );
                            }
                        });
                    }

                    // Update last save time
                    if (typeof window.updateLastSave === 'function') {
                        window.updateLastSave();
                    }

                    // Update summary count
                    if (typeof window.updateSummaryCount === 'function') {
                        setTimeout(window.updateSummaryCount, 500);
                    }

                    // Show success message dengan auto reload
                    self.showMessage('success', response.message || 'Semua nilai berhasil disimpan', true);

                } else {
                    self.showMessage('error', response.message || 'Gagal menyimpan nilai');
                }
            },
            error: function (xhr, status, error) {
                loadingDialog.close();

                var message = 'Terjadi kesalahan saat menyimpan nilai';
                var detailError = '';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    // Validation errors
                    var errors = [];
                    Object.keys(xhr.responseJSON.errors).forEach(function (key) {
                        errors.push(xhr.responseJSON.errors[key].join(', '));
                    });
                    detailError = '<br><small class="text-muted">Detail: ' + errors.join('; ') + '</small>';
                } else if (xhr.status === 500) {
                    detailError = '<br><small class="text-muted">Terjadi kesalahan server internal</small>';
                } else if (xhr.status === 419) {
                    message = 'Sesi telah berakhir, silakan refresh halaman';
                    detailError = '<br><small class="text-muted">CSRF Token tidak valid</small>';
                }

                $.alert({
                    title: '<i class="fa fa-times-circle text-red"></i> Gagal Menyimpan',
                    content: message + detailError,
                    type: 'red',
                    theme: 'bootstrap',
                    buttons: {
                        ok: {
                            text: 'OK',
                            btnClass: 'btn-red'
                        },
                        refresh: {
                            text: '<i class="fa fa-refresh"></i> Refresh',
                            btnClass: 'btn-light',
                            action: function () {
                                if (xhr.status === 419) {
                                    location.reload();
                                }
                            }
                        }
                    }
                });
            },
            complete: function () {
                self.data.isSaving = false;
                $('#btn-save-all').prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Simpan Semua Nilai');
            }
        });
    },

    // Reset semua nilai
    resetAllNilai: function () {
        var self = this;

        $.confirm({
            title: '<i class="fa fa-exclamation-triangle text-orange"></i> Konfirmasi Reset',
            content: 'Apakah Anda yakin ingin menghapus semua nilai yang telah diinput?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan!</small>',
            type: 'orange',
            theme: 'bootstrap',
            closeIcon: true,
            draggable: false,
            backgroundDismiss: false,
            buttons: {
                ya: {
                    text: '<i class="fa fa-eraser"></i> Ya, Reset',
                    btnClass: 'btn-warning',
                    action: function () {
                        $('input[name^="nilai["]').val('').removeClass('border-success border-danger border-warning');

                        // Clear error tooltips
                        $('input[name^="nilai["]').each(function () {
                            self.hideInputError($(this));
                        });

                        // Reset tracking system
                        self.data.unsavedChanges.clear();
                        self.updateUnsavedCounter();
                        self.initOriginalValues();

                        // Update summary
                        if (typeof window.updateSummaryCount === 'function') {
                            setTimeout(window.updateSummaryCount, 100);
                        }

                        self.showToast('success', 'Semua nilai berhasil direset');
                    }
                },
                batal: {
                    text: '<i class="fa fa-times"></i> Batal',
                    btnClass: 'btn-light'
                }
            }
        });
    },

    // Export nilai ke Excel
    exportNilai: function () {
        var self = this;

        // Tampilkan modal export jika ada
        if ($('#exportModal').length > 0) {
            $('#exportModal').modal('show');

            // Handle confirm export di modal
            $('#btn-confirm-export').off('click').on('click', function () {
                var format = $('input[name="export-format"]:checked').val();
                $('#exportModal').modal('hide');
                if (format === 'csv' || format === 'excel') {
                    self.processExport(format);
                } else {
                    window.open('/dosen/akademik/nilai-matakuliah-export/' + $("#id_jadwal").val());
                }
            });
            return;
        }

        // Fallback jika tidak ada modal
        self.processExport('csv');
    },

    // Proses export
    processExport: function (format) {
        var self = this;

        // Kumpulkan data untuk export
        var exportData = [];
        var headers = ['No', 'NIM', 'Nama'];

        // Ambil header kriteria
        var kriteriaHeaders = [];
        $('.table thead th').each(function (index) {
            if (index > 2 && index < $('.table thead th').length - 3) {
                kriteriaHeaders.push($(this).text().trim().replace(/\n/g, ' '));
                headers.push($(this).text().trim().replace(/\n/g, ' '));
            }
        });
        headers.push('Nilai Akhir', 'Nilai Mutu', 'Nilai Huruf');

        // Kumpulkan data mahasiswa
        $('.table tbody tr').each(function () {
            var row = [];
            var $tr = $(this);

            // Skip jika row kosong atau bukan data mahasiswa
            if ($tr.find('td').length < 3) return;

            // No, NIM, Nama
            row.push($tr.find('td:eq(0)').text().trim());
            row.push($tr.find('td:eq(1)').text().trim());
            row.push($tr.find('td:eq(2)').text().trim().replace(/\n/g, ' '));

            // Nilai per kriteria
            $tr.find('input[name^="nilai["]').each(function () {
                var nilai = $(this).val() || '0';
                // Ensure decimal values are properly formatted for export
                if (nilai && nilai !== '0' && !isNaN(parseFloat(nilai))) {
                    nilai = parseFloat(nilai).toString();
                }
                row.push(nilai);
            });

            // Nilai Akhir, Mutu, Huruf
            row.push($tr.find('.nilai-akhir').text().trim().replace(/\n/g, ' '));
            row.push($tr.find('.nilai-mutu').text().trim().replace(/\n/g, ' '));
            row.push($tr.find('.nilai-huruf').text().trim().replace(/\n/g, ' '));

            if (row.length > 3) { // Pastikan ada data
                exportData.push(row);
            }
        });

        if (exportData.length === 0) {
            self.showMessage('warning', 'Tidak ada data untuk diekspor');
            return;
        }

        if (format === 'excel') {
            self.exportToExcel(headers, exportData);
        } else {
            self.exportToCSV(headers, exportData);
        }
    },

    // Export ke CSV
    exportToCSV: function (headers, data) {
        var self = this;

        var csvContent = "data:text/csv;charset=utf-8,";
        csvContent += headers.join(",") + "\n";

        data.forEach(function (rowArray) {
            var row = rowArray.map(function (field, index) {
                var cleanField = field.toString().replace(/"/g, '""');

                // Format NIM column (index 1) dengan prefix untuk Excel
                if (index === 1) {
                    return '="' + cleanField + '"'; // Force Excel to treat as text
                }

                return '"' + cleanField + '"';
            }).join(",");
            csvContent += row + "\n";
        });

        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "nilai_mahasiswa_" + new Date().toISOString().split('T')[0] + ".csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        self.showToast('success', 'File CSV berhasil didownload');
    },

    // Export ke Excel (menggunakan SheetJS jika tersedia)
    exportToExcel: function (headers, data) {
        var self = this;

        // Check if XLSX is available
        if (typeof XLSX === 'undefined') {
            console.error('XLSX library not loaded');
            self.showToast('error', 'Library Excel tidak tersedia, menggunakan CSV');
            self.exportToCSV(headers, data);
            return;
        }

        console.log('Creating Excel file...');

        try {
            var wb = XLSX.utils.book_new();

            // Prepare data with proper formatting
            var wsData = [headers];

            // Process each row to ensure proper formatting
            data.forEach(function (row) {
                var processedRow = row.map(function (cell, index) {
                    // Format NIM column (index 1) as text to prevent scientific notation
                    if (index === 1) {
                        return String(cell); // Ensure it's a string
                    }
                    // Handle decimal values properly
                    if (index > 2 && index < row.length - 3) {
                        var numVal = parseFloat(cell);
                        if (!isNaN(numVal)) {
                            return numVal;
                        }
                    }
                    return cell;
                });
                wsData.push(processedRow);
            });

            var ws = XLSX.utils.aoa_to_sheet(wsData);

            // Set column widths
            ws['!cols'] = [
                { wch: 5 },   // No
                { wch: 15 },  // NIM
                { wch: 25 },  // Nama
                ...Array(headers.length - 6).fill({ wch: 10 }), // Kriteria columns
                { wch: 12 },  // Nilai Akhir
                { wch: 12 },  // Nilai Mutu
                { wch: 8 }    // Huruf
            ];

            // Style header row
            var range = XLSX.utils.decode_range(ws['!ref']);
            for (var C = range.s.c; C <= range.e.c; ++C) {
                var address = XLSX.utils.encode_cell({ r: 0, c: C });
                if (!ws[address]) continue;
                ws[address].s = {
                    font: { bold: true, color: { rgb: "FFFFFF" } },
                    fill: { fgColor: { rgb: "4472C4" } },
                    alignment: { horizontal: "center", vertical: "center" }
                };
            }

            // Format NIM column as text
            for (var R = 1; R <= range.e.r; ++R) {
                var nimAddress = XLSX.utils.encode_cell({ r: R, c: 1 });
                if (ws[nimAddress]) {
                    ws[nimAddress].t = 's'; // Set as string type
                    ws[nimAddress].z = '@'; // Text format
                }
            }

            XLSX.utils.book_append_sheet(wb, ws, "Nilai Mahasiswa");

            // Generate filename
            var fileName = "nilai_mahasiswa_" + new Date().toISOString().split('T')[0] + ".xlsx";

            console.log('Writing Excel file:', fileName);

            // Write file with explicit Excel format
            XLSX.writeFile(wb, fileName, {
                bookType: 'xlsx',
                type: 'array'
            });

            console.log('Excel file created successfully');
            self.showToast('success', 'File Excel berhasil didownload');

        } catch (error) {
            console.error('Error creating Excel file:', error);
            self.showToast('error', 'Gagal membuat file Excel: ' + error.message);
            self.exportToCSV(headers, data);
        }
    },

    // Refresh halaman
    refreshPage: function () {
        var self = this;

        $.confirm({
            title: '<i class="fa fa-sync-alt text-blue"></i> Refresh Halaman',
            content: 'Apakah Anda yakin ingin me-refresh halaman?<br><small class="text-warning">Pastikan semua perubahan telah disimpan!</small>',
            type: 'blue',
            theme: 'bootstrap',
            closeIcon: true,
            backgroundDismiss: false,
            buttons: {
                ya: {
                    text: '<i class="fa fa-sync-alt"></i> Ya, Refresh',
                    btnClass: 'btn-primary',
                    action: function () {
                        self.showReloadAnimation();
                        location.reload();
                    }
                },
                batal: {
                    text: '<i class="fa fa-times"></i> Batal',
                    btnClass: 'btn-light'
                }
            }
        });
    },

    // Enhanced validation with decimal support
    validateNilai: function (nilai, kriteriaName) {
        if (nilai === '' || nilai === null || nilai === undefined) {
            return { valid: true, value: '' }; // Kosong diizinkan
        }

        // Trim whitespace
        nilai = nilai.toString().trim();

        // Check for invalid decimal format
        if (nilai.indexOf('.') !== -1) {
            var parts = nilai.split('.');
            if (parts.length > 2) {
                return { valid: false, message: 'Format desimal tidak valid (terlalu banyak titik)' };
            }
            if (parts[1] && parts[1].length > 2) {
                return { valid: false, message: 'Maksimal 2 digit setelah koma' };
            }
        }

        var numValue = parseFloat(nilai);

        if (isNaN(numValue)) {
            return { valid: false, message: 'Nilai harus berupa angka (gunakan titik untuk desimal)' };
        }

        if (numValue < 0) {
            return { valid: false, message: 'Nilai tidak boleh kurang dari 0' };
        }

        // Check if criteria is "Kehadiran" for different max range
        var maxValue = 100; // default
        var maxValueText = "100";

        if (kriteriaName && kriteriaName.toLowerCase().includes('kehadiran')) {
            maxValue = 16;
            maxValueText = "16";
        }

        if (numValue > maxValue) {
            return {
                valid: false,
                message: 'Nilai tidak boleh lebih dari ' + maxValueText + (kriteriaName && kriteriaName.toLowerCase().includes('kehadiran') ? ' (kehadiran)' : '')
            };
        }

        // Round to 2 decimal places
        var roundedValue = Math.round(numValue * 100) / 100;

        return { valid: true, value: roundedValue };
    },

    // Get criteria name from input element
    getKriteriaName: function ($input) {
        return this.getKriteriaNameFromInput($input);
    },

    // Helper function to get criteria name from input element
    getKriteriaNameFromInput: function ($input) {
        try {
            // Get column index of this input
            var $td = $input.closest('td');
            var columnIndex = $td.index();

            // Get criteria name from table header
            var $headerCell = $input.closest('table').find('thead th').eq(columnIndex);
            var kriteriaText = $headerCell.text().trim();

            // Extract criteria name (remove percentage info)
            var kriteriaName = kriteriaText.replace(/\d+%/g, '').replace(/\n/g, ' ').trim();

            return kriteriaName;
        } catch (e) {
            return '';
        }
    },

    // Update input placeholders and titles based on criteria with decimal support
    updateInputPlaceholders: function () {
        var self = this;

        $('input[name^="nilai["]').each(function () {
            var $input = $(this);
            var kriteriaName = self.getKriteriaNameFromInput($input);

            // Set input attributes for decimal support
            $input.attr('type', 'text')
                .attr('inputmode', 'decimal')
                .attr('pattern', '[0-9]*[.]?[0-9]*')
                .attr('step', '0.01');

            if (kriteriaName && kriteriaName.toLowerCase().includes('kehadiran')) {
                $input.attr('placeholder', '0-16')
                    .attr('max', '16')
                    .attr('title', 'Masukkan nilai kehadiran 0-16 (gunakan titik untuk desimal, contoh: 15.5)');
            } else {
                $input.attr('placeholder', '0-100')
                    .attr('max', '100')
                    .attr('title', 'Masukkan nilai 0-100 (gunakan titik untuk desimal, contoh: 85.75)');
            }
        });
    },

    // Initialize original values untuk tracking changes
    initOriginalValues: function () {
        var self = this;
        $('input[name^="nilai["]').each(function () {
            var $input = $(this);
            var key = $input.attr('name');
            self.data.originalValues.set(key, $input.val());
        });
    },

    // Initialize auto-save indicator
    initAutoSaveIndicator: function () {
        var self = this;

        // Add auto-save status to page
        if ($('#auto-save-status').length === 0) {
            var statusHtml = `
                <div id="auto-save-status" class="position-fixed" style="bottom: 20px; right: 20px; z-index: 1050;">
                    <div class="card border-0 shadow-sm" style="display: none;">
                        <div class="card-body p-2">
                            <small class="text-muted">
                                <i class="fas fa-save mr-1"></i>
                                <span id="auto-save-text">Manual save mode</span>
                            </small>
                        </div>
                    </div>
                </div>
            `;
            $('body').append(statusHtml);
        }
    },

    // Track unsaved changes
    trackUnsavedChange: function (inputName) {
        var self = this;
        self.data.unsavedChanges.add(inputName);
        self.updateUnsavedCounter();
    },

    // Remove from unsaved changes
    markAsSaved: function (inputName) {
        var self = this;
        self.data.unsavedChanges.delete(inputName);
        self.updateUnsavedCounter();
    },

    // Update unsaved counter di UI
    updateUnsavedCounter: function () {
        var count = this.data.unsavedChanges.size;
        var $counter = $('#unsaved-count');

        if (count > 0) {
            $counter.find('span').text(count);
            $counter.show();
        } else {
            $counter.hide();
        }
    },

    // Updated showMessage function with auto reload capability and loading animation
    showMessage: function (type, message, autoReload) {
        var self = this;
        var config = {
            'success': {
                title: 'Berhasil',
                type: 'green',
                icon: 'fa fa-check-circle'
            },
            'error': {
                title: 'Error',
                type: 'red',
                icon: 'fa fa-times-circle'
            },
            'warning': {
                title: 'Peringatan',
                type: 'orange',
                icon: 'fa fa-exclamation-triangle'
            },
            'info': {
                title: 'Informasi',
                type: 'blue',
                icon: 'fa fa-info-circle'
            }
        };

        var setting = config[type] || config['info'];

        $.alert({
            title: setting.title,
            content: message,
            type: setting.type,
            icon: setting.icon,
            theme: 'bootstrap',
            closeIcon: true,
            // Removed autoClose to eliminate countdown timer
            buttons: {
                ok: {
                    text: 'OK',
                    btnClass: 'btn-' + setting.type.replace('orange', 'warning'),
                    action: function () {
                        if (autoReload) {
                            self.showReloadAnimation();
                            location.reload();
                        }
                    }
                }
            }
        });
    },

    // Show reload animation overlay
    showReloadAnimation: function () {
        // Remove existing overlay if any
        $('#reload-overlay').remove();

        var overlayHtml = `
            <div id="reload-overlay" class="position-fixed w-100 h-100" style="top: 0; left: 0; z-index: 9999; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(3px);">
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="text-center">
                        <!-- Spinner Animation -->
                        <div class="reload-spinner mb-4">
                            <div class="spinner-border text-success" role="status" style="width: 4rem; height: 4rem; border-width: 4px;">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <div class="reload-pulse"></div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress mb-3" style="width: 300px; height: 8px; border-radius: 10px;">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                 role="progressbar" style="width: 0%; transition: width 2.5s ease-in-out;">
                            </div>
                        </div>

                        <!-- Loading Text -->
                        <h5 class="text-success font-weight-bold mb-2">
                            <i class="fas fa-sync-alt fa-spin mr-2"></i>
                            Memuat Ulang Halaman
                        </h5>
                        <p class="text-muted mb-0">
                            Mengambil data terbaru dari server...
                        </p>
                    </div>
                </div>
            </div>

            <style>
                .reload-spinner {
                    position: relative;
                    display: inline-block;
                }

                .reload-pulse {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    width: 6rem;
                    height: 6rem;
                    border: 2px solid #28a745;
                    border-radius: 50%;
                    opacity: 0;
                    animation: pulse-reload 2s infinite;
                }

                @keyframes pulse-reload {
                    0% {
                        transform: translate(-50%, -50%) scale(0.8);
                        opacity: 1;
                    }
                    100% {
                        transform: translate(-50%, -50%) scale(1.5);
                        opacity: 0;
                    }
                }

                .reload-spinner .spinner-border {
                    animation-duration: 0.8s;
                }

                #reload-overlay {
                    animation: fadeInOverlay 0.3s ease-in-out;
                }

                @keyframes fadeInOverlay {
                    from {
                        opacity: 0;
                        transform: scale(0.95);
                    }
                    to {
                        opacity: 1;
                        transform: scale(1);
                    }
                }

                .progress {
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    background-color: #e9ecef;
                }

                .progress-bar {
                    background: linear-gradient(90deg, #28a745, #20c997, #28a745);
                    background-size: 200% 100%;
                    animation: shimmer 1.5s infinite;
                }

                @keyframes shimmer {
                    0% { background-position: -200% 0; }
                    100% { background-position: 200% 0; }
                }
            </style>
        `;

        $('body').append(overlayHtml);

        // Animate progress bar
        setTimeout(function () {
            $('#reload-overlay .progress-bar').css('width', '100%');
        }, 100);
    },

    // Toast notification untuk feedback ringan
    showToast: function (type, message) {
        var config = {
            'success': {
                title: 'Berhasil',
                type: 'green',
                icon: 'fa fa-check'
            },
            'error': {
                title: 'Error',
                type: 'red',
                icon: 'fa fa-times'
            },
            'warning': {
                title: 'Peringatan',
                type: 'orange',
                icon: 'fa fa-exclamation'
            }
        };

        var setting = config[type] || config['success'];

        // Hapus toast sebelumnya jika ada
        if (window.currentToast) {
            window.currentToast.close();
        }

        window.currentToast = $.dialog({
            title: setting.title,
            content: message,
            type: setting.type,
            icon: setting.icon,
            theme: 'bootstrap',
            closeIcon: false,
            backgroundDismiss: true,
            escapeKey: true,
            animation: 'scale',
            animationBounce: 1.2,
            animationSpeed: 300,
            columnClass: 'col-md-4 col-md-offset-8 col-sm-6 col-sm-offset-6 col-xs-10 col-xs-offset-1',
            containerFluid: true,
            buttons: false,
            autoClose: type === 'success' ? 'close|2000' : 'close|3000'
        });
    }
};

// Initialize when document ready
jQuery(document).ready(function () {
    jQuery.nilai_mahasiswa.init();
});
