jQuery.daftar_mahasiswa_lpppi = {
    data: {
        table: $("#table"),
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        // Option Data
        $(".select2").select2();
        // Bootstrap-toogle
        $('#is_lp3i').bootstrapToggle({
            on: 'Only LP3I',
            off: 'All'
        });
        $("#is_lp3i").change(function () {
            self.data.table.ajax.reload();
        });
        // Table With DataTable
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/adm-akademik/akademik/mahasiswa/mahasiswa-lpppi/json',
                type: 'post',
                data: function (data) {
                    data.angkatan = $("#angkatan").val();
                    data.prodi = $("#prodi").val();
                    data.is_lp3i = $("#is_lp3i").prop('checked');
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
                    width: "55%",
                    render: function (data) {
                        return "<b>" + data.nama_mahasiswa + " (" + data.nim + ")</b><br/>" +
                            "Alamat : <br/>" + data.alamat;
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%",
                    render: function (data) {
                        return "<b>" + data.jenjang_didik + " " + data.nama_program_studi + " (" + data.kd_program_studi + ") </b><br/>" +
                            "Fakultas : <b>" + data.nama_fakultas + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "10%",
                    render: function (data) {
                        if ($("#hak_akses").val() === "1") {
                            if (data.is_lp3i)
                                return "<button class='btn btn-danger btn-delete' title='Keluarkan dari LP3I' data-nim='" + data.nim + "'><span class='spinner-border spinner-border-sm mr-2' id='delete-loading-spin-" + data.nim + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
                            else
                                return "<button class='btn btn-success btn-add' title='Masukkan ke LP3I' data-nim='" + data.nim + "'><span class='spinner-border spinner-border-sm mr-2' id='add-loading-spin-" + data.nim + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-user-plus'></i></button>";
                        } else {
                            var lp3i = "Mahasiswa Mandala";
                            if (data.is_lp3i)
                                lp3i = "Mahasiswa LP3I";
                            return "<span class='badge badge-info p-2'>" + lp3i + "</span>";
                        }
                    }
                },
                {
                    data: 'nama_mahasiswa',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
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
        $("#prodi").change(function () {
            self.data.table.ajax.reload();
        });
        $("#angkatan").change(function () {
            self.data.table.ajax.reload();
        });
        $("#table").on('click', 'button.btn-delete', function () {
            var nim = $(this).data('nim');
            self.updateLpppi(nim, "delete", false);
        });
        $("#table").on('click', 'button.btn-add', function () {
            var nim = $(this).data('nim');
            self.updateLpppi(nim, "add", true);
        });
    },
    updateLpppi: function (nim, jenis = "add", status = true) {
        var self = this;
        $.ajax({
            url: '/adm-akademik/akademik/mahasiswa/mahasiswa-lpppi/update-lpppi',
            method: 'POST',
            data: {
                nim: nim,
                status: status
            },
            beforeSend: function () {
                $("#" + jenis + "-loading-spin-" + nim).show();
            },
            success: function (response) {
                var type = "success";
                if (response.status === 0 || response.status === "0")
                    type = "red";
                $.alert({
                    title: "Informasi",
                    type: type,
                    content: response.keterangan
                });
            },
            complete: function () {
                $("#" + jenis + "-loading-spin-" + nim).hide();

            }
        })
    }
};

jQuery(document).ready(function () {
    jQuery.daftar_mahasiswa_lpppi.init();
});
