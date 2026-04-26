jQuery.modul = {
    data: {},
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
                    //
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
                    width: "20%",
                    render: function (data) {
                        return "<b>" + data.nomor_pengajuan + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left align-middle',
                    width: "15%",
                    render: function (data) {
                        return "<small>" + data.tgl_pengajuan + "</small>";
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
                    render: function (data) {
                        return "<p class='mb-0'>" + data.keterangan_status + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: true,
                    sClass: 'text-center align-middle',
                    render: function (data) {
                        var id = data.id_riwayat_pengajuan_khs;
                        var status = data.status;

                        if (status === 2) {
                            return "<button class='btn btn-info btn-sm btn-icon btn-detail' data-id='" + id + "'><i class='fa fa-eye'></i></button>" +
                                "<button class='btn btn-success btn-sm btn-icon btn-download-lhs' data-id='" + id + "'><i class='fa fa-download'></i></button>";
                        } else {
                            return "<button class='btn btn-info btn-sm btn-icon btn-detail' data-id='" + id + "'><i class='fa fa-eye'></i></button>";
                        }
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

        $("#btn-filter").click(function () {
            table.ajax.reload();
        });
        $("#btn-cari-data").click(function () {
            table.search($("#cari-data").val()).draw();
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

        $("#table-riwayat-pengajuan").on('click', 'button.btn-detail', function () {
            var id = $(this).data("id");

            $.ajax({
                url: '/mhs/khs/riwayat-pengajuan/json',
                method: 'POST',
                data: { id_riwayat_pengajuan_khs: id, action: 'detail' },
                success: function (response) {
                    if (response.status === 1) {
                        var d = response.data;
                        $("#detail-nomor-pengajuan").text(d.nomor_pengajuan || '-');
                        $("#detail-nim").text(d.nim || '-');
                        $("#detail-nama-mahasiswa").text(d.nama_mahasiswa || '-');
                        $("#detail-nama-prodi").text(d.nama_prodi || '-');
                        $("#detail-tanggal-pengajuan").text(d.tgl_pengajuan || '-');
                        $("#detail-tahun-akademik").text(d.tahun_akademik || '-');
                        $("#detail-status").text(d.keterangan_status || '-');
                        $("#detail-keterangan").text(d.keterangan || '-');
                        $("#modal-detail-pengajuan").modal("show");
                    } else {
                        $.alert({ title: 'Informasi', type: 'red', content: response.keterangan || 'Data tidak ditemukan' });
                    }
                }
            });
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
                    if (response.status === 1) {
                        $.alert({
                            title: 'Informasi',
                            type: 'green',
                            content: response.keterangan
                        });

                        $("#form-collapse").collapse("hide");
                        $("#filter-collapse").collapse("show");
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
