jQuery.daftar_pegawai = {
    data: {
        table: $("#ohmytable"),
        number: $(".number")
    },
    init: function () {
        var self = this;
        // Cek apakah locale 'id' belum terdaftar sebelum melakukan register
        if (numeral.locales['id'] === undefined) {
            numeral.register('locale', 'id', {
                delimiters: { thousands: '.', decimal: ',' },
                abbreviations: { thousand: 'k', million: 'm', billion: 'b', trillion: 't' },
                ordinal: function (number) { return number === 1 ? 'er' : 'ème'; },
                currency: { symbol: 'Rp.' }
            });
        }
        numeral.locale('id');
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        $(".select2").select2({
            placeholder: "-- Semua Status --",
            width: '100%',
            minimumResultsForSearch: -1
        });
        $("#status").change(function () {
            self.data.table.ajax.reload();
        });
        self.data.table = $("#ohmytable").DataTable({
            serverSide: true,
            ajax: {
                url: '/keu/penggajian/pengaturan-gaji/pengaturan-gaji-pokok/request',
                type: 'post',
                data: function (data) {
                    data.status = $("#status").val();
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
                    searchable: true,
                    sClass: 'text-left',
                    width: "30%",
                    render: function (data) {
                        return `
                            <p class="mb-0 font-weight-bold text-primary">${data.golongan || ''}</p>
                            <hr class="my-1"/>
                            <span class="text-muted">
                                Masa Kerja : ${data.masa_kerja ?? 0} tahun
                            </span>
                        `;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        return `
                            <p class="mb-0 font-weight-bold text-primary">
                                ${data.gaji_pokok_ || 'Rp. 0'}
                            </p>
                        `;
                    }
                },
                {
                    data: 'sts_aktif',
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        return data
                            ? '<span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Aktif</span>'
                            : '<span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i> Tidak Aktif</span>';
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        return `
                            <div>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-primary btn-block mb-1 js-edit-golongan"
                                    data-id="${data.id_golongan}"
                                    data-golongan="${data.golongan || ''}"
                                    data-masakerja="${data.masa_kerja ?? 0}"
                                    data-gajipokok="${data.gaji_pokok ?? 0}">
                                    <i class="fas fa-edit mr-1"></i> Ubah Data
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-sm ${data.sts_aktif ? 'btn-danger' : 'btn-success'} btn-block js-toggle-status"
                                    data-id="${data.id_golongan}"
                                    data-status="${data.sts_aktif}">
                                    <i class="fas ${data.sts_aktif ? 'fa-times-circle' : 'fa-check-circle'} mr-1"></i>
                                    ${data.sts_aktif ? 'Nonaktifkan' : 'Aktifkan'}
                                </button>
                            </div>
                        `;
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

        // Custom File Input Label update
        $(".custom-file-input").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName || "Pilih file Excel...");
        });

        // Event for Import Button
        $("#btn-import").click(function () {
            $("#modal-import-excel").modal("show");
        });

        // Event for Edit Button
        $("#ohmytable").on('click', '.js-edit-golongan', function () {
            var id = $(this).data('id');
            var golongan = $(this).data('golongan');
            var masaKerja = $(this).data('masakerja');
            var gajiPokok = $(this).data('gajipokok');

            $("#insup_id_golongan").val(id);
            $("#insup_golongan").val(golongan);
            $("#insup_masa_kerja").val(masaKerja);

            var formattedGaji = gajiPokok ? parseInt(gajiPokok, 10).toLocaleString("id-ID") : "0";
            $("#insup_gaji_pokok").val(formattedGaji);

            $("#modal-insup-golongan").modal("show");
        });

        // Event for Save Edit Button
        $("#btn-simpan-golongan").click(function () {
            var id = $("#insup_id_golongan").val();
            var golongan = $("#insup_golongan").val();
            var masaKerja = $("#insup_masa_kerja").val();
            var gajiPokok = $("#insup_gaji_pokok").val();

            if (!golongan || masaKerja === "" || !gajiPokok) {
                $.alert({
                    title: 'Peringatan',
                    type: 'orange',
                    content: 'Harap lengkapi semua bidang.',
                    backgroundDismissAnimation: 'glow'
                });
                return;
            }

            $.ajax({
                url: '/keu/penggajian/pengaturan-gaji/pengaturan-gaji-pokok/insup',
                method: 'POST',
                data: {
                    _token: $('input[name="_token"]').val(),
                    id_golongan: id,
                    golongan: golongan,
                    masa_kerja: masaKerja,
                    gaji_pokok: gajiPokok
                },
                beforeSend: function () {
                    $("#btn-simpan-golongan").prop('disabled', true);
                },
                success: function (response) {
                    if (response.status === 1) {
                        $.alert({
                            title: 'Berhasil',
                            type: 'green',
                            content: response.keterangan,
                            backgroundDismissAnimation: 'glow'
                        });
                        $("#modal-insup-golongan").modal("hide");
                        self.data.table.ajax.reload();
                    } else {
                        $.alert({
                            title: 'Peringatan',
                            type: 'red',
                            content: response.keterangan,
                            backgroundDismissAnimation: 'glow'
                        });
                    }
                },
                error: function (xhr) {
                    var msg = 'Terjadi kesalahan sistem';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    $.alert({
                        title: 'Gagal',
                        type: 'red',
                        content: msg,
                        backgroundDismissAnimation: 'glow'
                    });
                },
                complete: function () {
                    $("#btn-simpan-golongan").prop('disabled', false);
                }
            });
        });

        // Event for Toggle Active / Inactive Status
        $("#ohmytable").on('click', '.js-toggle-status', function () {
            var id = $(this).data('id');
            var status = $(this).data('status');
            var isAktif = (status === true || status === 'true' || status === 1 || status === '1');
            var nextStatus = !isAktif;
            var actionText = isAktif ? 'menonaktifkan' : 'mengaktifkan';

            $.confirm({
                title: 'Konfirmasi !',
                type: isAktif ? 'red' : 'green',
                content: 'Apakah Anda yakin ingin <b>' + actionText + '</b> golongan ini?',
                buttons: {
                    confirm: {
                        text: 'Ya, ' + (isAktif ? 'Nonaktifkan' : 'Aktifkan'),
                        btnClass: isAktif ? 'btn-red' : 'btn-green',
                        action: function () {
                            $.ajax({
                                url: '/keu/penggajian/pengaturan-gaji/pengaturan-gaji-pokok/set-status',
                                method: 'POST',
                                data: {
                                    _token: $('input[name="_token"]').val(),
                                    id_golongan: id,
                                    status: nextStatus
                                },
                                success: function (response) {
                                    if (response.status === 1) {
                                        $.alert({
                                            title: 'Informasi',
                                            type: 'green',
                                            content: response.keterangan,
                                            backgroundDismissAnimation: 'glow'
                                        });
                                        self.data.table.ajax.reload();
                                    } else {
                                        $.alert({
                                            title: 'Peringatan',
                                            type: 'red',
                                            content: response.keterangan,
                                            backgroundDismissAnimation: 'glow'
                                        });
                                    }
                                },
                                error: function (xhr) {
                                    $.alert({
                                        title: 'Gagal',
                                        type: 'red',
                                        content: 'Terjadi kesalahan pada server.',
                                        backgroundDismissAnimation: 'glow'
                                    });
                                }
                            });
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-default'
                    }
                },
                backgroundDismissAnimation: 'glow'
            });
        });

        // Search & Filter Events
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
    }
};

jQuery(document).ready(function () {
    jQuery.daftar_pegawai.init();
});
