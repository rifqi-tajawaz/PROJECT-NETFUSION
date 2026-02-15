$(function() {
	"use strict";

    // Helper functions
    const renderSparkline = (selector, data, color, type = 'area', height = 50, width = 150) => {
        const options = {
            series: [{ name: "Desktops", data: data }],
            chart: {
                height: height,
                type: type,
                sparkline: { enabled: true },
                zoom: { enabled: false }
            },
            dataLabels: { enabled: false },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    gradientToColors: [color],
                    shadeIntensity: 1,
                    type: 'vertical',
                    opacityFrom: 0.8,
                    opacityTo: 0.1,
                    stops: [0, 100, 100, 100]
                },
            },
            colors: [color],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            }
        };

        if (width) {
            options.chart.width = width;
        }

        // Default stroke for sparklines
        options.stroke = { width: 2, curve: 'smooth' };

        // Special case for Chart 11 (Bar with plotOptions)
        if (selector === '#chart11') {
             options.plotOptions = {
                bar: { columnWidth: "45%", endingShape: "rounded" }
            };
            delete options.stroke; // Chart 11 had stroke commented out
        }

        new ApexCharts(document.querySelector(selector), options).render();
    };

    const renderBarChart = (selector, data, color, height = 200) => {
         const options = {
            series: [{ name: "Desktops", data: data }],
            chart: {
                foreColor: "#eee",
                height: height,
                type: 'bar',
                toolbar: { show: false },
                sparkline: { enabled: false },
                zoom: { enabled: false }
            },
            dataLabels: { enabled: false },
            stroke: { width: 2, curve: 'smooth' },
            plotOptions: {
                bar: { columnWidth: "45%", endingShape: "rounded" }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    gradientToColors: ['#fff'],
                    shadeIntensity: 1,
                    type: 'vertical',
                    opacityFrom: 0.8,
                    opacityTo: 0.1,
                    stops: [0, 100, 100, 100]
                },
            },
            colors: [color],
            grid: { show: true, borderColor: 'rgba(255, 255, 255, 0.15)' },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            }
        };
        new ApexCharts(document.querySelector(selector), options).render();
    };

    const renderDonut = (selector, series, colors, height = 237, size = '80%', responsiveHeight = null) => {
        const respHeight = responsiveHeight !== null ? responsiveHeight : (height - 37);
        const options = {
            series: series,
            chart: { height: height, type: 'donut' },
            legend: { position: 'bottom', show: false },
            colors: colors,
            dataLabels: { enabled: false },
            plotOptions: { pie: { donut: { size: size } } },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: { height: respHeight },
                    legend: { position: 'bottom', show: true }
                }
            }]
        };

        if (selector === '#chart13') {
            options.responsive[0].options.legend.show = false;
        }

        new ApexCharts(document.querySelector(selector), options).render();
    };

    const renderRadial = (selector, value, color, label, gradientToColor, height = 310, startAngle = -135, endAngle = 225) => {
         const options = {
            series: [value],
            chart: { height: height, type: 'radialBar', toolbar: { show: false } },
            plotOptions: {
                radialBar: {
                    startAngle: startAngle,
                    endAngle: endAngle,
                    hollow: {
                        margin: 0,
                        size: '80%',
                        background: 'transparent',
                        image: undefined,
                        imageOffsetX: 0,
                        imageOffsetY: 0,
                        position: 'front',
                        dropShadow: { enabled: false, top: 3, left: 0, blur: 4, opacity: 0.24 }
                    },
                    track: {
                        background: 'rgba(0, 0, 0, 0.1)',
                        strokeWidth: '67%',
                        margin: 0,
                        dropShadow: { enabled: false, top: -3, left: 0, blur: 4, opacity: 0.35 }
                    },
                    dataLabels: {
                        show: true,
                        name: { offsetY: -10, show: true, color: '#888', fontSize: '17px' },
                        value: {
                            formatter: function(val) { return parseInt(val); },
                            color: '#111',
                            fontSize: '36px',
                            show: true,
                        }
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'horizontal',
                    shadeIntensity: 0.5,
                    gradientToColors: [gradientToColor],
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                }
            },
            colors: [color],
            stroke: { lineCap: 'round' },
            labels: [label],
        };

        new ApexCharts(document.querySelector(selector), options).render();
    };

    const renderAreaChart = (selector, data, color, height = 200) => {
         const options = {
            series: [{ name: "Desktops", data: data }],
            chart: {
                foreColor: "#eee",
                height: height,
                type: 'area',
                toolbar: { show: false },
                sparkline: { enabled: false },
                zoom: { enabled: false }
            },
            dataLabels: { enabled: false },
            stroke: { width: 2, curve: 'smooth' },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    gradientToColors: ['#fff'],
                    shadeIntensity: 1,
                    type: 'vertical',
                    opacityFrom: 0.8,
                    opacityTo: 0.1,
                    stops: [0, 100, 100, 100]
                },
            },
            colors: [color],
            grid: { show: true, borderColor: 'rgba(255, 255, 255, 0.15)' },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            }
        };
        new ApexCharts(document.querySelector(selector), options).render();
    };

    // --- Chart Initializations ---

    // chart 1 (Area, Sparkline)
    renderSparkline("#chart1", [4, 41, 35, 51, 25, 8], "#0d6efd", 'area', 50);

    // chart 2 (Bar, Sparkline)
    renderSparkline("#chart2", [10, 41, 30, 51, 25, 15], "#fc185a", 'bar', 50);

    // chart 3 (Area, Sparkline)
    renderSparkline("#chart3", [4, 41, 35, 51, 25, 8], "#02c27a", 'area', 50);

    // chart 4 (Bar, Sparkline)
    renderSparkline("#chart4", [14, 41, 35, 51, 25, 18], "#fd7e14", 'bar', 50);

    // chart 5 (Bar, Standard)
    renderBarChart("#chart5", [14, 41, 35, 51, 25, 18, 21, 35, 15], "#fff");

    // chart 6 (Donut)
    renderDonut("#chart6", [44, 55, 41], ["#fff", "rgba(255, 255, 255, 0.70)", "rgba(255, 255, 255, 0.20)"], 237, '80%');

    // chart 7 (Area, Standard)
    renderAreaChart("#chart7", [14, 41, 35, 51, 25, 40, 21, 35, 15], "#fff");

    // chart 8 (Area, Sparkline, Height 85)
    renderSparkline("#chart8", [20, 50, 25, 65, 22, 45], "#6f42c1", 'area', 85, null);

    // chart 9 (Area, Sparkline, Height 85)
    renderSparkline("#chart9", [24, 41, 35, 51, 25, 15], "#ffc107", 'area', 85, null);

    // chart 10 (RadialBar)
    renderRadial("#chart10", 75, "#fd7e14", "Total Leads", "#fc185a", 310, -135, 225);

    // chart 11 (Bar, Sparkline-ish, Height 210, Full Width)
    renderSparkline("#chart11", [20, 41, 30, 51, 25, 60, 35, 54, 26, 18, 22, 43], "#02c27a", 'bar', 210, null);

    // chart 12 (RadialBar)
    renderRadial("#chart12", 85, "#fc185a", "Total Orders", "#0866ff", 300, -90, 90);

    // chart 13 (Donut)
    renderDonut("#chart13", [270, 55, 41, 35], ["#0d6efd", "#fc185a", "#02c27a", "#fd7e14"], 275, '85%', 270);

});
