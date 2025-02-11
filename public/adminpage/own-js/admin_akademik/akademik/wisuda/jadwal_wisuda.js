jQuery.jadwal_wisuda = {
    data: {
        table: $("#table"),
        tgl_pelaksanaan: $("#tgl_pelaksanaan"),
        tgl_pendaftaran_dibuka: $("#tgl_pendaftaran_dibuka"),
        tgl_pendaftaran_ditutup: $("#tgl_pendaftaran_ditutup"),
        pendaftaran_area: $("#pendaftaran-area"),
        btn_save_jadwal: $("#btn-save-jadwal"),
        kuota: $("#kuota"),
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        numeral.register('locale', 'id', {
            delimiters: {
                thousands: '.',
                decimal: ','
            },
            abbreviations: {
                thousand: 'k',
                million: 'm',
                billion: 'b',
                trillion: 't'
            },
            ordinal: function (number) {
                return number === 1 ? 'er' : 'ème';
            },
            currency: {
                symbol: 'Rp.'
            }
        });
        numeral.locale('id')
        // Option Data
        $(".select2").select2();
        self.data.kuota.keyup(function () {
            var $this = $(this);
            var input = $this.val();
            input = input.replace(/[\D\s\\._\-]+/g, "");
            input = input ? parseInt(input, 10) : 0;
            $this.val(function () {
                return input.toLocaleString("id-ID");
            });
        });
        self.data.tgl_pelaksanaan = $("#tgl_pelaksanaan").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            startDate: moment().format('D/M/YYYY'),
            orientation: 'bottom'
        }).on('changeDate', function (e) {
            self.data.pendaftaran_area.show();
            self.data.pendaftaran_area.addClass('animate__fadeInLeft');
            self.data.tgl_pendaftaran_dibuka.datepicker('setEndDate', moment(e.date).format('D/M/YYYY'));
            self.data.tgl_pendaftaran_ditutup.datepicker('setEndDate', moment(e.date).format('D/M/YYYY'));
        });

        self.data.tgl_pendaftaran_dibuka = $("#tgl_pendaftaran_dibuka").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            startDate: moment().format('D/M/YYYY'),
            endDate: moment().format('D/M/YYYY'),
            orientation: 'bottom'
        }).on('changeDate', function (e) {
            self.data.tgl_pendaftaran_ditutup.datepicker('setDate', moment(e.date).format('D/M/YYYY'));
            self.data.tgl_pendaftaran_ditutup.datepicker('setStartDate', moment(e.date).format('D/M/YYYY'));
        });

        self.data.tgl_pendaftaran_ditutup = $("#tgl_pendaftaran_ditutup").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            startDate: moment().format('D/M/YYYY'),
            endDate: moment().format('D/M/YYYY'),
            orientation: 'bottom'
        }).on('changeDate', function () {
            self.data.btn_save_jadwal.removeClass('btn-secondary disabled');
            self.data.btn_save_jadwal.addClass('btn-primary');
        });

        // Table With DataTable
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/adm-akadmik/perkuliahan/jadwal-wisuda/json',
                type: 'POST',
                data: function (data) {
                    data.tahun_pelaksanaan = $("#tahun_pelaksanaan_filter").val();
                }
            },
            scrollY: '400px',
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
                    width: "55%",
                    render: function (data) {
                        return "<b>Wisuda ITS Mandala Tahun " + data.tahun_pelaksanaan + " Periode " + data.periode + "</b><br/>" +
                            "<small>Tanggal Pelaksanaan : " + data.tgl_pelaksanaan_ + "</small><br/>" +
                            "<small>Tanggal Pendaftaran : " + data.tgl_pendaftaran_dibuka_ + " s/d " + data.tgl_pendaftaran_ditutup_ + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "25%",
                    render: function (data) {
                        var prosen = Math.round((data.peserta / data.kuota) * 100);
                        return "<div class='progress bg-gray' style='height: 15px'>" +
                            "<div class='progress-bar progress-bar-striped progress-bar-animated' style='height: 15px; width: " + prosen + "%'>" + prosen + "%</div>" +
                            "</div>" +
                            "<b>" + data.peserta + "/" + data.kuota + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        return "<button title='Ubah Jadwal' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_jadwal_wisuda + "' data-tahun_pelaksanaan='" + data.tahun_pelaksanaan + "' data-kuota='" + data.kuota + "' data-periode='" + data.periode + "' data-tgl_pelaksanaan='" + data.tgl_pelaksanaan + "' data-tgl_pendaftaran_dibuka='" + data.tgl_pendaftaran_dibuka + "' data-tgl_pendaftaran_ditutup='" + data.tgl_pendaftaran_ditutup + "'><i class='fas fa-edit'></i></button>" +
                            "<button title='Hapus Jadwal' class='btn btn-sm btn-danger btn-delete mr-2' data-id='" + data.id_jadwal_wisuda + "' data-tahun_pelaksanaan='" + data.tahun_pelaksanaan + "' data-periode='" + data.periode + "'><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_jadwal_wisuda + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
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
        // Add or Update Data
        // Add
        $("#btn-tambah-data").click(function () {
            $("#filter-collapse").collapse("hide");
            $("#form-collapse").collapse("show");
            $("#id").val("");
            $("#tgl_pendaftaran_dibuka").attr('disabled', false);
            self.data.tgl_pelaksanaan.datepicker('setStartDate', moment().format('D/M/YYYY'));
            self.setDate();
            self.setDitutupDate();
        });
        // On Cancel Click
        $("#btn-cancel").click(function () {
            self.data.kuota.val("150");
            self.data.tgl_pelaksanaan.datepicker('setDate', moment().format('D/M/YYYY'));
            self.setDate();
            $("#cari-data").val("");
            $("#filter-collapse").collapse("show");
            $("#form-collapse").collapse("hide");
        });
        // On Edit
        $("#table").on('click', 'button.btn-edit', function () {
            $("#periode").val($(this).data("periode")).change();
            $("#kuota").val(numeral($(this).data("kuota")).format('0,0'));
            if (moment($(this).data('tgl_pelaksanaan')).diff(moment()) >= 0)
                self.data.tgl_pelaksanaan.datepicker('setDate', moment($(this).data('tgl_pelaksanaan')).format('D/M/YYYY'));
            else {
                self.data.tgl_pelaksanaan.datepicker('setStartDate', moment($(this).data('tgl_pelaksanaan')).format('D/M/YYYY'));
                self.data.tgl_pelaksanaan.datepicker('setDate', moment($(this).data('tgl_pelaksanaan')).format('D/M/YYYY'));
            }
            self.setDate();
            if (moment($(this).data('tgl_pendaftaran_dibuka')).diff(moment()) >= 0)
                self.data.tgl_pendaftaran_dibuka.datepicker('setDate', moment($(this).data('tgl_pendaftaran_dibuka')).format('D/M/YYYY'));
            else {
                self.data.tgl_pendaftaran_dibuka.datepicker('setStartDate', moment($(this).data('tgl_pendaftaran_dibuka')).format('D/M/YYYY'));
                self.data.tgl_pendaftaran_dibuka.datepicker('setDate', moment($(this).data('tgl_pendaftaran_dibuka')).format('D/M/YYYY'));
            }
            self.setDitutupDate();
            if (moment($(this).data('tgl_pendaftaran_ditutup')).diff(moment()) >= 0)
                self.data.tgl_pendaftaran_ditutup.datepicker('setDate', moment($(this).data('tgl_pendaftaran_ditutup')).format('D/M/YYYY'));
            else {
                self.data.tgl_pendaftaran_ditutup.datepicker('setStartDate', moment($(this).data('tgl_pendaftaran_ditutup')).format('D/M/YYYY'));
                self.data.tgl_pendaftaran_ditutup.datepicker('setDate', moment($(this).data('tgl_pendaftaran_ditutup')).format('D/M/YYYY'));

            }
            $("#btn-tambah-data").trigger("click");
            $("#id").val($(this).data("id"));
            $("#tgl_pendaftaran_dibuka").attr('disabled', true);
        });

        // On Delete
        $("#table").on('click', 'button.btn-delete', function () {
            var id = $(this).data("id");
            var tahun_akademik = $(this).data('tahun_akademik');
            var periode = $(this).data('periode');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin menghapus Jadwal Wisuda pada Tahun Akademik <b>' + tahun_akademik + ' Periode ' + periode + '</b> dari sistem ?<br/><b class="text-danger">Jadwal akan terhapus jika dan hanya jika belum ada pendaftar</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',

                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/adm-akadmik/perkuliahan/jadwal-wisuda/delete',
                                method: 'POST',
                                data: {
                                    id: id
                                },
                                beforeSend: function () {
                                    $("#detail-loading-spin-" + id).show();
                                },
                                success: function (response) {
                                    if (response.status === 1) {
                                        $.alert({
                                            title: 'Informasi',
                                            type: 'green',
                                            content: response.keterangan
                                        });
                                    } else {
                                        $.alert({
                                            title: 'Informasi',
                                            type: 'red',
                                            content: response.keterangan
                                        });
                                    }
                                },
                                complete: function () {
                                    $("#detail-loading-spin-" + id).hide();
                                    self.data.table.ajax.reload();
                                }
                            })
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red'
                    }
                }
            });
        });

        // On Save Data
        self.data.btn_save_jadwal.click(function () {
            if (!self.data.kuota.val())
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Semua Data Terisi"
                });
            else {
                var operasi = 'store';
                var id = 0
                if ($("#id").val()) {
                    id = $("#id").val();
                    operasi = 'update'
                }
                $.ajax({
                    url: '/adm-akadmik/perkuliahan/jadwal-wisuda/' + operasi,
                    method: 'POST',
                    data: {
                        periode: $("#periode").val(),
                        kuota: self.data.kuota.val().replaceAll('.', ''),
                        tgl_pelaksanaan: moment(self.data.tgl_pelaksanaan.datepicker('getDate')).format('YYYY-MM-DD'),
                        tgl_pendaftaran_dibuka: moment(self.data.tgl_pendaftaran_dibuka.datepicker('getDate')).format('YYYY-MM-DD 00:00:01'),
                        tgl_pendaftaran_ditutup: moment(self.data.tgl_pendaftaran_ditutup.datepicker('getDate')).format('YYYY-MM-DD 23:59:59'),
                        id: id
                    },
                    beforeSend: function () {
                        $("#loading-tambah-data").show();
                    },
                    success: function (response) {
                        if (response.status === 1) {
                            $.alert({
                                title: 'Informasi',
                                type: 'green',
                                content: response.keterangan
                            });
                            $("#btn-cancel").trigger("click");
                        } else {
                            $.alert({
                                title: 'Informasi',
                                type: 'red',
                                content: response.keterangan
                            });
                        }
                    },
                    complete: function () {
                        $("#loading-tambah-data").hide();
                        self.data.table.ajax.reload();
                    }
                });
            }
        });
    },
    setDate: function () {
        var self = this;
        self.data.tgl_pendaftaran_dibuka.datepicker('setEndDate', moment(self.data.tgl_pelaksanaan.datepicker('getDate')).format('D/M/YYYY'));
        self.data.tgl_pendaftaran_dibuka.datepicker('setDate', moment().format('D/M/YYYY'));
    },
    setDitutupDate: function (){
        var self = this;
        self.data.tgl_pendaftaran_ditutup.datepicker('setStartDate', moment(self.data.tgl_pendaftaran_dibuka.datepicker('getDate')).format('D/M/YYYY'));
        self.data.tgl_pendaftaran_ditutup.datepicker('setEndDate', moment(self.data.tgl_pelaksanaan.datepicker('getDate')).format('D/M/YYYY'));
        self.data.tgl_pendaftaran_ditutup.datepicker('setDate', moment().format('D/M/YYYY'));
    }
};

jQuery(document).ready(function () {
    jQuery.jadwal_wisuda.init();
});
