jQuery.matakuliah = {
    data: {
        table: null,
        // Excel upload variables
        excelData: [],
        validatedData: [],
        isUploading: false,
        uploadStats: {
            total: 0,
            success: 0,
            error: 0,
            current: 0
        }
    },

    init: function () {
        var self = this;
        self.setEvents();
        self.initDataTable();
        self.initExcelUpload();
    },

    initDataTable: function () {
        var self = this;

        self.data.table = $("#table").DataTable({
            serverSide: true,
            responsive: true,
            processing: true,
            pageLength: 15,
            lengthChange: false,
            ordering: false,
            autoWidth: false,
            scrollX: true,
            searching: true, // Disable built-in search
            dom: 'ltipr', // Remove 'f' (filter/search) from DOM
            ajax: {
                url: '/adm-akadmik/perkuliahan/matakuliah/json',
                type: 'post',
                data: function (d) {
                    d.kd_prodi = $("#kd_prodi").val();
                    d.id_kurikulum = $("#id_kurikulum").val();
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                // No urut
                {
                    data: 'nomor',
                    searchable: false,
                    className: 'text-center',
                    width: '5%'
                },

                // Matakuliah (Kode + Nama)
                {
                    data: null,
                    searchable: true,
                    className: 'text-left',
                    width: '25%',
                    render: function (data) {
                        const matakuliah = self.safeString(data.matakuliah || data.nama_matakuliah, '-');
                        const kode = self.safeString(data.kd_matakuliah || data.kode_matakuliah, '-');

                        return '<div class="matakuliah-info">' +
                            '<div class="matakuliah-title">' + matakuliah + '</div>' +
                            '<div class="matakuliah-subtitle">' +
                            '<i class="fas fa-code mr-1"></i>' + kode +
                            '</div></div>';
                    }
                },

                // SKS + Semester
                {
                    data: null,
                    searchable: false,
                    className: 'text-center',
                    width: '8%',
                    render: function (data) {
                        const sks = data.sks || data.jumlah_sks || '0';
                        const semester = data.semester || '0';

                        return '<span class="badge badge-sks mb-1">' + sks + ' SKS</span><br>' +
                            '<span class="badge badge-semester">Sem ' + semester + '</span>';
                    }
                },

                // Program Studi + Konsentrasi
                {
                    data: null,
                    searchable: true,
                    className: 'text-left',
                    width: '20%',
                    render: function (data) {
                        const prodi = self.safeString(data.nama_prodi || data.nama_program_studi, '-');
                        const konsentrasi = self.safeString(data.nama_konsentrasi || data.nama_konsentrasi_jurusan, '-');

                        return '<div class="prodi-info">' +
                            '<div class="prodi-name">' + prodi + '</div>' +
                            '<div class="konsentrasi-name">' +
                            '<i class="fas fa-graduation-cap mr-1"></i>' + konsentrasi +
                            '</div></div>';
                    }
                },

                // Kurikulum
                {
                    data: null,
                    searchable: true,
                    className: 'text-left',
                    width: '15%',
                    render: function (data) {
                        const kurikulum = self.safeString(data.nama_kurikulum, '-');
                        return '<small>' + kurikulum + '</small>';
                    }
                },

                // Kategori (Jenis MK + Pelaksanaan)
                {
                    data: null,
                    searchable: false,
                    className: 'text-center',
                    width: '12%',
                    render: function (data) {
                        const jenisMK = self.safeString(data.nama_jenis_matakuliah || data.jenis_matakuliah, '-');
                        const jenisPelaksanaan = self.safeString(data.nama_jenis_pelaksanaan || data.jenis_pelaksanaan, '-');

                        // Safe toLowerCase check
                        const jenisLower = self.safeToLowerCase(jenisMK);
                        const pelaksanaanLower = self.safeToLowerCase(jenisPelaksanaan);

                        const jenisClass = jenisLower.includes('wajib') ? 'badge-wajib' : 'badge-pilihan';
                        const pelaksanaanClass = pelaksanaanLower.includes('teori') ? 'badge-teori' :
                            pelaksanaanLower.includes('praktek') ? 'badge-praktek' : 'badge-gabungan';

                        return '<span class="badge ' + jenisClass + ' mb-1 d-block">' + self.truncateText(jenisMK, 8) + '</span>' +
                            '<span class="badge ' + pelaksanaanClass + ' d-block">' + self.truncateText(jenisPelaksanaan, 8) + '</span>';
                    }
                },

                // Prasyarat
                {
                    data: null,
                    searchable: false,
                    className: 'text-left',
                    width: '10%',
                    render: function (data) {
                        const prasyarat = self.safeString(data.id_matakuliah_prasyarat || data.matakuliah_prasyarat || data.prasyarat, '');

                        if (!prasyarat || prasyarat === '-' || prasyarat.trim() === '') {
                            return '<small class="text-muted">Tidak ada</small>';
                        }

                        const prerequisites = prasyarat.split(',')
                            .map(function (p) {
                                return p.trim();
                            })
                            .filter(function (p) {
                                return p !== '' && p !== '-';
                            });

                        if (prerequisites.length === 0) {
                            return '<small class="text-muted">Tidak ada</small>';
                        }

                        if (prerequisites.length <= 2) {
                            return prerequisites.map(function (p) {
                                return '<span class="prasyarat-item">' + self.truncateText(p, 10) + '</span>';
                            }).join('');
                        } else {
                            var displayItems = prerequisites.slice(0, 2).map(function (p) {
                                return '<span class="prasyarat-item">' + self.truncateText(p, 8) + '</span>';
                            }).join('');
                            return '<div class="prasyarat-list">' + displayItems +
                                '<small class="text-muted d-block">+' + (prerequisites.length - 2) + ' lainnya</small></div>';
                        }
                    }
                },

                // Aksi
                {
                    data: null,
                    searchable: false,
                    className: 'text-center',
                    width: '5%',
                    render: function (data) {
                        const idMatakuliah = self.safeString(data.id_matakuliah || data.id, '');
                        const kdProdi = self.safeString(data.kd_program_studi || data.kd_prodi, '');
                        const namaMatakuliah = self.safeString(data.matakuliah || data.nama_matakuliah, '');
                        const kodeMatakuliah = self.safeString(data.kd_matakuliah || data.kode_matakuliah, '');

                        return '<div class="btn-group" role="group">' +
                            '<button title="Edit Matakuliah" class="btn btn-sm btn-primary btn-detail"' +
                            ' data-id="' + idMatakuliah + '"' +
                            ' data-kd_prodi="' + kdProdi + '"' +
                            ' data-id_kurikulum="' + self.safeString(data.id_kurikulum, '') + '"' +
                            ' data-kode_matakuliah="' + kodeMatakuliah + '"' +
                            ' data-nama_matakuliah="' + namaMatakuliah + '"' +
                            ' data-jumlah_sks="' + self.safeString(data.sks || data.jumlah_sks, '') + '"' +
                            ' data-semester="' + self.safeString(data.semester, '') + '"' +
                            ' data-id_konsentrasi="' + self.safeString(data.id_konsentrasi || data.id_konsentrasi_jurusan, '') + '"' +
                            ' data-id_jenis_matakuliah="' + self.safeString(data.id_jenis_matakuliah || data.kd_jenis_matakuliah, '') + '"' +
                            ' data-id_jenis_pelaksanaan="' + self.safeString(data.id_jenis_pelaksanaan || data.kd_jenis_pelaksanaan, '') + '"' +
                            ' data-prasyarat="' + self.safeString(data.id_matakuliah_prasyarat || data.matakuliah_prasyarat, '') + '">' +
                            '<i class="fas fa-edit"></i>' +
                            '</button>' +
                            '<button title="Hapus Matakuliah" class="btn btn-sm btn-danger btn-delete"' +
                            ' data-id="' + idMatakuliah + '"' +
                            ' data-nama="' + namaMatakuliah + '"' +
                            ' data-kode="' + kodeMatakuliah + '">' +
                            '<i class="fas fa-trash"></i>' +
                            '</button></div>';
                    }
                }
            ],
            language: {
                "emptyTable": "Tidak ditemukan data matakuliah",
                "processing": "Sedang memproses...",
                "loadingRecords": "Memuat data...",
                "lengthMenu": "Tampilkan _MENU_ data",
                "zeroRecords": "Tidak ditemukan data yang cocok",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "search": "",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    },

    setEvents: function () {
        var self = this;

        // Initialize select2
        $(".select2").select2({
            theme: 'bootstrap4'
        });

        // FILTER CASCADE SYSTEM: Prodi -> Kurikulum -> Table Reload
        $("#kd_prodi").change(function () {
            const selectedProdi = $(this).val();
            self.loadKurikulumByProdi(selectedProdi, '#id_kurikulum');
        });

        // Auto reload table when kurikulum filter changes
        $("#id_kurikulum").change(function () {
            const selectedKurikulum = $(this).val();
            self.data.table.ajax.reload();
        });

        // Manual filter button (backup)
        $("#btn-filter").click(function () {
            self.data.table.ajax.reload();
        });

        // Search events
        $("#btn-cari-data").click(function () {
            const searchValue = $("#cari-data").val();
            self.data.table.search(searchValue).draw();
        });

        $("#cari-data").on('keyup', function () {
            if (this.value === "") {
                self.data.table.search(this.value).draw();
            }
        }).on('keypress', function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                self.data.table.search(this.value).draw();
            }
        });

        // Modal events
        $("#btn-tambah-data").click(function () {
            self.showModal('add');
        });

        // Upload Excel button
        $("#btn-upload-excel").click(function () {
            self.showUploadModal();
        });

        $("#table").on('click', 'button.btn-detail', function () {
            const buttonData = $(this).data();
            self.showModal('edit', buttonData);
        });

        $('#modal-insup').on('hidden.bs.modal', function () {
            self.resetForm();
        });

        $('#modal-upload-excel').on('hidden.bs.modal', function () {
            self.resetUploadModal();
        });

        // FORM CASCADE DEPENDENCIES
        // Program Studi -> Konsentrasi
        $("#insup-kd_prodi").change(function () {
            const selectedProdi = $(this).val();
            self.loadKonsentrasiByProdi(selectedProdi);
            self.loadKurikulumByProdi(selectedProdi, '#insup-id_kurikulum');
        });

        // Program Studi -> Konsentrasi
        $("#defaultProdi").change(function () {
            const selectedProdi = $(this).val();
            self.loadKurikulumByProdi(selectedProdi, '#defaultKurikulum');
        });

        // Kurikulum -> Matakuliah Prasyarat
        $("#insup-id_kurikulum").change(function () {
            const selectedKurikulum = $(this).val();
            self.loadMatakuliahByKurikulum(selectedKurikulum);
        });

        // Save event
        $("#btn-simpan").click(function () {
            self.saveData();
        });

        // Delete event
        $("#table").on('click', 'button.btn-delete', function () {
            self.deleteData($(this).data());
        });
    },

    // EXCEL UPLOAD FUNCTIONS
    initExcelUpload: function () {
        var self = this;

        // Setup drag and drop
        self.setupDragDrop();

        // File input change
        $('#file-input').change(function () {
            self.handleFileSelect(this.files[0]);
        });

        // Initialize select2 for default values
        $('#defaultProdi, #defaultKurikulum').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        // ADD THIS: Monitor kurikulum selection
        $('#defaultKurikulum').on('change', function () {
            if ($(this).val()) {
                $(this).removeClass('is-invalid');
                // Enable validate button only if file is loaded
                if (self.data.excelData.length > 0) {
                    self.showLog('info', 'Kurikulum dipilih: ' + $(this).find('option:selected').text());
                }
            }
        });
    },

    setupDragDrop: function () {
        const uploadArea = document.getElementById('uploadArea');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            uploadArea.classList.add('dragover');
        }

        function unhighlight(e) {
            uploadArea.classList.remove('dragover');
        }

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            jQuery.matakuliah.handleFileSelect(files[0]);
        }
    },

    handleFileSelect: function (file) {
        var self = this;

        if (!file) return;

        // ... (validasi file existing) ...

        // Show file info
        $('#fileName').text(file.name);
        $('#fileSize').text(self.formatFileSize(file.size));
        $('#fileInfo').show();
        $('#validationControls').show();

        // ADD THIS: Focus on kurikulum if not selected
        if (!$('#defaultKurikulum').val()) {
            self.showLog('info', 'File berhasil dimuat. Silakan pilih Kurikulum untuk melanjutkan.');
            $('#defaultKurikulum').focus();
            // Add visual indicator
            $('#defaultKurikulum').addClass('border-warning');
        }

        // Read Excel file
        self.readExcelFile(file);
    },

    readExcelFile: function (file) {
        var self = this;
        self.showLog('info', 'Membaca file Excel...');

        const reader = new FileReader();
        reader.onload = function (e) {
            try {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {type: 'array'});

                // Get first sheet
                const firstSheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[firstSheetName];

                // Convert to JSON
                const jsonData = XLSX.utils.sheet_to_json(worksheet, {header: 1});

                if (jsonData.length < 2) {
                    self.showLog('error', 'File Excel harus memiliki header dan minimal 1 baris data');
                    return;
                }

                if (jsonData.length > 1001) { // 1000 data + 1 header
                    self.showLog('error', 'Data terlalu banyak. Maksimal 1000 baris');
                    return;
                }

                self.processExcelData(jsonData);
                self.showLog('success', `Berhasil membaca ${jsonData.length - 1} baris data dari Excel`);

            } catch (error) {
                self.showLog('error', 'Gagal membaca file Excel: ' + error.message);
            }
        };

        reader.readAsArrayBuffer(file);
    },

    processExcelData: function (jsonData) {
        var self = this;
        const headers = jsonData[0];
        const rows = jsonData.slice(1);

        // Convert array format to object format
        self.data.excelData = rows.map((row, index) => {
            const obj = {row_number: index + 2}; // +2 because Excel rows start from 1 and we skip header
            headers.forEach((header, colIndex) => {
                obj[header] = row[colIndex] || '';
            });
            return obj;
        });

        // Show preview
        self.showPreview(headers, rows.slice(0, 5)); // Show first 5 rows
    },

    showPreview: function (headers, rows) {
        const table = $('#previewTable');
        const thead = table.find('thead');
        const tbody = table.find('tbody');

        // Clear existing content
        thead.empty();
        tbody.empty();

        // Add headers
        const headerRow = $('<tr></tr>');
        headers.forEach(header => {
            headerRow.append(`<th class="text-center small">${header}</th>`);
        });
        thead.append(headerRow);

        // Add rows
        rows.forEach(row => {
            const dataRow = $('<tr></tr>');
            headers.forEach((header, index) => {
                const cellValue = row[index] || '';
                dataRow.append(`<td class="small">${cellValue}</td>`);
            });
            tbody.append(dataRow);
        });

        $('#previewContainer').show();
    },

    validateData: function () {
        var self = this;

        // CHECK KURIKULUM FIRST - WAJIB!
        const defaultKurikulum = $('#defaultKurikulum').val();

        if (!defaultKurikulum) {
            self.showLog('error', 'Silakan pilih Kurikulum terlebih dahulu sebelum validasi!');
            $('#defaultKurikulum').focus();
            // Highlight the field
            $('#defaultKurikulum').addClass('is-invalid');

            $.alert({
                title: 'Kurikulum Belum Dipilih',
                type: 'red',
                content: 'Anda harus memilih kurikulum terlebih dahulu sebelum melakukan validasi data. Kurikulum ini akan digunakan untuk semua matakuliah yang diupload.',
                buttons: {
                    ok: {
                        text: 'Mengerti',
                        btnClass: 'btn-primary',
                        action: function () {
                            $('#defaultKurikulum').focus();
                        }
                    }
                }
            });
            return;
        }

        // Remove error state if kurikulum is selected
        $('#defaultKurikulum').removeClass('is-invalid');

        if (self.data.excelData.length === 0) {
            $.alert({
                title: 'Warning',
                type: 'red',
                content: 'Tidak ditemukan data matakuliah pada file yang anda upload'
            });
            return;
        }

        self.showLog('info', 'Memulai validasi data...');

        const requiredFields = [
            'kode_matakuliah', 'nama_matakuliah', 'jumlah_sks', 'semester'
        ];

        const defaultProdi = $('#defaultProdi').val();

        self.data.validatedData = [];
        let validCount = 0;
        let errorCount = 0;

        self.data.excelData.forEach((row, index) => {
            const errors = [];

            // Check required fields
            requiredFields.forEach(field => {
                if (!row[field] || row[field].toString().trim() === '') {
                    errors.push(`${field} tidak boleh kosong`);
                }
            });

            // Validate SKS (1-6)
            const sks = parseInt(row.jumlah_sks);
            if (isNaN(sks) || sks < 1 || sks > 6) {
                errors.push('jumlah_sks harus antara 1-6');
            }

            // Validate Semester (1-8)
            const semester = parseInt(row.semester);
            if (isNaN(semester) || semester < 1 || semester > 8) {
                errors.push('semester harus antara 1-8');
            }

            // Set defaults if not provided
            if (!row.kd_prodi && defaultProdi) {
                row.kd_prodi = defaultProdi;
            }

            // ALWAYS use the selected kurikulum
            row.id_kurikulum = defaultKurikulum;

            // Check if still missing required cascade fields
            if (!row.kd_prodi) {
                errors.push('kd_prodi wajib diisi atau set default');
            }

            // Set defaults for other fields if not provided
            if (!row.id_konsentrasi) row.id_konsentrasi = null; // Default konsentrasi
            if (!row.kd_jenis_matakuliah) row.kd_jenis_matakuliah = null;
            if (!row.kd_jenis_pelaksanaa) row.kd_jenis_pelaksanaa = null;

            if (errors.length === 0) {
                self.data.validatedData.push(row);
                validCount++;
            } else {
                errorCount++;
                self.showLog('error', `Baris ${row.row_number}: ${errors.join(', ')}`);
            }
            console.log(self.data.excelData)
        });
        self.showLog('success', `Validasi selesai: ${validCount} valid, ${errorCount} error`);
        self.showLog('info', `Kurikulum yang akan digunakan: ${$('#defaultKurikulum option:selected').text()}`);

        if (validCount > 0) {
            $('#btnStartUpload').prop('disabled', false);
            self.updateUploadStats(validCount, 0, 0, 0);
        }
    },

    startUpload: function () {
        var self = this;

        if (self.data.validatedData.length === 0) {
            self.showLog('error', 'Tidak ada data valid untuk diupload');
            return;
        }

        if (self.data.isUploading) {
            self.showLog('error', 'Upload sedang berlangsung');
            return;
        }

        self.data.isUploading = true;
        $('#btnStartUpload').prop('disabled', true);
        $('#progressContainer').show();
        $('#statusContainer').show();
        $('#summaryStats').show();

        self.data.uploadStats = {
            total: self.data.validatedData.length,
            success: 0,
            error: 0,
            current: 0
        };

        self.updateUploadStats(self.data.uploadStats.total, self.data.uploadStats.success, self.data.uploadStats.error, self.data.uploadStats.current);
        self.showLog('info', `Memulai upload ${self.data.uploadStats.total} data matakuliah...`);

        // Process upload in batches
        self.uploadBatch(0);
    },

    uploadBatch: function (startIndex) {
        var self = this;
        const batchSize = 5; // Upload 5 records at a time
        const endIndex = Math.min(startIndex + batchSize, self.data.validatedData.length);
        const batch = self.data.validatedData.slice(startIndex, endIndex);

        const promises = batch.map(row => self.uploadSingleRecord(row));

        Promise.allSettled(promises).then(results => {
            results.forEach((result, index) => {
                const row = batch[index];
                self.data.uploadStats.current++;

                if (result.status === 'fulfilled' && result.value.success) {
                    self.data.uploadStats.success++;
                    self.showLog('success', `Baris ${row.row_number}: ${row.nama_matakuliah} berhasil disimpan`);
                } else {
                    self.data.uploadStats.error++;
                    const errorMsg = result.value ? result.value.message : 'Upload gagal';
                    self.showLog('error', `Baris ${row.row_number}: ${errorMsg}`);
                }

                self.updateProgress();
            });

            // Continue with next batch or finish
            if (endIndex < self.data.validatedData.length) {
                setTimeout(() => self.uploadBatch(endIndex), 500); // Small delay between batches
            } else {
                self.finishUpload();
            }
        });
    },

    uploadSingleRecord: function (row) {
        return new Promise((resolve) => {
            // Simulate the same route as manual form submission
            // Using the same endpoint as the manual form
            const formData = {
                id: '00000000-0000-0000-0000-000000000000', // New record
                kode_matakuliah: row.kode_matakuliah,
                nama_matakuliah: row.nama_matakuliah,
                jumlah_sks: row.jumlah_sks,
                semester: row.semester,
                kd_prodi: row.kd_prodi,
                id_konsentrasi: row.id_konsentrasi,
                id_kurikulum: row.id_kurikulum,
                id_jenis_matakuliah: row.kd_jenis_matakuliah,
                id_jenis_pelaksanaan: row.kd_jenis_pelaksanaan,
                'id_matakuliah_prasyarat[]': row.prasyarat ? row.prasyarat.split(',').map(p => p.trim()) : [],
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            $.ajax({
                url: '/adm-akadmik/perkuliahan/matakuliah/store', // Same route as manual form
                type: 'POST',
                data: formData,
                timeout: 10000,
                success: function (response) {
                    resolve({success: true, data: response});
                },
                error: function (xhr, status, error) {
                    let message = 'Error tidak diketahui';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = Object.values(xhr.responseJSON.errors).flat();
                        message = errors.join(', ');
                    } else if (status === 'timeout') {
                        message = 'Request timeout';
                    } else if (xhr.status === 422) {
                        message = 'Data tidak valid';
                    } else if (xhr.status === 500) {
                        message = 'Server error';
                    }

                    resolve({success: false, message: message});
                }
            });
        });
    },

    updateProgress: function () {
        var self = this;
        const percentage = Math.round((self.data.uploadStats.current / self.data.uploadStats.total) * 100);
        $('#progressBar').css('width', percentage + '%').text(percentage + '%');
        $('#progressText').text(`Processing ${self.data.uploadStats.current} of ${self.data.uploadStats.total}...`);
        $('#progressCount').text(`${self.data.uploadStats.current} / ${self.data.uploadStats.total}`);

        self.updateUploadStats(self.data.uploadStats.total, self.data.uploadStats.success, self.data.uploadStats.error, self.data.uploadStats.current);
    },

    updateUploadStats: function (total, success, error, current) {
        $('#totalCount').text(total);
        $('#successCount').text(success);
        $('#errorCount').text(error);
    },

    finishUpload: function () {
        var self = this;
        self.data.isUploading = false;
        $('#btnStartUpload').prop('disabled', false).html('<i class="fas fa-check mr-1"></i>Upload Selesai');

        $('#progressBar').removeClass('progress-bar-animated');
        $('#progressText').text('Upload selesai!');

        self.showLog('success', `Upload selesai! ${self.data.uploadStats.success} berhasil, ${self.data.uploadStats.error} gagal dari ${self.data.uploadStats.total} total data`);

        // Refresh main datatable
        if (self.data.table) {
            self.data.table.ajax.reload();
        }

        // Show completion notification
        if (self.data.uploadStats.success > 0) {
            $.alert({
                title: 'Upload Berhasil',
                type: 'green',
                content: `${self.data.uploadStats.success} data matakuliah berhasil diupload!`,
                // buttons: {
                //     ok: function () {
                //         // Reset form atau refresh data
                //         $('#modal-upload-excel').modal("hide");
                //     }
                // }
            });
        }
    },

    clearFile: function () {
        var self = this;
        $('#file-input').val('');
        $('#fileInfo').hide();
        $('#validationControls').hide();
        $('#previewContainer').hide();
        $('#progressContainer').hide();
        $('#statusContainer').hide();
        $('#summaryStats').hide();

        self.data.excelData = [];
        self.data.validatedData = [];

        $('#btnStartUpload').prop('disabled', true).html('<i class="fas fa-upload mr-1"></i>Mulai Upload');
        self.showLog('info', 'File dihapus. Silakan pilih file baru.');
    },

    clearLog: function () {
        $('#logContainer').empty();
    },

    showLog: function (type, message) {
        const timestamp = new Date().toLocaleTimeString();
        const icon = {
            'success': 'fas fa-check-circle',
            'error': 'fas fa-exclamation-circle',
            'info': 'fas fa-info-circle'
        }[type] || 'fas fa-info-circle';

        const logItem = $(`
            <div class="log-item log-${type}">
                <i class="${icon} mr-2"></i>
                <small class="text-muted">[${timestamp}]</small>
                ${message}
            </div>
        `);

        $('#logContainer').append(logItem);
        // Auto scroll to bottom
        const container = $('#logContainer').parent();
        container.scrollTop(container[0].scrollHeight);
    },

    formatFileSize: function (bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    },

    downloadTemplate: function () {
        var self = this;

        // Log bahwa template sedang didownload (untuk tracking)
        self.showLog('success', 'Template Excel berhasil didownload');
    },

    showUploadModal: function () {
        var self = this;
        self.resetUploadModal();
        $('#modal-upload-excel').modal('show');
    },

    resetUploadModal: function () {
        var self = this;

        // Reset all containers
        $('#fileInfo').hide();
        $('#validationControls').hide();
        $('#previewContainer').hide();
        $('#progressContainer').hide();
        $('#statusContainer').hide();
        $('#summaryStats').hide();

        // Reset file input
        $('#file-input').val('');

        // Reset data
        self.data.excelData = [];
        self.data.validatedData = [];
        self.data.isUploading = false;

        // Reset form elements
        $('#defaultProdi').val('all').trigger('change');
        $('#defaultKurikulum').val('').trigger('change');
        $('#btnStartUpload').prop('disabled', true).html('<i class="fas fa-upload mr-1"></i>Mulai Upload');

        // Clear log
        $('#logContainer').empty();

        // Reset progress
        $('#progressBar').css('width', '0%').text('0%').addClass('progress-bar-animated');
        $('#progressText').text('Menunggu...');
        $('#progressCount').text('0 / 0');

        // Reset stats
        self.updateUploadStats(0, 0, 0, 0);
    },

    // END EXCEL UPLOAD FUNCTIONS

    showModal: function (type, data) {
        data = data || null;
        if (type === 'add') {
            $("#insupLabel").html('<i class="fas fa-plus-circle mr-2"></i>Tambah Matakuliah');
            this.resetForm();
        } else {
            $("#insupLabel").html('<i class="fas fa-edit mr-2"></i>Ubah Matakuliah');
            this.populateForm(data);
        }
        $("#modal-insup").modal('show');
    },

    resetForm: function () {
        $("#insup-id").val("00000000-0000-0000-0000-000000000000");
        $("#insup-form")[0].reset();

        // Reset dependent cascade selects
        $("#insup-id_konsentrasi").html('<option value="">-- Pilih Program Studi Terlebih Dahulu --</option>');
        $("#insup-id_kurikulum").html('<option value="">-- Pilih Program Studi Terlebih Dahulu --</option>');
        $("#insup-id_matakuliah_prasyarat").html('<option value="">-- Pilih Kurikulum Terlebih Dahulu --</option>');

        // Remove error states
        $(".form-control").removeClass('is-invalid');

        // Reset button state
        $("#btn-simpan").prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Simpan Data');
    },

    populateForm: function (data) {
        var self = this;

        if (!data || typeof data !== 'object') {
            console.warn('Data tidak valid untuk populate form', data);
            return;
        }

        // Set ID
        $("#insup-id").val(data.id || data.id_matakuliah || "00000000-0000-0000-0000-000000000000");

        // Set basic fields yang tidak bergantung cascade
        if (data.kode_matakuliah || data.kd_matakuliah) {
            $("#insup-kode_matakuliah").val(data.kode_matakuliah || data.kd_matakuliah);
        }
        if (data.nama_matakuliah || data.matakuliah) {
            $("#insup-nama_matakuliah").val(data.nama_matakuliah || data.matakuliah);
        }
        if (data.jumlah_sks || data.sks) {
            $("#insup-jumlah_sks").val(data.jumlah_sks || data.sks);
        }
        if (data.semester) {
            $("#insup-semester").val(data.semester);
        }
        if (data.id_jenis_matakuliah || data.kd_jenis_matakuliah) {
            $("#insup-id_jenis_matakuliah").val(data.id_jenis_matakuliah || data.kd_jenis_matakuliah).trigger('change');
        }
        if (data.id_jenis_pelaksanaan || data.kd_jenis_pelaksanaan) {
            $("#insup-id_jenis_pelaksanaan").val(data.id_jenis_pelaksanaan || data.kd_jenis_pelaksanaan).trigger('change');
        }

        // CASCADE POPULATION 1: Program Studi -> Konsentrasi
        if (data.kd_prodi || data.kd_program_studi) {
            var prodiValue = data.kd_prodi || data.kd_program_studi;
            $("#insup-kd_prodi").val(prodiValue).trigger('change');

            // Wait for prodi change to load konsentrasi, then set konsentrasi
            setTimeout(function () {
                if (data.id_konsentrasi || data.id_konsentrasi_jurusan) {
                    var konsentrasiValue = data.id_konsentrasi || data.id_konsentrasi_jurusan;
                    $("#insup-id_konsentrasi").val(konsentrasiValue).trigger('change');
                }
            }, 1000); // Wait 1 second for konsentrasi AJAX to complete
        }

        // CASCADE POPULATION 2: Kurikulum -> Prasyarat
        if (data.id_kurikulum) {
            $("#insup-id_kurikulum").val(data.id_kurikulum).trigger('change');

            // Wait for kurikulum change to load matakuliah, then set prasyarat
            setTimeout(function () {
                var prasyaratData = data.prasyarat || data.id_matakuliah_prasyarat || data.matakuliah_prasyarat;
                if (prasyaratData) {

                    var prasyaratValues = prasyaratData;
                    if (typeof prasyaratValues === 'string') {
                        prasyaratValues = prasyaratValues.split(',').map(function (p) {
                            return p.trim();
                        }).filter(function (p) {
                            return p !== '' && p !== '-';
                        });
                    }

                    if (prasyaratValues && prasyaratValues.length > 0) {
                        $("#insup-id_matakuliah_prasyarat").val(prasyaratValues).trigger('change');
                    }
                }
            }, 2000); // Wait 2 seconds for prasyarat AJAX to complete
        }
    },

    saveData: function () {
        var self = this;

        // Required fields dengan Program Studi
        const requiredFields = [
            'insup-kd_prodi', 'insup-id_konsentrasi', 'insup-id_kurikulum',
            'insup-kode_matakuliah', 'insup-nama_matakuliah',
            'insup-jumlah_sks', 'insup-semester',
            'insup-id_jenis_matakuliah', 'insup-id_jenis_pelaksanaan'
        ];

        var isValid = true;
        var missingFields = [];

        requiredFields.forEach(function (fieldId) {
            const $field = $("#" + fieldId);
            const value = $field.val();

            if (!value || value === '' || value === null || (Array.isArray(value) && value.length === 0)) {
                isValid = false;
                // Ambil label dari field atau fallback ke nama field
                var labelText = $field.closest('.form-group').find('label').first().text().replace('*', '').trim() || fieldId.replace('insup-', '');
                missingFields.push(labelText);

                // Highlight field yang error
                $field.addClass('is-invalid');
            } else {
                // Remove error highlight jika ada
                $field.removeClass('is-invalid');
            }
        });

        if (!isValid) {
            $.alert({
                title: 'Data Tidak Lengkap',
                type: 'red',
                content: 'Harap lengkapi semua field yang wajib diisi:<br>' +
                    missingFields.map(function (f) {
                        return '• ' + f;
                    }).join('<br>')
            });

            // Focus ke field pertama yang error
            for (var i = 0; i < requiredFields.length; i++) {
                var fieldId = requiredFields[i];
                if (!$("#" + fieldId).val()) {
                    $("#" + fieldId).focus();
                    break;
                }
            }

            return;
        }

        // Validasi tambahan
        const sks = parseInt($("#insup-jumlah_sks").val());
        const semester = parseInt($("#insup-semester").val());

        if (sks < 1 || sks > 6) {
            $.alert({
                title: 'Data Tidak Valid',
                type: 'red',
                content: 'Jumlah SKS harus antara 1-6'
            });
            $("#insup-jumlah_sks").focus().addClass('is-invalid');
            return;
        }

        if (semester < 1 || semester > 8) {
            $.alert({
                title: 'Data Tidak Valid',
                type: 'red',
                content: 'Semester harus antara 1-8'
            });
            $("#insup-semester").focus().addClass('is-invalid');
            return;
        }

        // Show loading state
        const $btnSimpan = $("#btn-simpan");
        const originalText = $btnSimpan.html();
        $btnSimpan.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        // Prepare form data object sesuai dengan struktur yang diinginkan
        const formData = {
            id: $("#insup-id").val(), // Sesuai ID
            kode_matakuliah: $("#insup-kode_matakuliah").val(),
            nama_matakuliah: $("#insup-nama_matakuliah").val(),
            jumlah_sks: $("#insup-jumlah_sks").val(),
            semester: $("#insup-semester").val(),
            kd_prodi: $("#insup-kd_prodi").val(),
            id_konsentrasi: $("#insup-id_konsentrasi").val(),
            id_kurikulum: $("#insup-id_kurikulum").val(),
            id_jenis_matakuliah: $("#insup-id_jenis_matakuliah").val(),
            id_jenis_pelaksanaan: $("#insup-id_jenis_pelaksanaan").val(),
            'id_matakuliah_prasyarat[]': $("#insup-id_matakuliah_prasyarat").val() || [], // Array untuk multiple selection
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        // AJAX request
        $.ajax({
            url: '/adm-akadmik/perkuliahan/matakuliah/store', // Same route as manual form
            type: 'POST',
            data: formData,
            timeout: 10000,
            success: function (response) {
                // Reset loading state
                $btnSimpan.prop('disabled', false).html(originalText);

                // Handle success response
                $.alert({
                    title: 'Berhasil',
                    type: 'green',
                    content: response.message || 'Data berhasil disimpan!',
                    buttons: {
                        ok: function () {
                            // Redirect atau reload page jika diperlukan
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                // Reset form atau refresh data
                                $('#modal-insup').modal("hide");
                                self.data.table.ajax.reload();
                            }
                        }
                    }
                });
            },
            error: function (xhr, status, error) {
                // Reset loading state
                $btnSimpan.prop('disabled', false).html(originalText);

                let errorMessage = 'Error tidak diketahui';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMessage = errors.join(', ');
                } else if (status === 'timeout') {
                    errorMessage = 'Request timeout';
                } else if (xhr.status === 422) {
                    errorMessage = 'Data tidak valid';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error';
                }

                $.alert({
                    title: 'Error',
                    type: 'red',
                    content: errorMessage
                });

                console.error('AJAX Error:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
            }
        });
    },

    deleteData: function (data) {
        var self = this;

        if (!data || !data.id) {
            $.alert({
                title: 'Error',
                type: 'red',
                content: 'Data tidak valid untuk dihapus'
            });
            return;
        }

        $("#delete-id_matakuliah").val(data.id);

        const nama = self.safeString(data.nama, 'Matakuliah');
        const kode = self.safeString(data.kode, 'Tidak ada kode');

        $.confirm({
            title: 'Konfirmasi Hapus',
            type: 'orange',
            content: [
                '<div class="text-left">',
                '<p>Apakah Anda yakin ingin menghapus matakuliah:</p>',
                '<div class="bg-light p-2 rounded">',
                '<strong>' + nama + '</strong><br>',
                '<small class="text-muted">Kode: ' + kode + '</small>',
                '</div>',
                '<p class="text-danger mt-2"><small>',
                '<i class="fas fa-exclamation-triangle"></i>',
                ' Data yang sudah dihapus tidak dapat dikembalikan!',
                '</small></p>',
                '</div>'
            ].join(''),
            buttons: {
                confirm: {
                    text: 'Ya, Hapus',
                    btnClass: 'btn-danger',
                    keys: ['enter'],
                    action: function () {
                        $("#delete-form").submit();
                    }
                },
                cancel: {
                    text: 'Batal',
                    btnClass: 'btn-secondary'
                }
            }
        });
    },

    // AJAX CASCADE FUNCTIONS

    // Load konsentrasi berdasarkan program studi
    loadKonsentrasiByProdi: function (kdProdi) {
        var self = this;
        const $konsentrasi = $("#insup-id_konsentrasi");

        if (!kdProdi || kdProdi === 'all' || kdProdi === '') {
            $konsentrasi.html('<option value="">-- Pilih Program Studi Terlebih Dahulu --</option>').trigger('change');
            return;
        }

        // Show loading
        $konsentrasi.html('<option value="">Memuat konsentrasi...</option>').prop('disabled', true);

        $.ajax({
            url: '/adm-akadmik/perkuliahan/matakuliah/json-konsentrasi-by-prodi',
            type: 'POST',
            data: {
                kd_prodi: kdProdi,
            },
            success: function (response) {
                var options = '<option value="">-- Pilih Konsentrasi --</option>';

                if (response.status === 'success' && response.data && response.data.length > 0) {
                    response.data.forEach(function (item) {
                        const namaKonsentrasi = self.safeString(item.nama_konsentrasi_jurusan, 'Konsentrasi');
                        options += '<option value="' + item.id_konsentrasi_jurusan + '">' + namaKonsentrasi + '</option>';
                    });
                } else {
                    options += '<option value="">Tidak ada konsentrasi</option>';
                }

                $konsentrasi.html(options).prop('disabled', false).trigger('change');
            },
            error: function (xhr, status, error) {
                $konsentrasi.html('<option value="">Error memuat konsentrasi</option>').prop('disabled', false);

                $.alert({
                    title: 'Error',
                    type: 'red',
                    content: 'Gagal memuat data konsentrasi. Silakan coba lagi.<br>Error: ' + error
                });
            }
        });
    },

    // Load kurikulum berdasarkan program studi (untuk filter)
    loadKurikulumByProdi: function (kdProdi, targetSelector) {
        var self = this;

        // Show loading
        $(targetSelector).html('<option value="">Memuat kurikulum...</option>').prop('disabled', true);

        $.ajax({
            url: '/adm-akadmik/perkuliahan/matakuliah/json-kurikulum-by-prodi',
            type: 'POST',
            data: {
                kd_prodi: kdProdi,
            },
            success: function (response) {
                var options = '<option value="">-- Semua Kurikulum --</option>';

                if (response.status === 'success' && response.data && response.data.length > 0) {
                    response.data.forEach(function (item) {
                        const namaKurikulum = self.safeString(item.nama_kurikulum, 'Kurikulum');
                        options += '<option value="' + item.id_kurikulum + '">' + namaKurikulum + '</option>';
                    });
                } else {
                    options += '<option value="">Tidak ada kurikulum</option>';
                }

                $(targetSelector).html(options).prop('disabled', false).trigger('change');
            },
            error: function (xhr, status, error) {
                $(targetSelector).html('<option value="">Error memuat kurikulum</option>').prop('disabled', false);

                $.alert({
                    title: 'Error',
                    type: 'red',
                    content: 'Gagal memuat data kurikulum. Silakan coba lagi.<br>Error: ' + error
                });
            }
        });
    },

    // Load matakuliah berdasarkan kurikulum untuk prasyarat
    loadMatakuliahByKurikulum: function (idKurikulum) {
        var self = this;
        const $prasyarat = $("#insup-id_matakuliah_prasyarat");

        if (!idKurikulum) {
            $prasyarat.html('<option value="">-- Pilih Kurikulum Terlebih Dahulu --</option>').trigger('change');
            return;
        }

        // Show loading
        $prasyarat.html('<option value="">Memuat matakuliah...</option>').prop('disabled', true);

        $.ajax({
            url: '/adm-akadmik/perkuliahan/matakuliah/json-by-kurikulum',
            type: 'POST',
            data: {
                id_kurikulum: idKurikulum,
            },
            success: function (response) {

                var options = '';

                if (response.status === 'success' && response.data && response.data.length > 0) {
                    response.data.forEach(function (item) {
                        const namaMK = self.safeString(item.nama_matakuliah || item.matakuliah, 'Matakuliah');
                        const kodeMK = self.safeString(item.kode_matakuliah || item.kd_matakuliah, '');
                        const displayText = kodeMK ? namaMK + ' (' + kodeMK + ')' : namaMK;

                        options += '<option value="' + item.id_matakuliah + '">' + displayText + '</option>';
                    });
                } else {
                    options = '<option value="">Belum ada matakuliah</option>';
                }

                $prasyarat.html(options).prop('disabled', false).trigger('change');
            },
            error: function (xhr, status, error) {
                $prasyarat.html('<option value="">Error memuat matakuliah</option>').prop('disabled', false);

                $.alert({
                    title: 'Error',
                    type: 'red',
                    content: 'Gagal memuat data matakuliah. Silakan coba lagi.<br>Error: ' + error
                });
            }
        });
    },

    // UTILITY FUNCTIONS

    // Safe text truncation
    truncateText: function (text, maxLength) {
        if (!text || typeof text !== 'string') return '';
        return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
    },

    // Safe string operation function
    safeString: function (value, defaultValue) {
        defaultValue = defaultValue || '';
        if (value === null || value === undefined) return defaultValue;
        if (typeof value !== 'string') return String(value);
        return value;
    },

    // Safe toLowerCase function
    safeToLowerCase: function (value) {
        if (!value || typeof value !== 'string') return '';
        return value.toLowerCase();
    }
};

// Global functions for Excel upload (accessible from onclick events)
function validateData() {
    jQuery.matakuliah.validateData();
}

function startUpload() {
    jQuery.matakuliah.startUpload();
}

function clearFile() {
    jQuery.matakuliah.clearFile();
}

function clearLog() {
    jQuery.matakuliah.clearLog();
}

function downloadTemplate() {
    jQuery.matakuliah.downloadTemplate();
}

// Initialize when document ready
jQuery(document).ready(function () {
    jQuery.matakuliah.init();
});
