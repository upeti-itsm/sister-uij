jQuery.pengajuanSuratDekanAktif = {
    data: {
        table: null,
        filterStatus: '',
        filterSearch: ''
    },

    init: function () {
        this.initSelect2();
        this.initTable();
        this.bindEvents();
    },

    initSelect2: function () {
        if ($.fn.select2) {
            $('.select2').select2({ width: '100%', theme: 'bootstrap4' });
        }
    },

    initTable: function () {
        var self = this;

        if ($.fn.DataTable.isDataTable('#table-pengajuan-surat-dekan')) {
            $('#table-pengajuan-surat-dekan').DataTable().clear().destroy();
        }

        self.data.table = $('#table-pengajuan-surat-dekan').DataTable({
            serverSide: true,
            processing: false,
            searching: false,
            ajax: {
                url: '/dekan/pengajuan-surat-aktif/json',
                type: 'POST',
                beforeSend: function () {
                    $('#table-pengajuan-surat-dekan tbody').html(
                        '<tr><td colspan="7" class="text-center py-3 text-muted">' +
                        'Sedang memuat data...</td></tr>'
                    );
                },
                data: function (d) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.status = self.data.filterStatus;
                    d.search = self.data.filterSearch;
                    return d;
                },
                dataSrc: function (json) {
                    if (!json || !json.data) return [];
                    return json.data;
                },
                error: function (xhr) {
                    var msg = xhr.responseJSON ? xhr.responseJSON.error : 'Gagal memuat data';
                    $.alert({ title: 'Error', type: 'red', content: msg });
                }
            },
            columns: [
                {
                    data: null,
                    className: 'text-center align-middle',
                    width: '5%',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'nomor_pengajuan',
                    className: 'align-middle text-nowrap',
                    width: '20%',
                    render: function (data) {
                        return data || '-';
                    }
                },
                {
                    data: null,
                    className: 'align-middle',
                    width: '20%',
                    render: function (data) {
                        var nama = data.nama_mahasiswa || '-';
                        var nim = data.nim || '';
                        return nama + '<br><small class="text-muted">' + nim + '</small>';
                    }
                },
                {
                    data: 'keperluan',
                    className: 'align-middle',
                    width: '25%',
                    render: function (data, type, row) {
                        var html = data || '-';
                        if (String(row.status_pengajuan) === '5' && row.catatan) {
                            html += '<br><small class="text-danger"><i class="fas fa-comment-alt mr-1"></i>' + row.catatan + '</small>';
                        }
                        return html;
                    }
                },
                {
                    data: 'tgl_created',
                    className: 'text-center align-middle text-nowrap',
                    width: '15%',
                    render: function (data) {
                        return data || '-';
                    }
                },
                {
                    data: null,
                    className: 'text-center align-middle',
                    width: '10%',
                    render: function (data) {
                        return self.renderStatusBadge(data.status_pengajuan, data.keterangan_status);
                    }
                },
                {
                    data: null,
                    className: 'text-center align-middle text-nowrap',
                    width: '5%',
                    orderable: false,
                    searchable: false,
                    render: function (data) {
                        var id = data.id_riwayat_pengajuan_surat_aktif_mahasiswa;
                        var status = data.status_pengajuan;
                        return '<button class="btn btn-info btn-sm btn-detail-dekan" ' +
                            'data-id="' + id + '" data-status="' + status + '">' +
                            '<i class="fa fa-eye"></i></button>';
                    }
                }
            ],
            ordering: false,
            lengthChange: false,
            pageLength: 10,
            dom: '<"row"<"col-12"r>><"row"<"col-12"t>><"row align-items-center"<"col-sm-6"i><"col-sm-6"p>>',
            language: {
                emptyTable: 'Tidak ada data pengajuan',
                zeroRecords: 'Tidak ditemukan data yang sesuai',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
                paginate: {
                    next: 'Selanjutnya',
                    previous: 'Sebelumnya'
                }
            }
        });
    },

    bindEvents: function () {
        var self = this;
        var currentId = null;

        $('#filter-status-dekan').on('change', function () {
            self.data.filterStatus = $(this).val();
            self.data.table.ajax.reload();
        });

        $('#btn-cari-dekan').on('click', function () {
            self.data.filterSearch = $('#cari-pengajuan-dekan').val().trim();
            self.data.table.ajax.reload();
        });

        $('#cari-pengajuan-dekan').on('keypress', function (e) {
            if (e.keyCode === 13) {
                self.data.filterSearch = $(this).val().trim();
                self.data.table.ajax.reload();
            }
        });

        $('#table-pengajuan-surat-dekan').on('click', '.btn-detail-dekan', function () {
            currentId = $(this).data('id');

            $.ajax({
                url: '/dekan/pengajuan-surat-aktif/detail',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id_pengajuan: currentId
                },
                success: function (response) {
                    if (response.status === '1') {
                        var d = response.data;

                        $('#detail-dekan-nomor').text(d.nomor_pengajuan || '-');
                        $('#detail-dekan-mahasiswa').text((d.nama_mahasiswa || '-') + ' (' + (d.nim || '') + ')');
                        $('#detail-dekan-keperluan').text(d.keperluan || '-');
                        $('#detail-dekan-tgl').text(d.tgl_created || '-');
                        $('#detail-dekan-dpa').text(d.nama_dosen_pa || d.nama_dpa || '-');
                        $('#detail-dekan-status').html(self.renderStatusBadge(d.status_pengajuan, d.keterangan_status));

                        if (String(d.status_pengajuan) === '2') {
                            $('#section-aksi-dekan').show();
                        } else {
                            $('#section-aksi-dekan').hide();
                        }

                        $('#modal-detail-dekan').modal('show');
                    } else {
                        $.alert({ title: 'Error', type: 'red', content: response.keterangan });
                    }
                },
                error: function () {
                    $.alert({ title: 'Error', type: 'red', content: 'Gagal memuat detail.' });
                }
            });
        });

        $('#btn-approve-dekan').on('click', function () {
            $.confirm({
                title: 'Konfirmasi Setujui',
                content: 'Setujui pengajuan surat aktif ini?',
                type: 'green',
                buttons: {
                    cancel: { text: 'Batal' },
                    confirm: {
                        text: 'Ya, Setujui', btnClass: 'btn-success',
                        action: function () {
                            $.ajax({
                                url: '/dekan/pengajuan-surat-aktif/approve',
                                method: 'POST',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr('content'),
                                    id_pengajuan: currentId
                                },
                                success: function (response) {
                                    if (response.status === '1') {
                                        $('#modal-detail-dekan').modal('hide');
                                        $.alert({ title: 'Berhasil', type: 'green', content: response.keterangan });
                                        self.data.table.ajax.reload();
                                    } else {
                                        $.alert({ title: 'Gagal', type: 'red', content: response.keterangan });
                                    }
                                },
                                error: function () {
                                    $.alert({ title: 'Error', type: 'red', content: 'Gagal menyetujui.' });
                                }
                            });
                        }
                    }
                }
            });
        });

        $('#btn-reject-dekan').on('click', function () {
            $.confirm({
                title: 'Tolak Pengajuan',
                content: 'Apakah Anda yakin ingin menolak pengajuan surat aktif ini?',
                type: 'red',
                buttons: {
                    cancel: { text: 'Batal' },
                    confirm: {
                        text: 'Ya, Tolak', btnClass: 'btn-danger',
                        action: function () {
                            $.ajax({
                                url: '/dekan/pengajuan-surat-aktif/reject',
                                method: 'POST',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr('content'),
                                    id_pengajuan: currentId
                                },
                                success: function (response) {
                                    if (response.status === '1') {
                                        $('#modal-detail-dekan').modal('hide');
                                        $.alert({ title: 'Berhasil', type: 'green', content: response.keterangan });
                                        self.data.table.ajax.reload();
                                    } else {
                                        $.alert({ title: 'Gagal', type: 'red', content: response.keterangan });
                                    }
                                },
                                error: function () {
                                    $.alert({ title: 'Error', type: 'red', content: 'Gagal menolak.' });
                                }
                            });
                        }
                    }
                }
            });
        });
    },

    renderStatusBadge: function (status, label) {
        if (!status) return '<span class="badge badge-secondary">-</span>';
        var map = {
            '2': 'badge-status-dekan',
            '4': 'badge-status-disetujui',
            '5': 'badge-status-ditolak'
        };
        var cls = map[String(status)] || 'badge-secondary';
        var text = label || status;
        return '<span class="badge ' + cls + '">' + text + '</span>';
    }
};

$(document).ready(function () {
    if ($('#table-pengajuan-surat-dekan').length) {
        jQuery.pengajuanSuratDekanAktif.init();
    }
});
