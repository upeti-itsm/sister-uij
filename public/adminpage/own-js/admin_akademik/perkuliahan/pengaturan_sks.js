$(document).ready(function () {
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    var table = $('#table').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '/adm-akademik/perkuliahan/pengaturan-sks/json',
            type: 'POST',
            dataSrc: 'data'
        },
        columns: [
            {
                data: null,
                sortable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'ips_min' },
            { data: 'ips_max' },
            { data: 'sks' },
            {
                data: 'sts_aktif',
                render: function (data, type, row) {
                    return data ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-secondary">Nonaktif</span>';
                }
            },
            {
                data: null,
                orderable: false,
                render: function (data, type, row) {
                    // Kalau aktif (true) = tombol hijau dengan icon toggle-on
                    // Kalau nonaktif (false) = tombol abu-abu dengan icon toggle-off
                    var statusBtn = row.sts_aktif
                        ? `<button class="btn btn-sm btn-success btn-toggle-status" data-id="${row.id_aturan_sks}" data-status="false" title="Nonaktifkan"><i class="fas fa-toggle-on"></i></button>`
                        : `<button class="btn btn-sm btn-secondary btn-toggle-status" data-id="${row.id_aturan_sks}" data-status="true" title="Aktifkan"><i class="fas fa-toggle-off"></i></button>`;

                    return `
                        <button class="btn btn-sm btn-primary btn-edit" data-id="${row.id_aturan_sks}">
                            <i class="fas fa-edit"></i>
                        </button>
                        ${statusBtn}
                    `;
                }
            }
        ]
    });

    // Tambah data
    $('#btn-tambah-data').on('click', function () {
        $('#form-collapse').collapse('show');
        $('#id_aturan').val('');
        $('#ips_min').val('');
        $('#ips_max').val('');
        $('#sks').val('');
    });

    // Cancel
    $('#btn-cancel').on('click', function () {
        $('#form-collapse').collapse('hide');
    });

    // Save
    $('#btn-save').on('click', function () {
        var id = $('#id_aturan').val();
        var ips_min = $('#ips_min').val();
        var ips_max = $('#ips_max').val();
        var sks = $('#sks').val();

        if (!ips_min || !ips_max || !sks) {
            $.alert({
                title: 'Peringatan',
                content: 'Semua field harus diisi!',
                type: 'red'
            });
            return;
        }

        if (parseFloat(ips_min) >= parseFloat(ips_max)) {
            $.alert({
                title: 'Peringatan',
                content: 'IPS Min harus lebih kecil dari IPS Max!',
                type: 'red'
            });
            return;
        }

        var url = id ? '/adm-akademik/perkuliahan/pengaturan-sks/update' : '/adm-akademik/perkuliahan/pengaturan-sks/store';
        var data = {
            ips_min: ips_min,
            ips_max: ips_max,
            sks: sks
        };

        if (id) {
            data.id = id;
        }

        $('#loading-tambah-data').show();

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function (response) {
                $('#loading-tambah-data').hide();

                // Cek apakah success
                var isSuccess = false;

                if (response.status === 'success' || response.status === 200) {
                    isSuccess = true;
                } else if (response.message) {
                    var msg = String(response.message).toLowerCase();
                    if (msg.includes('berhasil')) {
                        isSuccess = true;
                    }
                }

                if (isSuccess) {
                    $.alert({
                        title: 'Berhasil',
                        content: response.message || 'Data berhasil disimpan',
                        type: 'green'
                    });
                    $('#form-collapse').collapse('hide');
                    table.ajax.reload();
                } else {
                    $.alert({
                        title: 'Gagal',
                        content: response.message || 'Terjadi kesalahan',
                        type: 'red'
                    });
                }
            },
            error: function (xhr) {
                $('#loading-tambah-data').hide();
                var message = 'Terjadi kesalahan!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                $.alert({
                    title: 'Error',
                    content: message,
                    type: 'red'
                });
            }
        });
    });

    // Edit
    $('#table').on('click', '.btn-edit', function () {
        var id = $(this).data('id');
        var row = table.rows().data().toArray().find(r => r.id_aturan_sks == id);

        if (row) {
            $('#id_aturan').val(row.id_aturan_sks);
            $('#ips_min').val(row.ips_min);
            $('#ips_max').val(row.ips_max);
            $('#sks').val(row.sks);
            $('#form-collapse').collapse('show');
        }
    });

    // Toggle Status
    $('#table').on('click', '.btn-toggle-status', function () {
        var id = $(this).data('id');
        var status = $(this).data('status');
        var statusText = status ? 'mengaktifkan' : 'menonaktifkan';

        $.confirm({
            title: 'Konfirmasi',
            content: 'Apakah Anda yakin ingin ' + statusText + ' aturan ini?',
            type: status ? 'green' : 'orange',
            buttons: {
                ya: {
                    text: 'Ya',
                    btnClass: status ? 'btn-green' : 'btn-orange',
                    action: function () {
                        $.ajax({
                            url: '/adm-akademik/perkuliahan/pengaturan-sks/delete',
                            type: 'POST',
                            data: {
                                id: id,
                                status: status
                            },
                            success: function (response) {
                                // Cek apakah success
                                var isSuccess = false;

                                if (response.status === 'success' || response.status === 200) {
                                    isSuccess = true;
                                } else if (response.message) {
                                    var msg = String(response.message).toLowerCase();
                                    if (msg.includes('berhasil')) {
                                        isSuccess = true;
                                    }
                                }

                                if (isSuccess) {
                                    $.alert({
                                        title: 'Berhasil',
                                        content: response.message || 'Status berhasil diubah',
                                        type: 'green'
                                    });
                                    table.ajax.reload();
                                } else {
                                    $.alert({
                                        title: 'Gagal',
                                        content: response.message || 'Gagal mengubah status',
                                        type: 'red'
                                    });
                                }
                            },
                            error: function (xhr) {
                                var message = 'Terjadi kesalahan!';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    message = xhr.responseJSON.message;
                                }
                                $.alert({
                                    title: 'Error',
                                    content: message,
                                    type: 'red'
                                });
                            }
                        });
                    }
                },
                batal: {
                    text: 'Batal'
                }
            }
        });
    });
});
