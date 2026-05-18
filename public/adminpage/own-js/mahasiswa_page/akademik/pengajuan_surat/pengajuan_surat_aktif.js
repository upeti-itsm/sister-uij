jQuery.pengajuanSurat = {
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
            $('.select2').select2({
                width: '100%',
                theme: 'bootstrap4'
            });
        }
    },

    initTable: function () {
        var self = this;

        if ($.fn.DataTable.isDataTable('#table-pengajuan-surat')) {
            $('#table-pengajuan-surat').DataTable().clear().destroy();
        }

        self.data.table = $('#table-pengajuan-surat').DataTable({
            serverSide: true,
            processing: false,
            searching: false,
            ajax: {
                url: '/mhs/pengajuan-surat-aktif/json',
                type: 'POST',
                beforeSend: function () {
                    $('#table-pengajuan-surat tbody').html(
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
                    className: 'text-center align-middle text-nowrap',
                    width: '5%',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'nomor_pengajuan',
                    className: 'align-middle text-nowrap',
                    width: '15%',
                    render: function (data) {
                        return data || '-';
                    }
                },
                {
                    data: 'keperluan',
                    className: 'align-middle text-wrap',
                    width: '24%',
                    render: function (data, type, row) {
                        var html = data || '-';
                        if ((String(row.status_pengajuan) === '3' || String(row.status_pengajuan) === '5') && row.catatan) {
                            html += '<br><small class="text-danger"><i class="fas fa-comment-alt mr-1"></i>' + row.catatan + '</small>';
                        }
                        return html;
                    }
                },
                {
                    data: 'tahun_akademik',
                    className: 'text-center align-middle text-nowrap',
                    width: '12%',
                    render: function (data) {
                        return data || '-';
                    }
                },
                {
                    data: 'tgl_created',
                    className: 'text-center align-middle text-nowrap',
                    width: '18%',
                    render: function (data) {
                        return data || '-';
                    }
                },
                {
                    data: null,
                    className: 'text-center align-middle text-nowrap',
                    width: '12%',
                    render: function (data) {
                        return self.renderStatusBadge(data.status_pengajuan, data.keterangan_status);
                    }
                },
                {
                    data: null,
                    className: 'text-center align-middle text-nowrap',
                    width: '14%',
                    orderable: false,
                    searchable: false,
                    render: function (data) {
                        var id = data.id_riwayat_pengajuan_surat_aktif_mahasiswa;
                        var status = data.status_pengajuan;
                        var buttons = '';

                        if (status === '1') {
                            buttons += ' <button class="btn btn-danger btn-sm btn-delete" data-id="' + id + '">' +
                                '<i class="fa fa-trash"></i></button>';
                        }

                        if (status === '4') {
                            buttons += ' <button class="btn btn-success btn-sm btn-download" data-id="' + id + '">' +
                                '<i class="fa fa-download"></i></button>';
                        }

                        return buttons;
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

        $('#filter-status').on('change', function () {
            self.data.filterStatus = $(this).val();
            self.data.table.ajax.reload();
        });

        $('#btn-cari-pengajuan').on('click', function () {
            self.data.filterSearch = $('#cari-pengajuan').val().trim();
            self.data.table.ajax.reload();
        });

        $('#cari-pengajuan').on('keypress', function (e) {
            if (e.keyCode === 13) {
                self.data.filterSearch = $(this).val().trim();
                self.data.table.ajax.reload();
            }
        });

        $('#form-pengajuan-surat').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: '/mhs/pengajuan-surat-aktif/store',
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    if (response.status === '1') {
                        $.alert({ title: 'Berhasil', type: 'green', content: response.keterangan });
                        $('#modal-pengajuan-surat').modal('hide');
                        $('#form-pengajuan-surat')[0].reset();
                        self.data.table.ajax.reload();
                    } else {
                        $.alert({ title: 'Gagal', type: 'red', content: response.keterangan });
                    }
                },
                error: function (xhr) {
                    var msg = xhr.responseJSON ? xhr.responseJSON.keterangan : 'Terjadi kesalahan.';
                    $.alert({ title: 'Error', type: 'red', content: msg });
                }
            });
        });

        $('#table-pengajuan-surat').on('click', '.btn-delete', function () {
            var id = $(this).data('id');

            $.confirm({
                title: 'Konfirmasi Hapus',
                content: 'Apakah Anda yakin ingin menghapus pengajuan ini?',
                type: 'red',
                buttons: {
                    cancel: { text: 'Batal' },
                    confirm: {
                        text: 'Ya, Hapus',
                        btnClass: 'btn-red',
                        action: function () {
                            $.ajax({
                                url: '/mhs/pengajuan-surat-aktif/delete',
                                method: 'POST',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr('content'),
                                    id_pengajuan: id
                                },
                                success: function (response) {
                                    if (response.status === '1') {
                                        $.alert({ title: 'Berhasil', type: 'green', content: response.keterangan });
                                        self.data.table.ajax.reload();
                                    } else {
                                        $.alert({ title: 'Gagal', type: 'red', content: response.keterangan });
                                    }
                                },
                                error: function () {
                                    $.alert({ title: 'Error', type: 'red', content: 'Gagal menghapus pengajuan.' });
                                }
                            });
                        }
                    }
                }
            });
        });

        $('#table-pengajuan-surat').on('click', '.btn-download', function () {
            window.open('/mhs/pengajuan-surat-aktif/download/' + $(this).data('id'), '_blank');
        });
    },

    renderStatusBadge: function (status, label) {
        if (!status) return '<span class="badge badge-secondary">-</span>';

        var map = {
            '1': 'badge-status-dosen',
            '2': 'badge-status-dosen',
            '3': 'badge-status-ditolak',
            '4': 'badge-status-disetujui',
            '5': 'badge-status-ditolak'
        };

        var cls = map[String(status)] || 'badge-secondary';
        var text = label || status;

        return '<span class="badge ' + cls + '">' + text + '</span>';
    }
};

$(document).ready(function () {
    if ($('#table-pengajuan-surat').length) {
        jQuery.pengajuanSurat.init();
    }
});
