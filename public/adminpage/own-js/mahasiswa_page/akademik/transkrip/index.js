jQuery.transkrip = {
    data: {
        table_transkrip: null,
        filter: {
            status: '',
            tahun: '',
            search: ''
        },
        statistik: {
            total_pengajuan: 0,
            diproses: 0,
            disetujui: 0,
            ditolak: 0
        },
        current_detail: null
    },

    init: function () {
        var self = this;

        console.log('Initializing Transkrip module...');

        if (!$('#table-transkrip').length) {
            console.error('Table #table-transkrip tidak ditemukan!');
            return;
        }

        self.initSelect2();
        self.initDataTable();
        self.setEvents();
        self.loadStatistik();
        self.loadMahasiswaInfo();
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

    initDataTable: function () {
        var self = this;

        if ($.fn.DataTable.isDataTable('#table-transkrip')) {
            $('#table-transkrip').DataTable().clear().destroy();
        }

        $('#table-transkrip tbody').empty();

        try {
            self.data.table_transkrip = $("#table-transkrip").DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: '/mhs/transkrip/json',
                    type: 'POST',
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.status  = self.data.filter.status;
                        d.tahun   = self.data.filter.tahun;
                        d.search  = self.data.filter.search;
                        return d;
                    },
                    dataSrc: function (json) {
                        console.log('Received transkrip data:', json);

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
                        console.error('Response:', xhr.responseText);

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
                        width: '5%',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    // No. Pengajuan
                    {
                        data: 'no_pengajuan',
                        searchable: true,
                        className: 'text-left',
                        width: '15%',
                        render: function (data) {
                            return `<strong class="text-primary">${data || '-'}</strong>`;
                        }
                    },
                    // Keperluan
                    {
                        data: 'keperluan',
                        searchable: true,
                        className: 'text-left',
                        width: '20%',
                        defaultContent: '-'
                    },
                    // Bahasa
                    {
                        data: 'bahasa',
                        searchable: false,
                        className: 'text-center',
                        width: '10%',
                        render: function (data) {
                            if (!data || data === '-') return '-';
                            var badgeClass = data === 'Inggris' ? 'badge-info' : 'badge-secondary';
                            return `<span class="badge ${badgeClass}">${data}</span>`;
                        }
                    },
                    // Tanggal Ajuan
                    {
                        data: 'tgl_pengajuan',
                        searchable: false,
                        className: 'text-center',
                        width: '12%',
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
                        width: '13%',
                        render: function (data) {
                            return self.getBadgeStatus(data);
                        }
                    },
                    // Progress
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: '15%',
                        render: function (data) {
                            return self.renderProgressMini(data ? data.status : null);
                        }
                    },
                    // Aksi
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: '10%',
                        render: function (data) {
                            if (!data) return '-';

                            var btnDetail = `
                                <button class="btn btn-info btn-sm btn-detail-pengajuan"
                                        data-id="${data.id_pengajuan || ''}"
                                        title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>`;

                            var btnBatal = '';
                            if (data.status === 'diajukan' || data.status === 'draft') {
                                btnBatal = `
                                    <button class="btn btn-danger btn-sm btn-batalkan ml-1"
                                            data-id="${data.id_pengajuan || ''}"
                                            data-no="${data.no_pengajuan || ''}"
                                            title="Batalkan Pengajuan">
                                        <i class="fas fa-ban"></i>
                                    </button>`;
                            }

                            var btnDownload = '';
                            if (data.status === 'disetujui') {
                                btnDownload = `
                                    <button class="btn btn-success btn-sm btn-download-row ml-1"
                                            data-id="${data.id_pengajuan || ''}"
                                            title="Download Transkrip">
                                        <i class="fas fa-download"></i>
                                    </button>`;
                            }

                            return `<div class="d-flex justify-content-center">${btnDetail}${btnBatal}${btnDownload}</div>`;
                        }
                    }
                ],
                drawCallback: function (settings) {
                    var api  = this.api();
                    var data = api.rows().data().toArray();

                    if (data.length === 0) {
                        $('#table-transkrip tbody').html(`
                            <tr>
                                <td colspan="8" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>Tidak ada data pengajuan transkrip</p>
                                        <small class="text-muted">
                                            Klik tombol "Ajukan Transkrip Baru" untuk membuat pengajuan
                                        </small>
                                    </div>
                                </td>
                            </tr>
                        `);
                    }
                },
                paging: true,
                processing: true,
                pageLength: 10,
                ordering: false,
                lengthChange: true,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                autoWidth: false,
                language: {
                    emptyTable:     "Tidak ada data pengajuan transkrip",
                    processing:     "Sedang memuat data...",
                    zeroRecords:    "Tidak ditemukan data yang sesuai",
                    info:           "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty:      "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered:   "(disaring dari _MAX_ total data)",
                    lengthMenu:     "Tampilkan _MENU_ data",
                    paginate: {
                        first:    "Pertama",
                        last:     "Terakhir",
                        next:     "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            console.log('DataTable Transkrip initialized successfully');

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

        // --- Buka Modal Ajukan ---
        $('#btn-ajukan-transkrip').off('click').on('click', function () {
            self.openModalAjukan();
        });

        // --- Simpan Pengajuan ---
        $('#btn-simpan-ajuan').off('click').on('click', function () {
            self.simpanPengajuan();
        });

        // --- Detail (delegasi — tombol dalam tabel) ---
        $(document).off('click', '.btn-detail-pengajuan').on('click', '.btn-detail-pengajuan', function (e) {
            e.stopPropagation();
            var id = $(this).data('id');
            self.loadDetailPengajuan(id);
        });

        // --- Batalkan (delegasi — tombol dalam tabel) ---
        $(document).off('click', '.btn-batalkan').on('click', '.btn-batalkan', function (e) {
            e.stopPropagation();
            var id = $(this).data('id');
            var no = $(this).data('no');
            self.konfirmasiBatalkan(id, no);
        });

        // --- Download (delegasi — tombol dalam tabel) ---
        $(document).off('click', '.btn-download-row').on('click', '.btn-download-row', function (e) {
            e.stopPropagation();
            var id = $(this).data('id');
            self.downloadTranskrip(id);
        });

        // --- Download dari modal detail ---
        $('#btn-download-transkrip').off('click').on('click', function () {
            if (self.data.current_detail) {
                self.downloadTranskrip(self.data.current_detail.id_pengajuan);
            }
        });

        // --- Batalkan dari modal detail ---
        $('#btn-batalkan-pengajuan').off('click').on('click', function () {
            if (self.data.current_detail) {
                $('#modal-detail-pengajuan').modal('hide');
                self.konfirmasiBatalkan(
                    self.data.current_detail.id_pengajuan,
                    self.data.current_detail.no_pengajuan
                );
            }
        });

        // --- Reset form saat modal ditutup ---
        $('#modal-ajukan-transkrip').on('hidden.bs.modal', function () {
            self.resetFormAjukan();
        });
    },

    // ============================================================
    // FILTER
    // ============================================================

    applyFilter: function () {
        var self = this;

        self.data.filter.status = $('#filter-status').val();
        self.data.filter.tahun  = $('#filter-tahun').val();
        self.data.filter.search = $('#filter-search').val().trim();

        console.log('Applying filter:', self.data.filter);

        if (self.data.table_transkrip) {
            self.data.table_transkrip.ajax.reload();
        }
    },

    resetFilter: function () {
        var self = this;

        $('#filter-status').val('').trigger('change');
        $('#filter-tahun').val('').trigger('change');
        $('#filter-search').val('');

        self.data.filter = {
            status: '',
            tahun: '',
            search: ''
        };

        if (self.data.table_transkrip) {
            self.data.table_transkrip.ajax.reload();
        }
    },

    // ============================================================
    // LOAD DATA
    // ============================================================

    loadStatistik: function () {
        var self = this;

        $.ajax({
            url: '/mhs/transkrip/statistik',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Statistik transkrip:', response);

                if (response) {
                    $('#stat-total-pengajuan').text(response.total_pengajuan || 0);
                    $('#stat-diproses').text(response.diproses || 0);
                    $('#stat-disetujui').text(response.disetujui || 0);
                    $('#stat-ditolak').text(response.ditolak || 0);

                    self.data.statistik = {
                        total_pengajuan: response.total_pengajuan || 0,
                        diproses:        response.diproses        || 0,
                        disetujui:       response.disetujui       || 0,
                        ditolak:         response.ditolak         || 0
                    };
                }
            },
            error: function (xhr, status, error) {
                console.warn('Gagal memuat statistik transkrip:', error);
            }
        });
    },

    loadMahasiswaInfo: function () {
        var self = this;

        $.ajax({
            url: '/mhs/transkrip/mahasiswa-info',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Mahasiswa info:', response);

                if (response) {
                    $('#form-nim').text(response.nim  || '-');
                    $('#form-nama').text(response.nama || '-');
                    $('#form-prodi').text(response.nama_prodi || '-');
                    $('#form-ipk').text(parseFloat(response.ipk || 0).toFixed(2));
                }
            },
            error: function (xhr, status, error) {
                console.warn('Gagal memuat info mahasiswa:', error);
            }
        });
    },

    loadDetailPengajuan: function (id) {
        var self = this;

        if (!id) {
            $.alert({ title: 'Error', content: 'ID pengajuan tidak valid', type: 'red' });
            return;
        }

        $.ajax({
            url: '/mhs/transkrip/detail',
            method: 'POST',
            data: {
                _token:       $('meta[name="csrf-token"]').attr('content'),
                id_pengajuan: id
            },
            success: function (response) {
                console.log('Detail pengajuan:', response);

                if (response && response.status === '1') {
                    self.data.current_detail = response.data;
                    self.renderModalDetail(response.data);
                    $('#modal-detail-pengajuan').modal('show');
                } else {
                    $.alert({
                        title: 'Error',
                        content: response.keterangan || 'Data tidak ditemukan',
                        type: 'red'
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('Gagal memuat detail pengajuan:', error);
                $.alert({
                    title: 'Error',
                    content: 'Gagal memuat detail pengajuan: ' + error,
                    type: 'red'
                });
            }
        });
    },

    // ============================================================
    // MODAL AJUKAN
    // ============================================================

    openModalAjukan: function () {
        this.resetFormAjukan();
        $('#modal-ajukan-transkrip').modal('show');
    },

    resetFormAjukan: function () {
        $('#form-ajukan-transkrip')[0].reset();

        // Reset select2
        $('#keperluan').val('').trigger('change');
        $('#bahasa').val('').trigger('change');

        // Reset validation state
        $('#form-ajukan-transkrip .form-control').removeClass('is-invalid is-valid');

        // Reset jumlah lembar default
        $('#jumlah_lembar').val(1);
    },

    simpanPengajuan: function () {
        var self = this;

        // --- Validasi ---
        if (!self.validateFormAjukan()) {
            return;
        }

        var $btn = $('#btn-simpan-ajuan');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Mengirim...');

        var formData = {
            _token:        $('meta[name="csrf-token"]').attr('content'),
            keperluan:     $('#keperluan').val(),
            bahasa:        $('#bahasa').val(),
            jumlah_lembar: $('#jumlah_lembar').val(),
            email_tujuan:  $('#email_tujuan').val().trim(),
            catatan:       $('#catatan').val().trim()
        };

        $.ajax({
            url: '/mhs/transkrip/ajukan',
            method: 'POST',
            data: formData,
            success: function (response) {
                $btn.prop('disabled', false).html('<i class="fas fa-paper-plane mr-1"></i>Kirim Pengajuan');

                if (response && response.status === '1') {
                    $('#modal-ajukan-transkrip').modal('hide');

                    $.alert({
                        title: 'Berhasil!',
                        content: `
                            <div class="text-center">
                                <i class="fas fa-check-circle text-success" style="font-size:3rem;"></i>
                                <p class="mt-3">Pengajuan transkrip berhasil dikirim!</p>
                                <p class="text-muted">
                                    Nomor Pengajuan: <strong>${response.no_pengajuan || '-'}</strong>
                                </p>
                                <p class="text-muted small">
                                    Pengajuan Anda akan diproses oleh Kaprodi dan Dekan.
                                    Pantau status di halaman ini.
                                </p>
                            </div>`,
                        type: 'green',
                        onClose: function () {
                            if (self.data.table_transkrip) {
                                self.data.table_transkrip.ajax.reload();
                            }
                            self.loadStatistik();
                        }
                    });
                } else {
                    $.alert({
                        title: 'Gagal',
                        content: response.keterangan || 'Terjadi kesalahan saat menyimpan pengajuan',
                        type: 'red'
                    });
                }
            },
            error: function (xhr, status, error) {
                $btn.prop('disabled', false).html('<i class="fas fa-paper-plane mr-1"></i>Kirim Pengajuan');

                var msg = error;
                try {
                    var res = JSON.parse(xhr.responseText);
                    msg = res.keterangan || res.message || error;
                } catch (e) {}

                $.alert({
                    title: 'Error',
                    content: 'Gagal mengirim pengajuan: ' + msg,
                    type: 'red'
                });
            }
        });
    },

    validateFormAjukan: function () {
        var isValid = true;

        // Reset
        $('#form-ajukan-transkrip .form-control').removeClass('is-invalid');

        // Keperluan
        if (!$('#keperluan').val()) {
            $('#keperluan').addClass('is-invalid');
            isValid = false;
        }

        // Bahasa
        if (!$('#bahasa').val()) {
            $('#bahasa').addClass('is-invalid');
            isValid = false;
        }

        // Jumlah lembar
        var jumlah = parseInt($('#jumlah_lembar').val());
        if (!jumlah || jumlah < 1 || jumlah > 10) {
            $('#jumlah_lembar').addClass('is-invalid');
            isValid = false;
        }

        // Email (opsional, tapi jika diisi harus valid)
        var email = $('#email_tujuan').val().trim();
        if (email && !self.isValidEmail(email)) {
            $('#email_tujuan').addClass('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            $.alert({
                title: 'Perhatian',
                content: 'Harap lengkapi semua field yang wajib diisi dengan benar',
                type: 'orange'
            });
        }

        return isValid;
    },

    // ============================================================
    // MODAL DETAIL
    // ============================================================

    renderModalDetail: function (data) {
        var self = this;

        if (!data) return;

        // Nomor & status
        $('#detail-no-pengajuan').text(data.no_pengajuan || '-');
        $('#detail-status-badge').html(self.getBadgeStatus(data.status));

        // Info dasar
        $('#detail-keperluan').text(data.keperluan || '-');
        $('#detail-bahasa').text(data.bahasa || '-');
        $('#detail-jumlah-lembar').text((data.jumlah_lembar || 0) + ' lembar');
        $('#detail-email-tujuan').text(data.email_tujuan || '-');

        // Tanggal
        $('#detail-tgl-ajuan').text(self.formatTanggal(data.tgl_pengajuan));
        $('#detail-tgl-kaprodi').text(data.tgl_kaprodi ? self.formatTanggal(data.tgl_kaprodi) : '-');
        $('#detail-tgl-dekan').text(data.tgl_dekan ? self.formatTanggal(data.tgl_dekan) : '-');
        $('#detail-tgl-selesai').text(data.tgl_selesai ? self.formatTanggal(data.tgl_selesai) : '-');

        // Catatan
        $('#detail-catatan').text(data.catatan || '-');

        // Alasan tolak
        if (data.status === 'ditolak' && data.alasan_tolak) {
            $('#detail-alasan-tolak').text(data.alasan_tolak);
            $('#section-alasan-tolak').show();
        } else {
            $('#section-alasan-tolak').hide();
        }

        // Step indicator
        $('#detail-step-indicator').html(self.renderStepIndicator(data.status));

        // Timeline
        $('#detail-timeline').html(self.renderTimeline(data.riwayat || []));

        // Tombol aksi di footer modal
        if (data.status === 'disetujui') {
            $('#btn-download-transkrip').removeClass('d-none');
        } else {
            $('#btn-download-transkrip').addClass('d-none');
        }

        if (data.status === 'diajukan' || data.status === 'draft') {
            $('#btn-batalkan-pengajuan').removeClass('d-none');
        } else {
            $('#btn-batalkan-pengajuan').addClass('d-none');
        }
    },

    // ============================================================
    // BATALKAN PENGAJUAN
    // ============================================================

    konfirmasiBatalkan: function (id, noPengajuan) {
        var self = this;

        $.confirm({
            title: 'Konfirmasi Pembatalan',
            content: `
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size:2.5rem;"></i>
                    <p class="mt-3">
                        Apakah Anda yakin ingin membatalkan pengajuan transkrip?
                    </p>
                    <p class="text-muted small">
                        No. Pengajuan: <strong>${noPengajuan || id}</strong>
                    </p>
                    <p class="text-danger small">
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>`,
            type: 'orange',
            buttons: {
                batal: {
                    text: '<i class="fas fa-ban mr-1"></i>Ya, Batalkan',
                    btnClass: 'btn-danger',
                    action: function () {
                        self.batalkanPengajuan(id);
                    }
                },
                tutup: {
                    text: '<i class="fas fa-times mr-1"></i>Tidak',
                    btnClass: 'btn-secondary'
                }
            }
        });
    },

    batalkanPengajuan: function (id) {
        var self = this;

        $.ajax({
            url: '/mhs/transkrip/batalkan',
            method: 'POST',
            data: {
                _token:       $('meta[name="csrf-token"]').attr('content'),
                id_pengajuan: id
            },
            success: function (response) {
                if (response && response.status === '1') {
                    $.alert({
                        title: 'Berhasil',
                        content: 'Pengajuan transkrip berhasil dibatalkan.',
                        type: 'green',
                        onClose: function () {
                            if (self.data.table_transkrip) {
                                self.data.table_transkrip.ajax.reload();
                            }
                            self.loadStatistik();
                        }
                    });
                } else {
                    $.alert({
                        title: 'Gagal',
                        content: response.keterangan || 'Gagal membatalkan pengajuan',
                        type: 'red'
                    });
                }
            },
            error: function (xhr, status, error) {
                $.alert({
                    title: 'Error',
                    content: 'Gagal membatalkan pengajuan: ' + error,
                    type: 'red'
                });
            }
        });
    },

    // ============================================================
    // DOWNLOAD TRANSKRIP
    // ============================================================

    downloadTranskrip: function (id) {
        var self = this;

        if (!id) {
            $.alert({ title: 'Error', content: 'ID pengajuan tidak valid', type: 'red' });
            return;
        }

        console.log('Downloading transkrip for id:', id);

        var form = $('<form>', {
            method: 'POST',
            action: '/mhs/transkrip/download',
            target: '_blank'
        });

        form.append($('<input>', {
            type:  'hidden',
            name:  '_token',
            value: $('meta[name="csrf-token"]').attr('content')
        }));

        form.append($('<input>', {
            type:  'hidden',
            name:  'id_pengajuan',
            value: id
        }));

        $('body').append(form);
        form.submit();
        form.remove();

        setTimeout(function () {
            $.alert({
                title:   'Download Dimulai',
                content: 'File transkrip sedang dipersiapkan. Jika download tidak dimulai, silakan coba lagi.',
                type:    'green'
            });
        }, 1500);
    },

    // ============================================================
    // RENDER HELPERS
    // ============================================================

    getBadgeStatus: function (status) {
        if (!status) return '<span class="badge badge-secondary">-</span>';

        var map = {
            'draft':          { cls: 'badge-status-draft',     label: 'Draft',            icon: 'fa-pencil-alt' },
            'diajukan':       { cls: 'badge-status-diajukan',  label: 'Diajukan',         icon: 'fa-paper-plane' },
            'proses_kaprodi': { cls: 'badge-status-kaprodi',   label: 'Proses Kaprodi',   icon: 'fa-user-tie' },
            'proses_dekan':   { cls: 'badge-status-dekan',     label: 'Proses Dekan',     icon: 'fa-user-shield' },
            'disetujui':      { cls: 'badge-status-disetujui', label: 'Disetujui',        icon: 'fa-check-circle' },
            'ditolak':        { cls: 'badge-status-ditolak',   label: 'Ditolak',          icon: 'fa-times-circle' }
        };

        var s = map[status];
        if (!s) return `<span class="badge badge-secondary">${status}</span>`;

        return `<span class="badge ${s.cls}"><i class="fas ${s.icon} mr-1"></i>${s.label}</span>`;
    },

    renderProgressMini: function (status) {
        // 4 lingkaran kecil: Mahasiswa -> Kaprodi -> Dekan -> Selesai
        var steps = [
            { key: 'diajukan',       label: 'M' },
            { key: 'proses_kaprodi', label: 'K' },
            { key: 'proses_dekan',   label: 'D' },
            { key: 'disetujui',      label: '✓' }
        ];

        var order = ['draft', 'diajukan', 'proses_kaprodi', 'proses_dekan', 'disetujui'];
        var currentIdx = order.indexOf(status);
        var isDitolak  = status === 'ditolak';

        var html = '<div class="d-flex align-items-center justify-content-center" style="gap:2px;">';

        steps.forEach(function (step, i) {
            var stepIdx = order.indexOf(step.key);
            var cls, title;

            if (isDitolak) {
                cls   = i === 0 ? 'bg-danger' : 'bg-light border';
                title = i === 0 ? 'Ditolak' : step.label;
            } else if (currentIdx >= stepIdx) {
                cls   = 'bg-success';
                title = 'Selesai';
            } else if (currentIdx === stepIdx - 1) {
                cls   = 'bg-primary';
                title = 'Proses';
            } else {
                cls   = 'bg-light border';
                title = 'Menunggu';
            }

            html += `
                <div class="${cls} rounded-circle d-flex align-items-center justify-content-center"
                     style="width:22px;height:22px;font-size:0.6rem;color:${cls === 'bg-light border' ? '#999' : 'white'};"
                     title="${step.label} - ${title}">
                    ${step.label}
                </div>`;

            if (i < steps.length - 1) {
                html += `<div style="width:8px;height:2px;background:${currentIdx > stepIdx ? '#4caf50' : '#e0e0e0'};"></div>`;
            }
        });

        html += '</div>';
        return html;
    },

    renderStepIndicator: function (status) {
        var steps = [
            { key: 'diajukan',       label: 'Mahasiswa',  icon: 'fa-user' },
            { key: 'proses_kaprodi', label: 'Kaprodi',    icon: 'fa-user-tie' },
            { key: 'proses_dekan',   label: 'Dekan',      icon: 'fa-user-shield' },
            { key: 'disetujui',      label: 'Selesai',    icon: 'fa-check' }
        ];

        var order     = ['diajukan', 'proses_kaprodi', 'proses_dekan', 'disetujui'];
        var currentIdx = order.indexOf(status);
        var isDitolak  = status === 'ditolak';

        var html = '<div class="step-indicator">';

        steps.forEach(function (step, i) {
            var stepIdx = order.indexOf(step.key);
            var cls;

            if (isDitolak && i === 0) {
                cls = 'reject';
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
            return '<div class="text-muted text-center py-3"><i class="fas fa-info-circle mr-2"></i>Tidak ada riwayat aktivitas</div>';
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
            var tanggal = item.tgl_aktivitas ? self.formatTanggal(item.tgl_aktivitas, true) : '-';

            html += `
                <div class="timeline-item ${cls}">
                    <div class="d-flex justify-content-between">
                        <strong>${item.keterangan || item.status || '-'}</strong>
                        <small class="text-muted">${tanggal}</small>
                    </div>
                    ${item.nama_user ? `<small class="text-muted">oleh: ${item.nama_user}</small>` : ''}
                    ${item.catatan   ? `<p class="mb-0 mt-1 small text-muted">${item.catatan}</p>` : ''}
                </div>`;
        });

        return html;
    },

    // ============================================================
    // UTILITY
    // ============================================================

    formatTanggal: function (tanggal, withTime) {
        if (!tanggal) return '-';

        try {
            if (typeof moment !== 'undefined') {
                var fmt = withTime ? 'D MMM YYYY HH:mm' : 'D MMM YYYY';
                return moment(tanggal).locale('id').format(fmt);
            }

            // Fallback tanpa moment
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
    },

    isValidEmail: function (email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
};

// Initialize when document ready
jQuery(document).ready(function () {
    console.log('Document ready, initializing Transkrip module...');
    jQuery.transkrip.init();
});
