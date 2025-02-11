jQuery.daftar_tunjangan_kinerja = {
    data: {
        table: $("#table"),
        number: $(".number"),
    },
    init: function () {
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
        numeral.locale('id');
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        self.data.number.keyup(function () {
            var $this = $(this);
            var input = $this.val();
            input = input.replace(/[\D\s\\._\-]+/g, "");
            input = input ? parseInt(input, 10) : 0;
            $this.val(function () {
                return input.toLocaleString("id-ID");
            });
        });
        // Option Data
        $(".select2").select2();
        $("#btn_add_data_kinerja").click(function () {
            $("#modal-tambah-data-kinerja").modal('show');
        });
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/keu/penggajian/pengaturan-gaji/tunjangan-kinerja/json',
                type: 'post'
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
                    width: "40%",
                    render: function (data) {
                        return "<p><b>" + data.kd_kinerja + "</b> <small>(" + data.keterangan + ")</small></p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                    render: function (data) {
                        return "<p><b>" + data.nominal_kinerja + "</b></p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        return "<button class='btn btn-success btn-edit mr-2' data-id='" + data.kd_kinerja + "' title='Ubah Nominal Tunjangan' data-kd_kinerja='" + data.kd_kinerja + "' data-nominal='" + data.nominal_kinerja_ + "' data-keterangan='" + data.keterangan + "'><i class='fas fa-edit'></i></button>" +
                            "<button class='btn btn-danger btn-delete' data-id='" + data.kd_kinerja + "' title='Hapus Nilai Kinerja'  data-kd_kinerja='" + data.kd_kinerja + "' data-nominal='" + data.nominal_kinerja + "'><i class='fas fa-trash'></i></button>";
                    }
                },
                {
                    data: 'kd_kinerja',
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
        $("#sub-menu").on('click', 'a', function () {
            location.href = $(this).attr('href');
        });
        $("#btn_add_data_kinerja").click(function () {
            $("#modal-insup-data-kinerja").modal('show');
        });
        $("#modal-insup-data-kinerja").on('hidden.bs.modal', function () {
            $("#add_nilai_kinerja").val("");
            $("#add_nominal_kinerja").val("");
            $("#add_keterangan").val("");
            $("#nilai_kinerja").val("");
            $("#nominal_kinerja").val("");
            $("#keterangan").val("");
            self.setOffSaveButton();
        });
        $("#table").on('click', 'button.btn-edit', function () {
            $("#add_nilai_kinerja").val($(this).data('kd_kinerja'));
            $("#add_nominal_kinerja").val(numeral($(this).data('nominal')).format('0,-'));
            $("#add_keterangan").val($(this).data('keterangan'));
            $("#modal-insup-data-kinerja").modal('show');
            self.setOnSaveButton();
        });
        $("#btn-simpan-data").click(function () {
            if ($("#add_nilai_kinerja").val() && $("#add_nominal_kinerja").val() && $("#add_keterangan").val()) {
                $("#nilai_kinerja").val($("#add_nilai_kinerja").val());
                $("#nominal_kinerja").val(numeral($("#add_nominal_kinerja").val()).value());
                $("#keterangan").val($("#add_keterangan").val());
                $("#add_form").submit();
            } else {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan KD Kinerja, Nilai Kinerja, dan Keterangan Diisi",
                    backgroundDismissAnimation: 'glow'
                });
            }
        });
        $("#add_nilai_kinerja").change(function () {
            if (numeral($("#add_nominal_kinerja").val()).value() > 0 && $(this).val() && $("#add_keterangan").val()) {
                self.setOnSaveButton();
            } else {
                self.setOffSaveButton();
            }
        });

        $("#add_nominal_kinerja").change(function () {
            if (numeral($(this).val()).value() > 0 && $("#add_nilai_kinerja").val() && $("#add_keterangan").val()) {
                self.setOnSaveButton();
            } else {
                self.setOffSaveButton();
            }
        });

        $("#add_keterangan").change(function () {
            if (numeral($("#add_nominal_kinerja").val()).value() > 0 && $("#add_nilai_kinerja").val() && $(this).val()) {
                self.setOnSaveButton();
            } else {
                self.setOffSaveButton();
            }
        });
    },
    setOnSaveButton: function (){
        $("#btn-simpan-data").removeClass('btn-secondary disabled');
        $("#btn-simpan-data").addClass('btn-primary');
        $("#btn-simpan-data").attr('disabled', false);
    },
    setOffSaveButton: function (){
        $("#btn-simpan-data").removeClass('btn-primary');
        $("#btn-simpan-data").addClass('btn-secondary disabled');
        $("#btn-simpan-data").attr('disabled', true);
    }
};

jQuery(document).ready(function () {
    jQuery.daftar_tunjangan_kinerja.init();
});
