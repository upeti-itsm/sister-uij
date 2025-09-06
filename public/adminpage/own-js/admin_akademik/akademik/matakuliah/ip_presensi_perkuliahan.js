jQuery.ip_presensi = {
    data: {},
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;

        var table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/adm-akademik/akademik/perkuliahan/ip-presensi-perkuliahan/json',
                type: 'post',
                data: function (data) {
                    data.status = $("#status").val() || "2";
                }
            },
            scrollY: '300px',
            scrollCollapse: true,
            columns: [
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "5%",
                    render: function (data, type, row, meta) {
                        const number = meta.row + meta.settings._iDisplayStart + 1;
                        return "<b>" + number + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return "<b>" + data.ip_address + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "15%",
                    render: function (data) {
                        const status = data.sts_aktif == 1 ? "Aktif" : "Tidak Aktif";
                        return "<p>" + status + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "25%",
                    render: function (data) {
                        return "<p>" + data.tgl_created + "</p>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "15%",
                    render: function (data) {
                        return "<button title='Edit Alamat IP' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_ip_address_presensi_perkuliahan + "' data-ip_address='" + data.ip_address + "' data-status='" + data.sts_aktif + "'><i class='fas fa-edit'></i></button>" +
                            "<button title='Hapus Alamat IP' class='btn btn-sm btn-danger btn-delete' data-id='" + data.id_ip_address_presensi_perkuliahan + "' data-ip_address='" + data.ip_address + "' ><span class='spinner-border spinner-border-sm mr-2' id='detail-loading-spin-" + data.id_ip_address_presensi_perkuliahan + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
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

        $(".select2").select2({
            placeholder: "-- Semua Status --",
            minimumResultsForSearch: -1,
        });

        $("#status").change(function () {
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

        // Event Ketika Tombol Tambah Diklik
        $("#btn-tambah-data").click(function () {
            $("#insupLabel").text("Tambah IP Presensi");
            $("#modal-insup-ip-presensi").modal('show');
        });

        // Event Ketika Tombol Cancel Diklik
        $('#modal-insup-ip-presensi').on('hidden.bs.modal', function () {
            $("#alamat_ip").val("");
            $("#sts_aktif").val("").change();
        });

        // Event Ketika Tombol Edit Diklik
        $("#table").on('click', 'button.btn-edit', function () {
            $("#insupLabel").text("Ubah IP Presensi");
            $("#id_ip_address_presensi_perkuliahan").val($(this).data("id"));
            $("#alamat_ip").val($(this).data("ip_address"));
            let status = $(this).data("status");
            $("#sts_aktif").val(status ? "1" : "0").trigger("change");
            $("#modal-insup-ip-presensi").modal('show');
        });


        $("#btn-simpan").click(function () {
            var id = $("#id_ip_address_presensi_perkuliahan").val();
            var alamat_ip = $("#alamat_ip").val();
            var status = $("#sts_aktif").val();

            if (alamat_ip === "" || status === "") {
                $.alert({
                    title: 'Informasi',
                    type: 'red',
                    content: 'Pastikan semua komponen terisi !!'
                });
            }

            $("#insup-form").submit();
        });

        $("#table").on('click', 'button.btn-delete', function () {
            $("#delete-id_ip_address_presensi_perkuliahan").val($(this).data('id'));
            var ip_address = $(this).data('ip_address');

            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Apakah anda yakin menghapus alamat IP <b>' + ip_address + '</b> ?',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $("#delete-form").submit();
                        }
                    },
                    cancel: {
                        text: 'Batal',
                        btnClass: 'btn-red'
                    }
                }
            });
        });
    }
};

jQuery(document).ready(function () {
    jQuery.ip_presensi.init();
});
