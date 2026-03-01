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
        self.initLoadingOverlay();
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

    initLoadingOverlay: function () {
        var $overlay = $('#global-loading-overlay');
        $overlay.css('display', 'none');

        $(document)
            .ajaxStart(function () {
                $overlay.css('display', 'flex');
            })
            .ajaxStop(function () {
                $overlay.css('display', 'none');
            });
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
                        d.status = self.data.filter.status;
                        d.tahun  = self.data.filter.tahun;
                        d.search = self.data.filter.search;
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
                        data: 'nomor',
                        searchable: false,
                        className: 'text-center',
                        width: '5%',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    // No. Pengajuan
                    {
                        data: 'nomor_pengajuan',
                        searchable: true,
                        className: 'text-left',
                        width: '15%',
                        render: function (data) {
                            if (!data) return '-';
                            return `<strong class="text-primary" title="${data}">${data}</strong>`;
                        }
                    },
                    // Keperluan
                    {
                        data: 'keperluan',
                        searchable: true,
                        className: 'text-left',
                        width: '25%',
                        defaultContent: '-'
                    },
                    // Program Studi
                    {
                        data: 'nama_prodi',
                        searchable: false,
                        className: 'text-left',
                        width: '15%',
                        render: function (data) {
                            if (!data) return '-';
                            return `<small>${data}</small>`;
                        }
                    },
                    // Tanggal Ajuan
                    {
                        data: 'tgl_created',
                        searchable: false,
                        className: 'text-center',
                        width: '12%',
                        render: function (data) {
                            return data || '-';
                        }
                    },
                    // Status
                    {
                        data: 'status',
                        searchable: false,
                        className: 'text-center',
                        width: '13%',
                        render: function (data, type, row) {
                            return self.getBadgeStatus(data, row.keterangan_status);
                        }
                    },
                    // Progress
                    {
                        data: 'status',
                        searchable: false,
                        className: 'text-center',
                        width: '10%',
                        render: function (data) {
                            return self.renderProgressMini(data);
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

                            var id     = data.id_riwayat_pengajuan_nilai || '';
                            var status = String(data.status);
                            var noTrk  = data.nomor_pengajuan;

                            // Tombol Detail — selalu tampil
                            var btnDetail = `
                                <button class="btn btn-info btn-sm btn-detail-pengajuan"
                                        data-id="${id}"
                                        title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>`;

                            // Tombol Ajukan — hanya status 1 (Draft)
                            var btnAjukan = '';
                            if (status === '1') {
                                btnAjukan = `
                                    <button class="btn btn-primary btn-sm btn-ajukan-row ml-1"
                                            data-id="${id}"
                                            data-no="${noTrk}"
                                            title="Ajukan Draft">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>`;
                            }

                            // Tombol Hapus Draft — hanya status 1 (Draft)
                            var btnHapus = '';
                            if (status === '1') {
                                btnHapus = `
                                    <button class="btn btn-danger btn-sm btn-hapus-draft ml-1"
                                            data-id="${id}"
                                            data-no="${noTrk}"
                                            title="Hapus Draft">
                                        <i class="fas fa-trash"></i>
                                    </button>`;
                            }

                            // Tombol Batalkan — hanya status 2 (Diajukan)
                            var btnBatal = '';
                            if (status === '2') {
                                btnBatal = `
                                    <button class="btn btn-warning btn-sm btn-batalkan ml-1"
                                            data-id="${id}"
                                            data-no="${noTrk}"
                                            title="Batalkan Pengajuan">
                                        <i class="fas fa-ban"></i>
                                    </button>`;
                            }

                            // Tombol Download — hanya status 5 (Disetujui)
                            var btnDownload = '';
                            if (status === '5') {
                                btnDownload = `
                                    <button class="btn btn-success btn-sm btn-download-row ml-1"
                                            data-id="${id}"
                                            title="Download Transkrip">
                                        <i class="fas fa-download"></i>
                                    </button>`;
                            }

                            return `<div class="d-flex justify-content-center flex-wrap" style="gap:2px;">
                                        ${btnDetail}${btnAjukan}${btnHapus}${btnBatal}${btnDownload}
                                    </div>`;
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
                sDom: 'ltipr',
                lengthChange: true,
                lengthMenu: [[10, 25, 50], [10, 25, 50]],
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

        // --- Buka Modal Buat Draft ---
        $('#btn-ajukan-transkrip').off('click').on('click', function () {
            self.openModalAjukan();
        });

        // --- Simpan sebagai Draft ---
        $('#btn-simpan-ajuan').off('click').on('click', function () {
            self.simpanPengajuan();
        });

        // --- Detail (delegasi) ---
        $(document).off('click', '.btn-detail-pengajuan')
            .on('click', '.btn-detail-pengajuan', function (e) {
                e.stopPropagation();
                self.loadDetailPengajuan($(this).data('id'));
            });

        // --- Ajukan dari tabel (delegasi) — status 1 → 2 ---
        $(document).off('click', '.btn-ajukan-row')
            .on('click', '.btn-ajukan-row', function (e) {
                e.stopPropagation();
                var id = $(this).data('id');
                var no = $(this).data('no');
                self.konfirmasiAjukan(id, no);
            });

        // --- Hapus Draft dari tabel (delegasi) — status 1 ---
        $(document).off('click', '.btn-hapus-draft')
            .on('click', '.btn-hapus-draft', function (e) {
                e.stopPropagation();
                var id = $(this).data('id');
                var no = $(this).data('no');
                self.konfirmasiHapusDraft(id, no);
            });

        // --- Batalkan Pengajuan dari tabel (delegasi) — status 2 ---
        $(document).off('click', '.btn-batalkan')
            .on('click', '.btn-batalkan', function (e) {
                e.stopPropagation();
                var id = $(this).data('id');
                var no = $(this).data('no');
                self.konfirmasiBatalkan(id, no);
            });

        // --- Download dari tabel (delegasi) ---
        $(document).off('click', '.btn-download-row')
            .on('click', '.btn-download-row', function (e) {
                e.stopPropagation();
                self.downloadTranskrip($(this).data('id'));
            });

        // --- Tombol aksi di footer modal detail ---
        $('#btn-download-transkrip').off('click').on('click', function () {
            if (self.data.current_detail) {
                self.downloadTranskrip(self.data.current_detail.id_riwayat_pengajuan_nilai);
            }
        });

        $('#btn-ajukan-pengajuan').off('click').on('click', function () {
            if (self.data.current_detail) {
                var d  = self.data.current_detail;
                var no = d.nomor_pengajuan;
                $('#modal-detail-pengajuan').modal('hide');
                self.konfirmasiAjukan(d.id_riwayat_pengajuan_nilai, no);
            }
        });

        $('#btn-hapus-draft-pengajuan').off('click').on('click', function () {
            if (self.data.current_detail) {
                var d  = self.data.current_detail;
                var no = 'TRK-' + d.id_riwayat_pengajuan_nilai.substring(0, 8).toUpperCase();
                $('#modal-detail-pengajuan').modal('hide');
                self.konfirmasiHapusDraft(d.id_riwayat_pengajuan_nilai, no);
            }
        });

        $('#btn-batalkan-pengajuan').off('click').on('click', function () {
            if (self.data.current_detail) {
                var d  = self.data.current_detail;
                var no = d.nomor_pengajuan ?? '-';
                $('#modal-detail-pengajuan').modal('hide');
                self.konfirmasiBatalkan(d.id_riwayat_pengajuan_nilai, no);
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

        self.data.filter = {status: '', tahun: '', search: ''};

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
            data: {_token: $('meta[name="csrf-token"]').attr('content')},
            success: function (response) {
                console.log('Statistik transkrip:', response);
                if (response) {
                    $('#stat-total-pengajuan').text(response.total_pengajuan || 0);
                    $('#stat-diproses').text(response.diproses             || 0);
                    $('#stat-disetujui').text(response.disetujui           || 0);
                    $('#stat-ditolak').text(response.ditolak               || 0);
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
            data: {_token: $('meta[name="csrf-token"]').attr('content')},
            success: function (response) {
                console.log('Mahasiswa info:', response);
                if (response) {
                    $('#form-nim').text(response.nim           || '-');
                    $('#form-nama').text(response.nama         || '-');
                    $('#form-prodi').text(response.nama_prodi  || '-');
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
            $.alert({title: 'Error', content: 'ID pengajuan tidak valid', type: 'red'});
            return;
        }

        $.ajax({
            url: '/mhs/transkrip/detail',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id_riwayat_pengajuan_nilai: id
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
                $.alert({
                    title: 'Error',
                    content: 'Gagal memuat detail pengajuan: ' + error,
                    type: 'red'
                });
            }
        });
    },

    // ============================================================
    // MODAL BUAT / EDIT DRAFT
    // ============================================================

    openModalAjukan: function () {
        this.resetFormAjukan();
        $('#modal-ajukan-transkrip').modal('show');
    },

    resetFormAjukan: function () {
        $('#form-ajukan-transkrip')[0].reset();
        $('#keperluan').val('').trigger('change');
        $('#form-ajukan-transkrip .form-control').removeClass('is-invalid is-valid');
    },

    /**
     * Menyimpan data sebagai DRAFT (status 1).
     * Endpoint: POST /mhs/transkrip/ajukan
     */
    simpanPengajuan: function () {
        var self = this;

        if (!self.validateFormAjukan()) return;

        var $btn = $('#btn-simpan-ajuan');
        $btn.prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...');

        $.ajax({
            url: '/mhs/transkrip/ajukan',
            method: 'POST',
            data: {
                _token:    $('meta[name="csrf-token"]').attr('content'),
                keperluan: $('#keperluan').val()
            },
            success: function (response) {
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-save mr-1"></i>Simpan Draft');

                if (response && response.status === '1') {
                    $('#modal-ajukan-transkrip').modal('hide');

                    $.alert({
                        title: 'Draft Tersimpan!',
                        content: `
                            <div class="text-center">
                                <i class="fas fa-file-alt text-primary" style="font-size:3rem;"></i>
                                <p class="mt-3">Draft pengajuan berhasil disimpan.</p>
                                <p class="text-muted small">
                                    Silakan klik tombol <strong>Ajukan</strong>
                                    pada tabel untuk mengirimkan ke Kaprodi.
                                </p>
                            </div>`,
                        type: 'blue',
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
                        content: response.keterangan || 'Terjadi kesalahan saat menyimpan draft',
                        type: 'red'
                    });
                }
            },
            error: function (xhr, status, error) {
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-save mr-1"></i>Simpan Draft');

                var msg = error;
                try {
                    var res = JSON.parse(xhr.responseText);
                    msg = res.keterangan || res.message || error;
                } catch (e) {}

                $.alert({
                    title: 'Error',
                    content: 'Gagal menyimpan draft: ' + msg,
                    type: 'red'
                });
            }
        });
    },

    validateFormAjukan: function () {
        var isValid = true;

        $('#form-ajukan-transkrip .form-control').removeClass('is-invalid');

        if (!$('#keperluan').val()) {
            $('#keperluan').addClass('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            $.alert({
                title: 'Perhatian',
                content: 'Harap lengkapi semua field yang wajib diisi',
                type: 'orange'
            });
        }

        return isValid;
    },

    // ============================================================
    // AJUKAN DRAFT → DIAJUKAN (status 1 → 2)
    // ============================================================

    konfirmasiAjukan: function (id, noPengajuan) {
        var self = this;

        $.confirm({
            title: 'Konfirmasi Pengajuan',
            content: `
                <div class="text-center">
                    <i class="fas fa-paper-plane text-primary" style="font-size:2.5rem;"></i>
                    <p class="mt-3">Ajukan transkrip ini ke Kaprodi?</p>
                    <p class="text-muted small">
                        No. Pengajuan: <strong>${noPengajuan || id}</strong>
                    </p>
                    <p class="text-info small">
                        Setelah diajukan, pengajuan tidak dapat diedit.
                    </p>
                </div>`,
            type: 'blue',
            buttons: {
                ajukan: {
                    text: '<i class="fas fa-paper-plane mr-1"></i>Ya, Ajukan',
                    btnClass: 'btn-primary',
                    action: function () {
                        self.ajukanDraft(id);
                    }
                },
                batal: {
                    text: '<i class="fas fa-times mr-1"></i>Batal',
                    btnClass: 'btn-secondary'
                }
            }
        });
    },

    /**
     * Mengubah status Draft (1) → Diajukan (2).
     * Endpoint: POST /mhs/transkrip/ajukan-draft
     */
    ajukanDraft: function (id) {
        var self = this;

        $.ajax({
            url: '/mhs/transkrip/ajukan-draft',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id_riwayat_pengajuan_nilai: id
            },
            success: function (response) {
                if (response && response.status === '1') {
                    $.alert({
                        title: 'Berhasil Diajukan!',
                        content: `
                            <div class="text-center">
                                <i class="fas fa-check-circle text-success" style="font-size:3rem;"></i>
                                <p class="mt-3">Pengajuan transkrip berhasil dikirim ke Kaprodi.</p>
                                <p class="text-muted small">${response.keterangan || ''}</p>
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
                        content: response.keterangan || 'Gagal mengajukan draft',
                        type: 'red'
                    });
                }
            },
            error: function (xhr, status, error) {
                $.alert({
                    title: 'Error',
                    content: 'Gagal mengajukan: ' + error,
                    type: 'red'
                });
            }
        });
    },

    // ============================================================
    // HAPUS DRAFT (status 1)
    // ============================================================

    konfirmasiHapusDraft: function (id, noPengajuan) {
        var self = this;

        $.confirm({
            title: 'Hapus Draft',
            content: `
                <div class="text-center">
                    <i class="fas fa-trash-alt text-danger" style="font-size:2.5rem;"></i>
                    <p class="mt-3">Hapus draft pengajuan ini secara permanen?</p>
                    <p class="text-muted small">
                        No. Pengajuan: <strong>${noPengajuan || id}</strong>
                    </p>
                    <p class="text-danger small">Tindakan ini tidak dapat dibatalkan.</p>
                </div>`,
            type: 'red',
            buttons: {
                hapus: {
                    text: '<i class="fas fa-trash mr-1"></i>Ya, Hapus',
                    btnClass: 'btn-danger',
                    action: function () {
                        self.hapusDraft(id);
                    }
                },
                batal: {
                    text: '<i class="fas fa-times mr-1"></i>Batal',
                    btnClass: 'btn-secondary'
                }
            }
        });
    },

    /**
     * Menghapus draft secara permanen.
     * Endpoint: POST /mhs/transkrip/hapus-draft
     */
    hapusDraft: function (id) {
        var self = this;

        $.ajax({
            url: '/mhs/transkrip/hapus-draft',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id_riwayat_pengajuan_nilai: id
            },
            success: function (response) {
                if (response && response.status === '1') {
                    $.alert({
                        title: 'Draft Dihapus',
                        content: response.keterangan || 'Draft berhasil dihapus.',
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
                        content: response.keterangan || 'Gagal menghapus draft',
                        type: 'red'
                    });
                }
            },
            error: function (xhr, status, error) {
                $.alert({
                    title: 'Error',
                    content: 'Gagal menghapus draft: ' + error,
                    type: 'red'
                });
            }
        });
    },

    // ============================================================
    // BATALKAN PENGAJUAN (status 2 → kembali ke draft / hapus)
    // ============================================================

    konfirmasiBatalkan: function (id, noPengajuan) {
        var self = this;

        $.confirm({
            title: 'Batalkan Pengajuan',
            content: `
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size:2.5rem;"></i>
                    <p class="mt-3">Batalkan pengajuan transkrip ini?</p>
                    <p class="text-muted small">
                        No. Pengajuan: <strong>${noPengajuan || id}</strong>
                    </p>
                    <p class="text-warning small">
                        Pengajuan akan kembali ke status <strong>Draft</strong>.
                    </p>
                </div>`,
            type: 'orange',
            buttons: {
                batalkan: {
                    text: '<i class="fas fa-ban mr-1"></i>Ya, Batalkan',
                    btnClass: 'btn-warning',
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
                _token: $('meta[name="csrf-token"]').attr('content'),
                id_riwayat_pengajuan_nilai: id
            },
            success: function (response) {
                if (response && response.status === '1') {
                    $.alert({
                        title: 'Pengajuan Dibatalkan',
                        content: response.keterangan || 'Pengajuan dikembalikan ke status Draft.',
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
    // DOWNLOAD (status 5)
    // ============================================================

    downloadTranskrip: function (id) {
        if (!id) {
            $.alert({title: 'Error', content: 'ID pengajuan tidak valid', type: 'red'});
            return;
        }

        var form = $('<form>', {
            method: 'POST',
            action: '/mhs/transkrip/download',
            target: '_blank'
        });

        form.append($('<input>', {type: 'hidden', name: '_token',
            value: $('meta[name="csrf-token"]').attr('content')}));
        form.append($('<input>', {type: 'hidden', name: 'id_riwayat_pengajuan_nilai',
            value: id}));

        $('body').append(form);
        form.submit();
        form.remove();

        setTimeout(function () {
            $.alert({
                title: 'Download Dimulai',
                content: 'File transkrip sedang dipersiapkan.',
                type: 'green'
            });
        }, 1500);
    },

    // ============================================================
    // MODAL DETAIL
    // ============================================================

    renderModalDetail: function (data) {
        var self = this;
        if (!data) return;

        var noPengajuan = data.nomor_pengajuan;

        $('#detail-no-pengajuan').text(noPengajuan);
        $('#detail-status-badge').html(self.getBadgeStatus(data.status, data.keterangan_status));

        $('#detail-keperluan').text(data.keperluan   || '-');
        $('#detail-tgl-ajuan').text(data.tgl_created || '-');
        $('#detail-tgl-kaprodi').text(data.tgl_kaprodi || '-');
        $('#detail-tgl-dekan').text(data.tgl_dekan   || '-');
        $('#detail-tgl-selesai').text(data.tgl_updated || '-');

        // Alasan tolak
        if (data.status === '6' && data.alasan_tolak) {
            $('#detail-alasan-tolak').text(data.alasan_tolak);
            $('#section-alasan-tolak').show();
        } else {
            $('#section-alasan-tolak').hide();
        }

        $('#detail-step-indicator').html(self.renderStepIndicator(data.status));
        $('#detail-timeline').html(self.renderTimeline(data.riwayat || []));

        // ---- Tombol footer modal ----
        var status = String(data.status);

        // Draft (1): tampilkan Ajukan + Hapus Draft
        $('#btn-ajukan-pengajuan').toggleClass('d-none',      status !== '1');
        $('#btn-hapus-draft-pengajuan').toggleClass('d-none', status !== '1');

        // Diajukan (2): tampilkan Batalkan
        $('#btn-batalkan-pengajuan').toggleClass('d-none',    status !== '2');

        // Disetujui (5): tampilkan Download
        $('#btn-download-transkrip').toggleClass('d-none',    status !== '5');
    },

    // ============================================================
    // RENDER HELPERS
    // ============================================================

    /**
     * Status:
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
            '1': {cls: 'badge-status-draft',      icon: 'fa-file-alt'},
            '2': {cls: 'badge-status-diajukan',   icon: 'fa-paper-plane'},
            '3': {cls: 'badge-status-kaprodi',    icon: 'fa-user-tie'},
            '4': {cls: 'badge-status-dekan',      icon: 'fa-user-shield'},
            '5': {cls: 'badge-status-disetujui',  icon: 'fa-check-circle'},
            '6': {cls: 'badge-status-ditolak',    icon: 'fa-times-circle'}
        };

        var s     = map[String(statusKode)];
        var label = keteranganStatus || statusKode;

        if (!s) return `<span class="badge badge-secondary">${label}</span>`;

        return `<span class="badge ${s.cls}">
                    <i class="fas ${s.icon} mr-1"></i>${label}
                </span>`;
    },

    renderProgressMini: function (statusKode) {
        var steps     = ['M', 'K', 'D', '✓'];
        // Draft (1) belum ada progress → index -1, Diajukan (2) → step M selesai, dst.
        var doneUntil = {'1': -1, '2': 0, '3': 1, '4': 2, '5': 3};
        var doneIdx   = (doneUntil[String(statusKode)] !== undefined)
            ? doneUntil[String(statusKode)] : -1;
        var isDitolak = String(statusKode) === '6';

        var html = '<div class="d-flex align-items-center justify-content-center" style="gap:2px;">';

        steps.forEach(function (label, i) {
            var cls, color;

            if (isDitolak) {
                cls   = i === 0 ? 'bg-danger' : 'bg-light border';
                color = i === 0 ? 'white' : '#999';
            } else if (i <= doneIdx) {
                cls   = 'bg-success';
                color = 'white';
            } else if (i === doneIdx + 1) {
                cls   = 'bg-primary';
                color = 'white';
            } else {
                cls   = 'bg-light border';
                color = '#999';
            }

            html += `<div class="${cls} rounded-circle d-flex align-items-center justify-content-center"
                          style="width:22px;height:22px;font-size:0.6rem;color:${color};">
                         ${label}
                     </div>`;

            if (i < steps.length - 1) {
                var lineColor = i < doneIdx ? '#4caf50' : '#e0e0e0';
                html += `<div style="width:8px;height:2px;background:${lineColor};"></div>`;
            }
        });

        html += '</div>';
        return html;
    },

    renderStepIndicator: function (statusKode) {
        var steps = [
            {key: '2', label: 'Diajukan', icon: 'fa-paper-plane'},
            {key: '3', label: 'Kaprodi',  icon: 'fa-user-tie'},
            {key: '4', label: 'Dekan',    icon: 'fa-user-shield'},
            {key: '5', label: 'Selesai',  icon: 'fa-check'}
        ];

        var order      = ['2', '3', '4', '5'];
        var kode       = String(statusKode);
        var currentIdx = order.indexOf(kode);   // -1 jika Draft / Ditolak
        var isDitolak  = kode === '6';
        var isDraft    = kode === '1';

        var html = '<div class="step-indicator" style="padding:0 12px;gap:4px;">';

        steps.forEach(function (step, i) {
            var stepIdx = order.indexOf(step.key);
            var cls;

            if (isDraft) {
                // Semua step belum aktif
                cls = '';
            } else if (isDitolak) {
                cls = stepIdx < currentIdx  ? 'done'
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
                <div class="step-item ${cls}" style="min-width:60px;">
                    <div class="step-circle"><i class="fas ${step.icon}"></i></div>
                    <div class="step-label">${step.label}</div>
                </div>`;
        });

        html += '</div>';
        return html;
    },

    renderTimeline: function (riwayat) {
        if (!riwayat || riwayat.length === 0) {
            return `<div class="text-muted text-center py-3">
                        <i class="fas fa-info-circle mr-2"></i>Tidak ada riwayat aktivitas
                    </div>`;
        }

        var html = '';

        riwayat.forEach(function (item) {
            var clsMap = {
                '1': '',
                '2': 'active',
                '3': 'warning',
                '4': 'warning',
                '5': 'success',
                '6': 'danger'
            };

            var cls     = clsMap[String(item.status)] || '';
            var tanggal = item.tgl_aktivitas || item.tgl_updated || '-';

            html += `
                <div class="timeline-item ${cls}">
                    <div class="d-flex justify-content-between">
                        <strong>${item.keterangan_status || item.keterangan || '-'}</strong>
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

    // ============================================================
    // UTILITY
    // ============================================================

    isValidEmail: function (email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
};

// Initialize when document ready
jQuery(document).ready(function () {
    jQuery.transkrip.init();
});
