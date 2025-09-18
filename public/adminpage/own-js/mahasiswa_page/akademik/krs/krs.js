jQuery.krs_jadwal = {
    data: {
        table_jadwal: $("#table-jadwal"),
        table_krs_terpilih: $("#table-krs-terpilih"),
        selected_matkul: [],
        sks_maksimal: 24, // SKS maksimal per semester
        search: ''
    },
    init: function () {
        var self = this;
        self.setEvents();
        self.updateStatistik();
        self.loadSKSMaksimal();
    },
    setEvents: function () {
        var self = this;

        // Initialize Select2
        $(".select2").select2();

        // Initialize DataTable untuk jadwal mata kuliah
        self.data.table_jadwal = $("#table-jadwal").DataTable({
            serverSide: true,
            ajax: {
                url: '/mhs/krs/json',
                type: 'post',
                data: function (d) {
                    d.search_matkul = self.data.search;
                    return d;
                }
            },
            fnDrawCallback: function () {
                var rows = this.fnGetData();
                var total_matkul = rows.length;

                $("#tot_matkul").text(total_matkul);

                // Update checkbox state
                self.updateCheckboxState();
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    sClass: 'text-center',
                    width: "5%",
                    render: function (data, type, row) {
                        var isSelected = self.data.selected_matkul.some(item => item.id === data.id);
                        var isDisabled = (data.jumlah_peserta >= data.kapasitas) ? 'disabled' : '';
                        var checked = isSelected ? 'checked' : '';
                        return `<input type="checkbox" class="matkul-checkbox" data-id="${data.id}" ${checked} ${isDisabled}>`;
                    }
                },
                {
                    data: 'nomor',
                    searchable: false,
                    sClass: 'text-center',
                    width: "5%"
                },
                {
                    data: null,
                    searchable: true,
                    sClass: 'text-left',
                    width: "15%",
                    render: function (data) {
                        return `<strong>${data.kd_mata_kuliah}</strong><br/>
                                <small>${data.nama_mata_kuliah}</small><br/>
                                <button class="btn btn-xs btn-info mt-1" onclick="jQuery.krs_jadwal.showDetailMatkul(${data.id})">
                                    <i class="fas fa-eye"></i> Detail
                                </button>`;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "10%",
                    render: function (data) {
                        return `<span class="badge badge-primary">${data.nama_kelas}</span><br/>
                                <small>${data.jenis_kelas}</small>`;
                    }
                },
                {
                    data: 'sks',
                    searchable: false,
                    sClass: 'text-center',
                    width: "8%",
                    render: function (data) {
                        return `<span class="badge badge-success">${data} SKS</span>`;
                    }
                },
                {
                    data: 'nama_dosen',
                    searchable: true,
                    sClass: 'text-left',
                    width: "10%",
                    render: function (data) {
                        return `<small>${data || '-'}</small>`;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "8%",
                    render: function (data) {
                        var hari_names = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                        return hari_names[data.hari] || '-';
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "12%",
                    render: function (data) {
                        return `${data.jam_mulai} - ${data.jam_selesai}`;
                    }
                },
                {
                    data: 'ruang',
                    searchable: false,
                    sClass: 'text-center',
                    width: "10%"
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "8%",
                    render: function (data) {
                        var sisa = data.kapasitas - data.jumlah_peserta;
                        var color = sisa > 0 ? 'success' : 'danger';
                        return `<span class="badge badge-${color}">${data.jumlah_peserta}/${data.kapasitas}</span>`;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "9%",
                    render: function (data) {
                        var sisa = data.kapasitas - data.jumlah_peserta;
                        if (sisa > 0) {
                            return `<span class="badge badge-success">Tersedia</span>`;
                        } else {
                            return `<span class="badge badge-danger">Penuh</span>`;
                        }
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
                "emptyTable": "Tidak ditemukan data jadwal mata kuliah"
            }
        });

        // Initialize DataTable untuk KRS terpilih
        self.data.table_krs_terpilih = $("#table-krs-terpilih").DataTable({
            data: self.data.selected_matkul,
            columns: [
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "5%",
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "20%",
                    render: function (data) {
                        return `<strong>${data.kd_mata_kuliah}</strong><br/>
                                <small>${data.nama_mata_kuliah}</small>`;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "10%",
                    render: function (data) {
                        return `<span class="badge badge-primary">${data.nama_kelas}</span>`;
                    }
                },
                {
                    data: 'sks',
                    searchable: false,
                    sClass: 'text-center',
                    width: "8%",
                    render: function (data) {
                        return `<span class="badge badge-success">${data} SKS</span>`;
                    }
                },
                {
                    data: 'nama_dosen',
                    searchable: false,
                    sClass: 'text-left',
                    width: "15%",
                    render: function (data) {
                        return `<small>${data || '-'}</small>`;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "8%",
                    render: function (data) {
                        var hari_names = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                        return hari_names[data.hari] || '-';
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "12%",
                    render: function (data) {
                        return `${data.jam_mulai} - ${data.jam_selesai}`;
                    }
                },
                {
                    data: 'ruang',
                    searchable: false,
                    sClass: 'text-center',
                    width: "10%"
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "7%",
                    render: function (data) {
                        return `<button class="btn btn-sm btn-danger btn-hapus-matkul" data-id="${data.id}">
                                    <i class="fas fa-trash"></i>
                                </button>`;
                    }
                }
            ],
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            language: {
                "emptyTable": "Belum ada mata kuliah yang dipilih"
            }
        });

        // Event handlers
        self.setEventHandlers();
    },

    setEventHandlers: function() {
        var self = this;

        // Search events
        $("#cari-matkul").keyup(function() {
            self.data.search = $(this).val();
        });

        $("#btn-cari-data").click(function() {
            self.data.table_jadwal.ajax.reload();
        });

        // Enter key search
        $("#cari-matkul").keypress(function(event) {
            if (event.keyCode === 13) {
                self.data.table_jadwal.ajax.reload();
            }
        });

        // Auto search on input change (optional - real-time search)
        $("#cari-matkul").on('input', function() {
            clearTimeout(self.searchTimeout);
            self.searchTimeout = setTimeout(function() {
                if (self.data.search !== $("#cari-matkul").val()) {
                    self.data.search = $("#cari-matkul").val();
                    self.data.table_jadwal.ajax.reload();
                }
            }, 500); // 500ms delay untuk menghindari terlalu banyak request
        });

        // Checkbox events
        $("#select-all").change(function() {
            var isChecked = $(this).is(':checked');
            $(".matkul-checkbox:not(:disabled)").prop('checked', isChecked);

            if (isChecked) {
                $(".matkul-checkbox:checked").each(function() {
                    var id = $(this).data('id');
                    self.addToKRS(id);
                });
            } else {
                self.data.selected_matkul = [];
                self.updateKRSTable();
            }
        });

        // Individual checkbox
        $(document).on('change', '.matkul-checkbox', function() {
            var id = $(this).data('id');
            if ($(this).is(':checked')) {
                self.addToKRS(id);
            } else {
                self.removeFromKRS(id);
            }
        });

        // Remove dari KRS
        $(document).on('click', '.btn-hapus-matkul', function() {
            var id = $(this).data('id');
            self.removeFromKRS(id);
            self.data.table_jadwal.ajax.reload();
        });

        // Hapus semua KRS
        $("#btn-hapus-semua").click(function() {
            $.confirm({
                title: 'Konfirmasi',
                content: 'Apakah Anda yakin ingin menghapus semua mata kuliah terpilih?',
                type: 'red',
                buttons: {
                    ya: {
                        text: 'Ya, Hapus Semua',
                        btnClass: 'btn-red',
                        action: function() {
                            self.data.selected_matkul = [];
                            self.updateKRSTable();
                            self.data.table_jadwal.ajax.reload();
                        }
                    },
                    batal: {
                        text: 'Batal',
                        btnClass: 'btn-default'
                    }
                }
            });
        });

        // Simpan KRS
        $("#btn-simpan-krs").click(function() {
            if (self.data.selected_matkul.length === 0) {
                $.alert({
                    title: 'Peringatan',
                    content: 'Anda belum memilih mata kuliah apapun!',
                    type: 'orange'
                });
                return;
            }

            var total_sks = self.getTotalSKS();

            if (total_sks > self.data.sks_maksimal) {
                $.alert({
                    title: 'Melebihi Batas SKS',
                    content: `Total SKS yang dipilih (${total_sks} SKS) melebihi batas maksimal ${self.data.sks_maksimal} SKS per semester. Silakan kurangi mata kuliah yang dipilih.`,
                    type: 'red'
                });
                return;
            }

            var pesan_sks = total_sks >= self.data.sks_maksimal * 0.9 ?
                `<br/><small class="text-warning"><strong>Perhatian:</strong> Anda mengambil ${total_sks} SKS dari maksimal ${self.data.sks_maksimal} SKS.</small>` : '';

            $("#pesan-konfirmasi").html(`
                Anda akan menyimpan KRS dengan ${self.data.selected_matkul.length} mata kuliah
                (Total ${total_sks} SKS dari maksimal ${self.data.sks_maksimal} SKS).
                Apakah Anda yakin?${pesan_sks}
            `);
            $("#modal-konfirmasi").modal('show');
        });

        // Konfirmasi simpan
        $("#btn-konfirmasi-ya").click(function() {
            self.simpanKRS();
        });
    },

    addToKRS: function(id) {
        var self = this;
        var rows = self.data.table_jadwal.rows().data().toArray();
        var matkul = rows.find(item => item.id == id);

        if (matkul && !self.data.selected_matkul.some(item => item.id == id)) {
            // Check SKS maksimal
            var current_sks = self.getTotalSKS();
            var new_total_sks = current_sks + parseInt(matkul.sks);

            if (new_total_sks > self.data.sks_maksimal) {
                $.alert({
                    title: 'Melebihi Batas SKS',
                    content: `Anda tidak dapat mengambil mata kuliah ini karena akan melebihi batas maksimal ${self.data.sks_maksimal} SKS per semester. Total SKS akan menjadi ${new_total_sks} SKS.`,
                    type: 'red'
                });
                $(`input[data-id="${id}"]`).prop('checked', false);
                return;
            }

            // Check bentrok jadwal
            if (self.checkBentrokJadwal(matkul)) {
                $.alert({
                    title: 'Bentrok Jadwal',
                    content: 'Mata kuliah ini bentrok dengan jadwal yang sudah dipilih!',
                    type: 'red'
                });
                $(`input[data-id="${id}"]`).prop('checked', false);
                return;
            }

            self.data.selected_matkul.push(matkul);
            self.updateKRSTable();
        }
    },

    removeFromKRS: function(id) {
        var self = this;
        self.data.selected_matkul = self.data.selected_matkul.filter(item => item.id != id);
        self.updateKRSTable();
        $(`input[data-id="${id}"]`).prop('checked', false);
    },

    updateKRSTable: function() {
        var self = this;
        self.data.table_krs_terpilih.clear().rows.add(self.data.selected_matkul).draw();
        self.updateStatistik();
        $("#total-sks").text(self.getTotalSKS());
    },

    updateCheckboxState: function() {
        var self = this;
        $(".matkul-checkbox").each(function() {
            var id = $(this).data('id');
            var isSelected = self.data.selected_matkul.some(item => item.id == id);
            $(this).prop('checked', isSelected);
        });
    },

    updateStatistik: function() {
        var self = this;
        var total_sks = self.getTotalSKS();
        var sisa_sks = self.data.sks_maksimal - total_sks;

        $("#tot_dipilih").text(self.data.selected_matkul.length);
        $("#tot_sks").text(total_sks);

        // Update info SKS
        $("#sks-terpilih-info").text(total_sks);
        $("#sks-sisa-info").text(sisa_sks);
        $("#sks-maks-info").text(self.data.sks_maksimal);

        // Update status dan warna card
        var sks_card = $("#tot_sks").closest('.card');
        var sks_status = $("#sks-status");

        if (total_sks > self.data.sks_maksimal) {
            sks_card.find('.card-header').removeClass('card-header-warning card-header-success').addClass('card-header-danger');
            sks_status.text('Melebihi Batas!');
        } else if (total_sks >= self.data.sks_maksimal * 0.8) {
            sks_card.find('.card-header').removeClass('card-header-warning card-header-danger').addClass('card-header-warning');
            sks_status.text('Mendekati Batas');
        } else {
            sks_card.find('.card-header').removeClass('card-header-danger card-header-warning').addClass('card-header-success');
            sks_status.text('Kredit Dipilih');
        }
    },

    getTotalSKS: function() {
        var self = this;
        return self.data.selected_matkul.reduce((total, item) => total + parseInt(item.sks), 0);
    },

    checkBentrokJadwal: function(matkul_baru) {
        var self = this;
        return self.data.selected_matkul.some(function(matkul) {
            return matkul.hari === matkul_baru.hari &&
                self.isTimeOverlap(matkul.jam_mulai, matkul.jam_selesai,
                    matkul_baru.jam_mulai, matkul_baru.jam_selesai);
        });
    },

    isTimeOverlap: function(start1, end1, start2, end2) {
        return (start1 < end2 && end1 > start2);
    },

    showDetailMatkul: function(id) {
        var self = this;
        var rows = self.data.table_jadwal.rows().data().toArray();
        var matkul = rows.find(item => item.id == id);

        if (matkul) {
            $("#detail-kode-matkul").text(matkul.kd_mata_kuliah);
            $("#detail-nama-matkul").text(matkul.nama_mata_kuliah);
            $("#detail-sks").text(matkul.sks + ' SKS');
            $("#detail-kelas").text(matkul.nama_kelas + ' (' + matkul.jenis_kelas + ')');
            $("#detail-dosen").text(matkul.nama_dosen || '-');
            $("#detail-ruang").text(matkul.ruang || '-');
            $("#detail-kapasitas").text(matkul.kapasitas);
            $("#detail-peserta").text(matkul.jumlah_peserta);
            $("#detail-keterangan").text(matkul.keterangan || '-');

            $("#modal-detail-matkul").modal('show');
        }
    },

    loadSKSMaksimal: function() {
        var self = this;
        // Load SKS maksimal dari server atau bisa di-hardcode
        $.ajax({
            url: '/mhs/krs/sks-maksimal',
            method: 'GET',
            success: function(response) {
                if (response.sks_maksimal) {
                    self.data.sks_maksimal = response.sks_maksimal;
                    $("#sks_maksimal").text(response.sks_maksimal);
                    $("#sks-maks-info").text(response.sks_maksimal);
                    self.updateStatistik();
                }
            },
            error: function() {
                // Jika gagal load dari server, gunakan default
                console.log('Menggunakan SKS maksimal default: 24');
            }
        });
    },

    simpanKRS: function() {
        var self = this;
        var data_krs = self.data.selected_matkul.map(item => ({
            id_jadwal: item.id,
            kd_mata_kuliah: item.kd_mata_kuliah,
            sks: item.sks
        }));

        $.ajax({
            url: '/mhs/krs/simpan',
            method: 'POST',
            data: {
                krs_data: JSON.stringify(data_krs)
            },
            beforeSend: function() {
                $("#btn-konfirmasi-ya").prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');
            },
            success: function(response) {
                if (response.status === "1") {
                    $.alert({
                        title: "Berhasil",
                        type: "green",
                        content: response.message,
                        onClose: function() {
                            window.location.reload();
                        }
                    });
                } else {
                    $.alert({
                        title: "Gagal",
                        type: "red",
                        content: response.message
                    });
                }
            },
            error: function() {
                $.alert({
                    title: "Error",
                    type: "red",
                    content: "Terjadi kesalahan sistem. Silakan coba lagi."
                });
            },
            complete: function() {
                $("#btn-konfirmasi-ya").prop('disabled', false).html('Ya, Simpan');
                $("#modal-konfirmasi").modal('hide');
            }
        });
    }
};

jQuery(document).ready(function () {
    jQuery.krs_jadwal.init();
});
