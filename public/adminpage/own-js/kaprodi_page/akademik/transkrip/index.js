jQuery.transkripKaprodi = {
    data: {
        table_transkrip: null,
        filter: {
            status: 'diajukan',
            tahun: '',
            prodi: '',
            search: ''
        },
        statistik: {
            menunggu: 0,
            disetujui: 0,
            ditolak: 0,
            total: 0
        },
        current_detail: null,
        current_id: null,
        current_no: null
    },

    init: function () {
        var self = this;

        console.log('Initializing Transkrip Kaprodi module...');

        if (!$('#table-transkrip-kaprodi').length) {
            console.error('Table #table-transkrip-kaprodi tidak ditemukan!');
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
            url: '/kaprodi/transkrip/prodi-list',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Prodi list:', response);

                if (response && Array.isArray(response)) {
                    var options = '<option value="">-- Semua Prodi --</option>';
                    response.forEach(function (item) {
                        options += `<option value="${item.id}">${item.nama}</option>`;
                    });
                    $('#filter-prodi').html(options);

                    // Re-init select2 setelah options diisi
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

        if ($.fn.DataTable.isDataTable('#table-transkrip-kaprodi')) {
            $('#table-transkrip-kaprodi').DataTable().clear().destroy();
        }

        $('#table-transkrip-kaprodi tbody').empty();

        try {
            self.data.table_transkrip = $('#table-transkrip-kaprodi').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: '/kaprodi/transkrip/json',
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
                        console.log('Received transkrip kaprodi data:', json);

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
                            return `<strong class="text-primary">${data || '-'}</strong>`;
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
                        width: '18%',
                        defaultContent: '-'
                    },
                    // Keperluan
                    {
                        data: 'keperluan',
                        searchable: true,
                        className: 'text-left',
                        width: '14%',
                        defaultContent: '-'
                    },
                    // Bahasa
                    {
                        data: 'bahasa',
                        searchable: false,
                        className: 'text-center',
                        width: '8%',
                        render: function (data) {
                            if (!data || data === '-') return '-';
                            var cls = data === 'Inggris' ? 'badge-info' : 'badge-secondary';
                            return `<span class="badge ${cls}">${data}</span>`;
                        }
                    },
                    // Tanggal Ajuan
                    {
                        data: 'tgl_pengajuan',
                        searchable: false,
                        className: 'text-center',
                        width: '10%',
                        render: function (data) {
                            if (!data) return '-';
                            return self.formatTanggal(data);
                        }
                    },
                    // Status
                    {
                        data: 'status',
                        searchable: false,
                        className: 'text-center',
                        width: '11%',
                        render: function (data) {
                            return self.getBadgeStatus(data);
                        }
                    },
                    // Aksi
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: '12%',
                        render: function (data) {
                            if (!data) return '-';

                            var btnDetail = `
                                <button class="btn btn-info btn-sm btn-detail-kaprodi"
                                        data-id="${data.id_pengajuan || ''}"
                                        title="Lihat Detail">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </button>`;

                            var btnSetujui = '';
                            var btnTolak   = '';

                            if (data.status === 'diajukan') {
                                btnSetujui = `
                                    <button class="btn btn-success btn-sm btn-quick-setujui mt-1"
                                            data-id="${data.id_pengajuan || ''}"
                                            data-no="${data.no_pengajuan || ''}"
                                            title="Setujui">
                                        <i class="fas fa-check mr-1"></i>Setujui
                                    </button>`;
                                btnTolak = `
                                    <button class="btn btn-danger btn-sm btn-quick-tolak mt-1"
                                            data-id="${data.id_pengajuan || ''}"
                                            data-no="${data.no_pengajuan || ''}"
                                            title="Tolak">
                                        <i class="fas fa-times mr-1"></i>Tolak
                                    </button>`;
                            }

                            return `
                                <div class="d-flex flex-column align-items-center">
                                    ${btnDetail}
                                    <div class="d-flex" style="gap:4px;">
                                        ${btnSetujui}${btnTolak}
                                    </div>
                                </div>`;
                        }
                    }
                ],
                rowCallback: function (row, data) {
                    // Highlight baris yang menunggu persetujuan kaprodi
                    if (data.status === 'diajukan') {
                        $(row).addClass('row-menunggu');
                    }
                },
                drawCallback: function (settings) {
                    var api  = this.api();
                    var data = api.rows().data().toArray();

                    if (data.length === 0) {
                        $('#table-transkrip-kaprodi tbody').html(`
                            <tr>
                                <td colspan="9" class="text-center">
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

                    // Update badge filter aktif
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

            console.log('DataTable Transkrip Kaprodi initialized successfully');

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
        $(document).off('click', '.btn-detail-kaprodi').on('click', '.btn-detail-kaprodi', function (e) {
            e.stopPropagation();
            var id = $(this).data('id');
            self.loadDetail(id);
        });

        // --- Quick Setujui (dari tombol di tabel) ---
        $(document).off('click', '.btn-quick-setujui').on('click', '.btn-quick-setujui', function (e) {
            e.stopPropagation();
            var id = $(this).data('id');
            var no = $(this).data('no');
            self.openKonfirmasiSetujui(id, no, '');
        });

        // --- Quick Tolak (dari tombol di tabel) ---
        $(document).off('click', '.btn-quick-tolak').on('click', '.btn-quick-tolak', function (e) {
            e.stopPropagation();
            var id = $(this).data('id');
            var no = $(this).data('no');
            self.openKonfirmasiTolak(id, no, '');
        });

        // --- Setujui dari modal detail ---
        $('#btn-setujui-kaprodi').off('click').on('click', function () {
            if (!self.data.current_id) return;
            var catatan = $('#kaprodi-catatan').val().trim();
            $('#modal-detail-kaprodi').modal('hide');
            self.openKonfirmasiSetujui(
                self.data.current_id,
                self.data.current_no,
                catatan
            );
        });

        // --- Tolak dari modal detail ---
        $('#btn-tolak-kaprodi').off('click').on('click', function () {
            if (!self.data.current_id) return;
            var catatan = $('#kaprodi-catatan').val().trim();
            $('#modal-detail-kaprodi').modal('hide');
            self.openKonfirmasiTolak(
                self.data.current_id,
                self.data.current_no,
                catatan
            );
        });

        // --- Konfirmasi Setujui ---
        $('#btn-konfirmasi-setujui').off('click').on('click', function () {
            self.prosesSetujui();
        });

        // --- Konfirmasi Tolak ---
        $('#btn-konfirmasi-tolak').off('click').on('click', function () {
            self.prosesTolak();
        });

        // --- Reset modal detail saat ditutup ---
        $('#modal-detail-kaprodi').on('hidden.bs.modal', function () {
            self.resetModalDetail();
        });

        // --- Reset modal tolak saat ditutup ---
        $('#modal-konfirmasi-tolak').on('hidden.bs.modal', function () {
            $('#alasan-tolak-final').val('').removeClass('is-invalid');
            $('#alasan-tolak-error').hide();
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

        console.log('Applying filter:', self.data.filter);

        if (self.data.table_transkrip) {
            self.data.table_transkrip.ajax.reload();
        }
    },

    resetFilter: function () {
        var self = this;

        $('#filter-status').val('diajukan').trigger('change');
        $('#filter-tahun').val('').trigger('change');
        $('#filter-prodi').val('').trigger('change');
        $('#filter-search').val('');

        self.data.filter = {
            status: 'diajukan',
            tahun: '',
            prodi: '',
            search: ''
        };

        if (self.data.table_transkrip) {
            self.data.table_transkrip.ajax.reload();
        }
    },

    updateBadgeFilterAktif: function () {
        var self   = this;
        var f      = self.data.filter;
        var aktif  = f.status || f.tahun || f.prodi || f.search;

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
            url: '/kaprodi/transkrip/statistik',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Statistik kaprodi:', response);

                if (response) {
                    $('#stat-menunggu').text(response.menunggu  || 0);
                    $('#stat-disetujui').text(response.disetujui || 0);
                    $('#stat-ditolak').text(response.ditolak    || 0);
                    $('#stat-total').text(response.total        || 0);

                    self.data.statistik = {
                        menunggu:  response.menunggu  || 0,
                        disetujui: response.disetujui || 0,
                        ditolak:   response.ditolak   || 0,
                        total:     response.total      || 0
                    };
                }
            },
            error: function (xhr, status, error) {
                console.warn('Gagal memuat statistik kaprodi:', error);
            }
        });
    },

    loadDetail: function (id) {
        var self = this;

        if (!id) {
            $.alert({ title: 'Error', content: 'ID pengajuan tidak valid', type: 'red' });
            return;
        }

        // Reset & buka modal dulu
        self.resetModalDetail();
        $('#modal-detail-kaprodi').modal('show');

        $.ajax({
            url: '/kaprodi/transkrip/detail',
            method: 'POST',
            data: {
                _token:       $('meta[name="csrf-token"]').attr('content'),
                id_pengajuan: id
            },
            success: function (response) {
                console.log('Detail pengajuan:', response);

                if (response && response.status === '1') {
                    self.data.current_detail = response.data;
                    self.data.current_id     = response.data.id_pengajuan;
                    self.data.current_no     = response.data.no_pengajuan;

                    self.renderModalDetail(response.data);
                    self.loadPreviewNilai(response.data.nim);
                } else {
                    $('#modal-detail-kaprodi').modal('hide');
                    $.alert({
                        title: 'Error',
                        content: response.keterangan || 'Data tidak ditemukan',
                        type: 'red'
                    });
                }
            },
            error: function (xhr, status, error) {
                $('#modal-detail-kaprodi').modal('hide');
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
            $('#kpd-preview-nilai').html(`
                <tr>
                    <td colspan="8" class="text-center text-muted py-3">NIM tidak valid</td>
                </tr>`);
            return;
        }

        $.ajax({
            url: '/kaprodi/transkrip/preview-nilai',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                nim: nim
            },
            success: function (response) {
                console.log('Preview nilai:', response);

                if (response && response.status === '1' && Array.isArray(response.data)) {
                    self.renderPreviewNilai(response.data);
                } else {
                    $('#kpd-preview-nilai').html(`
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">
                                Tidak ada data nilai
                            </td>
                        </tr>`);
                }
            },
            error: function (xhr, status, error) {
                $('#kpd-preview-nilai').html(`
                    <tr>
                        <td colspan="8" class="text-center text-danger py-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Gagal memuat preview nilai
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
        $('#kpd-no-pengajuan').text(data.no_pengajuan || '-');
        $('#kpd-status-badge').html(self.getBadgeStatus(data.status));

        // Info mahasiswa
        $('#kpd-nim').text(data.nim             || '-');
        $('#kpd-nama').text(data.nama_mahasiswa  || '-');
        $('#kpd-prodi').text(data.nama_prodi     || '-');
        $('#kpd-ipk').text(parseFloat(data.ipk || 0).toFixed(2));

        // Detail pengajuan
        $('#kpd-keperluan').text(data.keperluan              || '-');
        $('#kpd-bahasa').text(data.bahasa                    || '-');
        $('#kpd-jumlah-lembar').text((data.jumlah_lembar || 0) + ' lembar');
        $('#kpd-email-tujuan').text(data.email_tujuan        || '-');
        $('#kpd-tgl-ajuan').text(self.formatTanggal(data.tgl_pengajuan));
        $('#kpd-catatan').text(data.catatan                  || '-');

        // Step indicator
        $('#kpd-step-indicator').html(self.renderStepIndicator(data.status));

        // Timeline
        $('#kpd-timeline').html(self.renderTimeline(data.riwayat || []));

        // Tombol & form tindakan — hanya tampil jika status = diajukan
        if (data.status === 'diajukan') {
            $('#section-tindakan-kaprodi').show();
            $('#btn-setujui-kaprodi').removeClass('d-none');
            $('#btn-tolak-kaprodi').removeClass('d-none');
        } else {
            $('#section-tindakan-kaprodi').hide();
            $('#btn-setujui-kaprodi').addClass('d-none');
            $('#btn-tolak-kaprodi').addClass('d-none');
        }
    },

    renderPreviewNilai: function (data) {
        var self = this;

        if (!data || data.length === 0) {
            $('#kpd-preview-nilai').html(`
                <tr>
                    <td colspan="8" class="text-center text-muted py-3">
                        Tidak ada data nilai
                    </td>
                </tr>`);
            $('#kpd-preview-total-sks').text(0);
            $('#kpd-preview-ipk').text('0.00');
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

        $('#kpd-preview-nilai').html(html);

        var ipk = totalSks > 0 ? (totalBobot / totalSks) : 0;
        $('#kpd-preview-total-sks').text(totalSks);
        $('#kpd-preview-ipk').text(ipk.toFixed(2));
    },

    resetModalDetail: function () {
        // Header
        $('#kpd-no-pengajuan').text('-');
        $('#kpd-status-badge').html('');

        // Info mahasiswa
        $('#kpd-nim').text('-');
        $('#kpd-nama').text('-');
        $('#kpd-prodi').text('-');
        $('#kpd-ipk').text('-');

        // Detail
        $('#kpd-keperluan').text('-');
        $('#kpd-bahasa').text('-');
        $('#kpd-jumlah-lembar').text('-');
        $('#kpd-email-tujuan').text('-');
        $('#kpd-tgl-ajuan').text('-');
        $('#kpd-catatan').text('-');

        // Step & timeline
        $('#kpd-step-indicator').html('');
        $('#kpd-timeline').html('<div class="text-muted text-center py-3">Tidak ada riwayat</div>');

        // Preview nilai
        $('#kpd-preview-nilai').html(`
            <tr>
                <td colspan="8" class="text-center text-muted py-3">
                    <i class="fas fa-spinner fa-spin mr-2"></i>Memuat data nilai...
                </td>
            </tr>`);
        $('#kpd-preview-total-sks').text(0);
        $('#kpd-preview-ipk').text('0.00');

        // Form tindakan
        $('#kaprodi-catatan').val('');
        $('#section-tindakan-kaprodi').hide();
        $('#btn-setujui-kaprodi').addClass('d-none');
        $('#btn-tolak-kaprodi').addClass('d-none');

        // Reset state
        this.data.current_detail = null;
        this.data.current_id     = null;
        this.data.current_no     = null;
    },

    // ============================================================
    // PROSES SETUJUI
    // ============================================================

    openKonfirmasiSetujui: function (id, no, catatan) {
        var self = this;

        self.data.current_id  = id;
        self.data.current_no  = no;

        $('#konfirmasi-no-pengajuan').text(no || id);

        // Simpan catatan dari modal detail ke tempat sementara
        self._pendingCatatan = catatan || '';

        $('#modal-konfirmasi-setujui').modal('show');
    },

    prosesSetujui: function () {
        var self = this;

        var $btn = $('#btn-konfirmasi-setujui');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...');

        $.ajax({
            url: '/kaprodi/transkrip/setujui',
            method: 'POST',
            data: {
                _token:       $('meta[name="csrf-token"]').attr('content'),
                id_pengajuan: self.data.current_id,
                catatan:      self._pendingCatatan || ''
            },
            success: function (response) {
                $btn.prop('disabled', false).html('<i class="fas fa-check mr-1"></i>Ya, Setujui');
                $('#modal-konfirmasi-setujui').modal('hide');

                if (response && response.status === '1') {
                    $.alert({
                        title: 'Berhasil!',
                        content: `
                            <div class="text-center">
                                <i class="fas fa-check-circle text-success" style="font-size:3rem;"></i>
                                <p class="mt-3 font-weight-bold">Pengajuan berhasil disetujui!</p>
                                <p class="text-muted small">
                                    Pengajuan <strong>${self.data.current_no}</strong>
                                    telah diteruskan ke Dekan untuk persetujuan selanjutnya.
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
                        content: response.keterangan || 'Gagal menyetujui pengajuan',
                        type: 'red'
                    });
                }
            },
            error: function (xhr, status, error) {
                $btn.prop('disabled', false).html('<i class="fas fa-check mr-1"></i>Ya, Setujui');

                var msg = error;
                try {
                    var res = JSON.parse(xhr.responseText);
                    msg = res.keterangan || res.message || error;
                } catch (e) {}

                $.alert({
                    title: 'Error',
                    content: 'Gagal memproses persetujuan: ' + msg,
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

        $('#konfirmasi-tolak-no').text(no || id);
        $('#alasan-tolak-final').val(catatan || '');
        $('#alasan-tolak-final').removeClass('is-invalid');
        $('#alasan-tolak-error').hide();

        $('#modal-konfirmasi-tolak').modal('show');
    },

    prosesTolak: function () {
        var self = this;

        // Validasi alasan tolak wajib diisi
        var alasan = $('#alasan-tolak-final').val().trim();
        if (!alasan) {
            $('#alasan-tolak-final').addClass('is-invalid');
            $('#alasan-tolak-error').show();
            return;
        }

        var $btn = $('#btn-konfirmasi-tolak');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...');

        $.ajax({
            url: '/kaprodi/transkrip/tolak',
            method: 'POST',
            data: {
                _token:        $('meta[name="csrf-token"]').attr('content'),
                id_pengajuan:  self.data.current_id,
                alasan_tolak:  alasan
            },
            success: function (response) {
                $btn.prop('disabled', false).html('<i class="fas fa-ban mr-1"></i>Ya, Tolak');
                $('#modal-konfirmasi-tolak').modal('hide');

                if (response && response.status === '1') {
                    $.alert({
                        title: 'Pengajuan Ditolak',
                        content: `
                            <div class="text-center">
                                <i class="fas fa-times-circle text-danger" style="font-size:3rem;"></i>
                                <p class="mt-3 font-weight-bold">Pengajuan berhasil ditolak</p>
                                <p class="text-muted small">
                                    Pengajuan <strong>${self.data.current_no}</strong>
                                    telah ditolak dan mahasiswa akan diberitahu.
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
                $btn.prop('disabled', false).html('<i class="fas fa-ban mr-1"></i>Ya, Tolak');

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

    getBadgeStatus: function (status) {
        if (!status) return '<span class="badge badge-secondary">-</span>';

        var map = {
            'diajukan':       { cls: 'badge-status-diajukan',   label: 'Menunggu Kaprodi', icon: 'fa-clock' },
            'proses_kaprodi': { cls: 'badge-status-kaprodi',    label: 'Proses Kaprodi',   icon: 'fa-user-tie' },
            'proses_dekan':   { cls: 'badge-status-dekan',      label: 'Proses Dekan',     icon: 'fa-user-shield' },
            'disetujui':      { cls: 'badge-status-disetujui',  label: 'Disetujui',        icon: 'fa-check-circle' },
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

            if (isDitolak && i <= 1) {
                cls = i === 1 ? 'reject' : 'done';
            } else if (!isDitolak && currentIdx >= stepIdx) {
                cls = 'done';
            } else if (!isDitolak && currentIdx === stepIdx - 1) {
                cls = 'active';
            } else {
                cls = '';
            }

            html += `
                <div class="step-item ${cls}">
                    <div class="step-circle"><i class="fas ${step.icon}"></i></div>
                    <div class="step-label">${step.label}</div>
                </div>`;
        });

        html += '</div>';
        return html;
    },

    renderTimeline: function (riwayat) {
        if (!riwayat || riwayat.length === 0) {
            return '<div class="text-muted text-center py-3"><i class="fas fa-info-circle mr-2"></i>Tidak ada riwayat</div>';
        }

        var self = this;
        var html = '';

        riwayat.forEach(function (item) {
            var clsMap = {
                'diajukan':       'active',
                'proses_kaprodi': 'warning',
                'proses_dekan':   'warning',
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

        var tahun         = tahunAkademik.substring(0, 4);
        var semesterCode  = tahunAkademik.substring(4, 5);
        var tahunInt      = parseInt(tahun);

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

            var months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
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
    console.log('Document ready, initializing Transkrip Kaprodi module...');
    jQuery.transkripKaprodi.init();
});
