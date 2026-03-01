jQuery.khs = {
    data: {
        table_khs: null,
        filter: {
            tahun_akademik: '',
            semester: '',
            search: ''
        },
        statistik: {
            total_mk: 0,
            total_sks: 0,
            ips: 0.00,
            ipk: 0.00,
            total_sks_tempuh: 0,
            total_sks_lulus: 0
        },
        tahun_akademik_list: []
    },

    init: function () {
        var self = this;

        console.log('Initializing KHS module...');

        if (!$('#table-khs').length) {
            console.error('Table #table-khs tidak ditemukan!');
            return;
        }

        self.initSelect2();
        self.loadTahunAkademikList();
        self.initDataTable();
        self.setEvents();
        self.loadData();
        self.loadTranskrip();
    },

    initSelect2: function() {
        if (typeof $.fn.select2 !== 'undefined') {
            $(".select2").select2({
                width: '100%',
                theme: 'bootstrap4'
            });
        }
    },

    loadTahunAkademikList: function() {
        var self = this;

        $.ajax({
            url: '/mhs/khs/tahun-akademik-list',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Tahun Akademik List:', response);

                if (response && Array.isArray(response)) {
                    self.data.tahun_akademik_list = response;

                    var options = '<option value="">-- Semua Tahun Akademik --</option>';
                    response.forEach(function(item) {
                        options += `<option value="${item.id}">${item.nama}</option>`;
                    });

                    $('#filter-tahun-akademik').html(options);
                }
            },
            error: function(xhr, status, error) {
                console.warn('Gagal memuat daftar tahun akademik:', error);
            }
        });
    },

    initDataTable: function() {
        var self = this;

        if ($.fn.DataTable.isDataTable('#table-khs')) {
            $('#table-khs').DataTable().clear().destroy();
        }

        $('#table-khs tbody').empty();

        try {
            self.data.table_khs = $("#table-khs").DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: '/mhs/khs/json',
                    type: 'POST',
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.tahun_akademik = self.data.filter.tahun_akademik;
                        d.semester = self.data.filter.semester;
                        d.search = self.data.filter.search;
                        return d;
                    },
                    dataSrc: function(json) {
                        console.log('Received KHS data:', json);

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

                        if (json.statistik) {
                            self.updateStatistik(json.statistik);
                        }

                        json.recordsTotal = json.recordsTotal || (json.data ? json.data.length : 0);
                        json.recordsFiltered = json.recordsFiltered || json.recordsTotal;
                        json.draw = json.draw || 1;

                        return json.data || [];
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTable Error:', error, thrown);
                        console.error('Response:', xhr.responseText);

                        $.alert({
                            title: 'Error',
                            content: 'Gagal memuat data hasil studi: ' + (thrown || error),
                            type: 'red'
                        });
                    }
                },
                columns: [
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: "5%",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        // ✅ Mapping: kd_matakuliah (JSON) → kd_mata_kuliah (kolom)
                        data: 'kd_matakuliah',
                        searchable: true,
                        className: 'text-left',
                        width: "12%",
                        render: function(data) {
                            return `<strong>${data || '-'}</strong>`;
                        }
                    },
                    {
                        // ✅ Mapping: matakuliah (JSON) → nama_mata_kuliah (kolom)
                        data: 'matakuliah',
                        searchable: true,
                        className: 'text-left',
                        width: "25%",
                        defaultContent: '-'
                    },
                    {
                        data: 'sks',
                        searchable: false,
                        className: 'text-center',
                        width: "8%",
                        render: function(data) {
                            return `<span class="badge badge-primary">${data || 0}</span>`;
                        }
                    },
                    {
                        data: 'nilai_angka',
                        searchable: false,
                        className: 'text-center',
                        width: "10%",
                        render: function(data) {
                            // Jika nilai_angka masih "-", tampilkan "-"
                            if (!data || data === '-') return '-';
                            return parseFloat(data).toFixed(2);
                        }
                    },
                    {
                        // ✅ nilai_huruf — akan diisi nanti, sementara tampilkan "-"
                        data: 'nilai_huruf',
                        searchable: false,
                        className: 'text-center',
                        width: "10%",
                        defaultContent: '-',
                        render: function(data) {
                            if (!data || data === '-') return '-';
                            return self.getBadgeNilai(data);
                        }
                    },
                    {
                        data: 'bobot',
                        searchable: false,
                        className: 'text-center',
                        width: "8%",
                        render: function(data) {
                            if (!data || data === '-') return '-';
                            return parseFloat(data).toFixed(2);
                        }
                    },
                    {
                        data: null,
                        searchable: false,
                        className: 'text-center',
                        width: "12%",
                        render: function(data) {
                            if (!data) return '-';
                            var tahunNama = self.parseTahunAkademik(data.tahun_akademik);
                            return `${tahunNama.nama}<br/><small class="text-muted">${tahunNama.semester}</small>`;
                        }
                    },
                    {
                        // ✅ Kolom Status: menggunakan sts_nilai dari JSON
                        data: 'sts_nilai',
                        searchable: false,
                        className: 'text-center',
                        width: "10%",
                        render: function(data) {
                            if (!data || data === '-') {
                                return '<span class="badge badge-secondary">Belum Ada Nilai</span>';
                            }

                            var sts = data.toUpperCase();
                            if (sts === 'LULUS') {
                                return '<span class="status-lulus"><i class="fas fa-check-circle mr-1"></i>Lulus</span>';
                            } else if (sts === 'TIDAK LULUS') {
                                return '<span class="status-tidak-lulus"><i class="fas fa-times-circle mr-1"></i>Tidak Lulus</span>';
                            } else {
                                // Fallback: jika sts_nilai berisi nilai huruf (E/D = tidak lulus)
                                if (sts === 'E' || sts === 'D') {
                                    return '<span class="status-tidak-lulus"><i class="fas fa-times-circle mr-1"></i>Tidak Lulus</span>';
                                }
                                return `<span class="badge badge-secondary">${data}</span>`;
                            }
                        }
                    }
                ],
                drawCallback: function(settings) {
                    var api = this.api();
                    var data = api.rows().data().toArray();

                    var totalSKS = 0;
                    var totalBobot = 0;

                    data.forEach(function(item) {
                        totalSKS += parseInt(item.sks || 0);
                        // Hitung bobot hanya jika ada nilainya
                        if (item.bobot && item.bobot !== '-') {
                            totalBobot += parseFloat(item.bobot || 0) * parseInt(item.sks || 0);
                        }
                    });

                    var ips = totalSKS > 0 && totalBobot > 0 ? (totalBobot / totalSKS).toFixed(2) : '0.00';

                    $('#footer-total-sks').text(totalSKS);
                    $('#footer-ips').text(ips);

                    if (data.length === 0) {
                        $('#table-khs tbody').html(`
                            <tr>
                                <td colspan="9" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>Tidak ada data hasil studi</p>
                                        <small class="text-muted">Silakan pilih filter atau tunggu hasil nilai tersedia</small>
                                    </div>
                                </td>
                            </tr>
                        `);
                    }
                },
                paging: true,
                processing: true,
                pageLength: 25,
                ordering: false,
                lengthChange: true,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
                autoWidth: false,
                language: {
                    "emptyTable": "Tidak ada data hasil studi",
                    "processing": "Sedang memuat data...",
                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

            console.log('DataTable initialized successfully');

        } catch (e) {
            console.error('Error initializing DataTable:', e);
            $.alert({
                title: 'Error',
                content: 'Gagal inisialisasi tabel: ' + e.message,
                type: 'red'
            });
        }
    },

    setEvents: function() {
        var self = this;

        $('#btn-filter').off('click').on('click', function() {
            self.applyFilter();
        });

        $('#btn-reset-filter').off('click').on('click', function() {
            self.resetFilter();
        });

        $('#filter-search').off('keypress').on('keypress', function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                self.applyFilter();
            }
        });

        $('#btn-download-khs').off('click').on('click', function() {
            self.downloadKHS();
        });

        $(document).off('click', '#table-khs tbody tr').on('click', '#table-khs tbody tr', function() {
            if ($(this).find('.empty-state').length) {
                return;
            }

            var data = self.data.table_khs.row(this).data();
            if (data) {
                self.showDetailNilai(data);
            }
        });
    },

    applyFilter: function() {
        var self = this;

        self.data.filter.tahun_akademik = $('#filter-tahun-akademik').val();
        self.data.filter.semester = $('#filter-semester').val();
        self.data.filter.search = $('#filter-search').val().trim();

        console.log('Applying filter:', self.data.filter);

        if (self.data.table_khs) {
            self.data.table_khs.ajax.reload();
        }

        self.updateSemesterInfo();
    },

    resetFilter: function() {
        var self = this;

        $('#filter-tahun-akademik').val('').trigger('change');
        $('#filter-semester').val('').trigger('change');
        $('#filter-search').val('');

        self.data.filter = {
            tahun_akademik: '',
            semester: '',
            search: ''
        };

        if (self.data.table_khs) {
            self.data.table_khs.ajax.reload();
        }

        $('#semester-info').hide();
    },

    loadData: function() {
        var self = this;
        self.loadCurrentSemesterStats();
    },

    loadCurrentSemesterStats: function() {
        var self = this;

        $.ajax({
            url: '/mhs/khs/current-semester-stats',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Current semester stats:', response);

                if (response) {
                    $('#total_matakuliah').text(response.total_mk || 0);
                    $('#total_sks').text(response.total_sks || 0);
                    $('#ips').text(parseFloat(response.ips || 0).toFixed(2));
                    $('#ipk').text(parseFloat(response.ipk || 0).toFixed(2));
                }
            },
            error: function(xhr, status, error) {
                console.warn('Gagal memuat statistik semester:', error);
            }
        });
    },

    loadTranskrip: function() {
        var self = this;

        $.ajax({
            url: '/mhs/khs/transkrip',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Transkrip data:', response);

                if (response) {
                    $('#transkrip-total-sks').text((response.total_sks || 0) + ' SKS');
                    $('#transkrip-sks-lulus').text((response.sks_lulus || 0) + ' SKS');
                    $('#transkrip-total-mk').text((response.total_mk || 0) + ' Mata Kuliah');
                    $('#transkrip-ipk').text(parseFloat(response.ipk || 0).toFixed(2));
                }
            },
            error: function(xhr, status, error) {
                console.warn('Gagal memuat transkrip:', error);
            }
        });
    },

    updateStatistik: function(data) {
        var self = this;

        if (!data) return;

        self.data.statistik = {
            total_mk: data.total_mk || 0,
            total_sks: data.total_sks || 0,
            ips: data.ips || 0.00,
            ipk: data.ipk || 0.00,
            total_sks_tempuh: data.total_sks_tempuh || 0,
            total_sks_lulus: data.total_sks_lulus || 0
        };
    },

    updateSemesterInfo: function() {
        var self = this;

        if (self.data.filter.tahun_akademik || self.data.filter.semester) {
            var tahunNama = '-';
            var semesterNama = 'Semua Semester';

            if (self.data.filter.tahun_akademik) {
                var tahun = self.data.tahun_akademik_list.find(t => t.id == self.data.filter.tahun_akademik);
                if (tahun) {
                    tahunNama = tahun.nama;
                }
            }

            if (self.data.filter.semester) {
                var semesterMap = {
                    '1': 'Ganjil',
                    '2': 'Genap',
                    '3': 'Antara'
                };
                semesterNama = semesterMap[self.data.filter.semester] || 'Semua';
            }

            $('#semester-nama').text(semesterNama);
            $('#semester-tahun').text(tahunNama);
            $('#semester-info').show();
        } else {
            $('#semester-info').hide();
        }
    },

    getBadgeNilai: function(nilai) {
        if (!nilai || nilai === '-') return '-';

        var nilaiUpper = nilai.toUpperCase();
        var badgeClass = '';

        switch(nilaiUpper) {
            case 'A':
                badgeClass = 'badge-nilai-a';
                break;
            case 'AB':
            case 'A-':
                badgeClass = 'badge-nilai-ab';
                break;
            case 'B':
            case 'B+':
                badgeClass = 'badge-nilai-b';
                break;
            case 'BC':
            case 'B-':
                badgeClass = 'badge-nilai-bc';
                break;
            case 'C':
                badgeClass = 'badge-nilai-c';
                break;
            case 'D':
                badgeClass = 'badge-nilai-d';
                break;
            case 'E':
                badgeClass = 'badge-nilai-e';
                break;
            default:
                badgeClass = 'badge-secondary';
        }

        return `<span class="badge ${badgeClass}">${nilaiUpper}</span>`;
    },

    parseTahunAkademik: function(tahunAkademik) {
        var result = {
            nama: '-',
            semester: '-'
        };

        if (!tahunAkademik || tahunAkademik.length < 5) {
            return result;
        }

        var tahun = tahunAkademik.substring(0, 4);
        var semesterCode = tahunAkademik.substring(4, 5);

        var tahunInt = parseInt(tahun);
        var tahunBerikutnya = tahunInt + 1;
        result.nama = tahunInt + '/' + tahunBerikutnya;

        var semesterMap = {
            '1': 'Ganjil',
            '2': 'Genap',
            '3': 'Antara'
        };

        result.semester = semesterMap[semesterCode] || 'Ganjil';

        return result;
    },

    showDetailNilai: function(data) {
        var self = this;

        if (!data) return;

        // ✅ Mapping field sesuai JSON
        $('#detail-nama-mk').text(data.matakuliah || '-');
        $('#detail-kode-mk').text(data.kd_matakuliah || '-');
        $('#detail-sks').text((data.sks || 0) + ' SKS');

        var tahunParsed = self.parseTahunAkademik(data.tahun_akademik);
        $('#detail-semester').text(tahunParsed.semester);
        $('#detail-tahun-akademik').text(tahunParsed.nama);

        // nilai_angka: tampilkan "-" jika belum ada
        $('#detail-nilai-angka').text(
            (!data.nilai_angka || data.nilai_angka === '-') ? '-' : parseFloat(data.nilai_angka).toFixed(2)
        );

        // nilai_huruf: akan diisi nanti, sementara tampilkan "-"
        $('#detail-nilai-huruf').html(
            (!data.nilai_huruf || data.nilai_huruf === '-') ? '-' : self.getBadgeNilai(data.nilai_huruf)
        );

        $('#detail-bobot').text(
            (!data.bobot || data.bobot === '-') ? '-' : parseFloat(data.bobot).toFixed(2)
        );

        // ✅ Status: menggunakan sts_nilai
        var status = '-';
        if (data.sts_nilai && data.sts_nilai !== '-') {
            var sts = data.sts_nilai.toUpperCase();
            if (sts === 'LULUS') {
                status = '<span class="status-lulus"><i class="fas fa-check-circle mr-1"></i>Lulus</span>';
            } else if (sts === 'TIDAK LULUS' || sts === 'E' || sts === 'D') {
                status = '<span class="status-tidak-lulus"><i class="fas fa-times-circle mr-1"></i>Tidak Lulus</span>';
            } else {
                status = `<span class="badge badge-secondary">${data.sts_nilai}</span>`;
            }
        } else {
            status = '<span class="badge badge-secondary">Belum Ada Nilai</span>';
        }
        $('#detail-status').html(status);

        // ✅ nama_dosen dihilangkan — baris ini dihapus

        $('#modal-detail-nilai').modal('show');
    },

    downloadKHS: function() {
        var self = this;

        console.log('Downloading KHS...');

        var $btn = $('#btn-download-khs');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Mempersiapkan...');

        var form = $('<form>', {
            'method': 'POST',
            'action': '/mhs/khs/download',
            'target': '_blank'
        });

        form.append($('<input>', {
            'type': 'hidden',
            'name': '_token',
            'value': $('meta[name="csrf-token"]').attr('content')
        }));

        form.append($('<input>', {
            'type': 'hidden',
            'name': 'tahun_akademik',
            'value': self.data.filter.tahun_akademik
        }));

        form.append($('<input>', {
            'type': 'hidden',
            'name': 'semester',
            'value': self.data.filter.semester
        }));

        $('body').append(form);
        form.submit();
        form.remove();

        setTimeout(function() {
            $btn.prop('disabled', false).html('<i class="fas fa-download mr-2"></i>Download KHS');

            $.alert({
                title: 'Download Dimulai',
                content: 'File KHS sedang dipersiapkan. Jika download tidak dimulai, silakan klik tombol download lagi.',
                type: 'green'
            });
        }, 2000);
    }
};

// Initialize when document ready
jQuery(document).ready(function () {
    console.log('Document ready, initializing KHS...');
    jQuery.khs.init();
});
