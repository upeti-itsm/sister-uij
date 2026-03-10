jQuery.krs_riwayat = {
    data: {
        table_riwayat: null,
        filter_tahun_akademik: '',
    },

    init: function () {
        var self = this;

        if (!$('#table-riwayat-krs').length) {
            console.error('Table #table-riwayat-krs tidak ditemukan!');
            return;
        }

        // Ambil nilai default filter dari select2 (nilai pertama)
        self.data.filter_tahun_akademik = $('#filter-tahun-akademik').val();

        self.setEvents();
    },

    setEvents: function () {
        var self = this;

        // Initialize Select2
        if (typeof $.fn.select2 !== 'undefined') {
            $('#filter-tahun-akademik').select2();
        }

        // DESTROY table jika sudah ada
        if ($.fn.DataTable.isDataTable('#table-riwayat-krs')) {
            $('#table-riwayat-krs').DataTable().clear().destroy();
        }

        // Clear table content
        $('#table-riwayat-krs tbody').empty();

        try {
            self.data.table_riwayat = $('#table-riwayat-krs').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: '/mhs/krs/riwayat/json',
                    type: 'POST',
                    data: function (d) {
                        d._token              = $('meta[name="csrf-token"]').attr('content');
                        d.tahun_akademik      = self.data.filter_tahun_akademik;
                        return d;
                    },
                    dataSrc: function (json) {
                        console.log('Received riwayat data:', json);

                        if (!json || typeof json !== 'object') {
                            console.warn('Invalid JSON response');
                            return [];
                        }

                        if (!json.hasOwnProperty('data')) {
                            return Array.isArray(json) ? json : [];
                        }

                        json.recordsTotal    = json.recordsTotal    || (json.data ? json.data.length : 0);
                        json.recordsFiltered = json.recordsFiltered || json.recordsTotal;
                        json.draw            = json.draw            || 1;

                        // Update summary cards
                        if (json.summary) {
                            self.updateSummaryCards(json.summary);
                        }

                        return json.data || [];
                    },
                    complete: function () {
                        setTimeout(function () {
                            $('#table-riwayat-krs_processing').hide();
                        }, 300);
                    },
                    error: function (xhr, error, thrown) {
                        console.error('DataTable Error:', error, thrown);
                        $('#table-riwayat-krs_processing').hide();
                        $('#btn-cari-riwayat')
                            .prop('disabled', false)
                            .html('<i class="fas fa-search mr-1"></i>Cari');

                        $.alert({
                            title  : 'Error',
                            content: 'Gagal memuat data riwayat KRS: ' + (thrown || error),
                            type   : 'red'
                        });
                    }
                },
                drawCallback: function (settings) {
                    $('#table-riwayat-krs_processing').hide();

                    var totalRecords = this.api().page.info().recordsTotal;
                    $('#tot-semester').text(totalRecords);

                    if (totalRecords === 0) {
                        self.showEmptyState();
                    } else {
                        self.hideEmptyState();
                    }
                },
                scrollY       : '450px',
                scrollCollapse: true,
                columns: [
                    // No
                    {
                        data      : null,
                        searchable: false,
                        orderable : false,
                        className : 'text-center align-middle',
                        width     : '4%',
                        render    : function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    // Tahun Akademik
                    {
                        data      : null,
                        searchable: false,
                        orderable : false,
                        className : 'align-middle',
                        width     : '18%',
                        render    : function (data) {
                            if (!data) return '-';
                            return '<span class="badge badge-primary badge-tahun">' +
                                '<i class="fas fa-calendar-alt mr-1"></i>' +
                                (data.nama_tahun_akademik || '-') +
                                '</span>' +
                                '<br/>' +
                                '<small class="text-muted">' + (data.semester || '-') + '</small>';
                        }
                    },
                    // IPS
                    {
                        data      : 'ips',
                        searchable: false,
                        orderable : false,
                        className : 'text-center align-middle',
                        width     : '9%',
                        render    : function (data) {
                            if (data === null || data === undefined || data === '') {
                                return '<span class="text-muted">-</span>';
                            }
                            var val        = parseFloat(data);
                            var colorClass = val >= 3.5 ? 'text-success'
                                : val >= 3.0 ? 'text-primary'
                                    : val >= 2.5 ? 'text-warning'
                                        : 'text-danger';
                            return '<span class="font-weight-bold ' + colorClass + '">' + val.toFixed(2) + '</span>';
                        }
                    },
                    // IPK
                    {
                        data      : 'ipk',
                        searchable: false,
                        orderable : false,
                        className : 'text-center align-middle',
                        width     : '9%',
                        render    : function (data) {
                            if (data === null || data === undefined || data === '') {
                                return '<span class="text-muted">-</span>';
                            }
                            var val        = parseFloat(data);
                            var colorClass = val >= 3.5 ? 'text-success'
                                : val >= 3.0 ? 'text-primary'
                                    : val >= 2.5 ? 'text-warning'
                                        : 'text-danger';
                            return '<span class="font-weight-bold ' + colorClass + '">' + val.toFixed(2) + '</span>';
                        }
                    },
                    // SKS Maks
                    {
                        data      : 'sks_maks',
                        searchable: false,
                        orderable : false,
                        className : 'text-center align-middle',
                        width     : '11%',
                        render    : function (data) {
                            return '<span class="badge badge-secondary">' + (data || 0) + ' SKS</span>';
                        }
                    },
                    // SKS Ditempuh
                    {
                        data      : null,
                        searchable: false,
                        orderable : false,
                        className : 'text-center align-middle',
                        width     : '13%',
                        render    : function (data) {
                            if (!data) return '-';
                            var sks      = data.sks_ditempuh || 0;
                            var maks     = data.sks_maks     || 0;
                            var ratio    = maks > 0 ? (sks / maks) * 100 : 0;
                            var barClass = ratio >= 90 ? 'danger' : ratio >= 70 ? 'warning' : '';
                            return '<span class="badge badge-info">' + sks + ' SKS</span>' +
                                '<div class="sks-bar-wrap mt-1">' +
                                '<div class="sks-bar ' + barClass + '" style="width: ' + Math.min(ratio, 100) + '%"></div>' +
                                '</div>';
                        }
                    },
                    // Jumlah MK
                    {
                        data      : 'jml_matkul',
                        searchable: false,
                        orderable : false,
                        className : 'text-center align-middle',
                        width     : '12%',
                        render    : function (data) {
                            return '<span class="badge badge-warning">' + (data || 0) + ' MK</span>';
                        }
                    },
                    // Status
                    {
                        data      : 'status_krs',
                        searchable: false,
                        orderable : false,
                        className : 'text-center align-middle',
                        width     : '12%',
                        render    : function (data) {
                            return self.renderStatusBadge(data);
                        }
                    },
                    // Aksi — hanya tombol download
                    {
                        data      : null,
                        searchable: false,
                        orderable : false,
                        className : 'text-center align-middle',
                        width     : '10%',
                        render    : function (data) {
                            if (!data || !data.id_krs) return '-';

                            if (data.status_krs == 4) {
                                return '<button class="btn btn-sm btn-success btn-action btn-download-riwayat"' +
                                    ' data-id="'    + data.id_krs         + '"' +
                                    ' data-tahun="' + (data.tahun_akademik || '') + '"' +
                                    ' title="Download KRS PDF">' +
                                    '<i class="fas fa-download mr-1"></i>Download' +
                                    '</button>';
                            }

                            return '<button class="btn btn-sm btn-secondary btn-action" disabled' +
                                ' title="Download hanya tersedia jika KRS sudah disetujui final">' +
                                '<i class="fas fa-download mr-1"></i>Download' +
                                '</button>';
                        }
                    }
                ],
                paging      : true,
                processing  : true,
                pageLength  : 10,
                ordering    : false,
                lengthChange: false,
                autoWidth   : false,
                dom         : 'ltipr',
                language    : {
                    "emptyTable"  : "Tidak ada riwayat KRS",
                    "processing"  : "Sedang memuat data...",
                    "zeroRecords" : "Tidak ditemukan data yang sesuai",
                    "info"        : "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty"   : "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "paginate"    : {
                        "first"   : "Pertama",
                        "last"    : "Terakhir",
                        "next"    : "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

            console.log('DataTable table-riwayat-krs initialized successfully');

        } catch (e) {
            console.error('Error initializing table-riwayat-krs:', e);
            $.alert({
                title  : 'Error',
                content: 'Gagal inisialisasi tabel: ' + e.message,
                type   : 'red'
            });
            return;
        }

        self.setEventHandlers();
    },

    setEventHandlers: function () {
        var self = this;

        // Tombol Cari
        $('#btn-cari-riwayat').off('click').on('click', function () {
            var $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Mencari...');

            self.data.filter_tahun_akademik = $('#filter-tahun-akademik').val();

            if (self.data.table_riwayat && $.fn.DataTable.isDataTable('#table-riwayat-krs')) {
                self.data.table_riwayat.ajax.reload(function () {
                    $btn.prop('disabled', false).html('<i class="fas fa-search mr-1"></i>Cari');
                }, false);

                // Fallback timeout
                setTimeout(function () {
                    if ($btn.is(':disabled')) {
                        $btn.prop('disabled', false).html('<i class="fas fa-search mr-1"></i>Cari');
                    }
                }, 5000);
            }
        });

        // Tombol Reset
        $('#btn-reset-filter').off('click').on('click', function () {
            // Reset select2 ke option pertama
            $('#filter-tahun-akademik').val(
                $('#filter-tahun-akademik option:first').val()
            ).trigger('change');

            self.data.filter_tahun_akademik = $('#filter-tahun-akademik option:first').val();

            if (self.data.table_riwayat && $.fn.DataTable.isDataTable('#table-riwayat-krs')) {
                self.data.table_riwayat.ajax.reload(null, false);
            }
        });

        // Tombol Download
        $(document).off('click', '.btn-download-riwayat').on('click', '.btn-download-riwayat', function () {
            var id    = $(this).data('id');
            var tahun = $(this).data('tahun');
            self.downloadRiwayat(id, tahun, $(this));
        });
    },

    renderStatusBadge: function (status) {
        var statusMap = {
            0: { label: 'Draft',           cls: 'badge-secondary' },
            1: { label: 'Diajukan',        cls: 'badge-info'      },
            2: { label: 'Disetujui PA',    cls: 'badge-primary'   },
            3: { label: 'Ditolak',         cls: 'badge-danger'    },
            4: { label: 'Disetujui Final', cls: 'badge-success'   },
        };
        var info = statusMap[parseInt(status)];
        if (!info) {
            info = { label: 'Draft', cls: 'badge-secondary' };
        }
        return '<span class="badge status-badge ' + info.cls + '">' + info.label + '</span>';
    },

    downloadRiwayat: function (id, tahun, $btn) {
        var originalHtml = $btn.html();

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...');

        var form = $('<form>', {
            method: 'POST',
            action: '/mhs/krs/download-krs',
            target: '_blank'
        });

        form.append($('<input>', {
            type : 'hidden',
            name : '_token',
            value: $('meta[name="csrf-token"]').attr('content')
        }));

        form.append($('<input>', {
            type : 'hidden',
            name : 'id_krs',
            value: id
        }));

        form.append($('<input>', {
            type : 'hidden',
            name : 'tahun_akademik',
            value: tahun
        }));

        $('body').append(form);
        form.submit();
        form.remove();

        setTimeout(function () {
            $btn.prop('disabled', false).html(originalHtml);
        }, 2500);
    },

    updateSummaryCards: function (summary) {
        if (summary.total_semester !== undefined) {
            $('#tot-semester').text(summary.total_semester);
        }
        if (summary.ipk_terakhir !== undefined) {
            $('#ipk-terakhir').text(parseFloat(summary.ipk_terakhir).toFixed(2));
        }
        if (summary.total_sks_lulus !== undefined) {
            $('#tot-sks-lulus').text(summary.total_sks_lulus);
        }
        if (summary.total_mk !== undefined) {
            $('#tot-mk').text(summary.total_mk);
        }
    },

    showEmptyState: function () {
        if (!$('#empty-state-riwayat').length) {
            var url  = $('#btn-tambah-krs').attr('href') || '/mhs/krs';
            var html = '<div id="empty-state-riwayat" class="empty-state">' +
                '<i class="fas fa-folder-open"></i>' +
                '<p>Belum ada riwayat KRS yang tersedia.</p>' +
                '<a href="' + url + '" class="btn btn-primary btn-lg px-5">' +
                '<i class="fas fa-plus mr-2"></i>Buat KRS Pertama Anda' +
                '</a>' +
                '</div>';
            $('#wrap-table-riwayat').after(html);
        }
        $('#empty-state-riwayat').show();
        $('#wrap-table-riwayat').hide();
    },

    hideEmptyState: function () {
        $('#empty-state-riwayat').hide();
        $('#wrap-table-riwayat').show();
    }
};

jQuery(document).ready(function () {
    console.log('Document ready, initializing KRS Riwayat...');
    jQuery.krs_riwayat.init();
});
