jQuery.index = {
    data: {
        table: $("#tables"),
        table_jurnal_nasional: $("#table-jurnal-nasional"),
        table_jurnal_internasional: $("#table-jurnal-internasional"),
        table_jurnal_pengabdian: $("#table-jurnal-pengabdian"),
        tgl_lulus: $("#tanggal_lulus"),
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        // Option Data
        $(".select2").select2();
        $("#btn-add-pendidikan").click(function () {
            $("#form_collapse").collapse('show');
            $("#filter-collapse").collapse('hide');
        });
        $("#btn-cancel-pendidikan").click(function () {
            $("#form_collapse").collapse('hide');
            $("#filter-collapse").collapse('show');
        });
        $("#btn-add-jurnal-nasional").click(function () {
            $("#form_collapse_jurnal").collapse('show');
            $("#filter-collapse_jurnal").collapse('hide');
        });
        $("#btn-cancel-jurnal-nasional").click(function () {
            $("#form_collapse_jurnal").collapse('hide');
            $("#filter-collapse_jurnal").collapse('show');
        });
        $("#btn-add-jurnal-internasional").click(function () {
            $("#form_collapse_jurnal_internasional").collapse('show');
            $("#filter-collapse_jurnal_internasional").collapse('hide');
        });
        $("#btn-cancel-jurnal-internasional").click(function () {
            $("#form_collapse_jurnal_internasional").collapse('hide');
            $("#filter-collapse_jurnal_internasional").collapse('show');
        });
        $("#btn-add-pengabdian").click(function () {
            $("#form_collapse_pengabdian").collapse('show');
            $("#filter-collapse_pengabdian").collapse('hide');
        });
        $("#btn-cancel-pengabdian").click(function () {
            $("#form_collapse_pengabdian").collapse('hide');
            $("#filter-collapse_pengabdian").collapse('show');
        });
        $("#btn-add-buku").click(function () {
            $("#form_collapse_buku").collapse('show');
            $("#filter-collapse_buku").collapse('hide');
        });
        $("#btn-cancel-buku").click(function () {
            $("#form_collapse_buku").collapse('hide');
            $("#filter-collapse_buku").collapse('show');
        });
        $("#btn-add-jabatan").click(function () {
            $("#form_collapse_jabatan").collapse('show');
            $("#filter-collapse_jabatan").collapse('hide');
        });
        $("#btn-cancel-jabatan").click(function () {
            $("#form_collapse_jabatan").collapse('hide');
            $("#filter-collapse_jabatan").collapse('show');
        });
        $("#btn-add-haki").click(function () {
            $("#form_collapse_haki").collapse('show');
            $("#filter-collapse_haki").collapse('hide');
        });
        $("#btn-cancel-haki").click(function () {
            $("#form_collapse_haki").collapse('hide');
            $("#filter-collapse_haki").collapse('show');
        });
        $("#btn-add-prestasi").click(function () {
            $("#form_collapse_prestasi").collapse('show');
            $("#filter-collapse_prestasi").collapse('hide');
        });
        $("#btn-cancel-prestasi").click(function () {
            $("#form_collapse_prestasi").collapse('hide');
            $("#filter-collapse_prestasi").collapse('show');
        });
        // Table With DataTable
        self.data.table = $("#tables").DataTable({
            serverSide: true,
            ajax: {
                url: '/dosen/akademik/daftar-matakuliah/json',
                type: 'post',
                data: function (data) {
                    data.tahun = $("#tahun_akademik").val();
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%",
                    render: function (data) {
                        return "<b>" + data.nama_mata_kuliah + " (" + data.kelas_id + ")</b><br/>" +
                            "Kode Prodi : " + data.prodi;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "45%",
                    render: function (data) {
                        if (data.nik === data.nik_asisten) {
                            return "Dosen 1 : <b>" + data.nama_lengkap + "</b><br/>" +
                                "Dosen 2 : <b>-- Tidak Ada --</b>";
                        } else {
                            return "Dosen 1 : <b>" + data.nama_lengkap + "</b><br/>" +
                                "Dosen 2 : <b>" + data.nama_lengkap_asisten + "</b>";
                        }
                    }
                },
                {
                    data: 'nama_mata_kuliah',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                }
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

        self.data.table_jurnal_nasional = $("#table-jurnal-nasional").DataTable({
            serverSide: true,
            ajax: {
                url: '/dosen/cv/riwayat-publikasi-jurnal-json',
                type: 'post',
                data: function (data) {
                    data.jenis_jurnal = 1;
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'nomor',
                    searchable: false,
                    sClass: 'text-left',
                    width: "5%",
                },
                {
                    data: 'tahun_publikasi',
                    searchable: false,
                    sClass: 'text-left',
                    width: "15%",
                },
                {
                    data: 'judul_artikel',
                    searchable: true,
                    sClass: 'text-left',
                    width: "35%",
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                    render: function (data) {
                        return "<p>" + data.nama_jurnal + "/" + data.ket_volume + "</p>"
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: '10%',
                    render: function (data) {
                        return "<button class='btn btn-sm btn-info btn-edit mr-2' title='Ubah Data Jurnal' data-id='" + data.id_riwayat_publikasi_jurnal + "' data-nama='" + data.nama_jurnal + "' data-judul='" + data.judul_artikel + "' data-tahun='" + data.tahun_publikasi + "' data-volume='" + data.volume + "' data-nomor='" + data.nomor + "'><i class='fas fa-edit'></i></button>" +
                            "<button class='btn btn-sm btn-danger btn-delete' title='Hapus Data Jurnal' data-id='" + data.id_riwayat_publikasi_jurnal + "' data-nama='" + data.nama_jurnal + "' data-judul='" + data.judul_artikel + "' data-tahun='" + data.tahun_publikasi + "' data-volume='" + data.volume + "' data-nomor='" + data.nomor + "'><i class='fas fa-trash'></i></button>";
                    }
                }
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

        $("#table-jurnal-nasional").on('click', 'button.btn-edit', function () {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            var judul = $(this).data('judul');
            var tahun = $(this).data('tahun');
            var volume = $(this).data('volume');
            var nomor = $(this).data('nomor');

            $("#id_riwayat_jurnal_nasional").val(id);
            $("#judul_artikel").val(judul);
            $("#nama_jurnal").val(nama);
            $("#tahun_terbit").val(tahun).change();
            $("#vol_jurnal").val(volume);
            $("#no_jurnal").val(nomor);

            $("#form_collapse_jurnal").collapse('show');
        });

        $("#table-jurnal-nasional").on('click', 'button.btn-delete', function () {
            var id = $(this).data('id');
            $.confirm({
                type: 'purple',
                title: '',
                columnClass: 'large',
                content: 'Apakah anda yakin akan menghapus data jurnal ?',
                buttons: {
                    export: {
                        text: 'Yakin',
                        btnClass: 'btn-blue',
                        action: function () {
                            $("#del_id_riwayat_jurnal_nasional")
                            $("#form-delete-jurnal-nasional").submit();
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red',
                    },
                },
                backgroundDismissAnimation: 'glow'
            });
        });

        self.data.tgl_lulus = $("#tanggal_lulus").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment($("#tgl_lulus")).format('D/M/YYYY')).on('changeDate', function (e) {
            $("#tgl_lulus").val(moment(e.date).format('YYYY-MM-DD'));
        });

        $("#btn-export-pdf").click(function () {
            window.open('/dosen/akademik/daftar-matakuliah/export-pdf/' + $("#tahun_akademik").val() + '/' + $("#cari-data").val());
        });

        $("#btn-save-pendidikan").click(function () {
            if ($("#jenjang_pendidikan").val() && $("#nama_pt").val() && $("#nama_prodi").val() && $("#bidang_ilmu").val() && $("#tgl_lulus").val()) {
                $.confirm({
                    type: 'purple',
                    title: '',
                    columnClass: 'large',
                    content: 'Apakah anda yakin akan memperbarui data pendidikan anda ?',
                    buttons: {
                        export: {
                            text: 'Yakin',
                            btnClass: 'btn-blue',
                            action: function () {
                                $("#form-pendidikan").submit();
                            }
                        },
                        cancel: {
                            text: 'Batal',
                            btnClass: 'btn-red',
                        },
                    },
                    backgroundDismissAnimation: 'glow'
                });
            } else {
                $.alert({
                    title: 'Peringatan',
                    type: 'red',
                    content: 'Pastikan Semua Form Terisi',
                    backgroundDismissAnimation: 'glow'
                });
            }
        });

        $("#btn-save-jurnal-nasional").click(function () {
            if ($("#judul_artikel").val() && $("#nama_jurnal").val() && $("#vol_jurnal").val() && $("#no_jurnal").val() && $("#tahun_terbit").val()) {
                $.confirm({
                    type: 'purple',
                    title: '',
                    columnClass: 'large',
                    content: 'Apakah anda yakin akan memperbarui data jurnal anda ?',
                    buttons: {
                        export: {
                            text: 'Yakin',
                            btnClass: 'btn-blue',
                            action: function () {
                                $("#form-jurnal-nasional").submit();
                            }
                        },
                        cancel: {
                            text: 'Batal',
                            btnClass: 'btn-red',
                        },
                    },
                    backgroundDismissAnimation: 'glow'
                });
            } else {
                $.alert({
                    title: 'Peringatan',
                    type: 'red',
                    content: 'Pastikan Semua Form Terisi',
                    backgroundDismissAnimation: 'glow'
                });
            }
        });

        self.data.table_jurnal_internasional = $("#table-jurnal-internasional").DataTable({
            serverSide: true,
            ajax: {
                url: '/dosen/cv/riwayat-publikasi-jurnal-json',
                type: 'post',
                data: function (data) {
                    data.jenis_jurnal = 0;
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'nomor',
                    searchable: false,
                    sClass: 'text-left',
                    width: "5%",
                },
                {
                    data: 'tahun_publikasi',
                    searchable: false,
                    sClass: 'text-left',
                    width: "15%",
                },
                {
                    data: 'judul_artikel',
                    searchable: true,
                    sClass: 'text-left',
                    width: "40%",
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return "<p>" + data.nama_jurnal + "/" + data.ket_volume + "</p>"
                    }
                }
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

        $("#btn-save-jurnal-internasional").click(function () {
            if ($("#judul_artikel_internasional").val() && $("#nama_jurnal_internasional").val() && $("#vol_jurnal_internasional").val() && $("#no_jurnal_internasional").val() && $("#tahun_terbit_internasional").val()) {
                $.confirm({
                    type: 'purple',
                    title: '',
                    columnClass: 'large',
                    content: 'Apakah anda yakin akan memperbarui data jurnal anda ?',
                    buttons: {
                        export: {
                            text: 'Yakin',
                            btnClass: 'btn-blue',
                            action: function () {
                                $("#form-jurnal-internasional").submit();
                            }
                        },
                        cancel: {
                            text: 'Batal',
                            btnClass: 'btn-red',
                        },
                    },
                    backgroundDismissAnimation: 'glow'
                });
            } else {
                $.alert({
                    title: 'Peringatan',
                    type: 'red',
                    content: 'Pastikan Semua Form Terisi',
                    backgroundDismissAnimation: 'glow'
                });
            }
        });

        self.data.table_jurnal_pengabdian = $("#table-jurnal-pengabdian").DataTable({
            serverSide: true,
            ajax: {
                url: '/dosen/cv/riwayat-publikasi-jurnal-json',
                type: 'post',
                data: function (data) {
                    data.jenis_jurnal = 2;
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'nomor',
                    searchable: false,
                    sClass: 'text-left',
                    width: "5%",
                },
                {
                    data: 'tahun_publikasi',
                    searchable: false,
                    sClass: 'text-left',
                    width: "15%",
                },
                {
                    data: 'judul_artikel',
                    searchable: true,
                    sClass: 'text-left',
                    width: "40%",
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return "<p>" + data.nama_jurnal + "/" + data.ket_volume + "</p>"
                    }
                }
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

        $("#btn-save-pengabdian").click(function () {
            if ($("#judul_pengabdian").val() && $("#nama_jurnal_pengabdian").val() && $("#vol_jurnal_pengabdian").val() && $("#no_jurnal_pengabdian").val() && $("#tahun_pengabdian").val()) {
                $.confirm({
                    type: 'purple',
                    title: '',
                    columnClass: 'large',
                    content: 'Apakah anda yakin akan memperbarui data jurnal anda ?',
                    buttons: {
                        export: {
                            text: 'Yakin',
                            btnClass: 'btn-blue',
                            action: function () {
                                $("#form-pengabdian").submit();
                            }
                        },
                        cancel: {
                            text: 'Batal',
                            btnClass: 'btn-red',
                        },
                    },
                    backgroundDismissAnimation: 'glow'
                });
            } else {
                $.alert({
                    title: 'Peringatan',
                    type: 'red',
                    content: 'Pastikan Semua Form Terisi',
                    backgroundDismissAnimation: 'glow'
                });
            }
        });
    },
};

jQuery(document).ready(function () {
    jQuery.index.init();
});
