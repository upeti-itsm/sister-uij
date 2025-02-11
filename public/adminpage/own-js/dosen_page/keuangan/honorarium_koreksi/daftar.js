jQuery.daftar = {
    data: {
        table: $("#table"),
        bulan: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        // Option Data
        $(".select2").select2();
        self.data.table = $("#table").DataTable({
            serverSide: true,
            ajax: {
                url: '/dosen/keuangan/honorarium/honorarium-koreksi/json',
                type: 'post',
                data: function (data) {
                    data.bulan = $("#filter_bulan").val();
                    data.tahun = $("#filter_tahun").val();
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
                    data: 'keterangan',
                    searchable: false,
                    sClass: 'text-left',
                    width: "40%"
                },
                {
                    data: 'nominal_honor_koreksi',
                    searchable: false,
                    sClass: 'text-left',
                    width: "35%"
                },
                {
                    data: null,
                    searchable: false,
                    sClass: 'text-center',
                    width: "20%",
                    render: function (data) {
                        if (data.available_repair === true)
                            return "<a href='/dosen/keuangan/honorarium/honorarium-koreksi/detail/" + data.id_rekapitulasi_honorarium_koreksi + "' title='Lihat Detail Honor' class='btn btn-sm btn-primary btn-edit mr-2' data-id='" + data.id_rekapitulasi_honorarium_koreksi + "'><i class='fas fa-search'></i></a>";
                        else
                            return "<a href='/dosen/keuangan/honorarium/honorarium-koreksi/slip-gaji/" + data.id_rekapitulasi_honorarium_koreksi + "' target='_blank' title='Print Slip Honorarium' class='btn btn-sm btn-danger btn-print mr-2' data-id='" + data.id_rekapitulasi_honorarium_koreksi + "'><i class='fas fa-print'></i></a>";
                    }
                },
                {
                    data: 'nama',
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
        $("#filter_bulan, #filter_tahun").change(function () {
            self.data.table.ajax.reload();
        });
    },
};

jQuery(document).ready(function () {
    jQuery.daftar.init();
});
