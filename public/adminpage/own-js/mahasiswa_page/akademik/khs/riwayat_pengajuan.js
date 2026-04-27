jQuery.modul = {
    data: {
        filterPengajuan: null,
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;

        $('#tahun-akademik').select2({
            placeholder: '-- Pilih Tahun Akademik --',
            allowClear: true,
            language: {
                noResults: function () {
                    return "Tahun akademik yang anda cari tidak ditemukan.";
                }
            }
        });

        $('#filter-pengajuan').select2({
            placeholder: '-- Pilih Status Pengajuan --',
            allowClear: true,
            language: {
                noResults: function () {
                    return "Status pengajuan yang anda cari tidak ditemukan.";
                }
            }
        });

        var table = $("#table-riwayat-pengajuan").DataTable({
            serverSide: true,
            ajax: {
                url: '/mhs/khs/riwayat-pengajuan/json',
                type: 'post',
                data: function (data) {
                    data.status = self.data.filterPengajuan;
                }
            },
            scrollY: '300px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'nomor',
                    searchable: false,
                    sClass: 'text-center align-middle',
                    width: "5%"
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left align-middle',
                    width: "15%",
                    render: function (data) {
                        return "<b>" + data.nomor_pengajuan + "</b><br><hr/>" +
                            "<p class='mb-0 text-uppercase'>" + data.nama_mahasiswa + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left align-middle',
                    width: "15%",
                    render: function (data) {
                        var tgl = new Date(data.tgl_pengajuan);
                        var tanggal = ("0" + tgl.getDate()).slice(-2);
                        var bulan = ("0" + (tgl.getMonth() + 1)).slice(-2);
                        var tahun = tgl.getFullYear();
                        var jam = ("0" + tgl.getHours()).slice(-2);
                        var menit = ("0" + tgl.getMinutes()).slice(-2);

                        return "<p class='mb-0'>" + tanggal + "/" + bulan + "/" + tahun + " " + jam + ":" + menit + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center align-middle',
                    width: "15%",
                    render: function (data) {
                        return "<p class='mb-0'>" + data.tahun_akademik + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: true,
                    sClass: 'text-center align-middle',
                    width: "10%",
                    render: function (data) {
                        return "<p class='mb-0'>" + data.keterangan_status + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: true,
                    sClass: 'text-center align-middle',
                    width: "25%",
                    render: function (data) {
                        var catatan = data.catatan ? data.catatan : "-";

                        return "<p class='mb-0'>" + catatan + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: true,
                    sClass: 'text-center align-middle',
                    width: "15%",
                    render: function (data) {
                        var id = data.id_riwayat_pengajuan_khs;
                        var status = data.status;
                        var tahun = data.tahun_akademik;

                        var buttons = "<div class='d-flex flex-column'>";

                        buttons += "<button class='btn btn-info btn-sm mb-1 btn-detail' data-id='" + id + "'>" +
                            "<i class='fa fa-eye mr-2'></i> Detail</button>";

                        if (user && user.id_personal && status === "1") {
                            buttons += "<button class='btn btn-primary btn-sm mb-1 btn-approve' data-id='" + id + "'>" +
                                "<i class='fa fa-check mr-2'></i> Setujui</button>";

                            buttons += "<button class='btn btn-danger btn-sm btn-reject' data-id='" + id + "'>" +
                                "<i class='fa fa-times mr-2'></i> Tolak</button>";
                        }

                        if (status === "2") {
                            buttons += "<button class='btn btn-success btn-sm mt-1 btn-download-lhs' data-tahun='" + tahun + "' data-id='" + id + "'>" +
                                "<i class='fa fa-download mr-2'></i> Download</button>";
                        }

                        buttons += "</div>";

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
            sDom: 'ltipr',
            language: {
                "emptyTable": "Tidak ditemukan data"
            }
        });

        $("#filter-pengajuan").change(function () {
            self.data.filterPengajuan = $(this).val();
            table.ajax.reload();
        });

        $("#btn-cari-data").click(function () {
            var searchValue = $("#cari-data").val();
            table.search(searchValue).draw();
        });

        $("#cari-data").keyup(function () {
            if (this.value === "") {
                table.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                table.search(this.value).draw();
            }
        });

        $('#table-riwayat-pengajuan').on('click', 'button.btn-approve', function () {
            var id = $(this).data("id");
            var id_personal = user && user.id_personal ? user.id_personal : null;

            $.confirm({
                title: 'Konfirmasi',
                content: 'Apakah Anda yakin ingin menyetujui pengajuan ini?',
                type: 'green',
                buttons: {
                    cancel: {
                        text: 'Batal',
                        action: function () {
                            // Do nothing
                        }
                    },
                    confirm: {
                        text: 'Ya, Setujui',
                        btnClass: 'btn-green',
                        action: function () {
                            $.ajax({
                                url: '/mhs/khs/riwayat-pengajuan/set/approve',
                                method: 'POST',
                                data: { id_riwayat_pengajuan_khs: id, id_personal: id_personal },
                                success: function (response) {
                                    if (response.status === 1) {
                                        $.alert({ title: 'Informasi', type: 'green', content: response.keterangan });
                                        table.ajax.reload();
                                    } else {
                                        $.alert({ title: 'Informasi', type: 'red', content: response.keterangan || 'Terjadi kesalahan' });
                                    }
                                }
                            });
                        }
                    },
                }
            });
        });

        $('#table-riwayat-pengajuan').on('click', 'button.btn-reject', function () {
            var id = $(this).data("id");
            var id_personal = user && user.id_personal ? user.id_personal : null;

            $.confirm({
                title: 'Konfirmasi',
                content: '' +
                    '<form>' +
                    '<div class="form-group">' +
                    '<label class="form-label">Catatan Penolakan <span class="text-danger">*</span></label>' +
                    '<textarea class="form-control catatan" rows="2" placeholder="Masukkan alasan penolakan..."></textarea>' +
                    '</div>' +
                    '</form>',
                type: 'red',
                buttons: {
                    cancel: {
                        text: 'Batal'
                    },
                    confirm: {
                        text: 'Ya, Tolak',
                        btnClass: 'btn-red',
                        action: function () {
                            var catatan = this.$content.find('.catatan').val();

                            if (!catatan) {
                                $.alert('Catatan wajib diisi!');
                                return false;
                            }

                            $.ajax({
                                url: '/mhs/khs/riwayat-pengajuan/set/reject',
                                method: 'POST',
                                data: {
                                    id_riwayat_pengajuan_khs: id,
                                    id_personal: id_personal,
                                    catatan: catatan
                                },
                                success: function (response) {
                                    if (response.status === 1) {
                                        $.alert({ title: 'Informasi', type: 'green', content: response.keterangan });
                                        table.ajax.reload();
                                    } else {
                                        $.alert({ title: 'Informasi', type: 'red', content: response.keterangan || 'Terjadi kesalahan' });
                                    }
                                }
                            });
                        }
                    }
                }
            });
        });

        $("#table-riwayat-pengajuan").on('click', 'button.btn-detail', function () {
            var id = $(this).data("id");

            $.ajax({
                url: '/mhs/khs/riwayat-pengajuan/json',
                method: 'POST',
                data: { id_riwayat_pengajuan_khs: id, action: 'detail' },
                success: function (response) {
                    if (response.status === true) {
                        var d = response.data;
                        $("#detail-nomor-pengajuan").text(d.nomor_pengajuan || '-');
                        $("#detail-nim").text(d.nim || '-');
                        $("#detail-nama-mahasiswa").text(d.nama_mahasiswa || '-');
                        $("#detail-nama-prodi").text(d.nama_prodi || '-');
                        $("#detail-tanggal-pengajuan").text(d.tgl_pengajuan || '-');
                        $("#detail-tahun-akademik").text(d.tahun_akademik || '-');
                        $("#detail-status").text(d.keterangan_status || '-');
                        $("#detail-keterangan").text(d.catatan || '-');
                        $("#modal-detail-pengajuan").modal("show");
                    } else {
                        $.alert({ title: 'Informasi', type: 'red', content: response.keterangan || 'Data tidak ditemukan' });
                    }
                }
            });
        });

        $('#table-riwayat-pengajuan').on('click', 'button.btn-download-lhs', function () {
            var id = $(this).data("id");
            var tahun = $(this).data("tahun");
            var $button = $(this);
            var originalHtml = $button.html();

            $button.prop('disabled', true)
                .html('<i class="fa fa-spinner fa-spin mr-2"></i> Mengunduh...');

            var form = $('<form>', {
                method: 'POST',
                action: '/mhs/khs/download'
            });

            form.append($('<input>', {
                type: 'hidden',
                name: '_token',
                value: $('meta[name="csrf-token"]').attr('content')
            }));

            form.append($('<input>', {
                type: 'hidden',
                name: 'id_riwayat_pengajuan_khs',
                value: id
            }));

            form.append($('<input>', {
                type: 'hidden',
                name: 'tahun_akademik',
                value: tahun
            }));

            $('body').append(form);
            form.trigger('submit');
            form.remove();

            setTimeout(function () {
                $button.prop('disabled', false).html(originalHtml);
            }, 2000);
        });

        $('form#form-tambah-pengajuan').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: '/mhs/khs/riwayat-pengajuan/insup',
                method: 'POST',
                data: $(this).serialize(),
                beforeSend: function () {
                    $("#loading-tambah-pengajuan").show();
                },
                success: function (response) {
                    if (response.status === true) {
                        $.alert({
                            title: 'Informasi',
                            type: 'green',
                            content: response.keterangan
                        });

                        $("#modal-tambah-pengajuan").modal("hide");
                    } else {
                        $.alert({
                            title: 'Informasi',
                            type: 'red',
                            content: response.keterangan
                        });
                    }
                },
                complete: function () {
                    $("#loading-tambah-pengajuan").hide();

                    $("#table-riwayat-pengajuan").DataTable().ajax.reload();
                }
            });
        });
    },
};

jQuery(document).ready(function () {
    jQuery.modul.init();
});
