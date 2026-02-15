$(function() {
    "use strict";

    // Helper function to create chart options
    function createChartOptions(series, type, colors, height, extraOptions) {
        var options = {
            series: series,
            chart: {
                foreColor: "#9ba7b2",
                height: height,
                type: type,
                zoom: { enabled: false },
                toolbar: { show: false }
            },
            dataLabels: { enabled: false },
            stroke: { width: 4, curve: 'smooth' },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    gradientToColors: [], // Overridden by extraOptions
                    shadeIntensity: 1,
                    type: 'vertical',
                    opacityFrom: 0.8,
                    opacityTo: 0.1,
                    stops: [0, 100, 100, 100]
                }
            },
            colors: colors,
            grid: {
                show: true,
                borderColor: 'rgba(0, 0, 0, 0.15)',
                strokeDashArray: 4,
            },
            tooltip: { theme: "dark" },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            },
            markers: { show: false, size: 5 },
        };

        if (extraOptions) {
            $.extend(true, options, extraOptions);
        }

        return options;
    }

    // Chart 1: Total Sales
    var options1 = createChartOptions(
        [{
            name: "Total Sales",
            data: [25, 66, 41, 59, 25, 44, 12, 36, 9, 21]
        }],
        'area',
        ["#ffd200"],
        350,
        {
            fill: { gradient: { gradientToColors: ['#ff0080'] } }
        }
    );
    var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
    chart1.render();

    // Chart 2: Desktops (Example of reuse)
    var options2 = createChartOptions(
        [{
            name: "Desktops",
            data: [4, 10, 25, 12, 25, 18, 40, 22, 7]
        }],
        'area',
        ["#008ffb"],
        350,
        {
            fill: { gradient: { gradientToColors: ['#00e396'] } }
        }
    );
    var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
    chart2.render();

});
