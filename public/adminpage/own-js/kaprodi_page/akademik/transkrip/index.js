jQuery.transkripKaprodi = {
    data: {
        table_transkrip: null,
        filter: {
            status: '2',
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
        current_no: null,
        current_nama: null
    },

    init: function () {
        var self = this;

        if (!$('#table-transkrip-kaprodi').length) {
            return;
        }

        self.initSelect2();
        self.loadProdiList();
        self.initDataTable();
        self.setEvents();
        self.loadStatistik();
    },

    // ============================================================
    // LOADING OVERLAY
    // ============================================================

    showLoading: function (text) {
        $('#global-loading-text').text(text || 'Memuat data...');
        $('#global-loading').fadeIn(150);
    },

    hideLoading: function () {
        $('#global-loading').fadeOut(200);
    },

    // ============================================================
    // INISIALISASI
    // ============================================================

    initSelect2: function () {
        if (typeof $.fn.select2 !== 'undefined') {
            $(".select2").select2({ width: '100%', theme: 'bootstrap4' });
        }
    },

    loadProdiList: function () {
        var self = this;

        $.ajax({
            url: '/kaprodi/transkrip/prodi-list',
            method: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                if (response && Array.isArray(response)) {
                    var options = '<option value="">-- Semua Prodi --</option>';
                    response.forEach(function (item) {
                        options += `<option value="${item.id}">${item.nama}</option>`;
                    });
                    $('#filter-prodi').html(options);

                    if (typeof $.fn.select2 !== 'undefined') {
                        $('#filter-prodi').select2({ width: '100%', theme: 'bootstrap4' });
                    }
                }
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
                        d.tahun = self.data.filter.tahun;
                        d.prodi = self.data.filter.prodi;
                        d.search = self.data.filter.search;
                        return d;
                    },
                    dataSrc: function (json) {
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
                        json.recordsTotal = json.recordsTotal || (json.data ? json.data.length : 0);
                        json.recordsFiltered = json.recordsFiltered || json.recordsTotal;
                        json.draw = json.draw || 1;
                        return json.data || [];
                    },
                    error: function (xhr, error, thrown) {
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
                        data: 'nomor', searchable: false,
                        className: 'text-center', width: '4%',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    // No. Pengajuan
                    {
                        data: 'nomor_pengajuan', searchable: true,
                        className: 'text-left', width: '14%',
                        render: function (data, type, row) {
                            var noPengajuan = data || '-';
                            var idShort = row.id_riwayat_pengajuan_nilai
                                ? row.id_riwayat_pengajuan_nilai.substring(0, 8).toUpperCase()
                                : '-';
                            return `<strong class="text-primary d-block">${noPengajuan}</strong>
                                    <small class="text-muted">${idShort}</small>`;
                        }
                    },
                    // NIM
                    {
                        data: 'nim', searchable: true,
                        className: 'text-left', width: '10%',
                        render: function (data) {
                            return `<code>${data || '-'}</code>`;
                        }
                    },
                    // Nama Mahasiswa
                    {
                        data: 'nama_mahasiswa', searchable: true,
                        className: 'text-left', width: '20%',
                        defaultContent: '-'
                    },
                    // Program Studi
                    {
                        data: 'nama_prodi', searchable: true,
                        className: 'text-left', width: '15%',
                        render: function (data) {
                            return data ? `<small>${data}</small>` : '-';
                        }
                    },
                    // Keperluan
                    {
                        data: 'keperluan', searchable: true,
                        className: 'text-left', width: '14%',
                        defaultContent: '-'
                    },
                    // Tanggal Ajuan
                    {
                        data: 'tgl_created', searchable: false,
                        className: 'text-center', width: '10%',
                        render: function (data) { return data || '-'; }
                    },
                    // Status
                    {
                        data: 'status', searchable: false,
                        className: 'text-center', width: '10%',
                        render: function (data, type, row) {
                            return self.getBadgeStatus(data, row.keterangan_status);
                        }
                    },
                    // Aksi — hanya tombol Detail
                    {
                        data: null, searchable: false,
                        className: 'text-center', width: '8%',
                        render: function (data) {
                            if (!data) return '-';
                            var id = data.id_riwayat_pengajuan_nilai || '';
                            var idPengajuanInduk = data.id_pengajuan_induk || '';
                            return `
                                <button class="btn btn-info btn-sm btn-detail-kaprodi"
                                        data-id="${idPengajuanInduk}" title="Lihat Detail">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </button>`;
                        }
                    }
                ],
                rowCallback: function (row, data) {
                    if (String(data.status) === '2') {
                        $(row).addClass('row-menunggu');
                    }
                },
                drawCallback: function (settings) {
                    var api = this.api();
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
                            </tr>`);
                    }
                    self.updateBadgeFilterAktif();
                },
                paging: true,
                processing: true,
                pageLength: 10,
                ordering: false,
                lengthChange: true,
                lengthMenu: [[10, 25, 50], [10, 25, 50]],
                autoWidth: false,
                sDom: 'ltipr',
                language: {
                    emptyTable: "Tidak ada data pengajuan transkrip",
                    processing: "<i class='fas fa-spinner fa-spin mr-2'></i>Sedang memuat data...",
                    zeroRecords: "Tidak ditemukan data yang sesuai",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    lengthMenu: "Tampilkan _MENU_ data",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        } catch (e) {
            $.alert({ title: 'Error', content: 'Gagal inisialisasi tabel: ' + e.message, type: 'red' });
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

        // --- Buka detail ---
        $(document).off('click', '.btn-detail-kaprodi')
            .on('click', '.btn-detail-kaprodi', function (e) {
                e.stopPropagation();
                var id = $(this).data('id');
                self.loadDetail(id);
            });

        // --- Setujui dari modal detail ---
        // Gunakan $(document).on agar tidak terpengaruh re-render DOM
        $(document).off('click', '#btn-setujui-kaprodi')
            .on('click', '#btn-setujui-kaprodi', function () {
                console.log('[Kaprodi] Setujui diklik, current_id:', self.data.current_id);

                if (!self.data.current_id) {
                    $.alert({ title: 'Error', content: 'ID pengajuan tidak ditemukan.', type: 'red' });
                    return;
                }
                var catatan = $('#kaprodi-catatan').val().trim();
                $('#modal-detail-kaprodi').modal('hide');
                self.openKonfirmasiSetujui(
                    self.data.current_id,
                    self.data.current_no,
                    self.data.current_nama,
                    catatan
                );
            });

        // --- Tolak dari modal detail ---
        $(document).off('click', '#btn-tolak-kaprodi')
            .on('click', '#btn-tolak-kaprodi', function () {
                console.log('[Kaprodi] Tolak diklik, current_id:', self.data.current_id);

                if (!self.data.current_id) {
                    $.alert({ title: 'Error', content: 'ID pengajuan tidak ditemukan.', type: 'red' });
                    return;
                }
                var catatan = $('#kaprodi-catatan').val().trim();
                $('#modal-detail-kaprodi').modal('hide');
                self.openKonfirmasiTolak(
                    self.data.current_id,
                    self.data.current_no,
                    self.data.current_nama,
                    catatan
                );
            });

        // --- Eksekusi Setujui ---
        $('#btn-konfirmasi-setujui').off('click').on('click', function () {
            self.prosesSetujui();
        });

        // --- Eksekusi Tolak ---
        $('#btn-konfirmasi-tolak').off('click').on('click', function () {
            self.prosesTolak();
        });

        // --- Reset modal detail saat benar-benar ditutup ---
        $('#modal-detail-kaprodi').off('hidden.bs.modal').on('hidden.bs.modal', function () {
            // Hanya reset jika tidak sedang menunggu konfirmasi
            if (!$('#modal-konfirmasi-setujui').hasClass('show') &&
                !$('#modal-konfirmasi-tolak').hasClass('show')) {
                self.resetModalDetail();
            }
        });

        // --- Reset modal tolak saat ditutup ---
        $('#modal-konfirmasi-tolak').off('hidden.bs.modal').on('hidden.bs.modal', function () {
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
        self.data.filter.tahun = $('#filter-tahun').val();
        self.data.filter.prodi = $('#filter-prodi').val();
        self.data.filter.search = $('#filter-search').val().trim();

        if (self.data.table_transkrip) {
            self.data.table_transkrip.ajax.reload();
        }
    },

    resetFilter: function () {
        var self = this;
        $('#filter-status').val('2').trigger('change');
        $('#filter-tahun').val('').trigger('change');
        $('#filter-prodi').val('').trigger('change');
        $('#filter-search').val('');

        self.data.filter = { status: '2', tahun: '', prodi: '', search: '' };

        if (self.data.table_transkrip) {
            self.data.table_transkrip.ajax.reload();
        }
    },

    updateBadgeFilterAktif: function () {
        var f = this.data.filter;
        var aktif = f.tahun || f.prodi || f.search || (f.status && f.status !== '2');
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
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                if (response) {
                    $('#stat-menunggu').text(response.menunggu || 0);
                    $('#stat-disetujui').text(response.disetujui || 0);
                    $('#stat-ditolak').text(response.ditolak || 0);
                    $('#stat-total').text(response.total || 0);
                }
            },
            error: function (xhr, status, error) {
                console.warn('Gagal memuat statistik kaprodi:', error);
            }
        });
    },

    /**
     * Modal detail HANYA dibuka setelah AJAX selesai.
     * Selama proses AJAX, tampil loading overlay.
     */
    loadDetail: function (id) {
        var self = this;

        if (!id) {
            $.alert({ title: 'Error', content: 'ID pengajuan tidak valid', type: 'red' });
            return;
        }

        self.showLoading('Memuat detail pengajuan...');

        $.ajax({
            url: '/kaprodi/transkrip/detail',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id_riwayat_pengajuan_nilai: id
            },
            success: function (response) {
                self.hideLoading();

                if (response && response.status === '1') {
                    // 1. Set state DULU sebelum apapun
                    self.data.current_detail = response.data;
                    self.data.current_id = response.data.id_riwayat_pengajuan_nilai;
                    self.data.current_no = response.data.nomor_pengajuan;
                    self.data.current_nama = response.data.nama_mahasiswa;

                    // 2. Reset UI saja (tanpa null-kan state)
                    self.resetModalUI();

                    // 3. Render data ke modal
                    self.renderModalDetail(response.data);

                    // 4. Buka modal
                    $('#modal-detail-kaprodi').modal('show');

                    // 5. Load preview nilai
                    self.loadPreviewNilai(response.data.nim);
                } else {
                    $.alert({
                        title: 'Error',
                        content: response.keterangan || 'Data tidak ditemukan',
                        type: 'red'
                    });
                }
            },
            error: function (xhr, status, error) {
                self.hideLoading();
                $.alert({
                    title: 'Error',
                    content: 'Gagal memuat detail: ' + error,
                    type: 'red'
                });
            }
        });
    },

    resetModalUI: function () {
        $('#kpd-no-pengajuan').text('-');
        $('#kpd-id-pengajuan').text('-');
        $('#kpd-status-badge').html('');
        $('#kpd-nim').text('-');
        $('#kpd-nama').text('-');
        $('#kpd-prodi').text('-');
        $('#kpd-ipk').text('-');
        $('#kpd-keperluan').text('-');
        $('#kpd-email-tujuan').text('-');
        $('#kpd-tgl-ajuan').text('-');
        $('#kpd-tgl-updated').text('-');
        $('#kpd-catatan-mhs').text('-');
        $('#kpd-step-indicator').html('');
        $('#kpd-timeline').html(
            '<div class="text-muted text-center py-3">Tidak ada riwayat</div>'
        );
        $('#kpd-preview-nilai').html(`
            <tr>
                <td colspan="8" class="text-center text-muted py-3">
                    <i class="fas fa-spinner fa-spin mr-2"></i>Memuat data nilai...
                </td>
            </tr>`);
        $('#kpd-preview-total-sks').text(0);
        $('#kpd-preview-ipk').text('0.00');
        $('#kaprodi-catatan').val('');
        $('#section-tindakan-kaprodi').hide();
        $('#btn-setujui-kaprodi').addClass('d-none');
        $('#btn-tolak-kaprodi').addClass('d-none');
    },

    loadPreviewNilai: function (nim) {
        var self = this;

        if (!nim) {
            $('#kpd-preview-nilai').html(
                '<tr><td colspan="8" class="text-center text-muted py-3">NIM tidak valid</td></tr>'
            );
            return;
        }

        // Tampilkan spinner di tabel nilai (tidak pakai overlay global agar modal tetap terlihat)
        $('#kpd-preview-nilai').html(`
            <tr>
                <td colspan="8" class="text-center text-muted py-3">
                    <i class="fas fa-spinner fa-spin mr-2"></i>Memuat data nilai...
                </td>
            </tr>`);

        $.ajax({
            url: '/kaprodi/transkrip/preview-nilai',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                nim: nim
            },
            success: function (response) {
                if (response && response.status === '1' && Array.isArray(response.data)) {
                    self.renderPreviewNilai(response.data);
                } else {
                    $('#kpd-preview-nilai').html(
                        '<tr><td colspan="8" class="text-center text-muted py-3">Tidak ada data nilai</td></tr>'
                    );
                }
            },
            error: function () {
                $('#kpd-preview-nilai').html(`
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
        $('#kpd-no-pengajuan').text(data.nomor_pengajuan || '-');
        $('#kpd-id-pengajuan').text(data.id_riwayat_pengajuan_nilai || '-');
        $('#kpd-status-badge').html(self.getBadgeStatus(data.status, data.keterangan_status));

        // Info mahasiswa
        $('#kpd-nim').text(data.nim || '-');
        $('#kpd-nama').text(data.nama_mahasiswa || '-');
        $('#kpd-prodi').text(data.nama_prodi || '-');
        $('#kpd-ipk').text(data.ipk ? parseFloat(data.ipk).toFixed(2) : '-');

        // Detail pengajuan
        $('#kpd-keperluan').text(data.keperluan || '-');
        $('#kpd-email-tujuan').text(data.email_tujuan || '-');
        $('#kpd-tgl-ajuan').text(data.tgl_created || '-');
        $('#kpd-tgl-updated').text(data.tgl_updated || '-');
        $('#kpd-catatan-mhs').text(data.catatan || '-');

        // Parse riwayat — bisa string JSON atau array
        var riwayat = [];
        if (data.riwayat) {
            if (typeof data.riwayat === 'string') {
                try { riwayat = JSON.parse(data.riwayat); } catch (e) { riwayat = []; }
            } else if (Array.isArray(data.riwayat)) {
                riwayat = data.riwayat;
            }
        }

        // Step indicator & timeline
        $('#kpd-step-indicator').html(self.renderStepIndicator(data.status));
        $('#kpd-timeline').html(self.renderTimeline(riwayat));

        // Tombol tindakan — hanya tampil jika status = 2 (Diajukan)
        if (String(data.status) === '2') {
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
            $('#kpd-preview-nilai').html(
                '<tr><td colspan="8" class="text-center text-muted py-3">Tidak ada data nilai</td></tr>'
            );
            $('#kpd-preview-total-sks').text(0);
            $('#kpd-preview-ipk').text('0.00');
            return;
        }

        var html = '';
        var totalSks = 0;
        var totalBobot = 0;

        data.forEach(function (mk, i) {
            var sks = parseInt(mk.sks || 0);
            var bobot = parseFloat(mk.bobot || 0);
            totalSks += sks;
            totalBobot += (bobot * sks);

            var tahunParsed = self.parseTahunAkademik(mk.tahun_akademik);

            html += `
                <tr>
                    <td class="text-center">${i + 1}</td>
                    <td>${mk.kd_matakuliah || mk.kd_mata_kuliah || '-'}</td>
                    <td>${mk.matakuliah || mk.nama_mata_kuliah || '-'}</td>
                    <td class="text-center">${sks}</td>
                    <td class="text-center">
                        ${(!mk.nilai_angka || mk.nilai_angka === '-')
                    ? '-' : parseFloat(mk.nilai_angka).toFixed(2)}
                    </td>
                    <td class="text-center">${self.getBadgeNilai(mk.nilai_huruf)}</td>
                    <td class="text-center">
                        ${(!mk.bobot || mk.bobot === '-')
                    ? '-' : parseFloat(mk.bobot).toFixed(2)}
                    </td>
                    <td class="text-center">
                        ${tahunParsed.nama}<br>
                        <small class="text-muted">${tahunParsed.semester}</small>
                    </td>
                </tr>`;
        });

        var ipk = totalSks > 0 ? (totalBobot / totalSks) : 0;
        $('#kpd-preview-nilai').html(html);
        $('#kpd-preview-total-sks').text(totalSks);
        $('#kpd-preview-ipk').text(ipk.toFixed(2));
    },

    resetModalDetail: function () {
        this.resetModalUI();

        this.data.current_detail = null;
        this.data.current_id = null;
        this.data.current_no = null;
        this.data.current_nama = null;
    },


    // ============================================================
    // PROSES SETUJUI
    // ============================================================

    openKonfirmasiSetujui: function (id, no, nama, catatan) {
        var self = this;
        self.data.current_id = id;
        self.data.current_no = no;
        self.data.current_nama = nama;
        self._pendingCatatan = catatan || '';

        $('#konfirmasi-no-pengajuan').text(no || id);
        $('#konfirmasi-nama-mahasiswa').text(nama || '-');

        $('#modal-konfirmasi-setujui').modal('show');
    },

    prosesSetujui: function () {
        var self = this;
        var $btn = $('#btn-konfirmasi-setujui');

        $btn.prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...');

        self.showLoading('Menyetujui pengajuan...');

        $.ajax({
            url: '/kaprodi/transkrip/setujui',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id_riwayat_pengajuan_nilai: self.data.current_id,
                catatan: self._pendingCatatan || ''
            },
            success: function (response) {
                self.hideLoading();
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-check mr-1"></i>Ya, Setujui');
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
                                    atas nama <strong>${self.data.current_nama}</strong>
                                    telah diteruskan ke Dekan.
                                </p>
                            </div>`,
                        type: 'green',
                        onClose: function () { self.refreshAfterAction(); }
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
                self.hideLoading();
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-check mr-1"></i>Ya, Setujui');

                var msg = error;
                try { var res = JSON.parse(xhr.responseText); msg = res.keterangan || res.message || error; } catch (e) { }

                $.alert({ title: 'Error', content: 'Gagal memproses persetujuan: ' + msg, type: 'red' });
            }
        });
    },

    // ============================================================
    // PROSES TOLAK
    // ============================================================

    openKonfirmasiTolak: function (id, no, nama, catatan) {
        var self = this;
        self.data.current_id = id;
        self.data.current_no = no;
        self.data.current_nama = nama;
        self._pendingCatatan = catatan || '';

        $('#konfirmasi-tolak-no').text(no || id);
        $('#konfirmasi-tolak-nama').text(nama || '-');
        $('#alasan-tolak-final').val(catatan || '').removeClass('is-invalid');
        $('#alasan-tolak-error').hide();

        $('#modal-konfirmasi-tolak').modal('show');
    },

    prosesTolak: function () {
        var self = this;
        var alasan = $('#alasan-tolak-final').val().trim();

        if (!alasan) {
            $('#alasan-tolak-final').addClass('is-invalid');
            $('#alasan-tolak-error').show();
            return;
        }

        var $btn = $('#btn-konfirmasi-tolak');
        $btn.prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...');

        self.showLoading('Menolak pengajuan...');

        $.ajax({
            url: '/kaprodi/transkrip/tolak',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id_riwayat_pengajuan_nilai: self.data.current_id,
                alasan_tolak: alasan
            },
            success: function (response) {
                self.hideLoading();
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-ban mr-1"></i>Ya, Tolak');
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
                                    atas nama <strong>${self.data.current_nama}</strong>
                                    telah ditolak.
                                </p>
                            </div>`,
                        type: 'red',
                        onClose: function () { self.refreshAfterAction(); }
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
                self.hideLoading();
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-ban mr-1"></i>Ya, Tolak');

                var msg = error;
                try { var res = JSON.parse(xhr.responseText); msg = res.keterangan || res.message || error; } catch (e) { }

                $.alert({ title: 'Error', content: 'Gagal memproses penolakan: ' + msg, type: 'red' });
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

    /**
     * Mapping kode status:
     * 1 = Draft
     * 2 = Diajukan
     * 3 = Proses Kaprodi
     * 4 = Proses Dekan
     * 5 = Disetujui
     * 6 = Ditolak
     */
    getBadgeStatus: function (statusKode, keteranganStatus) {
        if (!statusKode) return '<span class="badge badge-secondary">-</span>';

        var map = {
            '1': { cls: 'badge-status-draft', icon: 'fa-pencil-alt', label: 'Draft' },
            '2': { cls: 'badge-status-diajukan', icon: 'fa-clock', label: 'Diajukan' },
            '3': { cls: 'badge-status-kaprodi', icon: 'fa-user-tie', label: 'Proses Kaprodi' },
            '4': { cls: 'badge-status-dekan', icon: 'fa-user-shield', label: 'Proses Dekan' },
            '5': { cls: 'badge-status-disetujui', icon: 'fa-check-circle', label: 'Disetujui' },
            '6': { cls: 'badge-status-ditolak', icon: 'fa-times-circle', label: 'Ditolak' }
        };

        var s = map[String(statusKode)];
        var label = keteranganStatus || (s ? s.label : statusKode);

        if (!s) return `<span class="badge badge-secondary">${label}</span>`;

        return `<span class="badge ${s.cls}">
                    <i class="fas ${s.icon} mr-1"></i>${label}
                </span>`;
    },

    getBadgeNilai: function (nilai) {
        if (!nilai || nilai === '-') return '-';
        var nilaiUpper = nilai.toUpperCase();
        var badgeMap = {
            'A': 'badge-success',
            'AB': 'badge-success',
            'B': 'badge-primary',
            'BC': 'badge-info',
            'C': 'badge-warning',
            'D': 'badge-warning',
            'E': 'badge-danger'
        };
        var cls = badgeMap[nilaiUpper] || 'badge-secondary';
        return `<span class="badge ${cls}">${nilaiUpper}</span>`;
    },

    /**
     * Step indicator — 4 langkah:
     * 1 = Draft     → Mahasiswa aktif
     * 2 = Diajukan  → Mahasiswa aktif
     * 3 = Proses Kaprodi → Kaprodi aktif
     * 4 = Proses Dekan   → Dekan aktif
     * 5 = Disetujui → semua done
     * 6 = Ditolak   → Kaprodi reject
     */
    renderStepIndicator: function (statusKode) {
        var steps = [
            { label: 'Mahasiswa', icon: 'fa-user' },
            { label: 'Kaprodi', icon: 'fa-user-tie' },
            { label: 'Dekan', icon: 'fa-user-shield' },
            { label: 'Selesai', icon: 'fa-check' }
        ];

        var kode = String(statusKode);
        var isDitolak = kode === '6';

        // index step aktif (0-based)
        var activeMap = { '1': 0, '2': 0, '3': 1, '4': 2, '5': 3, '6': 1 };
        var activeIdx = activeMap[kode] !== undefined ? activeMap[kode] : 0;

        var html = '<div class="step-indicator">';

        steps.forEach(function (step, i) {
            var cls;

            if (isDitolak) {
                // step 0 (Mahasiswa) = done, step 1 (Kaprodi) = reject, sisanya default
                cls = i === 0 ? 'done' : i === 1 ? 'reject' : '';
            } else if (kode === '5') {
                cls = 'done';
            } else if (i < activeIdx) {
                cls = 'done';
            } else if (i === activeIdx) {
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
                '1': 'active',
                '2': 'active',
                '3': 'warning',
                '4': 'warning',
                '5': 'success',
                '6': 'danger'
            };
            var cls = clsMap[String(item.status)] || '';

            // Field tanggal: tgl_updated lebih prioritas, fallback tgl_created
            var tanggal = item.tgl_updated || item.tgl_created || '-';

            // Field komentar: komentar_persetujuan atau catatan
            var komentar = item.komentar_persetujuan || item.catatan || '';

            // Field pelaku: nama_user (jika ada)
            var namaUser = item.nama_user || '';

            html += `
                <div class="timeline-item ${cls}">
                    <div class="d-flex justify-content-between align-items-start">
                        <strong>${item.keterangan_status || '-'}</strong>
                        <small class="text-muted ml-2 text-nowrap">${tanggal}</small>
                    </div>
                    ${namaUser
                    ? `<small class="text-muted">oleh: ${namaUser}</small>`
                    : ''}
                    ${komentar
                    ? `<p class="mb-0 mt-1 small text-muted border-left pl-2">${komentar}</p>`
                    : ''}
                </div>`;
        });

        return html;
    },

    parseTahunAkademik: function (tahunAkademik) {
        var result = { nama: '-', semester: '-' };
        if (!tahunAkademik || tahunAkademik.length < 5) return result;

        var tahun = tahunAkademik.substring(0, 4);
        var semesterCode = tahunAkademik.substring(4, 5);
        var tahunInt = parseInt(tahun);

        result.nama = tahunInt + '/' + (tahunInt + 1);
        var semesterMap = { '1': 'Ganjil', '2': 'Genap', '3': 'Antara' };
        result.semester = semesterMap[semesterCode] || 'Ganjil';

        return result;
    }
};

// Initialize when document ready
jQuery(document).ready(function () {
    jQuery.transkripKaprodi.init();
});
