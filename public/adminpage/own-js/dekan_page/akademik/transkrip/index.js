jQuery.transkripDekan = {
    data: {
        table_transkrip: null,
        filter: {
            status: '3',
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
    // LOADING HELPERS
    // ============================================================

    showOverlay: function (text) {
        $('#loading-overlay-text').text(text || 'Memproses...');
        $('#loading-overlay').addClass('show');
    },

    hideOverlay: function () {
        $('#loading-overlay').removeClass('show');
    },

    showModalLoading: function () {
        $('#modal-detail-loading').addClass('show');
        $('#modal-detail-content').hide();
        $('#btn-sahkan-dekan').addClass('d-none');
        $('#btn-tolak-dekan').addClass('d-none');
    },

    hideModalLoading: function () {
        $('#modal-detail-loading').removeClass('show');
        $('#modal-detail-content').show();
    },

    showPreviewLoading: function () {
        $('#preview-nilai-loading').addClass('show');
        $('#preview-nilai-wrapper-content').hide();
    },

    hidePreviewLoading: function () {
        $('#preview-nilai-loading').removeClass('show');
        $('#preview-nilai-wrapper-content').show();
    },

    setStatLoading: function () {
        var ids = ['#stat-menunggu', '#stat-disahkan', '#stat-ditolak', '#stat-total'];
        ids.forEach(function (id) {
            $(id).addClass('stat-loading')
                .html('<i class="fas fa-spinner fa-spin fa-sm"></i>');
        });
    },

    setStatValue: function (menunggu, disahkan, ditolak, total) {
        var map = {
            '#stat-menunggu': menunggu,
            '#stat-disahkan': disahkan,
            '#stat-ditolak': ditolak,
            '#stat-total': total
        };
        Object.keys(map).forEach(function (id) {
            $(id).removeClass('stat-loading').text(map[id]);
        });
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
                        d.tahun = self.data.filter.tahun;
                        d.prodi = self.data.filter.prodi;
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

                        json.recordsTotal = json.recordsTotal || (json.data ? json.data.length : 0);
                        json.recordsFiltered = json.recordsFiltered || json.recordsTotal;
                        json.draw = json.draw || 1;

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
                    {
                        data: 'nomor',
                        searchable: false,
                        className: 'text-center',
                        width: '4%',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'nomor_pengajuan',
                        searchable: true,
                        className: 'text-left',
                        width: '14%',
                        render: function (data, type, row) {
                            var no = data || '-';
                            var idShort = row.id_riwayat_pengajuan_nilai
                                ? row.id_riwayat_pengajuan_nilai.substring(0, 8).toUpperCase()
                                : '-';
                            return `<strong style="color:#4a148c;" class="d-block">${no}</strong>
                                    <small class="text-muted">${idShort}</small>`;
                        }
                    },
                    {
                        data: 'nim',
                        searchable: true,
                        className: 'text-left',
                        width: '10%',
                        render: function (data) {
                            return `<code>${data || '-'}</code>`;
                        }
                    },
                    {
                        data: 'nama_mahasiswa',
                        searchable: true,
                        className: 'text-left',
                        width: '17%',
                        defaultContent: '-'
                    },
                    {
                        data: 'nama_prodi',
                        searchable: true,
                        className: 'text-left',
                        width: '13%',
                        render: function (data) {
                            return data ? `<small>${data}</small>` : '-';
                        }
                    },
                    {
                        data: 'keperluan',
                        searchable: true,
                        className: 'text-left',
                        width: '12%',
                        defaultContent: '-'
                    },
                    {
                        data: 'tgl_created',
                        searchable: false,
                        className: 'text-center',
                        width: '10%',
                        render: function (data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'tgl_updated',
                        searchable: false,
                        className: 'text-center',
                        width: '10%',
                        render: function (data) {
                            if (!data) return '<span class="text-muted">-</span>';
                            return `<span class="text-success">${data}</span>`;
                        }
                    },
                    {
                        data: 'status',
                        searchable: false,
                        className: 'text-center',
                        width: '10%',
                        render: function (data, type, row) {
                            return self.getBadgeStatus(data, row.keterangan_status);
                        }
                    },
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: '6%',
                        render: function (data) {
                            if (!data) return '-';

                            var idRiwayat = data.id_riwayat_pengajuan_nilai || '';
                            var idPengajuanInduk = data.id_pengajuan_induk || idRiwayat;

                            return `
                                <button class="btn btn-info btn-sm btn-detail-dekan"
                                        data-id="${idPengajuanInduk}"
                                        data-riwayat="${idRiwayat}"
                                        title="Lihat Detail & Tindakan">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </button>`;
                        }
                    }
                ],
                rowCallback: function (row, data) {
                    if (String(data.status) === '3') {
                        $(row).addClass('row-menunggu-dekan');
                    }
                },
                drawCallback: function (settings) {
                    var api = this.api();
                    var rows = api.rows().data().toArray();

                    if (rows.length === 0) {
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
                    emptyTable: "Tidak ada data pengajuan transkrip",
                    processing: `<div class="text-center py-3">
                                       <i class="fas fa-spinner fa-spin mr-2"
                                          style="color:#9c27b0;"></i>
                                       Sedang memuat data...
                                   </div>`,
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

        $('#btn-refresh').off('click').on('click', function () {
            var $btn = $(this);
            $btn.prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin mr-1"></i>Refresh');

            if (self.data.table_transkrip) {
                self.data.table_transkrip.ajax.reload(function () {
                    $btn.prop('disabled', false)
                        .html('<i class="fas fa-sync-alt mr-1"></i>Refresh');
                });
            }

            self.loadStatistik();
        });

        $(document).off('click', '.btn-detail-dekan')
            .on('click', '.btn-detail-dekan', function (e) {
                e.stopPropagation();
                var idRiwayat = $(this).data('id');
                self.loadDetail(idRiwayat);
            });

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

        $('#btn-tolak-dekan').off('click').on('click', function () {
            if (!self.data.current_id) return;
            var catatan = $('#dekan-catatan').val().trim();
            $('#modal-detail-dekan').modal('hide');
            self.openKonfirmasiTolak(
                self.data.current_id,
                self.data.current_no,
                self.data.current_nama,
                catatan
            );
        });

        $('#btn-konfirmasi-sahkan').off('click').on('click', function () {
            self.prosesSahkan();
        });

        $('#btn-konfirmasi-tolak-dekan').off('click').on('click', function () {
            self.prosesTolak();
        });

        // ✅ FIX UTAMA: gunakan flag _isProcessing untuk mencegah
        //    resetModalDetail() menghapus current_id saat proses sedang berjalan
        $('#modal-detail-dekan').on('hidden.bs.modal', function () {
            if (!self._isProcessing) {
                self.resetModalDetail();
            }
        });

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
        self.data.filter.tahun = $('#filter-tahun').val();
        self.data.filter.prodi = $('#filter-prodi').val();
        self.data.filter.search = $('#filter-search').val().trim();

        if (self.data.table_transkrip) {
            self.data.table_transkrip.ajax.reload();
        }
    },

    resetFilter: function () {
        var self = this;

        $('#filter-status').val('3').trigger('change');
        $('#filter-tahun').val('').trigger('change');
        $('#filter-prodi').val('').trigger('change');
        $('#filter-search').val('');

        self.data.filter = {
            status: '3',
            tahun: '',
            prodi: '',
            search: ''
        };

        if (self.data.table_transkrip) {
            self.data.table_transkrip.ajax.reload();
        }
    },

    updateBadgeFilterAktif: function () {
        var self = this;
        var f = self.data.filter;
        var aktif = f.tahun || f.prodi || f.search ||
            (f.status && f.status !== '3');

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

        self.setStatLoading();

        $.ajax({
            url: '/dekan/transkrip/statistik',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Statistik dekan:', response);

                self.setStatValue(
                    response.menunggu || 0,
                    response.disahkan || 0,
                    response.ditolak || 0,
                    response.total || 0
                );

                self.data.statistik = {
                    menunggu: response.menunggu || 0,
                    disahkan: response.disahkan || 0,
                    ditolak: response.ditolak || 0,
                    total: response.total || 0
                };
            },
            error: function (xhr, status, error) {
                console.warn('Gagal memuat statistik dekan:', error);
                self.setStatValue('-', '-', '-', '-');
            }
        });
    },

    loadDetail: function (idRiwayat) {
        var self = this;

        if (!idRiwayat) {
            $.alert({ title: 'Error', content: 'ID riwayat pengajuan tidak valid', type: 'red' });
            return;
        }

        self.resetModalDetail();
        self.showModalLoading();
        $('#modal-detail-dekan').modal('show');

        $.ajax({
            url: '/dekan/transkrip/detail',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id_riwayat_pengajuan_nilai: idRiwayat
            },
            success: function (response) {
                console.log('Detail pengajuan dekan:', response);

                self.hideModalLoading();

                if (response && response.status === '1') {
                    var d = response.data;

                    if (d.riwayat && typeof d.riwayat === 'string') {
                        try {
                            d.riwayat = JSON.parse(d.riwayat);
                        } catch (e) {
                            console.warn('Gagal parse riwayat JSON:', e);
                            d.riwayat = [];
                        }
                    }

                    self.data.current_detail = d;
                    self.data.current_id = d.id_riwayat_pengajuan || idRiwayat;
                    self.data.current_no = d.nomor_pengajuan;
                    self.data.current_nama = d.nama_mahasiswa;

                    console.log('current_id set to:', self.data.current_id);

                    self.renderModalDetail(d);
                    self.showPreviewLoading();
                    self.loadPreviewNilai(d.nim);
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
                self.hideModalLoading();
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
            self.hidePreviewLoading();
            $('#dkn-preview-nilai').html(`
                <tr>
                    <td colspan="8" class="text-center text-muted py-3">
                        NIM tidak valid
                    </td>
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
                self.hidePreviewLoading();

                if (response && response.status === '1' && Array.isArray(response.data)) {
                    self.renderPreviewNilai(response.data);

                    // Fallback IPK: gunakan hitungan preview jika detail belum mengandung IPK valid.
                    var detailIpk = parseFloat(self.data.current_detail && self.data.current_detail.ipk);
                    if (!isFinite(detailIpk) || detailIpk <= 0) {
                        var totalSksPreview = 0;
                        var totalBobotPreview = 0;

                        response.data.forEach(function (mk) {
                            var sks = parseInt(mk.sks || 0, 10);
                            var bobot = parseFloat(mk.bobot || 0);
                            if (isFinite(sks) && isFinite(bobot)) {
                                totalSksPreview += sks;
                                totalBobotPreview += (bobot * sks);
                            }
                        });

                        var ipkPreview = totalSksPreview > 0 ? (totalBobotPreview / totalSksPreview) : 0;
                        $('#dkn-ipk').text(ipkPreview.toFixed(2));
                        // $('#dkn-predikat').text(ipkPreview > 0 ? self.getPredikat(ipkPreview) : '-');
                    }
                } else {
                    $('#dkn-preview-nilai').html(`
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">
                                Tidak ada data nilai
                            </td>
                        </tr>`);
                }
            },
            // BLOK ERROR YANG BENAR DITARUH DI SINI (Di dalam kurung $.ajax)
            error: function (xhr) {
                self.hidePreviewLoading();

                // Tangkap error asli dari backend
                var msg = 'Gagal memuat preview nilai';
                try {
                    var res = JSON.parse(xhr.responseText);
                    if (res.keterangan) msg = res.keterangan;
                } catch (e) { }

                $('#dkn-preview-nilai').html(`
                    <tr>
                        <td colspan="8" class="text-center text-danger py-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            ${msg}
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

        $('#dkn-no-pengajuan').text(data.nomor_pengajuan || '-');
        $('#dkn-id-riwayat').text(data.id_riwayat_pengajuan_nilai || data.id_riwayat_pengajuan || '-');
        $('#dkn-status-badge').html(
            self.getBadgeStatus(data.status, data.keterangan_status)
        );

        $('#dkn-nim').text(data.nim || '-');
        $('#dkn-nama').text(data.nama_mahasiswa || '-');
        $('#dkn-prodi').text(data.nama_prodi || '-');
        $('#dkn-angkatan').text(
            (!data.angkatan || data.angkatan === '-') ? '-' : data.angkatan
        );

        var ipk = parseFloat(data.ipk || 0);
        $('#dkn-ipk').text(isFinite(ipk) && ipk > 0 ? ipk.toFixed(2) : '0.00');
        // $('#dkn-predikat').text(ipk > 0 ? self.getPredikat(ipk) : '-');

        $('#dkn-nama-kaprodi').text(
            (!data.nama_kaprodi || data.nama_kaprodi === '-') ? '-' : data.nama_kaprodi
        );
        $('#dkn-tgl-kaprodi').text(data.tgl_kaprodi || '-');
        $('#dkn-catatan-kaprodi').text(
            (!data.catatan_kaprodi || data.catatan_kaprodi === '-') ? '-' : data.catatan_kaprodi
        );

        $('#dkn-keperluan').text(data.keperluan || '-');
        var tglAjuan = data.tgl_pengajuan || data.tgl_created || self.getTanggalAjuanDariRiwayat(data.riwayat);
        $('#dkn-tgl-ajuan').text(tglAjuan || '-');

        if (String(data.status) === '6' && data.alasan_tolak) {
            $('#dkn-alasan-tolak').text(data.alasan_tolak);
            $('#section-alasan-tolak').show();
        } else {
            $('#section-alasan-tolak').hide();
        }

        $('#dkn-step-indicator').html(self.renderStepIndicator(data.status));
        $('#dkn-timeline').html(self.renderTimeline(data.riwayat));

        if (String(data.status) === '3') {
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
                    <td>${mk.matakuliah || mk.nama_matakuliah || '-'}</td>
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

        var ipk = totalSks > 0 ? (totalBobot / totalSks) : 0;

        $('#dkn-preview-nilai').html(html);
        $('#dkn-preview-total-sks').text(totalSks);
        $('#dkn-preview-ipk').text(ipk.toFixed(2));
    },

    resetModalDetail: function () {
        $('#dkn-no-pengajuan').text('-');
        $('#dkn-id-riwayat').text('-');
        $('#dkn-status-badge').html('');

        $('#dkn-nim').text('-');
        $('#dkn-nama').text('-');
        $('#dkn-prodi').text('-');
        $('#dkn-angkatan').text('-');
        $('#dkn-ipk').text('0.00');
        // $('#dkn-predikat').text('-');

        $('#dkn-nama-kaprodi').text('-');
        $('#dkn-tgl-kaprodi').text('-');
        $('#dkn-catatan-kaprodi').text('-');

        $('#dkn-keperluan').text('-');
        $('#dkn-tgl-ajuan').text('-');

        $('#section-alasan-tolak').hide();
        $('#dkn-alasan-tolak').text('-');

        $('#dkn-step-indicator').html('');
        $('#dkn-timeline').html(
            '<div class="text-muted text-center py-3">Tidak ada riwayat</div>'
        );

        $('#dkn-preview-nilai').html('');
        $('#dkn-preview-total-sks').text(0);
        $('#dkn-preview-ipk').text('0.00');

        $('#dekan-catatan').val('');
        $('#section-tindakan-dekan').hide();
        $('#btn-sahkan-dekan').addClass('d-none');
        $('#btn-tolak-dekan').addClass('d-none');

        this.data.current_detail = null;
        this.data.current_id = null;
        this.data.current_no = null;
        this.data.current_nama = null;
    },

    // ============================================================
    // PROSES SAHKAN
    // ============================================================

    openKonfirmasiSahkan: function (id, no, nama, catatan) {
        var self = this;

        // ✅ Simpan ke _pending sebelum apapun di-hide
        self._pendingId = id;
        self._pendingNo = no;
        self._pendingNama = nama;
        self._pendingCatatan = catatan || '';

        // Tandai sedang dalam proses agar hidden.bs.modal tidak reset state
        self._isProcessing = true;

        $('#konfirmasi-sahkan-no').text(no || id);
        $('#konfirmasi-sahkan-nama').text(nama || '-');

        $('#modal-konfirmasi-sahkan').modal('show');
    },

    prosesSahkan: function () {
        var self = this;

        // ✅ Ambil dari _pending — aman dari reset oleh hidden.bs.modal
        var idToProcess = self._pendingId;
        var noToProcess = self._pendingNo;
        var namaToProcess = self._pendingNama;
        var catatan = self._pendingCatatan || '';

        if (!idToProcess) {
            $.alert({ title: 'Error', content: 'ID pengajuan tidak valid', type: 'red' });
            return;
        }

        var $btn = $('#btn-konfirmasi-sahkan');
        $btn.prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...');

        $('#modal-konfirmasi-sahkan').modal('hide');
        self.showOverlay('Mengesahkan transkrip...');

        $.ajax({
            url: '/dekan/transkrip/sahkan',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id_riwayat_pengajuan: idToProcess,
                catatan: catatan
            },
            success: function (response) {
                self.hideOverlay();
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-stamp mr-1"></i>Ya, Sahkan');

                if (response && response.status === '1') {
                    $.alert({
                        title: 'Berhasil Disahkan!',
                        content: `
                            <div class="text-center">
                                <i class="fas fa-stamp text-success"
                                   style="font-size:3rem;"></i>
                                <p class="mt-3 font-weight-bold">
                                    Transkrip berhasil disahkan!
                                </p>
                                <p class="text-muted small">
                                    Pengajuan <strong>${noToProcess}</strong>
                                    atas nama <strong>${namaToProcess}</strong>
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
                self.hideOverlay();
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-stamp mr-1"></i>Ya, Sahkan');

                var msg = error;
                try {
                    var res = JSON.parse(xhr.responseText);
                    msg = res.keterangan || res.message || error;
                } catch (e) { }

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

    openKonfirmasiTolak: function (id, no, nama, catatan) {
        var self = this;

        // ✅ Simpan ke _pending sebelum apapun di-hide
        self._pendingId = id;
        self._pendingNo = no;
        self._pendingNama = nama;
        self._pendingCatatan = catatan || '';

        // Tandai sedang dalam proses agar hidden.bs.modal tidak reset state
        self._isProcessing = true;

        $('#konfirmasi-tolak-dekan-no').text(no || id);
        $('#konfirmasi-tolak-dekan-nama').text(nama || '-');
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

        // ✅ Ambil dari _pending — aman dari reset oleh hidden.bs.modal
        var idToProcess = self._pendingId;
        var noToProcess = self._pendingNo;
        var namaToProcess = self._pendingNama;

        if (!idToProcess) {
            $.alert({ title: 'Error', content: 'ID pengajuan tidak valid', type: 'red' });
            return;
        }

        var $btn = $('#btn-konfirmasi-tolak-dekan');
        $btn.prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...');

        $('#modal-konfirmasi-tolak-dekan').modal('hide');
        self.showOverlay('Menolak pengajuan...');

        $.ajax({
            url: '/dekan/transkrip/tolak',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id_riwayat_pengajuan: idToProcess,
                alasan_tolak: alasan
            },
            success: function (response) {
                self.hideOverlay();
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-ban mr-1"></i>Ya, Tolak');

                if (response && response.status === '1') {
                    $.alert({
                        title: 'Pengajuan Ditolak',
                        content: `
                            <div class="text-center">
                                <i class="fas fa-times-circle text-danger"
                                   style="font-size:3rem;"></i>
                                <p class="mt-3 font-weight-bold">
                                    Pengajuan berhasil ditolak
                                </p>
                                <p class="text-muted small">
                                    Pengajuan <strong>${noToProcess}</strong>
                                    atas nama <strong>${namaToProcess}</strong>
                                    telah ditolak oleh Dekan.
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
                self.hideOverlay();
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-ban mr-1"></i>Ya, Tolak');

                var msg = error;
                try {
                    var res = JSON.parse(xhr.responseText);
                    msg = res.keterangan || res.message || error;
                } catch (e) { }

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

        // ✅ Bersihkan semua _pending state + flag processing
        self._isProcessing = false;
        self._pendingId = null;
        self._pendingNo = null;
        self._pendingNama = null;
        self._pendingCatatan = '';

        if (self.data.table_transkrip) {
            self.data.table_transkrip.ajax.reload();
        }
        self.loadStatistik();
    },

    getPredikat: function (ipk) {
        if (ipk >= 3.51) return 'Cum Laude';
        if (ipk >= 3.01) return 'Sangat Memuaskan';
        if (ipk >= 2.76) return 'Memuaskan';
        return 'Cukup';
    },

    getBadgeStatus: function (statusKode, keteranganStatus) {
        if (!statusKode) return '<span class="badge badge-secondary">-</span>';

        var map = {
            '1': { cls: 'badge-status-draft', icon: 'fa-pencil-alt' },
            '2': { cls: 'badge-status-diajukan', icon: 'fa-clock' },
            '3': { cls: 'badge-status-dekan', icon: 'fa-user-shield' },
            '4': { cls: 'badge-status-disetujui', icon: 'fa-stamp' },
            '6': { cls: 'badge-status-ditolak', icon: 'fa-times-circle' }
        };

        var s = map[String(statusKode)];
        var label = keteranganStatus || statusKode;

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

    renderStepIndicator: function (statusKode) {
        var steps = [
            { label: 'Mahasiswa', icon: 'fa-user' },
            { label: 'Kaprodi', icon: 'fa-user-tie' },
            { label: 'Dekan', icon: 'fa-user-shield' },
            { label: 'Selesai', icon: 'fa-check' }
        ];

        var kode = String(statusKode);
        var isDitolak = kode === '6';
        var isDone = kode === '4';

        var activeMap = { '1': 0, '2': 0, '3': 1 };
        var activeIdx = activeMap[kode] !== undefined ? activeMap[kode] : -1;

        var html = '<div class="step-indicator">';

        steps.forEach(function (step, i) {
            var cls;

            if (isDone) {
                cls = 'done';
            } else if (isDitolak) {
                cls = i < 1 ? 'done'
                    : i === 1 ? 'reject'
                        : '';
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
        if (!riwayat || !Array.isArray(riwayat) || riwayat.length === 0) {
            return `<div class="text-muted text-center py-3">
                        <i class="fas fa-info-circle mr-2"></i>Tidak ada riwayat aktivitas
                    </div>`;
        }

        var html = '';

        riwayat.forEach(function (item) {
            var clsMap = {
                '1': 'active',
                '2': 'active',
                '3': 'purple',
                '4': 'success',
                '6': 'danger'
            };

            var cls = clsMap[String(item.status)] || '';
            var tanggal = item.tgl_persetujuan || item.tgl_created || '-';
            var noPengajuan = item.nomor_pengajuan || '';
            var komentar = item.komentar_persetujuan || '';

            html += `
                <div class="timeline-item ${cls}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong>${item.keterangan_status || '-'}</strong>
                            ${noPengajuan
                    ? `<br><small class="text-muted">No: ${noPengajuan}</small>`
                    : ''}
                        </div>
                        <small class="text-muted text-right ml-2">${tanggal}</small>
                    </div>
                    ${komentar
                    ? `<p class="mb-0 mt-1 small text-muted">${komentar}</p>`
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
    },

    getTanggalAjuanDariRiwayat: function (riwayat) {
        if (!Array.isArray(riwayat) || riwayat.length === 0) {
            return '-';
        }

        var fallback = '-';

        for (var i = 0; i < riwayat.length; i++) {
            var item = riwayat[i] || {};
            var tanggal = item.tgl_created || '-';
            var status = String(item.status || '');
            var keterangan = String(item.keterangan_status || '').toLowerCase();

            if (fallback === '-' && tanggal !== '-') {
                fallback = tanggal;
            }

            if (status === '2' || keterangan.indexOf('diajukan') !== -1) {
                return tanggal;
            }
        }

        return fallback;
    }
};

jQuery(document).ready(function () {
    console.log('Document ready, initializing Transkrip Dekan module...');
    jQuery.transkripDekan.init();
});
