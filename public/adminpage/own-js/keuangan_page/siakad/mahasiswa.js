jQuery.mahasiswa = {
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
        var table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/keu/siakad/json/daftar-mahasiswa',
                type: 'post',
                data: function (data) {
                    data.angkatan = $("#angkatan").val();
                    data.status = $("#status").val();
                }
            },
            scrollY: '400px',
            scrollCollapse: true,
            columns: [
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "60%",
                    render: function (data) {
                        return "<div class='row'>" +
                            "<div class='col-md-8'>" +
                            "<b>" + data.nama_lengkap + " ( " + data.NPK + " )</b><br/>" +
                            "<small>Tanggal Lulus : " + data.inf_tgl_lulus + "</small><br/>" +
                            "<small>Tahun Akademik : " + data.tahun_akademik_lulus + "</small>" +
                            "</div>" +
                            "<div class='col-md-4'>" +
                            "<img src='http://siakad.stie-mandala.ac.id/_report/photo_m/"+data.NPK+".jpg' style='max-height: 100px' class='img img-fluid'>" +
                            "</div>" +
                            "</div>";
                    }
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%",
                    render: function (data) {
                        return "<b>" + data.nama_program + " (" + data.angkatan + ")</b><br/>" +
                            "<small>Nomor HP : " + data.hp + " | Kota Rumah : " + data.kota_rumah + "</small><br/>" +
                            "<small>Alamat : " + data.alamat_rumah + "</small>";
                    }
                },
                {
                    data: 'nama_lengkap',
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
        $("#angkatan, #status").change(function () {
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
    },
};

jQuery(document).ready(function () {
    jQuery.mahasiswa.init();
});
