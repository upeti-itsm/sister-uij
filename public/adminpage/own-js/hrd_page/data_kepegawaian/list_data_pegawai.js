jQuery.list_data_pegawai = {
    data: {
        table: $("#table-daftar-pegawai"),
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        // Select2
        $(".select2").select2({
            placeholder: " Semua Pegawai"
        });
        $("#jenis_karyawan").change(function (){
            self.data.table.ajax.reload();
        });
        // Table With DataTable
        self.data.table = $("#table-daftar-pegawai").DataTable({
            serverSide: true,
            ajax: {
                url: '/hrd/data-kepegawaian/list-data-pegawai/json',
                type: 'post',
                data: function (data){
                    data.jenis_pegawai = $("#jenis_karyawan").val();
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: 'nomor',
                    searchable: false,
                    sClass: 'text-center',
                    width: '5%'
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%",
                    render: function (data) {
                        return "<b>" + data.nama + "</b><br/>" +
                            "<small>NIK. " + data.nik_mandala + " / NIDN. " + data.nidn + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "45%",
                    render: function (data) {
                        return "<b>" + data.unit_kerja + "</b><br/>" +
                            "<small>" + data.pendidikan_terakhir + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        return "<a href='/hrd/data-kepegawaian/detail-data-pegawai/" + data.id_personal + "' title='Lihat Detail Pegawai' class='btn btn-sm btn-success btn-edit mr-2'><i class='fas fa-user-edit'></i></a>" +
                            "<button class='btn btn-sm btn-danger btn-delete' title='Hapus Pegawai' data-id='" + data.id_personal + "' data-nama='" + data.nama + "' data-unit_kerja='" + data.unit_kerja + "'><span class='spinner-border spinner-border-sm mr-2' id='delete-loading-spin-" + data.id_personal + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
                    }
                },
                {
                    data: 'nama',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },

                {
                    data: 'nik_mandala',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'nidn',
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

        $("#table-daftar-pegawai").on('click', 'button.btn-delete', function (){
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin menghapus data pegawai atas nama ' + nama + ' ?',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/hrd/data-kepegawaian/create-data-pegawai/delete',
                                method: 'POST',
                                data: {
                                    id: id
                                },
                                beforeSend: function () {
                                    $("#delete-loading-spin-" + id).show();
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
                                complete: function () {
                                    $("#delete-loading-spin-" + id).hide();
                                    self.data.table.ajax.reload();
                                },
                            })
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
    jQuery.list_data_pegawai.init();
});
