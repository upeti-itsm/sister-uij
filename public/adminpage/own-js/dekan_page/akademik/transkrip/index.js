jQuery.transkripDekan = {
    data: {
        table_transkrip: null,
        filter: {
            status: 'proses_dekan',
            tahun: '',
            prodi: '',
            search: ''
        },
        statistik: {
            menunggu: 0,
            disahkan: 0,
            ditolak: 0,
            total: 0
        },
        current_detail: null,
        current_id: null,
        current_no: null,
        current_nama: null
    },

    init: function () {
        var self = this;

        console.log('Initializing Transkrip Dekan module...');

        if (!$('#table-transkrip-dekan').length) {
            console.error('Table #table-transkrip-dekan tidak ditemukan!');
            return;
        }

        self.initSelect2();
        self.loadProdiList();
        self.initDataTable();
        self.setEvents();
        self.loadStatistik();
    },

    // ============================================================
    // INISIALISASI
    // ============================================================

    initSelect2: function () {
        if (typeof $.fn.select2 !== 'undefined') {
            $(".select2").select2({
                width: '100%',
                theme: 'bootstrap4'
            });
        }
    },

    loadProdiList: function () {
        var self = this;

        $.ajax({
            url: '/dekan/transkrip/prodi-list',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Prodi list dekan:', response);

                if (response && Array.isArray(response)) {
                    var options = '<option value="">-- Semua Prodi --</option>';
                    response.forEach(function (item) {
                        options += `<option value="${item.id}">${item.nama}</option>`;
                    });
                    $('#filter-prodi').html(options);

                    if (typeof $.fn.select2 !== 'undefined') {
                        $('#filter-prodi').select2({
                            width: '100%',
                            theme: 'bootstrap4'
                        });
                    }
                }
            },
            error: function (xhr, status, error) {
                console.warn('Gagal memuat daftar prodi:', error);
            }
        });
    },

    initDataTable: function () {
        var self = this;

        if ($.fn.DataTable.isDataTable('#table-transkrip-dekan')) {
            $('#table-transkrip-dekan').DataTable().clear().destroy();
        }

        $('#table-transkrip-dekan tbody').empty();

        try {
            self.data.table_transkrip = $('#table-transkrip-dekan').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: '/dekan/transkrip/json',
                    type: 'POST',
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.status = self.data.filter.status;
                        d.tahun  = self.data.filter.tahun;
                        d.prodi  = self.data.filter.prodi;
                        d.search = self.data.filter.search;
                        return d;
                    },
                    dataSrc: function (json) {
                        console.log('Received transkrip dekan data:', json);

                        if (!json || typeof json !== 'object') {
                            console.warn('Invalid JSON response');
                            return [];
                        }

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

                        json.recordsTotal    = json.recordsTotal    || (json.data ? json.data.length : 0);
                        json.recordsFiltered = json.recordsFiltered || json.recordsTotal;
                        json.draw            = json.draw            || 1;

                        return json.data || [];
                    },
                    error: function (xhr, error, thrown) {
                        console.error('DataTable Error:', error, thrown);
                        $.alert({
                            title: 'Error',
                            content: 'Gagal memuat data pengajuan transkrip: ' + (thrown || error),
                            type: 'red'
                        });
                    }
                },
                columns: [
                    // No
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: '4%',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    // No. Pengajuan
                    {
                        data: 'no_pengajuan',
                        searchable: true,
                        className: 'text-left',
                        width: '13%',
                        render: function (data) {
                            return `<strong style="color:#4a148c;">${data || '-'}</strong>`;
                        }
                    },
                    // NIM
                    {
                        data: 'nim',
                        searchable: true,
                        className: 'text-left',
                        width: '10%',
                        render: function (data) {
                            return `<code>${data || '-'}</code>`;
                        }
                    },
                    // Nama Mahasiswa
                    {
                        data: 'nama_mahasiswa',
                        searchable: true,
                        className: 'text-left',
                        width: '17%',
                        defaultContent: '-'
                    },
                    // Program Studi
                    {
                        data: 'nama_prodi',
                        searchable: true,
                        className: 'text-left',
                        width: '12%',
                        render: function (data) {
                            if (!data) return '-';
                            return `<small>${data}</small>`;
                        }
                    },
                    // Keperluan
                    {
                        data: 'keperluan',
                        searchable: true,
                        className: 'text-left',
                        width: '10%',
                        defaultContent: '-'
                    },
                    // Tanggal Ajuan
                    {
                        data: 'tgl_pengajuan',
                        searchable: false,
                        className: 'text-center',
                        width: '9%',
                        render: function (data) {
                            return data ? self.formatTanggal(data) : '-';
                        }
                    },
                    // Tanggal Kaprodi
                    {
                        data: 'tgl_kaprodi',
                        searchable: false,
                        className: 'text-center',
                        width: '9%',
                        render: function (data) {
                            if (!data) return '<span class="text-muted">-</span>';
                            return `<span class="text-success">${self.formatTanggal(data)}</span>`;
                        }
                    },
                    // Status
                    {
                        data: 'status',
                        searchable: false,
                        className: 'text-center',
                        width: '10%',
                        render: function (data) {
                            return self.getBadgeStatus(data);
                        }
                    },
                    // Aksi
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: '6%',
                        render: function (data) {
                            if (!data) return '-';

                            var btnDetail = `
                                <button class="btn btn-info btn-sm btn-detail-dekan"
                                        data-id="${data.id_pengajuan || ''}"
                                        title="Lihat Detail & Tindakan">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </button>`;

                            return btnDetail;
                        }
                    }
                ],
                rowCallback: function (row, data) {
                    if (data.status === 'proses_dekan') {
                        $(row).addClass('row-menunggu-dekan');
                    }
                },
                drawCallback: function (settings) {
                    var api  = this.api();
                    var data = api.rows().data().toArray();

                    if (data.length === 0) {
                        $('#table-transkrip-dekan tbody').html(`
                            <tr>
                                <td colspan="10" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>Tidak ada data pengajuan transkrip</p>
                                        <small class="text-muted">
                                            Belum ada pengajuan yang masuk atau sesuai filter
                                        </small>
                                    </div>
                                </td>
                            </tr>
                        `);
                    }

                    self.updateBadgeFilterAktif();
                },
                paging: true,
                processing: true,
                pageLength: 10,
                ordering: false,
                lengthChange: true,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                autoWidth: false,
                language: {
                    emptyTable:   "Tidak ada data pengajuan transkrip",
                    processing:   "Sedang memuat data...",
                    zeroRecords:  "Tidak ditemukan data yang sesuai",
                    info:         "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty:    "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    lengthMenu:   "Tampilkan _MENU_ data",
                    paginate: {
                        first:    "Pertama",
                        last:     "Terakhir",
                        next:     "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            console.log('DataTable Transkrip Dekan initialized successfully');

        } catch (e) {
            console.error('Error initializing DataTable:', e);
            $.alert({
                title: 'Error',
                content: 'Gagal inisialisasi tabel: ' + e.message,
                type: 'red'
            });
        }
    },

    // ============================================================
    // EVENTS
    // ============================================================

    setEvents: function () {
        var self = this;

        // --- Filter ---
        $('#btn-filter').off('click').on('click', function () {
            self.applyFilter();
        });

        $('#btn-reset-filter').off('click').on('click', function () {
            self.resetFilter();
        });

        $('#filter-search').off('keypress').on('keypress', function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                self.applyFilter();
            }
        });

        // --- Refresh ---
        $('#btn-refresh').off('click').on('click', function () {
            if (self.data.table_transkrip) {
                self.data.table_transkrip.ajax.reload();
            }
            self.loadStatistik();
        });

        // --- Detail (dari tombol di tabel) ---
        $(document).off('click', '.btn-detail-dekan').on('click', '.btn-detail-dekan', function (e) {
            e.stopPropagation();
            var id = $(this).data('id');
            self.loadDetail(id);
        });

        // --- Sahkan dari modal detail ---
        $('#btn-sahkan-dekan').off('click').on('click', function () {
            if (!self.data.current_id) return;
            var catatan = $('#dekan-catatan').val().trim();
            $('#modal-detail-dekan').modal('hide');
            self.openKonfirmasiSahkan(
                self.data.current_id,
                self.data.current_no,
                self.data.current_nama,
                catatan
            );
        });

        // --- Tolak dari modal detail ---
        $('#btn-tolak-dekan').off('click').on('click', function () {
            if (!self.data.current_id) return;
            var catatan = $('#dekan-catatan').val().trim();
            $('#modal-detail-dekan').modal('hide');
            self.openKonfirmasiTolak(
                self.data.current_id,
                self.data.current_no,
                catatan
            );
        });

        // --- Konfirmasi Sahkan ---
        $('#btn-konfirmasi-sahkan').off('click').on('click', function () {
            self.prosesSahkan();
        });

        // --- Konfirmasi Tolak ---
        $('#btn-konfirmasi-tolak-dekan').off('click').on('click', function () {
            self.prosesTolak();
        });

        // --- Reset modal detail saat ditutup ---
        $('#modal-detail-dekan').on('hidden.bs.modal', function () {
            self.resetModalDetail();
        });

        // --- Reset modal tolak saat ditutup ---
        $('#modal-konfirmasi-tolak-dekan').on('hidden.bs.modal', function () {
            $('#alasan-tolak-dekan-final').val('').removeClass('is-invalid');
            $('#alasan-tolak-dekan-error').hide();
        });
    },

    // ============================================================
    // FILTER
    // ============================================================

    applyFilter: function () {
        var self = this;

        self.data.filter.status = $('#filter-status').val();
        self.data.filter.tahun  = $('#filter-tahun').val();
        self.data.filter.prodi  = $('#filter-prodi').val();
        self.data.filter.search = $('#filter-search').val().trim();

        console.log('Applying filter dekan:', self.data.filter);

        if (self.data.table_transkrip) {
            self.data.table_transkrip.ajax.reload();
        }
    },

    resetFilter: function () {
        var self = this;

        $('#filter-status').val('proses_dekan').trigger('change');
        $('#filter-tahun').val('').trigger('change');
        $('#filter-prodi').val('').trigger('change');
        $('#filter-search').val('');

        self.data.filter = {
            status: 'proses_dekan',
            tahun: '',
            prodi: '',
            search: ''
        };

        if (self.data.table_transkrip) {
            self.data.table_transkrip.ajax.reload();
        }
    },

    updateBadgeFilterAktif: function () {
        var self  = this;
        var f     = self.data.filter;
        var aktif = f.tahun || f.prodi || f.search ||
            (f.status && f.status !== 'proses_dekan');

        if (aktif) {
            $('#badge-filter-aktif').removeClass('d-none');
        } else {
            $('#badge-filter-aktif').addClass('d-none');
        }
    },

    // ============================================================
    // LOAD DATA
    // ============================================================

    loadStatistik: function () {
        var self = this;

        $.ajax({
            url: '/dekan/transkrip/statistik',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Statistik dekan:', response);

                if (response) {
                    $('#stat-menunggu').text(response.menunggu  || 0);
                    $('#stat-disahkan').text(response.disahkan  || 0);
                    $('#stat-ditolak').text(response.ditolak    || 0);
                    $('#stat-total').text(response.total        || 0);

                    self.data.statistik = {
                        menunggu: response.menunggu || 0,
                        disahkan: response.disahkan || 0,
                        ditolak:  response.ditolak  || 0,
                        total:    response.total     || 0
                    };
                }
            },
            error: function (xhr, status, error) {
                console.warn('Gagal memuat statistik dekan:', error);
            }
        });
    },

    loadDetail: function (id) {
        var self = this;

        if (!id) {
            $.alert({ title: 'Error', content: 'ID pengajuan tidak valid', type: 'red' });
            return;
        }

        self.resetModalDetail();
        $('#modal-detail-dekan').modal('show');

        $.ajax({
            url: '/dekan/transkrip/detail',
            method: 'POST',
            data: {
                _token:       $('meta[name="csrf-token"]').attr('content'),
                id_pengajuan: id
            },
            success: function (response) {
                console.log('Detail pengajuan dekan:', response);

                if (response && response.status === '1') {
                    self.data.current_detail = response.data;
                    self.data.current_id     = response.data.id_pengajuan;
                    self.data.current_no     = response.data.no_pengajuan;
                    self.data.current_nama   = response.data.nama_mahasiswa;

                    self.renderModalDetail(response.data);
                    self.loadPreviewNilai(response.data.nim);
                } else {
                    $('#modal-detail-dekan').modal('hide');
                    $.alert({
                        title: 'Error',
                        content: response.keterangan || 'Data tidak ditemukan',
                        type: 'red'
                    });
                }
            },
            error: function (xhr, status, error) {
                $('#modal-detail-dekan').modal('hide');
                $.alert({
                    title: 'Error',
                    content: 'Gagal memuat detail: ' + error,
                    type: 'red'
                });
            }
        });
    },

    loadPreviewNilai: function (nim) {
        var self = this;

        if (!nim) {
            $('#dkn-preview-nilai').html(`
                <tr>
                    <td colspan="8" class="text-center text-muted py-3">NIM tidak valid</td>
                </tr>`);
            return;
        }

        $.ajax({
            url: '/dekan/transkrip/preview-nilai',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                nim: nim
            },
            success: function (response) {
                console.log('Preview nilai dekan:', response);

                if (response && response.status === '1' && Array.isArray(response.data)) {
                    self.renderPreviewNilai(response.data);
                } else {
                    $('#dkn-preview-nilai').html(`
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">
                                Tidak ada data nilai
                            </td>
                        </tr>`);
                }
            },
            error: function () {
                $('#dkn-preview-nilai').html(`
                    <tr>
                        <td colspan="8" class="text-center text-danger py-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Gagal memuat preview nilai
                        </td>
                    </tr>`);
            }
        });
    },

    // ============================================================
    // RENDER MODAL DETAIL
    // ============================================================

    renderModalDetail: function (data) {
        var self = this;

        if (!data) return;

        // Header
        $('#dkn-no-pengajuan').text(data.no_pengajuan || '-');
        $('#dkn-status-badge').html(self.getBadgeStatus(data.status));

        // Info mahasiswa
        $('#dkn-nim').text(data.nim                  || '-');
        $('#dkn-nama').text(data.nama_mahasiswa       || '-');
        $('#dkn-prodi').text(data.nama_prodi          || '-');
        $('#dkn-angkatan').text(data.angkatan         || '-');

        // Mini IPK card
        var ipk       = parseFloat(data.ipk || 0);
        var predikat  = self.getPredikat(ipk);
        $('#dkn-ipk').text(ipk.toFixed(2));
        $('#dkn-predikat').text(predikat);

        // Info persetujuan kaprodi
        $('#dkn-nama-kaprodi').text(data.nama_kaprodi     || '-');
        $('#dkn-tgl-kaprodi').text(
            data.tgl_kaprodi ? self.formatTanggal(data.tgl_kaprodi, true) : '-'
        );
        $('#dkn-catatan-kaprodi').text(data.catatan_kaprodi || '-');

        // Detail pengajuan
        $('#dkn-keperluan').text(data.keperluan              || '-');
        $('#dkn-bahasa').text(data.bahasa                    || '-');
        $('#dkn-jumlah-lembar').text((data.jumlah_lembar || 0) + ' lembar');
        $('#dkn-email-tujuan').text(data.email_tujuan        || '-');
        $('#dkn-tgl-ajuan').text(self.formatTanggal(data.tgl_pengajuan));
        $('#dkn-catatan').text(data.catatan                  || '-');

        // Step indicator
        $('#dkn-step-indicator').html(self.renderStepIndicator(data.status));

        // Timeline
        $('#dkn-timeline').html(self.renderTimeline(data.riwayat || []));

        // Tombol & form tindakan — hanya tampil jika status = proses_dekan
        if (data.status === 'proses_dekan') {
            $('#section-tindakan-dekan').show();
            $('#btn-sahkan-dekan').removeClass('d-none');
            $('#btn-tolak-dekan').removeClass('d-none');
        } else {
            $('#section-tindakan-dekan').hide();
            $('#btn-sahkan-dekan').addClass('d-none');
            $('#btn-tolak-dekan').addClass('d-none');
        }
    },

    renderPreviewNilai: function (data) {
        var self = this;

        if (!data || data.length === 0) {
            $('#dkn-preview-nilai').html(`
                <tr>
                    <td colspan="8" class="text-center text-muted py-3">
                        Tidak ada data nilai
                    </td>
                </tr>`);
            $('#dkn-preview-total-sks').text(0);
            $('#dkn-preview-ipk').text('0.00');
            return;
        }

        var html       = '';
        var totalSks   = 0;
        var totalBobot = 0;

        data.forEach(function (mk, i) {
            var sks   = parseInt(mk.sks   || 0);
            var bobot = parseFloat(mk.bobot || 0);

            totalSks   += sks;
            totalBobot += (bobot * sks);

            var tahunParsed = self.parseTahunAkademik(mk.tahun_akademik);

            html += `
                <tr>
                    <td class="text-center">${i + 1}</td>
                    <td>${mk.kd_matakuliah || mk.kd_mata_kuliah || '-'}</td>
                    <td>${mk.matakuliah    || mk.nama_mata_kuliah || '-'}</td>
                    <td class="text-center">${sks}</td>
                    <td class="text-center">
                        ${(!mk.nilai_angka || mk.nilai_angka === '-')
                ? '-'
                : parseFloat(mk.nilai_angka).toFixed(2)}
                    </td>
                    <td class="text-center">
                        ${self.getBadgeNilai(mk.nilai_huruf)}
                    </td>
                    <td class="text-center">
                        ${(!mk.bobot || mk.bobot === '-')
                ? '-'
                : parseFloat(mk.bobot).toFixed(2)}
                    </td>
                    <td class="text-center">
                        ${tahunParsed.nama}<br>
                        <small class="text-muted">${tahunParsed.semester}</small>
                    </td>
                </tr>`;
        });

        $('#dkn-preview-nilai').html(html);

        var ipk = totalSks > 0 ? (totalBobot / totalSks) : 0;
        $('#dkn-preview-total-sks').text(totalSks);
        $('#dkn-preview-ipk').text(ipk.toFixed(2));
    },

    resetModalDetail: function () {
        // Header
        $('#dkn-no-pengajuan').text('-');
        $('#dkn-status-badge').html('');

        // Info mahasiswa
        $('#dkn-nim').text('-');
        $('#dkn-nama').text('-');
        $('#dkn-prodi').text('-');
        $('#dkn-angkatan').text('-');
        $('#dkn-ipk').text('0.00');
        $('#dkn-predikat').text('-');

        // Info kaprodi
        $('#dkn-nama-kaprodi').text('-');
        $('#dkn-tgl-kaprodi').text('-');
        $('#dkn-catatan-kaprodi').text('-');

        // Detail
        $('#dkn-keperluan').text('-');
        $('#dkn-bahasa').text('-');
        $('#dkn-jumlah-lembar').text('-');
        $('#dkn-email-tujuan').text('-');
        $('#dkn-tgl-ajuan').text('-');
        $('#dkn-catatan').text('-');

        // Step & timeline
        $('#dkn-step-indicator').html('');
        $('#dkn-timeline').html(
            '<div class="text-muted text-center py-3">Tidak ada riwayat</div>'
        );

        // Preview nilai
        $('#dkn-preview-nilai').html(`
            <tr>
                <td colspan="8" class="text-center text-muted py-3">
                    <i class="fas fa-spinner fa-spin mr-2"></i>Memuat data nilai...
                </td>
            </tr>`);
        $('#dkn-preview-total-sks').text(0);
        $('#dkn-preview-ipk').text('0.00');

        // Form tindakan
        $('#dekan-catatan').val('');
        $('#section-tindakan-dekan').hide();
        $('#btn-sahkan-dekan').addClass('d-none');
        $('#btn-tolak-dekan').addClass('d-none');

        // Reset state
        this.data.current_detail = null;
        this.data.current_id     = null;
        this.data.current_no     = null;
        this.data.current_nama   = null;
    },

    // ============================================================
    // PROSES SAHKAN
    // ============================================================

    openKonfirmasiSahkan: function (id, no, nama, catatan) {
        var self = this;

        self.data.current_id   = id;
        self.data.current_no   = no;
        self.data.current_nama = nama;
        self._pendingCatatan   = catatan || '';

        $('#konfirmasi-sahkan-no').text(no    || id);
        $('#konfirmasi-sahkan-nama').text(nama || '-');

        $('#modal-konfirmasi-sahkan').modal('show');
    },

    prosesSahkan: function () {
        var self = this;

        var $btn = $('#btn-konfirmasi-sahkan');
        $btn.prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...');

        $.ajax({
            url: '/dekan/transkrip/sahkan',
            method: 'POST',
            data: {
                _token:       $('meta[name="csrf-token"]').attr('content'),
                id_pengajuan: self.data.current_id,
                catatan:      self._pendingCatatan || ''
            },
            success: function (response) {
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-stamp mr-1"></i>Ya, Sahkan');
                $('#modal-konfirmasi-sahkan').modal('hide');

                if (response && response.status === '1') {
                    $.alert({
                        title: 'Berhasil Disahkan!',
                        content: `
                            <div class="text-center">
                                <i class="fas fa-stamp text-success" style="font-size:3rem;"></i>
                                <p class="mt-3 font-weight-bold">Transkrip berhasil disahkan!</p>
                                <p class="text-muted small">
                                    Pengajuan <strong>${self.data.current_no}</strong>
                                    atas nama <strong>${self.data.current_nama}</strong>
                                    telah disahkan.
                                </p>
                                <p class="text-muted small">
                                    Mahasiswa kini dapat mengunduh transkrip resmi.
                                </p>
                            </div>`,
                        type: 'green',
                        onClose: function () {
                            self.refreshAfterAction();
                        }
                    });
                } else {
                    $.alert({
                        title: 'Gagal',
                        content: response.keterangan || 'Gagal mengesahkan transkrip',
                        type: 'red'
                    });
                }
            },
            error: function (xhr, status, error) {
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-stamp mr-1"></i>Ya, Sahkan');

                var msg = error;
                try {
                    var res = JSON.parse(xhr.responseText);
                    msg = res.keterangan || res.message || error;
                } catch (e) {}

                $.alert({
                    title: 'Error',
                    content: 'Gagal memproses pengesahan: ' + msg,
                    type: 'red'
                });
            }
        });
    },

    // ============================================================
    // PROSES TOLAK
    // ============================================================

    openKonfirmasiTolak: function (id, no, catatan) {
        var self = this;

        self.data.current_id  = id;
        self.data.current_no  = no;
        self._pendingCatatan  = catatan || '';

        $('#konfirmasi-tolak-dekan-no').text(no || id);
        $('#alasan-tolak-dekan-final').val(catatan || '');
        $('#alasan-tolak-dekan-final').removeClass('is-invalid');
        $('#alasan-tolak-dekan-error').hide();

        $('#modal-konfirmasi-tolak-dekan').modal('show');
    },

    prosesTolak: function () {
        var self = this;

        var alasan = $('#alasan-tolak-dekan-final').val().trim();
        if (!alasan) {
            $('#alasan-tolak-dekan-final').addClass('is-invalid');
            $('#alasan-tolak-dekan-error').show();
            return;
        }

        var $btn = $('#btn-konfirmasi-tolak-dekan');
        $btn.prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...');

        $.ajax({
            url: '/dekan/transkrip/tolak',
            method: 'POST',
            data: {
                _token:        $('meta[name="csrf-token"]').attr('content'),
                id_pengajuan:  self.data.current_id,
                alasan_tolak:  alasan
            },
            success: function (response) {
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-ban mr-1"></i>Ya, Tolak');
                $('#modal-konfirmasi-tolak-dekan').modal('hide');

                if (response && response.status === '1') {
                    $.alert({
                        title: 'Pengajuan Ditolak',
                        content: `
                            <div class="text-center">
                                <i class="fas fa-times-circle text-danger" style="font-size:3rem;"></i>
                                <p class="mt-3 font-weight-bold">Pengajuan berhasil ditolak</p>
                                <p class="text-muted small">
                                    Pengajuan <strong>${self.data.current_no}</strong>
                                    telah ditolak oleh Dekan.
                                    Mahasiswa akan diberitahu.
                                </p>
                            </div>`,
                        type: 'red',
                        onClose: function () {
                            self.refreshAfterAction();
                        }
                    });
                } else {
                    $.alert({
                        title: 'Gagal',
                        content: response.keterangan || 'Gagal menolak pengajuan',
                        type: 'red'
                    });
                }
            },
            error: function (xhr, status, error) {
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-ban mr-1"></i>Ya, Tolak');

                var msg = error;
                try {
                    var res = JSON.parse(xhr.responseText);
                    msg = res.keterangan || res.message || error;
                } catch (e) {}

                $.alert({
                    title: 'Error',
                    content: 'Gagal memproses penolakan: ' + msg,
                    type: 'red'
                });
            }
        });
    },

    // ============================================================
    // HELPERS
    // ============================================================

    refreshAfterAction: function () {
        var self = this;

        if (self.data.table_transkrip) {
            self.data.table_transkrip.ajax.reload();
        }
        self.loadStatistik();
        self._pendingCatatan = '';
    },

    getPredikat: function (ipk) {
        if (ipk >= 3.51) return 'Cum Laude';
        if (ipk >= 3.01) return 'Sangat Memuaskan';
        if (ipk >= 2.76) return 'Memuaskan';
        return 'Cukup';
    },

    getBadgeStatus: function (status) {
        if (!status) return '<span class="badge badge-secondary">-</span>';

        var map = {
            'diajukan':       { cls: 'badge-status-diajukan',   label: 'Menunggu Kaprodi', icon: 'fa-clock' },
            'proses_kaprodi': { cls: 'badge-status-kaprodi',    label: 'Proses Kaprodi',   icon: 'fa-user-tie' },
            'proses_dekan':   { cls: 'badge-status-dekan',      label: 'Menunggu Dekan',   icon: 'fa-user-shield' },
            'disetujui':      { cls: 'badge-status-disetujui',  label: 'Disahkan',         icon: 'fa-stamp' },
            'ditolak':        { cls: 'badge-status-ditolak',    label: 'Ditolak',          icon: 'fa-times-circle' },
            'dibatalkan':     { cls: 'badge-status-dibatalkan', label: 'Dibatalkan',       icon: 'fa-ban' }
        };

        var s = map[status];
        if (!s) return `<span class="badge badge-secondary">${status}</span>`;

        return `<span class="badge ${s.cls}"><i class="fas ${s.icon} mr-1"></i>${s.label}</span>`;
    },

    getBadgeNilai: function (nilai) {
        if (!nilai || nilai === '-') return '-';

        var nilaiUpper = nilai.toUpperCase();
        var badgeMap = {
            'A':  'badge-success',
            'AB': 'badge-success',
            'B':  'badge-primary',
            'BC': 'badge-info',
            'C':  'badge-warning',
            'D':  'badge-warning',
            'E':  'badge-danger'
        };

        var cls = badgeMap[nilaiUpper] || 'badge-secondary';
        return `<span class="badge ${cls}">${nilaiUpper}</span>`;
    },

    renderStepIndicator: function (status) {
        var steps = [
            { key: 'diajukan',       label: 'Mahasiswa', icon: 'fa-user' },
            { key: 'proses_kaprodi', label: 'Kaprodi',   icon: 'fa-user-tie' },
            { key: 'proses_dekan',   label: 'Dekan',     icon: 'fa-user-shield' },
            { key: 'disetujui',      label: 'Selesai',   icon: 'fa-check' }
        ];

        var order      = ['diajukan', 'proses_kaprodi', 'proses_dekan', 'disetujui'];
        var currentIdx = order.indexOf(status);
        var isDitolak  = status === 'ditolak';

        var html = '<div class="step-indicator">';

        steps.forEach(function (step, i) {
            var stepIdx = order.indexOf(step.key);
            var cls;

            if (isDitolak) {
                // Tentukan di mana ditolak berdasarkan step
                cls = stepIdx < currentIdx ? 'done'
                    : stepIdx === currentIdx ? 'reject'
                        : '';
            } else if (currentIdx >= stepIdx) {
                cls = 'done';
            } else if (currentIdx === stepIdx - 1) {
                cls = 'active';
            } else {
                cls = '';
            }

            html += `
                <div class="step-item ${cls}">
                    <div class="step-circle">
                        <i class="fas ${step.icon}"></i>
                    </div>
                    <div class="step-label">${step.label}</div>
                </div>`;
        });

        html += '</div>';
        return html;
    },

    renderTimeline: function (riwayat) {
        if (!riwayat || riwayat.length === 0) {
            return `<div class="text-muted text-center py-3">
                        <i class="fas fa-info-circle mr-2"></i>Tidak ada riwayat
                    </div>`;
        }

        var self = this;
        var html = '';

        riwayat.forEach(function (item) {
            var clsMap = {
                'diajukan':       'active',
                'proses_kaprodi': 'warning',
                'proses_dekan':   'purple',
                'disetujui':      'success',
                'ditolak':        'danger',
                'dibatalkan':     'danger'
            };

            var cls     = clsMap[item.status] || '';
            var tanggal = item.tgl_aktivitas
                ? self.formatTanggal(item.tgl_aktivitas, true)
                : '-';

            html += `
                <div class="timeline-item ${cls}">
                    <div class="d-flex justify-content-between">
                        <strong>${item.keterangan || item.status || '-'}</strong>
                        <small class="text-muted">${tanggal}</small>
                    </div>
                    ${item.nama_user
                ? `<small class="text-muted">oleh: ${item.nama_user}</small>`
                : ''}
                    ${item.catatan
                ? `<p class="mb-0 mt-1 small text-muted">${item.catatan}</p>`
                : ''}
                </div>`;
        });

        return html;
    },

    parseTahunAkademik: function (tahunAkademik) {
        var result = { nama: '-', semester: '-' };

        if (!tahunAkademik || tahunAkademik.length < 5) return result;

        var tahun        = tahunAkademik.substring(0, 4);
        var semesterCode = tahunAkademik.substring(4, 5);
        var tahunInt     = parseInt(tahun);

        result.nama = tahunInt + '/' + (tahunInt + 1);

        var semesterMap = { '1': 'Ganjil', '2': 'Genap', '3': 'Antara' };
        result.semester = semesterMap[semesterCode] || 'Ganjil';

        return result;
    },

    formatTanggal: function (tanggal, withTime) {
        if (!tanggal) return '-';

        try {
            if (typeof moment !== 'undefined') {
                var fmt = withTime ? 'D MMM YYYY HH:mm' : 'D MMM YYYY';
                return moment(tanggal).locale('id').format(fmt);
            }

            var d = new Date(tanggal);
            if (isNaN(d.getTime())) return tanggal;

            var months = ['Jan','Feb','Mar','Apr','Mei','Jun',
                'Jul','Agu','Sep','Okt','Nov','Des'];
            var result = d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();

            if (withTime) {
                var hh = String(d.getHours()).padStart(2, '0');
                var mm = String(d.getMinutes()).padStart(2, '0');
                result += ' ' + hh + ':' + mm;
            }

            return result;

        } catch (e) {
            return tanggal;
        }
    }
};

// Initialize when document ready
jQuery(document).ready(function () {
    console.log('Document ready, initializing Transkrip Dekan module...');
    jQuery.transkripDekan.init();
});
