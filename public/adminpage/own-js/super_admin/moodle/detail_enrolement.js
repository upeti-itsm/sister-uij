jQuery.detail_enrolment = {
    data: {
        isSyncCanceled: false,
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
        // Table With DataTable
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/super-admin/moodle/course-enrolment/json/detail-enrolment',
                type: 'post',
                data: function (data) {
                    data.shortname = $("#shortname").val();
                    data.role = $("#role").val();
                }
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
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return "<b>" + data.nama_user + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%",
                    render: function (data) {
                        return "<b>" + data.user_role + "</b>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "25%",
                    render: function (data) {
                        return "<button title='Un Enrol User' class='btn btn-danger btn-un_enrol' data-id='" + data.enrol_id + "' data-role='" + data.user_role + "' data-nama_user='" + data.nama_user + "' data-fullname='" + data.fullname + "'><span class='spinner-border spinner-border-sm mr-2' id='student-loading-spin-" + data.enrol_id + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-trash-alt mr-2'></i>Un Enrol</button>";
                    }
                },
                {
                    data: 'nama_user',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'username',
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

        // Add
        $("#btn-enrol-dosen").click(function () {
            $("#filter-collapse").collapse("hide");
            $("#form-collapse-mahasiswa").collapse("hide");
            $("#form-collapse").collapse("show");
        });
        // On Cancel Click
        $("#btn-cancel").click(function () {
            $("#cari-data").val("");
            $("#filter-collapse").collapse("show");
            $("#form-collapse").collapse("hide");
        });

        $("#btn-save-enrol").click(function () {
            if (!$("#add_username").val())
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Sudah Memilih Dosen"
                });
            else {
                $.ajax({
                    url: '/super-admin/moodle/course-enrolment/json/add-enrolment',
                    method: 'POST',
                    data: {
                        shortname: $("#shortname").val(),
                        username: $("#add_username").val(),
                        role: 'editingteacher',
                    },
                    beforeSend: function () {
                        $("#loading-tambah-data").show();
                    },
                    success: function (response) {
                        if (response.is_success) {
                            $.alert({
                                title: 'Informasi',
                                type: 'green',
                                content: response.result
                            });
                            $("#btn-cancel").trigger("click");
                        } else {
                            $.alert({
                                title: 'Informasi',
                                type: 'red',
                                content: response.result
                            });
                        }
                    },
                    complete: function () {
                        $("#loading-tambah-data").hide();
                        self.data.table.ajax.reload();
                    }
                });
            }
        });

        // Add
        $("#btn-enrol-mahasiswa").click(function () {
            $("#filter-collapse").collapse("hide");
            $("#form-collapse").collapse("hide");
            $("#form-collapse-mahasiswa").collapse("show");
        });
        // On Cancel Click
        $("#btn-cancel-mahasiswa").click(function () {
            $("#cari-data").val("");
            $("#filter-collapse").collapse("show");
            $("#form-collapse-mahasiswa").collapse("hide");
        });

        $("#angkatan").change(function (){
            var angkatan = $(this).val();
            $.ajax({
                url: '/super-admin/moodle/course-enrolment/json/get-mahasiswa-by-angkatan',
                method: 'POST',
                data: {
                    angkatan: angkatan
                },
                beforeSend: function () {
                    $("#loading_mahasiswa").show();
                },
                success: function (response) {
                    var html = "";
                    $("#add_mahasiswa").html("");
                    $.each(response, function (key, value) {
                        html = html + "<option value='" + value.nim + "'>" + value.nama_mahasiswa + " (" + value.nim + ")</option>"
                    });
                    $("#add_mahasiswa").append(html);
                },
                complete: function () {
                    $("#loading_mahasiswa").hide();
                }
            });
        })

        $("#btn-save-enrol-mahasiswa").click(function () {
            if (!$("#add_mahasiswa").val())
                $.alert({
                    title: "Peringatan",
                    type: "orange",
                    content: "Pastikan Sudah Memilih Mahasiswa"
                });
            else {
                $.ajax({
                    url: '/super-admin/moodle/course-enrolment/json/add-enrolment',
                    method: 'POST',
                    data: {
                        shortname: $("#shortname").val(),
                        username: $("#add_mahasiswa").val(),
                        role: 'student'
                    },
                    beforeSend: function () {
                        $("#loading-tambah-data").show();
                    },
                    success: function (response) {
                        if (response.is_success) {
                            $.alert({
                                title: 'Informasi',
                                type: 'green',
                                content: 'Berhasil Enrol Mahasiswa'
                            });
                            $("#btn-cancel-mahasiswa").trigger("click");
                        } else {
                            $.alert({
                                title: 'Informasi',
                                type: 'red',
                                content: response.result
                            });
                        }
                    },
                    complete: function () {
                        $("#loading-tambah-data").hide();
                        self.data.table.ajax.reload();
                    }
                });
            }
        });

        $("#table").on('click', 'button.btn-un_enrol', function () {
            var id = $(this).data("id");
            var fullname = $(this).data("fullname");
            var nama_user = $(this).data("nama_user");
            var role = $(this).data("role");
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin Un-Enrol ' + nama_user + ' sebagai ' + role + ' di ' + fullname + '?<br/><b class="text-danger">Akun yang bersangkutan tidak akan bisa mengakses matakuliah '+fullname+'</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/super-admin/moodle/course-enrolment/json/delete-enrolment',
                                method: 'POST',
                                data: {
                                    id: id
                                },
                                beforeSend: function () {
                                    $("#sync-teacher-loading-spin-" + id).show();
                                },
                                success: function (response) {
                                    if (response.is_success) {
                                        $.alert({
                                            title: "Informasi",
                                            type: "green",
                                            content: response.result
                                        });
                                    } else {
                                        $.alert({
                                            title: "Peringatan",
                                            type: "red",
                                            content: response.result
                                        });
                                    }
                                    self.data.table.ajax.reload();
                                }
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
    jQuery.detail_enrolment.init();
});
