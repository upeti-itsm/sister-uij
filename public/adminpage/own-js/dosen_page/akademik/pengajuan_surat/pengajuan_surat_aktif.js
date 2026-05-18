jQuery.pengajuanSuratDosenAktif = {
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

        if ($.fn.DataTable.isDataTable('#table-pengajuan-surat-dosen')) {
            $('#table-pengajuan-surat-dosen').DataTable().clear().destroy();
        }

        self.data.table = $('#table-pengajuan-surat-dosen').DataTable({
            serverSide: true,
            processing: false,
            searching: false,
            ajax: {
                url: '/dosen/pengajuan-surat-aktif/json',
                type: 'POST',
                beforeSend: function () {
                    $('#table-pengajuan-surat-dosen tbody').html(
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
                        if ((String(row.status_pengajuan) === '3' || String(row.status_pengajuan) === '5') && row.catatan) {
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
                        return '<button class="btn btn-info btn-sm btn-detail-dosen" ' +
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

        $('#filter-status-dosen').on('change', function () {
            self.data.filterStatus = $(this).val();
            self.data.table.ajax.reload();
        });

        $('#btn-cari-dosen').on('click', function () {
            self.data.filterSearch = $('#cari-pengajuan-dosen').val().trim();
            self.data.table.ajax.reload();
        });

        $('#cari-pengajuan-dosen').on('keypress', function (e) {
            if (e.keyCode === 13) {
                self.data.filterSearch = $(this).val().trim();
                self.data.table.ajax.reload();
            }
        });

        $('#table-pengajuan-surat-dosen').on('click', '.btn-detail-dosen', function () {
            currentId = $(this).data('id');

            $.ajax({
                url: '/dosen/pengajuan-surat-aktif/detail',
                method: 'POST',
                data: { _token: $('meta[name="csrf-token"]').attr('content'), id_pengajuan: currentId },
                success: function (response) {
                    if (response.status === '1') {
                        var d = response.data;

                        $('#detail-dosen-nomor').text(d.nomor_pengajuan || '-');
                        $('#detail-dosen-mahasiswa').text((d.nama_mahasiswa || '-') + ' (' + (d.nim || '') + ')');
                        $('#detail-dosen-keperluan').text(d.keperluan || '-');
                        $('#detail-dosen-tgl').text(d.tgl_created || '-');
                        $('#detail-dosen-status').text(d.keterangan_status || '-');

                        if (d.catatan) {
                            $('#detail-dosen-catatan').text(d.catatan);
                            $('#row-catatan-dosen').show();
                        } else {
                            $('#detail-dosen-catatan').text('-');
                            $('#row-catatan-dosen').hide();
                        }

                        if (String(d.status_pengajuan) === '1') {
                            $('#section-aksi-dosen').show();
                        } else {
                            $('#section-aksi-dosen').hide();
                        }

                        $('#modal-detail-dosen').modal('show');
                    } else {
                        $.alert({ title: 'Error', type: 'red', content: response.keterangan });
                    }
                },
                error: function () {
                    $.alert({ title: 'Error', type: 'red', content: 'Gagal memuat detail.' });
                }
            });
        });

        $('#btn-approve-dosen').on('click', function () {
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
                                url: '/dosen/pengajuan-surat-aktif/approve',
                                method: 'POST',
                                data: { _token: $('meta[name="csrf-token"]').attr('content'), id_pengajuan: currentId },
                                success: function (response) {
                                    if (response.status === '1') {
                                        $('#modal-detail-dosen').modal('hide');
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

        $('#btn-reject-dosen').on('click', function () {
            $('#modal-detail-dosen').modal('hide');

            setTimeout(function () {
                $.confirm({
                    title: 'Tolak Pengajuan',
                    content: '<label>Alasan Penolakan <span class="text-danger">*</span></label>' +
                        '<textarea id="catatan-tolak" class="form-control mt-1" rows="3" placeholder="Tuliskan alasan penolakan..."></textarea>',
                    type: 'red',
                    buttons: {
                        cancel: {
                            text: 'Batal',
                            action: function () {
                                $('#modal-detail-dosen').modal('show');
                            }
                        },
                        confirm: {
                            text: 'Ya, Tolak', btnClass: 'btn-danger',
                            action: function () {
                                var catatan = this.$content.find('#catatan-tolak').val().trim();
                                if (!catatan) {
                                    $.alert({ title: 'Perhatian', type: 'orange', content: 'Alasan penolakan wajib diisi' });
                                    return false;
                                }
                                $.ajax({
                                    url: '/dosen/pengajuan-surat-aktif/reject',
                                    method: 'POST',
                                    data: {
                                        _token: $('meta[name="csrf-token"]').attr('content'),
                                        id_pengajuan: currentId,
                                        catatan: catatan
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
                                        $.alert({ title: 'Error', type: 'red', content: 'Gagal menolak.' });
                                    }
                                });
                            }
                        }
                    }
                });
            }, 500);
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
    if ($('#table-pengajuan-surat-dosen').length) {
        jQuery.pengajuanSuratDosenAktif.init();
    }
});
