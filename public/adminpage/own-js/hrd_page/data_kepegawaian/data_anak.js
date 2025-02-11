jQuery.data_anak = {
    data: {
        table: $("#table"),
        tanggal_lahir: $("#tanggal_lahir"),
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        $(".select2").select2();
        // Datepicker
        self.data.tanggal_lahir = $("#tanggal_lahir").datepicker({
            language: 'id',
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom'
        }).datepicker('setDate', moment().format('D/M/YYYY')).on('changeDate', function (e) {
            $("#tgl_lahir").val(moment(e.date).format('YYYY-MM-DD'));
        });
        $("#tgl_lahir").val(moment().format('YYYY-MM-DD'));

        // Table With DataTable
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/hrd/data-kepegawaian/detail-data-anak/json',
                type: 'post',
                data: function (data) {
                    data.id_personal = $("#id_personal").val();
                }
            },
            scrollY: '300px',
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
                    width: "45%",
                    render: function (data) {
                        return "<b>" + data.nama_anak + "</b><br/>" +
                            "<small>NIK. " + data.nik_anak + "</small><br/>" +
                            "<small>Tempat Lahir: " + data.tempat_lahir + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "30%",
                    render: function (data) {
                        return "<b>" + data.usia.replace('years', 'Tahun').replace('mons', 'Bulan').replace('days', 'Hari').replace('year', 'Tahun').replace('mon', 'Bulan').replace('day', 'Hari') + "</b><br/>" +
                            "<small>Tgl. Lahir: " + data.tanggal_lahir_ + "</small><br/>" +
                            "<small>Jenis Kelamin: " + data.jenis_kelamin + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        return "<button class='btn btn-sm btn-info btn-edit mr-2' title='Ubah Anak' data-id='" + data.id_anak_karyawan + "' data-nama='" + data.nama_anak + "'><span class='spinner-border spinner-border-sm mr-2' id='edit-loading-spin-" + data.id_anak_karyawan + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-edit'></i></button>" +
                            "<button class='btn btn-sm btn-danger btn-delete' title='Hapus Anak' data-id='" + data.id_anak_karyawan + "' data-nama='" + data.nama_anak + "'><span class='spinner-border spinner-border-sm mr-2' id='delete-loading-spin-" + data.id_anak_karyawan + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash'></i></button>";
                    }
                },
                {
                    data: 'nama_anak',
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

        $("#table").on('click', 'button.btn-edit', function () {
            var id = $(this).data('id');
            $.ajax({
                url: '/hrd/data-kepegawaian/detail/detail-data-anak/' + id,
                method: 'GET',
                beforeSend: function () {
                    $("#edit-loading-spin-" + id).show();
                },
                success: function (response) {
                    if (response.id_anak_karyawan){
                        $("#id_anak").val(response.id_anak_karyawan);
                        $("#nik").val(response.nik_anak);
                        $("#nama").val(response.nama_anak);
                        $("#jenis_kelamin").val(response.kd_jenis_kelamin).change();
                        $("#tempat_lahir").val(response.tempat_lahir);
                        $("#tgl_lahir").val(response.tanggal_lahir);
                        self.data.tanggal_lahir.datepicker('update', moment(response.tanggal_lahir).format('D/M/YYYY'));
                    } else {
                        $.alert({
                            title: 'Informasi',
                            type: 'red',
                            content: 'Data Anak Tidak Ditemukan!'
                        });
                    }
                },
                complete: function () {
                    $("#edit-loading-spin-" + id).hide();
                },
            })
        });
        $("#table").on('click', 'button.btn-delete', function () {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin menghapus data anak atas nama ' + nama + ' ?',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/hrd/data-kepegawaian/detail-data-anak/delete',
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
    jQuery.data_anak.init();
});
