jQuery.krs_jadwal = {
    data: {
        table_jadwal: null,
        table_krs_terpilih: null,
        selected_matkul: [],
        sks_maksimal: 24,
        search: '',
        draft_loaded: false // flag untuk track apakah draft sudah dimuat
    },

    init: function () {
        var self = this;

        // Pastikan DOM sudah ready
        if (!$('#table-jadwal').length) {
            console.error('Table #table-jadwal tidak ditemukan!  ');
            return;
        }

        if (!$('#table-krs-terpilih').length) {
            console.error('Table #table-krs-terpilih tidak ditemukan! ');
            return;
        }

        // Load draft KRS terlebih dahulu sebelum init table
        self.loadDraftKRS(function() {
            self.setEvents();
            self.updateStatistik();
            self.loadSKSMaksimal();
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
                    self.data.selected_matkul = response.data;
                    self.data.draft_loaded = true;
                    console.log('Draft KRS loaded:', self.data.selected_matkul.length + ' mata kuliah');
                } else {
                    console.log('Tidak ada draft KRS atau data kosong');
                    self.data.selected_matkul = [];
                    self.data.draft_loaded = false;
                }
            },
            error: function(xhr, status, error) {
                console.warn('Gagal memuat draft KRS:', error);
                console.warn('Response:', xhr.responseText);
                self.data.selected_matkul = [];
                self.data.draft_loaded = false;
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
                        if (! json || typeof json !== 'object') {
                            console.warn('Invalid JSON response');
                            return [];
                        }

                        // Jika response tidak memiliki struktur DataTable yang benar
                        if (!json.hasOwnProperty('data')) {
                            if (Array.isArray(json)) {
                                json = {
                                    data: json,
                                    recordsTotal: json.length,
                                    recordsFiltered: json.length,
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
                            content: 'Gagal memuat data jadwal:   ' + (thrown || error),
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
                            if (!  data || !  data.id) return '';
                            var isSelected = self.data.selected_matkul.some(item => item.id === data.id);
                            var isDisabled = (data.jumlah_peserta >= data.kapasitas) ?   'disabled' : '';
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
                        searchable: false,
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
                            var color = sisa > 0 ?  'success' : 'danger';
                            return `<span class="badge badge-${color}">${data.jumlah_peserta || 0}/${data.kapasitas || 0}</span>`;
                        }
                    },
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width:   "7%",
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
                    "infoFiltered":   "(disaring dari _MAX_ total data)",
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
                content: 'Gagal inisialisasi tabel:  ' + e.message,
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
                data: self.data.selected_matkul, // Data sudah dimuat dari draft
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
                        data:   null,
                        searchable:   false,
                        className:  'text-center',
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
                        render:   function (data) {
                            return `<span class="badge badge-success">${data || 0} SKS</span>`;
                        }
                    },
                    {
                        data:   null,
                        searchable:  false,
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
                        className:   'text-center',
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
                        defaultContent:   '-'
                    },
                    {
                        data:  null,
                        searchable:  false,
                        className: 'text-center',
                        width: "7%",
                        render: function (data) {
                            if (!data || !data.id) return '';
                            return `<button class="btn btn-sm btn-danger btn-hapus-matkul" data-id="${data.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>`;
                        }
                    }
                ],
                paging:   false,
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
            var isChecked = $(this).is(':checked');
            $(".matkul-checkbox: not(:disabled)").prop('checked', isChecked);

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
            var id = $(this).data('id');
            self.removeFromKRS(id);

            if (self.data.table_jadwal && $.fn.DataTable.isDataTable('#table-jadwal')) {
                self.data.table_jadwal.ajax.reload(null, false);
            }
        });

        // Hapus semua KRS
        $("#btn-hapus-semua").off('click').on('click', function() {
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
                        text:   'Batal',
                        btnClass: 'btn-default'
                    }
                }
            });
        });

        // Simpan KRS
        $("#btn-simpan-krs").off('click').on('click', function() {
            if (self.data.selected_matkul.length === 0) {
                $.alert({
                    title: 'Peringatan',
                    content: 'Anda belum memilih mata kuliah apapun! ',
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
    },

    addToKRS: function(id) {
        var self = this;

        if (!  self.data.table_jadwal || ! $.fn.DataTable.isDataTable('#table-jadwal')) {
            console.error('DataTable not initialized');
            return;
        }

        var rows = self.data.table_jadwal.rows().data().toArray();
        var matkul = rows.find(item => item.id == id);

        if (matkul && !  self.data.selected_matkul.some(item => item.id == id)) {
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
                    content: 'Mata kuliah ini bentrok dengan jadwal yang sudah dipilih!  ',
                    type: 'red'
                });
                $(`input[data-id="${id}"]`).prop('checked', false);
                return;
            }

            self.data.selected_matkul.push(matkul);
            self.updateKRSTable();
        }
    },

    removeFromKRS: function(id) {
        var self = this;
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
        });
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
            sks_status.text('Melebihi Batas!  ');
        } else if (total_sks >= self.data.sks_maksimal * 0.8) {
            sks_card.find('.card-header').removeClass('card-header-warning card-header-danger').addClass('card-header-warning');
            sks_status.text('Mendekati Batas');
        } else {
            sks_card.find('.card-header').removeClass('card-header-danger card-header-warning').addClass('card-header-success');
            sks_status.text('Kredit Dipilih');
        }
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

        if (! self.data.table_jadwal || !$.fn.DataTable.isDataTable('#table-jadwal')) {
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
                console.log('Menggunakan SKS maksimal default:   24');
            }
        });
    },

    simpanKRS: function() {
        var self = this;
        var data_krs = self.data.selected_matkul.map(item => ({
            id_jadwal: item.id_jadwal_kuliah_id || item.id,
            kd_mata_kuliah: item.kd_mata_kuliah,
            sks: item.sks
        }));

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
                        type:   "red",
                        content:   response.keterangan || 'Gagal menyimpan KRS'
                    });
                }
            },
            error: function(xhr) {
                console.error('Save error:', xhr);
                $.alert({
                    title: "Error",
                    type: "red",
                    content: "Terjadi kesalahan sistem:   " + (xhr.responseJSON?.message || xhr.statusText)
                });
            },
            complete: function() {
                $("#btn-konfirmasi-ya").prop('disabled', false).html('Ya, Simpan');
                $("#modal-konfirmasi").modal('hide');
            }
        });
    }
};

jQuery(document).ready(function () {
    console.log('Document ready, initializing KRS Jadwal...');
    jQuery.krs_jadwal.init();
});
