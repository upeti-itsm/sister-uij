jQuery.pengaturan_hr = {
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

        $(".select2").select2({
            placeholder: " Semua Jenis Jenjang",
            minimumResultsForSearch: -1,
        });

        $("#jenis_jenjang").change(function () {
            self.data.table.ajax.reload();
        });

        self.data.number.keyup(function () {
            var $this = $(this);
            var input = $this.val();
            input = input.replace(/[\D\s\\._\-]+/g, "");
            input = input ? parseInt(input, 10) : 0;
            $this.val(function () {
                return input.toLocaleString("id-ID");
            });
        });

        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/keu/penggajian/pengaturan-gaji/pengaturan-hr/json',
                type: 'post',
                data: function (data) {
                    data.jenis_jenjang = $("#jenis_jenjang").val();
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
                        const number = meta.row + meta.settings._iDisplayStart + 1;
                        return "<b>" + number + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "45%",
                    render: function (data) {
                        return "<p><b>" + data.jabatan_fungsional + "</b></p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "45%",
                    render: function (data) {
                        const formatRupiah = (angka, prefix) => {
                            const number_string = angka.toString().replace(/[^,\d]/g, '').split(',');
                            const ribuan = number_string[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            return prefix === undefined ? ribuan : (ribuan ? 'Rp. ' + ribuan : '');
                        };

                        return "<p><b>Rp " + formatRupiah(data.nominal) + "</b></p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return "<p><b>" + data.keterangan.toUpperCase() + "</b></p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "10%",
                    render: function (data) {
                        return "<button class='btn btn-success btn-edit mr-2' data-id='" + data.id_jabatan_fungsional + "' data-jabatan_fungsional='" + data.jabatan_fungsional + "' title='Ubah Honor' data-nominal='" + data.nominal + "' data-keterangan='" + data.keterangan + "'><i class='fas fa-edit'></i></button>";
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

        $("#sub-menu").on('click', 'a', function () {
            location.href = $(this).attr('href');
        });

        $("#table").on('click', 'button.btn-edit', function () {
            $("#id_jabatan_fungsional").val($(this).data('id'));
            $("#add_jabatan_fungsional").val($(this).data('jabatan_fungsional'));
            $("#keterangan").text($(this).data('keterangan'));
            $("#add_nominal_tunjangan").val(numeral($(this).data('nominal')).format('0,-'));
            $("#input_jenis_jenjang").val($("#keterangan").val());
            $("#modal-edit-data-fungsional").modal('show');
            self.setOnSaveButton();
        });

        $("#btn-simpan-data").click(function () {
            if ($("#add_jabatan_fungsional").val() && $("#add_nominal_tunjangan").val()) {
                $("#jabatan_fungsional").val($("#add_jabatan_fungsional").val());
                $("#nominal_tunjangan").val(numeral($("#add_nominal_tunjangan").val()).value());
                $("#input_jenis_jenjang").val($("#keterangan").val());
                $("#add_form").submit();
            } else {
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Jabatan Struktural dan Nominal Honor Diisi",
                    backgroundDismissAnimation: 'glow'
                });
            }
        });

        $("#add_jabatan_fungsional").change(function () {
            if (numeral($("#add_nominal_tunjangan").val()).value() > 0 && $(this).val()) {
                self.setOnSaveButton();
            } else {
                self.setOffSaveButton();
            }
        });

        $("#add_nominal_tunjangan").change(function () {
            if (numeral($(this).val()).value() > 0 && $("#add_jabatan_fungsional").val()) {
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
    jQuery.pengaturan_hr.init();
});
