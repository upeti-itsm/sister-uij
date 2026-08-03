jQuery.presensi_mahasiswa = {
    data: {
        table: $("#table"),
        mahasiswa: [],
        status_absensi: [
            {id: 1, label: "Hadir"},
            {id: 2, label: "Ijin"},
            {id: 3, label: "Sakit"},
            {id: 4, label: "Alpha"}
        ],
        selectedId: 4,

    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        // Option Data
        $(".select2").select2();

        // Table With DataTable
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/dosen/akademik/rekapitulasi-absen-mengajar/presensi/json',
                type: 'post',
                data: function (data) {
                    data.id_rekap = $("#id_rekap").val();
                }
            },
            fnDrawCallback: function () {
                var rows = this.api().rows().data().toArray();

                // isi array mahasiswa
                self.data.mahasiswa = rows.map(r => ({
                    id_mhs: r.id_mhs,
                    nama: r.nama_mahasiswa
                }));

                // update rekap jumlah
                self.update_rekap(rows);
            },
            scrollY: '500px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'nomor',
                    searchable: false,
                    sClass: 'text-center',
                    width: "5%"
                },
                {
                    data: "nim",
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                },
                {
                    data: "nama_mahasiswa",
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                },
                {
                    data: "nama_program_studi",
                    searchable: false,
                    sClass: 'text-center',
                    width: "25%",
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        // daftar status
                        var options = [
                            {id: 0, text: "Alpha"},
                            {id: 1, text: "Present"},
                            {id: 2, text: "Ijin"},
                            {id: 3, text: "Sakit"}
                        ];

                        // bangun option dinamis
                        var select = "<select class='statusSelect form-control'>";
                        options.forEach(function (opt) {
                            var selected = (data.id_sts_presensi === opt.id) ? "selected" : "";
                            select += "<option value='" + opt.id + "' " + selected + ">" + opt.text + "</option>";
                        });
                        select += "</select>";

                        return select;
                    }
                },
                {
                    data: 'nama_mahasiswa',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                }
            ],
            paging: true,
            processing: true,
            pageLength: 250,
            ordering: false,
            lengthChange: false,
            autoWidth: false,
            sDom: 'ltir',
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

        // ketika datatable selesai di-draw
        self.data.table.on("draw.dt", function () {
            // set warna awal semua select
            $(".statusSelect").each(function () {
                self.updateSelector(this); // pakai fungsi pewarnaan
            });
        });

        $(document).on("change", ".statusSelect", function () {
            self.updateSelector(this); // atau fungsi lain yang dipanggil
        });

        $("#btn-simpan-presensi").click(function () {
            self.simpanPresensi();
        });

        $("#btn-hadir-semua").click(function () {
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'small',
                content: 'Apakah anda yakin, semua mahasiswa akan di anggap hadir, data hanya akan tersimpan setelah menekan tombol <b>Simpan Presensi</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            self.hadirSemua();
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red'
                    }
                }
            });
        });

        $("#export-to-pdf").click(function () {
            window.open('/dosen/akademik/daftar-matakuliah/export-presensi/' + $("#id_rekap").val());
        });

        $("#table").on('click', 'button.btn-delete', function () {
            var id = $(this).data('id');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin akan menghapus data ini ? dalam waktu satu minggu hanya diperkenankan menghapus 3 data. <b>Seluruh Presensi Mahasiswa akan Terhapus, dan Data yang terhapus tidak dapat dikembalikan lagi</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/dosen/akademik/rekapitulasi-absen-mengajar/delete',
                                method: 'POST',
                                data: {
                                    id_rekap: id
                                },
                                beforeSend: function () {
                                    $("#delete-loading-spin-" + id).show();
                                },
                                success: function (response) {
                                    if (response.status === "1") {
                                        $.alert({
                                            title: "Informasi",
                                            type: "green",
                                            content: response.keterangan
                                        });
                                    } else {
                                        $.alert({
                                            title: "Peringatan",
                                            type: "red",
                                            content: response.keterangan
                                        })
                                    }
                                },
                                complete: function () {
                                    $("#delete-loading-spin-" + id).hide();
                                    self.data.table.ajax.reload();
                                }
                            });
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red'
                    }
                }
            });
        });
    },
    update_rekap: function (rows) {
        var self = this;
        if (rows.length === 0) {
            $("#tot_mahasiswa").text("0");
            $("#tot_hadir").text("0");
            $("#tot_ijin").text("0");
            $("#tot_sakit").text("0");
            $("#tot_alpha").text("0");
        } else {
            $("#tot_mahasiswa").text(rows[0].jml_total_mahasiswa);
            $("#tot_hadir").text(rows[0].jml_total_mahasiswa_hadir);
            $("#tot_sakit").text(rows[0].jml_total_mahasiswa_sakit);
            $("#tot_ijin").text(rows[0].jml_total_mahasiswa_izin);
            $("#tot_alpha").text(rows[0].jml_total_mahasiswa_tidak_hadir);
        }
    },
    updateSelector: function (select) {
        select.classList.remove("bg-success", "bg-warning", "bg-danger", "text-white");
        if (select.value === "1") {
            select.classList.add("bg-success", "text-white");
        } else if (select.value === "2" || select.value === "3") {
            select.classList.add("bg-warning");
        } else {
            select.classList.add("bg-danger", "text-white");
        }
    },
    simpanPresensi: function () {
        var self = this;
        let ids = [];
        let statuses = [];
        let desc = [];

        $(".statusSelect").each(function (i) {
            ids.push(self.data.mahasiswa[i].id_mhs);
            statuses.push($(this).val());
            desc.push($(this).find("option:selected").text());
        });
        let $btn = $("#btn-simpan-presensi");
        let $spinner = $btn.find(".spinner-border");
        let $btnText = $btn.find(".btn-text");
        $.confirm({
            title: 'Konfirmasi !',
            type: 'orange',
            columnClass: 'medium',
            content: 'Apakah anda yakin akan menyimpan presensi mahasiswa ini ? <b>Seluruh Presensi Mahasiswa akan tersimpan kedalam database</b>',
            buttons: {
                confirm: {
                    text: 'Yakin',
                    btnClass: 'btn-green',
                    keys: ['enter'],
                    action: function () {
                        $.ajax({
                            url: '/dosen/akademik/rekapitulasi-absen-mengajar/presensi/store',
                            method: 'POST',
                            data: {
                                id_mhs: ids.join(","),      // gabung dengan koma
                                status: statuses.join(","), // gabung dengan koma
                                keterangan: desc.join(","), // gabung dengan koma
                                id_rekap: $("#id_rekap").val()
                            },
                            beforeSend: function () {
                                // 🔹 Aktifkan loading sebelum request dikirim
                                $btn.prop("disabled", true);
                                $btnText.text("Menyimpan...");
                                $spinner.removeClass("d-none");
                            },
                            success: function (response) {
                                if (response.status === 1) {
                                    $.alert({
                                        title: "Informasi",
                                        type: "green",
                                        content: response.keterangan
                                    });
                                } else {
                                    $.alert({
                                        title: "Peringatan",
                                        type: "red",
                                        content: response.keterangan
                                    });
                                }
                            },
                            error: function () {
                                $.alert({
                                    title: "Peringatan",
                                    type: "red",
                                    content: "Terjadi Kesalahan Sistem, Hubungi Admin"
                                });
                            },
                            complete: function () {
                                // 🔹 Reset tombol setelah request selesai
                                $btn.prop("disabled", false);
                                $btnText.text("Simpan Presensi");
                                $spinner.addClass("d-none");
                                self.data.table.ajax.reload();
                            }
                        });
                    }
                },
                cancel: {
                    text: 'Batal',
                    btnClass: 'btn-red'
                }
            }
        });
    },
    hadirSemua: function () {
        var self = this;
        $(".statusSelect").each(function () {
            $(this).val(1);       // set value jadi Hadir
            self.updateSelector(this);    // panggil fungsi pewarnaan
        });
    }
};

jQuery(document).ready(function () {
    jQuery.presensi_mahasiswa.init();
});
