jQuery.daftar_tunjangan_struktural = {
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
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/keu/penggajian/pengaturan-gaji/tunjangan-struktural/json',
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
                        return "<p><b>" + data.jabatan_struktural + "</b></p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                    render: function (data) {
                        return "<p><b>" + data.tunjangan_struktural + "</b><br/>" +
                            "<small>Ekivalensi SKS: " + data.nilai_ekuivalen + "</small></p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        return "<button class='btn btn-success btn-edit mr-2' data-id='" + data.id_jabatan_struktural + "' data-jabatan_struktural='" + data.jabatan_struktural + "' title='Ubah Nominal Tunjangan' data-nominal='" + data.tunjangan_struktural_ + "' data-ekivalen='" + data.nilai_ekuivalen + "'><i class='fas fa-edit'></i></button>" +
                            "<button class='btn btn-danger btn-delete' data-id='" + data.id_jabatan_struktural + "'  data-jabatan_struktural='" + data.jabatan_struktural + "' title='Hapus Jabatan Struktural' data-nominal='" + data.tunjangan_struktural + "'><i class='fas fa-trash'></i></button>";
                    }
                },
                {
                    data: 'jabatan_struktural',
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
        $("#sub-menu").on('click', 'a', function () {
            location.href = $(this).attr('href');
        });
        $("#btn_add_data_struktural").click(function () {
            $("#modal-insup-data-struktural").modal('show');
        });
        $("#modal-insup-data-struktural").on('hidden.bs.modal', function () {
            $("#add_jabatan_struktural").val("");
            $("#add_nominal_tunjangan").val("");
            $("#add_sks_wajib").val("");
            $("#jabatan_struktural").val("");
            $("#nominal_tunjangan").val("");
            $("#id_jabatan_struktural").val(0);
            $("#sks_wajib").val("");
            self.setOffSaveButton();
        });
        $("#table").on('click', 'button.btn-edit', function () {
            $("#id_jabatan_struktural").val($(this).data('id'));
            $("#add_sks_wajib").val($(this).data('ekivalen'));
            $("#add_jabatan_struktural").val($(this).data('jabatan_struktural'));
            $("#add_nominal_tunjangan").val(numeral($(this).data('nominal')).format('0,-'));
            $("#modal-insup-data-struktural").modal('show');
            self.setOnSaveButton();
        });
        $("#btn-simpan-data").click(function () {
            if ($("#add_jabatan_struktural").val() && $("#add_nominal_tunjangan").val()) {
                $("#jabatan_struktural").val($("#add_jabatan_struktural").val());
                $("#nominal_tunjangan").val(numeral($("#add_nominal_tunjangan").val()).value());
                $("#sks_ekivalen").val(numeral($("#add_sks_wajib").val()).value());
                $("#add_form").submit();
            } else {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Jabatan Struktural dan Nominal Tunjangan Diisi",
                    backgroundDismissAnimation: 'glow'
                });
            }
        });
        $("#add_jabatan_struktural").change(function () {
            if (numeral($("#add_nominal_tunjangan").val()).value() > 0 && $(this).val()) {
                self.setOnSaveButton();
            } else {
                self.setOffSaveButton();
            }
        });

        $("#add_nominal_tunjangan").change(function () {
            if (numeral($(this).val()).value() > 0 && $("#add_jabatan_struktural").val()) {
                self.setOnSaveButton();
            } else {
                self.setOffSaveButton();
            }
        });
    },
    setOnSaveButton: function () {
        $("#btn-simpan-data").removeClass('btn-secondary disabled');
        $("#btn-simpan-data").addClass('btn-primary');
        $("#btn-simpan-data").attr('disabled', false);
    },
    setOffSaveButton: function () {
        $("#btn-simpan-data").removeClass('btn-primary');
        $("#btn-simpan-data").addClass('btn-secondary disabled');
        $("#btn-simpan-data").attr('disabled', true);
    }
};

jQuery(document).ready(function () {
    jQuery.daftar_tunjangan_struktural.init();
});
