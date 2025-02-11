jQuery.tanggungan_mahasiswa = {
    data: {
        reg_class: 'REG',
        regm_class: 'REGM',
        trf_class: 'TRF',
        trfm_class: 'TRFM',
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
        var table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/super-admin/siakad/tanggungan-mahasiswa/json/daftar-tanggungan',
                type: 'post',
                data: function (d) {
                    d.batas = $("#batas_tanggungan").val();
                    d.reg = self.data.reg_class;
                    d.regm = self.data.regm_class;
                    d.trf = self.data.trf_class;
                    d.trfm = self.data.trfm_class;
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                    render: function (data) {
                        return "<b>" + data.nama_lengkap + " (" + data.NPK + ")</b><br/>" +
                            "<small>Angkatan : " + data.angkatan + " | Jenis Kelas : " + data.jenis_kelas + " | Jenis Pendanaan : " + data.jenis_pendanaan + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return "<b>Sisa Tanggungan : " + Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                            }).format(data.sisa_tanggungan) + "</b><br/>" +
                            "<small>Total Tanggungan : " + Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                            }).format(data.total_tanggungan) + " | Total Bayar : " + Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                            }).format(data.total_bayar) + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "25%",
                    render: function (data) {
                        return "<button title='Lihat Transaksi' class='btn btn-sm btn-success-soft btn-lihat-transaksi mr-2' data-npk='" + data.NPK + "'><span class='spinner-border spinner-border-sm mr-2' id='lihat-transaksi-loading-spin-" + data.NPK + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-eye'></i></button>";
                    }
                },
                {
                    data: 'NPK',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'nama_lengkap',
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
        $("#reg").change(function () {
            if ($(this).is(':checked'))
                self.data.reg_class = 'REG';
            else
                self.data.reg_class = '';
            table.ajax.reload();
        });
        $("#regm").change(function () {
            if ($(this).is(':checked'))
                self.data.regm_class = 'REGM';
            else
                self.data.regm_class = '';
            table.ajax.reload();
        });
        $("#trf").change(function () {
            if ($(this).is(':checked'))
                self.data.trf_class = 'TRF';
            else
                self.data.trf_class = '';
            table.ajax.reload();
        });
        $("#trfm").change(function () {
            if ($(this).is(':checked'))
                self.data.trfm_class = 'TRFM';
            else
                self.data.trfm_class = '';
            table.ajax.reload();
        });
        $("#batas_tanggungan").change(function () {
            table.ajax.reload();
        });
        $("#btn-export").click(function () {
            var reg = self.data.reg_class, regm = self.data.regm_class, trf = self.data.trf_class,
                trfm = self.data.trfm_class;
            if (reg === '')
                reg = '-';
            if (regm === '')
                regm = '-';
            if (trf === '')
                trf = '-';
            if (trfm === '')
                trfm = '-';
            window.open('/super-admin/siakad/tanggungan-mahasiswa/export/export-tanggungan/' + $("#batas_tanggungan").val() + '/' + reg + '/' + regm + '/' + trf + '/' + trfm);
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
        $("#table").on('click', 'button.btn-lihat-transaksi', function () {
            var npk = $(this).data("npk");
            $.ajax({
                url: '/super-admin/siakad/tanggungan-mahasiswa/json/get-transaksi-mahasiswa',
                method: 'POST',
                data: {
                    npk: npk
                },
                beforeSend: function () {
                    $("#lihat-transaksi-loading-spin-" + npk).show();
                },
                success: function (response) {
                    var row = "<tr>" +
                        "<td colspan='9' style='text-align: center'>Tidak Ada Data</td>" +
                        "</tr>";
                    if (response.length > 0) {
                        row = "";
                        $.each(response, function (key, value) {
                            row = row + "<tr>" +
                                "<td>" + value.kode_biaya + "</td>" +
                                "<td>" + value.nama_biaya + "</td>" +
                                "<td>" + value.jumlah_bayar + "</td>" +
                                "<td>" + value.semester + "</td>" +
                                "<td>" + value.tgl_bayar + "</td>" +
                                "<td>" + value.id_kwitansi + "</td>" +
                                "<td>" + value.jenis_bayar + "</td>" +
                                "<td>" + value.keterangan + "</td>" +
                                "<td>" + value.denda + "</td>" +
                                "</tr>";
                        });
                    }
                    $.alert({
                        title: "Informasi",
                        type: "blue",
                        columnClass: "xlarge",
                        content: "<div class='table-responsive'>" +
                            "<table class='table table-striped'>" +
                            "<thead class='bg-info text-white'>" +
                            "<td>KODE</td>" +
                            "<td>NAMA BIAYA</td>" +
                            "<td>JUMLAH</td>" +
                            "<td>SEMESTER</td>" +
                            "<td>TGL BAYAR</td>" +
                            "<td>NO. KWIT.</td>" +
                            "<td>J. BYR</td>" +
                            "<td>KET</td>" +
                            "<td>DENDA</td>" +
                            "</thead>" +
                            "<tbody>" + row +
                            "</tbody>" +
                            "</table>" +
                            "</div>"
                    });
                },
                complete: function () {
                    $("#lihat-transaksi-loading-spin-" + npk).hide();
                }
            })
        });
    },
};

jQuery(document).ready(function () {
    jQuery.tanggungan_mahasiswa.init();
});
