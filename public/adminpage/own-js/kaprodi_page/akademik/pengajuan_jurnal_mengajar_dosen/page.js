jQuery.modul = {
    data: {
        filterPengajuan: null,
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;

        $('#filter-status').select2({
            placeholder: '-- Pilih Status --',
            allowClear: true,
            language: {
                noResults: function () {
                    return "Status yang anda cari tidak ditemukan.";
                }
            }
        });

        $('#filter-pengajuan').select2({
            placeholder: '-- Pilih Status Pengajuan --',
            allowClear: true,
            language: {
                noResults: function () {
                    return "Status pengajuan yang anda cari tidak ditemukan.";
                }
            }
        });

        var table = $("#table-pengajuan-jurnal-mengajar-dosen").DataTable({
            serverSide: true,
            ajax: {
                url: '/kaprodi/pengajuan-jurnal-mengajar-dosen/json',
                type: 'post',
                data: function (data) {
                    data.status = self.data.filterPengajuan;
                }
            },
            scrollY: '300px',
            scrollCollapse: true,
            columns: [
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    className: 'text-center align-middle',
                    render: function (data, type, row, meta) {
                        return meta.settings._iDisplayStart + meta.row + 1;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left align-middle',
                    render: function (data) {
                        var matkul = data.nama_matakuliah
                            ? data.nama_matakuliah
                            : '<em class="text-danger">Mata kuliah tidak ditemukan</em>';

                        var kelas = data.nama_kelas
                            ? data.nama_kelas
                            : '<em class="text-danger">Kelas tidak ditemukan</em>';

                        var prodi = data.nama_prodi
                            ? data.nama_prodi
                            : '<em class="text-danger">Program studi tidak ditemukan</em>';

                        return `
                            <div>
                                <strong>${matkul} (${kelas})</strong>
                                <hr class="my-1">
                                <small class="text-muted">${prodi}</small>
                            </div>
                        `;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left align-middle',
                    render: function (data) {
                        return "<b>" + data.nama_dosen + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left align-middle',
                    render: function (data) {
                        return `
                            <p class="mb-0">
                                ${data.catatan || '<span class="font-italic text-muted">Tidak ada catatan pengajuan</span>'}
                            </p>
                        `;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center align-middle',
                    render: function (data) {
                        var tglPengajuan = "-";

                        if (data.tgl_created) {
                            tglPengajuan = new Intl.DateTimeFormat('id-ID', {
                                day: '2-digit',
                                month: 'long',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            }).format(new Date(data.tgl_created));
                        }

                        return `
                            <p class="mb-0">
                                ${tglPengajuan}
                            </p>
                        `;
                    }
                },
                {
                    data: null,
                    searchable: true,
                    sClass: 'text-center align-middle',
                    render: function (data) {
                        return "<p class='mb-0'>" + data.ket_sts_pengajuan + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: true,
                    sClass: 'text-center align-middle',
                    render: function (data) {
                        var idJurnal = data.id_jurnal || data.id_jurnal_mengajar_dosen || data.id_pengajuan_jurnal_mengajar_dosen || '';
                        var sts = parseInt(data.sts_pengajuan || 0);

                        if (sts === 1) {
                            return `
                                <div class="text-center">
                                    <span class="badge badge-secondary px-3 py-2">
                                        <i class="fa fa-pencil-alt mr-1"></i> Belum diajukan
                                    </span>
                                </div>
                            `;
                        }

                        if (sts === 3) {
                            return `
                                <div class="text-center">
                                    <span class="badge badge-success px-3 py-2">
                                        <i class="fa fa-check-circle mr-1"></i> Disetujui
                                    </span>
                                </div>
                            `;
                        }

                        if (sts === 4 || sts === 5) {
                            return `
                                <div class="text-center">
                                    <span class="badge badge-danger px-3 py-2">
                                        <i class="fa fa-times-circle mr-1"></i> Ditolak / Revisi
                                    </span>
                                </div>
                            `;
                        }

                        return `
                            <div class="d-flex flex-column">
                                <button class="btn btn-success btn-sm mb-1 btn-approve" data-id="${idJurnal}">
                                    <i class="fa fa-check mr-2"></i> Setujui
                                </button>

                                <button class="btn btn-danger btn-sm btn-reject" data-id="${idJurnal}">
                                    <i class="fa fa-times mr-2"></i> Tolak
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

        $("#filter-status").change(function () {
            self.data.filterPengajuan = $(this).val();
            table.ajax.reload();
        });

        $("#btn-cari-data").click(function () {
            var searchValue = $("#cari-data").val();
            table.search(searchValue).draw();
        });

        $("#cari-data").keyup(function () {
            if (this.value === "") {
                table.search(this.value).draw();
            }
        }).keypress(function (event) {
            if (event.keyCode === 13) {
                table.search(this.value).draw();
            }
        });

        $('#table-pengajuan-jurnal-mengajar-dosen').on('click', 'button.btn-approve', function () {
            var id = $(this).data("id");
            var status = 3; // 3 = Disetujui / ACC

            $.confirm({
                title: '<i class="fa fa-check-circle mr-2 text-success"></i>Konfirmasi Persetujuan',
                content: 'Apakah Anda yakin ingin menyetujui pengajuan jurnal mengajar ini?',
                type: 'green',
                theme: 'modern',
                columnClass: 'col-md-5 col-md-offset-4 col-sm-8 col-xs-10',
                buttons: {
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-secondary'
                    },
                    confirm: {
                        text: '<i class="fa fa-check mr-2"></i>Ya, Setujui',
                        btnClass: 'btn-success',
                        action: function () {
                            var jc = this;
                            $.ajax({
                                url: '/kaprodi/pengajuan-jurnal-mengajar-dosen/set-status',
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: { id_jurnal: id, status: status },
                                success: function (response) {
                                    jc.close();
                                    if (response.status === true || response.status == 1) {
                                        $.alert({
                                            title: 'Berhasil',
                                            type: 'green',
                                            theme: 'modern',
                                            columnClass: 'col-md-5 col-md-offset-4 col-sm-8 col-xs-10',
                                            content: response.keterangan || 'Pengajuan jurnal berhasil disetujui.'
                                        });
                                        table.ajax.reload();
                                    } else {
                                        $.alert({
                                            title: 'Gagal',
                                            type: 'red',
                                            theme: 'modern',
                                            columnClass: 'col-md-5 col-md-offset-4 col-sm-8 col-xs-10',
                                            content: response.keterangan || 'Terjadi kesalahan.'
                                        });
                                    }
                                },
                                error: function (xhr) {
                                    jc.close();
                                    $.alert({
                                        title: 'Error',
                                        type: 'red',
                                        theme: 'modern',
                                        columnClass: 'col-md-5 col-md-offset-4 col-sm-8 col-xs-10',
                                        content: xhr.responseJSON?.message || 'Terjadi kesalahan sistem.'
                                    });
                                }
                            });
                            return false;
                        }
                    }
                }
            });
        });

        $('#table-pengajuan-jurnal-mengajar-dosen').on('click', 'button.btn-reject', function () {
            var id = $(this).data("id");
            var status = 4; // 4 = Revisi / Ditolak

            $.confirm({
                title: '<i class="fa fa-times-circle mr-2 text-danger"></i>Konfirmasi Penolakan / Revisi',
                content: '' +
                    '<form>' +
                    '<div class="form-group">' +
                    '<label class="form-label font-weight-bold">Catatan Penolakan / Revisi <span class="text-danger">*</span></label>' +
                    '<textarea class="form-control catatan" rows="3" placeholder="Masukkan alasan penolakan atau catatan revisi..." required></textarea>' +
                    '</div>' +
                    '</form>',
                type: 'red',
                theme: 'modern',
                columnClass: 'col-md-6 col-md-offset-3 col-sm-8 col-xs-10',
                buttons: {
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-secondary'
                    },
                    confirm: {
                        text: '<i class="fa fa-times mr-2"></i>Tolak / Minta Revisi',
                        btnClass: 'btn-danger',
                        action: function () {
                            var jc = this;
                            var catatan = this.$content.find('.catatan').val();

                            if (!catatan || !catatan.trim()) {
                                $.alert({
                                    title: 'Peringatan',
                                    type: 'orange',
                                    theme: 'modern',
                                    columnClass: 'col-md-5 col-md-offset-4 col-sm-8 col-xs-10',
                                    content: 'Catatan penolakan wajib diisi!'
                                });
                                return false;
                            }

                            $.ajax({
                                url: '/kaprodi/pengajuan-jurnal-mengajar-dosen/set-status',
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    id_jurnal: id,
                                    status: status,
                                    catatan: catatan
                                },
                                success: function (response) {
                                    jc.close();
                                    if (response.status === true || response.status == 1) {
                                        $.alert({
                                            title: 'Berhasil',
                                            type: 'green',
                                            theme: 'modern',
                                            columnClass: 'col-md-5 col-md-offset-4 col-sm-8 col-xs-10',
                                            content: response.keterangan || 'Pengajuan jurnal berhasil ditolak / diminta revisi.'
                                        });
                                        table.ajax.reload();
                                    } else {
                                        $.alert({
                                            title: 'Gagal',
                                            type: 'red',
                                            theme: 'modern',
                                            columnClass: 'col-md-5 col-md-offset-4 col-sm-8 col-xs-10',
                                            content: response.keterangan || 'Terjadi kesalahan.'
                                        });
                                    }
                                },
                                error: function (xhr) {
                                    jc.close();
                                    $.alert({
                                        title: 'Error',
                                        type: 'red',
                                        theme: 'modern',
                                        columnClass: 'col-md-5 col-md-offset-4 col-sm-8 col-xs-10',
                                        content: xhr.responseJSON?.message || 'Terjadi kesalahan sistem.'
                                    });
                                }
                            });
                            return false;
                        }
                    }
                }
            });
        });

        $('#table-pengajuan-jurnal-mengajar-dosen').on('click', 'button.btn-download-lhs', function () {
            var id = $(this).data("id");
            var tahun = $(this).data("tahun");
            var id_tandatangan = $(this).data("id_tandatangan");
            var nama_wadek = $(this).data("nama_wadek");
            var nidn_wadek = $(this).data("nidn_wadek");
            var $button = $(this);
            var originalHtml = $button.html();

            $button.prop('disabled', true)
                .html('<i class="fa fa-spinner fa-spin mr-2"></i> Mengunduh...');

            var form = $('<form>', {
                method: 'POST',
                action: '/mhs/khs/download'
            });

            form.append($('<input>', {
                type: 'hidden',
                name: '_token',
                value: $('meta[name="csrf-token"]').attr('content')
            }));

            form.append($('<input>', {
                type: 'hidden',
                name: 'id_riwayat_pengajuan_khs',
                value: id
            }));

            form.append($('<input>', {
                type: 'hidden',
                name: 'tahun_akademik',
                value: tahun
            }));

            form.append($('<input>', {
                type: 'hidden',
                name: 'id_tandatangan',
                value: id_tandatangan
            }));

            form.append($('<input>', {
                type: 'hidden',
                name: 'nama_wakil_dekan',
                value: nama_wadek
            }));

            form.append($('<input>', {
                type: 'hidden',
                name: 'nidn_wakil_dekan',
                value: nidn_wadek
            }));

            $('body').append(form);
            form.trigger('submit');
            form.remove();

            setTimeout(function () {
                $button.prop('disabled', false).html(originalHtml);
            }, 2000);
        });
    },
};

jQuery(document).ready(function () {
    jQuery.modul.init();
});
