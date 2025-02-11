jQuery.dashboard = {
    data: {},
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        self.set_graph();
    },
    set_graph: function () {
        var self = this;
        $.ajax({
            url: "/mhs/dashboard/get-index-prestasi",
            method: 'post',
            data: {
                nim: $("#nim").val()
            },
            beforeSend: function () {
                $("#loading").show();
            },
            success: function (response) {
                var data = [];
                $.each(response, function (index, val) {
                    data.push({
                        "tahun_akademik": val.tahun_akademik,
                        "ipk": parseFloat(val.ipk),
                        "ips": parseFloat(val.ips),
                        "sks_smt": val.sks_smt
                    })
                });
                self.new_chart("chartdiv", data)
            },
            complete: function () {
                $("#loading").hide();
            }
        });
    },
    new_chart: function (id, data) {
        var root = am5.Root.new(id);
        root.setThemes([
            am5themes_Animated.new(root)
        ]);
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
            panX: true,
            panY: false,
            wheelX: "panX",
            wheelY: "zoomX",
            layout: root.verticalLayout
        }));
        chart.set("scrollbarX", am5.Scrollbar.new(root, {
            orientation: "horizontal"
        }));
        var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
            categoryField: "tahun_akademik",
            renderer: am5xy.AxisRendererX.new(root, {

            }),
            tooltip: am5.Tooltip.new(root, {
                themeTags: ["axis"],
                animationDuration: 200
            })
        }));
        xAxis.data.setAll(data);
        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            min: 0,
            renderer: am5xy.AxisRendererY.new(root, {})
        }));
        var series0 = chart.series.push(am5xy.ColumnSeries.new(root, {
            name: "IP",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "ips",
            categoryXField: "tahun_akademik",
            clustered: false,
            tooltip: am5.Tooltip.new(root, {
                labelText: "IP: {valueY}"
            })
        }));
        series0.columns.template.setAll({
            width: am5.percent(80),
            tooltipY: 0
        });
        series0.data.setAll(data);

        var series1 = chart.series.push(am5xy.ColumnSeries.new(root, {
            name: "IPK",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "ipk",
            categoryXField: "tahun_akademik",
            clustered: false,
            tooltip: am5.Tooltip.new(root, {
                labelText: "IPK: {valueY}"
            })
        }));
        series1.columns.template.setAll({
            width: am5.percent(50),
            tooltipY: 0
        });
        series1.data.setAll(data);

        let legend = chart.children.unshift(am5.Legend.new(root, {
            x: am5.percent(50),
            centerX: am5.percent(50),
            layout: root.horizontalLayout
        }));
        legend.data.setAll(chart.series.values);

        chart.set("cursor", am5xy.XYCursor.new(root, {}));
        chart.appear(1000, 100);
        series0.appear();
        series1.appear();
    }
};

jQuery(document).ready(function () {
    jQuery.dashboard.init();
});
