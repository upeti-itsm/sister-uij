jQuery.tanggungan_mahasiswa = {
    data: {
        table_daftar_tanggungan: null,
        table_riwayat_pembayaran: null,
        modal_opened: false,
        summary_tagihan: {
            total_fmt: 'Rp 0',
            lunas_fmt: 'Rp 0',
            sisa_fmt: 'Rp 0'
        },
        summary_pembayaran: {
            total_fmt: 'Rp 0',
            jumlah_transaksi: 0
        }
    },

    init: function () {
        var self = this;
        self.setEvents();
    },

    setEvents: function () {
        var self = this;

        // Initialize Select2
        $(".select2").select2();

        // Tabel Daftar Tanggungan
        self.data.table_daftar_tanggungan = $("#table-daftar-tanggungan").DataTable({
            serverSide: true,
            ajax: {
                url: '/mhs/tanggungan/json',
                type: 'post',
                data: function (data) {
                    data.search_value = $("#cari-data-tanggungan").val();
                    data.status_lunas = $("#filter-status-lunas").val();
                }
            },
            scrollY: '500px',
            scrollCollapse: true,
            columns: [
                {data: 'nomor', searchable: false, sClass: 'text-center', width: "5%"},
                {data: 'nama_tagihan', searchable: false, sClass: 'text-left', width: "30%"},
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        return "<strong>" + data.tahun_akademik + "</strong> | <small>Semester " + data.semester + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-right',
                    width: "20%",
                    render: function (data) {
                        var html = "<strong>Total: " + data.jumlah_tagihan_fmt + "</strong>";
                        return html;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        var statusBadge = data.status_lunas ?
                            "<span class='badge badge-success'>Lunas</span>" :
                            "<span class='badge badge-warning'>Belum Lunas</span>";

                        var jatuhTempo = '-';
                        if (data.tgl_jatuh_tempo) {
                            var date = new Date(data.tgl_jatuh_tempo);
                            jatuhTempo = "<small>" + date.toLocaleDateString('id-ID') + "</small>";
                        }
                        return statusBadge + " | " + jatuhTempo;
                    }
                },
                {data: 'tipe_periodisasi', searchable: false, sClass: 'text-center', width: "10%"},
                {data: 'nama_tagihan', searchable: true, sClass: 'text-center', visible: false}
            ],
            paging: true,
            processing: true,
            pageLength: 10,
            ordering: false,
            lengthChange: false,
            autoWidth: false,
            sDom: 'ltipr',
            language: {
                "emptyTable": "Tidak ditemukan data tagihan",
                "processing": "Memuat data...",
                "loadingRecords": "Memuat data...",
                "zeroRecords": "Tidak ditemukan data yang sesuai"
            },
            drawCallback: function (settings) {
                var api = this.api();
                var data = api.rows({page: 'current'}).data();
                if (data.length > 0) {
                    var firstRow = data[0];
                    jQuery.tanggungan_mahasiswa.data.summary_tagihan = {
                        total_fmt: firstRow.total_tagihan_fmt,
                        lunas_fmt: "Return ini ya fer.",
                        sisa_fmt: firstRow.sisa_tagihan_fmt
                    };
                    jQuery.tanggungan_mahasiswa.renderSummaryTagihan();
                }
            }
        });

        // Event handler untuk membuka modal riwayat pembayaran
        $("#btn-lihat-riwayat-pembayaran").click(function () {
            self.openModalRiwayatPembayaran();
        });

        // Event handlers untuk tabel tagihan
        $("#btn-cari-data-tanggungan").click(function () {
            self.data.table_daftar_tanggungan.draw();
        });

        $("#cari-data-tanggungan").keyup(function () {
            if (this.value === "") {
                self.data.table_daftar_tanggungan.draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.table_daftar_tanggungan.draw();
            }
        });

        $("#filter-status-lunas").change(function () {
            self.data.table_daftar_tanggungan.draw();
        });

        // Event handlers untuk modal riwayat pembayaran
        $("#modal-riwayat-pembayaran").on('shown.bs.modal', function () {
            if (!self.data.modal_opened) {
                self.initModalRiwayatPembayaran();
                self.data.modal_opened = true;
            } else {
                if (self.data.table_riwayat_pembayaran) {
                    self.data.table_riwayat_pembayaran.draw();
                }
            }
        });

        // Event handlers untuk pencarian dalam modal
        $(document).on('click', '#btn-cari-data-pembayaran', function () {
            if (self.data.table_riwayat_pembayaran) {
                self.data.table_riwayat_pembayaran.draw();
            }
        });

        $(document).on('keyup', '#cari-data-pembayaran', function () {
            if (this.value === "" && self.data.table_riwayat_pembayaran) {
                self.data.table_riwayat_pembayaran.draw();
            }
        }).on('keypress', '#cari-data-pembayaran', function (event) {
            if (event.keyCode === 13 && self.data.table_riwayat_pembayaran) {
                self.data.table_riwayat_pembayaran.draw();
            }
        });
    },

    openModalRiwayatPembayaran: function () {
        $("#modal-riwayat-pembayaran").modal('show');
    },

    initModalRiwayatPembayaran: function () {
        var self = this;

        self.data.table_riwayat_pembayaran = $("#table-riwayat-pembayaran").DataTable({
            serverSide: true,
            ajax: {
                url: '/mhs/tanggungan/json-riwayat-pembayaran',
                type: 'post',
                data: function (data) {
                    data.search_value = $("#cari-data-pembayaran").val();
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "5%",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-right',
                    width: "15%",
                    render: function (data) {
                        var html = "<strong>" + data.jumlah_bayar_fmt + "</strong>";
                        return html;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        var html = "<strong>" + data.metode_bayar + "</strong>";
                        if (data.nomor_referensi) {
                            html += " | <small>Ref: " + data.nomor_referensi + "</small>";
                        }
                        return html;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        if (data.tgl_bayar) {
                            var date = new Date(data.tgl_bayar);
                            return "<strong>" + date.toLocaleDateString('id-ID') + "</strong> | <small>" + date.toLocaleTimeString('id-ID') + "</small>";
                        }
                        return '-';
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        if (data.jenis_tanggungan) {
                            return "<p>" + data.jenis_tanggungan + "</p>";
                        }
                        return '-';
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        if (data.keterangan) {
                            return data.keterangan.length > 80 ?
                                data.keterangan.substring(0, 80) + '...' :
                                data.keterangan;
                        }
                        return '-';
                    }
                },
                {data: 'nomor_referensi', searchable: true, sClass: 'text-center', visible: false}
            ],
            paging: true,
            processing: true,
            pageLength: 10,
            ordering: false,
            lengthChange: false,
            autoWidth: false,
            sDom: 'ltipr',
            language: {
                "emptyTable": "Tidak ditemukan data pembayaran",
                "processing": "Memuat data...",
                "loadingRecords": "Memuat data...",
                "zeroRecords": "Tidak ditemukan data yang sesuai"
            },
            drawCallback: function (settings) {
                var api = this.api();
                var data = api.rows({page: 'current'}).data();

                if (data.length > 0) {
                    var firstRow = data[0];

                    jQuery.tanggungan_mahasiswa.data.summary_pembayaran = {
                        total_fmt: firstRow.total_bayar_fmt,
                        jumlah_transaksi: settings.json ? settings.json.recordsTotal : api.rows().count()
                    };

                    jQuery.tanggungan_mahasiswa.renderSummaryPembayaran();
                }
            }
        });
    },

    renderSummaryTagihan: function () {
        var data = this.data.summary_tagihan;
        $("#total-tagihan").text(data.total_fmt || 'Rp 0');
        $("#tagihan-lunas").text(data.lunas_fmt || 'Rp 0');
        $("#sisa-tagihan").text(data.sisa_fmt || 'Rp 0');

        $("#total-tagihan, #tagihan-lunas, #sisa-tagihan").each(function () {
            $(this).addClass('text-success');
            setTimeout(() => {
                $(this).removeClass('text-success');
            }, 1000);
        });
    },

    renderSummaryPembayaran: function () {
        var data = this.data.summary_pembayaran;
        $("#total-pembayaran").text(data.total_fmt || 'Rp 0');
        $("#jumlah-transaksi").text(data.jumlah_transaksi || '0');

        $("#total-pembayaran, #jumlah-transaksi").each(function () {
            $(this).addClass('text-success');
            setTimeout(() => {
                $(this).removeClass('text-success');
            }, 1000);
        });
    },

    refreshData: function () {
        if (this.data.table_daftar_tanggungan) {
            this.data.table_daftar_tanggungan.draw();
        }
        if (this.data.modal_opened && this.data.table_riwayat_pembayaran) {
            this.data.table_riwayat_pembayaran.draw();
        }
    },

    refreshModalData: function () {
        if (this.data.table_riwayat_pembayaran) {
            this.data.table_riwayat_pembayaran.draw();
        }
    }
};

jQuery(document).ready(function () {
    jQuery.tanggungan_mahasiswa.init();
});
