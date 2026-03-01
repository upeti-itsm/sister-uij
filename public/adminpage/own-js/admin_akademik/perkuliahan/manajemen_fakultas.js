$(document).ready(function () {
    // Setup AJAX with CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.select2').select2({
        theme: 'bootstrap4'
    });

    var table = $('#table').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        language: {
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            search: "Cari:",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            },
            processing: "Memproses data...",
            emptyTable: "Tidak ada data di tabel",
            loadingRecords: "Memuat data..."
        },
        ajax: {
            url: '/adm-akademik/perkuliahan/manajemen-fakultas/json',
            type: 'POST',
            data: function (d) {
                d.sts_aktif = $('#filter_status').val() || null;
                d.param_search = $('#filter_search').val() || '';
            },
            dataSrc: function (json) {
                console.log('Response data:', json);
                return json.data || [];
            }
        },
        columns: [
            {
                data: null,
                sortable: false,
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'kd_fakultas',
                defaultContent: '-',
                render: function (data, type, row) {
                    return data ? data.toString().trim() : '-';
                }
            },
            {
                data: 'nama_fakultas',
                defaultContent: '-'
            },
            {
                data: 'dekan',
                defaultContent: '-'
            },
            {
                data: 'kd_nim_fak',
                defaultContent: '-',
                render: function (data, type, row) {
                    return data ? data.toString().trim() : '-';
                }
            },
            {
                data: 'is_data_aktif',
                className: 'text-center',
                render: function (data, type, row) {
                    return data || data === 't' || data === 'true' || data === true
                        ? '<span class="badge badge-success">Aktif</span>'
                        : '<span class="badge badge-secondary">Nonaktif</span>';
                }
            },
            {
                data: null,
                orderable: false,
                className: 'text-center text-nowrap mx-2',
                render: function (data, type, row) {
                    var id = row.kd_fakultas;
                    var isAktif = row.is_data_aktif === true || row.is_data_aktif === 't' || row.is_data_aktif === 'true';

                    var statusBtn = isAktif
                        ? `<button class="btn btn-sm btn-success btn-toggle-status" data-id="${id}" data-status="false" title="Nonaktifkan"><i class="fas fa-toggle-on"></i></button>`
                        : `<button class="btn btn-sm btn-secondary btn-toggle-status" data-id="${id}" data-status="true" title="Aktifkan"><i class="fas fa-toggle-off"></i></button>`;

                    return `
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-primary btn-edit mr-1" data-id="${id}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            ${statusBtn}
                        </div>
                    `;
                }
            }
        ]
    });

    // Tambah data
    $('#btn-tambah-data').on('click', function () {
        $('#form-collapse').collapse('show');
        $('#kd_fakultas_old').val('');
        $('#kd_fakultas').val('').prop('readonly', false);
        $('#nama_fakultas').val('');
        $('#dekan').val('');
        $('#kd_nim_fak').val('');
    });

    // Cancel
    $('#btn-cancel').on('click', function () {
        $('#form-collapse').collapse('hide');
    });

    // Save
    $('#btn-save').on('click', function () {
        var kd_fakultas_old = $('#kd_fakultas_old').val();
        var kd_fakultas = $('#kd_fakultas').val().trim();
        var nama_fakultas = $('#nama_fakultas').val();
        var dekan = $('#dekan').val();
        var kd_nim_fak = $('#kd_nim_fak').val();

        if (!kd_fakultas || !nama_fakultas || !kd_nim_fak) {
            $.alert({
                title: 'Peringatan',
                content: 'Field bertanda * wajib diisi!',
                type: 'red'
            });
            return;
        }

        var url = kd_fakultas_old ? '/adm-akademik/perkuliahan/manajemen-fakultas/update' : '/adm-akademik/perkuliahan/manajemen-fakultas/store';
        var data = {
            kd_fakultas: kd_fakultas,
            nama_fakultas: nama_fakultas,
            dekan: dekan,
            kd_nim_fak: kd_nim_fak
        };

        if (kd_fakultas_old) {
            data.kd_fakultas_old = kd_fakultas_old;
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
        var row = table.rows().data().toArray().find(r => {
            return r.kd_fakultas == id;
        });

        if (row) {
            $('#kd_fakultas_old').val(row.kd_fakultas ? row.kd_fakultas.toString().trim() : '');
            $('#kd_fakultas').val(row.kd_fakultas ? row.kd_fakultas.toString().trim() : '').prop('readonly', true);
            $('#nama_fakultas').val(row.nama_fakultas || '');
            $('#dekan').val(row.dekan || '');
            $('#kd_nim_fak').val(row.kd_nim_fak ? row.kd_nim_fak.toString().trim() : '');

            $('#form-collapse').collapse('show');
        } else {
            $.alert({
                title: 'Error',
                content: 'Data tidak ditemukan',
                type: 'red'
            });
        }
    });

    // Toggle Status
    $('#table').on('click', '.btn-toggle-status', function () {
        var id = $(this).data('id').toString().trim();
        var status = $(this).data('status');
        var statusText = status === 'true' || status === true ? 'mengaktifkan' : 'menonaktifkan';

        console.log('Toggle status for:', id, 'to:', status);

        $.confirm({
            title: 'Konfirmasi',
            content: 'Apakah Anda yakin ingin ' + statusText + ' fakultas ini?',
            type: (status === 'true' || status === true) ? 'green' : 'orange',
            buttons: {
                ya: {
                    text: 'Ya',
                    btnClass: (status === 'true' || status === true) ? 'btn-green' : 'btn-orange',
                    action: function () {
                        $.ajax({
                            url: '/adm-akademik/perkuliahan/manajemen-fakultas/toggle-status',
                            type: 'POST',
                            data: {
                                kd_fakultas: id,
                                status: status
                            },
                            success: function (response) {
                                console.log('Toggle response:', response);

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
                                    // Reload dengan memaksa refresh data
                                    table.ajax.reload(null, false);
                                } else {
                                    $.alert({
                                        title: 'Gagal',
                                        content: response.message || 'Gagal mengubah status',
                                        type: 'red'
                                    });
                                }
                            },
                            error: function (xhr) {
                                console.error('Toggle error:', xhr);
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
