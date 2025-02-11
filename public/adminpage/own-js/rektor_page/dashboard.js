jQuery.dashboard = {
    data: {
        Calendar: FullCalendar.Calendar,
        myCalendar: "",
        legend: [[], [], [], [], [], [], [], [], [], [], [], []],
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        $.ajax({
            url: "/rek/dashboard/get-daftar-ulang-tahun",
            method: 'post',
            data: {},
            beforeSend: function () {
            },
            success: function (response) {
                var ultah = [];
                $.each(response.ultah, function (index, val) {
                    self.data.legend[val.bulan - 1].push({
                        nama: val.nama,
                        umur: val.umur
                    });
                    ultah.push({
                        title: val.nama,
                        start: val.tanggal_ulang_tahun,
                        groupId: val.bulan,
                        color: '#a0eb3f',
                        description: val.keterangan_ulang_tahun
                    })
                });
                self.set_calender('calendar', ultah);
            },
            complete: function () {
                self.set_list_ultah();
            }
        });
        $('#calendar').on('click', 'button.fc-next-button, button.fc-prev-button', function () {
            self.set_list_ultah();
        });
    },
    set_list_ultah: function (){
        var self = this;
        var html = "";
        $.each(self.data.legend[moment(self.data.myCalendar.getDate()).format('M') - 1], function (index, val) {
            html = html + "<small class='text-success'><i class='fas fa-user text-info mr-2'></i>" + val.nama + " ("+val.umur+")</small><br/>";
        })
        $("#list_ultah").html("");
        $("#list_ultah").append(html);
    },
    set_calender: function (id, event) {
        var self = this;
        var calendarEl = document.getElementById('calendar');
        self.data.myCalendar = new self.data.Calendar(calendarEl, {
            plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
            defaultView: 'dayGridMonth',
            defaultDate: $("#tgl").val(),
            navLinks: false,
            businessHours: true,
            events: event,
            eventClick: function (info) {
                $.alert({
                    title: 'Informasi',
                    type: 'green',
                    content: info.event._def.extendedProps.description,
                    backgroundDismissAnimation: 'glow'
                });
            },
        });
        self.data.myCalendar.render();
    },
    set_maba_chart: function (id, data) {
        var self = this;
        var root = am5.Root.new(id);
        root.setThemes([
            am5themes_Animated.new(root)
        ]);
        var chart = root.container.children.push(am5percent.PieChart.new(root, {
            layout: root.verticalLayout
        }));
        var series = chart.series.push(am5percent.PieSeries.new(root, {
            alignLabels: true,
            calculateAggregates: true,
            valueField: "value",
            categoryField: "category"
        }));
        series.slices.template.setAll({
            strokeWidth: 3,
            stroke: am5.color(0xffffff)
        });
        series.labelsContainer.set("paddingTop", 30)
        series.slices.template.adapters.add("radius", function (radius, target) {
            var dataItem = target.dataItem;
            var high = series.getPrivate("valueHigh");
            if (dataItem) {
                var value = target.dataItem.get("valueWorking", 0);
                return radius * value / high
            }
            return radius;
        });
        series.data.setAll(data);
        series.appear(1000, 100);
    },
};

jQuery(document).ready(function () {
    jQuery.dashboard.init();
});
