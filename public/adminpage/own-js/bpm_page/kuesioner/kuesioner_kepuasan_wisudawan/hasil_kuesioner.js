jQuery.hasil_kuesioner = {
    data: {
        sub_unsur_area: $("#sub-unsur-area"),
    },
    init: function () {
        var self = this;
        self.setEvents();
    },
    setEvents: function () {
        var self = this;
        $(".select2").select2();
        self.reload_unsur();
        $("#id_unsur").change(function () {
            self.reload_unsur();
        });
        $("#btn-kelola-kuesioner").click(function () {
            location.href = "/bpm/kuesioner/kuesioner-kepuasan-wisudawan/export/" + $("#id_jadwal").val()
        });
        $("#id_jadwal").change(function () {
            self.reload_unsur();
        });
    },
    reload_unsur: function () {
        var self = this;
        $("#sub-unsur-area").html("");
        $.ajax({
            url: "/bpm/kuesioner/kuesioner-kepuasan-wisudawan/json",
            method: 'post',
            data: {
                id_unsur: $("#id_unsur").val(),
                id_jadwal: $("#id_jadwal").val(),
            },
            beforeSend: function () {
                $("#loading").show();
            },
            success: function (response) {
                $.each(response, function (index, val) {
                    self.add_sub_unsur(val.id_sub_unsur_kuesioner_kepuasan_wisudawan, index + 1, val.pernyataan);
                    var data = [
                        {
                            name: "Sangat Tidak Baik",
                            value: val.sangat_tidak_baik,
                            bulletSettings: {src: "/image/satisfaction/1.png"}
                        },
                        {
                            name: "Tidak Baik",
                            value: val.tidak_baik,
                            bulletSettings: {src: "/image/satisfaction/2.png"}
                        },
                        {
                            name: "Cukup Baik",
                            value: val.cukup_baik,
                            bulletSettings: {src: "/image/satisfaction/3.png"}
                        },
                        {
                            name: "Baik",
                            value: val.baik,
                            bulletSettings: {src: "/image/satisfaction/4.png"}
                        },
                        {
                            name: "Sangat Baik",
                            value: val.sangat_baik,
                            bulletSettings: {src: "/image/satisfaction/5.png"}
                        }
                    ];
                    self.new_chart(val.id_sub_unsur_kuesioner_kepuasan_wisudawan, data);
                });
            },
            complete: function () {
                $("#loading").hide();
            }
        });
    },
    add_sub_unsur: function (id, nomor, pernyataan) {
        var self = this;
        const div = document.createElement('div');
        div.className = "mb-2";
        div.innerHTML = "<p><b>" + nomor + ". " + pernyataan + "</b></p>" +
            "<div id='" + id + "' class='chartdiv'></div>";
        document.getElementById('sub-unsur-area').appendChild(div);
    },
    new_chart: function (id, data) {
        var root = am5.Root.new(id);
        root.setThemes([
            am5themes_Animated.new(root)
        ]);
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
            panX: false,
            panY: false,
            wheelX: "none",
            wheelY: "none"
        }));
        var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
        cursor.lineY.set("visible", false);
        var xRenderer = am5xy.AxisRendererX.new(root, {minGridDistance: 30});
        var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
            maxDeviation: 0,
            categoryField: "name",
            renderer: xRenderer,
            tooltip: am5.Tooltip.new(root, {})
        }));
        xRenderer.grid.template.set("visible", false);
        var yRenderer = am5xy.AxisRendererY.new(root, {});
        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            maxDeviation: 0,
            min: 0,
            extraMax: 0.1,
            renderer: yRenderer
        }));
        yRenderer.grid.template.setAll({
            strokeDasharray: [2, 2]
        });
        var series = chart.series.push(am5xy.ColumnSeries.new(root, {
            name: "Series 1",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            sequencedInterpolation: true,
            categoryXField: "name",
            tooltip: am5.Tooltip.new(root, {dy: -25, labelText: "{valueY}"})
        }));
        series.columns.template.setAll({
            cornerRadiusTL: 5,
            cornerRadiusTR: 5
        });
        series.columns.template.adapters.add("fill", (fill, target) => {
            return chart.get("colors").getIndex(series.columns.indexOf(target));
        });
        series.columns.template.adapters.add("stroke", (stroke, target) => {
            return chart.get("colors").getIndex(series.columns.indexOf(target));
        });
        series.bullets.push(function () {
            return am5.Bullet.new(root, {
                locationY: 1,
                sprite: am5.Picture.new(root, {
                    templateField: "bulletSettings",
                    width: 50,
                    height: 50,
                    centerX: am5.p50,
                    centerY: am5.p50,
                    shadowColor: am5.color(0x000000),
                    shadowBlur: 4,
                    shadowOffsetX: 4,
                    shadowOffsetY: 4,
                    shadowOpacity: 0.6
                })
            });
        });
        xAxis.data.setAll(data);
        series.data.setAll(data);
        series.appear(1000);
        chart.appear(1000, 100);
    }
};

jQuery(document).ready(function () {
    jQuery.hasil_kuesioner.init();
});
