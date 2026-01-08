jQuery.edit_mahasiswa = {
    init: function () {
        var self = this;
        self.checkSessionMessages(); // Check session messages
        self.setEvents();
        self.initPlugins();
    },

    initPlugins: function () {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    },

    setEvents: function () {
        var self = this;
        // IPK validation
        $('input[name="ipk"]').on('input', function () {
            var ipk = parseFloat($(this).val());

            if (isNaN(ipk)) {
                return;
            }

            // Batasi 2 desimal
            if ($(this).val().includes('.')) {
                var parts = $(this).val().split('.');
                if (parts[1] && parts[1].length > 2) {
                    $(this).val(ipk.toFixed(2));
                }
            }

            // Validasi range 0-4
            if (ipk < 0) {
                $(this).val(0);
                $.alert({
                    title: 'Peringatan',
                    type: 'orange',
                    content: 'IPK tidak boleh kurang dari 0'
                });
            } else if (ipk > 4) {
                $(this).val(4);
                $.alert({
                    title: 'Peringatan',
                    type: 'orange',
                    content: 'IPK tidak boleh lebih dari 4.00'
                });
            }
        });

        // Format IPK on blur
        $('input[name="ipk"]').on('blur', function () {
            var ipk = parseFloat($(this).val());
            if (!isNaN(ipk)) {
                $(this).val(ipk.toFixed(2));
            }
        });
        // Form validation and AJAX submit
        $('#form-edit-mahasiswa').on('submit', function (e) {
            e.preventDefault(); // Prevent default form submit

            var self = jQuery.edit_mahasiswa;
            var isValid = self.validateForm();

            if (! isValid) {
                return false;
            }

            // Konfirmasi sebelum submit
            $.confirm({
                title: '<i class="fas fa-question-circle mr-2"></i>Konfirmasi',
                content: 'Apakah Anda yakin ingin menyimpan perubahan data mahasiswa ini?',
                type: 'blue',
                columnClass: 'medium',
                typeAnimated: true,
                buttons: {
                    confirm: {
                        text: '<i class="fas fa-check mr-2"></i>Ya, Simpan',
                        btnClass: 'btn-primary',
                        action: function() {
                            self.submitFormAjax();
                        }
                    },
                    cancel: {
                        text: '<i class="fas fa-times mr-2"></i>Batal',
                        btnClass: 'btn-secondary'
                    }
                }
            });
        });
        // Auto format NIK (hanya angka, max 16)
        $('input[name="nik"]').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);
        });

        // Auto format NISN (hanya angka, max 10)
        $('input[name="nisn"]').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });

        // Auto format NPWP (format:  XX.XXX.XXX.X-XXX.XXX)
        $('input[name="npwp"]').on('input', function () {
            var value = this.value.replace(/[^0-9]/g, '');
            if (value.length > 15) {
                value = value.slice(0, 15);
            }

            // Format NPWP
            if (value.length > 2) {
                value = value.slice(0, 2) + '.' + value.slice(2);
            }
            if (value.length > 6) {
                value = value.slice(0, 6) + '.' + value.slice(6);
            }
            if (value.length > 10) {
                value = value.slice(0, 10) + '.' + value.slice(10);
            }
            if (value.length > 12) {
                value = value.slice(0, 12) + '-' + value.slice(12);
            }
            if (value.length > 16) {
                value = value.slice(0, 16) + '.' + value.slice(16);
            }

            this.value = value;
        });

        // Auto format phone (hanya angka dan +)
        $('input[name="telepon"], input[name="handphone"]').on('input', function () {
            this.value = this.value.replace(/[^0-9+]/g, '');

            // Batasi panjang maksimal 15 karakter
            if (this.value.length > 15) {
                this.value = this.value.slice(0, 15);
            }
        });

        // Auto format kode pos (hanya angka, max 5)
        $('input[name="kode_pos"]').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
        });

        // Auto format RT/RW (hanya angka, max 3)
        $('input[name="rt"], input[name="rw"]').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3);
        });

        // Email validation on blur
        $('input[name="email"]').on('blur', function () {
            var email = $(this).val();
            if (email && !self.isValidEmail(email)) {
                $(this).addClass('is-invalid');
                if (!$(this).next('.invalid-feedback').length) {
                    $(this).after('<div class="invalid-feedback">Format email tidak valid</div>');
                }
            } else {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            }
        });

        // Tanggal lahir validation
        $('input[name="tanggal_lahir"]').on('change', function () {
            var birthDate = new Date($(this).val());
            var today = new Date();
            var age = today.getFullYear() - birthDate.getFullYear();
            var monthDiff = today.getMonth() - birthDate.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            if (age < 15) {
                $.alert({
                    title: 'Peringatan',
                    type: 'orange',
                    content: 'Usia mahasiswa kurang dari 15 tahun. Pastikan tanggal lahir sudah benar.'
                });
            }

            if (birthDate > today) {
                $(this).addClass('is-invalid');
                if (!$(this).next('.invalid-feedback').length) {
                    $(this).after('<div class="invalid-feedback">Tanggal lahir tidak boleh di masa depan</div>');
                }
            } else {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            }
        });

        // Konfirmasi perubahan program studi
        var originalProdi = $('select[name="program_id"]').val();
        $('select[name="program_id"]').on('change', function () {
            var newProdi = $(this).val();

            if (originalProdi && newProdi && originalProdi !== newProdi) {
                $.confirm({
                    title: 'Konfirmasi Perubahan Program Studi',
                    type: 'orange',
                    content: 'Anda akan mengubah program studi mahasiswa. Apakah Anda yakin?',
                    buttons: {
                        confirm: {
                            text: 'Ya, Lanjutkan',
                            btnClass: 'btn-warning',
                            action: function () {
                                // Continue
                            }
                        },
                        cancel: {
                            text: 'Batal',
                            btnClass: 'btn-secondary',
                            action: function () {
                                $('select[name="program_id"]').val(originalProdi).trigger('change');
                            }
                        }
                    }
                });
            }
        });

        // Toggle KPS fields
        $('select[name="penerima_kps"]').on('change', function () {
            var isKPS = $(this).val() === 'Ya';
            $('input[name="nomor_kps"]').prop('required', isKPS);

            if (!isKPS) {
                $('input[name="nomor_kps"]').val('');
            }
        });

        // Button Batal confirmation
        $('a.btn-secondary[href*="sinkronisasi"]').on('click', function (e) {
            if (self.isFormChanged()) {
                e.preventDefault();
                var href = $(this).attr('href');

                $.confirm({
                    title: 'Konfirmasi',
                    type: 'orange',
                    content: 'Ada perubahan yang belum disimpan.Yakin ingin membatalkan?',
                    buttons: {
                        confirm: {
                            text: 'Ya, Batalkan',
                            btnClass: 'btn-danger',
                            action: function () {
                                window.location.href = href;
                            }
                        },
                        cancel: {
                            text: 'Tidak',
                            btnClass: 'btn-secondary'
                        }
                    }
                });
            }
        });

        // Auto dismiss alerts after 5 seconds
        setTimeout(function () {
            $('.alert').fadeOut('slow');
        }, 5000);
    },

    validateForm: function () {
        var isValid = true;
        var errors = [];

        // Validate required fields
        $('#form-edit-mahasiswa').find('[required]').each(function () {
            if (! $(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
                var fieldName = $(this).closest('.form-group').find('label').text().replace('*', '').trim();
                errors.push(fieldName + ' harus diisi');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        // Validate email format
        var email = $('input[name="email"]').val();
        if (email && ! this.isValidEmail(email)) {
            isValid = false;
            errors.push('Format email tidak valid');
            $('input[name="email"]').addClass('is-invalid');
        }

        // Validate NIK length
        var nik = $('input[name="nik"]').val();
        if (nik && nik.length !== 16) {
            isValid = false;
            errors.push('NIK harus 16 digit');
            $('input[name="nik"]').addClass('is-invalid');
        }

        // Validate IPK
        var ipk = parseFloat($('input[name="ipk"]').val());
        if (! isNaN(ipk)) {
            if (ipk < 0 || ipk > 4) {
                isValid = false;
                errors.push('IPK harus antara 0.00 - 4.00');
                $('input[name="ipk"]').addClass('is-invalid');
            }
        }

        // Show errors if any using jconfirm with large class
        if (! isValid && errors.length > 0) {
            var errorHtml = '<div class="alert alert-danger mb-0"><ul class="mb-0">';
            errors.forEach(function (error) {
                errorHtml += '<li>' + error + '</li>';
            });
            errorHtml += '</ul></div>';

            $.confirm({
                title: '<i class="fas fa-exclamation-triangle mr-2"></i>Validasi Gagal',
                content: errorHtml,
                type: 'red',
                columnClass: 'large',
                typeAnimated: true,
                buttons: {
                    ok: {
                        text: '<i class="fas fa-check mr-2"></i>OK',
                        btnClass:  'btn-red',
                        keys: ['enter']
                    }
                }
            });
        }

        return isValid;
    },

    isValidEmail: function (email) {
        var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    },

    isFormChanged: function () {
        var changed = false;

        $('#form-edit-mahasiswa').find('input, select, textarea').each(function () {
            var $field = $(this);
            var originalValue = $field.data('original-value');
            var currentValue = $field.val();

            if (originalValue !== undefined && originalValue !== currentValue) {
                changed = true;
                return false; // break loop
            }
        });

        return changed;
    },

    saveOriginalValues: function () {
        $('#form-edit-mahasiswa').find('input, select, textarea').each(function () {
            $(this).data('original-value', $(this).val());
        });
    },

    // TAMBAHAN: Method untuk submit form via AJAX
    submitFormAjax: function() {
        var self = this;
        var form = $('#form-edit-mahasiswa');
        var formData = new FormData(form[0]);
        var url = form.attr('action');

        // Show loading overlay
        self.showLoadingOverlay(true);

        // Disable submit button
        var btn = $('#btn-submit');
        btn.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm mr-2"></span>Menyimpan...'
        );

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                // Hide loading overlay
                self.showLoadingOverlay(false);

                if (response.is_success) {
                    // Show success message
                    $.confirm({
                        title: '<i class="fas fa-check-circle mr-2"></i>Sukses',
                        content:  response.result || 'Data mahasiswa berhasil diperbarui',
                        type: 'green',
                        columnClass: 'medium',
                        typeAnimated: true,
                        buttons: {
                            ok: {
                                text: '<i class="fas fa-check mr-2"></i>OK',
                                btnClass: 'btn-green',
                                keys: ['enter'],
                                action: function() {
                                    // Redirect to list page
                                    window.location.reload();
                                }
                            }
                        }
                    });
                } else {
                    // Show error message
                    $.confirm({
                        title: '<i class="fas fa-exclamation-circle mr-2"></i>Error',
                        content: response.result || 'Gagal memperbarui data mahasiswa',
                        type: 'red',
                        columnClass: 'large',
                        typeAnimated:  true,
                        buttons: {
                            ok: {
                                text: '<i class="fas fa-check mr-2"></i>OK',
                                btnClass: 'btn-red',
                                keys: ['enter']
                            }
                        }
                    });

                    // Re-enable button
                    btn.prop('disabled', false).html(
                        '<i class="fas fa-save mr-2"></i>Simpan Perubahan'
                    );
                }
            },
            error: function(xhr) {
                // Hide loading overlay
                self.showLoadingOverlay(false);

                var errorMessage = 'Terjadi kesalahan saat menyimpan data';

                if (xhr.responseJSON && xhr.responseJSON.result) {
                    errorMessage = xhr.responseJSON.result;
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Validation errors
                    var errors = xhr.responseJSON.errors;
                    errorMessage = '<div class="alert alert-danger mb-0"><ul class="mb-0">';
                    $.each(errors, function(key, value) {
                        errorMessage += '<li>' + value[0] + '</li>';
                    });
                    errorMessage += '</ul></div>';
                }

                $.confirm({
                    title: '<i class="fas fa-exclamation-circle mr-2"></i>Error',
                    content: errorMessage,
                    type:  'red',
                    columnClass: 'large',
                    typeAnimated: true,
                    buttons: {
                        ok: {
                            text: '<i class="fas fa-check mr-2"></i>OK',
                            btnClass: 'btn-red',
                            keys: ['enter']
                        }
                    }
                });

                // Re-enable button
                btn.prop('disabled', false).html(
                    '<i class="fas fa-save mr-2"></i>Simpan Perubahan'
                );
            }
        });
    },

// TAMBAHAN: Method untuk show/hide loading overlay
    showLoadingOverlay: function(show) {
        if (show) {
            if ($('#loading-overlay-ajax').length === 0) {
                var overlay = '<div id="loading-overlay-ajax" class="loading-overlay-ajax">' +
                    '<div class="loading-spinner-ajax">' +
                    '<div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">' +
                    '<span class="sr-only">Loading...</span>' +
                    '</div>' +
                    '<h5 class="mt-3 text-primary">Menyimpan Data...</h5>' +
                    '<p class="text-muted">Mohon tunggu sebentar</p>' +
                    '</div>' +
                    '</div>';
                $('body').append(overlay);
                $('#loading-overlay-ajax').fadeIn(300);
            }
        } else {
            $('#loading-overlay-ajax').fadeOut(300, function() {
                $(this).remove();
            });
        }
    },

    // TAMBAHAN: Check session messages
    checkSessionMessages: function() {
        // Check for success message
        var successAlert = $('.alert-success');
        if (successAlert.length > 0) {
            var successMessage = successAlert.html();

            $.confirm({
                title: '<i class="fas fa-check-circle mr-2"></i>Sukses',
                content:  successMessage,
                type: 'green',
                columnClass:  'medium',
                typeAnimated: true,
                buttons:  {
                    ok: {
                        text: '<i class="fas fa-check mr-2"></i>OK',
                        btnClass: 'btn-green',
                        keys: ['enter']
                    }
                }
            });

            successAlert.hide();
        }

        // Check for error message
        var errorAlert = $('.alert-danger');
        if (errorAlert.length > 0) {
            var errorMessage = errorAlert.html();

            $.confirm({
                title: '<i class="fas fa-exclamation-circle mr-2"></i>Error',
                content: errorMessage,
                type: 'red',
                columnClass: 'large',
                typeAnimated: true,
                buttons: {
                    ok: {
                        text: '<i class="fas fa-check mr-2"></i>OK',
                        btnClass: 'btn-red',
                        keys:  ['enter']
                    }
                }
            });

            errorAlert.hide();
        }
    },
};

jQuery(document).ready(function () {
    jQuery.edit_mahasiswa.init();
    jQuery.edit_mahasiswa.saveOriginalValues();
});
