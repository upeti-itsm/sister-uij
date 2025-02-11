jQuery.konsentrasi_jurusan = {
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
                url: '/adm-akadmik/perkuliahan/konsentrasi-jurusan/json/get-konsentrasi-jurusan',
                type: 'post',
                data: function (d) {
                    d.id = '00000000-0000-0000-0000-000000000000';
                    d.prodi = $("#kd_prodi").val();
                }
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
                        return "<b>" + data.nama_konsentrasi_jurusan + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    render: function (data) {
                        return "<small>Prodi. : " + data.nama_program_studi + "</small><br/>" +
                            "<small>Tahun Dibuka. : " + data.tahun_dibuka + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    render: function (data) {
                        return "<button title='Edit Konsentrasi Jurusan' class='btn btn-sm btn-primary btn-detail mr-2' data-id='" + data.id_konsentrasi_jurusan + "' data-kd_prodi='" + data.kd_program_studi + "' data-nama_konsentrasi='" + data.nama_konsentrasi_jurusan + "' data-tahun_dibuka='" + data.tahun_dibuka + "'><i class='fas fa-edit'></i></button>" +
                            "<button title='Hapus Konsentrasi Jurusan' class='btn btn-sm btn-danger btn-delete' data-id='" + data.id_konsentrasi_jurusan + "' data-nama_prodi='" + data.nama_program_studi + "' data-nama_konsentrasi='" + data.nama_konsentrasi_jurusan + "'><i class='fas fa-trash'></i></button>";
                    }
                },
                {
                    data: 'kd_program_studi',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'nama_konsentrasi_jurusan',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'tahun_dibuka',
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
        // Add or Update Data
        // Add
        $("#btn-tambah-data").click(function () {
            $("#insupLabel").text("Tambah Konsentrasi Jurusan");
            $("#modal-insup-konsentrasi-jurusan").modal('show');
        });
        // Update
        $("#table").on('click', 'button.btn-detail', function () {
            $("#insupLabel").text("Ubah Konsentrasi Jurusan");
            $("#insup-id_konsentrasi_jurusan").val($(this).data("id"));
            $("#insup-kd_prodi").val($(this).data("kd_prodi")).change();
            $("#insup-nama_konsentrasi").val($(this).data("nama_konsentrasi"));
            $("#insup-tahun_dibuka").val($(this).data("tahun_dibuka"));
            $("#modal-insup-konsentrasi-jurusan").modal('show');
        });
        // On Close Modal : Set Default Data
        $('#modal-insup-konsentrasi-jurusan').on('hidden.bs.modal', function () {
            $("#insup-id_konsentrasi_jurusan").val("00000000-0000-0000-0000-000000000000");
            $("#insup-kd_prodi").val("").change();
            $("#insup-nama_konsentrasi").val("");
            $("#insup-tahun_dibuka").val("");
        });
        // On Save Data
        $("#btn-simpan").click(function () {
            var id = $("#insup-id_konsentrasi_jurusan").val();
            var kd_prodi = $("#insup-kd_prodi").val();
            var nama_konsentrasi = $("#insup-nama_konsentrasi").val();
            var tahun_dibuka = $("#insup-tahun_dibuka").val();
            if (id === "" || kd_prodi === "" || nama_konsentrasi === "" || tahun_dibuka === "")
                $.alert({
                    title: 'Informasi',
                    type: 'red',
                    content: 'Pastikan semua komponen terisi !!'
                });
            else
                $("#insup-form").submit();
        });
        // On Delete
        $("#table").on('click', 'button.btn-delete', function () {
            $("#delete-id_konsentrasi_jurusan").val($(this).data('id'));
            var prodi = $(this).data('nama_prodi');
            var nama = $(this).data('nama_konsentrasi');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Apakah anda yakin menghapus <b>' + nama + '</b> dari prodi <b>' + prodi + '</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function() {
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
    },
};

jQuery(document).ready(function () {
    jQuery.konsentrasi_jurusan.init();
});
