document.addEventListener('DOMContentLoaded', function () {
    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Chart
    var chartElement = document.querySelector("#chart1");
    if (chartElement) {
        var chartData = JSON.parse(chartElement.dataset.chartData || '[]');
        var chartLabels = JSON.parse(chartElement.dataset.chartLabels || '[]');

        var options = {
            series: [{
                name: "Events",
                data: chartData
            }],
            chart: {
                type: 'area',
                height: 90,
                toolbar: { show: false },
                sparkline: { enabled: true }
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.5,
                    opacityTo: 0,
                    stops: [0, 90, 100]
                }
            },
            colors: ["#0d6efd"],
            labels: chartLabels,
            tooltip: {
                theme: 'dark',
                fixed: { enabled: false },
                x: { show: true },
                marker: { show: false }
            }
        };

        var chart = new ApexCharts(chartElement, options);
        chart.render();
    }
});
