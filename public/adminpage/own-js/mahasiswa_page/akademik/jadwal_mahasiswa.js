jQuery.jadwal_matakuliah = {
    data: {
        hari: [
            'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUM\'AT', 'SABTU', 'MINGGU'
        ],
        isSyncCanceled: false,
        log_table_jadwal_kuliah: $("#log-table-jadwal-kuliah").DataTable({
            scrollY: '300px',
            columns: [
                { width: "5%", sClass: 'text-center', searchable: false },
                { width: "50%", searchable: false },
                { width: "45%", sClass: 'text-center', searchable: false },
                { searchable: true, visible: false },
                { searchable: true, visible: false },
            ],
            scrollCollapse: true,
            paging: true,
            processing: true,
            pageLength: 10,
            ordering: false,
            lengthChange: false,
            autoWidth: false,
            sDom: 'ltipr',
            language: {
                "emptyTable": "Tidak ditemukan data"
            },
        }),
        table_jadwal_kuliah: $("#table-jadwal-kuliah"),
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        // Option Data
        $(".select2").select2();
        // Table With DataTable
        $("#prodi").change(function () {
            self.data.table_jadwal_kuliah.ajax.reload();
        });
        $("#angkatan").change(function () {
            self.data.table_jadwal_kuliah.ajax.reload();
        });
        self.data.table_jadwal_kuliah = $("#table-jadwal-kuliah").DataTable({
            serverSide: true,
            ajax: {
                url: '/mhs/akademik/perkuliahan/jadwal-mahasiswa/json',
                type: 'post',
                data: function (data) {
                    data.tahun_akademik = $("#tahun_akademik").val();
                }
            },
            scrollY: '500px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'nomor',
                    searchable: false,
                    sClass: 'text-center',
                    width: "5%"
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%",
                    render: function (data) {
                        return "<p>" + data.nama_mata_kuliah + " (" + data.nama_kelas + ") - " + data.nama_prodi + "</p>";
                    }
                },
                {
                    data: 'jml_sks',
                    searchable: false,
                    sClass: 'text-center',
                    width: "5%",
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "10%",
                    render: function (data) {
                        return "<p>" + self.data.hari[data.hari] + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        return "<p>" + data.jam_mulai + " s/d " + data.jam_selesai + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        return "<button class='btn btn-sm btn-info-soft'>" + data.persentase_absensi + "</button>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "16%",
                    render: function (data) {
                        return "<button class='btn btn-sm btn-success btn-rekap-kehadiran' data-id='" + data.id_jadwal_kuliah + "' data-nama='" + data.nama_mata_kuliah + "' title='Rekap Kehadiran'>" +
                            "<i class='fas fa-clipboard-list'></i> Rekap Kehadiran" +
                            "</button>";
                    }
                },
                {
                    data: 'nama_mata_kuliah',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
            ],
            paging: true,
            processing: true,
            pageLength: 10,
            ordering: false,
            lengthChange: false,
            autoWidth: false,
            sDom: 'ltipr',
            language: {
                "emptyTable": "Tidak ditemukan data"
            }
        });
        $("#btn-cari-data-jadwal-kuliah").click(function () {
            self.data.table_jadwal_kuliah.search($("#cari-data-jadwal-kuliah").val()).draw();
        });
        $("#cari-data-jadwal-kuliah").keyup(function () {
            if (this.value === "") {
                self.data.table_jadwal_kuliah.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.table.search(this.value).draw();
            }
        });

        $("#openModalBtn").click(function () {
            $("#modal-scanner").modal('show');
        });

        $("#btn-sync-ulang-jadwal-kuliah").click(function () {
            $.ajax({
                url: '/mhs/akademik/perkuliahan/jadwal-mahasiswa/json-by-tahun-akademik',
                method: 'post',
                data: {
                    tahun_akademik: $("#tahun_akademik_aktif").val()
                },
                success: function (result) {
                    if (result[0]) {
                        $("#modal-sync-jadwal-kuliah").modal('hide');
                        self.isSyncCanceled = false;
                        $("#progress-bar-syncron-ulang-jadwal-kuliah").show();
                        $("#log-syncron-ulang-jadwal-kuliah").show();
                        $("#btn-cancel-syncron-ulang-jadwal-kuliah").show();
                        $("#loading-progress-jadwal-kuliah").show();
                        $("#keterangan-progress-jadwal-kuliah").text("Mohon menunggu hingga proses sinkronisasi selesai ...");
                        self.next_data(result);
                    } else {
                        $.alert({
                            title: "INFORMASI",
                            type: "red",
                            content: "Jadwal Tidak Ditemukan"
                        });
                    }
                },
                error: function (result) {
                    $.alert({
                        title: "Peringatan",
                        type: "red",
                        content: result.responseJSON.keterangan
                    });
                }
            })
        });
        $("#btn-cari-log-jadwal-kuliah").click(function () {
            self.data.log_table_jadwal_kuliah.search($("#cari-log-data-center").val()).draw();
        });
        $("#cari-log-jadwal-kuliah").keyup(function () {
            if (this.value === "") {
                self.data.log_table_jadwal_kuliah.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.log_table_jadwal_kuliah.search(this.value).draw();
            }
        });
        $("#status-log-jadwal-kuliah").on('change', function () {
            self.data.log_table_jadwal_kuliah.search(this.value).draw();
        });
        $("#btn-failed-log-jadwal-kuliah").click(function () {
            $("#status-log-jadwal-kuliah").val('failed').trigger('change');
        });
        $("#btn-inserted-log-jadwal-kuliah").click(function () {
            $("#status-log-jadwal-kuliah").val('inserted').trigger('change');
        });
        $("#btn-updated-log-jadwal-kuliah").click(function () {
            $("#status-log-jadwal-kuliah").val('updated').trigger('change');
        });
        $("#btn-tutup-log-jadwal-kuliah").click(function () {
            $("#log-syncron-ulang-jadwal-kuliah").hide();
        });
        $("#btn-cancel-syncron-ulang-jadwal-kuliah").click(function () {
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Proses sinkronisasi akan dihentikan, apakah anda yakin ?',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            self.isSyncCanceled = true;
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red'
                    }
                }
            })
        });
        $("#tahun_akademik, #prodi").on('change', function () {
            self.data.table_jadwal_kuliah.ajax.reload();
        });

        $(document).on('click', '.btn-rekap-kehadiran', function () {
            var id_jadwal = $(this).data('id');
            var nama_matkul = $(this).data('nama');
            $('#modal-rekap-kehadiran').modal('show');
            $('#rekap-matkul-title').text(nama_matkul);
            $('#rekap-kehadiran-loading').show();
            $('#rekap-kehadiran-content').hide().html('');
            $.ajax({
                url: '/mhs/akademik/perkuliahan/jadwal-mahasiswa/rekap-kehadiran',
                method: 'post',
                data: { id_jadwal_kuliah: id_jadwal },
                success: function (result) {
                    $('#rekap-kehadiran-loading').hide();
                    $('#rekap-kehadiran-content').show();
                    if (result.length === 0) {
                        $('#rekap-kehadiran-content').html('<p class="text-center text-muted">Belum ada data rekap kehadiran.</p>');
                        return;
                    }
                    var d = result[0];
                    var html = '<div class="row mb-3">';
                    html += '<div class="col-md-6"><strong>Mata Kuliah:</strong> ' + d.nama_mata_kuliah + '</div>';
                    html += '<div class="col-md-6"><strong>Kelas:</strong> ' + d.nama_kelas + '</div>';
                    html += '<div class="col-md-6 mt-1"><strong>Dosen:</strong> ' + d.nama_dosen + '</div>';
                    html += '<div class="col-md-6 mt-1"><strong>SKS:</strong> ' + d.sks + '</div>';
                    html += '</div>';
                    html += '<div class="row mb-3">';
                    html += '<div class="col-md-3"><div class="card bg-success text-white text-center p-2"><div class="font-weight-bold">Hadir</div><div class="h4 mb-0">' + (d.total_hadir || 0) + '</div></div></div>';
                    html += '<div class="col-md-3"><div class="card bg-warning text-white text-center p-2"><div class="font-weight-bold">Izin</div><div class="h4 mb-0">' + (d.total_izin || 0) + '</div></div></div>';
                    html += '<div class="col-md-3"><div class="card bg-info text-white text-center p-2"><div class="font-weight-bold">Sakit</div><div class="h4 mb-0">' + (d.total_sakit || 0) + '</div></div></div>';
                    html += '<div class="col-md-3"><div class="card bg-danger text-white text-center p-2"><div class="font-weight-bold">Tidak Hadir</div><div class="h4 mb-0">' + (d.total_tidak_hadir || 0) + '</div></div></div>';
                    html += '</div>';
                    html += '<div class="table-responsive"><table class="table table-bordered table-sm text-center"><thead class="thead-dark"><tr><th>Pertemuan</th><th>Tanggal</th><th>Kehadiran</th><th>Nama Materi</th><th>Link Materi</th></tr></thead><tbody>';
                    for (var i = 1; i <= 16; i++) {
                        var tgl = d['tgl_pertemuan_' + i];
                        if (!tgl) continue;
                        var hadir = d['pertemuan_' + i];
                        var path = d['path_bukti_ajar_' + i];
                        var nama_materi = d['materi_' + i];
                        var badge = '';
                        if (hadir === 1) badge = '<span class="badge badge-success">Hadir</span>';
                        else if (hadir === 2) badge = '<span class="badge badge-info">Sakit</span>';
                        else if (hadir === 3) badge = '<span class="badge badge-warning">Izin</span>';
                        else badge = '<span class="badge badge-danger">Tidak Hadir</span>';
                        var namaMateriHtml = nama_materi ? nama_materi : '<span class="text-muted">-</span>';
                        var linkMateri = path ? '<a href="' + path + '" target="_blank" class="btn btn-xs btn-primary-soft btn-sm"><i class="fas fa-file-alt mr-1"></i>Lihat Materi</a>' : '<span class="text-muted">-</span>';
                        html += '<tr><td>' + i + '</td><td>' + tgl + '</td><td>' + badge + '</td><td>' + namaMateriHtml + '</td><td>' + linkMateri + '</td></tr>';
                    }
                    html += '</tbody></table></div>';
                    $('#rekap-kehadiran-content').html(html);
                },
                error: function () {
                    $('#rekap-kehadiran-loading').hide();
                    $('#rekap-kehadiran-content').show().html('<p class="text-center text-danger">Gagal memuat data rekap kehadiran.</p>');
                }
            });
        });
    },
    next_data: function (data, index = 0, progres = 0, failed = 0, inserted = 0, updated = 0) {
        var self = this;
        var n = data.length;
        $.ajax({
            url: '/mhs/akademik/perkuliahan/jadwal-mahasiswa/synchron',
            method: 'post',
            data: {
                'jadwal_kuliah_id': data[index].jadwal_kuliah_id,
                'nim': data[index].NPK,
                'kelas_id': data[index].kelas_id,
                'nama_dosen': data[index].nama_dosen,
                'nama_mata_kuliah': data[index].nama_mata_kuliah,
                'tahun_akademik': data[index].tahun_akademik,
            },
            success: function (result) {
                if (result.status) {
                    self.data.log_table_jadwal_kuliah.row.add([
                        (index + 1),
                        data[index].nama_mata_kuliah + " (" + data[index].kelas_id + ")",
                        "<i class='fas fa-check-circle text-success p-1'></i> " + result.keterangan,
                        data[index].nama_mata_kuliah,
                        "inserted"
                    ]).draw();
                    inserted++;
                } else {
                    self.data.log_table_jadwal_kuliah.row.add([
                        (index + 1),
                        data[index].nama_mata_kuliah + " (" + data[index].kelas_id + ")",
                        "<i class='fas fa-check-circle text-danger p-1'></i> " + result.keterangan,
                        data[index].nama_mata_kuliah,
                        "gagal"
                    ]).draw();
                    failed++;
                }
                progres++;
            },
            complete: function () {
                $("#progress-bar-jadwal-kuliah").width((progres / n * 100).toFixed(2) + '%');
                $("#progress-text-jadwal-kuliah").text((progres / n * 100).toFixed(2) + '% Complete');
                $("#btn-failed-log-jadwal-kuliah").text("Failed : " + failed);
                $("#btn-inserted-log-jadwal-kuliah").text("Inserted : " + inserted);
                if (index >= (data.length - 1)) {
                    self.data.table_jadwal_kuliah.search("").draw();
                    $("#log-syncron-ulang-jadwal-kuliah").show();
                    $("#progress-bar-syncron-ulang-jadwal-kuliah").hide();
                } else {
                    if (!self.isSyncCanceled) {
                        index++;
                        self.next_data(data, index, progres, failed, inserted, updated)
                    } else {
                        $("#loading-progress-jadwal-kuliah").hide();
                        $("#keterangan-progress-jadwal-kuliah").text("Dibatalkan oleh pengguna");
                        $("#log-syncron-ulang-jadwal-kuliah").show();
                        $("#btn-cancel-syncron-ulang-jadwal-kuliah").hide();
                    }
                }
            },
            error: function () {
                self.data.log_table_jadwal_kuliah.row.add([
                    (index + 1),
                    data[index].nama_mata_kuliah + " (" + data[index].kelas_id + ")",
                    "<i class='fas fa-check-circle text-danger p-1'></i> " + result.keterangan,
                    data[index].nama_mata_kuliah,
                    "gagal"
                ]).draw();
                failed++;
                progres++;
            }
        })
    }
    ,
};

jQuery(document).ready(function () {
    jQuery.jadwal_matakuliah.init();
});
