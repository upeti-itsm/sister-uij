jQuery.gaji_pokok = {
    data: {
        table: $("#table")
    },
    parseCurrency: function (str) {
        if (!str) return 0;
        return parseFloat(String(str).replace(/[^0-9.-]+/g, "")) || 0;
    },
    loadJenisKaryawan: function (callback) {
        $.ajax({
            url: '/keu/penggajian/pengaturan-gaji/gaji-pokok/jenis-karyawan',
            method: 'POST',
            data: {
                _token: $('input[name="_token"]').val()
            },
            success: function (response) {
                callback(response.results || []);
            },
            error: function (xhr) {
                console.error('Gagal memuat jenis karyawan:', xhr.status, xhr.responseText);
                callback([]);
            }
        });
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;

        self.loadJenisKaryawan(function (results) {
            var $filterSelect = $("#id_jenis_karyawan");
            $filterSelect.empty();
            $filterSelect.append('<option value="">-- Semua Jenis Karyawan --</option>');
            results.forEach(function (item) {
                $filterSelect.append('<option value="' + item.id + '">' + item.text + '</option>');
            });
            $filterSelect.select2({
                placeholder: "-- Semua Jenis Karyawan --",
                width: '100%'
            });
            $filterSelect.on('change', function () {
                self.data.table.ajax.reload();
            });

            var $modalSelect = $("#insert_id_jenis_karyawan");
            $modalSelect.empty();
            $modalSelect.append('<option value="">-- Pilih Jenis Karyawan --</option>');
            results.forEach(function (item) {
                $modalSelect.append('<option value="' + item.id + '">' + item.text + '</option>');
            });
            $modalSelect.select2({
                placeholder: "-- Pilih Jenis Karyawan --",
                width: '100%',
                dropdownParent: $("#modal-tambah-data")
            });
        });

        $("#insert_kd_pendidikan").select2({
            placeholder: "-- Pilih Jenis Pendidikan --",
            width: '100%',
            dropdownParent: $("#modal-tambah-data"),
            minimumInputLength: 0,
            ajax: {
                url: '/keu/penggajian/pengaturan-gaji/gaji-pokok/pendidikan',
                type: 'post',
                dataType: 'json',
                data: function (params) {
                    return {
                        _token: $('input[name="_token"]').val(),
                        search: params.term || ''
                    };
                },
                processResults: function (response) {
                    return { results: response.results };
                },
                transport: function (params, success, failure) {
                    var $request = $.ajax(params);
                    $request.then(success);
                    $request.fail(function (xhr) {
                        console.error('Gagal memuat data pendidikan:', xhr.status, xhr.responseText);
                        failure();
                    });
                    return $request;
                },
                delay: 300,
                cache: true
            },
            language: {
                searching: function () { return 'Mencari...'; },
                noResults: function () { return 'Data pendidikan tidak ditemukan'; },
                errorLoading: function () { return 'Gagal memuat data. Cek koneksi / hubungi admin.'; }
            }
        });

        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/keu/penggajian/pengaturan-gaji/gaji-pokok/data',
                type: 'post',
                data: function (data) {
                    data.id_jenis_karyawan = $("#id_jenis_karyawan").val();
                }
            },
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
                    render: function (data) {
                        var label = data.kd_pendidikan.toUpperCase();
                        return `<span class="font-weight-bold">${label}</span>`;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    render: function (data) {
                        var formatted = data.nominal_tunjangan;
                        return `<p class="mb-0 font-weight-bold text-primary">Rp. ${self.parseCurrency(formatted).toLocaleString("id-ID")}</p>`;
                    }
                },
                {
                    data: 'sts_aktif',
                    searchable: false,
                    sClass: 'text-center',
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
                    render: function (data) {
                        var label = data.kd_pendidikan.toUpperCase();
                        return `
                            <div>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-primary btn-block mb-1 js-edit-nominal"
                                    data-id="${data.id_config_tunjangan_pendidikan}"
                                    data-nama="${label}"
                                    data-nominal="${self.parseCurrency(data.nominal_tunjangan)}"
                                    data-status="${data.sts_aktif}">
                                    <i class="fas fa-edit mr-1"></i> Ubah Nominal
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-sm ${data.sts_aktif ? 'btn-danger' : 'btn-success'} btn-block js-toggle-status"
                                    data-id="${data.id_config_tunjangan_pendidikan}"
                                    data-nominal="${self.parseCurrency(data.nominal_tunjangan)}"
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

        // Event: Buka Modal Tambah Data
        $("#btn-tambah-data").click(function () {
            $("#insert_id_jenis_karyawan").val('').trigger('change');
            $("#insert_kd_pendidikan").val(null).trigger('change');
            $("#insert_nominal_tunjangan").val('');
            $("#modal-tambah-data").modal("show");
        });

        // Event: Simpan Tambah Data
        $("#btn-simpan-tambah").click(function () {
            var idJenisKaryawan = $("#insert_id_jenis_karyawan").val();
            var kdPendidikan = $("#insert_kd_pendidikan").val();
            var nominal = $("#insert_nominal_tunjangan").val();

            if (!idJenisKaryawan || !kdPendidikan || !nominal) {
                $.alert({
                    title: 'Peringatan',
                    type: 'orange',
                    content: 'Harap lengkapi semua bidang.',
                    backgroundDismissAnimation: 'glow'
                });
                return;
            }

            $.ajax({
                url: '/keu/penggajian/pengaturan-gaji/gaji-pokok/insert',
                method: 'POST',
                data: {
                    _token: $('input[name="_token"]').val(),
                    id_jenis_karyawan: idJenisKaryawan,
                    kd_pendidikan: kdPendidikan,
                    nominal_tunjangan: nominal
                },
                beforeSend: function () {
                    $("#btn-simpan-tambah").prop('disabled', true);
                },
                success: function (response) {
                    if (response.status === 1) {
                        $.alert({
                            title: 'Berhasil',
                            type: 'green',
                            content: response.keterangan,
                            backgroundDismissAnimation: 'glow'
                        });
                        $("#modal-tambah-data").modal("hide");
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
                    $("#btn-simpan-tambah").prop('disabled', false);
                }
            });
        });

        $("#table").on('click', '.js-edit-nominal', function () {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            var nominal = $(this).data('nominal');
            var status = $(this).data('status');

            $("#update_id_config_tunjangan_pendidikan").val(id);
            $("#update_sts_aktif").val(status);
            $("#nama_jenis_karyawan").text(nama);

            var formattedNominal = nominal ? parseInt(nominal, 10).toLocaleString("id-ID") : "0";
            $("#update_nominal_tunjangan").val(formattedNominal);

            $("#modal-update-nominal").modal("show");
        });

        $("#btn-simpan-nominal").click(function () {
            var id = $("#update_id_config_tunjangan_pendidikan").val();
            var statusVal = $("#update_sts_aktif").val();
            var nominal = $("#update_nominal_tunjangan").val();

            if (!nominal) {
                $.alert({
                    title: 'Peringatan',
                    type: 'orange',
                    content: 'Harap isi nominal tunjangan.',
                    backgroundDismissAnimation: 'glow'
                });
                return;
            }

            $.ajax({
                url: '/keu/penggajian/pengaturan-gaji/gaji-pokok/update',
                method: 'POST',
                data: {
                    _token: $('input[name="_token"]').val(),
                    id_config_tunjangan_pendidikan: id,
                    nominal_tunjangan: nominal,
                    sts_aktif: statusVal
                },
                beforeSend: function () {
                    $("#btn-simpan-nominal").prop('disabled', true);
                },
                success: function (response) {
                    if (response.status === 1) {
                        $.alert({
                            title: 'Berhasil',
                            type: 'green',
                            content: response.keterangan,
                            backgroundDismissAnimation: 'glow'
                        });
                        $("#modal-update-nominal").modal("hide");
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
                error: function () {
                    $.alert({
                        title: 'Gagal',
                        type: 'red',
                        content: 'Terjadi kesalahan pada server.',
                        backgroundDismissAnimation: 'glow'
                    });
                },
                complete: function () {
                    $("#btn-simpan-nominal").prop('disabled', false);
                }
            });
        });

        $("#table").on('click', '.js-toggle-status', function () {
            var id = $(this).data('id');
            var nominal = $(this).data('nominal');
            var status = $(this).data('status');
            var isAktif = (status === true || status === 'true' || status === 1 || status === '1');
            var nextStatus = !isAktif;
            var actionText = isAktif ? 'menonaktifkan' : 'mengaktifkan';

            $.confirm({
                title: 'Konfirmasi !',
                type: isAktif ? 'red' : 'green',
                content: 'Apakah Anda yakin ingin <b>' + actionText + '</b> data ini?',
                buttons: {
                    confirm: {
                        text: 'Ya, ' + (isAktif ? 'Nonaktifkan' : 'Aktifkan'),
                        btnClass: isAktif ? 'btn-red' : 'btn-green',
                        action: function () {
                            $.ajax({
                                url: '/keu/penggajian/pengaturan-gaji/gaji-pokok/set-status',
                                method: 'POST',
                                data: {
                                    _token: $('input[name="_token"]').val(),
                                    id_config_tunjangan_pendidikan: id,
                                    nominal_tunjangan: nominal,
                                    sts_aktif: nextStatus
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
                                error: function () {
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
    }
};

jQuery(document).ready(function () {
    jQuery.gaji_pokok.init();
});
