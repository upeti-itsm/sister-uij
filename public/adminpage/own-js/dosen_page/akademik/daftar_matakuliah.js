/**
 * Daftar Mata Kuliah dengan Fitur Kriteria Penilaian
 * File: adminpage/own-js/dosen_page/akademik/daftar_matakuliah.js
 *
 * Fitur yang tersedia:
 * - CRUD Mata Kuliah (existing)
 * - Tambah Kriteria Penilaian (baru)
 * - Hapus Kriteria Penilaian (baru)
 * - Validasi Total Bobot 100% (baru)
 *
 * ❌ TIDAK ADA: Edit Kriteria, Detail Modal
 *
 * Dependencies:
 * - jQuery
 * - DataTables
 * - Select2
 * - jQuery.Confirm (untuk alert/confirm/dialog)
 *
 * jQuery.Confirm Config:
 * - CSS: adminpage/assets/plugins/jquery-confirm/jquery-confirm.min.css
 * - JS: adminpage/assets/plugins/jquery-confirm/jquery-confirm.min.js
 */

jQuery.daftar_matakuliah = {
    data: {
        table: $("#table"),
    },
    init: function () {
        var self = this;
        self.setEvents();
        self.setKriteriaEvents();
    },
    setEvents: function () {
        var self = this;
        // Option Data
        $(".select2").select2();

        // Handle perubahan dropdown kriteria
        $('#id_kriteria').change(function() {
            self.handleKriteriaChange();
        });
        // Initialize Select2 untuk dropdown kriteria
        $('#id_kriteria').select2({
            dropdownParent: $('#modalKriteria'),
            theme: 'bootstrap4',
            width: '100%'
        });

        // Table With DataTable
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/dosen/akademik/daftar-matakuliah/json',
                type: 'post',
                data: function (data) {
                    data.tahun = $("#tahun_akademik").val();
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'nomor',
                    searchable: false,
                    sClass: 'text-center',
                    width: "5%",
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return "<p>" + data.nama_matakuliah + " (" + data.nama_kelas + ")</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        return "<p>" + data.nama_prodi + "</p>";
                    }
                },
                {
                    // Kolom Aksi - Hanya Kelola dan Export
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "30%",
                    render: function (data) {
                        var buttons = '';

                        // Tombol Nilai Mahasiswa
                        buttons += "<a href='/dosen/akademik/nilai-matakuliah/"+data.id_jadwal+"' class='btn btn-success-soft btn-sm mr-1 mb-1' title='Nilai Mahasiswa'>" +
                            "<i class='fas fa-clipboard-check'></i>" +
                            "</a>";
                        // Tombol Kelola Kriteria
                        buttons += "<button type='button' class='btn btn-primary-soft btn-sm mr-1 mb-1' " +
                            "onclick=\"jQuery.daftar_matakuliah.kelolaKriteria('" + data.id_jadwal + "', '" +
                            data.nama_matakuliah.replace(/'/g, "\\'") + "', '" + data.nama_prodi.replace(/'/g, "\\'") + "')\" " +
                            "title='Kelola Kriteria Penilaian'>" +
                            "<i class='fas fa-cogs'></i></button>";

                        // Tombol Export PDF (yang sudah ada)
                        buttons += "<a href='/dosen/akademik/daftar-matakuliah/export-presensi/" + data.id_jadwal + "' " +
                            "title='Export Presensi Mahasiswa' class='btn btn-danger-soft btn-sm btn-print mb-1' " +
                            "data-id='" + data.id_jadwal + "' target='_blank'>" +
                            "<i class='fas fa-file-pdf'></i></a>";

                        return buttons;
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
                "emptyTable": "Tidak ditemukan data"
            }
        });

        // Event handlers yang sudah ada
        $("#btn-filter").click(function () {
            self.data.table.ajax.reload();
        });
        $("#tahun_akademik").change(function () {
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
        $("#btn-export-pdf").click(function () {
            window.open('/dosen/akademik/daftar-matakuliah/export-pdf/' + $("#tahun_akademik").val() + '/' + $("#cari-data").val());
        });
    },

    // ===== FITUR KRITERIA PENILAIAN (TANPA EDIT & DETAIL) =====
    handleKriteriaChange: function() {
        var selectedValue = $('#id_kriteria').val();
        var selectedText = $('#id_kriteria option:selected').text();

        if (selectedValue === '0') {
            $('#nama_kriteria').prop('readonly', false).val('').focus();
        } else {
            $('#nama_kriteria').prop('readonly', true).val(selectedText);
        }
    },
    // Events untuk fitur kriteria penilaian
    setKriteriaEvents: function() {
        var self = this;

        // Form submit handler untuk kriteria (hanya tambah)
        $('#formKriteria').submit(function(e) {
            e.preventDefault();
            self.simpanKriteria();
        });

        // Reset form ketika modal ditutup
        $('#modalKriteria').on('hidden.bs.modal', function () {
            $('#formKriteria')[0].reset();
            $('#btnSimpanKriteria').html('<i class="fas fa-plus mr-2"></i>Tambah Kriteria');
            $('#btnSimpanKriteria').prop('disabled', false);
        }).on('shown.bs.modal', function () {
            $("#id_kriteria").change();
        });
    },

    // Fungsi untuk membuka modal kelola kriteria (dengan loading)
    kelolaKriteria: function(matkulId, matkulName, prodi) {
        var self = this;

        // Show loading dialog sebelum modal dibuka
        var loadingDialog = $.dialog({
            title: '<i class="fas fa-spinner fa-spin mr-2"></i>Loading',
            content: 'Memuat data kriteria penilaian...',
            type: 'blue',
            theme: 'modern',
            columnClass: 'col-md-4 col-md-offset-4',
            closeIcon: false,
            buttons: {}
        });

        // Set data mata kuliah
        $('#matkul_id').val(matkulId);
        $('#matkul-name').text(matkulName);
        $('#matkul-prodi').text(prodi);

        // Reset form (hanya mode tambah)
        $('#formKriteria')[0].reset();
        $('#matkul_id').val(matkulId); // Set ulang karena reset form
        $('#btnSimpanKriteria').html('<i class="fas fa-plus mr-2"></i>Tambah Kriteria');

        // Load kriteria yang sudah ada dengan callback
        self.loadKriteriaList(matkulId, function(success, message) {
            // Close loading dialog
            loadingDialog.close();

            if (success) {
                // Buka modal hanya jika data berhasil dimuat
                $('#modalKriteria').modal('show');
            } else {
                // Tampilkan error jika gagal load
                self.showMessage('error', message || 'Gagal memuat data kriteria penilaian');
            }
        });
    },

    // Fungsi untuk load daftar kriteria (dengan callback support)
    loadKriteriaList: function(matkulId, callback) {
        var self = this;
        $.ajax({
            url: '/dosen/akademik/daftar-matakuliah/json-kriteria',
            type: 'post',
            data: { id_jadwal: matkulId },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    self.updateKriteriaList(response.data);
                    self.updateTotalWeight(response.data);

                    // Call callback with success
                    if (typeof callback === 'function') {
                        callback(true, 'Data kriteria berhasil dimuat');
                    }
                } else {
                    var errorMessage = response.message || 'Gagal memuat daftar kriteria';

                    // Call callback with error
                    if (typeof callback === 'function') {
                        callback(false, errorMessage);
                    } else {
                        // Fallback jika tidak ada callback (untuk backward compatibility)
                        self.showMessage('error', errorMessage);
                    }
                }
            },
            error: function(xhr) {
                console.error('Error loading kriteria list:', xhr);
                var message = 'Terjadi kesalahan saat memuat daftar kriteria';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                // Call callback with error
                if (typeof callback === 'function') {
                    callback(false, message);
                } else {
                    // Fallback jika tidak ada callback
                    self.showMessage('error', message);
                }
            }
        });
    },

    // Fungsi untuk update tampilan daftar kriteria (TANPA TOMBOL EDIT)
    updateKriteriaList: function(kriteriaList) {
        var html = '';

        if (kriteriaList.length === 0) {
            html =
                '<div class="text-center text-muted py-4" id="no-criteria">' +
                '<i class="fas fa-info-circle fa-lg mr-2"></i>' +
                'Belum ada kriteria penilaian' +
                '</div>';
        } else {
            html =
                '<div class="table-responsive">' +
                '<table class="table table-sm table-bordered table-hover mb-0">' +
                '<thead class="thead-light">' +
                '<tr>' +
                '<th style="width:5%;text-align:center;">#</th>' +
                '<th>Nama Kriteria</th>' +
                '<th style="width:15%;text-align:center;">Bobot (%)</th>' +
                '<th style="width:10%;text-align:center;">Aksi</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>';

            kriteriaList.forEach(function(item, index) {
                var namaKriteria = item.nama_kriteria.replace(/'/g, "\\'");
                html +=
                    '<tr>' +
                    '<td class="text-center">' + (index + 1) + '</td>' +
                    '<td>' + item.nama_kriteria + '</td>' +
                    '<td class="text-center">' +
                    '<span class="badge badge-pill badge-primary">' + item.bobot + '%</span>' +
                    '</td>' +
                    '<td class="text-center">' +
                    '<button type="button" class="btn btn-sm btn-outline-danger" ' +
                    'onclick="jQuery.daftar_matakuliah.hapusKriteria(\'' + item.id_kriteria_penilaian_matakuliah + '\', \'' + namaKriteria + '\')" ' +
                    'title="Hapus Kriteria">' +
                    '<i class="fas fa-trash"></i>' +
                    '</button>' +
                    '</td>' +
                    '</tr>';
            });

            html +=
                '</tbody>' +
                '</table>' +
                '</div>';
        }

        $('#kriteriaList').html(html);
    },

    // Fungsi untuk update total weight
    updateTotalWeight: function(kriteriaList) {
        var totalWeight = kriteriaList.reduce(function(sum, item) {
            return sum + parseFloat(item.bobot);
        }, 0);

        var badgeClass = totalWeight === 100 ? 'badge-success' : 'badge-warning';
        var statusText = totalWeight === 100 ? 'Total: 100% ✓' : 'Total: ' + totalWeight + '%';

        $('#total-weight-badge')
            .removeClass('badge-primary badge-success badge-warning')
            .addClass(badgeClass)
            .text(statusText);
    },

    // Fungsi untuk simpan kriteria (hanya tambah, tidak ada edit)
    simpanKriteria: function() {
        var self = this;
        var formData = $('#formKriteria').serialize();
        var url = '/dosen/akademik/daftar-matakuliah/json-kriteria-store';

        // Disable button untuk prevent double submit
        $('#btnSimpanKriteria').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Success message dengan jQuery.Confirm
                    $.alert({
                        title: '<i class="fas fa-check-circle mr-2 text-success"></i>Berhasil!',
                        content: response.message,
                        type: 'green',
                        typeAnimated: true,
                        theme: 'modern',
                        columnClass: 'col-md-4 col-md-offset-4',
                        buttons: {
                            ok: {
                                text: '<i class="fas fa-check mr-2"></i>OK',
                                btnClass: 'btn-success',
                                action: function() {
                                    // Dialog akan tertutup otomatis
                                }
                            }
                        }
                    });

                    // Reset form
                    $('#formKriteria')[0].reset();
                    $('#matkul_id').val($('#matkul_id').val()); // Pertahankan matkul_id
                    $('#btnSimpanKriteria').html('<i class="fas fa-plus mr-2"></i>Tambah Kriteria');

                    // Reload kriteria list (tanpa callback karena modal sudah terbuka)
                    self.loadKriteriaList($('#matkul_id').val());

                    // Reload DataTable
                    self.data.table.ajax.reload(null, false);
                } else {
                    self.showMessage('error', response.message);
                }
            },
            error: function(xhr) {
                var message = 'Terjadi kesalahan sistem';
                var errors = [];

                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var validationErrors = xhr.responseJSON.errors;
                    errors = Object.keys(validationErrors).map(function(key) {
                        return '• ' + validationErrors[key][0];
                    });
                    message = 'Terdapat kesalahan validasi:<br><br>' + errors.join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                // Error message dengan detail
                $.alert({
                    title: '<i class="fas fa-exclamation-triangle mr-2 text-danger"></i>Error Validasi',
                    content: message,
                    type: 'red',
                    typeAnimated: true,
                    theme: 'modern',
                    columnClass: 'col-md-6 col-md-offset-3',
                    buttons: {
                        ok: {
                            text: '<i class="fas fa-check mr-2"></i>OK',
                            btnClass: 'btn-danger',
                            action: function() {
                                // Focus ke field pertama yang error
                                if (errors.length > 0) {
                                    var firstErrorField = $('#formKriteria').find('.is-invalid').first();
                                    if (firstErrorField.length > 0) {
                                        firstErrorField.focus();
                                    } else {
                                        $('#nama_kriteria').focus();
                                    }
                                }
                            }
                        }
                    }
                });
            },
            complete: function() {
                // Re-enable button
                $('#btnSimpanKriteria').prop('disabled', false).html('<i class="fas fa-plus mr-2"></i>Tambah Kriteria');
            }
        });
    },

    // Fungsi untuk hapus kriteria dengan jQuery.Confirm
    hapusKriteria: function(id, nama) {
        var self = this;

        // Menggunakan jQuery.Confirm untuk konfirmasi hapus
        $.confirm({
            title: '<i class="fas fa-trash mr-2 text-danger"></i>Konfirmasi Hapus',
            content: 'Apakah Anda yakin ingin menghapus kriteria penilaian <strong>"' + nama + '"</strong>?<br><br>' +
                '<div class="alert alert-warning mt-2 mb-0">' +
                '<i class="fas fa-exclamation-triangle mr-2"></i>' +
                '<strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.' +
                '</div>',
            type: 'red',
            typeAnimated: true,
            theme: 'modern',
            columnClass: 'col-md-6 col-md-offset-3',
            buttons: {
                hapus: {
                    text: '<i class="fas fa-trash mr-2"></i>Ya, Hapus',
                    btnClass: 'btn-danger',
                    action: function () {
                        var jc = this;
                        jc.buttons.hapus.setText('<i class="fas fa-spinner fa-spin mr-2"></i>Menghapus...');
                        jc.buttons.hapus.disable();
                        jc.buttons.batal.disable();

                        $.ajax({
                            url: '/dosen/akademik/daftar-matakuliah/json-kriteria-delete',
                            type: 'post',
                            data: { id: id },
                            success: function(response) {
                                if (response.success) {
                                    jc.close();
                                    self.showMessage('success', response.message);

                                    // Reload kriteria list (tanpa callback karena modal sudah terbuka)
                                    self.loadKriteriaList($('#matkul_id').val());

                                    // Reload DataTable
                                    self.data.table.ajax.reload(null, false);
                                } else {
                                    jc.close();
                                    self.showMessage('error', response.message);
                                }
                            },
                            error: function(xhr) {
                                var message = xhr.responseJSON && xhr.responseJSON.message ?
                                    xhr.responseJSON.message : 'Terjadi kesalahan sistem';
                                jc.close();
                                self.showMessage('error', message);
                            }
                        });
                        return false; // Prevent dialog from closing
                    }
                },
                batal: {
                    text: '<i class="fas fa-times mr-2"></i>Batal',
                    btnClass: 'btn-secondary',
                    action: function () {
                        // Dialog akan tertutup otomatis
                    }
                }
            }
        });
    },

    // Utility function untuk show message menggunakan jQuery.Confirm
    showMessage: function(type, message) {
        var config = {
            columnClass: 'col-md-6 col-md-offset-3',
            theme: 'modern',
            typeAnimated: true,
            content: message,
            buttons: {
                ok: {
                    text: '<i class="fas fa-check mr-2"></i>OK',
                    btnClass: 'btn-primary',
                    action: function() {
                        // Dialog akan tertutup otomatis
                    }
                }
            }
        };

        switch(type) {
            case 'success':
                config.title = '<i class="fas fa-check-circle mr-2 text-success"></i>Berhasil';
                config.type = 'green';
                config.buttons.ok.btnClass = 'btn-success';
                break;
            case 'error':
                config.title = '<i class="fas fa-exclamation-triangle mr-2 text-danger"></i>Error';
                config.type = 'red';
                config.buttons.ok.btnClass = 'btn-danger';
                break;
            case 'warning':
                config.title = '<i class="fas fa-exclamation-triangle mr-2 text-warning"></i>Peringatan';
                config.type = 'orange';
                config.buttons.ok.btnClass = 'btn-warning';
                break;
            case 'info':
                config.title = '<i class="fas fa-info-circle mr-2 text-info"></i>Informasi';
                config.type = 'blue';
                config.buttons.ok.btnClass = 'btn-info';
                break;
            default:
                config.title = '<i class="fas fa-info-circle mr-2"></i>Notifikasi';
                config.type = 'blue';
        }

        $.alert(config);
    },

    // Utility function untuk konfirmasi umum
    showConfirm: function(title, message, callback) {
        $.confirm({
            title: title,
            content: message,
            type: 'blue',
            typeAnimated: true,
            theme: 'modern',
            columnClass: 'col-md-6 col-md-offset-3',
            buttons: {
                ya: {
                    text: '<i class="fas fa-check mr-2"></i>Ya',
                    btnClass: 'btn-primary',
                    action: function () {
                        if (typeof callback === 'function') {
                            callback(true);
                        }
                    }
                },
                tidak: {
                    text: '<i class="fas fa-times mr-2"></i>Tidak',
                    btnClass: 'btn-secondary',
                    action: function () {
                        if (typeof callback === 'function') {
                            callback(false);
                        }
                    }
                }
            }
        });
    },

    // Utility function untuk loading dialog
    showLoading: function(message) {
        return $.dialog({
            title: '<i class="fas fa-spinner fa-spin mr-2"></i>Loading',
            content: message || 'Sedang memproses data...',
            type: 'blue',
            theme: 'modern',
            columnClass: 'col-md-4 col-md-offset-4',
            closeIcon: false,
            buttons: {}
        });
    }
};

// Initialize when document ready
jQuery(document).ready(function () {
    jQuery.daftar_matakuliah.init();
});
