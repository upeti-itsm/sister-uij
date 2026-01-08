jQuery.krs_jadwal = {
    data: {
        table_jadwal: null,
        table_krs_terpilih: null,
        selected_matkul: [],
        sks_maksimal: 24,
        search:  '',
        draft_loaded: false,
        status_krs: 0, // Status KRS:  0=draft, 1=diajukan, 2=disetujui PA, 3=ditolak, 4=disetujui final
        can_edit: true, // Flag apakah bisa edit
        komentar_dps: '' // Komentar dari DPS
    },

    init: function () {
        var self = this;

        // Pastikan DOM sudah ready
        if (!$('#table-jadwal').length) {
            console.error('Table #table-jadwal tidak ditemukan! ');
            return;
        }

        if (!$('#table-krs-terpilih').length) {
            console.error('Table #table-krs-terpilih tidak ditemukan!');
            return;
        }

        // Load draft KRS terlebih dahulu sebelum init table
        self.loadDraftKRS(function() {
            self.setEvents();
            self.updateStatistik();
            self.loadSKSMaksimal();
            self.updateUIBasedOnStatus(); // Update UI berdasarkan status
        });
    },

    loadDraftKRS: function(callback) {
        var self = this;

        $.ajax({
            url: '/mhs/krs/json-draft',
            method: 'POST',
            dataType: 'json',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Draft KRS response:', response);

                // Cek apakah response valid dan ada data
                if (response && response.data && Array.isArray(response.data) && response.data.length > 0) {
                    // Pastikan setiap item memiliki id_krs_mahasiswa
                    self.data.selected_matkul = response.data.map(function(item) {
                        // Set default UUID jika id_krs_mahasiswa null atau tidak ada
                        if (! item.id_krs_mahasiswa || item.id_krs_mahasiswa === null) {
                            item.id_krs_mahasiswa = '00000000-0000-0000-0000-000000000000';
                        }

                        // Ambil status_krs dari item pertama (asumsi semua item punya status sama)
                        if (item.status_krs !== undefined && self.data.status_krs === 0) {
                            self.data.status_krs = parseInt(item.status_krs) || 0;
                        }

                        // Ambil komentar DPS dari item pertama
                        if (item.komentar_dps && ! self.data.komentar_dps) {
                            self.data.komentar_dps = item.komentar_dps;
                        }

                        return item;
                    });

                    // Set flag can_edit berdasarkan status
                    self.data.can_edit = (self.data.status_krs === 0 || self.data.status_krs === 3);

                    self.data.draft_loaded = true;
                    console.log('Draft KRS loaded:', self.data.selected_matkul.length + ' mata kuliah');
                    console.log('Status KRS:', self.data.status_krs);
                    console.log('Can Edit:', self.data.can_edit);
                    console.log('Komentar DPS:', self.data.komentar_dps);
                } else {
                    console.log('Tidak ada draft KRS atau data kosong');
                    self.data.selected_matkul = [];
                    self.data.draft_loaded = false;
                    self.data.status_krs = 0;
                    self.data.can_edit = true;
                    self.data.komentar_dps = '';
                }
            },
            error: function(xhr, status, error) {
                console.warn('Gagal memuat draft KRS:', error);
                console.warn('Response:', xhr.responseText);
                self.data.selected_matkul = [];
                self.data.draft_loaded = false;
                self.data.status_krs = 0;
                self.data.can_edit = true;
                self.data.komentar_dps = '';
            },
            complete: function() {
                // Panggil callback setelah selesai (berhasil atau gagal)
                if (typeof callback === 'function') {
                    callback();
                }
            }
        });
    },

    setEvents: function () {
        var self = this;

        // Initialize Select2
        if (typeof $.fn.select2 !== 'undefined') {
            $(".select2").select2();
        }

        // DESTROY table jika sudah ada
        if ($.fn.DataTable.isDataTable('#table-jadwal')) {
            $('#table-jadwal').DataTable().clear().destroy();
        }

        // Clear table content
        $('#table-jadwal tbody').empty();

        // Initialize DataTable untuk jadwal mata kuliah
        try {
            self.data.table_jadwal = $("#table-jadwal").DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: '/mhs/krs/json',
                    type: 'POST',
                    data: function (d) {
                        d.search_matkul = self.data.search;
                        console.log('Sending data:', d);
                        return d;
                    },
                    dataSrc: function(json) {
                        console.log('Received data:', json);

                        // Pastikan response valid
                        if (!json || typeof json !== 'object') {
                            console.warn('Invalid JSON response');
                            return [];
                        }

                        // Jika response tidak memiliki struktur DataTable yang benar
                        if (!json.hasOwnProperty('data')) {
                            if (Array.isArray(json)) {
                                json = {
                                    data: json,
                                    recordsTotal: json.length,
                                    recordsFiltered:  json.length,
                                    draw: 1
                                };
                            } else {
                                return [];
                            }
                        }

                        // Set default values untuk server-side processing
                        json.recordsTotal = json.recordsTotal || (json.data ? json.data.length : 0);
                        json.recordsFiltered = json.recordsFiltered || json.recordsTotal;
                        json.draw = json.draw || 1;

                        return json.data || [];
                    },
                    complete: function(xhr, status) {
                        setTimeout(function() {
                            $("#table-jadwal_processing").hide();
                        }, 300);
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTable Error:', error, thrown);
                        console.error('Response:', xhr.responseText);

                        $("#table-jadwal_processing").hide();
                        $("#btn-cari-data").prop('disabled', false).html('<i class="fas fa-search mr-2"></i>Cari');

                        $.alert({
                            title: 'Error',
                            content: 'Gagal memuat data jadwal:  ' + (thrown || error),
                            type: 'red'
                        });
                    }
                },
                drawCallback: function (settings) {
                    $("#table-jadwal_processing").hide();

                    var api = this.api();
                    var data = api.rows().data().toArray();
                    var total_matkul = data.length;

                    $("#tot_matkul").text(total_matkul);
                    self.updateCheckboxState();
                },
                scrollY: '400px',
                scrollCollapse: true,
                columns: [
                    {
                        data: null,
                        searchable: false,
                        orderable: false,
                        className: 'text-center',
                        width: "3%",
                        render: function (data, type, row) {
                            if (! data || ! data.id) return '';
                            var isSelected = self.data.selected_matkul.some(item => item.id === data.id);
                            var isDisabled = (data.jumlah_peserta >= data.kapasitas) ?  'disabled' : '';

                            // Disable checkbox jika tidak bisa edit
                            if (!self.data.can_edit) {
                                isDisabled = 'disabled';
                            }

                            var checked = isSelected ? 'checked' : '';
                            return `<input type="checkbox" class="matkul-checkbox" data-id="${data.id}" ${checked} ${isDisabled}>`;
                        }
                    },
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: "3%",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: null,
                        searchable: true,
                        className: 'text-left',
                        width: "16%",
                        render: function (data) {
                            if (!data) return '-';
                            return `<strong>${data.kd_mata_kuliah || '-'}</strong><br/>
                                    <small class="text-muted">${data.nama_mata_kuliah || '-'}</small>`;
                        }
                    },
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: "10%",
                        render: function (data) {
                            if (!data) return '-';
                            return `<span class="badge badge-primary">${data.nama_kelas || '-'}</span><br/>
                                    <small class="text-muted">${data.jenis_kelas || '-'}</small>`;
                        }
                    },
                    {
                        data: 'sks',
                        searchable:  false,
                        className: 'text-center',
                        width: "7%",
                        render: function (data) {
                            return `<span class="badge badge-success">${data || 0} SKS</span>`;
                        }
                    },
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: "8%",
                        render: function (data) {
                            if (!data) return '-';
                            var hari_names = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                            return hari_names[data.hari] || '-';
                        }
                    },
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: "12%",
                        render: function (data) {
                            if (!data) return '-';
                            return `${data.jam_mulai || '-'} - ${data.jam_selesai || '-'}`;
                        }
                    },
                    {
                        data: 'ruang',
                        searchable:  false,
                        className: 'text-center',
                        width: "10%",
                        defaultContent: '-'
                    },
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: "9%",
                        render: function (data) {
                            if (!data) return '-';
                            var sisa = (data.kapasitas || 0) - (data.jumlah_peserta || 0);
                            var color = sisa > 0 ? 'success' : 'danger';
                            return `<span class="badge badge-${color}">${data.jumlah_peserta || 0}/${data.kapasitas || 0}</span>`;
                        }
                    },
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: "7%",
                        render: function (data) {
                            if (!data) return '-';
                            var sisa = (data.kapasitas || 0) - (data.jumlah_peserta || 0);
                            if (sisa > 0) {
                                return `<span class="badge badge-success">Tersedia</span>`;
                            } else {
                                return `<span class="badge badge-danger">Penuh</span>`;
                            }
                        }
                    },
                    {
                        data: null,
                        searchable: false,
                        orderable: false,
                        className: 'text-center',
                        width: "6%",
                        render: function (data) {
                            if (!data || !data.id) return '';
                            return `<button class="btn btn-sm btn-info btn-detail-matkul" data-id="${data.id}" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>`;
                        }
                    }
                ],
                paging: true,
                processing: true,
                pageLength: 10,
                ordering: false,
                lengthChange: false,
                autoWidth: false,
                dom: 'ltipr',
                language: {
                    "emptyTable": "Tidak ditemukan data jadwal mata kuliah",
                    "processing": "Sedang memuat data...",
                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next":  "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

            console.log('DataTable table-jadwal initialized successfully');

        } catch (e) {
            console.error('Error initializing table-jadwal:', e);
            $.alert({
                title: 'Error',
                content: 'Gagal inisialisasi tabel: ' + e.message,
                type: 'red'
            });
            return;
        }

        // Initialize DataTable untuk KRS terpilih
        try {
            // DESTROY table jika sudah ada
            if ($.fn.DataTable.isDataTable('#table-krs-terpilih')) {
                $('#table-krs-terpilih').DataTable().clear().destroy();
            }

            // Clear table content
            $('#table-krs-terpilih tbody').empty();

            self.data.table_krs_terpilih = $("#table-krs-terpilih").DataTable({
                data: self.data.selected_matkul,
                columns: [
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: "4%",
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: null,
                        searchable: false,
                        className: 'text-left',
                        width: "25%",
                        render: function (data) {
                            if (!data) return '-';
                            return `<strong>${data.kd_mata_kuliah || '-'}</strong><br/>
                                    <small>${data.nama_mata_kuliah || '-'}</small>`;
                        }
                    },
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: "12%",
                        render: function (data) {
                            if (!data) return '-';
                            return `<span class="badge badge-primary">${data.nama_kelas || '-'}</span>`;
                        }
                    },
                    {
                        data: 'sks',
                        searchable: false,
                        className: 'text-center',
                        width: "8%",
                        render: function (data) {
                            return `<span class="badge badge-success">${data || 0} SKS</span>`;
                        }
                    },
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: "10%",
                        render: function (data) {
                            if (!data) return '-';
                            var hari_names = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                            return hari_names[data.hari] || '-';
                        }
                    },
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: "15%",
                        render: function (data) {
                            if (!data) return '-';
                            return `${data.jam_mulai || '-'} - ${data.jam_selesai || '-'}`;
                        }
                    },
                    {
                        data:  'ruang',
                        searchable: false,
                        className: 'text-center',
                        width: "15%",
                        defaultContent:  '-'
                    },
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: "7%",
                        render: function (data) {
                            if (!data || !data.id) return '';

                            // Hide tombol hapus jika tidak bisa edit
                            if (!self.data.can_edit) {
                                return '<span class="text-muted"><i class="fas fa-lock"></i></span>';
                            }

                            return `<button class="btn btn-sm btn-danger btn-hapus-matkul" data-id="${data.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>`;
                        }
                    }
                ],
                paging:  false,
                searching: false,
                ordering: false,
                info: false,
                language: {
                    "emptyTable": "Belum ada mata kuliah yang dipilih"
                }
            });

            console.log('DataTable table-krs-terpilih initialized successfully');

            // Update statistik setelah table terpilih di-init
            self.updateStatistik();

        } catch (e) {
            console.error('Error initializing table-krs-terpilih:', e);
        }

        // Event handlers
        self.setEventHandlers();
    },

    setEventHandlers: function() {
        var self = this;

        // Search events
        $("#btn-cari-data").off('click').on('click', function() {
            // Disable jika tidak bisa edit
            if (!self.data.can_edit) {
                return;
            }

            var $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Mencari...');

            self.data.search = $("#cari-matkul").val().trim();

            if (self.data.table_jadwal && $.fn.DataTable.isDataTable('#table-jadwal')) {
                $("#table-jadwal_processing").hide();

                self.data.table_jadwal.ajax.reload(function(json) {
                    $btn.prop('disabled', false).html('<i class="fas fa-search mr-2"></i>Cari');
                    $("#table-jadwal_processing").hide();
                }, false);

                setTimeout(function() {
                    if ($btn.is(':disabled')) {
                        $btn.prop('disabled', false).html('<i class="fas fa-search mr-2"></i>Cari');
                        $("#table-jadwal_processing").hide();
                    }
                }, 5000);
            }
        });

        // Enter key search
        $("#cari-matkul").off('keypress').on('keypress', function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                $("#btn-cari-data").click();
            }
        });

        // Checkbox events
        $("#select-all").off('change').on('change', function() {
            if (! self.data.can_edit) {
                $(this).prop('checked', false);
                return;
            }

            var isChecked = $(this).is(':checked');
            $(".matkul-checkbox:not(:disabled)").prop('checked', isChecked);

            if (isChecked) {
                $(".matkul-checkbox:checked").each(function() {
                    var id = $(this).data('id');
                    self.addToKRS(id);
                });
            } else {
                self.data.selected_matkul = [];
                self.updateKRSTable();
            }
        });

        // Individual checkbox
        $(document).off('change', '.matkul-checkbox').on('change', '.matkul-checkbox', function() {
            if (!self.data.can_edit) {
                $(this).prop('checked', false);
                return;
            }

            var id = $(this).data('id');
            if ($(this).is(':checked')) {
                self.addToKRS(id);
            } else {
                self.removeFromKRS(id);
            }
        });

        // Detail button
        $(document).off('click', '.btn-detail-matkul').on('click', '.btn-detail-matkul', function() {
            var id = $(this).data('id');
            self.showDetailMatkul(id);
        });

        // Remove dari KRS
        $(document).off('click', '.btn-hapus-matkul').on('click', '.btn-hapus-matkul', function() {
            if (!self.data.can_edit) {
                return;
            }

            var id = $(this).data('id');
            self.removeFromKRS(id);

            if (self.data.table_jadwal && $.fn.DataTable.isDataTable('#table-jadwal')) {
                self.data.table_jadwal.ajax.reload(null, false);
            }
        });

        // Hapus semua KRS
        $("#btn-hapus-semua").off('click').on('click', function() {
            if (!self.data.can_edit) {
                $.alert({
                    title: 'Tidak Dapat Diubah',
                    content: 'KRS sudah diajukan dan tidak dapat diubah.',
                    type: 'orange'
                });
                return;
            }

            $.confirm({
                title: 'Konfirmasi',
                content: 'Apakah Anda yakin ingin menghapus semua mata kuliah terpilih?',
                type: 'red',
                buttons: {
                    ya: {
                        text: 'Ya, Hapus Semua',
                        btnClass: 'btn-red',
                        action: function() {
                            self.data.selected_matkul = [];
                            self.updateKRSTable();

                            if (self.data.table_jadwal && $.fn.DataTable.isDataTable('#table-jadwal')) {
                                self.data.table_jadwal.ajax.reload(null, false);
                            }
                        }
                    },
                    batal: {
                        text:  'Batal',
                        btnClass: 'btn-default'
                    }
                }
            });
        });

        // Simpan KRS
        $("#btn-simpan-krs").off('click').on('click', function() {
            if (!self.data.can_edit) {
                $.alert({
                    title: 'Tidak Dapat Diubah',
                    content: 'KRS sudah diajukan dan tidak dapat diubah.',
                    type: 'orange'
                });
                return;
            }

            if (self.data.selected_matkul.length === 0) {
                $.alert({
                    title: 'Peringatan',
                    content:  'Anda belum memilih mata kuliah apapun! ',
                    type: 'orange'
                });
                return;
            }

            var total_sks = self.getTotalSKS();

            if (total_sks > self.data.sks_maksimal) {
                $.alert({
                    title: 'Melebihi Batas SKS',
                    content: `Total SKS yang dipilih (${total_sks} SKS) melebihi batas maksimal ${self.data.sks_maksimal} SKS per semester. Silakan kurangi mata kuliah yang dipilih.`,
                    type: 'red'
                });
                return;
            }

            var pesan_sks = total_sks >= self.data.sks_maksimal * 0.9 ?
                `<br/><small class="text-warning"><strong>Perhatian:</strong> Anda mengambil ${total_sks} SKS dari maksimal ${self.data.sks_maksimal} SKS.</small>` : '';

            $("#pesan-konfirmasi").html(`
                Anda akan menyimpan KRS dengan ${self.data.selected_matkul.length} mata kuliah
                (Total ${total_sks} SKS dari maksimal ${self.data.sks_maksimal} SKS).
                Apakah Anda yakin?  ${pesan_sks}
            `);
            $("#modal-konfirmasi").modal('show');
        });

        // Konfirmasi simpan
        $("#btn-konfirmasi-ya").off('click').on('click', function() {
            self.simpanKRS();
        });

        // Ajukan KRS
        $("#btn-ajukan-krs").off('click').on('click', function() {
            self.ajukanKRS();
        });

        // Download KRS
        $("#btn-download-krs").off('click').on('click', function() {
            self.downloadKRS();
        });
    },

    addToKRS: function(id) {
        var self = this;

        if (! self.data.can_edit) {
            return;
        }

        if (! self.data.table_jadwal || !$.fn.DataTable.isDataTable('#table-jadwal')) {
            console.error('DataTable not initialized');
            return;
        }

        var rows = self.data.table_jadwal.rows().data().toArray();
        var matkul = rows.find(item => item.id == id);

        if (matkul && ! self.data.selected_matkul.some(item => item.id == id)) {
            var current_sks = self.getTotalSKS();
            var new_total_sks = current_sks + parseInt(matkul.sks);

            if (new_total_sks > self.data.sks_maksimal) {
                $.alert({
                    title: 'Melebihi Batas SKS',
                    content: `Anda tidak dapat mengambil mata kuliah ini karena akan melebihi batas maksimal ${self.data.sks_maksimal} SKS per semester. Total SKS akan menjadi ${new_total_sks} SKS.`,
                    type: 'red'
                });
                $(`input[data-id="${id}"]`).prop('checked', false);
                return;
            }

            if (self.checkBentrokJadwal(matkul)) {
                $.alert({
                    title: 'Bentrok Jadwal',
                    content: 'Mata kuliah ini bentrok dengan jadwal yang sudah dipilih! ',
                    type: 'red'
                });
                $(`input[data-id="${id}"]`).prop('checked', false);
                return;
            }

            // Set default id_krs_mahasiswa jika belum ada
            if (!matkul.id_krs_mahasiswa) {
                matkul.id_krs_mahasiswa = '00000000-0000-0000-0000-000000000000';
            }

            self.data.selected_matkul.push(matkul);
            self.updateKRSTable();
        }
    },

    removeFromKRS: function(id) {
        var self = this;

        if (!self.data.can_edit) {
            return;
        }

        self.data.selected_matkul = self.data.selected_matkul.filter(item => item.id != id);
        self.updateKRSTable();
        $(`input[data-id="${id}"]`).prop('checked', false);
    },

    updateKRSTable: function() {
        var self = this;

        if (self.data.table_krs_terpilih && $.fn.DataTable.isDataTable('#table-krs-terpilih')) {
            self.data.table_krs_terpilih.clear().rows.add(self.data.selected_matkul).draw();
        }

        self.updateStatistik();
        $("#total-sks").text(self.getTotalSKS());
    },

    updateCheckboxState: function() {
        var self = this;
        $(".matkul-checkbox").each(function() {
            var id = $(this).data('id');
            var isSelected = self.data.selected_matkul.some(item => item.id == id);
            $(this).prop('checked', isSelected);

            // Disable jika tidak bisa edit
            if (!self.data.can_edit) {
                $(this).prop('disabled', true);
            }
        });

        // Disable select all jika tidak bisa edit
        if (!self.data.can_edit) {
            $("#select-all").prop('disabled', true);
        }
    },

    updateStatistik:  function() {
        var self = this;
        var total_sks = self.getTotalSKS();
        var sisa_sks = self.data.sks_maksimal - total_sks;

        $("#tot_dipilih").text(self.data.selected_matkul.length);
        $("#tot_sks").text(total_sks);
        $("#sks-terpilih-info").text(total_sks);
        $("#sks-sisa-info").text(sisa_sks);
        $("#sks-maks-info").text(self.data.sks_maksimal);

        var sks_card = $("#tot_sks").closest('.card');
        var sks_status = $("#sks-status");

        if (total_sks > self.data.sks_maksimal) {
            sks_card.find('.card-header').removeClass('card-header-warning card-header-success').addClass('card-header-danger');
            sks_status.text('Melebihi Batas! ');
        } else if (total_sks >= self.data.sks_maksimal * 0.8) {
            sks_card.find('.card-header').removeClass('card-header-warning card-header-danger').addClass('card-header-warning');
            sks_status.text('Mendekati Batas');
        } else {
            sks_card.find('.card-header').removeClass('card-header-danger card-header-warning').addClass('card-header-success');
            sks_status.text('Kredit Dipilih');
        }
    },

    updateUIBasedOnStatus: function() {
        var self = this;

        console.log('Updating UI based on status:', self.data.status_krs);

        // Tampilkan status badge
        self.showStatusBadge();

        // Jika status >= 1 dan bukan status 3 (ditolak), disable semua input dan tombol edit
        if (! self.data.can_edit) {
            // Disable input pencarian
            $("#cari-matkul").prop('disabled', true);
            $("#btn-cari-data").prop('disabled', true);

            // Disable checkbox select all
            $("#select-all").prop('disabled', true);

            // Hide tombol edit
            $("#btn-hapus-semua").hide();
            $("#btn-simpan-krs").hide();
            $("#btn-ajukan-krs").hide();

            // Tampilkan pesan
            self.showStatusMessage();
        } else {
            // Enable controls
            $("#cari-matkul").prop('disabled', false);
            $("#btn-cari-data").prop('disabled', false);
            $("#select-all").prop('disabled', false);

            // Show tombol edit
            $("#btn-hapus-semua").show();
            $("#btn-simpan-krs").show();
            $("#btn-ajukan-krs").show();

            // Tampilkan pesan untuk status 3 (ditolak)
            if (self.data.status_krs === 3) {
                self.showStatusMessage();
            }
        }

        // Jika status = 4 (approved final), tampilkan tombol download
        if (self.data.status_krs === 4) {
            $("#btn-download-krs").show();
        } else {
            $("#btn-download-krs").hide();
        }

        // Tampilkan komentar DPS jika status = 3 atau 4
        if ((self.data.status_krs === 3 || self.data.status_krs === 4) && self.data.komentar_dps) {
            self.showKomentarDPS();
        }
    },

    showStatusBadge: function() {
        var self = this;
        var statusText = '';
        var statusClass = '';

        switch(self.data.status_krs) {
            case 0:
                statusText = 'Draft';
                statusClass = 'badge-secondary';
                break;
            case 1:
                statusText = 'Diajukan';
                statusClass = 'badge-info';
                break;
            case 2:
                statusText = 'Disetujui PA';
                statusClass = 'badge-primary';
                break;
            case 3:
                statusText = 'Ditolak';
                statusClass = 'badge-danger';
                break;
            case 4:
                statusText = 'Disetujui';
                statusClass = 'badge-success';
                break;
            default:
                statusText = 'Draft';
                statusClass = 'badge-secondary';
        }

        var badgeHtml = `<span class="badge ${statusClass} ml-2" id="status-krs-badge">${statusText}</span>`;

        // Tambahkan badge ke header
        if ($("#status-krs-badge").length) {
            $("#status-krs-badge").replaceWith(badgeHtml);
        } else {
            $(".card-header h6:first").append(badgeHtml);
        }
    },

    showStatusMessage:  function() {
        var self = this;
        var messageHtml = '';

        switch(self.data.status_krs) {
            case 1:
                messageHtml = `
                <i class="fas fa-info-circle mr-2"></i>
                <strong>KRS Anda sudah diajukan</strong> dan sedang menunggu persetujuan.  Anda tidak dapat mengubah KRS saat ini.
            `;
                $('#status-message').removeClass().addClass('alert alert-info').html(messageHtml).show();
                break;
            case 2:
                messageHtml = `
                <i class="fas fa-check-circle mr-2"></i>
                <strong>KRS Anda sudah disetujui oleh Pembimbing Akademik</strong> dan sedang menunggu persetujuan final.
            `;
                $('#status-message').removeClass().addClass('alert alert-primary').html(messageHtml).show();
                break;
            case 3:
                messageHtml = `
                <i class="fas fa-times-circle mr-2"></i>
                <strong>KRS Anda ditolak. </strong> Silakan perbaiki KRS Anda sesuai dengan komentar Pembimbing Akademik di bawah.
            `;
                $('#status-message').removeClass().addClass('alert alert-danger').html(messageHtml).show();
                break;
            case 4:
                messageHtml = `
                <i class="fas fa-check-double mr-2"></i>
                <strong>KRS Anda sudah disetujui! </strong> Anda dapat mendownload KRS Anda.
            `;
                $('#status-message').removeClass().addClass('alert alert-success').html(messageHtml).show();
                break;
            default:
                $('#status-message').hide();
        }
    },

    showKomentarDPS: function() {
        var self = this;

        if (! self.data.komentar_dps) {
            $('#komentar-dps-message').hide();
            return;
        }

        var komentarClass = self.data.status_krs === 3 ? 'alert-warning' : 'alert-info';
        var komentarIcon = self.data.status_krs === 3 ? 'fa-exclamation-triangle' :  'fa-comment-dots';
        var komentarTitle = self.data.status_krs === 3 ? 'Alasan Penolakan' : 'Catatan Pembimbing Akademik';

        var komentarHtml = `
        <h6 class="alert-heading">
            <i class="fas ${komentarIcon} mr-2"></i>${komentarTitle}
        </h6>
        <hr/>
        <p class="mb-0">${self.data.komentar_dps}</p>
    `;

        $('#komentar-dps-message').removeClass().addClass('alert ' + komentarClass).html(komentarHtml).show();
    },

    getTotalSKS: function() {
        var self = this;
        return self.data.selected_matkul.reduce((total, item) => total + parseInt(item.sks || 0), 0);
    },

    checkBentrokJadwal: function(matkul_baru) {
        var self = this;
        return self.data.selected_matkul.some(function(matkul) {
            return matkul.hari === matkul_baru.hari &&
                self.isTimeOverlap(matkul.jam_mulai, matkul.jam_selesai,
                    matkul_baru.jam_mulai, matkul_baru.jam_selesai);
        });
    },

    isTimeOverlap: function(start1, end1, start2, end2) {
        return (start1 < end2 && end1 > start2);
    },

    showDetailMatkul: function(id) {
        var self = this;

        if (!self.data.table_jadwal || !$.fn.DataTable.isDataTable('#table-jadwal')) {
            console.error('DataTable not initialized');
            return;
        }

        var rows = self.data.table_jadwal.rows().data().toArray();
        var matkul = rows.find(item => item.id == id);

        if (matkul) {
            var hari_names = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

            $("#detail-nama-matkul").text(matkul.nama_mata_kuliah || '-');
            $("#detail-kode-matkul").text(matkul.kd_mata_kuliah || '-');
            $("#detail-sks").text((matkul.sks || 0) + ' SKS');
            $("#detail-kelas").text((matkul.nama_kelas || '-') + ' (' + (matkul.jenis_kelas || '-') + ')');
            $("#detail-hari").text(hari_names[matkul.hari] || '-');
            $("#detail-jam").text((matkul.jam_mulai || '-') + ' - ' + (matkul.jam_selesai || '-'));
            $("#detail-ruang").text(matkul.ruang + ' ' + matkul.lokasi || '-');
            $("#detail-nama_dosen").text(matkul.nama_dosen || '-');
            $("#detail-kapasitas").text(matkul.kapasitas || 0);
            $("#detail-peserta").text(matkul.jumlah_peserta || 0);
            $("#detail-keterangan").text(matkul.keterangan || 'Tidak ada keterangan khusus');

            $("#modal-detail-matkul").modal('show');
        }
    },

    loadSKSMaksimal: function() {
        var self = this;
        $.ajax({
            url: '/mhs/krs/sks-maksimal',
            method: 'POST',
            success: function(response) {
                if (response.sks >= 0) {
                    self.data.sks_maksimal = response.sks;
                    $("#sks_maksimal").text(response.sks);
                    $("#sks-maks-info").text(response.sks);
                    self.updateStatistik();
                }
            },
            error: function() {
                console.log('Menggunakan SKS maksimal default:  24');
            }
        });
    },

    simpanKRS: function() {
        var self = this;

        // Mapping data KRS dengan id_krs_mahasiswa
        var data_krs = self.data.selected_matkul.map(item => {
            var krs_item = {
                id_jadwal: item.id_jadwal_kuliah_id || item.id,
                kd_mata_kuliah: item.kd_mata_kuliah,
                sks: item.sks
            };

            // Tambahkan id_krs_mahasiswa, gunakan default UUID jika null
            krs_item.id_krs_mahasiswa = item.id_krs_mahasiswa || '00000000-0000-0000-0000-000000000000';

            return krs_item;
        });

        console.log('Data KRS yang akan dikirim:', data_krs);

        $.ajax({
            url: '/mhs/krs/simpan',
            method: 'POST',
            data: {
                krs_data: JSON.stringify(data_krs),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $("#btn-konfirmasi-ya").prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');
            },
            success: function(response) {
                console.log('Response simpan KRS:', response);

                if (response.status === "1" || response.status === 1) {
                    $.alert({
                        title: "Berhasil",
                        type: "green",
                        content: response.keterangan || 'KRS berhasil disimpan',
                        columnClass: 'medium',
                        onClose: function() {
                            window.location.reload();
                        }
                    });
                } else {
                    $.alert({
                        title: "Gagal",
                        type: "red",
                        content: response.keterangan || 'Gagal menyimpan KRS'
                    });
                }
            },
            error: function(xhr) {
                console.error('Save error:', xhr);
                $.alert({
                    title: "Error",
                    type: "red",
                    content: "Terjadi kesalahan sistem:  " + (xhr.responseJSON?.message || xhr.statusText)
                });
            },
            complete: function() {
                $("#btn-konfirmasi-ya").prop('disabled', false).html('Ya, Simpan');
                $("#modal-konfirmasi").modal('hide');
            }
        });
    },

    ajukanKRS: function() {
        var self = this;

        // Validasi apakah ada mata kuliah yang dipilih
        if (self.data.selected_matkul.length === 0) {
            $.alert({
                title: 'Peringatan',
                content: 'Anda belum memilih mata kuliah apapun!  Silakan pilih mata kuliah terlebih dahulu.',
                type: 'orange'
            });
            return;
        }

        // Filter hanya id_krs_mahasiswa yang bukan default UUID
        var id_krs_list = self.data.selected_matkul
            .map(item => item.id_krs_mahasiswa)
            .filter(id => id && id !== '00000000-0000-0000-0000-000000000000');

        // Validasi apakah ada data yang sudah tersimpan (bukan default UUID)
        if (id_krs_list.length === 0) {
            $.alert({
                title: 'Peringatan',
                content: 'Anda harus menyimpan draft KRS terlebih dahulu sebelum mengajukan! ',
                type: 'orange'
            });
            return;
        }

        var total_sks = self.getTotalSKS();

        // Validasi SKS maksimal
        if (total_sks > self.data.sks_maksimal) {
            $.alert({
                title: 'Melebihi Batas SKS',
                content: `Total SKS yang dipilih (${total_sks} SKS) melebihi batas maksimal ${self.data.sks_maksimal} SKS per semester. Silakan kurangi mata kuliah yang dipilih sebelum mengajukan.`,
                type: 'red'
            });
            return;
        }

        // Konfirmasi pengajuan
        $.confirm({
            title: 'Konfirmasi Pengajuan KRS',
            content: `
                <div class="alert alert-info">
                    <strong>Informasi Pengajuan: </strong><br/>
                    - Jumlah Mata Kuliah: <strong>${self.data.selected_matkul.length}</strong><br/>
                    - Total SKS: <strong>${total_sks} SKS</strong><br/>
                    - Batas Maksimal:  <strong>${self.data.sks_maksimal} SKS</strong>
                </div>
                <p class="mt-2">Apakah Anda yakin ingin mengajukan KRS ini? </p>
                <small class="text-muted">Setelah diajukan, KRS akan diproses untuk persetujuan dan tidak dapat diubah.</small>
            `,
            type: 'blue',
            typeAnimated: true,
            columnClass: 'medium',
            buttons: {
                ajukan: {
                    text:  'Ya, Ajukan',
                    btnClass: 'btn-primary',
                    action: function() {
                        self.prosesAjukanKRS(id_krs_list);
                    }
                },
                batal: {
                    text:  'Batal',
                    btnClass: 'btn-default'
                }
            }
        });
    },

    prosesAjukanKRS: function(id_krs_list) {
        var self = this;

        console.log('ID KRS yang akan diajukan:', id_krs_list);

        $.ajax({
            url: '/mhs/krs/ajukan-krs',
            method: 'POST',
            data: {
                id_krs_list: id_krs_list[0],
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $("#btn-ajukan-krs").prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Mengajukan...');
            },
            success: function(response) {
                console.log('Response ajukan KRS:', response);

                if (response.status === "1" || response.status === 1 || response.status === true) {
                    $.alert({
                        title: "Berhasil",
                        type:  "green",
                        content:  response.keterangan || 'KRS berhasil diajukan. Menunggu persetujuan.',
                        columnClass: 'medium',
                        onClose:  function() {
                            window.location.reload();
                        }
                    });
                } else {
                    $.alert({
                        title: "Gagal",
                        type: "red",
                        content: response.keterangan || 'Gagal mengajukan KRS'
                    });
                    $("#btn-ajukan-krs").prop('disabled', false).html('<i class="fas fa-paper-plane mr-2"></i>Ajukan KRS');
                }
            },
            error: function(xhr) {
                console.error('Ajukan error:', xhr);
                var errorMsg = 'Terjadi kesalahan sistem';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        errorMsg = response.keterangan || response.message || errorMsg;
                    } catch (e) {
                        errorMsg = xhr.statusText || errorMsg;
                    }
                }

                $.alert({
                    title: "Error",
                    type: "red",
                    content: errorMsg
                });
                $("#btn-ajukan-krs").prop('disabled', false).html('<i class="fas fa-paper-plane mr-2"></i>Ajukan KRS');
            },
            complete: function() {
                setTimeout(function() {
                    if ($("#btn-ajukan-krs").is(':disabled')) {
                        $("#btn-ajukan-krs").prop('disabled', false).html('<i class="fas fa-paper-plane mr-2"></i>Ajukan KRS');
                    }
                }, 1000);
            }
        });
    },

    downloadKRS: function() {
        var self = this;

        console.log('Downloading KRS...');

        // Tampilkan loading
        $("#btn-download-krs").prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Mempersiapkan...');

        // Buat form dan submit untuk download
        var form = $('<form>', {
            'method': 'POST',
            'action': '/mhs/krs/download-krs',
            'target': '_blank'
        });

        // Tambahkan CSRF token
        form.append($('<input>', {
            'type': 'hidden',
            'name': '_token',
            'value': $('meta[name="csrf-token"]').attr('content')
        }));

        // Submit form
        $('body').append(form);
        form.submit();
        form.remove();

        // Reset tombol setelah delay
        setTimeout(function() {
            $("#btn-download-krs").prop('disabled', false).html('<i class="fas fa-download mr-2"></i>Download KRS');

            $.alert({
                title: 'Download Dimulai',
                content: 'File KRS sedang dipersiapkan.Jika download tidak dimulai, silakan klik tombol download lagi.',
                type: 'green'
            });
        }, 2000);
    }
};

jQuery(document).ready(function () {
    console.log('Document ready, initializing KRS Jadwal...');
    jQuery.krs_jadwal.init();
});
