jQuery.persetujuan_krs_dps = {
    data: {
        table_krs: null,
        table_detail_matkul: null,
        search: '',
        status_filter: '',
        tahun_akademik: '',
        current_krs_id: null
    },

    // Helper function untuk konversi value ke number
    toNumber: function(value, defaultValue) {
        defaultValue = defaultValue || 0;
        var num = parseFloat(value);
        return isNaN(num) ? defaultValue : num;
    },

    // Helper function untuk format persentase
    toPercentage: function(value, decimals) {
        decimals = decimals || 1;
        return this.toNumber(value, 0).toFixed(decimals);
    },

    init: function () {
        var self = this;

        // Pastikan DOM sudah ready
        if (! $('#table-krs-dps').length) {
            console.error('Table #table-krs-dps tidak ditemukan! ');
            return;
        }

        // Get tahun akademik yang dipilih
        self.data.tahun_akademik = $('#filter-tahun-akademik').val();

        self.loadRekapData();
        self.setEvents();
    },

    loadRekapData: function() {
        var self = this;

        $.ajax({
            url: '/dosen/krs/rekap-data',
            method: 'POST',
            dataType: 'json',
            data: {
                tahun_akademik: self.data.tahun_akademik,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success:  function(response) {
                console.log('Rekap data:', response);

                if (response && response.data && response.data.length > 0) {
                    var rekap = response.data[0];

                    // Update info alert
                    $('#rekap-total-mhs').text(self.toNumber(rekap.total_mahasiswa_bimbingan));
                    $('#rekap-sudah-krs').text(self.toNumber(rekap.total_mahasiswa_sudah_krs));
                    $('#rekap-belum-krs').text(self.toNumber(rekap.total_mahasiswa_belum_krs));
                    $('#rekap-persen-sudah').text(self.toPercentage(rekap.persentase_sudah_krs));
                    $('#rekap-persen-belum').text(self.toPercentage(rekap.persentase_belum_krs));
                    $('#rekap-persen-verifikasi').text(self.toPercentage(rekap.persentase_verifikasi));

                    // Update statistics cards
                    $('#stat-draft').text(self.toNumber(rekap.draft));
                    $('#stat-menunggu').text(self.toNumber(rekap.menunggu_persetujuan));
                    $('#stat-disetujui').text(self.toNumber(rekap.disetujui));
                    $('#stat-ditolak').text(self.toNumber(rekap.ditolak));
                    $('#stat-selesai').text(self.toNumber(rekap.selesai));
                    $('#stat-total').text(self.toNumber(rekap.total_krs));
                } else {
                    // Set default values ketika tidak ada data
                    self.resetRekapDisplay();
                }
            },
            error: function(xhr, status, error) {
                console.warn('Gagal memuat rekap data:', error);
                // Set default values ketika error
                self.resetRekapDisplay();
            }
        });
    },

    resetRekapDisplay: function() {
        $('#rekap-total-mhs').text(0);
        $('#rekap-sudah-krs').text(0);
        $('#rekap-belum-krs').text(0);
        $('#rekap-persen-sudah').text('0.0');
        $('#rekap-persen-belum').text('0.0');
        $('#rekap-persen-verifikasi').text('0.0');

        $('#stat-draft').text(0);
        $('#stat-menunggu').text(0);
        $('#stat-disetujui').text(0);
        $('#stat-ditolak').text(0);
        $('#stat-selesai').text(0);
        $('#stat-total').text(0);
    },

    setEvents:  function () {
        var self = this;

        // Initialize Select2
        if (typeof $.fn.select2 !== 'undefined') {
            $(".select2").select2();
        }

        // DESTROY table jika sudah ada
        if ($.fn.DataTable.isDataTable('#table-krs-dps')) {
            $('#table-krs-dps').DataTable().clear().destroy();
        }

        // Clear table content
        $('#table-krs-dps tbody').empty();

        // Initialize DataTable untuk daftar KRS
        try {
            self.data.table_krs = $("#table-krs-dps").DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: '/dosen/krs/json-data',
                    type: 'POST',
                    data:  function (d) {
                        d.search_mahasiswa = self.data.search;
                        d.status_krs = self.data.status_filter;
                        d.tahun_akademik = self.data.tahun_akademik;
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        console.log('Sending data:', d);
                        return d;
                    },
                    dataSrc: function(json) {
                        console.log('Received data:', json);

                        // Pastikan response valid
                        if (!json || typeof json !== 'object') {
                            console.warn('Invalid JSON response');
                            return [];
                        }

                        // Jika response tidak memiliki struktur DataTable yang benar
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

                        // Set default values untuk server-side processing
                        json.recordsTotal = json.recordsTotal || (json.data ?  json.data.length : 0);
                        json.recordsFiltered = json.recordsFiltered || json.recordsTotal;
                        json.draw = json.draw || 1;

                        return json.data || [];
                    },
                    complete: function(xhr, status) {
                        setTimeout(function() {
                            $("#table-krs-dps_processing").hide();
                        }, 300);
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTable Error:', error, thrown);
                        console.error('Response:', xhr.responseText);

                        $("#table-krs-dps_processing").hide();
                        $("#btn-cari-data").prop('disabled', false).html('<i class="fas fa-search mr-2"></i>Cari');

                        $.alert({
                            title: 'Error',
                            content:  'Gagal memuat data KRS: ' + (thrown || error),
                            type: 'red'
                        });
                    }
                },
                drawCallback: function (settings) {
                    $("#table-krs-dps_processing").hide();
                },
                scrollY: '400px',
                scrollCollapse: true,
                columns: [
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: "3%",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'nim',
                        searchable: true,
                        className: 'text-left',
                        width: "12%",
                        render: function (data) {
                            return `<strong>${data || '-'}</strong>`;
                        }
                    },
                    {
                        data: 'nama_mahasiswa',
                        searchable:  true,
                        className: 'text-left',
                        width: "20%",
                        defaultContent: '-'
                    },
                    {
                        data: 'nama_prodi',
                        searchable: false,
                        className: 'text-left',
                        width: "15%",
                        defaultContent: '-'
                    },
                    {
                        data: 'total_mk',
                        searchable: false,
                        className: 'text-center',
                        width: "8%",
                        render: function (data) {
                            return `<span class="badge badge-info">${data || 0} MK</span>`;
                        }
                    },
                    {
                        data: 'total_sks',
                        searchable: false,
                        className: 'text-center',
                        width: "8%",
                        render: function (data) {
                            return `<span class="badge badge-success">${data || 0} SKS</span>`;
                        }
                    },
                    {
                        data: 'tgl_pengajuan_formatted',
                        searchable: false,
                        className: 'text-center',
                        width: "12%",
                        render: function (data, type, row) {
                            if (! data || data === '-') return '<small class="text-muted">Belum diajukan</small>';
                            return data;
                        }
                    },
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: "10%",
                        render: function (data) {
                            if (! data) return '-';
                            var statusClass = self.getStatusClass(data.status_krs);
                            return `<span class="badge ${statusClass}">${data.status_text || '-'}</span>`;
                        }
                    },
                    {
                        data:  null,
                        searchable:  false,
                        orderable: false,
                        className: 'text-center',
                        width: "12%",
                        render: function (data) {
                            if (!data || ! data.id_krs_mahasiswa) return '-';

                            var buttons = `
                                <button class="btn btn-sm btn-info btn-detail-krs" data-id="${data.id_krs_mahasiswa}" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                            `;

                            // Hanya tampilkan tombol approve/reject jika status = 1 (Menunggu Persetujuan)
                            if (data.status_krs === 1) {
                                buttons += `
                                    <button class="btn btn-sm btn-success btn-approve-krs"
                                            data-id="${data.id_krs_mahasiswa}"
                                            data-nim="${data.nim}"
                                            data-nama="${data.nama_mahasiswa}"
                                            data-mk="${data.total_mk}"
                                            data-sks="${data.total_sks}"
                                            title="Setujui KRS">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger btn-reject-krs"
                                            data-id="${data.id_krs_mahasiswa}"
                                            data-nim="${data.nim}"
                                            data-nama="${data.nama_mahasiswa}"
                                            data-mk="${data.total_mk}"
                                            data-sks="${data.total_sks}"
                                            title="Tolak KRS">
                                        <i class="fas fa-times"></i>
                                    </button>
                                `;
                            }

                            // Tombol download jika status = 4 (Disetujui Final)
                            if (data.status_krs === 4) {
                                buttons += `
                                    <button class="btn btn-sm btn-primary btn-download-krs"
                                            data-id="${data.id_krs_mahasiswa}"
                                            data-nim="${data.nim}"
                                            data-nama="${data.nama_mahasiswa}"
                                            title="Download KRS">
                                        <i class="fas fa-download"></i>
                                    </button>
                                `;
                            }

                            return buttons;
                        }
                    }
                ],
                paging: true,
                processing: true,
                pageLength: 10,
                ordering: false,
                lengthChange: false,
                autoWidth: false,
                dom: 'ltipr',
                language: {
                    "emptyTable": "Tidak ditemukan data KRS mahasiswa bimbingan",
                    "processing": "Sedang memuat data...",
                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered":  "(disaring dari _MAX_ total data)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next":  "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

            console.log('DataTable table-krs-dps initialized successfully');

        } catch (e) {
            console.error('Error initializing table-krs-dps:', e);
            $.alert({
                title: 'Error',
                content: 'Gagal inisialisasi tabel:  ' + e.message,
                type: 'red'
            });
            return;
        }

        // Event handlers
        self.setEventHandlers();
    },

    setEventHandlers: function() {
        var self = this;

        // Filter tahun akademik
        $("#btn-filter-tahun").off('click').on('click', function() {
            var $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Memuat...');

            self.data.tahun_akademik = $('#filter-tahun-akademik').val();

            // Reload rekap data
            self.loadRekapData();

            // Reload table
            if (self.data.table_krs && $.fn.DataTable.isDataTable('#table-krs-dps')) {
                self.data.table_krs.ajax.reload(function() {
                    $btn.prop('disabled', false).html('<i class="fas fa-sync mr-2"></i>Terapkan');
                }, false);
            } else {
                $btn.prop('disabled', false).html('<i class="fas fa-sync mr-2"></i>Terapkan');
            }
        });

        // Tahun akademik change
        $("#filter-tahun-akademik").off('change').on('change', function() {
            $("#btn-filter-tahun").click();
        });

        // Search events
        $("#btn-cari-data").off('click').on('click', function() {
            var $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Mencari...');

            self.data.search = $("#cari-mahasiswa").val().trim();
            self.data.status_filter = $("#filter-status").val();

            if (self.data.table_krs && $.fn.DataTable.isDataTable('#table-krs-dps')) {
                $("#table-krs-dps_processing").hide();

                self.data.table_krs.ajax.reload(function(json) {
                    $btn.prop('disabled', false).html('<i class="fas fa-search mr-2"></i>Cari');
                    $("#table-krs-dps_processing").hide();
                }, false);

                setTimeout(function() {
                    if ($btn.is(': disabled')) {
                        $btn.prop('disabled', false).html('<i class="fas fa-search mr-2"></i>Cari');
                        $("#table-krs-dps_processing").hide();
                    }
                }, 5000);
            }
        });

        // Enter key search
        $("#cari-mahasiswa").off('keypress').on('keypress', function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                $("#btn-cari-data").click();
            }
        });

        // Filter status change
        $("#filter-status").off('change').on('change', function() {
            $("#btn-cari-data").click();
        });

        // Detail button
        $(document).off('click', '.btn-detail-krs').on('click', '.btn-detail-krs', function() {
            var id = $(this).data('id');
            self.showDetailKRS(id);
        });

        // Approve button
        $(document).off('click', '.btn-approve-krs').on('click', '.btn-approve-krs', function() {
            var id = $(this).data('id');
            var nim = $(this).data('nim');
            var nama = $(this).data('nama');
            var mk = $(this).data('mk');
            var sks = $(this).data('sks');

            self.showModalApprove(id, nim, nama, mk, sks);
        });

        // Reject button
        $(document).off('click', '.btn-reject-krs').on('click', '.btn-reject-krs', function() {
            var id = $(this).data('id');
            var nim = $(this).data('nim');
            var nama = $(this).data('nama');
            var mk = $(this).data('mk');
            var sks = $(this).data('sks');

            self.showModalReject(id, nim, nama, mk, sks);
        });

        // Download button
        $(document).off('click', '.btn-download-krs').on('click', '.btn-download-krs', function() {
            var id = $(this).data('id');
            var nim = $(this).data('nim');
            var nama = $(this).data('nama');

            self.downloadKRSMahasiswa(id, nim, nama);
        });

        // Konfirmasi setujui
        $("#btn-setujui-krs").off('click').on('click', function() {
            self.approveKRS();
        });

        // Konfirmasi tolak
        $("#btn-tolak-krs").off('click').on('click', function() {
            self.rejectKRS();
        });
    },

    getStatusClass: function(status) {
        switch(status) {
            case 0: return 'badge-secondary';
            case 1: return 'badge-warning';
            case 2: return 'badge-info';
            case 3: return 'badge-danger';
            case 4: return 'badge-success';
            default: return 'badge-secondary';
        }
    },

    showDetailKRS: function(id) {
        var self = this;

        // Show loading dialog
        var loadingDialog = $.dialog({
            title: false,
            content: '<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><br/><p class="mt-2">Memuat data...</p></div>',
            closeIcon: false,
            backgroundDismiss: false
        });

        $.ajax({
            url: '/dosen/krs/detail',
            method: 'POST',
            data: {
                id_krs:  id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                loadingDialog.close();

                if (response && response.data) {
                    var data = response.data;

                    // Update info mahasiswa
                    $('#detail-nim').text(data.nim || '-');
                    $('#detail-nama').text(data.nama_mahasiswa || '-');
                    $('#detail-prodi').text(data.nama_prodi || '-');
                    $('#detail-ta').text(data.tahun_akademik || '-');
                    $('#detail-tgl-pengajuan').text(data.tgl_pengajuan_formatted || '-');

                    var statusClass = self.getStatusClass(data.status_krs);
                    $('#detail-status').removeClass().addClass('badge ' + statusClass).text(data.status_text || '-');

                    // Update komentar
                    if (data.komentar_dps) {
                        $('#detail-komentar-dps').html(`<i class="fas fa-comment-dots mr-2"></i><span>${data.komentar_dps}</span>`);
                        $('#section-komentar').show();
                    } else {
                        $('#detail-komentar-dps').html('<i class="fas fa-comment-dots mr-2"></i><span class="text-muted">Belum ada komentar</span>');
                        $('#section-komentar').show();
                    }

                    // Load mata kuliah
                    self.loadDetailMataKuliah(id);

                    $('#modal-detail-krs').modal('show');
                }
            },
            error: function(xhr) {
                loadingDialog.close();
                $.alert({
                    title: 'Error',
                    content: 'Gagal memuat detail KRS: ' + (xhr.responseJSON?.message || xhr.statusText),
                    type: 'red'
                });
            }
        });
    },

    loadDetailMataKuliah: function(id_krs) {
        var self = this;

        $.ajax({
            url: '/dosen/krs/detail-matkul',
            method: 'POST',
            data: {
                id_krs: id_krs,
                _token:  $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response && response.data) {
                    var data = response.data;

                    // DESTROY table jika sudah ada
                    if ($.fn.DataTable.isDataTable('#table-detail-matkul')) {
                        $('#table-detail-matkul').DataTable().clear().destroy();
                    }

                    // Clear table content
                    $('#table-detail-matkul tbody').empty();

                    // Initialize DataTable
                    self.data.table_detail_matkul = $("#table-detail-matkul").DataTable({
                        data: data,
                        columns:  [
                            {
                                data: null,
                                className: 'text-center',
                                render: function (data, type, row, meta) {
                                    return meta.row + 1;
                                }
                            },
                            {
                                data: 'kd_mata_kuliah',
                                className: 'text-left',
                                render: function (data) {
                                    return `<strong>${data || '-'}</strong>`;
                                }
                            },
                            {
                                data: 'nama_mata_kuliah',
                                className: 'text-left',
                                defaultContent: '-'
                            },
                            {
                                data: 'nama_kelas',
                                className: 'text-center',
                                render: function (data) {
                                    return `<span class="badge badge-primary">${data || '-'}</span>`;
                                }
                            },
                            {
                                data: 'sks',
                                className: 'text-center',
                                render:  function (data) {
                                    return `<span class="badge badge-success">${data || 0}</span>`;
                                }
                            },
                            {
                                data: 'hari_nama',
                                className: 'text-left',
                                defaultContent: '-'
                            },
                            {
                                data: null,
                                className: 'text-left',
                                render: function (data) {
                                    if (! data) return '-';
                                    return `${data.jam_mulai || '-'} - ${data.jam_selesai || '-'}`;
                                }
                            }
                        ],
                        paging: false,
                        searching: false,
                        ordering:  false,
                        info: false,
                        language:  {
                            "emptyTable": "Tidak ada mata kuliah"
                        }
                    });

                    // Update total SKS
                    var totalSKS = data.reduce((sum, item) => sum + parseInt(item.sks || 0), 0);
                    $('#detail-total-sks').text(totalSKS);
                }
            },
            error: function(xhr) {
                console.error('Failed to load mata kuliah:', xhr);
            }
        });
    },

    showModalApprove: function(id, nim, nama, mk, sks) {
        var self = this;

        $('#approval-id-krs').val(id);
        $('#approval-nim-mhs').text(nim);
        $('#approval-nama-mhs').text(nama);
        $('#approval-total-mk').text(mk);
        $('#approval-total-sks').text(sks);
        $('#approval-komentar').val('');

        $('#modal-persetujuan').modal('show');
    },

    showModalReject: function(id, nim, nama, mk, sks) {
        var self = this;

        $('#reject-id-krs').val(id);
        $('#reject-nim-mhs').text(nim);
        $('#reject-nama-mhs').text(nama);
        $('#reject-total-mk').text(mk);
        $('#reject-total-sks').text(sks);
        $('#reject-alasan').val('');

        $('#modal-penolakan').modal('show');
    },

    approveKRS: function() {
        var self = this;
        var id_krs = $('#approval-id-krs').val();
        var komentar = $('#approval-komentar').val();

        $.confirm({
            title: 'Konfirmasi Persetujuan',
            content: 'Apakah Anda yakin ingin menyetujui KRS ini?',
            type: 'green',
            typeAnimated: true,
            buttons: {
                ya: {
                    text: 'Ya, Setujui',
                    btnClass: 'btn-green',
                    action: function() {
                        $.ajax({
                            url: '/dosen/krs/approve',
                            method: 'POST',
                            data: {
                                id_krs: id_krs,
                                komentar: komentar,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            beforeSend: function() {
                                $("#btn-setujui-krs").prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
                            },
                            success: function(response) {
                                if (response.status === "1" || response.status === 1) {
                                    $.alert({
                                        title:  "Berhasil",
                                        type: "green",
                                        content: response.keterangan || 'KRS berhasil disetujui',
                                        onClose: function() {
                                            $('#modal-persetujuan').modal('hide');
                                            self.data.table_krs.ajax.reload(null, false);
                                            self.loadRekapData();
                                        }
                                    });
                                } else {
                                    $.alert({
                                        title: "Gagal",
                                        type: "red",
                                        content: response.keterangan || 'Gagal menyetujui KRS'
                                    });
                                }
                            },
                            error: function(xhr) {
                                $.alert({
                                    title:  "Error",
                                    type: "red",
                                    content: "Terjadi kesalahan sistem:  " + (xhr.responseJSON?.message || xhr.statusText)
                                });
                            },
                            complete: function() {
                                $("#btn-setujui-krs").prop('disabled', false).html('<i class="fas fa-check mr-1"></i> Setujui KRS');
                            }
                        });
                    }
                },
                batal: {
                    text: 'Batal',
                    btnClass: 'btn-default'
                }
            }
        });
    },

    rejectKRS: function() {
        var self = this;
        var id_krs = $('#reject-id-krs').val();
        var alasan = $('#reject-alasan').val().trim();

        if (!alasan) {
            $.alert({
                title: 'Peringatan',
                content: 'Alasan penolakan wajib diisi! ',
                type: 'orange'
            });
            $('#reject-alasan').focus();
            return;
        }

        $.confirm({
            title: 'Konfirmasi Penolakan',
            content: 'Apakah Anda yakin ingin menolak KRS ini?',
            type: 'red',
            typeAnimated: true,
            buttons: {
                ya: {
                    text: 'Ya, Tolak',
                    btnClass:  'btn-red',
                    action: function() {
                        $.ajax({
                            url: '/dosen/krs/reject',
                            method: 'POST',
                            data:  {
                                id_krs: id_krs,
                                alasan: alasan,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            beforeSend: function() {
                                $("#btn-tolak-krs").prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
                            },
                            success: function(response) {
                                if (response.status === "1" || response.status === 1) {
                                    $.alert({
                                        title: "Berhasil",
                                        type:  "green",
                                        content: response.keterangan || 'KRS berhasil ditolak',
                                        onClose: function() {
                                            $('#modal-penolakan').modal('hide');
                                            self.data.table_krs.ajax.reload(null, false);
                                            self.loadRekapData();
                                        }
                                    });
                                } else {
                                    $.alert({
                                        title:  "Gagal",
                                        type: "red",
                                        content: response.keterangan || 'Gagal menolak KRS'
                                    });
                                }
                            },
                            error: function(xhr) {
                                $.alert({
                                    title:  "Error",
                                    type: "red",
                                    content: "Terjadi kesalahan sistem: " + (xhr.responseJSON?.message || xhr.statusText)
                                });
                            },
                            complete:  function() {
                                $("#btn-tolak-krs").prop('disabled', false).html('<i class="fas fa-ban mr-1"></i> Tolak KRS');
                            }
                        });
                    }
                },
                batal:  {
                    text: 'Batal',
                    btnClass: 'btn-default'
                }
            }
        });
    },

    downloadKRSMahasiswa: function(id_krs, nim, nama) {
        var self = this;

        console.log('Downloading KRS untuk mahasiswa:', nim, nama);

        $.confirm({
            title: 'Download KRS',
            content: `Apakah Anda ingin mendownload KRS untuk mahasiswa: <br/><strong>${nim} - ${nama}</strong>? `,
            type: 'blue',
            typeAnimated: true,
            buttons: {
                download: {
                    text: 'Download',
                    btnClass: 'btn-primary',
                    action: function() {
                        // Buat form dan submit untuk download
                        var form = $('<form>', {
                            'method': 'POST',
                            'action': '/dosen/krs/download',
                            'target': '_blank'
                        });

                        // Tambahkan CSRF token
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': '_token',
                            'value':  $('meta[name="csrf-token"]').attr('content')
                        }));

                        // Tambahkan id_krs
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': 'id_krs',
                            'value': id_krs
                        }));

                        // Submit form
                        $('body').append(form);
                        form.submit();
                        form.remove();

                        // Show success notification
                        $.alert({
                            title: 'Download Dimulai',
                            content:  'File KRS sedang dipersiapkan.Jika download tidak dimulai, silakan coba lagi.',
                            type: 'green'
                        });
                    }
                },
                batal: {
                    text:  'Batal',
                    btnClass: 'btn-default'
                }
            }
        });
    }
};

jQuery(document).ready(function () {
    console.log('Document ready, initializing Persetujuan KRS DPS...');
    jQuery.persetujuan_krs_dps.init();
});
