jQuery.kui_kerjasama = {
    data: {
        table: $("#table"),
        tgl_kegiatan: $("#tgl_kegiatan_"),
        tahun_berlaku: $("#tgl_berlaku_"),
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
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/kui/insup-kerjsama-json',
                type: 'post',
                data: function (data) {
                    data.level = $("#level").val();
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'nomor',
                    searchable: false,
                    sClass: 'text-center',
                    width: "5%",
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        return "<b>" + data.lembaga_mitra_kerjasama + "</b><br/>" +
                            "<small>Tingkat Kerjasama: " + data.tingkat_kerjasama + "<br/>" +
                            "Tanggal: " + data.tanggal_kegiatan + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                    render: function (data) {
                        return "<b>" + data.bentuk_kegiatan + "</b><br/>" +
                            "<small>Cakupan: " + data.level_institusi + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        return "<b>" + data.bukti_kerjasama + "</b><br/>" +
                            "<a href='" + data.link_dokumen_bukti_kerjasama + "'>" + data.link_dokumen_bukti_kerjasama + "</a>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        return "<button class='btn btn-danger btn-delete mr-2' data-id='" + data.id_kerjasama + "'><i class='fas fa-trash'></i></button>" +
                            "<button class='btn btn-primary btn-edit' data-id='"+data.id_kerjasama+"' data-mitra='"+data.lembaga_mitra_kerjasama+"' data-tingkat='"+data.id_tingkat_kerjasama+"' data-bentuk='"+data.bentuk_kegiatan+"' data-tanggal='"+data.tanggal_kegiatan_+"' data-tahun='"+data.masa_berlaku+"' data-level='"+data.id_level_institusi+"' data-bukti='"+data.bukti_kerjasama+"' data-link='"+data.link_dokumen_bukti_kerjasama+"'><i class='fas fa-edit'></i></button>";
                    }
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
        $("#btn-filter").click(function () {
            self.data.table.ajax.reload();
        });
        $("#btn-cari-data").click(function () {
            self.data.table.search($("#cari-data").val()).draw();
        });
        $("#cari-data").keyup(function () {
            if (this.value === "") {
                self.data.table.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                self.data.table.search(this.value).draw();
            }
        });

        $("#btn-add-kerjasama").click(function () {
            $("#form_collapse").collapse('show');
            $("#filter-collapse").collapse('hide');
        });

        $("#btn-cancel").click(function () {
            $("#id_dokumen").val(0);
            $("#form_collapse").collapse('hide');
            $("#filter-collapse").collapse('show');
        });
        $("#btn-save").click(function () {
            if ($("#lembaga_mitra").val() && $("#tingkat_kerjasama").val() && $("#bentuk_kegiatan").val() && $("#tingkatan_level").val() && $("#link_dokumen").val() && $("#bukti_kerjasama").val()) {
                $.confirm({
                    type: 'purple',
                    title: '',
                    columnClass: 'large',
                    content: 'Apakah anda yakin akan menambahkan data kerjasama ?',
                    buttons: {
                        export: {
                            text: 'Yakin',
                            btnClass: 'btn-blue',
                            action: function () {
                                $("#form-data").submit();
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

        self.data.tgl_kegiatan = $("#tgl_kegiatan_").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment().format('D/M/YYYY')).on('changeDate', function (e) {
            $("#tgl_kegiatan").val(moment(e.date).format('YYYY-MM-DD'));
        });
        $("#tgl_kegiatan").val(moment().format('YYYY-MM-DD'));

        self.data.tahun_berlaku = $("#tgl_berlaku_").datepicker({
            language: 'id',
            format: 'yyyy',
            autoclose: true,
            orientation: 'bottom',
            minViewMode: 'years'
        }).datepicker('setDate', moment().format('D/M/YYYY')).on('changeDate', function (e) {
            $("#masa_berlaku").val(moment(e.date).format('YYYY'));
        });
        $("#masa_berlaku").val(moment().format('YYYY'));

        $("#table").on('click', 'button.btn-delete', function () {
            var id = $(this).data('id');
            $.confirm({
                title: "Confirmation!",
                content: "Apakah anda yakin untuk menghapus kerjasama ini ?",
                type: 'warning',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $("#id_kerjasama_del").val(id)
                            $("#form-delete").submit();
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red'
                    }
                },
            })
        });

        $("#table").on('click', 'button.btn-edit', function () {
            var id = $(this).data('id');
            var mitra = $(this).data('mitra');
            var tingkat = $(this).data('tingkat');
            var bentuk = $(this).data('bentuk');
            var tanggal = $(this).data('tanggal');
            var tahun = $(this).data('tahun');
            var level = $(this).data('level');
            var bukti = $(this).data('bukti');
            var link = $(this).data('link');
            $("#id_dokumen").val(id);
            $("#lembaga_mitra").val(mitra);
            $("#tingkat_kerjasama").val(tingkat).change();
            $("#bentuk_kegiatan").val(bentuk);
            $("#tgl_kegiatan").val(tanggal);
            self.data.tgl_kegiatan.datepicker('setDate', moment(tanggal).format('D/M/YYYY'));
            self.data.tahun_berlaku.datepicker('setDate', moment(tahun + '-01-01').format('YYYY'))
            $("#masa_berlaku").val(tahun);
            $("#level").val(level).change();
            $("#bukti_kerjasama").val(bukti);
            $("#link_dokumen").val(link);

            $("#form_collapse").collapse('show');
            $("#filter-collapse").collapse('hide');
        });
    },
};

jQuery(document).ready(function () {
    jQuery.kui_kerjasama.init();
});
