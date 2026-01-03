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
                            "<span class='badge badge-warning btn-create-va' style='cursor: pointer;' data-id='" + data.id_tagihan_mahasiswa + "' data-nama='" + data.nama_tagihan + "' data-jumlah='" + data.jumlah_tagihan + "' data-jumlah-fmt='" + data.jumlah_tagihan_fmt + "' data-sisa='" + data.sisa_tagihan + "' data-sisa-fmt='" + data.sisa_tagihan_fmt + "' data-cicilan='" + (data.cicilan_terbayar || 0) + "' data-cicilan-fmt='" + (data.cicilan_terbayar_fmt || 'Rp 0') + "'>Belum Lunas</span>";

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

        // Event handler untuk klik badge "Belum Lunas" untuk create VA
        $(document).on('click', '.btn-create-va', function () {
            var tagihan_id = $(this).data('id');
            var nama_tagihan = $(this).data('nama');
            var jumlah_tagihan = $(this).data('jumlah');
            var jumlah_tagihan_fmt = $(this).data('jumlah-fmt');
            var sisa_tagihan = $(this).data('sisa');
            var sisa_tagihan_fmt = $(this).data('sisa-fmt');
            var cicilan_terbayar = $(this).data('cicilan') || 0;
            var cicilan_terbayar_fmt = $(this).data('cicilan-fmt') || 'Rp 0';

            self.showCreateVADialog(tagihan_id, nama_tagihan, jumlah_tagihan, jumlah_tagihan_fmt, sisa_tagihan, sisa_tagihan_fmt, cicilan_terbayar, cicilan_terbayar_fmt);
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

    showCreateVADialog: function (tagihan_id, nama_tagihan, jumlah_tagihan, jumlah_tagihan_fmt, sisa_tagihan, sisa_tagihan_fmt, cicilan_terbayar, cicilan_terbayar_fmt) {
        var self = this;

        $.confirm({
            title: 'Buat Virtual Account',
            content: function () {
                var form = '' +
                    '<form id="form-create-va">' +

                    // Informasi tagihan dalam tabel
                    '<div class="form-group mb-3">' +
                    '<table class="table table-sm table-bordered">' +
                    '<tbody>' +
                    '<tr>' +
                    '<td class="fw-bold">Tagihan</td>' +
                    '<td>' + nama_tagihan + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td class="fw-bold">Total Tagihan</td>' +
                    '<td>' + jumlah_tagihan_fmt + '</td>' +
                    '</tr>';

                if (cicilan_terbayar > 0) {
                    form += '<tr>' +
                        '<td class="fw-bold">Cicilan Terbayar</td>' +
                        '<td><span class="text-info">' + cicilan_terbayar_fmt + '</span></td>' +
                        '</tr>';
                }

                form +=     '<tr>' +
                    '<td class="fw-bold">Sisa Total Tagihan</td>' +
                    '<td><span class="text-danger">' + sisa_tagihan_fmt + '</span></td>' +
                    '</tr>' +
                    '</tbody>' +
                    '</table>' +
                    '</div>' +

                    '<hr>' +

                    // Pilihan pembayaran
                    '<div class="form-group mb-3">' +
                    '<label for="tipe_pembayaran" class="fw-bold">Pilih Tipe Pembayaran:</label><br>' +
                    '<div class="btn-group" data-toggle="buttons">' +
                    '<label class="btn btn-outline-primary active">' +
                    '<input type="radio" name="tipe_pembayaran" value="lunas" checked> Lunas' +
                    '</label>' +
                    '<label class="btn btn-outline-primary">' +
                    '<input type="radio" name="tipe_pembayaran" value="cicil"> Cicil' +
                    '</label>' +
                    '</div>' +
                    '</div>' +

                    // Nominal cicilan (hidden by default)
                    '<div class="form-group mb-3" id="nominal-cicil-group" style="display: none;">' +
                    '<label for="nominal_cicil" class="fw-bold">Nominal Cicilan:</label>' +
                    '<div class="input-group">' +
                    '<div class="input-group-prepend">' +
                    '<span class="input-group-text">Rp</span>' +
                    '</div>' +
                    '<input type="text" class="form-control" id="nominal_cicil" name="nominal_cicil" placeholder="Masukkan nominal cicilan">' +
                    '</div>' +
                    '<small class="form-text text-muted">' +
                    'Minimal Rp 50.000, Maksimal ' + jumlah_tagihan_fmt +
                    '</small>' +
                    '</div>' +

                    '</form>';

                return form;
            },
            columnClass: 'col-md-6 col-md-offset-3',
            type: 'blue',
            typeAnimated: true,
            onContentReady: function () {
                var jc = this;

                // Format input nominal dengan separator ribuan
                $('#nominal_cicil').on('input', function() {
                    var value = this.value.replace(/[^\d]/g, '');
                    this.value = self.formatRupiah(value);
                });

                // Toggle tampilan nominal cicil
                $('input[name="tipe_pembayaran"]').change(function() {
                    if ($(this).val() === 'cicil') {
                        $('#nominal-cicil-group').show();
                    } else {
                        $('#nominal-cicil-group').hide();
                        $('#nominal_cicil').val('');
                    }
                });
            },
            buttons: {
                buat: {
                    text: 'Buat VA',
                    btnClass: 'btn-primary',
                    action: function () {
                        var tipe_pembayaran = $('input[name="tipe_pembayaran"]:checked').val();
                        var nominal_cicil = 0;

                        if (tipe_pembayaran === 'cicil') {
                            var nominal_input = $('#nominal_cicil').val().replace(/[^\d]/g, '');
                            nominal_cicil = parseInt(nominal_input) || 0;

                            if (nominal_cicil < 50000) {
                                $.alert({
                                    title: 'Error!',
                                    content: 'Nominal cicilan minimal Rp 50.000',
                                    type: 'red'
                                });
                                return false;
                            }

                            if (nominal_cicil > jumlah_tagihan) {
                                $.alert({
                                    title: 'Error!',
                                    content: 'Nominal cicilan tidak boleh melebihi jumlah tagihan',
                                    type: 'red'
                                });
                                return false;
                            }
                        } else {
                            // Untuk pelunasan, gunakan sisa tagihan
                            // Pastikan format angka bersih tanpa .00 di akhir
                            var cleanSisaTagihan = jumlah_tagihan.toString().replace(/\.00$/, '');
                            nominal_cicil = cleanSisaTagihan;
                        }

                        // Konfirmasi sebelum membuat VA
                        var konfirmasi_content = 'Anda akan membuat Virtual Account untuk:<br>' +
                            '<strong>Tagihan:</strong> ' + nama_tagihan + '<br>' +
                            '<strong>Tipe:</strong> ' + (tipe_pembayaran === 'lunas' ? 'Pelunasan' : 'Cicilan') + '<br>' +
                            '<strong>Nominal:</strong> ' + self.formatRupiahDisplay(nominal_cicil);

                        $.confirm({
                            title: 'Konfirmasi Buat VA',
                            content: konfirmasi_content,
                            type: 'green',
                            buttons: {
                                ya: {
                                    text: 'Ya, Buat VA',
                                    btnClass: 'btn-success',
                                    action: function () {
                                        self.createVirtualAccount(tagihan_id, tipe_pembayaran, nominal_cicil);
                                    }
                                },
                                batal: {
                                    text: 'Batal',
                                    btnClass: 'btn-secondary'
                                }
                            }
                        });

                        return false; // Jangan tutup dialog pertama
                    }
                },
                batal: {
                    text: 'Batal',
                    btnClass: 'btn-secondary'
                }
            }
        });
    },

    createVirtualAccount: function (tagihan_id, tipe_pembayaran, nominal) {
        var self = this;

        // Pastikan nominal hanya berupa angka, hilangkan format rupiah dan .00
        var cleanNominal = nominal.toString()
            .replace(/\.00$/, '')  // Hilangkan .00 di akhir
            .replace(/[^\d]/g, ''); // Hilangkan semua selain digit

        // Show loading
        $.alert({
            title: 'Memproses...',
            content: 'Sedang membuat Virtual Account, mohon tunggu...',
            type: 'blue',
            buttons: false,
            closeIcon: false
        });

        $.ajax({
            url: '/mhs/tanggungan/json-create-va',
            type: 'POST',
            dataType: 'json',
            data: {
                tagihan_id: tagihan_id,
                tipe_pembayaran: tipe_pembayaran,
                nominal: cleanNominal
            },
            success: function (response) {
                // Close loading dialog
                $('.jconfirm').remove();

                if (response.success) {
                    $.alert({
                        title: 'Berhasil!',
                        content: response.message || 'Virtual Account berhasil dibuat',
                        type: 'green',
                        buttons: {
                            ok: {
                                text: 'OK',
                                action: function () {
                                    // Refresh data setelah sukses
                                    self.refreshData();
                                }
                            }
                        }
                    });
                } else {
                    $.alert({
                        title: 'Error!',
                        content: response.message || 'Gagal membuat Virtual Account',
                        type: 'red'
                    });
                }
            },
            error: function (xhr, status, error) {
                // Close loading dialog
                $('.jconfirm').remove();

                var errorMessage = 'Terjadi kesalahan saat membuat Virtual Account';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 422) {
                    errorMessage = 'Data yang dikirim tidak valid';
                } else if (xhr.status === 500) {
                    errorMessage = 'Terjadi kesalahan server';
                }

                $.alert({
                    title: 'Error!',
                    content: errorMessage,
                    type: 'red'
                });
            }
        });
    },

    formatRupiah: function (angka) {
        var number_string = angka.toString().replace(/[^,\d]/g, '');
        var split = number_string.split(',');
        var sisa = split[0].length % 3;
        var rupiah = split[0].substr(0, sisa);
        var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    },

    formatRupiahDisplay: function (angka) {
        return 'Rp ' + this.formatRupiah(angka.toString());
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
