jQuery.program_studi = {
    data: {},
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        // Option Data
        $(".select2").select2();
        // Table With DataTable
        table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/adm-akadmik/sinkronisasi-data/program-studi/json/get-program-studi',
                type: 'post'
            },
            scrollY: '300px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'nomor',
                    searchable: false,
                    sClass: 'text-center'
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    render: function (data) {
                        return "<b>" + data.nama_program_studi + " </b><br/>" +
                            "<small>Kode Prodi : " + data.kd_program_studi + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    render: function (data) {
                        return "<small>Jenjang Didik: " + data.jenjang_didik + "</small><br/>" +
                            "<small>Nama Fakultas: " + data.nama_fakultas + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    render: function (data) {
                        return "<button title='Cek Data Feeder' class='btn btn-sm btn-info btn-check-feeder mr-2' data-id='" + data.id_program_studi + "' data-kd_prodi='" + data.kd_program_studi + "'><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_program_studi + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-cogs'></i></button>";
                    }
                },
                {
                    data: 'nama_program_studi',
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
        $("#btn-filter").click(function () {
            table.ajax.reload();
        });
        $("#btn-cari-data").click(function () {
            table.search($("#cari-data").val()).draw();
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

        $("#table").on('click', 'button.btn-check-feeder', function () {
            var id = $(this).data('id');
            var kd = $(this).data('kd_prodi');
            $.ajax({
                url: "/adm-akadmik/sinkronisasi-data/program-studi/json/perbandingan-data-feeder",
                method: 'POST',
                data: {
                    id_program_studi: id,
                    kd_prodi: kd
                },
                beforeSend: function () {
                    $("#detail-loading-spin-" + id).show();
                },
                success: function (result) {
                    $("#modal-check-id_program_studi").val(result.sipadu[0].id_program_studi)
                    self.set_data_sipadu(result.sipadu);
                    self.set_data_feeder(result.feeder);
                    self.set_perbandingan(result);
                },
                complete: function () {
                    $("#modal-check-feeder").modal("show");
                    $("#detail-loading-spin-" + id).hide();
                }
            })
        });

        $('#modal-check-feeder').on('hidden.bs.modal', function () {
            $("#modal-check-id_program_studi").val();
        });

        $("#modal-check-btn-sync").click(function () {
            location.href = '/adm-akadmik/sinkronisasi-data/program-studi/sync/' + $("#modal-check-id_program_studi").val();
        });
        $("#btn-sync-ulang").click(function (){
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Apakah anda yakin akan melakukan Sinkronisasi Data ? <b style="color: red">Semua data Prodi akan diseuaikan dengan Feeder</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function() {
                            location.href = '/adm-akadmik/sinkronisasi-data/program-studi/sync/';
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
    set_data_sipadu: function (data) {
        data = data[0];
        var self = this;
        $("#modal-check-kd_prodi_sipadu").text(data.kd_program_studi);
        $("#modal-check-nama_prodi_sipadu").text(data.nama_program_studi);
        $("#modal-check-jenjang_didik_sipadu").text(data.jenjang_didik);
        $("#modal-check-status_sipadu").text(data.status_data_feeder);
    },
    set_data_feeder: function (data) {
        data = data[0];
        var self = this;
        $("#modal-check-kd_prodi_feeder").text(data.kode_program_studi);
        $("#modal-check-nama_prodi_feeder").text(data.nama_program_studi);
        $("#modal-check-jenjang_didik_feeder").text(data.nama_jenjang_pendidikan);
        $("#modal-check-status_feeder").text(data.status);
    },
    set_perbandingan: function (data) {
        var self = this;
        var sipadu = data.sipadu[0], feeder = data.feeder[0];
        var success = "<button class='btn btn-block btn-sm btn-success' title='Data Sama' style='cursor: none'><i class='fas fa-check-square'></i></button>";
        var error = "<button class='btn btn-block btn-sm btn-danger' title='Terdapat Perbedaan' style='cursor: none'><i class='fas fa-window-close'></i></button>";
        var kd_prodi = success, nama_prodi = success, jenjang_didik = success, status = success;

        if (sipadu.kd_program_studi !== feeder.kode_program_studi)
            kd_prodi = error;
        if (sipadu.nama_program_studi !== feeder.nama_program_studi)
            nama_prodi = error;
        if (sipadu.jenjang_didik !== feeder.nama_jenjang_pendidikan)
            jenjang_didik = error;
        if (sipadu.status_data_feeder !== feeder.status)
            status = error;

        $("#modal-check-perbandingan_kd_prodi").html(kd_prodi);
        $("#modal-check-perbandingan_nama_prodi").html(nama_prodi);
        $("#modal-check-perbandingan_jenjang_didik").html(jenjang_didik);
        $("#modal-check-perbandingan_status").html(status);
    }

};

jQuery(document).ready(function () {
    jQuery.program_studi.init();
});
