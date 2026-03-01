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

    // Load fakultas data
    loadFakultas();

    // Load jenjang data
    loadJenjang();

    // Custom search handler
    $('#btn-search').on('click', function () {
        table.ajax.reload();
    });

    // Enter key pada search input
    $('#search_input').on('keypress', function (e) {
        if (e.which === 13) {
            table.ajax.reload();
        }
    });

    // Custom filter handler
    $('#btn-filter').on('click', function () {
        table.ajax.reload();
    });

    var table = $('#table').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        searching: false,  // Disable default search
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
            url: '/adm-akademik/perkuliahan/manajemen-prodi/json',
            type: 'POST',
            data: function (d) {
                var filterStatus = $('#filter_status').val();
                var filterFakultas = $('#filter_fakultas').val();
                var searchInput = $('#search_input').val();

                // Clean up values
                if (filterStatus === '' || filterStatus === null) {
                    filterStatus = null;
                }

                if (filterFakultas === '' || filterFakultas === null) {
                    filterFakultas = null;
                } else {
                    filterFakultas = String(filterFakultas).trim();
                }

                if (searchInput === '' || searchInput === null) {
                    searchInput = '';
                } else {
                    searchInput = String(searchInput).trim();
                }

                d.sts_aktif = filterStatus;
                d.kd_fakultas = filterFakultas;
                d.param_search = searchInput;
                d.no_page = -1;
                d.jml_record_perpage = 999999; // Ambil semua data untuk client-side pagination

                console.log('========== SENDING FILTER PARAMS ==========');
                console.log('Status Filter:', filterStatus);
                console.log('Fakultas Filter:', filterFakultas);
                console.log('Search Input:', searchInput);
                console.log('==========================================');
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
                data: 'kd_program_studi',
                defaultContent: '-'
            },
            {
                data: 'nama_program_studi',
                defaultContent: '-'
            },
            {
                data: 'nama_jenjang_didik',
                defaultContent: '-'
            },
            {
                data: 'kd_dikti',
                defaultContent: '-'
            },
            {
                data: 'kd_fakultas',
                defaultContent: '-'
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
                    var id = row.id_program_studi || row.kd_program_studi;
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

    // Load fakultas for select2 and filter
    function loadFakultas() {
        var fakultasSelect = $('#kd_fakultas');
        var filterFakultasSelect = $('#filter_fakultas');
        fakultasSelect.empty();
        filterFakultasSelect.empty();
        fakultasSelect.append('<option value="">-- Pilih Fakultas --</option>');
        filterFakultasSelect.append('<option value="" selected>-- Semua Fakultas --</option>');

        $.ajax({
            url: '/adm-akademik/perkuliahan/manajemen-prodi/json-fakultas',
            type: 'POST',
            data: {
                sts_aktif: 1, // 1 = Aktif only (integer, bukan boolean)
                jml_record_perpage: 100
            },
            success: function (response) {
                console.log('========== FAKULTAS DATA LOADED ==========');
                console.log('Response:', response);
                if (response.status === 'success' && response.data && response.data.length > 0) {
                    response.data.forEach(function (item) {
                        console.log('Adding fakultas:', item.kd_fakultas, '-', item.nama_fakultas);
                        fakultasSelect.append(`<option value="${item.kd_fakultas}">${item.nama_fakultas}</option>`);
                        filterFakultasSelect.append(`<option value="${item.kd_fakultas}">${item.nama_fakultas}</option>`);
                    });
                    console.log('Total fakultas loaded:', response.data.length);
                } else {
                    console.warn('No fakultas data found');
                }
                console.log('==========================================');
            },
            error: function (xhr, status, error) {
                console.error('Failed to load fakultas data:', error);
                $.alert({
                    title: 'Error',
                    content: 'Gagal memuat data fakultas',
                    type: 'red'
                });
            }
        });
    }

    // Load jenjang for select2
    function loadJenjang() {
        var jenjangSelect = $('#jenjang');
        jenjangSelect.empty();
        jenjangSelect.append('<option value="">-- Pilih Jenjang --</option>');

        $.ajax({
            url: '/adm-akademik/perkuliahan/manajemen-prodi/json-jenjang',
            type: 'POST',
            data: {
                jml_record_perpage: 100
            },
            success: function (response) {
                console.log('Jenjang response:', response);
                if (response.status === 'success' && response.data && response.data.length > 0) {
                    response.data.forEach(function (item) {
                        jenjangSelect.append(`<option value="${item.kd_jenjang_didik}">${item.jenjang_didik}</option>`);
                    });
                } else {
                    console.warn('No jenjang data found');
                }
            },
            error: function (xhr, status, error) {
                console.error('Failed to load jenjang data:', error);
                $.alert({
                    title: 'Error',
                    content: 'Gagal memuat data jenjang',
                    type: 'red'
                });
            }
        });
    }

    // Tambah data
    $('#btn-tambah-data').on('click', function () {
        $('#form-collapse').collapse('show');
        $('#id_prodi').val('');
        $('#kd_prodi').val('');
        $('#nm_prodi').val('');
        $('#kd_dikti').val('');
        $('#kd_nim').val('');
        $('#kaprodi_id').val('');
        $('#no_urut_wisuda').val('');
        $('#jenjang').val('').trigger('change');
        $('#kd_fakultas').val('').trigger('change');
        $('#sts_aktif').val('1');
        $('#sts_kip').prop('checked', true);
        $('#is_s2').prop('checked', false);
        // SEMBUNYIKAN dropdown status untuk INSERT (otomatis aktif)
        $('#div-status-aktif').hide();
    });

    // Cancel
    $('#btn-cancel').on('click', function () {
        $('#form-collapse').collapse('hide');
    });

    // Save
    $('#btn-save').on('click', function () {
        var id = $('#id_prodi').val();
        var kd_prodi = $('#kd_prodi').val();
        var nm_prodi = $('#nm_prodi').val();
        var kd_dikti = $('#kd_dikti').val();
        var jenjang = $('#jenjang').val();
        var kd_fakultas = $('#kd_fakultas').val();
        var kd_nim = $('#kd_nim').val();
        var kaprodi_id = $('#kaprodi_id').val();
        var no_urut_wisuda = $('#no_urut_wisuda').val();
        var sts_aktif = $('#sts_aktif').val();
        var sts_kip = $('#sts_kip').is(':checked') ? 1 : 0;
        var is_s2 = $('#is_s2').is(':checked') ? 1 : 0;

        if (!kd_prodi || !nm_prodi || !kd_dikti || !jenjang || !kd_fakultas || !kd_nim) {
            $.alert({
                title: 'Peringatan',
                content: 'Field bertanda * wajib diisi!',
                type: 'red'
            });
            return;
        }

        var url = id ? '/adm-akademik/perkuliahan/manajemen-prodi/update' : '/adm-akademik/perkuliahan/manajemen-prodi/store';
        var data = {
            kd_prodi: kd_prodi,
            nm_prodi: nm_prodi,
            kd_dikti: kd_dikti,
            jenjang: jenjang,
            kd_fakultas: kd_fakultas,
            kd_nim: kd_nim,
            kaprodi_id: kaprodi_id,
            no_urut_wisuda: no_urut_wisuda,
            sts_aktif: sts_aktif,
            sts_kip: sts_kip,
            is_s2: is_s2
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
        var row = table.rows().data().toArray().find(r => {
            return r.id_program_studi == id || r.kd_program_studi == id;
        });

        if (row) {
            console.log('Full row data:', row); // Debug full data

            $('#id_prodi').val(row.id_program_studi || '');
            $('#kd_prodi').val(row.kd_program_studi || '');
            $('#nm_prodi').val(row.nama_program_studi || '');
            $('#kd_dikti').val(row.kd_dikti || '');
            $('#kd_nim').val(row.kd_nim ? row.kd_nim.trim() : '');
            $('#kaprodi_id').val(row.karyawan_id_kaprodi || '');
            $('#no_urut_wisuda').val(row.no_urut_prodi_wisuda || '');
            $('#sts_kip').prop('checked', row.sts_kip === true || row.sts_kip === 't' || row.sts_kip === 'true');
            $('#is_s2').prop('checked', row.is_s2 === true || row.is_s2 === 't' || row.is_s2 === 'true');

            // Mapping kode jenjang - cek semua kemungkinan field
            var jenjang = '';
            if (row.kd_jenjang_didik) {
                jenjang = row.kd_jenjang_didik.toString().trim();
            }

            console.log('Setting jenjang:', jenjang);
            $('#jenjang').val(jenjang).trigger('change');

            $('#kd_fakultas').val(row.kd_fakultas || '').trigger('change');

            var isAktif = row.is_data_aktif === true || row.is_data_aktif === 't' || row.is_data_aktif === 'true';
            $('#sts_aktif').val(isAktif ? '1' : '0');

            // TAMPILKAN dropdown status untuk EDIT
            $('#div-status-aktif').show();

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
        var id = $(this).data('id');
        var status = $(this).data('status');
        var statusText = status ? 'mengaktifkan' : 'menonaktifkan';

        $.confirm({
            title: 'Konfirmasi',
            content: 'Apakah Anda yakin ingin ' + statusText + ' program studi ini?',
            type: status ? 'green' : 'orange',
            buttons: {
                ya: {
                    text: 'Ya',
                    btnClass: status ? 'btn-green' : 'btn-orange',
                    action: function () {
                        $.ajax({
                            url: '/adm-akademik/perkuliahan/manajemen-prodi/toggle-status',
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
