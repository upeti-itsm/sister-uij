jQuery.daftar_pegawai = {
    data: {
        table: $("#table"),
        number: $(".number"),
        bulan: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
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
        // Option Data
        $(".select2").select2({
            placeholder: " Semua Pegawai"
        });
        $("#jenis_karyawan").change(function () {
            self.data.table.ajax.reload();
        });
        $("#btn_export_pdf").click(function () {
            $.confirm({
                type: 'purple',
                title: '',
                columnClass: 'small',
                content: '' +
                    '<div class="form-group">' +
                    '<label>Pilih Format</label>' +
                    '<select class="form-control format">' +
                    '<option value="data_bank">Data Bank</option>' +
                    '</select>' +
                    '</div>',
                buttons: {
                    export: {
                        text: 'Export',
                        btnClass: 'btn-blue',
                        action: function () {
                            var format = this.$content.find('.format').val();
                            if (!format) {
                                $.alert('Pilih Format Terlebih Dahulu');
                                return false;
                            }
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red',
                    },
                },
                backgroundDismissAnimation: 'glow'
            });
        });
        $("#btn_permanen").click(function () {
            var dateObj = new Date();
            $.confirm({
                type: 'purple',
                title: '',
                columnClass: 'medium',
                content: '' +
                    'Apakah anda yakin tidak ada perubahan data pada gaji periode pembayaran <b>' + self.data.bulan[dateObj.getMonth()] + '</b> tahun <b>' + dateObj.getFullYear() + '</b> ?<hr/>' +
                    '<span class="text-danger">Setelah permanen, anda tidak dapat merubah data gaji</span>',
                buttons: {
                    yakin: {
                        text: 'Yakin',
                        btnClass: 'btn-blue',
                        action: function () {

                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red',
                    },
                },
                backgroundDismissAnimation: 'glow'
            });
        });
        $("#btn_buat_gaji").click(function () {
            var dateObj = new Date();
            var bulan_sebelumnya = dateObj.getMonth() - 1;
            var tahun_sebelumnya = dateObj.getFullYear();
            if (bulan_sebelumnya < 0) {
                bulan_sebelumnya = 11
                tahun_sebelumnya = tahun_sebelumnya - 1;
            }
            $.confirm({
                type: 'purple',
                title: '',
                columnClass: 'medium',
                content: 'Apakah anda yakin akan membuat Gaji Bulanan periode <b>' + self.data.bulan[dateObj.getMonth()] + '</b> tahun <b>' + dateObj.getFullYear() + '</b> ?<hr/>' +
                    '<span class="text-danger">Perhitungan Gaji akan didasarkan pada data kinerja bulan <b>' + self.data.bulan[bulan_sebelumnya] + '</b> tahun <b>' + tahun_sebelumnya + '</b></span>',
                buttons: {
                    export: {
                        text: 'Yakin',
                        btnClass: 'btn-blue',
                        action: function () {
                            $.ajax({
                                url: '/keu/penggajian/gaji-bulanan/set-rekapitulasi',
                                method: 'post',
                                data: {},
                                beforeSend: function () {
                                    $("#loading-spin-buat-gaji").show();
                                },
                                success: function (response) {
                                    if (response.status === 1) {
                                        $.alert({
                                            title: 'Informasi',
                                            type: 'green',
                                            content: response.keterangan,
                                            backgroundDismissAnimation: 'glow'
                                        });
                                    } else {
                                        $.alert({
                                            title: 'Informasi',
                                            type: 'red',
                                            content: response.keterangan,
                                            backgroundDismissAnimation: 'glow'
                                        });
                                    }
                                },
                                complete: function () {
                                    $("#loading-spin-buat-gaji").hide();
                                    self.data.table.ajax.reload();
                                }
                            });
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red',
                    },
                },
                backgroundDismissAnimation: 'glow'
            });
        });

        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/keu/penggajian/pengaturan-gaji/daftar-pegawai/json',
                type: 'post',
                data: function (data) {
                    data.jenis_pegawai = $("#jenis_karyawan").val();
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
                    width: "35%",
                    render: function (data) {
                        var kinerja = "-";
                        if (data.is_kinerja)
                            kinerja = data.kd_kinerja + "( " + data.nominal_kinerja + " )";
                        return "<p><b>" + data.nama + "</b><br/>" +
                            "<small>NIK. " + data.nik_mandala +
                            " | " + data.jenis_karyawan + "</small></p>" +
                            "<hr/>" +
                            "<p>Kinerja : <span class='badge badge-info p-1 kinerja' data-nama='" + data.nama + "' style='cursor: pointer' title='Ubah Nilai Kinerja' id='kinerja-" + data.id_karyawan + "' data-id='" + data.id_karyawan + "' data-kinerja='" + data.kd_kinerja + "'>" + kinerja + "</span></p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        var dplk = '-';
                        if (data.is_dplk)
                            dplk = 'Rp. ' + numeral(data.nominal_dplk).format('0,-');
                        return "<p><b>" + data.unit_kerja + "</b><br/>" +
                            "<small>Pendidikan Terakhir : " + data.pendidikan_terakhir + "</small><hr/>" +
                            "<small>Nominal DPLK: " + dplk + "</small></p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                    render: function (data) {
                        var kesehatan = '';
                        if (data.is_kesehatan)
                            kesehatan = 'checked';

                        var ketenaga_kerjaan = '';
                        if (data.is_ketenagakerjaan)
                            ketenaga_kerjaan = 'checked';

                        var dplk = '';
                        if (data.is_dplk)
                            dplk = 'checked';

                        return "<div class='custom-control custom-switch'>" +
                            "<input type='checkbox' class='asuransi custom-switch custom-control-input' data-type='sehat' id='kesehatan-" + data.id_karyawan + "' data-id='" + data.id_karyawan + "' " + kesehatan + ">" +
                            "<label class='custom-control-label' for='kesehatan-" + data.id_karyawan + "'>Kesehatan</label>" +
                            "</div>" +
                            "<div class='custom-control custom-switch'>" +
                            "<input type='checkbox' class='asuransi custom-switch custom-control-input' data-type='kerja'  id='ketenagakerjaan-" + data.id_karyawan + "' data-id='" + data.id_karyawan + "' " + ketenaga_kerjaan + ">" +
                            "<label class='custom-control-label' for='ketenagakerjaan-" + data.id_karyawan + "'>Ketenagakerjaan</label>" +
                            "</div>" +
                            "<div class='custom-control custom-switch'>" +
                            "<input type='checkbox' class='dplk custom-switch custom-control-input' data-type='dplk' data-dplk='" + data.nominal_dplk + "' data-nama='" + data.nama + "' id='dplk-" + data.id_karyawan + "' data-id='" + data.id_karyawan + "' " + dplk + ">" +
                            "<label class='custom-control-label' for='dplk-" + data.id_karyawan + "'>Paguyuban Cooper</label>" +
                            "</div>";
                    }
                },
                {
                    data: 'nama',
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
        $("#table").on('click', 'input.asuransi', function () {
            var id = $(this).data('id');
            var type = $(this).data('type');
            var value = $(this).is(':checked');
            $.ajax({
                url: '/keu/penggajian/pengaturan-gaji/daftar-pegawai/update-asuransi',
                method: 'post',
                data: {
                    id: id,
                    status: value,
                    jenis: type
                },
                success: function (response) {
                    if (response.status === 1) {
                        $.alert({
                            title: 'Informasi',
                            type: 'green',
                            content: response.keterangan,
                            backgroundDismissAnimation: 'glow'
                        });
                    } else {
                        $.alert({
                            title: 'Informasi',
                            type: 'red',
                            content: response.keterangan,
                            backgroundDismissAnimation: 'glow'
                        });
                    }
                    self.data.table.ajax.reload();
                },
            });
        });
        $("#table").on('click', 'span.kinerja', function () {
            var id = $(this).data('id');
            var kinerja = $(this).data('kinerja');
            var nama = $(this).data('nama');
            $("#nama_karyawan").text(nama);
            $("#modal-nilai-kinerja").val(kinerja).change();
            $("#id_karyawan").val(id);
            $("#modal-update-data-kinerja").modal('show');
        });
        $("#modal-nilai-kinerja").change(function () {
            if ($(this).val() === "-") {
                $("#btn-simpan-data").removeClass('btn-primary');
                $("#btn-simpan-data").addClass('btn-secondary disabled');
            } else {
                $("#btn-simpan-data").removeClass('btn-secondary disabled');
                $("#btn-simpan-data").addClass('btn-primary');
            }
        });
        $("#btn-simpan-data").click(function () {
            if ($("#id_karyawan").val() && $("#modal-nilai-kinerja").val() !== "-") {
                $("#nilai_kinerja").val($("#modal-nilai-kinerja").val());
                $("#update_kinerja_form").submit();
            } else
                $.alert({
                    title: 'Peringatan',
                    type: 'green',
                    content: 'Pastikan Anda Sudah Memilih Nilai Kinerja',
                    backgroundDismissAnimation: 'glow'
                });
        })
        $("#table").on('click', 'input.dplk', function () {
            var id = $(this).data('id');
            var nominal = $(this).data('dplk');
            var nama = $(this).data('nama');
            var value = $(this).is(':checked');
            if (value) {
                $.confirm({
                    title: 'Konfirmasi !',
                    type: 'orange',
                    content: '<p>' +
                        'Isikan Potongan Terbaru Untuk <b>' + nama + '</b>' +
                        '</p>' +
                        '<div class="form-group">' +
                        '<label>Jumlah Potongan</label>' +
                        '<input type="text" class="form-control potongan" id="changeDPLK" onkeyup="keyUpNumber(\'changeDPLK\')" placeholder="Jumlah Potongan" value="' + numeral(nominal).format('0,-') + '">' +
                        '</div>',
                    buttons: {
                        confirm: {
                            text: 'Simpan',
                            btnClass: 'btn-green',
                            keys: ['enter'],
                            action: function () {
                                $.ajax({
                                    url: '/keu/penggajian/pengaturan-gaji/daftar-pegawai/update-dplk',
                                    type: 'POST',
                                    data: {
                                        id: id,
                                        nominal: numeral(this.$content.find('.potongan').val()).value(),
                                        status: value
                                    },
                                    success: function (result) {
                                        if (result.status === 1) {
                                            $.alert({
                                                title: "Informasi",
                                                type: "green",
                                                content: result.keterangan,
                                                backgroundDismissAnimation: 'glow',
                                            });
                                        } else {
                                            $.alert({
                                                title: "Peringatan",
                                                type: "red",
                                                content: result.keterangan,
                                                backgroundDismissAnimation: 'glow',
                                            });
                                        }
                                        self.data.table.ajax.reload();
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Batal',
                            btnClass: 'btn-red',
                            action: function () {
                                $("#dplk-" + id).prop('checked', false)
                            }
                        }
                    },
                    backgroundDismissAnimation: 'glow',
                    columnClass: 'small'
                });
            } else {
                $.confirm({
                    title: 'Konfirmasi !',
                    type: 'orange',
                    content: '<p>' +
                        'Apakah anda yakin mengahapus potongan DPLK sebesar Rp. ' + numeral(nominal).format('0,-') + ' karyawan atas nama <b>' + nama + '</b> ?' +
                        '</p>',
                    buttons: {
                        confirm: {
                            text: 'Yakin',
                            btnClass: 'btn-green',
                            keys: ['enter'],
                            action: function () {
                                $.ajax({
                                    url: '/keu/penggajian/pengaturan-gaji/daftar-pegawai/update-dplk',
                                    type: 'POST',
                                    data: {
                                        id: id,
                                        status: value
                                    },
                                    success: function (result) {
                                        if (result.status === 1) {
                                            $.alert({
                                                title: "Informasi",
                                                type: "green",
                                                content: result.keterangan,
                                                backgroundDismissAnimation: 'glow',
                                            });
                                        } else {
                                            $.alert({
                                                title: "Peringatan",
                                                type: "red",
                                                content: result.keterangan,
                                                backgroundDismissAnimation: 'glow',
                                            });
                                        }
                                        self.data.table.ajax.reload();
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Batal',
                            btnClass: 'btn-red',
                            action: function () {
                                $("#dplk-" + id).prop('checked', true)
                            }
                        }
                    },
                    backgroundDismissAnimation: 'glow',
                    columnClass: 'small'
                });
            }
        });
        $("#sub-menu").on('click', 'a', function () {
            location.href = $(this).attr('href');
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
        $("#filter_bulan").change(function () {
            self.data.table.ajax.reload();
        });
    },
};

jQuery(document).ready(function () {
    jQuery.daftar_pegawai.init();
});
