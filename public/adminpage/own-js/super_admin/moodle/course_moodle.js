jQuery.course_moodle = {
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
                url: '/super-admin/moodle/course-enrolment/json/daftar-course',
                type: 'post',
                data: function (data) {
                    data.tahun_akademik = $("#tahun_akademik").val();
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
                    width: "60%",
                    render: function (data) {
                        var nama_asisten = data.nama_asisten;
                        if (!data.nik_asisten)
                            nama_asisten = "-- Tidak Ada --";
                        return "<b>" + data.fullname + " (" + data.shortname_course + ")</b><br/>" +
                            "<small>Dosen Pengampu : " + data.nama_pengajar + "" +
                            " | Nama Asisten : " + nama_asisten + "</small>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "35%",
                    render: function (data) {
                        return "<div class='row'>" +
                            "<div class='col-md-4'>" +
                            "<a title='Total Partisipan' href='/super-admin/moodle/course-enrolment/detail-course/" + data.shortname_course + "' class='btn btn-block btn-success-soft mr-2'><i class='fas fa-users mr-2'></i>" + data.jml_all_participant + "</a>" +
                            "</div>" +
                            "<div class='col-md-4'>" +
                            "<button title='Total Dosen' style='cursor: default' class='btn btn-block btn-danger-soft mr-2' data-id='" + data.jadwal_id + "'><span class='spinner-border spinner-border-sm mr-2' id='sync-teacher-loading-spin-" + data.jadwal_id.replaceAll("@", "_") + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-user-check mr-2'></i>" + data.jml_editingteacher + "</button>" +
                            "</div>" +
                            "<div class='col-md-4'>" +
                            "<button title='Total Mahasiswa' style='cursor: default' class='btn btn-block btn-primary-soft' data-id='" + data.jadwal_id + "'><span class='spinner-border spinner-border-sm mr-2' id='student-loading-spin-" + data.jadwal_id.replaceAll("@", "_") + "' style='display: none' role='status' aria-hidden='true'></span><i class='fas fa-graduation-cap mr-2'></i>" + data.jml_student + "</button>" +
                            "</div>" +
                            "</div>";
                    }
                },
                {
                    data: 'fullname',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'nama_pengajar',
                    searchable: true,
                    sClass: 'text-center',
                    visible: false
                },
                {
                    data: 'nama_asisten',
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
        $("#btn-sync-ulang").click(function () {
            $("#modal-sync-jadwal").modal('show');
        });
        $("#modal-btn-sync").click(function () {
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                content: 'Apakah anda yakin akan melakukan Enrolment Peserta ? <b style="color: red">Akan Berpengaruh Ke Moodle</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '/super-admin/moodle/json/json-course-enrol',
                                method: 'post',
                                data: {
                                    tahun_akademik: $("#tahun_akademik_sync").val()
                                },
                                success: function (result) {
                                    if (result.is_success) {
                                        $.alert({
                                            title: "Informasi",
                                            type: "green",
                                            content: result.result
                                        });
                                    } else {
                                        $.alert({
                                            title: "Peringatan",
                                            type: "red",
                                            content: result.result
                                        });
                                    }
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

        $("#table").on('click', 'button.btn-sync-teacher', function () {
            var id = $(this).data("id");
            $.confirm({
                title: 'Konfirmasi !',
                type: 'orange',
                columnClass: 'medium',
                content: 'Apakah anda yakin melakukan Enrolment Dosen/Teacher ?<br/><b class="text-danger">Semua data akan di update berdasarkan data jadwal siakad</b>',
                buttons: {
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $.ajax({
                                url: '',
                                method: 'POST',
                                data: {
                                    id: id
                                },
                                beforeSend: function () {
                                    $("#sync-teacher-loading-spin-" + id.replaceAll("@", "_")).show();
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
    jQuery.course_moodle.init();
});
